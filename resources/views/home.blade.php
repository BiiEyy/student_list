@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="mydiv">
            <div class="header">
                <center>
                    <h1>Student List</h1>
                </center>
            </div>
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
            </div>
            <br>
            <div class="custom-search-containers">
                <label for="customFilter" id="custom-search-label">Select Student Type:</label>
                <select id="customFilter">
                    <option value="">All Students</option>
                    <option value="local">Local Students</option>
                    <option value="foreign">Foreign Students</option>
                </select>
            </div>
            <table class="table table-bordered" id="studentTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Student Type</th>
                        <th>ID Number</th>
                        <th>Name</th>
                        <th>Age</th>
                        <th>Gender</th>
                        <th>City</th>
                        <th>Mobile Number</th>
                        <th>Grades</th>
                        <th>Email</th>
                        <th style="width: 6%;">Action</th>
                        <th>
                            <input type="checkbox" id="select-all-checkbox" style="cursor: crosshair;">
                        </th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>

    @include('create')
    @include('edit')
    @include('delete')

    <div class="floating-button2">
        <button class="btn btn-success plus-button" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="fas fa-solid fa-user-plus"></i>
        </button>
    </div>
    <div class="floating-button">
        <button class="btn btn-danger custom-button" id="deleteSelected">
            <i class="fas fa-trash-alt"></i>
        </button>
    </div>
@endsection


