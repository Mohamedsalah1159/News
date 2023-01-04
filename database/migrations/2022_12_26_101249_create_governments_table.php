<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGovernmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('governments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('governmentStatus')->default(0)->comment('0=>basec 1=>sub ');
            $table->boolean('registration_status')->default(0)->comment('0=>non register(false) 1=>can register(true) ');
            $table->unsignedBigInteger('parent_id')->default(0);
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
        Schema::dropIfExists('governments');
    }
}
