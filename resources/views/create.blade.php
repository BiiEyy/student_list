@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Add Student</div>
                @if (session('error'))
                    <div class="alert alert-danger" id="danger-alert">
                        {{ session('error') }}
                    </div>
                    <script>
                        setTimeout(function() {
                            $('#danger-alert').fadeOut('slow');
                        }, 3000);
                    </script>
                @endif

                <div class="card-body">
                    <form method="POST" action="{{ route('submit') }}">
                        @csrf
                        <div class="form-group row">
                            <label for="student_type" class="col-md-4 col-form-label text-md-right">Student Type:</label>
                            <div class="col-md-6">
                                <select class="form-select @error('student_type') is-invalid @enderror" aria-label="Default select example" name="student_type">
                                    <option value="local" selected>Local</option>
                                    <option value="foreign">Foreign</option>
                                </select>
                                @error('student_type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="id_number" class="col-md-4 col-form-label text-md-right">ID Number:</label>
                            <div class="col-md-6">
                                <input id="id_number" type="text" class="form-control @error('id_number') is-invalid @enderror" name="id_number" value="{{ old('id_number') }}" autocomplete="id_number" autofocus>
                                @error('id_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Name:</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" autocomplete="name" autofocus>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="age" class="col-md-4 col-form-label text-md-right">Age:</label>
                            <div class="col-md-6">
                                <input id="age" type="text" class="form-control @error('age') is-invalid @enderror" name="age" value="{{ old('age') }}" autocomplete="age" autofocus>
                                @error('age')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="gender" class="col-md-4 col-form-label text-md-right">Gender:</label>
                            <div class="col-md-6">
                                <select class="form-select @error('gender') is-invalid @enderror" aria-label="Default select example" name="gender">
                                    <option value="Male" selected>Male</option>
                                    <option value="Female">Female</option>
                                  </select>
                                @error('gender')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="city" class="col-md-4 col-form-label text-md-right">City:</label>
                            <div class="col-md-6">
                                <input id="city" type="text" class="form-control @error('city') is-invalid @enderror" name="city"  value="{{ old('city') }}" autocomplete="city" autofocus>
                                @error('city')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="mobile_number" class="col-md-4 col-form-label text-md-right">Mobile Number:</label>
                            <div class="col-md-6">
                                <input id="contact" type="text" class="form-control @error('mobile_number') is-invalid @enderror" name="mobile_number"  value="{{ old('mobile_number') }}" autocomplete="mobile_number" autofocus>
                                @error('mobile_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="grades" class="col-md-4 col-form-label text-md-right">Grades:</label>
                            <div class="col-md-6">
                                <input id="grades" type="text" class="form-control @error('grades') is-invalid @enderror" name="grades" value="{{ old('grades') }}" autocomplete="grades" autofocus>
                                @error('grades')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address:</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
