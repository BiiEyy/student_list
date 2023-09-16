<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <h1 class="modal-title fs-5" id="createModalLabel">Add Student</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="error-message" style="display: none;" class="alert alert-danger"></div>
            <form id="addStudentForm">
                @csrf

                <div class="form-group row">
                    <label for="student_type" class="col-md-4 col-form-label text-md-right">Student Type:</label>
                    <div class="col-md-6">
                        <select class="form-select resettable-input" aria-label="Default select example" name="student_type" id="student_type">
                            <option value="local" selected>Local</option>
                            <option value="foreign">Foreign</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="id_number" class="col-md-4 col-form-label text-md-right">ID Number:</label>
                    <div class="col-md-6">
                        <input id="id_number" type="text" class="form-control input-field resettable-input" name="id_number" autocomplete="id_number" autofocus>
                        <span id="sid_number" style="color:red"></span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="name" class="col-md-4 col-form-label text-md-right">Name:</label>
                    <div class="col-md-6">
                        <input id="name" type="text" class="form-control input-field resettable-input" name="name" autocomplete="name" autofocus>
                        <span id="sname" style="color:red"></span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="age" class="col-md-4 col-form-label text-md-right">Age:</label>
                    <div class="col-md-6">
                        <input id="age" type="text" class="form-control input-field resettable-input" name="age" autocomplete="age" autofocus>
                        <span id="sage" style="color:red"></span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="gender" class="col-md-4 col-form-label text-md-right">Gender:</label>
                    <div class="col-md-6">
                        <select class="form-select resettable-input" aria-label="Default select example" name="gender" id="gender">
                            <option value="Male" selected>Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="city" class="col-md-4 col-form-label text-md-right">City:</label>
                    <div class="col-md-6">
                        <input id="city" type="text" class="form-control input-field resettable-input" name="city" autocomplete="city" autofocus>
                        <span id="scity" style="color:red"></span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="mobile_number" class="col-md-4 col-form-label text-md-right">Mobile Number:</label>
                    <div class="col-md-6">
                        <input id="mobile_number" type="text" class="form-control input-field resettable-input" name="mobile_number" autocomplete="mobile_number" autofocus>
                        <span id="smobile_number" style="color:red"></span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="grades" class="col-md-4 col-form-label text-md-right">Grades:</label>
                    <div class="col-md-6">
                        <input id="grades" type="text" class="form-control input-field resettable-input" name="grades" autocomplete="grades" autofocus>
                        <span id="sgrades" style="color:red"></span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address:</label>
                    <div class="col-md-6">
                        <input id="email" type="email" class="form-control input-field resettable-input" name="email" autocomplete="email">
                        <span id="semail" style="color:red"></span>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="addStudent">Add Student</button>
        </div>
    </div>
    </div>
</div>
