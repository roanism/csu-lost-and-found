<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['lost', 'found']);
            $table->string('item_name');
            $table->text('description');
            $table->string('category')->nullable();
            $table->string('location')->nullable();
            $table->string('contact_name');
            $table->string('contact_info');
            $table->string('image_path')->nullable();
            $table->string('reference_number')->unique();
            $table->enum('status', ['open', 'pending_claim', 'pending_return', 'claimed', 'returned'])->default('open');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('posts');
    }
}; 