<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('global_id')->nullable();
            $table->integer('school_id');
            $table->string('firstName', 50);
            $table->string('lastName', 50);
            $table->string('otherName', 50)->nullable();
            $table->enum('gender', ['M', 'F']);
            $table->string('dob', 50)->nullable();
            $table->integer('state_of_origin_id');
            $table->integer('state_of_residence_id')->nullable();
            $table->integer('lga_id');
            $table->string('residentialCity', 50)->nullable();
            $table->string('postalAddress', 50)->nullable();
            $table->string('homeTown', 50)->nullable();
            $table->integer('religion_id');
            $table->integer('denomination_id');
            $table->integer('former_school_id')->nullable();
            $table->integer('former_class_id')->nullable();
            $table->string('doa', 50)->nullable();
            $table->string('regNo', 50)->nullable();
            $table->integer('parent_id')->nullable();
            $table->string('phoneNo', 20)->nullable();
            $table->enum('status', ['0', '1'])->default('1');
            $table->string('imgFile', 100)->nullable();
            $table->string('reg_session', 5)->nullable();
            $table->string('reg_term', 5)->nullable();
            $table->integer('last_login_id')->nullable();
            $table->integer('createdBy')->nullable();
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
        Schema::dropIfExists('students');
    }
}
