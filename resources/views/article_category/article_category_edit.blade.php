<?php $page_title = 'Create Article Category'; ?>

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
                            <li class="breadcrumb-item"><a href="{{ route('article-category.index') }}">Master Article
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
                        <div class="card">
                            <form
                                action="{{ route('article-category.update', ['article_category' => $articleCategory->id]) }}"
                                class="form-horizontal" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('patch')
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group row">
                                                <label for="category_name" class="col-sm-2 col-form-label">Category
                                                    Name</label>
                                                <div class="col-sm-10">
                                                    <input type="text"
                                                        class="form-control @error('category_name') is-invalid @enderror"
                                                        id="category_name" name="category_name" placeholder="Category Name"
                                                        value="{{ old('category_name', $articleCategory->category_name) }}">
                                                    <span id="category_name_error" class="error invalid-feedback">
                                                        @error('category_name')
                                                            {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <a href="{{ route('article-category.index') }}" class="btn btn-secondary">Back</a>
                                    <button id="submit_article_category" type="submit"
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
    <script type="text/javascript" src="/assets/adminlte/plugins/select2/js/select2.full.min.js"></script>
    <script>
        $(function() {
            $('#submit_article_category').click(function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Update Article Category ?',
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
                        $('#submit_article_category').parents('form').submit();
                    }
                })
            });
        });
    </script>
@endsection
