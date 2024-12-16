<?php $page_title = 'Create FAQ'; ?>

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
                            <li class="breadcrumb-item"><a href="{{ route('mitra-faq.index') }}">FAQ</a></li>
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
                            <form action="{{ route('mitra-faq.store') }}" class="form-horizontal" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group row">
                                                <label for="question" class="col-sm-2 col-form-label">Question</label>
                                                <div class="col-sm-10">
                                                    <textarea class="form-control @error('question') is-invalid @enderror" id="question" name="question"
                                                        placeholder="Question" rows="5">{{ old('question') }}</textarea>
                                                    <span id="question_error" class="error invalid-feedback">
                                                        @error('question')
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
                                                <label for="answer" class="col-sm-2 col-form-label">Answer</label>
                                                <div class="col-sm-10">
                                                    <textarea class="form-control @error('answer') is-invalid @enderror" id="answer" name="answer" placeholder="answer"
                                                        rows="5">{{ old('answer') }}</textarea>
                                                    <span id="answer_error" class="error invalid-feedback">
                                                        @error('answer')
                                                            {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <a href="{{ route('mitra-faq.index') }}" class="btn btn-secondary">Back</a>
                                    <button id="submit_mitra_faq" type="submit"
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
            $('#submit_mitra_faq').click(function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Simpan FAQ ?',
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
                        $('#submit_mitra_faq').parents('form').submit();
                    }
                })
            });
        });
    </script>
@endsection
