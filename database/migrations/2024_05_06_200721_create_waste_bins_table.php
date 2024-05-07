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
        Schema::create('waste_bins', function (Blueprint $table) {
            $table->id();
            $table->string("bin_number");
            $table->integer("fill")->default(0);
            $table->dateTime("last_update")->nullable();
            $table->string("zone")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('waste_bins');
    }
};
