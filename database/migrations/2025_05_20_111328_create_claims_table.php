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
        Schema::create('claims', function (Blueprint $table) {
           
            $table->id();
        $table->foreignId('objet_id')->constrained()->cascadeOnDelete();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->text('description');
        $table->string('proof_url')->nullable();
        $table->json('answers')->nullable();
        $table->string('status')->default('pending'); // pending, approved, rejected
        $table->text('admin_notes')->nullable();
        $table->text('rejection_reason')->nullable();
        $table->timestamps();
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claims');
    }
};
