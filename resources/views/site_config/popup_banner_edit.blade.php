<?php $page_title = 'Edit Pop Up Banner'; ?>

@extends('layouts.main')

@section('page-title', $page_title)

@section('page-css')
    <link rel="stylesheet" href="/assets/adminlte/plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
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
                            <li class="breadcrumb-item"><a href="{{ route('config.popup.index') }}">Pop Up Banner</a>
                            </li>
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
                            <form action="{{ route('config.popup.update', ['id' => $banner->id]) }}" class="form-horizontal"
                                method="POST" enctype="multipart/form-data" autocomplete="off">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group row">
                                                <label for="description" class="col-sm-2 col-form-label">Description</label>
                                                <div class="col-sm-10">
                                                    <input type="text"
                                                        class="form-control @error('description') is-invalid @enderror"
                                                        id="description" name="description" placeholder="Description"
                                                        value="{{ old('description', $banner->description) }}">
                                                    <span id="description_error" class="error invalid-feedback">
                                                        @error('description')
                                                            {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="start_date" class="col-sm-2 col-form-label">Start Date</label>
                                                <div class="col-sm-10">
                                                    <div class="input-group date" data-target-input="nearest">
                                                        <input id="start_date" type="text" name="start_date"
                                                            placeholder="yyyy-mm-dd"
                                                            class="form-control datetimepicker-input @error('start_date') is-invalid @enderror"
                                                            data-target="#start_date" data-toggle="datetimepicker"
                                                            value="{{ old('start_date', $banner->start_date) }}">
                                                        <div class="input-group-append" data-target="#start_date">
                                                            <div class="input-group-text"><i class="fa fa-calendar"></i>
                                                            </div>
                                                        </div>
                                                        <span id="start_date_error" class="error invalid-feedback">
                                                            @error('start_date')
                                                                {{ $message }}
                                                            @enderror
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="end_date" class="col-sm-2 col-form-label">End Date</label>
                                                <div class="col-sm-10">
                                                    <div class="input-group date" data-target-input="nearest">
                                                        <input id="end_date" type="text" name="end_date"
                                                            placeholder="yyyy-mm-dd"
                                                            class="form-control datetimepicker-input @error('end_date') is-invalid @enderror"
                                                            data-target="#end_date" data-toggle="datetimepicker"
                                                            value="{{ old('end_date', $banner->end_date) }}">
                                                        <div class="input-group-append" data-target="#end_date">
                                                            <div class="input-group-text"><i class="fa fa-calendar"></i>
                                                            </div>
                                                        </div>
                                                        <span id="end_date_error" class="error invalid-feedback">
                                                            @error('end_date')
                                                                {{ $message }}
                                                            @enderror
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="web_link" class="col-sm-2 col-form-label">Web Link</label>
                                                <div class="col-sm-10">
                                                    <input type="text"
                                                        class="form-control @error('web_link') is-invalid @enderror"
                                                        id="web_link" name="web_link" placeholder="Web Link"
                                                        value="{{ old('web_link', $banner->web_link) }}">
                                                    <span id="web_link_error" class="error invalid-feedback">
                                                        @error('web_link')
                                                            {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="image" class="col-sm-2 col-form-label">Image</label>
                                                <div class="col-sm-10">
                                                    <div class="custom-file">
                                                        <input id="image" name="image" type="file"
                                                            class="custom-file-input @error('image') is-invalid @enderror"
                                                            accept="image/png, image/jpeg,image/jpg">
                                                        <label class="custom-file-label" for="image">Choose
                                                            image</label>
                                                        <span id="image_error" class="error invalid-feedback">
                                                            @error('image')
                                                                {{ $message }}
                                                            @enderror
                                                        </span>
                                                    </div>
                                                    <div id="product_image_preview"
                                                        class="row row-cols-2 row-cols-md-12 mt-2">
                                                        <div class="col mb-4">
                                                            <div class="card h-100">
                                                                <img src="{{ $banner->image ? $banner->image->url : '' }}"
                                                                    class="card-img-top">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <a href="{{ route('config.popup.index') }}" class="btn btn-secondary">Back</a>
                                    <button id="submit_popup" type="submit"
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
    <script type="text/javascript" src="/assets/adminlte/plugins/moment/moment.min.js"></script>
    <script src="/assets/adminlte/plugins/daterangepicker/daterangepicker.js"></script>
    <script src="/assets/adminlte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <script>
        $(function() {
            $('#start_date').datetimepicker({
                format: 'YYYY-MM-DD'
            });

            $('#end_date').datetimepicker({
                format: 'YYYY-MM-DD'
            });
        })

        $(function() {
            $('#image').on('change', function() {
                readURL(this);
            });

            var reader = new FileReader();
            reader.onload = function(e) {

                $('#product_image_preview').html(`
                    <div class="col mb-4">
                        <div class="card h-100">
                        <img src="${e.target.result}" class="card-img-top">
                        <div class="card-body">
                            <p class="card-text">${e.target.fileName}</p>
                        </div>
                        </div>
                    </div>
                `);
            }

            function readURL(input) {
                if (input.files && input.files[0]) {
                    reader.fileName = input.files[0].name;
                    reader.index = $(input).attr('data-index');
                    reader.readAsDataURL(input.files[0]);
                }
            }
        })


        $(function() {
            $('#submit_popup').click(function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Update Pop Up ?',
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
                        $('#submit_popup').parents('form').submit();
                    }
                })
            });
        });
    </script>
@endsection
