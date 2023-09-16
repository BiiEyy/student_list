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
use App\Http\Requests\MyValidationRequest;
use Exception;
use Yajra\DataTables\Facades\DataTables;

class StudentController extends Controller
{
    public function create()
    {
        return view('create');
    }


    public function save(MyValidationRequest $request)
    {
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
        $localStudents = LocalStudents::where('id_number', $validatedData['id_number'])->first();
        $foreignStudents = ForeignStudents::where('id_number', $request->id_number)->first();
        $localDuplicate = LocalStudents::where('name', $request->name)
            ->where('mobile_number', $request->mobile_number)
            ->first();

        $foreignDuplicate = ForeignStudents::where('name', $request->name)
            ->where('mobile_number', $request->mobile_number)
            ->first();

        if ($localStudents || $foreignStudents) {
            // Duplicate found, return with an error message
            return response()->json(['status' => 'error', 'message' => 'ID number already exists!']);
        }elseif ($localDuplicate || $foreignDuplicate){
            return response()->json(['status' => 'error', 'message' => 'Name with the same mobile number already exists!']);
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

        return response()->json(['status' => 'success', 201]);
    }


    public function combineStudentData()
    {
        $allStudents = AllStudents::with('localStudent', 'foreignStudent')->get();

        // Initialize an array to store the modified data
        $data = [];

        foreach ($allStudents as $student) {
            // Determine the student type
            $studentType = $student->student_type;

            // Define default fields
            $rowData = [
                'id' => '',
                'student_type' => $studentType,
                'id_number' => '',
                'name' => '',
                'age' => '',
                'gender' => '',
                'city' => '',
                'mobile_number' => '',
                'grades' => '',
                'email' => '',
            ];

            // Populate the fields based on the student type
            if ($studentType === 'local' && $student->localStudent) {
                $localStudent = $student->localStudent;
                $rowData['id'] = $student->id;
                $rowData['id_number'] = $localStudent->id_number;
                $rowData['name'] = $localStudent->name;
                $rowData['age'] = $localStudent->age;
                $rowData['gender'] = $localStudent->gender;
                $rowData['city'] = $localStudent->city;
                $rowData['mobile_number'] = $localStudent->mobile_number;
                $rowData['grades'] = $localStudent->grades;
                $rowData['email'] = $localStudent->email;
            } elseif ($studentType === 'foreign' && $student->foreignStudent) {
                $foreignStudent = $student->foreignStudent;
                $rowData['id'] = $student->id;
                $rowData['id_number'] = $foreignStudent->id_number;
                $rowData['name'] = $foreignStudent->name;
                $rowData['age'] = $foreignStudent->age;
                $rowData['gender'] = $foreignStudent->gender;
                $rowData['city'] = $foreignStudent->city;
                $rowData['mobile_number'] = $foreignStudent->mobile_number;
                $rowData['grades'] = $foreignStudent->grades;
                $rowData['email'] = $foreignStudent->email;
            }

            // Add the row data to the final data array
            $data[] = $rowData;
        }

        // Return the modified data as JSON for DataTables
        return datatables()->of($data)->toJson();
    }


    public function update(MyValidationRequest $request)
    {
        $studentId = $request->input('id');
        $updatedData = $request->except('_token', '_method', 'id');
        $newStudentType = $request->input('student_type');

        $student = AllStudents::findOrFail($studentId);
        $id = AllStudents::whereid($studentId)->first();

        $columnsToCheck = ['name', 'id_number', 'mobile_number'];
        $errorMessages = [];

        $errorMessages = $this->checkForDuplicates($id, $newStudentType, $updatedData);

        if (!empty($errorMessages)) {
            return response()->json(['status' => 'error', 'message' => $errorMessages]);
        }

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

            return response()->json(['status' => 'success'], 201);
        }



    public function delete(Request $request)
    {
        $selectedIds = $request->input('ids');

        foreach ($selectedIds as $id) {
            $student = AllStudents::findOrFail($id);
            $studentType = $student->student_type;

            if ($studentType === 'local') {
                LocalStudents::where('id', $student->local_students_id)->delete();
            } elseif ($studentType === 'foreign') {
                ForeignStudents::where('id', $student->foreign_students_id)->delete();
            }
        }


        return response()->json(['status' => 'success'], 201);
    }



    private function checkForDuplicates($id, $newStudentType, $updatedData)
    {
        $errorMessages = [];
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
            $errorMessages[] = 'Name with the same mobile number already exist!';
        }

        // Check for duplicates based on id_number across both student types
        $existingLocalStudentById = LocalStudents::where('id', '!=', $id->local_students_id)
            ->where('id_number', $updatedData['id_number'])
            ->first();

        $existingForeignStudentById = ForeignStudents::where('id', '!=', $id->foreign_students_id)
            ->where('id_number', $updatedData['id_number'])
            ->first();

        if ($existingLocalStudentById || $existingForeignStudentById) {
            $errorMessages[] = 'ID number already exist!';
        }

        return $errorMessages;
    }

}
