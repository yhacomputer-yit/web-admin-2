<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * How files uploaded to the `public` disk are named and served.
 *
 * Every upload in the portal is stored as `{uniqid}_{slug}.{ext}`: the uniqid
 * prefix is what stops two "notes.pdf" uploads from overwriting each other, and
 * the slugged original is what keeps the row recognisable instead of showing a
 * bare hash.
 *
 * The prefix is an implementation detail that only gets in the way in a list, so
 * it is dropped for display while the path on disk keeps it. Several models
 * store uploads this way and all of them need the same three answers -- the name
 * to show, the URL to link to, and whether the file is still there -- so the
 * answers live here rather than being written once per model.
 */
final class StoredFile
{
    /**
     * The name to show for a stored file, or null when there is no path.
     */
    public static function label(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        $name = basename($path);

        return preg_replace('/^[0-9a-f]{13}_/', '', $name) ?: $name;
    }

    /**
     * A ready URL, so no caller has to rebuild a storage path. Null when there
     * is no path to serve.
     */
    public static function url(?string $path): ?string
    {
        return filled($path) ? Storage::url($path) : null;
    }

    /**
     * Whether the file is still behind the path.
     *
     * A row can outlive its upload -- a file removed off disk, or a restore that
     * did not include storage -- so a list that shows a size has to be able to
     * say "missing" instead of rendering a broken link as if it were fine.
     *
     * Most uploads go on the public disk, so that is the default. An exam paper
     * is the exception -- it is kept private and streamed through a controller --
     * so the disk is a parameter rather than a constant here.
     */
    public static function exists(?string $path, string $disk = 'public'): bool
    {
        return filled($path) && Storage::disk($disk)->exists($path);
    }

    /**
     * Put an upload on the public disk under a name a list can still read.
     *
     * The uniqid prefix is what stops two "notes.pdf" uploads from overwriting
     * each other; the slugged original is what keeps the row recognisable.
     *
     * The extension is checked against $allowed rather than taken as given,
     * because a stored filename is served straight back to a browser and the mime
     * rule alone cannot stop a crafted original name from choosing an extension
     * the file is not. The original wins when it is allowed, then the guessed
     * one, and the aliases PHP reports for a jpeg come back as "jpeg" so a valid
     * upload never lands with a .bin name.
     *
     * @param  array<int, string>  $allowed  extensions without the dot
     */
    public static function store(UploadedFile $file, string $directory, array $allowed): string
    {
        $original = strtolower((string) $file->getClientOriginalExtension());
        $guessed = strtolower((string) $file->guessExtension());

        $extension = match (true) {
            in_array($original, $allowed, true) => $original,
            in_array($guessed, $allowed, true) => $guessed,
            in_array($guessed, ['pjpeg', 'jpe'], true) && in_array('jpeg', $allowed, true) => 'jpeg',
            default => 'bin',
        };

        $base = Str::slug(pathinfo((string) $file->getClientOriginalName(), PATHINFO_FILENAME)) ?: 'file';

        return $file->storeAs($directory, uniqid() . '_' . $base . '.' . $extension, 'public');
    }

    /**
     * Remove a stored file. Safe to call with a path that is already gone, which
     * is what makes it usable on a replace or a rollback.
     */
    public static function delete(?string $path): void
    {
        if (filled($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
