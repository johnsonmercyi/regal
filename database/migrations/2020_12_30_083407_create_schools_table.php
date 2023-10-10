<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchoolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('global_id')->nullable();
            $table->string('name', 255);
            $table->string('address', 255);
            $table->string('tag', 50);
            $table->integer('lga_id');
            $table->integer('region_id');
            $table->string('motto', 255);
            $table->string('logo', 255);
            $table->string('prefix', 20);
            $table->string('head', 255);
            $table->string('headSignature', 255);
            $table->string('website', 255)->nullable();
            $table->string('themeColor', 255);
            $table->integer('academic_session_id')->nullable();
            $table->integer('current_term_id')->nullable();
            $table->integer('status');
            $table->enum('sync_status', ['0', '1', '2'])->default('0');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schools');
    }
}
