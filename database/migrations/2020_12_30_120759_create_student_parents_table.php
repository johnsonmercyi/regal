<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentParentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_parents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('global_id')->nullable();
            $table->integer('school_id');
            $table->string('father_title', 20)->nullable();
            $table->string('father_firstName', 50)->nullable();
            $table->string('father_lastName', 50)->nullable();
            $table->string('father_otherName', 50)->nullable();
            $table->string('father_email', 100)->nullable();
            $table->string('father_phoneNo', 20)->nullable();
            $table->string('father_occupation', 50)->nullable();
            $table->string('father_officePhone', 50)->nullable();
            $table->string('father_officeAddress', 100)->nullable();
            $table->string('mother_title', 20)->nullable();
            $table->string('mother_firstName', 50)->nullable();
            $table->string('mother_lastName', 50)->nullable();
            $table->string('mother_otherName', 50)->nullable();
            $table->string('mother_email', 100)->nullable();
            $table->string('mother_phoneNo', 20)->nullable();
            $table->string('mother_occupation', 50)->nullable();
            $table->string('mother_officePhone', 50)->nullable();
            $table->string('mother_officeAddress', 100)->nullable();
            $table->string('family_doctor_name', 100)->nullable();
            $table->string('family_doctor_phone', 100)->nullable();
            $table->string('family_doctor_address', 100)->nullable();
            $table->integer('marital_status_id')->nullable();
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
        Schema::dropIfExists('student_parents');
    }
}
