<?php $page_title = 'Dashboard'; ?>

@extends('layouts.main')

@section('page-title', $page_title)

@section('page-css')
    <link rel="stylesheet" href="/assets/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
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
                            <li class="breadcrumb-item active">{{ $page_title }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        @canany(['ACTIVITY_LOG_VIEW', 'DASHBOARD_SUMMARY'])
            <div class="content">
                <div class="container-fluid">
                    @can('DASHBOARD_SUMMARY')
                        <div class="row">
                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <h3 id="productCount">0</h3>
                                        <p>Products</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-fish"></i>
                                    </div>
                                    <a href="{{ route('product.index') }}" class="small-box-footer">More info <i
                                            class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>

                            <div class="col-lg-3 col-6">

                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3 id="articleCount">0</h3>
                                        <p>Articles</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-newspaper"></i>
                                    </div>
                                    <a href="{{ route('article.index') }}" class="small-box-footer">More info <i
                                            class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>

                            <div class="col-lg-3 col-6">

                                <div class="small-box bg-warning">
                                    <div class="inner">
                                        <h3 id="subscriberCount">0</h3>
                                        <p>Subscribers</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-thumbs-up"></i>
                                    </div>
                                    <a href="{{ route('subscriber.index') }}" class="small-box-footer">More info <i
                                            class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>

                            <div class="col-lg-3 col-6">

                                <div class="small-box bg-danger">
                                    <div class="inner">
                                        <h3 id="contactFormCount">0</h3>
                                        <p>Contact Form</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-address-card"></i>
                                    </div>
                                    <a href="{{ route('contact-form.index') }}" class="small-box-footer">More info <i
                                            class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>

                        </div>
                    @endcan
                    @can('ACTIVITY_LOG_VIEW')
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header py-2">
                                        <div class="row">
                                            <div class="col-12 my-1">
                                                <h3 class="card-title">
                                                    Log Activities
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="table-activity-logs" class="table table-bordered table-hover table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Logs</th>
                                                        <th>Action</th>
                                                        <th>Causer</th>
                                                        <th>Description</th>
                                                        <th>Date Time</th>
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
                    @endcan
                </div>
            </div>
        @endcanany
        @cannot('ACTIVITY_LOG_VIEW', 'DASHBOARD_SUMMARY')
            <div class="content">
                <div class="container-fluid d-flex flex-column justify-content-center align-items-center" style="height: 80vh">
                    <img src="/images/panenqu.svg" class="mb-3" height="100vh" alt="Panenqu Logo">
                    <h3 class="text-center" style="color: #EE632C;font-size:3em ">Content Management System</h3>
                </div>
            </div>
        @endcannot
    </div>
    <div class="modal fade" id="modalLogs">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Log Data</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <pre id="json"></pre>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-js')
    <script type="text/javascript" src="/assets/adminlte/plugins/moment/moment.min.js"></script>
    <script type="text/javascript" src="/assets/adminlte/plugins/datatables/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="/assets/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(function() {
            $('#table-activity-logs').on('click', '.see-log', function() {
                var jsonData = $(this).attr('data-json');

                $('#json').text(JSON.stringify(JSON.parse(jsonData), undefined, 2));
                $('#modalLogs').modal('show');
            });
        });

        $(function() {
            var activityLogsTable = $('#table-activity-logs').DataTable({
                ajax: {
                    url: "{{ route('dashboard.logActivities') }}",
                },
                columns: [{
                    data: 'properties',
                    render: function(data, type, row, meta) {
                        return `
                            <button class="d-inline btn btn-xs btn-default see-log"
                                data-json='${JSON.stringify(row.properties)}'>view log</button>
                        `;
                    },
                    width: "60px",
                    className: "text-center"
                }, {
                    data: 'log_name'
                }, {
                    data: 'causer.name'
                }, {
                    data: 'description'
                }, {
                    data: 'created_at',
                    render: function(data, type, row, meta) {
                        return `
                            ${moment(row.created_at).fromNow()}
                        `;
                    },
                }],
                "columnDefs": [{
                    "targets": [0, 1, 2, 3],
                    "orderable": false
                }],
                "order": [
                    [4, 'desc']
                ],
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "pageLength": 5,
                "processing": true,
                "serverSide": true,
            });
        });

        $(function() {
            $.ajax({
                url: "{{ route('dashboard.summary') }}",
                dataType: "JSON",
                complete: function(data) {
                    var response = data.responseJSON;

                    var productCount = response?.products;
                    var articleCount = response?.articles;
                    var subscriberCount = response?.subscribers;
                    var contactFormCount = response?.contactForm;
                    $('#productCount').html(productCount);
                    $('#articleCount').html(articleCount);
                    $('#subscriberCount').html(subscriberCount);
                    $('#contactFormCount').html(contactFormCount);
                }
            })
        });
    </script>
@endsection
