<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSectionHeadIdToTermlyComments extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('termly_comments', function (Blueprint $table) {
      $table->unsignedBigInteger('sectionHeadId')->nullable()->after('student_id');
    });
  }

  /**
   * Reverse the migrations.
   * @return void
   */
  public function down()
  {
    Schema::table('termly_comments', function (Blueprint $table) {
      $table->dropColumn('sectionHeadId');
    });
  }
}
