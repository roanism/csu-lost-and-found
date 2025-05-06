<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClaimsTable extends Migration
{
    public function up()
    {
        Schema::create('claims', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('post_id');
            $table->string('claimer_name');
            $table->string('claimer_contact');
            $table->enum('claim_type', ['claimed', 'returned']);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('claims');
    }
}