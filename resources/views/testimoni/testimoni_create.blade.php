<?php $page_title = 'Create Testimoni'; ?>

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
                            <li class="breadcrumb-item"><a href="{{ route('testimoni.index') }}">Testimoni</a></li>
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
                            <form action="{{ route('testimoni.store') }}" class="form-horizontal" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group row">
                                                <label for="name" class="col-sm-2 col-form-label">Nama</label>
                                                <div class="col-sm-10">
                                                    <input type="text"
                                                        class="form-control @error('name') is-invalid @enderror"
                                                        id="name" name="name" placeholder="Nama"
                                                        value="{{ old('name') }}">
                                                    <span id="name_error" class="error invalid-feedback">
                                                        @error('name')
                                                            {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group row">
                                                <label for="profession" class="col-sm-2 col-form-label">Pekerjaan</label>
                                                <div class="col-sm-10">
                                                    <input type="text"
                                                        class="form-control @error('profession') is-invalid @enderror"
                                                        id="profession" name="profession" placeholder="Pekerjaan"
                                                        value="{{ old('profession') }}">
                                                    <span id="profession_error" class="error invalid-feedback">
                                                        @error('profession')
                                                            {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group row">
                                                <label for="testimoni" class="col-sm-2 col-form-label">Testimoni</label>
                                                <div class="col-sm-10">
                                                    <textarea class="form-control @error('testimoni') is-invalid @enderror" id="testimoni" name="testimoni"
                                                        placeholder="Testimoni" rows="5">{{ old('testimoni') }}</textarea>
                                                    <span id="testimoni_error" class="error invalid-feedback">
                                                        @error('testimoni')
                                                            {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group row">
                                                <label for="rating" class="col-sm-2 col-form-label">Rating</label>
                                                <div class="col-sm-10">
                                                    <input type="text"
                                                        class="form-control testimoni-rating rating-loading @error('rating') is-invalid @enderror"
                                                        id="rating" name="rating" placeholder="Rating" data-size="sm"
                                                        value="{{ old('rating', 4) }}">
                                                    <span id="rating_error" class="error invalid-feedback">
                                                        @error('rating')
                                                            {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <a href="{{ route('testimoni.index') }}" class="btn btn-secondary">Back</a>
                                    <button id="submit_testimoni" type="submit"
                                        class="btn btn-primary float-right">Save</button>
                                </div>

                            </form>
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

            $('.testimoni-rating').rating({
                step: 1,
                theme: 'krajee-fas'
            });

            $('#submit_testimoni').click(function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Simpan Testimoni ?',
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
                        $('#submit_testimoni').parents('form').submit();
                    }
                })
            });
        });
    </script>
@endsection
