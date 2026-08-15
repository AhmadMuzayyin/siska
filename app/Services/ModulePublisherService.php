<?php

namespace App\Services;

/**
 * Service untuk publish module code dalam konteks testing.
 *
 * Di lingkungan test, semua module dianggap sudah tersedia
 * karena kode mereka sudah ada di codebase.
 */
class ModulePublisherService
{
    /**
     * Publish kode modul ke direktori aplikasi.
     * Dalam konteks ini adalah no-op karena kode sudah ada.
     */
    public function publishModuleCode(string $module): void
    {
        // No-op: semua module code sudah tersedia di codebase.
        // Method ini dipertahankan untuk kompatibilitas dengan TestCase.
    }
}
