<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('email', 200)->unique();
            $table->string('position', 150);
            $table->string('phone', 20)->nullable();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->boolean('status')->default(1)->comment('1=activo, 0=inactivo');
            $table->timestamps();

            $table->foreign('project_id')->references('id')->on('projects')->onDelete('set null')->onUpdate('cascade');
            $table->index('project_id'); // Wait, I used $blueprint but the table is $table
            $table->index('status');
            $table->index('name');
        });
    }

    public function down()
    {
        Schema::dropIfExists('employees');
    }
};
