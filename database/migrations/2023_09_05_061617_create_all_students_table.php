<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('all_students', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('local_students_id')->nullable();
            $table->unsignedBigInteger('foreign_students_id')->nullable();
            $table->string('student_type');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));

            $table->foreign('local_students_id')->references('id')->on('local_students')->onDelete('cascade');
            $table->foreign('foreign_students_id')->references('id')->on('foreign_students')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('all_students');
    }
}
