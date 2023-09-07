<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\ForeignStudents;
use App\Models\LocalStudents;


class AllStudents extends Model
{

    protected $fillable = ['student_type','local_students_id','foreign_students_id'];
    protected $table = 'all_students';

    public function localStudent()
    {
        return $this->belongsTo(LocalStudents::class, 'local_students_id');
    }


    public function foreignStudent()
    {
        return $this->belongsTo(ForeignStudents::class, 'foreign_students_id');
    }
}
