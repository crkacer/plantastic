<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id')->unique()->unsigned();
            $table->string('location', 700);
            $table->string('title', 700);
            $table->dateTime('startdate');
            $table->dateTime('enddate');
            $table->dateTime('starttime');
            $table->dateTime('endtime');
            $table->text('description')->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->text('organizer_description')->nullable();
            $table->integer('event_type_id')->unsigned()->nullable();
            $table->string('background_photo', 1000);
            $table->string('template')->default('A');
            $table->integer('category_id')->unsigned()->nullable();
            $table->string('url', 1000);
            $table->bigInteger('registered_amount')->unsigned();
            $table->bigInteger('capacity')->unsigned();
            $table->string('code')->default('0000000');
            $table->bigInteger('price')->unsigned()->default(0);
            $table->rememberToken();
            $table->timestamps();
        });

        // Schema::table('events', function($table) {
        //     $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        //     $table->foreign('event_type_id')->references('id')->on('event_types')->onDelete('set null');
        //     $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
