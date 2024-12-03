<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filter Users</title>

    <!-- Include jQuery from CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include Select2 CSS from CDN -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Include Select2 CSS from CDN -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Include Select2 JS from CDN -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


</head>

<body>
    <h1>User List</h1>

    <!-- Filters -->
    <div class="ml-2 mt-3 mb-3">
        <label for="members">Members</label>
        <div class="form-group d-flex">
            <select name="members" id="members" class="form-control"></select>
            <a href="#" class="btn btn-primary ml-2" id="searchBtn">
                <i class='fas fa-search'></i>
            </a>
        </div>



    </div>


    <script>
        $(document).ready(function () {
            // Log Select2 initialization status
            console.log($.fn.select2 ? "Select2 loaded successfully!" : "Select2 failed to load.");

            // Initialize Select2
            $('#members').select2({
                placeholder: "Select Member",
                allowClear: true,
                width: '50%',
                ajax: {
                    url: 'http://localhost:8000/Student/get_users',
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
                            results: data.map(function (member) {
                                return {
                                    id: member.id,
                                    text: `${member.name} [${member.email}]`
                                };
                            })
                        };
                    },
                    cache: true
                },
                minimumInputLength: 1
            });
        });
    </script>
</body>

</html>