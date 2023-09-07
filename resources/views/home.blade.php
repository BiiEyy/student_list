@extends('layouts.app')

@section('content')
<div class="container">
    <div class="mydiv">
        <div class="header">
            <center>
                <h1>Student List</h1>
            </center>
            <div class="add">
                @if (session('success'))
                    <div class="alert alert-success" id="success-alert">
                        {{ session('success') }}
                    </div>
                    <script>
                        setTimeout(function() {
                            $('#success-alert').fadeOut('slow');
                        }, 3000);
                    </script>
                @endif
                <form method="POST" action="{{ route('filter.students') }}">
                    @csrf
                <div class="row g-0 text-center">
                    <div class="col-sm-3 col-md-2">
                        <a href="{{ route('create') }}" type="submit" class="btn btn-success">Add new student</a>
                    </div>
                    <div class="col-sm-3 col-md-2">
                        <p style="height: 100%; display: flex; align-items: center; justify-content: right;">Select Student Type:</p>
                    </div>
                    <div class="col-sm-3 col-md-2">
                        <select class="form-select" name="student_type">
                            <option value="all" selected>All Students</option>
                            <option value="local">Local Students</option>
                            <option value="foreign">Foreign Students</option>
                          </select>
                    </div>
                    <div class="col-sm-3 col-md-2" style="display: flex; align-items: center; justify-content: left;">
                        <button type="submit" class="btn btn-primary">Apply Filter</button>
                    </div>
                </div>
            </form>
            </div>
            <br>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Student Type</th>
                        <th>ID Number</th>
                        <th>Name</th>
                        <th>Age</th>
                        <th>Gender</th>
                        <th>City</th>
                        <th>Mobile Number</th>
                        <th>Grades</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- @php
                    usort($combinedData, function ($a, $b) {
                        return strcasecmp($a['name'], $b['name']);
                    });
                    @endphp --}}
                    @foreach ($allStudents as $student)
                    <tr>
                        @if ($student->localStudent)
                        <td>{{ $student->localStudent->student_type }}</td>
                        <td>{{ $student->localStudent->id_number }}</td>
                        <td>{{ $student->localStudent->name }}</td>
                        <td>{{ $student->localStudent->age }}</td>
                        <td>{{ $student->localStudent->gender }}</td>
                        <td>{{ $student->localStudent->city }}</td>
                        <td>{{ $student->localStudent->mobile_number }}</td>
                        <td>{{ $student->localStudent->grades }}</td>
                        <td>{{ $student->localStudent->email }}</td>
                        @elseif ($student->foreignStudent)
                        <td>{{ $student->foreignStudent->student_type }}</td>
                        <td>{{ $student->foreignStudent->id_number }}</td>
                        <td>{{ $student->foreignStudent->name }}</td>
                        <td>{{ $student->foreignStudent->age }}</td>
                        <td>{{ $student->foreignStudent->gender }}</td>
                        <td>{{ $student->foreignStudent->city }}</td>
                        <td>{{ $student->foreignStudent->mobile_number }}</td>
                        <td>{{ $student->foreignStudent->grades }}</td>
                        <td>{{ $student->foreignStudent->email }}</td>
                        @endif
                        <td>
                            <a href="{{ route('edit', ['student_type' => $student['student_type'], 'id' => $student['id']]) }}" type="submit" class="btn btn-primary">Edit</a>
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal{{ $student['id'] }}">
                                Delete
                            </button>
                            <div class="modal fade" id="deleteModal{{ $student['id'] }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Confirm Deletion</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete this student?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                            <form action="{{ route('delete.student', ['id' => $student['id']]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
