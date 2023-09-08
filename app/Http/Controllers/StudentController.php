<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AllStudents;
use App\Models\LocalStudents;
use App\Models\ForeignStudents;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Exception;

class StudentController extends Controller
{
    public function create()
    {
        return view('create');
    }

    public function save(Request $request)
    {
        $request->validate([
            'id_number' => 'required|numeric|max:99999',
            'name' => 'required|string',
            'age' => 'required|numeric|max:100',
            'city' => 'required|string',
            'mobile_number' => 'required|regex:/^09\d{9}$/|max:11',
            'grades' => 'required|numeric|max:100',
            'email' => 'required|email',
        ], [
            'id_number.max' => 'The id number must be a maximum of 5 digits.',
            'age.max' => 'You are too old',
            'mobile_number.regex' => 'The mobile number must start with "09" and must be 11 digits in total',
        ]);

        $validatedData = [
            'student_type' => $request->student_type,
            'id_number' => $request->id_number,
            'name' => $request->name,
            'age' => $request->age,
            'gender' => $request->gender,
            'city' => $request->city,
            'mobile_number' => $request->mobile_number,
            'grades' => $request->grades,
            'email' => $request->email,
        ];

        $localStudents = LocalStudents::where('id_number', $request->id_number)->first();
        $foreignStudents = ForeignStudents::where('id_number', $request->id_number)->first();
        $localDuplicate = LocalStudents::where('name', $request->name)
            ->where('mobile_number', $request->mobile_number)
            ->first();

        $foreignDuplicate = ForeignStudents::where('name', $request->name)
            ->where('mobile_number', $request->mobile_number)
            ->first();

        if ($localStudents || $foreignStudents) {
            // Duplicate found, return with an error message
            return redirect()->route('create')->with('error', 'ID Number already exists!');
        }elseif ($localDuplicate || $foreignDuplicate){
            return redirect()->route('create')->with('error', 'Name with the same mobile number already exist!');
        }

        $modelClass = $validatedData['student_type'] == 'local' ? LocalStudents::class : ForeignStudents::class;
        $student = new $modelClass;
        $student->fill($validatedData);
        $student->save();

        $allStudent = new AllStudents;
        $allStudent->student_type = $validatedData['student_type'];


        if ($validatedData['student_type'] == 'local') {
            $allStudent->local_students_id = $student->id;
        } else {
            $allStudent->foreign_students_id = $student->id;
        }

        $allStudent->save();

        return redirect()->route('home')->with('success', 'Student Added successfully');
    }




    public function combineStudentData()
    {
        $allStudents = AllStudents::with('localStudent', 'foreignStudent')->get();

        return view('home', compact('allStudents'));
    }




    public function displayEdit($student_type, $id)
    {
        $student = "";
        $type = "";
        if ($student_type == 'local') {
            $student = AllStudents::whereHas('localStudent')->with('localStudent')->whereid($id)->first()->toArray();
            $type = 'local_student';
        } else {
            $student = AllStudents::whereHas('foreignStudent')->with('foreignStudent')->whereid($id)->first()->toArray();
            $type = 'foreign_student';
        }
        return view('edit', compact('student', 'type'));
    }


