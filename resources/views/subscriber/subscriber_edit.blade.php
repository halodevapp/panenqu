<?php $page_title = 'Edit Subscriber'; ?>

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
                            <li class="breadcrumb-item"><a href="{{ route('subscriber.index') }}">Subscriber</a>
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
                            <form action="{{ route('subscriber.update', ['subscriber' => $subscriber->id]) }}"
                                class="form-horizontal" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('patch')
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group row">
                                                <label for="name" class="col-sm-2 col-form-label">Email</label>
                                                <div class="col-sm-10">
                                                    <input type="text"
                                                        class="form-control @error('name') is-invalid @enderror"
                                                        id="name" name="name" placeholder="Email"
                                                        value="{{ old('name', $subscriber->email) }}">
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
                                                <label for="subscribe_date" class="col-sm-2 col-form-label">Subscribe
                                                    Date</label>
                                                <div class="col-sm-10">
                                                    <label id="subscribe_date"
                                                        class="col-form-label">{{ $subscriber->subscribe_date }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group row">
                                                <label for="usubscribe_date" class="col-sm-2 col-form-label">Unsubscribe
                                                    Date</label>
                                                <div class="col-sm-10">
                                                    <label id="usubscribe_date"
                                                        class="col-form-label">{{ $subscriber->usubscribe_date }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <a href="{{ route('subscriber.index') }}" class="btn btn-secondary">Back</a>
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
