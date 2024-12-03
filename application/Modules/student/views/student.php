<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HMVC CRUD by AJAX</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Toastr for Notifications -->
    <link href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/toastr.min.js"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h1 class="text-center text-primary mb-4">User Management</h1>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p><?php endif; ?>
        <?php echo validation_errors(); ?>

        <!-- Add User Button -->
        <div class="mb-4 text-end">
            <button id="openModalButton" class="btn btn-success">Add User</button>
        </div>
        <div class="modal fade" id="userModal" aria-labelledby="userModalLabel" aria-hidden="false">
            <div class="modal-dialog modal-lg">
                <!-- Adjusted to a larger, rectangular layout -->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="userModalLabel">Add User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="max-height: 800px; padding: 20px;">
                        <form id="userForm">
                            <!-- Name Input -->
                            <div class="mb-3">
                                <label for="name" class="form-label">Name:</label>
                                <input type="text" id="name" class="form-control" placeholder="Enter name" required>
                            </div>

                            <!-- Email Input -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" id="email" class="form-control" placeholder="Enter email" required>
                            </div>

                            <!-- Date of Birth -->
                            <div class="mb-3">
                                <label for="dob" class="form-label">Date of Birth:</label>
                                <input type="date" id="dob" class="form-control" required>
                            </div>

                            <!-- Status Dropdown -->
                            <div class="mb-3">
                                <label for="status" class="form-label">Status:</label>
                                <select id="status" class="form-select" required>
                                    <option value="" disabled selected>Select status</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="delete">Delete</option>
                                </select>
                            </div>

                            <!-- Category Dropdown -->
                            <div class="mb-3">
                                <label for="users_id" class="form-label">Category:</label>
                                <select name="users_id" id="users_id" class="js-example-basic-single">
                                    <option value="" selected>Select Category</option>
                                </select>


                            </div>

                            <!-- Submit Button -->
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">Submit</button>
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
                        <form id="editUserForm">
                            <!-- Hidden Input for User ID -->
                            <input type="hidden" id="modalUserId" name="id">

                            <!-- Name Field -->
                            <div class="mb-3">
                                <label for="modalUserName" class="form-label">Name</label>
                                <input type="text" id="modalUserName" class="form-control" placeholder="Enter name"
                                    required>
                            </div>

                            <!-- Email Field -->
                            <div class="mb-3">
                                <label for="modalUserEmail" class="form-label">Email</label>
                                <input type="email" id="modalUserEmail" class="form-control" placeholder="Enter email"
                                    required>
                            </div>

                            <!-- Date of Birth Field -->
                            <div class="mb-3">
                                <label for="modalUserDob" class="form-label">Date of Birth</label>
                                <input type="date" id="modalUserDob" class="form-control">
                            </div>


                            <div class="mb-3"> <label for="modalUserStatus" class="form-label">Status:</label>
                                <select id="modalUserStatus" class="form-select" required>
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
                                <button type="submit" class="btn btn-success">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users List -->
        <h2 class="text-secondary">Users List</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle" id="usersTable">
                <thead class="table-primary text-center">
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
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be dynamically loaded here -->
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Fetch and display users
            function fetchUsers() {
                $.ajax({
                    url: 'Student/getData',
                    method: 'GET',
                    success: function (response) {

                        const users = JSON.parse(response);
                        let rows = '';
                        let sn = 1;

                        if (users.length > 0) {
                            users.forEach(user => {
                                rows += `
                            <tr>
                            <td class="text-center">${sn++}</td>
                            <td>${user.name}</td>
                            <td>${user.email}</td>
                            <td>${user.Category}</td>
                            <td>${user.dob}</td>
                            <td>${user.upcoming_day_count}</td>
                            <td>${user.days_until_birthday}</td>
                            <td>${user.newDate}</td>
                            <td>${user.status}</td>
                            <td class="text-center">
                            <button class="btn btn-warning btn-sm editBtn" 
                            data-id="${user.id}" data-name="${user.name}" 
                            data-email="${user.email}" data-dob="${user.dob}" data-status="${user.status}" data-users_id="${user.Category}"s>
                            Edit
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="deleteUser(${user.id})">Delete</button>
                            </td>
                            </tr>`;
                            });
                        } else {
                            rows = `<tr><td colspan="4" class="text-center">No users found.</td></tr>`;
                        }

                        $('#usersTable tbody').html(rows);
                    },
                    error: function () {
                        toastr.error('Error fetching users.');
                    }
                });
            }

            // Open Add User Modal
            $('#openModalButton').click(function () {
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
                        data: function (params) {
                            return {
                                searchTerm: params.term
                            };
                        },
                        processResults: function (data) {
                            return {
                                results: data.map(function (usertype) {
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

            $('#userForm').submit(function (e) {
                e.preventDefault();
                $userData = {
                    name: $('#name').val(),
                    email: $('#email').val(),
                    dob: $('#dob').val(),
                    status: $('#status').val(),
                    users_id: $('#users_id').val()
                };
                $.ajax({

                    url: 'Student/add_user',
                    method: 'POST',
                    data: $userData,
                    success: function (output) {
                        const res = JSON.parse(output);
                        if (res.status === 'success') {
                            toastr.success(res.message);
                            $('#userModal').modal('hide');
                            $('#userForm')[0].reset();
                            fetchUsers();
                        } else {
                            toastr.error(res.message);
                        }
                    },
                    error: function () {
                        toastr.error(re.message);
                    }
                });
            });

            // Edit User
            $(document).on('click', '.editBtn', function () {
                const id = $(this).data('id');
                const name = $(this).data('name');
                const email = $(this).data('email');
                const dob = $(this).data('dob');
                const status = $(this).data('status');
                const users_id = $(this).data('users_id');


                // Populate modal fields
                $('#modalUserId').val(id);
                $('#modalUserName').val(name);
                $('#modalUserEmail').val(email);
                $('#modalUserDob').val(dob);

                const normalizedStatus = status.toLowerCase(); // Normalize the status value

                // Check if the status value exists in the dropdown
                if ($('#modalUserStatus option[value="' + normalizedStatus + '"]').length) {
                    $('#modalUserStatus').val(normalizedStatus).change(); // Set status to selected value
                } else {
                    console.error('Status value not found in dropdown:', status);
                }

                $('#modalUsersId').val(users_id);
                $('#editUserModal').modal('show');

                // Initialize Select2
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
                        data: function (params) {
                            return {
                                searchTerm: params.term
                            };
                        },
                        processResults: function (data) {
                            return {
                                results: data.map(function (usertype) {
                                    console.log(usertype);
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

                // Set the selected option for Select2
                const selectedOption = {

                    id: id,
                    text: users_id,

                }; // Use data attribute or fallback
                if (selectedOption.id) {
                    const option = new Option(selectedOption.text, selectedOption.id, true, true);
                    selectElement.append(option).trigger('change'); // Add and set the option
                }
            });


            // Update User
            $('#editUserForm').submit(function (e) {
                e.preventDefault();
                const userData = {
                    id: $('#modalUserId').val(),
                    name: $('#modalUserName').val(),
                    email: $('#modalUserEmail').val(),
                    dob: $('#modalUserDob').val(),
                    status: $('#modalUserStatus').val(),
                    users_id: $('#modalUsersId').val(),


                };


                $.ajax({
                    url: 'Student/update_user',
                    method: 'POST',
                    data: userData,
                    success: function (output) {
                        const res = JSON.parse(output);
                        if (res.status === 'success') {
                            toastr.success(res.message);
                            $('#editUserModal').modal('hide');
                            fetchUsers();
                        } else {
                            toastr.error(res.message);
                        }
                    },
                    error: function () {
                        toastr.error('Error updating user.');
                    }
                });
            });



            // Delete User
            window.deleteUser = function (id) {
                if (confirm('Are you sure you want to delete this user?')) {
                    $.ajax({
                        url: 'Student/delete_user',
                        method: 'POST',
                        data: {
                            'id': id
                        },
                        success: function (output) {
                            const res = JSON.parse(output);
                            if (res.status === 'success') {
                                toastr.success(res.message);
                                fetchUsers();
                            } else {
                                toastr.error(res.message);
                            }
                        },
                        error: function () {
                            toastr.error('Error deleting user.');
                        }
                    });
                }
            };

            // Load users on page load
            fetchUsers();
        });
    </script>
</body>

</html>