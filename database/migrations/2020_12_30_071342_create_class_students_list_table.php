<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassStudentsListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_students_list', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('global_id')->nullable();
            $table->integer('academic_session_id');
            $table->enum('first_term', ['0', '1'])->nullable();
            $table->enum('second_term', ['0', '1'])->nullable();
            $table->enum('third_term', ['0', '1'])->nullable();
            $table->integer('term_id')->nullable();
            $table->integer('school_id');
            $table->integer('class_id');
            $table->integer('student_id');
            $table->enum('status', ['Active', 'Inactive'])->nullable();
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
        Schema::dropIfExists('class_students_list');
    }
}
