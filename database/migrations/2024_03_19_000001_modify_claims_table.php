<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('claims', function (Blueprint $table) {
            // Drop old columns
            $table->dropColumn(['claimer_contact', 'claim_type', 'notes']);
            
            // Add new columns
            $table->string('claimer_email')->after('claimer_name');
            $table->string('claimer_phone')->after('claimer_email');
            $table->text('claim_reason')->after('claimer_phone');
        });
    }

    public function down()
    {
        Schema::table('claims', function (Blueprint $table) {
            // Drop new columns
            $table->dropColumn(['claimer_email', 'claimer_phone', 'claim_reason']);
            
            // Add back old columns
            $table->string('claimer_contact')->after('claimer_name');
            $table->string('claim_type')->after('claimer_contact');
            $table->text('notes')->nullable()->after('claim_type');
        });
    }
}; 