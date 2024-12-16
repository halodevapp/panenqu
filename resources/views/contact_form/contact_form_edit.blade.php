<?php $page_title = 'Edit Contact Form'; ?>

@extends('layouts.main')

@section('page-title', $page_title)

@section('page-css')

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
                            <li class="breadcrumb-item"><a href="{{ route('contact-form.index') }}">Contact Form</a>
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
                            <form action="{{ route('contact-form.update', ['contact_form' => $contactForm->id]) }}"
                                class="form-horizontal" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('patch')
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group row">
                                                <label for="category_name" class="col-sm-2 col-form-label">Category</label>
                                                <div class="col-sm-10">
                                                    <label id="category_name"
                                                        class="col-form-label">{{ $contactForm->category->category_name }}</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="form-group row">
                                                        <label for="created_at" class="col-sm-2 col-form-label">Date
                                                            Submit</label>
                                                        <div class="col-sm-10">
                                                            <label id="created_at"
                                                                class="col-form-label">{{ $contactForm->created_at }}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="name" class="col-sm-2 col-form-label">Nama</label>
                                                <div class="col-sm-10">
                                                    <input type="text"
                                                        class="form-control @error('name') is-invalid @enderror"
                                                        id="name" name="name" placeholder="Nama"
                                                        value="{{ old('name', $contactForm->name) }}">
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
                                                <label for="email" class="col-sm-2 col-form-label">Email</label>
                                                <div class="col-sm-10">
                                                    <input type="text"
                                                        class="form-control @error('email') is-invalid @enderror"
                                                        id="email" name="email" placeholder="Email"
                                                        value="{{ old('email', $contactForm->email) }}">
                                                    <span id="email_error" class="error invalid-feedback">
                                                        @error('email')
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
                                                <label for="subject" class="col-sm-2 col-form-label">Subject</label>
                                                <div class="col-sm-10">
                                                    <input type="text"
                                                        class="form-control @error('subject') is-invalid @enderror"
                                                        id="subject" name="subject" placeholder="Subject"
                                                        value="{{ old('subject', $contactForm->subject) }}">
                                                    <span id="subject_error" class="error invalid-feedback">
                                                        @error('subject')
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
                                                <label for="message" class="col-sm-2 col-form-label">Message</label>
                                                <div class="col-sm-10">
                                                    <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message"
                                                        placeholder="Message" rows="5">{{ old('message', $contactForm->message) }}</textarea>
                                                    <span id="message_error" class="error invalid-feedback">
                                                        @error('message')
                                                            {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <a href="{{ route('contact-form.index') }}" class="btn btn-secondary">Back</a>
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

@endsection
