<?php $page_title = 'Event Galery'; ?>

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
                            <div class="card-header py-2">
                                <div class="row">
                                    <div class="col-lg-6 my-1">
                                        <a href="{{ route('event-galery.create') }}"
                                            class="btn btn-primary btn-sm d-block d-sm-inline-block">
                                            Add New Photo
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row row-cols-1 row-cols-md-6">
                                    @foreach ($galery as $image)
                                        <div class="col mb-4">
                                            <div class="card h-100">
                                                <div class="p-3">
                                                    <img src="{{ $image->url }}" class="card-img-top">
                                                </div>
                                                <div class="card-body">
                                                </div>
                                                <div class="card-footer text-right">
                                                    <form
                                                        action="{{ route('event-galery.destroy', ['event_galery' => $image->id]) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="delete-galery btn btn-xs btn-danger"
                                                            title="Delete">
                                                            <i class="far fa-trash-alt fa-fw"></i> Remove
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
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

            $('.delete-galery').click(function(e) {
                var dis = this;
                e.preventDefault();
                Swal.fire({
                    title: 'Delete Photo ?',
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
