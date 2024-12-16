<?php $page_title = 'Testimoni'; ?>

@extends('layouts.main')

@section('page-title', $page_title)

@section('page-css')
    <link rel="stylesheet" href="/assets/kartik-v-bootstrap-star-rating/css/star-rating.min.css">
    <link rel="stylesheet" href="/assets/kartik-v-bootstrap-star-rating/themes/krajee-fas/theme.min.css">
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
                                        <a href="{{ route('testimoni.create') }}"
                                            class="btn btn-primary btn-sm d-block d-sm-inline-block">
                                            Create New Testimoni
                                        </a>
                                    </div>
                                    <div class="col-lg-4 offset-lg-2 my-1">
                                        <form
                                            action="{{ route('testimoni.index', ['search' => request()->get('search')]) }}"
                                            method="GET">
                                            <div class="input-group input-group-sm">
                                                <input type="search" id="search" class="form-control" name="search"
                                                    placeholder="Search" value="{{ request()->get('search') }}">
                                                <span class="input-group-append">
                                                    <button type="submit" id="btn-search"
                                                        class="btn btn-primary btn-flat">Search</button>
                                                </span>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="table-testimoni" class="table table-bordered table-hover table-sm">
                                        <thead>
                                            <tr>
                                                <th class="text-center w70px">#</th>
                                                <th>Nama</th>
                                                <th>Pekerjaan</th>
                                                <th class="w-25">Testimoni</th>
                                                <th>Rating</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($testimonis as $testimoni)
                                                <tr>
                                                    <td class="text-center">
                                                        <a href="{{ route('testimoni.edit', ['testimoni' => $testimoni->id]) }}"
                                                            class="btn btn-xs btn-success" title="Edit">
                                                            <i class="far fa-edit fa-fw"></i>
                                                        </a>
                                                        <form
                                                            action="{{ route('testimoni.destroy', ['testimoni' => $testimoni->id]) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('delete')
                                                            <button type="submit"
                                                                class="delete_testimoni btn btn-xs btn-danger"
                                                                title="Delete">
                                                                <i class="far fa-trash-alt fa-fw"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                    <td>{{ $testimoni->name }}</td>
                                                    <td>{{ $testimoni->profession }}</td>
                                                    <td>{{ $testimoni->testimoni }}</td>
                                                    <td>
                                                        <input value="{{ $testimoni->rating }}" data-size="xs"
                                                            class="testimoni-rating d-none">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-2 float-right">
                                    {{ $testimonis->onEachSide(2)->appends(['search' => request()->get('search')])->links() }}
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
    <script type="text/javascript" src="/assets/kartik-v-bootstrap-star-rating/js/star-rating.min.js"></script>
    <script type="text/javascript" src="/assets/kartik-v-bootstrap-star-rating/themes/krajee-fas/theme.min.js"></script>

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

            $('.delete_testimoni').click(function(e) {
                var dis = this;
                e.preventDefault();
                Swal.fire({
                    title: 'Delete Testimoni ?',
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
            });

            $('.testimoni-rating').rating({
                displayOnly: true,
                step: 1,
                theme: 'krajee-fas'
            });

        });
    </script>
@endsection
