<?php $page_title = 'Edit Product Category'; ?>

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
                            <li class="breadcrumb-item"><a href="{{ route('product-category.index') }}">Master Product
                                    Category</a></li>
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
                        <form id="product_category_form"
                            action="{{ route('product-category.update', ['product_category' => $productCategory->id]) }}"
                            class="form-horizontal" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('patch')
                            <div class="card card-primary card-outline card-outline-tabs">
                                <div class="card-header p-0 border-bottom-0">
                                    <ul class="nav nav-tabs nav-fill" id="product-category-tab-header" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="product-category-form-tab" data-toggle="pill"
                                                href="#product-category-form" role="tab"
                                                aria-controls="product-category-form" aria-selected="true">Form</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="product-category-media-tab" data-toggle="pill"
                                                href="#product-category-media" role="tab"
                                                aria-controls="product-category-media" aria-selected="false">Media</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content" id="product-category-tab-body">
                                        <div class="tab-pane fade active show" id="product-category-form" role="tabpanel"
                                            aria-labelledby="product-category-form-tab">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="form-group row">
                                                        <label for="category_name" class="col-sm-2 col-form-label">Category
                                                            Name</label>
                                                        <div class="col-sm-10">
                                                            <input type="text"
                                                                class="form-control @error('category_name') is-invalid @enderror"
                                                                id="category_name" name="category_name"
                                                                placeholder="Category Name"
                                                                value="{{ old('category_name', $productCategory->category_name) }}">
                                                            <span id="category_name_error" class="error invalid-feedback">
                                                                @error('category_name')
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
                                                        <label for="category_description"
                                                            class="col-sm-2 col-form-label">Category
                                                            Description</label>
                                                        <div class="col-sm-10">
                                                            <textarea class="form-control @error('category_description') is-invalid @enderror" id="category_description"
                                                                name="category_description" placeholder="Category Description" rows="5">{{ old('category_description', $productCategory->category_description) }}</textarea>
                                                            <span id="category_description_error"
                                                                class="error invalid-feedback">
                                                                @error('category_description')
                                                                    {{ $message }}
                                                                @enderror
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="product-category-media" role="tabpanel"
                                            aria-labelledby="product-category-media-tab" style="min-height: 250px">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="form-group row">
                                                        <label for="category_image" class="col-sm-2 col-form-label">Category
                                                            Images</label>
                                                        <div class="col-sm-10">
                                                            <div class="custom-file">
                                                                <input type="file"
                                                                    class="custom-file-input @error('category_image') is-invalid @enderror"
                                                                    data-index="0" name="category_image[]">
                                                                <label class="custom-file-label" for="category_image">Choose
                                                                    file</label>
                                                                <span id="category_image_error"
                                                                    class="error invalid-feedback">
                                                                    @error('category_image')
                                                                        {{ $message }}
                                                                    @enderror
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="category_image_preview" class="row row-cols-2 row-cols-md-4">
                                                @foreach ($productCategory->images as $image)
                                                    <div class="col mb-4">
                                                        <div class="card h-100">
                                                            <img src="{{ $image->url }}" class="card-img-top">
                                                            <div class="card-body">
                                                                <p class="card-text">{{ $image->media_name }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <a href="{{ route('product-category.index') }}" class="btn btn-secondary">Back</a>
                                    <button id="submit_product_category" type="submit"
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

            $('#submit_product_category').click(function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Simpan Product Category ?',
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
                        $('#submit_product_category').parents('form').submit();
                    }
                })
            });
        });

        $(function() {

            CKEDITOR.replace('category_description', {
                removePlugins: "exportpdf"
            });

        });


        $(function() {
            var indexImages = 0;
            var isMultiple = false;

            $(`[name="category_image[]"][data-index="${indexImages}"]`).on('change', function() {
                readURL(this);

                if (isMultiple == false) {
                    $('#category_image_preview').html('');
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
                    <label class="custom-file-label" for="category_image">Choose file</label>
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
                    <label class="custom-file-label" for="category_image">Choose file</label>
                `);

                $(obj).addClass('d-none');
                $(obj).next('label.custom-file-label').addClass('d-none');

            }

            var reader = new FileReader();
            reader.onload = function(e) {
                $($.parseHTML(`
                        <div class="col mb-4">
                            <div class="card h-100">
                            <img src="${e.target.result}" class="card-img-top">
                            <div class="card-body">
                                <p class="card-text">${e.target.fileName}</p>
                                <a data-index="${e.target.index}" class="btn btn-xs btn-danger remove_preview">Remove</a>
                            </div>
                            </div>
                        </div>
                    `))
                    .appendTo('#category_image_preview');
            }

            function readURL(input) {
                if (input.files && input.files[0]) {
                    reader.fileName = input.files[0].name;
                    reader.index = $(input).attr('data-index');
                    reader.readAsDataURL(input.files[0]);
                }
            }

            $('#category_image_preview').on('click', '.remove_preview', function() {
                var removeIndex = $(this).attr('data-index');

                $(this).parents(':eq(2)').remove()
                $(`[name="category_image[]"][data-index="${removeIndex}"]`).val('');
            });

        });

        $(function() {
            var isInvalid = $('.is-invalid').length;
            if (isInvalid > 0) {
                var currentPaneID = '';
                $('.is-invalid').each(function(i, el) {
                    $(el).parents('div.tab-pane').addClass('tabBodyHasError');

                    var tabPaneID = $(el).parents('div.tab-pane').attr('id');
                    if (currentPaneID != tabPaneID) {
                        $(`[href="#${tabPaneID}"]`).addClass('tabHeaderHasError');
                        $(`[href="#${tabPaneID}"]`).append(`
                            <span class="badge badge-danger float-right">Has Error</span>
                        `);
                    }
                    currentPaneID = tabPaneID;
                });

                $('#product-category-tab-header .nav-link').removeClass('active');
                $('#product-category-tab-body .tab-pane').removeClass('active show');

                $('.tabHeaderHasError').each(function(i, el) {
                    $(el).addClass('active');
                    return false;
                });

                $('.tabBodyHasError').each(function(i, el) {
                    $(el).addClass('active show');
                    return false;
                });
            }
        });
    </script>
@endsection
