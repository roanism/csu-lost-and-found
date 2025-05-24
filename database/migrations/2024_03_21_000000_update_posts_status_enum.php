<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE posts MODIFY COLUMN status ENUM('open', 'pending_claim', 'pending_return', 'claimed', 'returned') DEFAULT 'open'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE posts MODIFY COLUMN status ENUM('open', 'claimed', 'returned') DEFAULT 'open'");
    }
}; 