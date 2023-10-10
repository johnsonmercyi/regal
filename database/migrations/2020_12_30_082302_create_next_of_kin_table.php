<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNextOfKinTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('next_of_kin', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('global_id')->nullable();
            $table->integer('school_id');
            $table->string('title', 30)->nullable();
            $table->string('lastName', 50);
            $table->string('firstName', 50);
            $table->string('otherName', 50);
            $table->string('phoneNo', 50);
            $table->string('occupation', 50)->nullable();
            $table->string('relationship', 20);
            $table->string('address', 150)->nullable();
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
        Schema::dropIfExists('next_of_kin');
    }
}
