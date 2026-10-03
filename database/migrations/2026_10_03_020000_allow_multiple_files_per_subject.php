<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * One file per reference row, so a subject can hold any number of materials.
 *
 * The old shape was one row per course + subject with a `book_link`, a
 * `video_link` and a `zip_link` column, kept unique by
 * reference(course_id, subject_id). That allowed exactly one book, one recording
 * and one archive per subject, and it is why the admin form rejected a subject
 * that already had a row.
 *
 * A subject can legitimately hold several books, several recordings of the same
 * lecture and several exercise archives, so the row is now keyed by the file:
 * `type` says which kind it is, `file_link` is the one path, and `title` is a
 * display name that falls back to the stored filename. The pair index becomes a
 * plain index because many rows share a pair.
 *
 * Every existing file is carried over as its own row before the old shells are
 * removed, so nothing already uploaded is lost. `title` is left null on the way
 * in, which keeps the uploaded filename as the label until an admin sets one.
 *
 * Every step checks the state it is about to change, so a run that stops part
 * way through can simply be run again.
 */
return new class extends Migration
{
    /** the old shape: file kind => the column that held its path */
    private const LINK_COLUMNS = ['book' => 'book_link', 'video' => 'video_link', 'zip' => 'zip_link'];

    /**
     * The pair index can carry either name: create_materials_table was recorded
     * as ran while the table was still called `materials`, and MySQL keeps the
     * index name it was created under even after the table is renamed.
     */
    private const PAIR_INDEXES = [
        'reference_course_id_subject_id_unique',
        'materials_course_id_subject_id_unique',
    ];

    public function up(): void
    {
        if (! Schema::hasTable('reference')) {
            return;
        }

        $this->addFileColumns();

        // the pair index has to go before the split, or the rows it is about to
        // gain would collide with each other; its replacement goes in first,
        // because course_id's foreign key is served by the index being dropped
        // and InnoDB refuses to drop the last one a constraint has
        $this->addIndexes();
        $this->dropPairIndex();

        $this->splitRowsIntoFiles();
        $this->dropColumns(array_values(self::LINK_COLUMNS));
    }

    public function down(): void
    {
        if (! Schema::hasTable('reference') || ! Schema::hasColumn('reference', 'file_link')) {
            return;
        }

        $this->addLinkColumns();
        $this->collapseFilesIntoRows();
        $this->dropIndex('reference_type_index');

        // the pair index has to be back before the composite one it is currently
        // serving can go: course_id's foreign key needs an index whose first
        // column it is, and InnoDB refuses to drop the last one it has
        $this->addPairUniqueIndex();
        $this->dropIndex('reference_course_id_subject_id_index');

        Schema::table('reference', fn (Blueprint $table) => $table->dropColumn(['type', 'title', 'file_link']));
    }

    private function addLinkColumns(): void
    {
        $missing = array_values(array_filter(
            array_values(self::LINK_COLUMNS),
            fn (string $column) => ! Schema::hasColumn('reference', $column)
        ));

        if ($missing === []) {
            return;
        }

        Schema::table('reference', function (Blueprint $table) use ($missing) {
            foreach ($missing as $column) {
                $table->string($column)->nullable();
            }
        });
    }

    private function addPairUniqueIndex(): void
    {
        if ($this->hasIndex(self::PAIR_INDEXES[0])) {
            return;
        }

        Schema::table('reference', fn (Blueprint $table) => $table->unique(
            ['course_id', 'subject_id'],
            self::PAIR_INDEXES[0]
        ));
    }

    private function addFileColumns(): void
    {
        if (Schema::hasColumn('reference', 'type')) {
            return;
        }

        Schema::table('reference', function (Blueprint $table) {
            // which kind of file this row holds
            $table->string('type', 20)->nullable()->after('subject_id');
            // display name, falling back to the stored filename when empty
            $table->string('title')->nullable()->after('type');
            // the one path on the `public` disk
            $table->string('file_link')->nullable()->after('title');
        });
    }

    /**
     * Give every stored file a row of its own, then drop the shells they were
     * lifted out of.
     */
    private function splitRowsIntoFiles(): void
    {
        // nothing left to lift: either the link columns are already gone, or
        // every file has a row of its own and only empty shells remain
        if (! $this->hasOldLinks()) {
            return;
        }

        $rows = DB::table('reference')->orderBy('id')->get();
        $now = now();
        $files = [];
        $shells = [];

        foreach ($rows as $row) {
            foreach (self::LINK_COLUMNS as $type => $column) {
                if (blank($row->{$column})) {
                    continue;
                }

                $files[] = [
                    'course_id' => $row->course_id,
                    'subject_id' => $row->subject_id,
                    'type' => $type,
                    'title' => null,
                    'file_link' => $row->{$column},
                    'remark' => $row->remark ?? null,
                    'created_at' => $row->created_at ?? $now,
                    'updated_at' => $row->updated_at ?? $now,
                ];
            }

            // the old row only existed to hold those columns
            $shells[] = $row->id;
        }

        // the files go in before the shells go out, so a failure in between
        // leaves the originals where they were
        foreach (array_chunk($files, 200) as $chunk) {
            DB::table('reference')->insert($chunk);
        }

        DB::table('reference')->whereIn('id', $shells)->delete();

        // a row with no file is a leftover from the old shape: nothing can open
        // it, and the columns that said it was worth keeping are about to go
        DB::table('reference')->whereNull('file_link')->delete();
    }

    /**
     * The way back: one row per course + subject again.
     *
     * The old shape has one column per kind, so only the first file of each kind
     * survives and the rest are dropped. That loss is inherent to rolling back
     * this migration, not a choice: there is nowhere else for them to go.
     */
    private function collapseFilesIntoRows(): void
    {
        // nothing to collapse when no row points at a file any more
        if (! DB::table('reference')->whereNotNull('file_link')->exists()) {
            return;
        }

        $files = DB::table('reference')->orderBy('id')->get()
            ->groupBy(fn ($file) => $file->course_id . '-' . $file->subject_id);

        DB::table('reference')->delete();

        $rows = [];

        foreach ($files as $group) {
            $values = [
                'course_id' => $group->first()->course_id,
                'subject_id' => $group->first()->subject_id,
                'book_link' => null,
                'video_link' => null,
                'zip_link' => null,
                'remark' => null,
                'created_at' => $group->first()->created_at,
                'updated_at' => $group->first()->updated_at,
            ];

            foreach ($group as $file) {
                $column = self::LINK_COLUMNS[$file->type] ?? null;

                if ($column && filled($file->file_link) && blank($values[$column])) {
                    $values[$column] = $file->file_link;
                }

                $values['remark'] = $values['remark'] ?? $file->remark;
            }

            $rows[] = $values;
        }

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('reference')->insert($chunk);
        }
    }

    private function addIndexes(): void
    {
        // the pair is no longer unique, but it is still the only way a page ever
        // asks for "the files of this subject in this course", and its leading
        // course_id keeps the foreign key indexed once the old pair index is gone
        if (! $this->hasIndex('reference_course_id_subject_id_index')) {
            Schema::table('reference', fn (Blueprint $table) => $table->index(
                ['course_id', 'subject_id'],
                'reference_course_id_subject_id_index'
            ));
        }

        if (! $this->hasIndex('reference_type_index')) {
            Schema::table('reference', fn (Blueprint $table) => $table->index('type', 'reference_type_index'));
        }
    }

    private function dropPairIndex(): void
    {
        foreach ($this->indexNames(self::PAIR_INDEXES) as $name) {
            $this->dropIndex($name);
        }
    }

    /**
     * Whether any row still has a file in one of the old link columns.
     */
    private function hasOldLinks(): bool
    {
        foreach (array_values(self::LINK_COLUMNS) as $column) {
            if (Schema::hasColumn('reference', $column)
                && DB::table('reference')->whereNotNull($column)->exists()) {
                return true;
            }
        }

        return false;
    }

    private function hasIndex(string $name): bool
    {
        return in_array($name, $this->indexNames([$name]), true);
    }

    /**
     * The indexes among the candidates that actually exist.
     *
     * A Laravel 10 schema builder cannot drop an index under a name it would not
     * have generated itself, and the pair index here was created while the table
     * was still called `materials`, so the lookup asks the server rather than
     * guessing.
     *
     * @param  array<int, string>  $candidates
     * @return array<int, string>
     */
    private function indexNames(array $candidates): array
    {
        if (DB::connection()->getDriverName() !== 'mysql') {
            return $candidates;
        }

        return DB::table('information_schema.statistics')
            ->where('TABLE_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', 'reference')
            ->whereIn('INDEX_NAME', $candidates)
            ->select('INDEX_NAME')
            ->distinct()
            ->pluck('INDEX_NAME')
            ->all();
    }

    private function dropIndex(string $name): void
    {
        if ($this->hasIndex($name)) {
            DB::statement('ALTER TABLE `reference` DROP INDEX `' . $name . '`');
        }
    }

    /**
     * @param  array<int, string>  $columns
     */
    private function dropColumns(array $columns): void
    {
        $columns = array_values(array_filter(
            $columns,
            fn (string $column) => Schema::hasColumn('reference', $column)
        ));

        if ($columns === []) {
            return;
        }

        Schema::table('reference', fn (Blueprint $table) => $table->dropColumn($columns));
    }
};