<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <style type="text/css">
        .error {
            color: red;
        }
    </style>
    <title>Hello, world!</title>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-lg-12 mt-5">
                <div class="alert alert-success successMessageAlert" style="display: none;" role="alert">

                </div>
                <form id="userForm" method="POST" action="javascript:void(0)" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                        <small id="name_error" class="form-text text-danger"></small>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                        <small id="email_error" class="form-text text-danger"></small>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" required>
                        <small id="phone_error" class="form-text text-danger"></small>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" required></textarea>
                        <small id="description_error" class="form-text text-danger"></small>
                    </div>
                    <div class="mb-3">
                        <label for="role_id" class="form-label">Role</label>
                        <select class="form-control" id="role_id" name="role_id" required>
                            <option value="">--Select Role --</option>
                            @forelse ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                            @empty
                            @endforelse
                        </select>
                        <small id="role_id_error" class="form-text text-danger"></small>
                    </div>
                    <div class="mb-3">
                        <label for="profile_image" class="form-label">Profile Image</label>
                        <input type="file" class="form-control" id="profile_image" name="profile_image" required>
                        <small id="profile_image_error" class="form-text text-danger"></small>
                    </div>
                    <button type="submit" id="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-md-12">
                <table class="table table-bordered user-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Description</th>
                            <th>Role</th>
                            <th>Profile Image</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="{{ asset('assets/jquery-validation/dist/jquery.validate.min.js') }}"></script>
    <script src="http://jqueryvalidation.org/files/dist/additional-methods.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            jQuery.validator.addMethod("email_regx", function(value, element) {
                var emailregex =
                    /^[a-zA-Z0-9]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9]+(\.[a-zA-Z0-9-]+)*(\.[A-Z a-z]{2,3})$/;
                if (emailregex.test(value)) {
                    return true;
                } else {
                    return this.optional(element)
                }
            }, 'Please Enter Valid Value.');

            jQuery.validator.addMethod("only_number_regx", function(value, element) {
                var regex16 = /^[0-9]+$/;
                if (regex16.test(value)) {
                    return true;
                } else {
                    return this.optional(element)
                }
            }, 'Please Enter Valid Value.');

            $("#userForm").validate({
                rules: {
                    name: {
                        required: true,
                    },
                    email: {
                        required: true,
                        email: true,
                        email_regx: true,
                    },
                    phone: {
                        required: true,
                        only_number_regx: true,
                        minlength: 10,
                        maxlength: 10
                    },
                    description: {
                        required: true,
                    },
                    role_id: {
                        required: true
                    },
                    profile_image: {
                        required: true,
                        extension: "png|jpg|jpeg|gif",
                    }
                },
                messages: {
                    name: {
                        required: "Please enter name"
                    },
                    email: {
                        required: "Please enter email address",
                        email: "Please enter a valid email address",
                        email_regx: "Please enter a valid email address",
                    },
                    phone: {
                        required: "Please enter phone number",
                        only_number_regx: "Please enter valid number",
                        minlength: "Please enter at least 10 characters",
                        maxlength: "Please enter not more than 10 characters"
                    },
                    description: {
                        required: "Please enter description"
                    },
                    role_id: {
                        required: "Please select role"
                    },
                    profile_image: {
                        required: "Please select a profile image",
                        extension: "Please upload a file with a valid extension (png, jpg, jpeg, gif)",
                    }
                },
                submitHandler: function(form) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $('#submit').html('Processing...');
                    $("#submit").attr("disabled", true);
                    $.ajax({
                        url: "{{ route('user.post') }}",
                        type: "POST",
                        data: new FormData(document.getElementById("userForm")),
                        contentType: false,
                        processData: false,
                        cache: false,
                        success: function(response) {
                            $('.user-table').DataTable().draw();
                            $('.successMessageAlert').show().html(
                                'User Created Successfully.');
                            $("#submit").attr("disabled", false);
                            $('#submit').html('Submit');
                            $('#userForm').trigger('reset');

                            setTimeout(function() {
                                $('div.successMessageAlert').hide();
                            }, 4000);
                        },
                        error: function(reject) {
                            var response = $.parseJSON(reject.responseText);
                            $.each(response.errors, function(key, val) {
                                $("#" + key + "_error").text(val[0]);
                            })
                        }
                    });
                }
            });
        })
    </script>

    <script type="text/javascript">
        $(function() {
            var table = $('.user-table').DataTable({
                processing: true,
                responsive: true,
                serverSide: true,
                ajax: "{{ route('user.list') }}",
                columns: [{
                    data: 'name',
                    name: 'name'
                }, {
                    data: 'email',
                    name: 'email'
                }, {
                    data: 'phone',
                    name: 'phone'
                }, {
                    data: 'description',
                    name: 'description'
                }, {
                    data: 'role',
                    name: 'role'
                }, {
                    data: 'profile_pic',
                    name: 'profile_pic'
                }]
            })
        })
    </script>
</body>

</html>
