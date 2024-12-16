<?php $page_title = 'Create Product'; ?>

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
                            <li class="breadcrumb-item"><a href="{{ route('product.index') }}">Master Product</a></li>
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
                        <form id="product_form" action="{{ route('product.store') }}" class="form-horizontal" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="card card-primary card-outline card-outline-tabs">
                                <div class="card-header p-0 border-bottom-0">
                                    <ul class="nav nav-tabs nav-fill" id="product-tab-header" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="product-form-tab" data-toggle="pill"
                                                href="#product-form" role="tab" aria-controls="product-form"
                                                aria-selected="true">Form</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="product-media-tab" data-toggle="pill"
                                                href="#product-media" role="tab" aria-controls="product-media"
                                                aria-selected="false">Media</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content" id="product-tab-body">
                                        <div class="tab-pane fade active show" id="product-form" role="tabpanel"
                                            aria-labelledby="product-form-tab">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="form-group row">
                                                        <label for="product_name" class="col-sm-2 col-form-label">Product
                                                            Name</label>
                                                        <div class="col-sm-10">
                                                            <input type="text"
                                                                class="form-control @error('product_name') is-invalid @enderror"
                                                                id="product_name" name="product_name"
                                                                placeholder="Product Name"
                                                                value="{{ old('product_name') }}">
                                                            <span id="product_name_error" class="error invalid-feedback">
                                                                @error('product_name')
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
                                                        <label for="product_category"
                                                            class="col-sm-2 col-form-label">Product
                                                            Category</label>
                                                        <div class="col-sm-10">
                                                            <select id="product_category" name="product_category"
                                                                class="form-control select2 select2-hidden-accessible @error('product_category') is-invalid @enderror"
                                                                style="width: 100%;" aria-hidden="true">
                                                                <option value="">Choose Product Category</option>
                                                                @foreach ($categories as $category)
                                                                    <option value="{{ $category->id }}"
                                                                        {{ old('product_category') == $category->id ? 'selected' : '' }}>
                                                                        {{ $category->category_name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <span id="product_category" class="error invalid-feedback">
                                                                @error('product_category')
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
                                                        <label for="sequence" class="col-sm-2 col-form-label">Product
                                                            Sequence</label>
                                                        <div class="col-sm-10">
                                                            <input type="text"
                                                                class="form-control @error('sequence') is-invalid @enderror"
                                                                id="sequence" name="sequence"
                                                                placeholder="Product Sequence"
                                                                value="{{ old('sequence') }}">
                                                            <span id="sequence_error" class="error invalid-feedback">
                                                                @error('sequence')
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
                                                        <label for="product_description"
                                                            class="col-sm-2 col-form-label">Product
                                                            Description</label>
                                                        <div class="col-sm-10">
                                                            <textarea class="form-control @error('product_description') is-invalid @enderror" id="product_description"
                                                                name="product_description" placeholder="Product Description" rows="5">{{ old('product_description') }}</textarea>
                                                            <span id="product_description_error"
                                                                class="error invalid-feedback">
                                                                @error('product_description')
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
                                                        <label for="answer" class="col-sm-2 col-form-label">Marketplace
                                                            Link</label>
                                                        <div class="col-sm-10">
                                                            <table id="table-mitra-faq"
                                                                class="table table-bordered table-hover table-sm">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="w-25">Marketplace</th>
                                                                        <th>Link</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($stores as $store)
                                                                        <tr>
                                                                            <td>
                                                                                <input type="hidden"
                                                                                    value="{{ $store->id }}"
                                                                                    name="marketplace[{{ $store->id }}]">
                                                                                {{ $store->store_name }}
                                                                            </td>
                                                                            <td>
                                                                                <input type="text"
                                                                                    class="form-control @error('marketplace_link') is-invalid @enderror"
                                                                                    id="marketplace_link"
                                                                                    name="marketplace_link[{{ $store->id }}]"
                                                                                    placeholder="Market Place Link"
                                                                                    value="{{ old("marketplace_link.$store->id") }}">
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="product-media" role="tabpanel"
                                            aria-labelledby="product-media-tab" style="min-height: 250px">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="form-group row">
                                                        <label for="product_image" class="col-sm-2 col-form-label">Product
                                                            Images</label>
                                                        <div class="col-sm-10">
                                                            <div class="custom-file">
                                                                <input type="file"
                                                                    class="custom-file-input @error('product_image') is-invalid @enderror"
                                                                    data-index="0" name="product_image[]">
                                                                <label class="custom-file-label"
                                                                    for="product_image">Choose
                                                                    file</label>
                                                                <span id="product_image_error"
                                                                    class="error invalid-feedback">
                                                                    @error('product_image')
                                                                        {{ $message }}
                                                                    @enderror
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="product_image_preview" class="row row-cols-2 row-cols-md-4">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <a href="{{ route('product.index') }}" class="btn btn-secondary">Back</a>
                                    <button id="submit_product" type="submit"
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
            $('#product_category').select2({});

            $('#submit_product').click(function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Simpan Product ?',
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
                        $('#submit_product').parents('form').submit();
                    }
                })
            });
        });

        $(function() {

            CKEDITOR.replace('product_description', {
                removePlugins: "exportpdf"
            });

        });


        $(function() {
            var indexImages = 0;
            var isMultiple = true;

            $(`[name="product_image[]"][data-index="${indexImages}"]`).on('change', function() {
                readURL(this);

                if (isMultiple == false) {
                    $('#product_image_preview').html('');
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
                    <label class="custom-file-label" for="product_image">Choose file</label>
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
                    <label class="custom-file-label" for="product_image">Choose file</label>
                `);

                $(obj).addClass('d-none');
                $(obj).next('label.custom-file-label').addClass('d-none');

            }

            var reader = new FileReader();
            reader.onload = function(e) {

                $('#product_image_preview').append(`
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

            $('#product_image_preview').on('click', '.remove_preview', function() {
                var removeIndex = $(this).attr('data-index');

                $(this).parents(':eq(2)').remove()
                $(`[name="product_image[]"][data-index="${removeIndex}"]`).val('');
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

                $('#product-tab-header .nav-link').removeClass('active');
                $('#product-tab-body .tab-pane').removeClass('active show');

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
