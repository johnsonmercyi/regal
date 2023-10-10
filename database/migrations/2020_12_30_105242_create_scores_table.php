<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('global_id')->nullable();
            $table->integer('school_id');
            $table->integer('class_id');
            $table->integer('academic_session_id');
            $table->integer('term_id');
            $table->integer('subject_id');
            $table->integer('student_id');
            $table->string('CA1', 5)->nullable();
            $table->string('CA2', 5)->nullable();
            $table->string('CA3', 5)->nullable();
            $table->string('CA4', 5)->nullable();
            $table->string('CA5', 5)->nullable();
            $table->string('CA6', 5)->nullable();
            $table->string('CA7', 5)->nullable();
            $table->string('CA8', 5)->nullable();
            $table->string('EXAM', 5)->nullable();
            $table->string('TOTAL', 5)->nullable();
            $table->enum('status', ['0', '1'])->default('1');
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
        Schema::dropIfExists('scores');
    }
}