    public function update(Request $request)
    {
        $request->validate([
            'id_number' => 'required|numeric|max:99999',
            'name' => 'required|string',
            'age' => 'required|numeric|max:100',
            'city' => 'required|string',
            'mobile_number' => 'required|regex:/^09\d{9}$/|max:11',
            'grades' => 'required|numeric|max:100',
            'email' => 'required|email',
        ], [
            'id_number.max' => 'The id number must be a maximum of 5 digits.',
            'age.max' => 'You are too old',
            'mobile_number.regex' => 'The mobile number must start with "09" and must be 11 digits in total',
        ]);

        $studentId = $request->input('id');
        $updatedData = $request->except('_token', '_method', 'id');
        $newStudentType = $request->input('student_type');

        $student = AllStudents::findOrFail($studentId);
        $id = AllStudents::whereid($studentId)->first();

        $columnsToCheck = ['name', 'id_number', 'mobile_number'];
        $errorMessages = [];

        // dd($id->local_students_id, $studentId);
        // Check for duplicates based on name and mobile_number within the same student type
        $existingLocalStudentByNameMobile = null;
        $existingForeignStudentByNameMobile = null;

            $existingLocalStudentByNameMobile = LocalStudents::where('id', '!=', $id->local_students_id)
                ->where('name', $updatedData['name'])
                ->where('mobile_number', $updatedData['mobile_number'])
                ->first();

            $existingForeignStudentByNameMobile = ForeignStudents::where('id', '!=', $id->foreign_students_id)
                ->where('name', $updatedData['name'])
                ->where('mobile_number', $updatedData['mobile_number'])
                ->first();


        if ($existingLocalStudentByNameMobile || $existingForeignStudentByNameMobile) {
            $errorMessages[] = 'Name and Mobile number combination is already in use by another student.';
        }

        // Check for duplicates based on id_number across both student types
        $existingLocalStudentById = LocalStudents::where('id', '!=', $id->local_students_id)
            ->where('id_number', $updatedData['id_number'])
            ->first();

        $existingForeignStudentById = ForeignStudents::where('id', '!=', $id->foreign_students_id)
            ->where('id_number', $updatedData['id_number'])
            ->first();

        if ($existingLocalStudentById || $existingForeignStudentById) {
            $errorMessages[] = 'ID number is already in use by another student.';
        }

        // Check if there are any error messages and handle them
        if (!empty($errorMessages)) {
            return redirect()->back()->with('error', implode('<br>', $errorMessages));
        }

            // Proceed with the update
            if ($student->student_type == 'local' && $newStudentType == 'foreign') {
                $localId = $id->local_students_id;
                $foreign = ForeignStudents::create(array_merge(['student_type' => 'foreign'], $updatedData));
                AllStudents::create(['foreign_students_id' => $foreign->id, 'student_type' => $newStudentType]);
                LocalStudents::where('id', $localId)->delete();
            } elseif ($student->student_type == 'foreign' && $newStudentType == 'local') {
                $foreignId = $id->foreign_students_id;
                $local = LocalStudents::create(array_merge(['student_type' => 'local'], $updatedData));
                AllStudents::create(['local_students_id' => $local->id, 'student_type' => $newStudentType]);
                ForeignStudents::where('id', $foreignId)->delete();
            } else {
                if ($student->student_type == 'local') {
                    $localId = $id->local_students_id;
                    LocalStudents::where('id', $localId)->update($updatedData);
                } elseif ($student->student_type == 'foreign') {
                    $foreignId = $id->foreign_students_id;
                    ForeignStudents::where('id', $foreignId)->update($updatedData);
                }
            }

            return redirect()->route('home')->with('success', 'Student data updated successfully.');
        }

    public function filter(Request $request)
    {
        $studentType = $request->input('student_type');

        if ($studentType === 'all') {
            $allStudents = AllStudents::with('localStudent', 'foreignStudent')->get();
        } elseif ($studentType === 'local') {
            $allStudents = AllStudents::whereHas('localStudent')->with('localStudent')->get();
        } elseif ($studentType === 'foreign') {
            $allStudents = AllStudents::whereHas('foreignStudent')->with('foreignStudent')->get();
        } else {
            return redirect()->route('home')->with('error', 'Invalid student type selected');
        }

        return view('home', compact('allStudents'));
    }

    public function delete($id)
    {
        $student = AllStudents::findOrFail($id);

        $studentType = $student->student_type;

        if ($studentType === 'local') {
            LocalStudents::where('id', $student->local_students_id)->delete();
        } elseif ($studentType === 'foreign') {
            ForeignStudents::where('id', $student->foreign_students_id)->delete();
        }

        $student->delete();

        return redirect()->route('home')->with('success', 'Student deleted successfully');
    }

}
