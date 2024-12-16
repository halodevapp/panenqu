<?php $page_title = 'Edit Role'; ?>

@extends('layouts.main')

@section('page-title', $page_title)

@section('page-css')
    <link rel="stylesheet" href="/assets/adminlte/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="/assets/jquery-datatables-checkboxes-1.2.12/css/dataTables.checkboxes.css">
    <style>
        table.dataTable tbody>tr.selected td {
            color: black !important;
            background-color: #eeeeee !important;
        }
    </style>
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
                            <li class="breadcrumb-item"><a href="{{ route('role.index') }}">Master Role</a></li>
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
                            <form action="{{ route('role.update', ['role' => $role->id]) }}" class="form-horizontal"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('patch')
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group row">
                                                <label for="name" class="col-sm-2 col-form-label">Role Name</label>
                                                <div class="col-sm-10">
                                                    <input type="text"
                                                        class="form-control @error('name') is-invalid @enderror"
                                                        id="name" name="name" placeholder="Role Name"
                                                        value="{{ old('name', $role->name) }}">
                                                    <span id="name_error" class="error invalid-feedback">
                                                        @error('name')
                                                            {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <a href="{{ route('role.index') }}" class="btn btn-secondary">Back</a>
                                    <button id="submit_role" type="submit"
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
                                    <button type="button" id="showModalPermissions" class="btn btn-xs btn-primary mr-2">
                                        <i class="fas fa-plus fa-fw"></i>
                                    </button>
                                    Assign permission
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-sm assigned-permissions">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($role->permissions as $permission)
                                                <tr>
                                                    <td>{{ $permission['name'] }}</td>
                                                    <td>{{ $permission['description'] }}</td>
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
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="syncRolePermissions" type="button" class="btn btn-primary">Save changes</button>
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
            var tablePermissions;
            var roleID = "{{ $role->id }}";

            $('#submit_role').click(function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Update Role ?',
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
                        $('#submit_role').parents('form').submit();
                    }
                })
            });

            $('#showModalPermissions').click(function() {
                $('#modalPermissions').modal('show');
            });

            $('.assigned-permissions').DataTable({});

            $('#modalPermissions').on("show.bs.modal", function() {
                $('#table-permissions').DataTable().clear().destroy();
                tablePermissions = $('#table-permissions').DataTable({
                    ajax: {
                        url: "{{ route('getRolePermissions') }}",
                        data: function(d) {
                            d.role_id = roleID
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
                            data: 'name',
                            width: "20%"
                        },
                        {
                            data: 'description'
                        }
                    ],
                    select: {
                        style: 'multi',
                    },
                    info: false,
                    lengthChange: false,
                    "order": [
                        [1, 'asc']
                    ]
                })
            });

            $('#syncRolePermissions').click(function() {
                var rows_selected = tablePermissions.column(0).checkboxes.selected();
                var selectedPermissions = [];
                $.each(rows_selected, function(index, rowId) {
                    selectedPermissions.push(rowId);
                });

                var message = selectedPermissions.length == 0 ?
                    'Tidak ada permission yang dipilih, anda yakin mau menghapus permissions ?' :
                    'Sync Role Permissions ?';

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
                        syncRolePermissionsProcess(roleID, selectedPermissions);
                    }
                })
            });

            function syncRolePermissionsProcess(roleID, selectedPermissions) {
                showLoading();

                $.ajax({
                    url: "{{ route('syncRolePermissions') }}",
                    data: {
                        role_id: roleID,
                        permissions: selectedPermissions
                    },
                    type: "POST",
                    dataType: "JSON",
                    complete: function(data) {
                        console.log(data);
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
        });
    </script>
@endsection
