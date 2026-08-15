<?php

namespace App\Models;

use Database\Factories\SettingFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property bool $is_installed
 * @property bool $is_multi_lembaga
 * @property array|null $installed_modules
 * @property string $app_version
 * @property string $landing_theme
 * @property string|null $lembaga
 * @property string|null $nsm
 * @property string|null $alamat
 * @property string|null $google_maps_url
 * @property string|null $email
 * @property string|null $telepon
 * @property string|null $logo
 * @property string|null $favicon
 * @property string|null $meta_deskripsi
 * @property string|null $meta_keyword
 * @property int $payroll_cutoff_day
 * @property bool $fitur_pesan_whatsapp
 * @property string|null $pesan_whatsapp
 * @property string|null $api_key_whatsapp
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'is_installed', 'is_multi_lembaga', 'installed_modules', 'app_version', 'landing_theme',
    'lembaga', 'nsm', 'alamat', 'google_maps_url', 'email', 'telepon',
    'logo', 'favicon', 'meta_deskripsi', 'meta_keyword',
    'payroll_cutoff_day', 'fitur_pesan_whatsapp', 'pesan_whatsapp', 'api_key_whatsapp',
])]
#[Hidden(['api_key_whatsapp'])]
class Setting extends Model
{
    /** @use HasFactory<SettingFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_installed' => 'boolean',
            'is_multi_lembaga' => 'boolean',
            'installed_modules' => 'array',
            'payroll_cutoff_day' => 'integer',
            'fitur_pesan_whatsapp' => 'boolean',
            'api_key_whatsapp' => 'encrypted',
        ];
    }

    /**
     * Helper to check if a specific module bundle is installed.
     * Always returns true if installed_modules is not defined or in standard mode.
     */
    public function hasModule(string $module): bool
    {
        if (empty($this->installed_modules)) {
            return true;
        }

        return in_array($module, $this->installed_modules, true);
    }

    /**
     * Settings is semantically a singleton: only the first row may ever be created.
     */
    protected static function booted(): void
    {
        static::creating(function (self $setting): void {
            if (app()->isProduction() && static::query()->exists()) {
                throw new \RuntimeException('Only one Setting row may exist. Update the existing row instead.');
            }
        });
    }
}
