<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

/**
 * Makes `reference` the only table for per subject files.
 *
 * The table has always been called `reference`; `create_materials_table` recorded
 * itself as ran long before that name was settled, so a database can be found in
 * either of two states:
 *
 *   - only `reference` exists, which is the healthy state and a no-op here
 *   - a stray `materials` table sits alongside it, left by a migration that
 *     replayed create_materials_table against a table name it should never have
 *     used; this folds its rows into `reference` and drops it
 *
 * Rows are copied rather than renamed so the two shapes can differ: the stray
 * table predates the `remark` column, so it is copied by column name and only
 * the columns `reference` actually has. A pair that already exists in
 * `reference` wins and the stray duplicate is skipped, which is the only case
 * where data is not carried over.
 *
 * The link columns store `materials/...` paths, so the uploaded files move with
 * the rows and every path is rewritten to match.
 */
return new class extends Migration
{
    /** order matters: the pair unique index has to be checked before the insert */
    private const COLUMNS = [
        'id', 'course_id', 'subject_id', 'book_link', 'video_link', 'zip_link', 'remark', 'created_at', 'updated_at',
    ];

    public function up(): void
    {
        if (! Schema::hasTable('materials')) {
            return;
        }

        if (! Schema::hasTable('reference')) {
            Schema::rename('materials', 'reference');

            return;
        }

        $this->moveRows();
        $this->moveStoredFiles();
        $this->rewritePaths();

        Schema::drop('materials');
    }

    public function down(): void
    {
        // Renaming back is not possible without the stray table's original shape,
        // and there is nothing to undo: reference is the intended single table.
    }

    private function moveRows(): void
    {
        $target = Schema::getColumnListing('reference');
        $source = Schema::getColumnListing('materials');

        $columns = array_values(array_intersect(self::COLUMNS, $target, $source));

        foreach (DB::table('materials')->orderBy('id')->get() as $row) {
            $values = [];

            foreach ($columns as $column) {
                $values[$column] = $row->{$column};
            }

            // a pair that already exists in reference keeps its own files
            $clash = DB::table('reference')
                ->where('course_id', $values['course_id'])
                ->where('subject_id', $values['subject_id'])
                ->exists();

            if (! $clash) {
                DB::table('reference')->insert($values);
            }
        }
    }

    private function moveStoredFiles(): void
    {
        $disk = Storage::disk('public');

        if (! $disk->exists('materials')) {
            return;
        }

        foreach ($disk->files('materials') as $file) {
            $name = basename($file);

            if ($disk->exists('reference/' . $name)) {
                $disk->delete($file);

                continue;
            }

            $disk->move($file, 'reference/' . $name);
        }

        $disk->deleteDirectory('materials');
    }

    private function rewritePaths(): void
    {
        foreach (['book_link', 'video_link', 'zip_link'] as $column) {
            DB::table('reference')
                ->where($column, 'like', 'materials/%')
                ->update([
                    $column => DB::raw("REPLACE({$column}, 'materials/', 'reference/')"),
                ]);
        }
    }
};
