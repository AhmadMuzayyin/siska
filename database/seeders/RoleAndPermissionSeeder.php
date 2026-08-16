<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Create Granular Permissions
        $permissions = [
            // Master Data
            'view-master-data',
            'manage-tahun-akademik',
            'manage-kelas',
            'manage-mapel',
            'manage-guru',
            'manage-santri',

            // Akademik & Operasional
            'manage-jadwal',
            'manage-absensi-santri',
            'input-absensi-santri',
            'manage-absensi-guru',
            'input-absensi-guru',
            'manage-nilai',
            'input-nilai',
            'view-nilai',
            'manage-setting-rapor',
            'print-rapor',

            // Keuangan
            'manage-spp',
            'manage-haflatul-imtihan',
            'manage-tabungan',
            'manage-gaji-guru',
            'view-keuangan-santri',

            // Administrasi & Sistem Pusat
            'manage-konten-website',
            'manage-lembagas',
            'manage-users',
            'manage-whatsapp',
            'manage-settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        // 2. Create Roles and Assign Permissions
        $adminRole = Role::firstOrCreate(['name' => UserRole::Admin->value, 'guard_name' => 'web']);
        $adminRole->syncPermissions(Permission::all());

        $operatorRole = Role::firstOrCreate(['name' => UserRole::Operator->value, 'guard_name' => 'web']);
        $operatorRole->syncPermissions([
            'view-master-data',
            'manage-tahun-akademik',
            'manage-kelas',
            'manage-mapel',
            'manage-guru',
            'manage-santri',
            'manage-jadwal',
            'manage-absensi-santri',
            'input-absensi-santri',
            'manage-absensi-guru',
            'input-absensi-guru',
            'manage-nilai',
            'input-nilai',
            'view-nilai',
            'manage-setting-rapor',
            'print-rapor',
            'manage-spp',
            'manage-haflatul-imtihan',
            'manage-tabungan',
            'manage-gaji-guru',
        ]);

        $guruRole = Role::firstOrCreate(['name' => UserRole::Guru->value, 'guard_name' => 'web']);
        $guruRole->syncPermissions([
            'view-master-data',
            'manage-jadwal',
            'input-absensi-santri',
            'input-absensi-guru',
            'input-nilai',
            'view-nilai',
            'print-rapor',
            'manage-setting-rapor',
        ]);

        $keuanganRole = Role::firstOrCreate(['name' => UserRole::Keuangan->value, 'guard_name' => 'web']);
        $keuanganRole->syncPermissions([
            'view-master-data',
            'manage-spp',
            'manage-haflatul-imtihan',
            'manage-tabungan',
            'manage-gaji-guru',
        ]);

        $kepalaRole = Role::firstOrCreate(['name' => UserRole::KepalaMadrasah->value, 'guard_name' => 'web']);
        $kepalaRole->syncPermissions([
            'view-master-data',
            'manage-jadwal',
            'view-nilai',
            'print-rapor',
            'manage-absensi-santri',
            'manage-absensi-guru',
        ]);

        $santriRole = Role::firstOrCreate(['name' => UserRole::Santri->value, 'guard_name' => 'web']);
        $santriRole->syncPermissions([
            'view-nilai',
            'view-keuangan-santri',
        ]);

        // 3. Sync existing users in database to Spatie Roles
        User::query()->get()->each(function (User $user) {
            if ($user->role) {
                $roleName = $user->role instanceof UserRole ? $user->role->value : (string) $user->role;
                $user->syncRoles([$roleName]);
            }
        });
    }
}
