<?php $page_title = 'Edit User'; ?>

@extends('layouts.main')

@section('page-title', $page_title)

@section('page-css')
    <link rel="stylesheet" href="/assets/adminlte/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="/assets/jquery-datatables-checkboxes-1.2.12/css/dataTables.checkboxes.css">
@endsection

@section('content')
    <div class="content-wrapper">

        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">{{ $page_title }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('user.index') }}">Master User</a></li>
                            <li class="breadcrumb-item active">{{ $page_title }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <form action="{{ route('user.update', ['user' => $user->id]) }}" class="form-horizontal"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('patch')
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group row">
                                                <label for="is_active" class="col-sm-2 col-form-label">Status</label>
                                                <div class="col-sm-10">
                                                    <div class="col-form-label">
                                                        <div
                                                            class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="is_active" name="is_active"
                                                                {{ $user->is_active == $user->user_active ? 'checked' : '' }}>
                                                            <label class="custom-control-label"
                                                                for="is_active">{{ $user->is_active == $user->user_active ? 'Active' : 'Non Active' }}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="email" class="col-sm-2 col-form-label">User Email</label>
                                                <div class="col-sm-10">
                                                    <label id="email" class="col-form-label">{{ $user->email }}</label>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="name" class="col-sm-2 col-form-label">User Name</label>
                                                <div class="col-sm-10">
                                                    <input type="text"
                                                        class="form-control @error('name') is-invalid @enderror"
                                                        id="name" name="name" placeholder="User Name"
                                                        value="{{ old('name', $user->name) }}">
                                                    <span id="name_error" class="error invalid-feedback">
                                                        @error('name')
                                                            {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="password" class="col-sm-2 col-form-label">Password</label>
                                                <div class="col-sm-10">
                                                    <div class="input-group">
                                                        <input type="password"
                                                            class="form-control @error('password') is-invalid @enderror"
                                                            id="password" name="password" placeholder="Password"
                                                            value="{{ old('password') }}">
                                                        <div id="toggle-password" class="input-group-append">
                                                            <span class="input-group-text"><i
                                                                    class="far fa-eye-slash"></i></span>
                                                        </div>
                                                        <span id="password_error" class="error invalid-feedback">
                                                            @error('password')
                                                                {{ $message }}
                                                            @enderror
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <a href="{{ route('user.index') }}" class="btn btn-secondary">Back</a>
                                    <button id="submit_user" type="submit"
                                        class="btn btn-primary float-right">Save</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <button type="button" id="showModalRoles" class="btn btn-xs btn-primary mr-2">
                                        <i class="fas fa-plus fa-fw"></i>
                                    </button>
                                    Assign Role
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-sm">
                                        <thead>
                                            <tr>
                                                <th class="w-25">Name</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($user->roles as $role)
                                                <tr>
                                                    <td>{{ $role->name }}</td>
                                                    <td>
                                                        <button type="button"
                                                            class="btn btn-xs btn-default seePermissions"
                                                            data-id="{{ $role->id }}"
                                                            data-name="{{ $role->name }}">
                                                            See Permissions
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <button type="button" id="showModalPermissions" class="btn btn-xs btn-primary mr-2">
                                        <i class="fas fa-plus fa-fw"></i>
                                    </button>
                                    Assign Direct Permission
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-sm">
                                        <thead>
                                            <tr>
                                                <th class="w-25">Name</th>
                                                <th>Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($user->permissions as $permission)
                                                <tr>
                                                    <td>{{ $permission->name }}</td>
                                                    <td>{{ $permission->description }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalRolesPermissions">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table id="table-roles-permissions" class="table table-bordered table-hover table-sm"
                            width="100%">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="syncUserPermissions" type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalPermissions">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">List Permissions</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table id="table-permissions" class="table table-bordered table-hover table-sm" width="100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="syncUserPermissions" type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalRoles">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">List Roles</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table id="table-roles" class="table table-bordered table-hover table-sm" width="100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Name</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="syncUserRoles" type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-js')
    <script type="text/javascript" src="/assets/adminlte/plugins/select2/js/select2.full.min.js"></script>
    <script type="text/javascript" src="/assets/adminlte/plugins/datatables/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="/assets/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript" src="/assets/adminlte/plugins/datatables-select/js/dataTables.select.min.js"></script>
    <script type="text/javascript" src="/assets/jquery-datatables-checkboxes-1.2.12/js/dataTables.checkboxes.min.js">
    </script>
    <script>
        $(function() {
            var tablePermissions, tableRoles, tableRolesPermissions;
            var user_id = "{{ $user->id }}";

            $('#submit_user').click(function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Update User ?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Save',
                    buttonsStyling: false,
                    allowOutsideClick: false,
                    customClass: {
                        confirmButton: 'btn btn-success mr-3',
                        cancelButton: 'btn btn-danger'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        showLoading();
                        $('#submit_user').parents('form').submit();
                    }
                })
            });

            $('#toggle-password').click(function() {
                var passAttribute = $('#password').attr('type');
                console.log(passAttribute);

                if (passAttribute == 'password') {
                    $(this).find('i').attr('class', 'far fa-eye');
                    $('#password').attr('type', 'text');
                } else {
                    $(this).find('i').attr('class', 'far fa-eye-slash');
                    $('#password').attr('type', 'password');
                }
            });

            $('#is_active').change(function() {
                var isChecked = $(this).is(':checked');

                if (isChecked) {
                    $(this).next().html('Active');
                } else {
                    $(this).next().html('Non Active');
                }
            });

            $('#showModalRoles').click(function() {
                $('#modalRoles').modal('show');
            });

            $('#modalRoles').on("show.bs.modal", function() {
                $('#table-roles').DataTable().clear().destroy();
                tableRoles = $('#table-roles').DataTable({
                    ajax: {
                        url: "{{ route('userRoles') }}",
                        data: function(d) {
                            d.user_id = user_id
                        },
                        type: "post",
                        dataType: "JSON"

                    },
                    columns: [{
                            data: 'name',
                            checkboxes: {
                                selectRow: true
                            },
                            createdCell: function(td, cellData, rowData, row, col) {
                                console.log(rowData);
                                if (rowData.has == true) {
                                    this.api().cell(td).checkboxes.select();
                                }
                            }
                        },
                        {
                            data: 'name'
                        },
                        {
                            data: 'has',
                            visible: false,
                            searchable: false,
                        }
                    ],
                    select: {
                        style: 'multi',
                    },
                    info: false,
                    lengthChange: false,
                    "order": [
                        [2, 'desc']
                    ]
                })
            });

            $('#syncUserRoles').click(function() {
                var rows_selected = tableRoles.column(0).checkboxes.selected();
                var selectedRoles = [];
                $.each(rows_selected, function(index, rowId) {
                    selectedRoles.push(rowId);
                });

                var message = selectedRoles.length == 0 ?
                    'Tidak ada role yang dipilih, anda yakin mau menghapus user roles ?' :
                    'Sync User Roles ?';

                Swal.fire({
                    title: message,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Save',
                    buttonsStyling: false,
                    allowOutsideClick: false,
                    customClass: {
                        confirmButton: 'btn btn-success mr-3',
                        cancelButton: 'btn btn-danger'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        syncUserRolesProcess(user_id, selectedRoles);
                    }
                })
            });

            function syncUserRolesProcess(user_id, selectedRoles) {
                showLoading();

                $.ajax({
                    url: "{{ route('syncUserRoles') }}",
                    data: {
                        user_id: user_id,
                        roles: selectedRoles
                    },
                    type: "POST",
                    dataType: "JSON",
                    complete: function(data) {
                        var res = data.responseJSON;
                        if (res.success) {
                            Swal.fire({
                                title: res.message,
                                icon: 'success',
                                confirmButtonText: 'OK',
                                buttonsStyling: false,
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                customClass: {
                                    confirmButton: 'btn btn-success'
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            })
                        } else {
                            Swal.fire({
                                title: res.message,
                                icon: 'error',
                                confirmButtonText: 'OK',
                                buttonsStyling: false,
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                customClass: {
                                    confirmButton: 'btn btn-success'
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            })
                        }
                    }
                })
            }

            $('#showModalPermissions').click(function() {
                $('#modalPermissions').modal('show');
            });

            $('#modalPermissions').on("show.bs.modal", function() {
                $('#table-permissions').DataTable().clear().destroy();
                tablePermissions = $('#table-permissions').DataTable({
                    ajax: {
                        url: "{{ route('userPermissions') }}",
                        data: function(d) {
                            d.user_id = user_id
                        },
                        type: "post",
                        dataType: "JSON"

                    },
                    columns: [{
                            data: 'name',
                            checkboxes: {
                                selectRow: true
                            },
                            createdCell: function(td, cellData, rowData, row, col) {
                                console.log(rowData);
                                if (rowData.has == true) {
                                    this.api().cell(td).checkboxes.select();
                                }
                            }
                        },
                        {
                            data: 'name'
                        },
                        {
                            data: 'description'
                        },
                        {
                            data: 'has',
                            visible: false,
                            searchable: false,
                        }
                    ],
                    select: {
                        style: 'multi',
                    },
                    info: false,
                    lengthChange: false,
                    "order": [
                        [3, 'desc']
                    ]
                })
            });

            $('#syncUserPermissions').click(function() {
                var rows_selected = tablePermissions.column(0).checkboxes.selected();
                var selectedPermissions = [];
                $.each(rows_selected, function(index, rowId) {
                    selectedPermissions.push(rowId);
                });

                var message = selectedPermissions.length == 0 ?
                    'Tidak ada permission yang dipilih, anda yakin mau menghapus user permissions ?' :
                    'Sync User Permissions ?';

                Swal.fire({
                    title: message,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Save',
                    buttonsStyling: false,
                    allowOutsideClick: false,
                    customClass: {
                        confirmButton: 'btn btn-success mr-3',
                        cancelButton: 'btn btn-danger'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        syncUserPermissionsProcess(user_id, selectedPermissions);
                    }
                })
            });

            function syncUserPermissionsProcess(user_id, selectedPermissions) {
                showLoading();

                $.ajax({
                    url: "{{ route('syncUserPermissions') }}",
                    data: {
                        user_id: user_id,
                        permissions: selectedPermissions
                    },
                    type: "POST",
                    dataType: "JSON",
                    complete: function(data) {
                        var res = data.responseJSON;
                        if (res.success) {
                            Swal.fire({
                                title: res.message,
                                icon: 'success',
                                confirmButtonText: 'OK',
                                buttonsStyling: false,
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                customClass: {
                                    confirmButton: 'btn btn-success'
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            })
                        } else {
                            Swal.fire({
                                title: res.message,
                                icon: 'error',
                                confirmButtonText: 'OK',
                                buttonsStyling: false,
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                customClass: {
                                    confirmButton: 'btn btn-success'
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            })
                        }
                    }
                })
            }

            $('.seePermissions').on('click', function() {
                role_id = $(this).data('id');
                role_name = $(this).data('name');
                $('#modalRolesPermissions .modal-title').html(`${role_name} Permissions`);
                $('#modalRolesPermissions').modal('show');
            })


            $('#modalRolesPermissions').on("show.bs.modal", function() {
                $('#table-roles-permissions').DataTable().clear().destroy();
                tablePermissions = $('#table-roles-permissions').DataTable({
                    ajax: {
                        url: "{{ route('getPermissionsByRole') }}",
                        data: function(d) {
                            d.role_id = role_id
                        },
                        type: "post",
                        dataType: "JSON"

                    },
                    columns: [{
                            data: 'name'
                        },
                        {
                            data: 'description'
                        }
                    ],
                    info: false,
                    lengthChange: false
                })
            });
        });
    </script>
@endsection
