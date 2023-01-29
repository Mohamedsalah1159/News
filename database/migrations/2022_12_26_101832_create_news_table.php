<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->text('desc');
            $table->string('user');
            $table->text('link');
            $table->boolean('status')->default(0)->comment('0=>news 1=>article ');
            $table->boolean('const')->default(0)->comment('0=>nonconst 1=>const ');
            $table->unsignedBigInteger('governorate_id');
            $table->foreign('governorate_id')->references('id')->on('governorates')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('initiative_id')->nullable();
            $table->foreign('initiative_id')->references('id')->on('initiatives')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('government_id')->nullable();
            $table->foreign('government_id')->references('id')->on('governments')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news');
    }
}
