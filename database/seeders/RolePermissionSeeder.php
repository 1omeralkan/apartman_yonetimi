<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Yetkileri oluştur
        $permissions = [
            // Kullanıcı yönetimi
            'user.create',
            'user.read',
            'user.update',
            'user.delete',
            
            // Site yönetimi (Site bazlı kullanım için)
            'site.create',
            'site.read',
            'site.update',
            'site.delete',
            
            // Blok yönetimi (Site bazlı kullanım için)
            'block.create',
            'block.read',
            'block.update',
            'block.delete',
            
            // Apartman yönetimi
            'apartment.create',
            'apartment.read',
            'apartment.update',
            'apartment.delete',
            
            // Kat yönetimi
            'floor.create',
            'floor.read',
            'floor.update',
            'floor.delete',
            
            // Daire yönetimi
            'flat.create',
            'flat.read',
            'flat.update',
            'flat.delete',
            
            // Aidat yönetimi
            'dues.create',
            'dues.read',
            'dues.update',
            'dues.delete',
            
            // Duyuru yönetimi
            'announcement.create',
            'announcement.read',
            'announcement.update',
            'announcement.delete',
            
            // Şikayet yönetimi
            'complaint.create',
            'complaint.read',
            'complaint.update',
            'complaint.delete',
            
            // Rapor yönetimi
            'report.read',
            'report.export',
            
            // Sistem ayarları
            'system.settings',
            'system.backup',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Rolleri oluştur ve yetkileri ata
        $superAdmin = Role::create(['name' => 'super_admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // Site bazlı admin (Site → Blok → Apartman → Kat → Daire)
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo([
            'user.create', 'user.read', 'user.update',
            'site.create', 'site.read', 'site.update', 'site.delete',
            'block.create', 'block.read', 'block.update', 'block.delete',
            'apartment.create', 'apartment.read', 'apartment.update', 'apartment.delete',
            'floor.create', 'floor.read', 'floor.update', 'floor.delete',
            'flat.create', 'flat.read', 'flat.update', 'flat.delete',
            'dues.create', 'dues.read', 'dues.update', 'dues.delete',
            'announcement.create', 'announcement.read', 'announcement.update', 'announcement.delete',
            'complaint.create', 'complaint.read', 'complaint.update', 'complaint.delete',
            'report.read', 'report.export',
        ]);

        // Apartman bazlı yönetici (Apartman → Kat → Daire)
        $apartmentManager = Role::create(['name' => 'apartment_manager']);
        $apartmentManager->givePermissionTo([
            'user.read', 'user.update',
            'apartment.read', 'apartment.update',
            'floor.create', 'floor.read', 'floor.update', 'floor.delete',
            'flat.create', 'flat.read', 'flat.update', 'flat.delete',
            'dues.create', 'dues.read', 'dues.update',
            'announcement.create', 'announcement.read', 'announcement.update', 'announcement.delete',
            'complaint.create', 'complaint.read', 'complaint.update',
            'report.read',
        ]);

        // Sakin (Sadece kendi daire bilgileri)
        $resident = Role::create(['name' => 'resident']);
        $resident->givePermissionTo([
            'user.read', 'user.update',
            'apartment.read',
            'floor.read',
            'flat.read',
            'dues.read',
            'announcement.read',
            'complaint.create', 'complaint.read',
        ]);
    }
}