<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocalStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('local_students', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('student_type');
            $table->integer('id_number');
            $table->string('names');
            $table->integer('age');
            $table->string('city');
            $table->string('mobile_number', 100);
            $table->decimal('grades', 30, 2)->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));

            $table->unique(array('id_number', 'names', 'mobile_number'), 'local_indexs');
        });
    }

    // id
    // student_type
    // id_number
    // names
    // age
    // city
    // mobile_number
    // grades

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('local_students');
    }
}
