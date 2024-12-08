<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('authors', function (Blueprint $table) {
            $table->bigIncrements('id'); // Primary key (auto-increment)
            $table->string('author_uid', 50)->unique(); // Unique string identifier
            $table->string('name', 255); // Author name
            $table->string('image_url', 2083)->nullable(); // Image URL for profile picture
            $table->string('designation', 100)->nullable(); // Designation (like "Writer" or "Editor")
            $table->text('description')->nullable(); // Short biography of the author
            $table->json('social_accounts')->nullable(); // JSON array of social media accounts (name + URL)
            $table->json('course_links')->nullable(); // JSON array of courses (name + URL)
            $table->timestamps(); // created_at and updated_at timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('authors');
    }
};
