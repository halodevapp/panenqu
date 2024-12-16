<?php $page_title = 'Popup Banner'; ?>

@extends('layouts.main')

@section('page-title', $page_title)

@section('page-css')
    <link rel="stylesheet" href="/assets/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <style>
        #table-popup_paginate {
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
                                    <a href="{{ route('config.popup.create') }}"
                                        class="btn btn-primary btn-sm d-block d-sm-inline-block">
                                        Create Pop Up Banner
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="table-popup" class="table table-bordered table-hover table-sm">
                                        <thead>
                                            <tr>
                                                <th class="text-center w70px">#</th>
                                                <th>Description</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                <th>Web Link</th>
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
            var tablePopup;

            tablePopup = $('#table-popup').DataTable({
                "ajax": "{{ route('config.popup.getAll') }}",
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
                    "searchPlaceholder": "Search by description"
                },
                "order": [
                    [0, 'desc']
                ],
                "search": {
                    return: true,
                },
                "columns": [{
                        data: 'id',
                        orderable: false,
                        class: 'text-center',
                        render: function(data, type, row, meta) {
                            data =
                                `<a href="{{ url('config/popup') }}/${data}/edit"
                                    class="btn btn-xs btn-success" title="Edit">
                                    <i class="far fa-edit fa-fw"></i>
                                </a>
                                <form
                                    action="{{ url('config/popup') }}/${data}"
                                    method="POST" class="d-inline">
                                    @csrf
                                    @method('delete')
                                    <button type="submit"
                                        class="delete_popup btn btn-xs btn-danger"
                                        title="Delete">
                                        <i class="far fa-trash-alt fa-fw"></i>
                                    </button>
                                </form>
                                `;
                            return data;
                        }
                    },
                    {
                        data: 'description',
                        orderable: false
                    },
                    {
                        data: 'start_date',
                        orderable: true
                    },
                    {
                        data: 'end_date',
                        orderable: true
                    },
                    {
                        data: 'web_link',
                        orderable: false,
                        render: function(data, type, row, meta) {
                            data =
                                `<a href="${data}" target="_blank"
                                    class="btn btn-xs btn-default" title="Open Link">
                                    ${data}
                                </a>
                                `;
                            return data;
                        }
                    }
                ],
            });

            $(window).resize(function() {
                tablePopup.columns.adjust();
            });

            $('#table-popup').on('click', '.delete_popup', function(e) {
                var dis = this;
                e.preventDefault();
                Swal.fire({
                    title: 'Delete Pop Up ?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Delete',
                    buttonsStyling: false,
                    allowOutsideClick: false,
                    customClass: {
                        confirmButton: 'btn btn-danger mr-3',
                        cancelButton: 'btn btn-secondary'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        showLoading();
                        $(dis).parents('form').submit();
                    }
                })
            })
        });
    </script>
@endsection
