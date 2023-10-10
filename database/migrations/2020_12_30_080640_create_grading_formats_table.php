<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGradingFormatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grading_formats', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('global_id');
            $table->integer('school_id');
            $table->integer('format_id');
            $table->string('description', 50);
            $table->string('grade', 3);
            $table->string('minScore', 6);
            $table->string('maxScore', 6);
            $table->enum('status', ['0', '1']);
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
        Schema::dropIfExists('grading_formats');
    }
}