@section('script')
    <script>
        $(document).ready(function() {

            //Home Display
            var table = $('#studentTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('home') }}",
                columns: [{
                        "data": "id",
                        "visible": false
                    },
                    {
                        "data": "student_type"
                    },
                    {
                        "data": "id_number"
                    },
                    {
                        "data": "name"
                    },
                    {
                        "data": "age"
                    },
                    {
                        "data": "gender"
                    },
                    {
                        "data": "city"
                    },
                    {
                        "data": "mobile_number"
                    },
                    {
                        "data": "grades"
                    },
                    {
                        "data": "email"
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            // Add a Edit and Delete button for each row
                            return '<button class="btn btn-sm btn-primary edit-button"><i class="fas fa-edit"></i></button> <button class="btn btn-sm btn-danger delete-button" data-id="' +
                                row.id + '"><i class="fas fa-trash-alt"></i></button>';
                        }
                    },
                    {
                        "data": null,
                        "defaultContent": '<input type="checkbox" class="select-checkbox" style="cursor: pointer;">'
                    }
                ],
                columnDefs: [{
                    targets: [11, 10],
                    orderable: false,
                    searchable: false
                }],
                order: [
                    [0, "desc"]
                ]
            });

            // highlight all selected
            $('#select-all-checkbox').on('click', function() {
                var columnIndex = table.column($(this).closest('th')).index();
                var $checkboxes = table.column(columnIndex).nodes().to$().find('.select-checkbox');

                $checkboxes.prop('checked', this.checked);

                if (this.checked) {
                    table.rows().nodes().to$().addClass('selected');
                } else {
                    table.rows().nodes().to$().removeClass('selected');
                }
            });


            //highlight selected
            $('#studentTable tbody').on('click', 'input.select-checkbox', function() {
                var row = $(this).closest('tr');
                if (this.checked) {
                    row.addClass('selected');
                } else {
                    row.removeClass('selected');
                }
            });

            //removes validation
            $('.input-field').on('input', function() {
                const id = this.id;
                const errorId = "s" + id;
                $(this).css('border-color', '');
                $('#' + errorId).html('');
            });

            //Add Student
            $("#addStudent").click(function() {
                let formData = {
                    student_type: $("#student_type").val(),
                    id_number: $("#id_number").val(),
                    name: $("#name").val(),
                    age: $("#age").val(),
                    gender: $("#gender").val(),
                    city: $("#city").val(),
                    mobile_number: $("#mobile_number").val(),
                    grades: $("#grades").val(),
                    email: $("#email").val()
                };

                $.ajax({
                    type: "POST",
                    url: "{{ route('submit') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    dataType: "json",
                    success: function(response) {
                        if (response.status === 'success') {
                            $('.resettable-input').val('');

                            $("#createModal").modal('hide');
                            Swal.fire(
                                'Success',
                                'Student Added Successfully',
                                'success'
                            )

                            $('#studentTable').DataTable().ajax.reload();
                        } else {
                            // Error: Display the error message on top of the table
                            $("#error-message").html(response.message).show();

                            setTimeout(function() {
                                $("#error-message").fadeOut();
                            }, 2000);
                        }
                    },
                    //return validations
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;

                            const fields = ['id_number', 'name', 'age', 'city', 'mobile_number',
                                'grades', 'email'
                            ];

                            fields.forEach(field => {
                                if (errors[field]) {
                                    $(`#s${field}`).html(errors[field][0]);
                                    $(`#${field}`).css('border-color', 'red');
                                }
                            });

                            console.log(errors);
                        } else {
                            console.log('An error occurred:', xhr.statusText);
                        }
                    },
                    complete: function() {
                        $("#submitProduct").prop("disabled", false);
                        $("#submitProduct").html("Add Product");
                    },
                });
            });

            //EDIT DISPLAY
            var dataTableContainer = $('#studentTable');
            dataTableContainer.on('click', '.edit-button', function() {

                var data = $(this).closest('table').DataTable().row($(this).closest('tr')).data();
                // Populate the edit form fields with data
                const fields = [
                    'id', 'student_type', 'id_number', 'name', 'age', 'gender', 'city', 'mobile_number',
                    'grades', 'email'
                ];

                fields.forEach(field => {
                    $(`#edit${field}`).val(data[field]);
                });

                $("#editModal").modal('show');
            });

            //Clears update validation
            $('.edit-input-field').on('input', function() {
                const fieldName = this.id.replace('edit', '');
                const $errorSpan = $(`#edits${fieldName}`);

                $(this).add($errorSpan).css('border-color', '').html('');
            });


            //UPDATE STUDENT
            $("#updateStudent").click(function() {
                const formData = {
                    id: $("#editid").val(),
                    student_type: $("#editstudent_type").val(),
                    id_number: $("#editid_number").val(),
                    name: $("#editname").val(),
                    age: $("#editage").val(),
                    gender: $("#editgender").val(),
                    city: $("#editcity").val(),
                    mobile_number: $("#editmobile_number").val(),
                    grades: $("#editgrades").val(),
                    email: $("#editemail").val()
                };


                $.ajax({
                    type: "PUT",
                    url: "{{ route('update') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    dataType: "json",
                    success: function(response) {
                        if (response.status === 'success') {
                            $('.editresettable-input').val('');

                            $("#editModal").modal('hide');
                            Swal.fire(
                                'Success',
                                'Update Successful',
                                'success'
                            )

                            $('#studentTable').DataTable().ajax.reload();
                        } else {
                            // Error: Display the error message on top of the table
                            $("#editerror-message").html(response.message).show();

                            setTimeout(function() {
                                $("#editerror-message").fadeOut();
                            }, 2000);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            var errorResponse = xhr.responseJSON;
                            var errors = xhr.responseJSON.errors;

                            if (errorResponse.status === 'error') {
                                var errorMessages = errorResponse.message;

                                // Loop through errorMessages and handle each error
                                for (var field in errorMessages) {
                                    if (errorMessages.hasOwnProperty(field)) {
                                        // Handle each field's error messages as needed
                                        var fieldErrors = errorMessages[field];
                                        // ...
                                    }
                                }
                            }

                            const fields = ['id_number', 'name', 'age', 'city', 'mobile_number',
                                'grades', 'email'
                            ];

                            fields.forEach(field => {
                                if (errors[field]) {
                                    const errorMessage = errors[field][0];
                                    $(`#edits${field}`).html(errorMessage);
                                    $(`#edit${field}`).css('border-color',
                                        errorMessage ? 'red' : '');
                                }
                            });
                            console.log(errors);
                        } else {
                            console.log('An error occurred:', xhr.statusText);
                        }
                    },
                    complete: function() {
                        $("#updateStudent").prop("disabled", false);
                        $("#updateStudent").html("Update Student");
                    },
                });
            });

            //Delete 1 row
            var selectedRowId = null;

            $('#studentTable').on('click', '.delete-button', function() {
                selectedRowId = $(this).data('id');

                $('#deleteConfirmationModal').modal('show');
            });


            //disable delete selected button
            function toggleDeleteButton() {
                var selectedRowCount = table.rows('.selected').count();
                var isSelectAllChecked = $('#select-all-checkbox').prop('checked');

                // Enable the delete button if there are selected rows or if "Select All" is checked
                $('#deleteSelected').prop('disabled', selectedRowCount === 0 && !isSelectAllChecked);
            }

            toggleDeleteButton();
            $('#deleteSelected').on('click', function() {
                // Show the confirmation modal
                $('#deleteConfirmationModal').modal('show');
            });

            $('#confirmDelete').on('click', function() {
                var selectedIds = table.rows('.selected').data().toArray().map(function(row) {
                    return row.id;
                });

                // Delete multiple rows or the selected row
                if (selectedIds.length > 0) {
                    deleteRows(selectedIds);
                } else if (selectedRowId !== null) {
                    deleteRows([selectedRowId]);
                }
            });

            //Delete multiple data
            function deleteRows(ids) {
                if (ids.length > 0) {
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('delete') }}",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            ids: ids
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response.status === 'success') {
                                $("#deleteConfirmationModal").modal('hide');
                                Swal.fire(
                                    'Success',
                                    'Delete Successful',
                                    'success'
                                )
                                $('#studentTable').DataTable().ajax.reload();
                            }
                        },
                        error: function(xhr) {
                            console.log('An error occurred:', xhr.statusText);
                        }
                    });
                }
            }

            $('#confirmDelete').on('click', function() {

                $('#deleteConfirmationModal').modal('hide');

                toggleDeleteButton();
            });

            table.on('click', '.select-checkbox', function() {
                toggleDeleteButton();
            });

            $('#select-all-checkbox').on('click', function() {
                toggleDeleteButton();
            });

            $('#customFilter').on('change', function() {
                var selectedFilter = $(this).val();


                if (selectedFilter === '') {
                    table.search('').draw();
                } else {
                    table.column(1).search(selectedFilter).draw();
                }
            });

        });
    </script>
@endsection
