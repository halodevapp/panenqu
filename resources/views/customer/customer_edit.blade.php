<?php $page_title = 'Edit Customer'; ?>

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
                            <li class="breadcrumb-item"><a href="{{ route('customer.index') }}">Master Customer</a></li>
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
                        <form id="customer_form" action="{{ route('customer.update', ['customer' => $customer->id]) }}"
                            class="form-horizontal" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('patch')
                            <div class="card card-primary card-outline card-outline-tabs">
                                <div class="card-header p-0 border-bottom-0">
                                    <ul class="nav nav-tabs nav-fill" id="customer-tab-header" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="customer-form-tab" data-toggle="pill"
                                                href="#customer-form" role="tab" aria-controls="customer-form"
                                                aria-selected="true">Form</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="customer-media-tab" data-toggle="pill"
                                                href="#customer-media" role="tab" aria-controls="customer-media"
                                                aria-selected="false">Media</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content" id="customer-tab-body">
                                        <div class="tab-pane fade active show" id="customer-form" role="tabpanel"
                                            aria-labelledby="customer-form-tab">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="form-group row">
                                                        <label for="customer_name" class="col-sm-2 col-form-label">Customer
                                                            Name</label>
                                                        <div class="col-sm-10">
                                                            <input type="text"
                                                                class="form-control @error('customer_name') is-invalid @enderror"
                                                                id="customer_name" name="customer_name"
                                                                placeholder="Customer Name"
                                                                value="{{ old('customer_name', $customer->customer_name) }}">
                                                            <span id="customer_name_error" class="error invalid-feedback">
                                                                @error('customer_name')
                                                                    {{ $message }}
                                                                @enderror
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="customer_category"
                                                            class="col-sm-2 col-form-label">Customer
                                                            Category</label>
                                                        <div class="col-sm-10">
                                                            <select id="customer_category" name="customer_category"
                                                                class="form-control select2 select2-hidden-accessible @error('customer_category') is-invalid @enderror"
                                                                style="width: 100%;" aria-hidden="true">
                                                                <option value="">Choose Customer Category</option>
                                                                @foreach ($categories as $category)
                                                                    <option value="{{ $category->id }}"
                                                                        {{ old('customer_category', $customer->customer_category) == $category->id ? 'selected' : '' }}>
                                                                        {{ $category->category_name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <span id="customer_category" class="error invalid-feedback">
                                                                @error('customer_category')
                                                                    {{ $message }}
                                                                @enderror
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="customer-media" role="tabpanel"
                                            aria-labelledby="customer-media-tab" style="min-height: 250px">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="form-group row">
                                                        <label for="customer_image" class="col-sm-2 col-form-label">Customer
                                                            Images</label>
                                                        <div class="col-sm-10">
                                                            <div class="custom-file">
                                                                <input type="file"
                                                                    class="custom-file-input @error('customer_image') is-invalid @enderror"
                                                                    data-index="0" name="customer_image[]">
                                                                <label class="custom-file-label" for="customer_image">Choose
                                                                    file</label>
                                                                <span id="customer_image_error"
                                                                    class="error invalid-feedback">
                                                                    @error('customer_image')
                                                                        {{ $message }}
                                                                    @enderror
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="customer_image_preview" class="row row-cols-2 row-cols-md-4">
                                                @foreach ($customer->images as $image)
                                                    <div class="col offset-2 mb-4">
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
                                    <a href="{{ route('customer.index') }}" class="btn btn-secondary">Back</a>
                                    <button id="submit_customer" type="submit"
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
    <script>
        $(function() {
            $('#customer_category').select2({});

            $('#submit_customer').click(function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Simpan Customer ?',
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
                        $('#submit_customer').parents('form').submit();
                    }
                })
            });
        });

        $(function() {
            var indexImages = 0;
            var isMultiple = false;

            $(`[name="customer_image[]"][data-index="${indexImages}"]`).on('change', function() {
                readURL(this);

                if (isMultiple == false) {
                    $('#customer_image_preview').html('');
                    return true;
                }

                var inputFile = $(this).clone();
                var currentIndex = $(this).attr('data-index');
                indexImages = parseInt(currentIndex) + 1;

                $(inputFile[0]).val('');
                $(inputFile[0]).attr('data-index', indexImages);
                $(inputFile[0]).bind('change', function() {
                    customerImageOnChangeListener(inputFile[0]);
                });

                $(this).before(inputFile);

                $(inputFile).after(`
                    <label class="custom-file-label" for="customer_image">Choose file</label>
                `);

                $(this).addClass('d-none');
                $(this).next('label.custom-file-label').addClass('d-none');

            });

            function customerImageOnChangeListener(obj) {
                readURL(obj);

                var inputFile = $(obj).clone();
                var currentIndex = $(obj).attr('data-index');
                indexImages = parseInt(currentIndex) + 1;
                $(inputFile[0]).val('');
                $(inputFile[0]).attr('data-index', indexImages);

                $(inputFile[0]).bind('change', function() {
                    customerImageOnChangeListener(inputFile[0]);
                })
                $(obj).before(inputFile);


                $(inputFile).after(`
                    <label class="custom-file-label" for="customer_image">Choose file</label>
                `);

                $(obj).addClass('d-none');
                $(obj).next('label.custom-file-label').addClass('d-none');

            }

            var reader = new FileReader();
            reader.onload = function(e) {
                $($.parseHTML(`
                        <div class="col offset-2 mb-4">
                            <div class="card h-100">
                            <img src="${e.target.result}" class="card-img-top">
                            <div class="card-body">
                                <p class="card-text">${e.target.fileName}</p>
                                <a data-index="${e.target.index}" class="btn btn-xs btn-danger remove_preview">Remove</a>
                            </div>
                            </div>
                        </div>
                    `))
                    .appendTo('#customer_image_preview');
            }

            function readURL(input) {
                if (input.files && input.files[0]) {
                    reader.fileName = input.files[0].name;
                    reader.index = $(input).attr('data-index');
                    reader.readAsDataURL(input.files[0]);
                }
            }

            $('#customer_image_preview').on('click', '.remove_preview', function() {
                var removeIndex = $(this).attr('data-index');

                $(this).parents(':eq(2)').remove()
                $(`[name="customer_image[]"][data-index="${removeIndex}"]`).val('');
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

                $('#customer-tab-header .nav-link').removeClass('active');
                $('#customer-tab-body .tab-pane').removeClass('active show');

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
