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
        Schema::table('tickets', function (Blueprint $table) {
            // Change status to enum
            $table->enum('status', ['open', 'closed', 'pending'])->default('open')->change();
            $table->boolean('category_manual')->after('status')->default(false);

            // Add new columns
            $table->text('note')->nullable()->after('category_manual');
            $table->text('explanation')->nullable()->after('confidence');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Rollback changes
            $table->string('status')->change(); // assuming original was string
            $table->dropColumn(['note', 'explanation', 'category_manual']);
        });
    }
};
