<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->nullable();
            $table->string('nip')->unique()->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('photo')->nullable();
            $table->string('api_token')->nullable();
            $table->string('gender');
            $table->unsignedBigInteger('role_id');
            $table->foreign('role_id')->references('id')->on('roles');
            $table->integer('active');
            $table->softDeletes();
            $table->rememberToken();
            $table->timestamps();
        });



        // Tabel shifts
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->string('shift_name');
            $table->string('day')->nullable();
            $table->string('start_time');
            $table->string('end_time');
            $table->timestamps();
        });

        // Tabel attendances
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('barcode')->nullable();
            $table->timestamp('hour_came')->nullable();
            $table->timestamp('home_time')->nullable();
            $table->decimal('overtime_hours', 5, 2)->nullable();
            $table->enum('status', ['Hadir', 'Sakit', 'Alpa', 'Izin'])->default('Hadir');
            $table->timestamps();
        });

        // Tabel barcode_scans
        Schema::create('barcode_scans', function (Blueprint $table) {
            $table->string('barcode')->primary();
            $table->string('status')->nullable();
            $table->timestamp('scan_timestamp');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('shifts');
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('barcode_scans');
    }
};
