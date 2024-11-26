<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HMVC CRUD by AJAX</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Toastr for Notifications -->
    <link href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/toastr.min.js"></script>
</head>
<body class="bg-light">

    

    <div class="container mt-5">


        <h1 class="text-center text-primary mb-4">User Management</h1>
        
        <?php if(isset($error)): ?> <p style="color: red;"><?php echo $error; ?></p> <?php endif; ?>
        <?php echo validation_errors(); ?>


        <!-- Add User Button -->
        <div class="mb-4 text-end">
            <button id="openModalButton" class="btn btn-success">Add User</button>
        </div>

        <!-- Add User Modal -->
        <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="userModalLabel">Add User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="userForm">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name:</label>
                                <input type="text" id="name" class="form-control" placeholder="Enter name"  >
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" id="email" class="form-control" placeholder="Enter email" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit User Modal -->
        <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="editUserForm">
                        <div class="modal-body">
                            <input type="hidden" id="modalUserId">
                            <div class="mb-3">
                                <label for="modalUserName" class="form-label">Name</label>
                                <input type="text" id="modalUserName" class="form-control" placeholder="Enter name" required>
                            </div>
                            <div class="mb-3">
                                <label for="modalUserEmail" class="form-label">Email</label>
                                <input type="email" id="modalUserEmail" class="form-control" placeholder="Enter email" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </form>
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
                    url: 'Student/get_users',
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
                                        <td class="text-center">
                                            <button class="btn btn-warning btn-sm editBtn" 
                                                data-id="${user.id}" data-name="${user.name}" 
                                                data-email="${user.email}">
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
            });

            // Add User
            $('#userForm').submit(function (e) {
                e.preventDefault();
                $userData = {
                    name: $('#name').val(),
                    email: $('#email').val(),
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

            // Delete User
            window.deleteUser = function (id) {
                if (confirm('Are you sure you want to delete this user?')) {
                    $.ajax({
                        url: 'Student/delete_user',
                        method: 'POST',
                        data: { 'id': id },
                        success: function (output) {
                            const res = JSON.parse(output);
                            if(res.status === 'success'){
                                toastr.success(res.message);
                                fetchUsers();
                            }
                            else{
                                toastr.error(res.message);
                            }
                        },
                        error: function () {
                            toastr.error('Error deleting user.');
                        }
                    });
                }
            };

            // Edit User
            $(document).on('click', '.editBtn', function () {
                const id = $(this).data('id');
                const name = $(this).data('name');
                const email = $(this).data('email');

                $('#modalUserId').val(id);
                $('#modalUserName').val(name);
                $('#modalUserEmail').val(email);
                $('#editUserModal').modal('show');
            });

            // Update User
            $('#editUserForm').submit(function (e) {
                e.preventDefault();
                const userData = {
                    id: $('#modalUserId').val(),
                    name: $('#modalUserName').val(),
                    email: $('#modalUserEmail').val(),
                };

                $.ajax({
                    url: 'Student/update_user',
                    method: 'POST',
                    data: userData,
                    success: function (output) {
                        const res = JSON.parse(output);
                        if(res.status === 'success'){
                            toastr.success(res.message);
                            $('#editUserModal').modal('hide');
                            fetchUsers();
                        }else{
                            toastr.error(res.message);
                        }
                    },
                    error: function () {
                        toastr.error('Error updating user.');
                    }
                });
            });

            // Load users on page load
            fetchUsers();
        });
    </script>
</body>
</html>
