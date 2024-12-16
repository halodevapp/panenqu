<?php $page_title = 'Formulir Kemitraan'; ?>

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
                                    <div class="col-lg-6 my-1">
                                        <a href="{{ route('mitra-form.export') }}" target="_blank"
                                            class="btn btn-outline-success btn-sm d-block d-sm-inline-block">
                                            <i class="fas fa-download fa-fw"></i>
                                            Export .xlsx
                                        </a>
                                    </div>
                                    <div class="col-lg-6 my-1">
                                        <form
                                            action="{{ route('mitra-form.index', ['search' => request()->get('search')]) }}"
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
                                    <table id="table-mitra-forms" class="table table-bordered table-hover table-sm">
                                        <thead>
                                            <tr>
                                                <th class="text-center w70px">#</th>
                                                <th>Nama</th>
                                                <th>No HP</th>
                                                <th>Email</th>
                                                <th>Date Submit</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($mitraForm as $form)
                                                <tr>
                                                    <td class="text-center">
                                                        <a href="{{ route('mitra-form.edit', ['mitra_form' => $form->id]) }}"
                                                            class="btn btn-xs btn-success" title="Edit">
                                                            <i class="far fa-edit fa-fw"></i>
                                                        </a>
                                                    </td>
                                                    <td>{{ $form->name }}</td>
                                                    <td>{{ $form->phone }}</td>
                                                    <td>{{ $form->email }}</td>
                                                    <td>{{ $form->created_at }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-2 float-right">
                                    {{ $mitraForm->onEachSide(2)->appends(['search' => request()->get('search')])->links() }}
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
