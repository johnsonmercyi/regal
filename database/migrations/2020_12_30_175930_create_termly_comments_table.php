<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTermlyCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('termly_comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('global_id')->nullable();
            $table->integer('school_id');
            $table->integer('academic_session_id');
            $table->integer('term_id');
            $table->integer('class_id');
            $table->integer('student_id');
            $table->string('formTeacherComment', 300)->nulllable();
            $table->string('headTeacherComment', 300)->nulllable();
            $table->enum('passOrFail', ['PASS', 'FAIL'])->nullable();
            $table->enum('promotedOrNotPromoted', ['PROMOTED', 'NOT PROMOTED', 'PROMOTED ON TRIAL'])->nullable();
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
        Schema::dropIfExists('termly_comments');
    }
}
