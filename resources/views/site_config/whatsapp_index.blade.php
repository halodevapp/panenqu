<?php $page_title = 'Whatsapp'; ?>

@extends('layouts.main')

@section('page-title', $page_title)

@section('page-css')

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

        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="table-whatsapps" class="table table-bordered table-hover table-sm">
                                        <thead>
                                            <tr>
                                                <th class="text-center w70px">#</th>
                                                <th>Type</th>
                                                <th>Value</th>
                                                <th>Created At</th>
                                                <th>Created By</th>
                                                <th>Updated At</th>
                                                <th>Updated By</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($whatsapps as $whatsapp)
                                                <tr>
                                                    <td class="text-center">
                                                        <a href="{{ route('config.whatsapp.edit', ['id' => $whatsapp->id]) }}"
                                                            class="btn btn-xs btn-success" title="Edit">
                                                            <i class="far fa-edit fa-fw"></i>
                                                        </a>
                                                    </td>
                                                    <td>{{ $whatsapp->type }}</td>
                                                    <td>{{ $whatsapp->value }}</td>
                                                    <td>{{ $whatsapp->created_at }}</td>
                                                    <td>{{ $whatsapp->createdBy->name }}</td>
                                                    <td>{{ $whatsapp->updated_at }}</td>
                                                    <td>{{ $whatsapp->updatedBy->name }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-2 float-right">
                                    {{ $whatsapps->onEachSide(2)->appends(['search' => request()->get('search')])->links() }}
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

            $('.delete_whatsapp').click(function(e) {
                var dis = this;
                e.preventDefault();
                Swal.fire({
                    title: 'Delete Notification ?',
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
        })
    </script>
@endsection
