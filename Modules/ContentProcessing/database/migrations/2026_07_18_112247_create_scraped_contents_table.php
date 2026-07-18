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
        Schema::create('scraped_contents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->mediumText('content');
            $table->string('author')->nullable();
            $table->string('categories')->nullable();
            $table->string('tags')->nullable();
            $table->enum('status', ['successful', 'failed'])->default('successful');
            $table->string('domain');
            $table->string('url');
            $table->date('published_at')->nullable();
            $table->string('content_hash', 64)->unique();
            $table->string('errors')->nullable();
            $table->timestamps();

            $table->index('domain');
            $table->index('status');
            $table->index('content_hash');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scraped_contents');
    }
};
