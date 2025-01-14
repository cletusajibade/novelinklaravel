<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConsultationPackagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('consultation_packages')->insert([
            [
                'package_name' => 'Permanent Residence',
                'amount' => '150',
                'currency' => 'USD',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'package_name' => 'Family Sponsorship',
                'amount' => '150',
                'currency' => 'USD',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'package_name' => 'Visitor Visas',
                'amount' => '150',
                'currency' => 'USD',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'package_name' => 'Study Permit',
                'amount' => '150',
                'currency' => 'USD',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'package_name' => 'Work Permit',
                'amount' => '150',
                'currency' => 'USD',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'package_name' => 'Citizenship',
                'amount' => '120',
                'currency' => 'USD',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'package_name' => 'Entrepreneur/Investor',
                'amount' => '400',
                'currency' => 'USD',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'package_name' => 'Refusals and GCMS Review ',
                'amount' => '200',
                'currency' => 'USD',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'package_name' => 'Others',
                'amount' => '150',
                'currency' => 'USD',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
