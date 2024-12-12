<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HMVC CRUD by AJAX</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Toastr for Notifications -->
    <link href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/toastr.min.js"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

    <link href="https://cdn.datatables.net/v/dt/dt-2.1.8/datatables.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/v/dt/dt-2.1.8/datatables.min.js"></script>

    <!-- CSS for DataTables -->
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css"> -->

    <!-- <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"> </script> -->


    <!-- 
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script> -->



    <style>
    #image_preview {
        width: 100px;
        /* Adjust the width as needed */
        height: 100px;
        /* Adjust the height as needed */
        border: 1px solid #ddd;
        /* Optional border for the image box */
        border-radius: 5px;
        /* Optional rounded corners */
        object-fit: cover;
        /* Maintain aspect ratio */
    }
    </style>



</head>

<body class="bg-light">
    <div class="container mt-5">
        <h1 class="text-center text-primary mb-4">User Management</h1>
        <?php if ($this->session->flashdata('success')) { ?>

        <div class="alert alert-success">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
            <strong><?php echo $this->session->flashdata('success'); ?></strong>
        </div>

        <?php } ?>

        <?php if ($this->session->flashdata('error')) { ?>

        <div class="alert alert-danger">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
            <strong><?php echo $this->session->flashdata('error'); ?></strong>
        </div>

        <?php } ?>


        <!-- Add User Button -->

        <div class="modal fade" id="userModal" aria-labelledby="userModalLabel" aria-hidden="false">
            <div class="modal-dialog modal-lg">
                <!-- Adjusted to a larger, rectangular layout -->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="userModalLabel">Add User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="max-height: 500px; padding: 15px;">
                        <form id="userForm" action="<?php echo base_url('Student/store_student');?>" method="POST"
                            enctype="multipart/form-data">

                            <div class="mb-3">
                                <label for="profile_pic" class="form-label">Images</label>
                                <input type="file" id="profile_pic" name="profile_pic" class="form-control">
                                <div class="mt-3">
                                    <img id="image_preview" src="#" alt="Image Preview" class="img-thumbnail d-none" />
                                </div>
                            </div>


                            <!-- Name Input -->
                            <div class="mb-3">
                                <label for="name" class="form-label">Name:</label>
                                <input type="text" id="name" name="name" class="form-control" placeholder="Enter name">
                            </div>

                            <!-- Email Input -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" id="email" name="email" class="form-control"
                                    placeholder="Enter email">
                            </div>

                            <!-- Date of Birth -->
                            <div class="mb-3">
                                <label for="dob" class="form-label">Date of Birth:</label>
                                <input type="date" id="dob" name="dob" class="form-control">
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="province" class="form-label">Province</label>
                                        <input id="province" name="province" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="district" class="form-label">District</label>
                                        <input id="district" name="district" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status</label>
                                        <select id="status" name="status" class="form-select">
                                            <option value="" disabled selected>Select status</option>
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                            <option value="delete">Delete</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <label for="users_id" class="form-label">Category:</label>
                                    <select name="users_id" id="users_id" class="js-example-basic-single form-select">
                                        <option value="" selected>Select Category</option>
                                    </select>
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100" value="upload">ADD</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>




        <!-- Edit User Modal -->
        <div class="modal fade" id="editUserModal" aria-labelledby="editUserModalLabel" aria-hidden="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editUserForm" action="<?php echo base_url('Student/update_user');?>" method="POST"
                            enctype="multipart/form-data">
                            <!-- Hidden Input for User ID -->
                            <input type="hidden" id="modalUserId" name="id">

                            <div class="mb-3">
                                <label for="currentProfilePic" class="form-label">Profile Picture:</label>
                                <img id="currentProfilePic" name="currentProfilePic"
                                    src="base_url( 'Images/'. profile_pic" alt="Current Profile Picture"
                                    style="width: 80px; height: 100px; border-radius: 20%; object-fit: cover;">
                            </div>

                            <div class="mb-3">
                                <label for="changeImage" class="form-label">CLick Here</label>
                                <input type=file id="changeimage" name="changeimage">
                            </div>

                            <!-- Name Field -->
                            <div class="mb-3">
                                <label for="modalUserName" class="form-label">Name</label>
                                <input type="text" id="modalUserName" name="modalUserName" class="form-control"
                                    placeholder="Enter name" required>
                            </div>

                            <!-- Email Field -->
                            <div class="mb-3">
                                <label for="modalUserEmail" class="form-label">Email</label>
                                <input type="email" id="modalUserEmail" name="modalUserEmail" class="form-control"
                                    placeholder="Enter email" required>
                            </div>

                            <!-- Date of Birth Field -->
                            <div class="mb-3">
                                <label for="modalUserDob" class="form-label">Date of Birth</label>
                                <input type="date" id="modalUserDob" name="modalUserDob" class="form-control">
                            </div>


                            <div class="mb-3"> <label for="modalUserStatus" class="form-label">Status:</label>
                                <select id="modalUserStatus" name="modalUserStatus" class="form-select" required>
                                    <option value="" disabled selected>Select status</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="delete">Delete</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="modalUsersId" class="form-label">Category:</label>
                                <select name="modalUsersId" id="modalUsersId" class="js-example-basic-category"
                                    required>
                                    <!-- Options will be populated dynamically -->
                                </select>
                            </div>


                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" value="upload">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>



        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    Users List
                    <h2 class="text-secondary">Users List</h2>
                    <div class="card">
                        <div class="">
                            <button class="btn btn-primary px-4 m-2 float-right" id="openModalButton">Add</button>
                        </div>
                        <div class="card-body table-responsive p-2 table-container">
                            <table class="table table-hover table-bordered display compact" id="usersTable">
                                <thead>
                                    <tr>
                                        <th>SN</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>UserCategory</th>
                                        <th>DOB</th>
                                        <th>upcoming birthday(sql)</th>
                                        <th>days until birthday(php)</th>
                                        <th>newdate</th>
                                        <th>Status</th>
                                        <th>profile_pic</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="container">
            <h1>Upcoming Birthdays</h1>
            <table id="birthdayTable" class="display">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Date of Birth</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Upcoming Day Count</th>
                        <th>Days Until Birthday</th>
                        <th>Next Birthday</th>
                    </tr>
                </thead>
                <tbody>
                    < Data will be populated here by DataTables -->
    <!-- </tbody>
    </table>
    </div> -->


    <script>
    var usersTable;
    $(document).ready(function() {
        //Fetch and display users

        // function fetchUsers() {
        //     $.ajax({
        //         url: 'Student/getData',
        //         method: 'GET',
        //         success: function(response) {

        //             const users = JSON.parse(response);
        //             let rows = '';
        //             let sn = 1;

        //             if (users.length > 0) {
        //                 users.forEach(user => {
        //                     rows += `
        //                     <tr>
        //                     <td class="text-center">${sn++}</td>
        //                     <td>${user.name}</td>
        //                     <td>${user.email}</td>
        //                     <td>${user.Category}</td>
        //                     <td>${user.dob}</td>
        //                     <td>${user.upcoming_day_count}</td>
        //                     <td>${user.days_until_birthday}</td>
        //                     <td>${user.newDate}</td>
        //                     <td>${user.status}</td>
        //                     <td class="text-center">
        //                     <button class="btn btn-warning btn-sm editBtn" 
        //                     data-id="${user.id}" data-name="${user.name}" 
        //                     data-email="${user.email}" data-dob="${user.dob}" data-status="${user.status}" data-users_id="${user.Category}"s>
        //                     Edit
        //                     </button>
        //                     <button class="btn btn-danger btn-sm" onclick="deleteUser(${user.id})">Delete</button>
        //                     </td>
        //                     </tr>`;
        //                 });
        //             } else {
        //                 rows =
        //                     `<tr><td colspan="4" class="text-center">No users found.</td></tr>`;
        //             }

        //             $('#usersTable tbody').html(rows);
        //         },
        //         error: function() {
        //             toastr.error('Error fetching users.');
        //         }
        //     });
        // }


        var usersTable = $('#usersTable').DataTable({
            destroy: true,
            processing: true,
            language: {
                processing: '<span style="color:black;">Processing...</span>'

            },
            columnDefs: [{
                "targets": [0, 6, 7, 9],
                "orderable": false
            }],


            serverSide: true,
            ajax: {
                url: "Student/birthdaycount",
                type: "POST",
                data: function(data) {


                },
            },


            columns: [{
                    data: "sn"
                },
                {
                    data: "name"
                },
                {
                    data: "email"
                },
                {
                    data: "Category"
                },
                {
                    data: "dob"
                },

                {
                    data: "upcoming_day_count"
                },
                {
                    data: "days_until_birthday"
                },
                {
                    data: "newDate"
                },
                {
                    data: "status"
                },
                {
                    data: "profile_pic"
                },
                {
                    data: "action"
                },
            ]
        });


    });

    // // Open Add User Modal
    $(document).on('click', '#openModalButton', function() {
        $('#userModal').modal('show');

        $('#users_id').select2({
            dropdownParent: $('#userModal'),
            tags: true,
            allowClear: true,
            width: '50%',
            height: '34px',

            ajax: {
                url: 'http://localhost:8000/Student/usertype',
                type: 'POST',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        searchTerm: params.term
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.map(function(usertype) {
                            return {
                                id: usertype.User_id,
                                text: usertype.Category
                            };
                        })
                    };
                },
                cache: true
            },
            minimumInputLength: 1,
        });
    });

    // $('#userForm').submit(function(e) {
    //     e.preventDefault();
    //     $userData = {
    //         name: $('#name').val(),
    //         email: $('#email').val(),
    //         dob: $('#dob').val(),
    //         status: $('#status').val(),
    //         users_id: $('#users_id').val(),
    //         profile_pic: $('#profile_pic').val()
    //     };
    //     $.ajax({

    //         url: 'Student/add_user',
    //         method: 'POST',
    //         data: $userData,
    //         success: function(output) {
    //             const res = JSON.parse(output);
    //             if (res.status === 'success') {
    //                 toastr.success(res.message);
    //                 $('#userModal').modal('hide');
    //                 $('#userForm')[0].reset();
    //                 fetchUsers();
    //             } else {
    //                 toastr.error(res.message);
    //             }
    //         },
    //         error: function() {
    //             toastr.error(re.message);
    //         }
    //     });
    // });

    // // Edit User
    $(document).on('click', '.editBtn', function() {

        const id = $(this).data('id');
        const name = $(this).data('name');
        const email = $(this).data('email');
        const dob = $(this).data('dob');
        const status = $(this).data('status');
        const users_id = $(this).data('category');
        const user_id = $(this).data('users_id');
        const profile_pic = $(this).data('profile_pic');


        $('#modalUserId').val(id);
        $('#modalUserName').val(name);
        $('#modalUserEmail').val(email);
        $('#modalUserDob').val(dob);
        $('#currentProfilePic').val(profile_pic);

        $('#currentProfilePic').attr('src', "<?php echo base_url('Images/'); ?>" + profile_pic);
        $('#currentProfilePic').attr('alt', profile_pic);



        const normalizedStatus = status.toLowerCase();


        if ($('#modalUserStatus option[value="' + normalizedStatus + '"]').length) {
            $('#modalUserStatus').val(normalizedStatus)
                .change();
        } else {
            console.error('Status value not found in dropdown:', status);
        }

        $('#modalUsersId').val(users_id);

        $('#editUserModal').modal('show');

        const selectedOption = {

            ids: user_id,
            text: users_id,

        };


        if (selectedOption.ids) {
            const option = new Option(selectedOption.text, selectedOption.ids, true, true);
            selectElement.append(option).trigger('change');
        }

    });


    const selectElement = $('.js-example-basic-category');
    selectElement.select2({
        dropdownParent: $('#editUserModal'),
        placeholder: 'select an option',
        width: '50%',
        ajax: {
            url: 'http://localhost:8000/Student/usertype',
            type: 'POST',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    searchTerm: params.term
                };
            },
            processResults: function(data) {
                return {
                    results: data.map(function(usertype) {

                        return {

                            id: usertype.User_id,
                            text: usertype.Category
                        };
                    })
                };
            },
            cache: true
        },
        minimumInputLength: 1,
    });





    // Update User
    // $('#editUserForm').submit(function(e) {
    //     e.preventDefault(e);


    //     const userData = {

    //         id: $('#modalUserId').val(),
    //         name: $('#modalUserName').val(),
    //         email: $('#modalUserEmail').val(),
    //         dob: $('#modalUserDob').val(),
    //         status: $('#modalUserStatus').val(),
    //         users_id: $('#modalUsersId').val(),
    //         profile_pic: $('#changeimage').val(),

    //     };
    //     // console.log(userData);

    //     $.ajax({
    //         url: 'Student/update_user',
    //         method: 'POST',
    //         data: userData,
    //         success: function(output) {
    //             const res = JSON.parse(output);
    //             if (res.status === 'success') {
    //                 toastr.success(res.message);
    //                 $('#editUserModal').modal('hide');

    //             } else {
    //                 toastr.error(res.message);
    //             }
    //         },
    //         error: function() {
    //             toastr.error('Error updating user.');
    //         }
    //     });
    // });






    window.deleteUser = function(id) {
        if (confirm('Are you sure you want to delete this user?')) {
            $.ajax({
                url: 'Student/delete_user',
                method: 'POST',
                data: {
                    'id': id
                },
                success: function(output) {
                    const res = JSON.parse(output);
                    if (res.status === 'success') {
                        toastr.success(res.message);

                    } else {
                        toastr.error(res.message);
                    }
                },
                error: function() {
                    toastr.error('Error deleting user.');
                }

            });
        }


    };

    document.getElementById('profile_pic').addEventListener('change', function(event) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var imagePreview = document.getElementById('image_preview');
            imagePreview.src = e.target.result;
            imagePreview.style.display = 'block';
        }
        reader.readAsDataURL(event.target.files[0]);
    });


    window.addEventListener('load', function() {
        toastr.success('<?php echo $this->session->flashdata('success'); ?>');
    });
    </script>
</body>

</html>