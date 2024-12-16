<?php $page_title = 'Edit Article'; ?>

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
                            <li class="breadcrumb-item"><a href="{{ route('article.index') }}">Article</a></li>
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
                        <form id="article_form" action="{{ route('article.update', ['article' => $article->id]) }}"
                            class="form-horizontal" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('patch')
                            <div class="card card-primary card-outline card-outline-tabs">
                                <div class="card-header p-0 border-bottom-0">
                                    <ul class="nav nav-tabs nav-fill" id="article-tab-header" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="article-form-tab" data-toggle="pill"
                                                href="#article-form" role="tab" aria-controls="article-form"
                                                aria-selected="true">Form</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="article-media-tab" data-toggle="pill"
                                                href="#article-media" role="tab" aria-controls="article-media"
                                                aria-selected="false">Media</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content" id="article-tab-body">
                                        <div class="tab-pane fade active show" id="article-form" role="tabpanel"
                                            aria-labelledby="article-form-tab">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="form-group row">
                                                        <label for="article_title" class="col-sm-2 col-form-label">Article
                                                            Title</label>
                                                        <div class="col-sm-10">
                                                            <input type="text"
                                                                class="form-control @error('article_title') is-invalid @enderror"
                                                                id="article_title" name="article_title"
                                                                placeholder="Article Title"
                                                                value="{{ old('article_title', $article->article_title) }}">
                                                            <span id="article_title_error" class="error invalid-feedback">
                                                                @error('article_title')
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
                                                        <label for="article_category"
                                                            class="col-sm-2 col-form-label">Article
                                                            Category</label>
                                                        <div class="col-sm-10">
                                                            <select id="article_category" name="article_category"
                                                                class="form-control select2 select2-hidden-accessible @error('article_category') is-invalid @enderror"
                                                                style="width: 100%;" aria-hidden="true">
                                                                <option value="">Choose Article Category</option>
                                                                @foreach ($categories as $category)
                                                                    <option value="{{ $category->id }}"
                                                                        {{ old('article_category', $article->article_category) == $category->id ? 'selected' : '' }}>
                                                                        {{ $category->category_name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <span id="article_category" class="error invalid-feedback">
                                                                @error('article_category')
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
                                                        <label for="article_content" class="col-sm-2 col-form-label">Article
                                                            Content</label>
                                                        <div class="col-sm-10">
                                                            <textarea class="form-control @error('article_content') is-invalid @enderror" id="article_content"
                                                                name="article_content" placeholder="Article Description" rows="5">{{ old('article_content', $article->article_content) }}</textarea>
                                                            <span id="article_content_error" class="error invalid-feedback">
                                                                @error('article_content')
                                                                    {{ $message }}
                                                                @enderror
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="publish_date" class="col-sm-2 col-form-label">Publish
                                                    Date</label>
                                                <div class="col-sm-10">
                                                    <label id="publish_date"
                                                        class="col-form-label">{{ $article->publish_date }}</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="article-media" role="tabpanel"
                                            aria-labelledby="article-media-tab" style="min-height: 250px">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="form-group row">
                                                        <label for="article_image_thumbnail"
                                                            class="col-sm-6 col-form-label">Article
                                                            Thumbnail Image</label>
                                                        <div class="col-sm-5">
                                                            <div class="custom-file">
                                                                <input type="file"
                                                                    class="custom-file-input @error('article_image_thumbnail') is-invalid @enderror"
                                                                    data-index="0" name="article_image_thumbnail[]">
                                                                <label class="custom-file-label"
                                                                    for="article_image_thumbnail">Choose
                                                                    file</label>
                                                                <span id="article_image_thumbnail_error"
                                                                    class="error invalid-feedback">
                                                                    @error('article_image_thumbnail')
                                                                        {{ $message }}
                                                                    @enderror
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="article_image_thumbnail_preview"
                                                        class="row row-cols-1 row-cols-md-2">
                                                        @foreach ($article->thumbnail as $image)
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
                                                <div class="col-lg-6">
                                                    <div class="form-group row">
                                                        <label for="article_image_banner"
                                                            class="col-sm-6 col-form-label">Article
                                                            Banner Image</label>
                                                        <div class="col-sm-5">
                                                            <div class="custom-file">
                                                                <input type="file"
                                                                    class="custom-file-input @error('article_image_banner') is-invalid @enderror"
                                                                    data-index="0" name="article_image_banner[]">
                                                                <label class="custom-file-label"
                                                                    for="article_image_banner">Choose
                                                                    file</label>
                                                                <span id="article_image_banner_error"
                                                                    class="error invalid-feedback">
                                                                    @error('article_image_banner')
                                                                        {{ $message }}
                                                                    @enderror
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="article_image_banner_preview"
                                                        class="row row-cols-1 row-cols-md-2">
                                                        @foreach ($article->banner as $image)
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
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <a href="{{ route('article.index') }}" class="btn btn-secondary">Back</a>
                                    <input id="submit" type="submit" name="submit" class="d-none">
                                    <button id="publish" type="button"
                                        class="ml-1 btn btn-primary float-right {{ $article->publish_date != '' ? 'd-none' : '' }}">Save
                                        and Publish</button>
                                    <button id="draft" type="button"
                                        class="ml-1 btn btn-success float-right {{ $article->publish_date != '' ? 'd-none' : '' }}">Draft</button>
                                    <button id="unpublish" type="button"
                                        class="btn btn-danger float-right {{ $article->publish_date != '' ? '' : 'd-none' }}">Unpublish</button>
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
            $('#article_category').select2({});

            var isPublish = "{{ $article->publish_date }}";

            if (isPublish != '') {
                $("#article_form").each(function() {
                    $(this).find(':input').each(function() {
                        console.log($(this).hasClass('select2'));
                        if ($(this).hasClass('select2')) {
                            $(this).select2({
                                'disabled': true
                            });
                        }
                        $(this).attr('readonly', true)
                    })

                });
            }

            $('#unpublish').click(function() {
                var articleID = "{{ $article->id }}";
                Swal.fire({
                    title: 'Unpublish Article ?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'OK',
                    buttonsStyling: false,
                    allowOutsideClick: false,
                    customClass: {
                        confirmButton: 'btn btn-success mr-3',
                        cancelButton: 'btn btn-danger'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        showLoading();
                        $.ajax({
                            url: "{{ route('article.unpublish') }}",
                            data: {
                                id: articleID
                            },
                            type: 'POST',
                            dataType: 'JSON',
                            complete: function(data) {
                                var res = data.responseJSON;
                                Swal.fire({
                                    title: res.message,
                                    icon: res.success ? 'success' : 'error',
                                    confirmButtonText: 'OK',
                                    buttonsStyling: false,
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    customClass: {
                                        confirmButton: 'btn btn-success'
                                    }
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload()
                                    }
                                })
                            }
                        })
                    }
                })
            })

            $('#draft,#publish').click(function(e) {
                var id = $(this).attr('id');
                $('#submit').val(id);

                var message = id == 'draft' ? 'Save as Draft ?' : 'Save and Publish ?';
                Swal.fire({
                    title: message,
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
                        $('#submit').click();
                    }
                })
            });
        });

        $(function() {

            CKEDITOR.replace('article_content', {
                removePlugins: "exportpdf"
            });

        });


        $(function() {
            var indexImages = 0;
            var isMultiple = false;

            $(`[name="article_image_thumbnail[]"][data-index="${indexImages}"]`).on('change', function() {
                readURL(this);

                if (isMultiple == false) {
                    $('#article_image_thumbnail_preview').html('');
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
                    <label class="custom-file-label" for="article_image_thumbnail">Choose file</label>
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
                    <label class="custom-file-label" for="article_image_thumbnail">Choose file</label>
                `);

                $(obj).addClass('d-none');
                $(obj).next('label.custom-file-label').addClass('d-none');

            }

            var reader = new FileReader();
            reader.onload = function(e) {

                $('#article_image_thumbnail_preview').append(`
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

            $('#article_image_thumbnail_preview').on('click', '.remove_preview', function() {
                var removeIndex = $(this).attr('data-index');

                $(this).parents(':eq(2)').remove()
                $(`[name="article_image_thumbnail[]"][data-index="${removeIndex}"]`).val('');
            });

        });

        $(function() {
            var indexImages = 0;
            var isMultiple = false;

            $(`[name="article_image_banner[]"][data-index="${indexImages}"]`).on('change', function() {
                readURL(this);

                if (isMultiple == false) {
                    $('#article_image_banner_preview').html('');
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
                    <label class="custom-file-label" for="article_image_banner">Choose file</label>
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
                    <label class="custom-file-label" for="article_image_banner">Choose file</label>
                `);

                $(obj).addClass('d-none');
                $(obj).next('label.custom-file-label').addClass('d-none');

            }

            var reader = new FileReader();
            reader.onload = function(e) {

                $('#article_image_banner_preview').append(`
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

            $('#article_image_banner_preview').on('click', '.remove_preview', function() {
                var removeIndex = $(this).attr('data-index');

                $(this).parents(':eq(2)').remove()
                $(`[name="article_image_banner[]"][data-index="${removeIndex}"]`).val('');
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

                $('#article-tab-header .nav-link').removeClass('active');
                $('#article-tab-body .tab-pane').removeClass('active show');

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
