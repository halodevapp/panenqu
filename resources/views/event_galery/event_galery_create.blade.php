<?php $page_title = 'Event Galery Add Photo'; ?>

@extends('layouts.main')

@section('page-title', $page_title)

@section('page-css')
    <link rel="stylesheet" href="/assets/adminlte/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
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
                            <li class="breadcrumb-item"><a href="{{ route('event-galery.index') }}">Event Galery</a></li>
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
                        <form id="product_form" action="{{ route('event-galery.store') }}" class="form-horizontal"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group row">
                                                <label for="galery_image" class="col-sm-2 col-form-label">Photo</label>
                                                <div class="col-sm-10">
                                                    <div class="custom-file">
                                                        <input type="file"
                                                            class="custom-file-input @error('galery_image') is-invalid @enderror"
                                                            data-index="0" name="galery_image[]"
                                                            accept="image/png, image/webp, image/jpeg">
                                                        <label class="custom-file-label" for="galery_image">Choose
                                                            file</label>
                                                        <span id="galery_image_error" class="error invalid-feedback">
                                                            @error('galery_image')
                                                                {{ $message }}
                                                            @enderror
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="galery_image_preview" class="row row-cols-2 row-cols-md-4">
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <a href="{{ route('event-galery.index') }}" class="btn btn-secondary">Back</a>
                                    <button id="submit_galery" type="submit"
                                        class="btn btn-primary float-right">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-js')
    <script type="text/javascript" src="/assets/adminlte/plugins/select2/js/select2.full.min.js"></script>
    <script type="text/javascript" src="/assets/ckeditor/ckeditor.js"></script>
    <script>
        $(function() {
            $('#submit_galery').click(function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Add Photo Galery ?',
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
                        $('#submit_galery').parents('form').submit();
                    }
                })
            });
        });


        $(function() {
            var indexImages = 0;
            var isMultiple = true;

            $(`[name="galery_image[]"][data-index="${indexImages}"]`).on('change', function() {
                readURL(this);

                if (isMultiple == false) {
                    $('#galery_image_preview').html('');
                    return true;
                }

                var inputFile = $(this).clone();
                var currentIndex = $(this).attr('data-index');
                indexImages = parseInt(currentIndex) + 1;

                $(inputFile[0]).val('');
                $(inputFile[0]).attr('data-index', indexImages);
                $(inputFile[0]).bind('change', function() {
                    categoryImageOnChangeListener(inputFile[0]);
                });

                $(this).before(inputFile);

                $(inputFile).after(`
                    <label class="custom-file-label" for="galery_image">Choose file</label>
                `);

                $(this).addClass('d-none');
                $(this).next('label.custom-file-label').addClass('d-none');

            });

            function categoryImageOnChangeListener(obj) {
                readURL(obj);

                var inputFile = $(obj).clone();
                var currentIndex = $(obj).attr('data-index');
                indexImages = parseInt(currentIndex) + 1;
                $(inputFile[0]).val('');
                $(inputFile[0]).attr('data-index', indexImages);

                $(inputFile[0]).bind('change', function() {
                    categoryImageOnChangeListener(inputFile[0]);
                })
                $(obj).before(inputFile);


                $(inputFile).after(`
                    <label class="custom-file-label" for="galery_image">Choose file</label>
                `);

                $(obj).addClass('d-none');
                $(obj).next('label.custom-file-label').addClass('d-none');

            }

            var reader = new FileReader();
            reader.onload = function(e) {

                $('#galery_image_preview').append(`
                    <div class="col mb-4">
                        <div class="card h-100">
                        <img src="${e.target.result}" class="card-img-top">
                        <div class="card-body">
                            <p class="card-text">${e.target.fileName}</p>
                            <a data-index="${e.target.index}" class="btn btn-xs btn-danger remove_preview">Remove</a>
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

            $('#galery_image_preview').on('click', '.remove_preview', function() {
                var removeIndex = $(this).attr('data-index');

                $(this).parents(':eq(2)').remove()
                $(`[name="galery_image[]"][data-index="${removeIndex}"]`).val('');
            });

        });
    </script>
@endsection
