<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\AllStudents;

class ForeignStudents extends Model
{

    protected $table = 'foreign_students';

    protected $fillable = ['student_type','id_number','name','age','gender','city','mobile_number','grades','email'];

    public function allStudents()
    {
       return $this->hasMany(AllStudents::class);
    }
}
