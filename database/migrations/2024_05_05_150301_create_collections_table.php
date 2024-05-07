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
        Schema::create('collections', function (Blueprint $table) {
            $table->id();

            $table->string("requested_by");
            $table->date("requested_date");

            $table->string("type");
            $table->string("address");

            $table->enum("status",["Pending","Collected","Assigned"])->default("Pending");


            $table->date("collection_date")->nullable();

            $table->string("collected_by")->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collections');
    }
};
