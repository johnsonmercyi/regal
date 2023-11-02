<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSectionHeadsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('section_heads', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->integer('global_id')->nullable();
      $table->integer('section_id');
      $table->integer('school_id');
      $table->string('name', 255);
      $table->string('signature', 500);
      $table->timestamp('created_at')->useCurrent();
      $table->timestamp('updated_at')->nullable();
      $table->enum('sync_status', ['0', '1', '2'])->default('0');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('section_heads');
  }
}
