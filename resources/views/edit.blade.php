<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <h1 class="modal-title fs-5" id="editModalLabel">Update Student</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="editerror-message" style="display: none;" class="alert alert-danger"></div>
            <form id="editStudentForm">
                @csrf

                <input type="hidden" name="editid" id="editid">
                <div class="form-group row">
                    <label for="editstudent_type" class="col-md-4 col-form-label text-md-right">Student Type:</label>
                    <div class="col-md-6">
                        <select class="form-select editresettable-input" aria-label="Default select example" name="editstudent_type" id="editstudent_type">
                            <option value="local">Local</option>
                            <option value="foreign">Foreign</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="editid_number" class="col-md-4 col-form-label text-md-right">ID Number:</label>
                    <div class="col-md-6">
                        <input id="editid_number" type="text" class="form-control edit-input-field editresettable-input" name="editid_number" autocomplete="editid_number" autofocus>
                        <span id="editsid_number" style="color:red"></span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="editname" class="col-md-4 col-form-label text-md-right">Name:</label>
                    <div class="col-md-6">
                        <input id="editname" type="text" class="form-control edit-input-field editresettable-input" name="editname" autocomplete="editname" autofocus>
                        <span id="editsname" style="color:red"></span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="editage" class="col-md-4 col-form-label text-md-right">Age:</label>
                    <div class="col-md-6">
                        <input id="editage" type="text" class="form-control edit-input-field editresettable-input" name="editage" autocomplete="editage" autofocus>
                        <span id="editsage" style="color:red"></span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="editgender" class="col-md-4 col-form-label text-md-right">Gender:</label>
                    <div class="col-md-6">
                        <select class="form-select editresettable-input" aria-label="Default select example" name="editgender" id="editgender">
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="editcity" class="col-md-4 col-form-label text-md-right">City:</label>
                    <div class="col-md-6">
                        <input id="editcity" type="text" class="form-control edit-input-field editresettable-input" name="editcity" autocomplete="editcity" autofocus>
                        <span id="editscity" style="color:red"></span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="editmobile_number" class="col-md-4 col-form-label text-md-right">Mobile Number:</label>
                    <div class="col-md-6">
                        <input id="editmobile_number" type="text" class="form-control edit-input-field editresettable-input" name="editmobile_number" autocomplete="editmobile_number" autofocus>
                        <span id="editsmobile_number" style="color:red"></span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="editgrades" class="col-md-4 col-form-label text-md-right">Grades:</label>
                    <div class="col-md-6">
                        <input id="editgrades" type="text" class="form-control edit-input-field editresettable-input" name="editgrades" autocomplete="editgrades" autofocus>
                        <span id="editsgrades" style="color:red"></span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="editemail" class="col-md-4 col-form-label text-md-right">E-Mail Address:</label>
                    <div class="col-md-6">
                        <input id="editemail" type="email" class="form-control edit-input-field editresettable-input" name="editemail" autocomplete="editemail">
                        <span id="editsemail" style="color:red"></span>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="updateStudent">Update Student</button>
        </div>
    </div>
    </div>
</div>
