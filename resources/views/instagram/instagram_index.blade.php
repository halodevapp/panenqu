<?php $page_title = 'Instagram'; ?>

@extends('layouts.main')

@section('page-title', $page_title)

@section('page-css')
    <style>
        .instagram-link {
            text-decoration: none;
            color: black;
        }

        .instagram-link:hover {
            text-decoration: none;
            color: black;
        }
    </style>
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
                            <div class="card-header py-2">
                                <div class="row">
                                    <div class="col-lg-6 my-1">
                                        <a href="{{ route('config.instagram.redirect') }}"
                                            class="btn btn-primary btn-sm d-block d-sm-inline-block">
                                            <i class="fab fa-instagram fa-fw"></i> Connect To Instagram
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                @if ($data['success'] == false)
                                    <p class="text-center">No Instagram Connected</p>
                                @else
                                    <div class="row row-cols-1 row-cols-md-4">
                                        @foreach ($data['instagram']->data as $ig)
                                            @php
                                                $caption = '';
                                                if (property_exists($ig, 'caption')) {
                                                    $caption = $ig->caption;
                                                }
                                                
                                                $thumbnail = $ig->media_type == 'VIDEO' ? $ig->thumbnail_url : $ig->media_url;
                                            @endphp
                                            <div class="col mb-4">
                                                <a class="instagram-link" href="{{ $ig->permalink }}" target="_blank">
                                                    <div class="card h-100">
                                                        <img src="{{ $thumbnail }}" class="card-img-top">
                                                        <div class="card-body">
                                                            <h5 class="text-bold">{{ $ig->username }}</h5>
                                                            <p class="card-text">{{ Str::limit($caption, 100) }}</p>
                                                        </div>
                                                        <div class="card-footer">
                                                            <small
                                                                class="text-muted">{{ date('d-m-Y', strtotime($ig->timestamp)) }}</small>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-js')
    <script>
        $(function() {
            var responseMessage = $('meta[name="response-message"]').attr('content');
            var responseStatus = $('meta[name="response-status"]').attr('content');

            if (responseMessage.trim() != '') {

                if (responseStatus == 'success') {
                    toastr.success(responseMessage)
                } else {
                    toastr.error(responseMessage)

                }
            }

            $('.delete_user').click(function(e) {
                var dis = this;
                e.preventDefault();
                Swal.fire({
                    title: 'Delete User ?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Delete',
                    buttonsStyling: false,
                    allowOutsideClick: false,
                    customClass: {
                        confirmButton: 'btn btn-danger mr-3',
                        cancelButton: 'btn btn-secondary'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        showLoading();
                        $(dis).parents('form').submit();
                    }
                })
            })
        })
    </script>
@endsection
