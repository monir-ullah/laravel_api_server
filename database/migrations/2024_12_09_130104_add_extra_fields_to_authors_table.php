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
        Schema::table('authors', function (Blueprint $table) {
            $table->text('extraInfo')->nullable(); // Extra info as a text column
            $table->string('address')->nullable(); // Address as a string
            $table->string('phone')->nullable(); // Phone as a string
            $table->string('website')->nullable(); // Website as a string
            $table->json('follow_us')->nullable(); // Follow Us as a JSON array
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('authors', function (Blueprint $table) {
            //
        });
    }
};
