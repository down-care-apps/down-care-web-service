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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();

            //add age
            $table->integer('age')->nullable();

            //add createAt
            $table->timestamp('createAt')->nullable();

            //add displayName
            $table->string('displayName')->nullable();

            //add email
            $table->string('email')->unique();

            //add name
            $table->string('name')->nullable();

            //add phoneNumber
            $table->string('phoneNumber')->nullable();

            //add photoURL
            $table->string('photoURL')->nullable();

            //add uid
            $table->string('uid')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
