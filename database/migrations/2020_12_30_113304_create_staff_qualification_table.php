<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffQualificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_qualification', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('global_id')->nullable();
            $table->integer('school_id');
            $table->integer('staff_id');
            $table->string('type',20);
            $table->string('institution',200);
            $table->string('course', 100)->nullable();
            $table->string('year', 10);
            $table->string('certificate', 200);
            $table->string('grade', 50)->nullable();
            $table->string('body', 50)->nullable();
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
        Schema::dropIfExists('staff_qualification');
    }
}
