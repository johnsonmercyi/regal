<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchoolSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_sections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('global_id')->nullable();
            $table->string('sectionName', 255);
            $table->string('shortName', 20);
            $table->string('sectionHead', 255);
            $table->string('sectionHeadSign', 255)->nullable();
            $table->integer('school_id');
            $table->integer('assessment_format_id')->nullable();
            $table->integer('grading_format_id')->nullable();
            $table->enum('status', ['Active', 'Inactive']);
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
        Schema::dropIfExists('school_sections');
    }
}
