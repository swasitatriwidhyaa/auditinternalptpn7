<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\AuditStandard;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Standar Audit (ISO, SMAP, dll)
        $standards = [
            ['kode' => 'ISO 9001:2015', 'nama' => 'Sistem Manajemen Mutu'],
            ['kode' => 'ISO 14001:2015', 'nama' => 'Sistem Manajemen Lingkungan'],
            ['kode' => 'SMAP ISO 37001', 'nama' => 'Sistem Manajemen Anti Penyuapan'],
            ['kode' => 'SMK3', 'nama' => 'Sistem Manajemen K3 PP 50/2012'],
            ['kode' => 'HALAL', 'nama' => 'Sistem Jaminan Halal'],
            ['kode' => 'SNI', 'nama' => 'Standar Nasional Indonesia'],
            ['kode' => 'IMS', 'nama' => 'Integrated Management System (IMS)'],
        ];

        foreach ($standards as $s) {
            AuditStandard::create($s);
        }

        // 2. Buat 1 Akun AUDITOR (SPI - Satuan Pengawas Internal)
        User::create([
            'name' => 'Lead Auditor',
            'email' => 'auditor@ptpn7.com',
            'password' => Hash::make('12345678'),
            'role' => 'auditor',
            'unit_kerja' => 'Kantor Direksi / SPI'
        ]);

        // 3. Buat 15 Akun AUDITEE (Unit Kerja yang berbeda-beda)
        $daftarUnit = [
            'Unit Kedaton',
            'Unit Bekri',
            'Unit Way Berulu',
            'Unit Way Lima',
            'Unit Bergen',
            'Unit Tulung Buyut',
            'Unit Bunga Mayang',
            'Unit Cinta Manis',
            'Unit Pematang Kiwah',
            'Unit Musi Landas',
            'Unit Betung',
            'Unit Padang Ratu',
            'Unit Senabing',
            'Unit Talo Pino',
            'Unit Ketahun'
        ];

        foreach ($daftarUnit as $index => $namaUnit) {
            $no = $index + 1;
            User::create([
                'name' => 'Admin ' . $namaUnit,
                'email' => "unit{$no}@ptpn7.com", // Email jadi unit1@ptpn7.com s/d unit15@ptpn7.com
                'password' => Hash::make('12345678'),
                'role' => 'auditee',
                'unit_kerja' => $namaUnit
            ]);
        }
    }
}