<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterLocalStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('local_students', function (Blueprint $table) {
            $table->enum('gender', ['Male', 'Female'])->after('age');
            $table->string('email')->after('grades');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('local_students', function (Blueprint $table) {
            $table->dropColumn(['gender', 'email']);
        });
    }
}
