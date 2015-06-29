<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LocationData extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('areas', function(Blueprint $table)
    {
      $table->increments('id');
      $table->string('top_left');
      $table->string('bottom_right');
      $table->string('name');
      $table->timestamps();
    });

    Schema::create('categories', function(Blueprint $table)
    {
      $table->increments('id');
      $table->string('name');
      $table->string('name_nl');
      $table->timestamps();
    });

    Schema::create('locations', function(Blueprint $table)
    {
      $table->increments('id');
      $table->integer('area_id');
      $table->string('name');
      $table->string('top_left');
      $table->string('top_right');
      $table->string('bottom_left');
      $table->string('bottom_right');
      $table->timestamps();
    });

    Schema::create('randomlists', function(Blueprint $table)
    {
      $table->increments('id');
      $table->integer('completed_at');
      $table->integer('start_date');
      $table->integer('end_date');
      $table->timestamps();
    });

    Schema::create('location_randomlist', function(Blueprint $table)
    {
      $table->increments('id');
      $table->integer('randomlist_id');
      $table->integer('location_id');
      $table->boolean('visited');
      $table->integer('user_id');
      $table->timestamps();
    });

    Schema::create('subcategories', function(Blueprint $table)
    {
      $table->increments('id');
      $table->integer('category_id')->nullable();
      $table->string('name');
      $table->string('name_nl');
      $table->string('code');
      $table->timestamps();
    });

    Schema::create('location_subcategory', function(Blueprint $table)
    {
      $table->increments('id');
      $table->integer('subcategory_id');
      $table->integer('location_id');
      $table->integer('accepted_grade');
      $table->timestamps();
    });

    Schema::create('checklists', function(Blueprint $table)
    {
      $table->increments('id');
      $table->integer('user_id');
      $table->integer('location_id');
      $table->timestamps();
    });

    Schema::create('checklist_items', function(Blueprint $table)
    {
      $table->increments('id');
      $table->integer('checklist_id');
      $table->integer('subcategory_id');
      $table->string('grade');
      $table->string('subcategorylocation_id');
      $table->string('image_url');
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
    Schema::drop('areas');
    Schema::drop('categories');
    Schema::drop('locations');
    Schema::drop('randomlists');
    Schema::drop('location_randomlist');
    Schema::drop('subcategories');
    Schema::drop('location_subcategory');
    Schema::drop('checklists');
    Schema::drop('checklist_items');
  }

}
