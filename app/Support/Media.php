<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

/**
 * Pembantu URL media: nilai yang sudah berupa URL penuh (http/https/ //) dipakai apa adanya
 * (file di-host online: CDN / R2 / Bunny / dsb), selain itu dianggap path di disk 'public'.
 * Nilai kosong -> null.
 */
class Media
{
    public static function url(?string $nilai): ?string
    {
        $nilai = trim((string) $nilai);
        if ($nilai === '') {
            return null;
        }
        if (preg_match('#^(https?:)?//#i', $nilai)) {
            return $nilai;
        }

        return Storage::disk('public')->url($nilai);
    }
}
