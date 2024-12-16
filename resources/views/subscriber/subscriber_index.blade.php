<?php $page_title = 'Subscriber'; ?>

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
                                    <div class="col-lg-4 offset-lg-8 my-1">
                                        <form
                                            action="{{ route('subscriber.index', ['search' => request()->get('search')]) }}"
                                            method="GET">
                                            <div class="input-group input-group-sm">
                                                <input type="search" id="search" class="form-control" name="search"
                                                    placeholder="Search" value="{{ request()->get('search') }}">
                                                <span class="input-group-append">
                                                    <button type="submit" id="btn-search"
                                                        class="btn btn-primary btn-flat">Search</button>
                                                </span>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="table-subscriber-forms" class="table table-bordered table-hover table-sm">
                                        <thead>
                                            <tr>
                                                <th class="text-center w70px">#</th>
                                                <th>Email</th>
                                                <th>Tanggal Subscribe</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($subscribers as $subscriber)
                                                <tr>
                                                    <td class="text-center">
                                                        <a href="{{ route('subscriber.edit', ['subscriber' => $subscriber->id]) }}"
                                                            class="btn btn-xs btn-success" title="Edit">
                                                            <i class="far fa-edit fa-fw"></i>
                                                        </a>
                                                    </td>
                                                    <td>{{ $subscriber->email }}</td>
                                                    <td>{{ $subscriber->subscribe_date }}</td>
                                                    <td>
                                                        {{ $subscriber->unsubscribe_date ? 'Inactive' : 'Active' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-2 float-right">
                                    {{ $subscribers->onEachSide(2)->appends(['search' => request()->get('search')])->links() }}
                                </div>
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
        });
    </script>
@endsection
