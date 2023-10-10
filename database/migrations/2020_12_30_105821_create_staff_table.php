<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('global_id')->nullable();
            $table->string('regNo', 50)->nullable();
            $table->string('title', 10)->nullable();
            $table->string('firstName', 50);
            $table->string('lastName', 50);
            $table->string('otherNames', 50)->nullable();
            $table->integer('staff_category_id')->nullable();
            $table->string('dob')->nullable();
            $table->enum('gender', ['M', 'F']);
            $table->integer('state_of_origin_id')->nullable();
            $table->integer('lga_of_origin_id')->nullable();
            $table->string('homeTown', 100)->nullable();
            $table->string('homeAddress', 100)->nullable();
            $table->string('email', 50)->nullable();
            $table->integer('school_id');
            $table->string('phoneNo', 50)->nullable();
            $table->string('imgFile', 100)->nullable();
            $table->string('signature', 100)->nullable();
            $table->integer('marital_status_id')->nullable();
            $table->string('position', 100)->nullable();
            $table->integer('category_id')->nullable();
            $table->string('salary_grade_id', 11)->nullable();
            $table->string('rank_id', 50)->nullable();
            $table->integer('next_of_kin_id')->nullable();
            $table->string('appointmentDate', 50)->nullable();
            $table->enum('status', ['0', '1'])->default('1');
            $table->integer('religion_id')->nullable();
            $table->integer('denomination_id')->nullable();
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
        Schema::dropIfExists('staff');
    }
}
