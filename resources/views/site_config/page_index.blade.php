<?php $page_title = 'Page Config'; ?>

@extends('layouts.main')

@section('page-title', $page_title)

@section('page-css')
    <link rel="stylesheet" href="/assets/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <style>
        #table-page_paginate {
            margin-top: 10px;
        }

        .dataTables_scrollBody {
            border-bottom: 1px solid rgba(169, 169, 169, 0.5);
        }

        div.dropdown-item:active {
            background-color: transparent !important;
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
                            <li class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item">{{ $page_title }}</li>
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
                            <div class="card-header">
                                <div class="row col-6">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-primary dropdown-toggle" type="button"
                                            data-toggle="dropdown" aria-expanded="false">
                                            Create Page
                                        </button>
                                        <div class="dropdown-menu">
                                            <div class="dropdown-item">
                                                <div class="input-group input-group-sm" style="width:300px !important">
                                                    <input type="text" id="page_name" class="form-control">
                                                    <span class="input-group-append">
                                                        <button type="button" class="btn btn-primary"
                                                            id="createPage">Create</button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="table-page" class="table table-bordered table-hover table-sm">
                                        <thead>
                                            <tr>
                                                <th class="text-center w20px">#</th>
                                                <th>Page Name</th>
                                            </tr>
                                        </thead>
                                        <tbody>
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
@endsection

@section('page-js')
    <script type="text/javascript" src="/assets/adminlte/plugins/datatables/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="/assets/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(function() {
            var responseMessage = $('meta[name="response-message"]').attr('content');
            var responseStatus = $('meta[name="response-status"]').attr('content');

            if (responseMessage.trim() != '') {

                if (responseStatus == 'success') {
                    toastr.success(responseMessage)
                } else {
                    toastr.error(responseMessage)

                }
            }
        });

        $(function() {
            var tablePage;

            tablePage = $('#table-page').DataTable({
                "ajax": "{{ route('config.page.getAll') }}",
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "responsive": true,
                "autoWidth": false,
                "processing": true,
                "serverSide": true,
                "language": {
                    "searchPlaceholder": "Search by name"
                },
                "order": [
                    [1, 'asc']
                ],
                "search": {
                    return: true,
                },
                "columns": [{
                        data: 'id',
                        orderable: false,
                        render: function(data, type, row, meta) {
                            data =
                                `<a href="{{ url('config/page') }}/${data}/edit"
                                    class="btn btn-xs btn-success" title="Edit">
                                    <i class="far fa-edit fa-fw"></i>
                                </a>`;
                            return data;
                        }
                    },
                    {
                        data: 'page_name',
                        orderable: true
                    }
                ],
            });

            $(window).resize(function() {
                tablePage.columns.adjust();
            });
        });

        $(function() {
            $('#createPage').click(function() {
                var pageName = $('#page_name').val();

                $.ajax({
                    url: "{{ route('config.page.pageCreate') }}",
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        pageName
                    },
                    complete: function(data) {
                        var response = data.responseJSON;
                        if (response.success) {
                            location.reload();
                        } else {
                            Swal.fire({
                                title: response.message,
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
            });
        });
    </script>
@endsection
