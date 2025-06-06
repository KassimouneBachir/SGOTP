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
        Schema::create('objets', function (Blueprint $table) {
    $table->id();
    $table->string('nom');
    $table->enum('statut', ['perdu', 'trouvé', 'rendu'])->default('perdu');
    $table->string('lieu');
    $table->date('date_perte');
    $table->text('description');
    $table->string('photo_url')->nullable();
      $table->foreignId('user_id')->nullable()->constrained();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('objets');
    }
};
