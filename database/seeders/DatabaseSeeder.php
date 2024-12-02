<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call(TeachersTableSeeder::class);
        $this->call(ShiftsTableSeeder::class);
        $this->call(BarcodeScansTableSeeder::class);
        $this->call(AttendancesTableSeeder::class);
    }
}

class TeachersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('roles')->insert([
            'name' => 'Admin',
            'slug' => 'admin',

        ]);

        DB::table('users')->insert([
            'name' => 'vidi',
            'nip' => '123456789',
            'username' => 'vidi',
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('123456789'), // password
            'gender' => 'male',
            'role_id' => '1',
            'active' => 1,
            'remember_token' => Str::random(10)
        ]);
    }
}

class ShiftsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('shifts')->truncate();

        $shifts = [
            ['shift_name' => 'Senin', 'day' => 'Senin', 'start_time' => '08:00', 'end_time' => '16:00'],
            ['shift_name' => 'Selasa', 'day' => 'Selasa', 'start_time' => '08:00', 'end_time' => '16:00'],
            ['shift_name' => 'Rabu', 'day' => 'Rabu', 'start_time' => '08:00', 'end_time' => '16:00'],
            ['shift_name' => 'Kamis', 'day' => 'Kamis', 'start_time' => '08:00', 'end_time' => '16:00'],
            ['shift_name' => 'Jumat', 'day' => 'Jumat', 'start_time' => '08:00', 'end_time' => '16:00'],
            ['shift_name' => 'Sabtu', 'day' => 'Sabtu', 'start_time' => '08:00', 'end_time' => '12:00'],
        ];

        DB::table('shifts')->insert($shifts);
    }
}

class BarcodeScansTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('barcode_scans')->insert([
            'barcode' => '123456',
            'scan_timestamp' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}

class AttendancesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('attendances')->insert([
            'user_id' => 1,
            'hour_came' => Carbon::now(),
            'home_time' => Carbon::now()->addHours(8),
            'barcode_hour_came' => '123456',
            'overtime_hours' => 2.5,
            'status' => 'Hadir',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
