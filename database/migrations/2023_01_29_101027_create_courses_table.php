<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name', 191);
            $table->text('desc')->nullable();
            $table->string('hours', 255)->nullable();
            $table->string('price', 191);
            $table->string('price_after_discount', 191);
            $table->string('discount', 191)->nullable();
            $table->date('start_date');
            $table->date('last_date_booking')->nullable();
            $table->string('course_img', 191)->nullable();
            $table->string('teacher', 191);
            $table->string('teacher_img', 191)->nullable();
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
        Schema::dropIfExists('courses');
    }
}
