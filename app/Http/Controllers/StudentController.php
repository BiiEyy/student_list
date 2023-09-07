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
            'id_number' => 'required|string',
            'name' => 'required|string',
            'age' => 'required|numeric',
            'city' => 'required|string',
            'mobile_number' => 'required|string',
            'grades' => 'required|string',
            'email' => 'required|email',
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
        $studentId = $request->input('id');
        $updatedData = $request->except('_token', '_method', 'id');
        $newStudentType = $request->input('student_type');
        $newIdNumber = $request->input('id_number');
        $newMobileNumber = $request->input('mobile_number');
        $newName = $request->input('name');

        $student = AllStudents::findOrFail($studentId);
        $id = AllStudents::whereid($studentId)->first();

        if ($student->student_type == 'local' && $newStudentType == 'foreign') {
            // Check if pinapalitan ba id_number
            if ($id->id_number !== $newIdNumber) {
                // Check if there's a duplicate id_number in foreign_students
                $duplicateForeignStudent = ForeignStudents::where('id_number', $newIdNumber)->first();
                if ($duplicateForeignStudent) {
                    return redirect()->back()->with('error', 'Duplicate id_number in foreign_students table.');
                }
            }

            // Check if there's a duplicate name and mobile_number in foreign_students
            $duplicateNameMobileForeign = ForeignStudents::where('name', $newName)
                ->where('mobile_number', $newMobileNumber)
                ->first();
            if ($duplicateNameMobileForeign) {
                return redirect()->back()->with('error', 'Duplicate name and mobile_number in foreign_students table.');
            }

            $localId = $id->local_students_id;
            $foreign = ForeignStudents::create(array_merge(['student_type' => 'foreign'], $updatedData));
            AllStudents::create(['foreign_students_id' => $foreign->id, 'student_type' => $newStudentType]);
            LocalStudents::where('id', $localId)->delete();
        } elseif ($student->student_type == 'foreign' && $newStudentType == 'local') {
            // Check if id_number is changing
            if ($id->id_number !== $newIdNumber) {
                // Check if there's a duplicate id_number in local_students
                $duplicateLocalStudent = LocalStudents::where('id_number', $newIdNumber)->first();
                if ($duplicateLocalStudent) {
                    return redirect()->back()->with('error', 'Duplicate id_number in local_students table.');
                }
            }

            // Check if there's a duplicate name and mobile_number in local_students
            $duplicateNameMobileLocal = LocalStudents::where('name', $newName)
                ->where('mobile_number', $newMobileNumber)
                ->first();
            if ($duplicateNameMobileLocal) {
                return redirect()->back()->with('error', 'Duplicate name and mobile_number in local_students table.');
            }

            $foreignId = $id->foreign_students_id;
            $local = LocalStudents::create(array_merge(['student_type' => 'local'], $updatedData));
            AllStudents::create(['local_students_id' => $local->id, 'student_type' => $newStudentType]);
            ForeignStudents::where('id', $foreignId)->delete();
        } else {
            if ($student->student_type == 'local') {
                $localId = $id->local_students_id;
                // Check if id_number is changing
                if ($id->id_number !== $newIdNumber) {
                    // Check if there's a duplicate id_number in local_students
                    $duplicateLocalStudent = LocalStudents::where('id_number', $newIdNumber)->first();
                    if ($duplicateLocalStudent) {
                        return redirect()->back()->with('error', 'Duplicate id_number in local_students table.');
                    }
                }

                // Check if there's a duplicate name and mobile_number in local_students
                $duplicateNameMobileLocal = LocalStudents::where('name', $newName)
                    ->where('mobile_number', $newMobileNumber)
                    ->where('id', '!=', $localId) // Exclude the current student record from the check
                    ->first();
                if ($duplicateNameMobileLocal) {
                    return redirect()->back()->with('error', 'Duplicate name and mobile_number in local_students table.');
                }

                LocalStudents::where('id', $localId)->update($updatedData);
            } elseif ($student->student_type == 'foreign') {
                $foreignId = $id->foreign_students_id;
                // Check if id_number is changing
                if ($id->id_number !== $newIdNumber) {
                    // Check if there's a duplicate id_number in foreign_students
                    $duplicateForeignStudent = ForeignStudents::where('id_number', $newIdNumber)->first();
                    if ($duplicateForeignStudent) {
                        return redirect()->back()->with('error', 'Duplicate id_number in foreign_students table.');
                    }
                }

                // Check if there's a duplicate name and mobile_number in foreign_students
                $duplicateNameMobileForeign = ForeignStudents::where('name', $newName)
                    ->where('mobile_number', $newMobileNumber)
                    ->where('id', '!=', $foreignId) // Exclude the current student record from the check
                    ->first();
                if ($duplicateNameMobileForeign) {
                    return redirect()->back()->with('error', 'Duplicate name and mobile_number in foreign_students table.');
                }

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
