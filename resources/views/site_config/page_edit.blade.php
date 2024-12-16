<?php $page_title = "{$page->page_name} Config"; ?>

@extends('layouts.main')

@section('page-title', $page_title)

@section('page-css')
    <link rel="stylesheet" href="/assets/adminlte/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

    <style>
        div.dropdown-item:active {
            background-color: transparent !important;
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
                            <li class="breadcrumb-item"><a href="{{ route('config.page.index') }}">Page Config</a>
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
                            <div class="card-header">
                                <div class="row col-6">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-primary dropdown-toggle" type="button"
                                            data-toggle="dropdown" aria-expanded="false">
                                            Add Config Section
                                        </button>
                                        <div class="dropdown-menu">
                                            <div class="dropdown-item">
                                                <div class="input-group input-group-sm" style="width:300px !important">
                                                    <input type="text" id="section_name" class="form-control">
                                                    <span class="input-group-append">
                                                        <button type="button" class="btn btn-primary"
                                                            id="createSection">Create</button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body" style="height:60vh;max-height:60vh;overflow-y:auto">
                                @foreach ($page->sections as $section)
                                    <div class="card section" data-section-id="{{ $section->id }}"
                                        data-section-name="{{ $section->section_name }}">
                                        <div class="card-header">
                                            <div class="btn-group">
                                                <button type="button"
                                                    class="btn btn-sm btn-default">{{ $section->section_name }}</button>
                                                <button type="button"
                                                    class="btn btn-sm btn-default dropdown-toggle dropdown-icon"
                                                    data-toggle="dropdown" aria-expanded="true">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-left" role="menu">
                                                    @foreach ($sectionTypes as $type)
                                                        <a class="dropdown-item inputSection 
                                                            {{ $type->name == 'IMAGE' && $section->section_name != 'BANNER_IMAGE' ? 'disabled' : '' }}
                                                            {{ $type->name != 'IMAGE' && $section->section_name == 'BANNER_IMAGE' ? 'disabled' : '' }} 
                                                            "
                                                            data-type-id='{{ $type->id }}'
                                                            data-type-name='{{ $type->name }}'
                                                            data-section-id="{{ $section->id }}"
                                                            data-section-name="{{ $section->section_name }}"
                                                            href="#">ADD {{ $type->name }}</a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover table-sm"
                                                    data-section-id="{{ $section->id }}">
                                                    <thead>
                                                        <tr>
                                                            <th class="w70px text-center">#</th>
                                                            <th>Type</th>
                                                            <th>Sequence</th>
                                                            <th>Key</th>
                                                            <th>Value</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($section->contents as $content)
                                                            <tr>
                                                                <td class="text-center">
                                                                    <button data-content-id="{{ $content->id }}"
                                                                        href="#"
                                                                        class="btn btn-xs btn-success editSection"
                                                                        title="Edit">
                                                                        <i class="far fa-edit fa-fw"></i>
                                                                    </button>
                                                                    @if ($page->page_name == 'SOCIAL')
                                                                        <form
                                                                            action="{{ route('config.page.destroy', ['page' => $content->id]) }}"
                                                                            method="POST" class="d-inline">
                                                                            @csrf
                                                                            @method('delete')
                                                                            <button type="submit"
                                                                                class="delete_page_config btn btn-xs btn-danger"
                                                                                title="Delete">
                                                                                <i class="far fa-trash-alt fa-fw"></i>
                                                                            </button>
                                                                        </form>
                                                                    @endif
                                                                </td>
                                                                <td>{{ $content->group->name }}</td>
                                                                <td>{{ $content->seq }}</td>
                                                                <td>{{ $content->key }}</td>
                                                                <td>{{ $content->value }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
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
    </div>

    <div class="modal fade" id="modalSection">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row nonImageSection">
                        <div class="col-lg-12">
                            <div class="form-group row">
                                <label for="key" class="col-sm-2 col-form-label">Key</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="key" name="key"
                                        placeholder="You can input key as title or anything else" value="">
                                    <span id="key_error" class="error invalid-feedback">
                                    </span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="value" class="col-sm-2 col-form-label">Values</label>
                                <div class="col-sm-10 d-none" id="textSection">
                                    <textarea name="value_text" id="value_text" cols="30" rows="10"
                                        placeholder="You can input value as description or content"></textarea>
                                </div>
                                <div class="col-sm-10 d-none" id="linkSection">
                                    <input type="text" class="form-control" id="value_video" name="value_video"
                                        placeholder="You can input value as description or content or link"
                                        value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row imageSection">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="image_desktop">Image Desktop View</label>
                                <div class="custom-file">
                                    <input type="file"
                                        class="custom-file-input @error('image_desktop') is-invalid @enderror"
                                        data-index="0" name="image_desktop[]">
                                    <label class="custom-file-label" for="image_desktop">Choose
                                        file</label>
                                    <span id="image_desktop_error" class="error invalid-feedback">
                                        @error('image_desktop')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                                <div class="image_desktop_preview row row-cols-12 row-cols-md-1 mt-1">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="image_mobile">Image Mobile View</label>
                                <div class="custom-file">
                                    <input type="file"
                                        class="custom-file-input @error('image_mobile') is-invalid @enderror"
                                        data-index="0" name="image_mobile[]">
                                    <label class="custom-file-label" for="image_mobile">Choose
                                        file</label>
                                    <span id="image_mobile_error" class="error invalid-feedback">
                                        @error('image_mobile')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                                <div class=" image_mobile_preview row row-cols-12 row-cols-md-1 mt-1">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="saveSectionInput" type="button" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalSectionEdit">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row nonImageSection">
                        <div class="col-lg-12">
                            <div class="form-group row">
                                <label for="section_type" class="col-sm-2 col-form-label">Type</label>
                                <div class="col-sm-10">
                                    <label id="section_type" class="col-form-label"></label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="key_edit" class="col-sm-2 col-form-label">Key</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="key_edit" name="key_edit"
                                        placeholder="You can input key as title or anything else" value="">
                                    <span id="key_edit_error" class="error invalid-feedback">
                                    </span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="value_edit" class="col-sm-2 col-form-label">Values</label>
                                <div class="col-sm-10 d-none" id="textSectionEdit">
                                    <textarea name="value_text_edit" id="value_text_edit" cols="30" rows="10"
                                        placeholder="You can input value as description or content"></textarea>
                                </div>
                                <div class="col-sm-10 d-none" id="linkSectionEdit">
                                    <input type="text" class="form-control" id="value_video_edit"
                                        name="value_video_edit"
                                        placeholder="You can input value as description or content or link"
                                        value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row imageSection">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="image_desktop">Image Desktop View</label>
                                <div class="custom-file">
                                    <input type="file"
                                        class="custom-file-input @error('image_desktop') is-invalid @enderror"
                                        data-index="0" name="image_desktop_update[]">
                                    <label class="custom-file-label" for="image_desktop">Choose
                                        file</label>
                                </div>
                                <div class="image_desktop_preview row row-cols-12 row-cols-md-1 mt-1">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="image_mobile">Image Mobile View</label>
                                <div class="custom-file">
                                    <input type="file"
                                        class="custom-file-input @error('image_mobile') is-invalid @enderror"
                                        data-index="0" name="image_mobile_update[]">
                                    <label class="custom-file-label" for="image_mobile">Choose
                                        file</label>
                                </div>
                                <div class="image_mobile_preview row row-cols-12 row-cols-md-1 mt-1">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="saveSectionEdit" type="button" class="btn btn-primary">Save</button>
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

            var responseMessage = $('meta[name="response-message"]').attr('content');
            var responseStatus = $('meta[name="response-status"]').attr('content');

            if (responseMessage.trim() != '') {

                if (responseStatus == 'success') {
                    toastr.success(responseMessage)
                } else {
                    toastr.error(responseMessage)

                }
            }

            CKEDITOR.replace('value_text', {
                removePlugins: "exportpdf"
            });

            CKEDITOR.replace('value_text_edit', {
                removePlugins: "exportpdf"
            });

        });

        $(function() {
            var pageID = "{{ $page->id }}";
            $('#createSection').click(function() {
                var section = $('#section_name').val();

                $.ajax({
                    url: "{{ route('config.page.sectionStore') }}",
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        section,
                        pageID
                    },
                    complete: function(data) {
                        var response = data.responseJSON;
                        if (response.success) {
                            location.reload();
                        } else {
                            Swal.fire({
                                title: response.message,
                                icon: 'error',
                                confirmButtonText: 'OK',
                                buttonsStyling: false,
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                customClass: {
                                    confirmButton: 'btn btn-success'
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            })
                        }
                    }
                })
            });

            $('.delete_page_config').click(function(e) {
                var dis = this;
                e.preventDefault();
                Swal.fire({
                    title: 'Delete Page Content ?',
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

        $(function() {
            var typeID = '';
            var sectionID = '';
            var sectionName = '';
            var type = '';

            $('.inputSection').unbind().click(function() {
                sectionName = $(this).attr('data-section-name');
                type = $(this).attr('data-type-name');

                typeID = $(this).attr('data-type-id');
                sectionID = $(this).attr('data-section-id');

                if (type == 'VIDEO') {
                    $('#textSection').addClass('d-none');
                    $('#linkSection').removeClass('d-none');
                    $('.nonImageSection').removeClass('d-none');
                    $('.imageSection').addClass('d-none');
                } else if (type == 'IMAGE') {
                    $('.nonImageSection').addClass('d-none');
                    $('.imageSection').removeClass('d-none');

                    $('.image_desktop_preview').html('');
                    $('.image_mobile_preview').html('');
                } else {
                    CKEDITOR.instances['value_text'].setData('');
                    $('#textSection').removeClass('d-none');
                    $('#linkSection').addClass('d-none');
                    $('.nonImageSection').removeClass('d-none');
                    $('.imageSection').addClass('d-none');
                }

                $('#modalSection').modal('show');
            });

            $('#modalSection').on('shown.bs.modal', function() {
                $('#modalSection .modal-title').html('');
                $('#modalSection .modal-title').html(sectionName + ' ' + type);
            });

            function storeContent(typeID, sectionID) {
                var value = type == 'TEXT' ? CKEDITOR.instances['value_text'].getData() : $('#value_video').val();

                data = new FormData();
                data.append('image_desktop', $(`[name="image_desktop[]"]`)[0].files[0]);
                data.append('image_mobile', $(`[name="image_mobile[]"]`)[0].files[0]);
                data.append('typeID', typeID);
                data.append('sectionID', sectionID);
                data.append('value', value);
                data.append('key', $('#key').val());

                console.log(data)

                $.ajax({
                    url: "{{ route('config.page.contentStore') }}",
                    type: "POST",
                    enctype: 'multipart/form-data',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: data,
                    complete: function(data) {
                        var response = data.responseJSON;

                        if (response.success) {
                            location.reload();
                        } else {
                            Swal.fire({
                                title: response.message,
                                icon: 'error',
                                confirmButtonText: 'OK',
                                buttonsStyling: false,
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                customClass: {
                                    confirmButton: 'btn btn-success'
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            })
                        }
                    }
                })
            }


            $('#saveSectionInput').click(function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Simpan ?',
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
                        storeContent(typeID, sectionID);
                    }
                })
            });
        });

        $(function() {
            var contentID;
            var dataContent = {};

            $('.editSection').unbind().click(function() {
                contentID = $(this).attr('data-content-id');


                $.ajax({
                    url: "{{ route('config.page.contentShow') }}",
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        contentID
                    },
                    complete: function(data) {
                        var response = data.responseJSON;

                        if (response.success) {
                            $('#modalSectionEdit').modal('show');

                            dataContent = response.data;


                        }
                        console.log(response);
                    }
                });
            });

            $('#modalSectionEdit').on('shown.bs.modal', function() {
                $('#modalSectionEdit .modal-title').html('');
                $('#modalSectionEdit .modal-title').html(dataContent.section
                    .section_name + ' ' + dataContent.group
                    .name);
                $('#section_type').html(dataContent.group
                    .name + '_' + dataContent.seq);

                $('#key_edit').val(dataContent.key);

                if (dataContent.group.name == 'VIDEO') {
                    $('#textSectionEdit').addClass('d-none');
                    $('#linkSectionEdit').removeClass('d-none');
                    $('#value_video_edit').val(dataContent.value);

                    $('.nonImageSection').removeClass('d-none');
                    $('.imageSection').addClass('d-none');

                } else if (dataContent.group.name == 'IMAGE') {

                    var desktopImage = dataContent.images.find(function(v) {
                        return v.viewport == "DESKTOP";
                    });

                    var mobileImage = dataContent.images.find(function(v) {
                        return v.viewport == "MOBILE";
                    });

                    $('.nonImageSection').addClass('d-none');
                    $('.imageSection').removeClass('d-none');
                    $('.image_desktop_preview').html('');
                    $('.image_mobile_preview').html('');
                    $('.image_desktop_preview').append(`
                        <div class="col mb-4">
                            <div class="card h-100">
                            <img src="${desktopImage?.url}" class="card-img-top">
                            </div>
                        </div>
                    `);

                    $('.image_mobile_preview').append(`
                        <div class="col mb-4">
                            <div class="card h-100">
                            <img src="${mobileImage?.url}" class="card-img-top">
                            </div>
                        </div>
                    `);
                } else {
                    CKEDITOR.instances['value_text_edit'].setData(dataContent
                        .value);
                    $('#textSectionEdit').removeClass('d-none');
                    $('#linkSectionEdit').addClass('d-none');

                    $('.nonImageSection').removeClass('d-none');
                    $('.imageSection').addClass('d-none');
                }
            });

            $('#saveSectionEdit').click(function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Update ?',
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
                        updateContent(dataContent);
                    }
                })
            });

            function updateContent(dataContent) {
                var value = dataContent.group.name == 'TEXT' ? CKEDITOR.instances['value_text_edit'].getData() : $(
                    '#value_video_edit').val();

                data = new FormData();
                data.append('image_desktop', $(`[name="image_desktop_update[]"]`)[0].files[0]);
                data.append('image_mobile', $(`[name="image_mobile_update[]"]`)[0].files[0]);
                data.append('contentID', contentID);
                data.append('value', value);
                data.append('key', $('#key_edit').val());
                data.append('_method', 'PATCH');

                $.ajax({
                    url: "{{ route('config.page.contentUpdate') }}",
                    type: "POST",
                    enctype: 'multipart/form-data',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: data,
                    complete: function(data) {
                        var response = data.responseJSON;
                        if (response.success) {
                            location.reload();
                        } else {
                            Swal.fire({
                                title: response.message,
                                icon: 'error',
                                confirmButtonText: 'OK',
                                buttonsStyling: false,
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                customClass: {
                                    confirmButton: 'btn btn-success'
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            })
                        }
                    }
                })
            }
        });

        $(function() {
            var indexImages = 0;
            var isMultiple = false;

            $(`[name="image_desktop[]"][data-index="${indexImages}"],[name="image_desktop_update[]"][data-index="${indexImages}"]`)
                .on('change', function() {
                    readURL(this);

                    if (isMultiple == false) {
                        $('.image_desktop_preview').html('');
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
                    <label class="custom-file-label" for="image_desktop">Choose file</label>
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
                    <label class="custom-file-label" for="image_desktop">Choose file</label>
                `);

                $(obj).addClass('d-none');
                $(obj).next('label.custom-file-label').addClass('d-none');

            }

            var reader = new FileReader();
            reader.onload = function(e) {

                $('.image_desktop_preview').append(`
                    <div class="col mb-4">
                        <div class="card h-100">
                        <img src="${e.target.result}" class="card-img-top">
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

            $('.image_desktop_preview').on('click', '.remove_preview', function() {
                var removeIndex = $(this).attr('data-index');

                $(this).parents(':eq(2)').remove()
                $(`[name="image_desktop[]"][data-index="${removeIndex}"],[name="image_desktop_update[]"][data-index="${removeIndex}"]`)
                    .val('');
            });

        });

        $(function() {
            var indexImages = 0;
            var isMultiple = false;

            $(`[name="image_mobile[]"][data-index="${indexImages}"],[name="image_mobile_update[]"][data-index="${indexImages}"]`)
                .on('change', function() {
                    readURL(this);

                    if (isMultiple == false) {
                        $('.image_mobile_preview').html('');
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
                    <label class="custom-file-label" for="image_mobile">Choose file</label>
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
                    <label class="custom-file-label" for="image_mobile">Choose file</label>
                `);

                $(obj).addClass('d-none');
                $(obj).next('label.custom-file-label').addClass('d-none');

            }

            var reader = new FileReader();
            reader.onload = function(e) {

                $('.image_mobile_preview').append(`
                    <div class="col mb-4">
                        <div class="card h-100">
                        <img src="${e.target.result}" class="card-img-top">
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

            $('.image_mobile_preview').on('click', '.remove_preview', function() {
                var removeIndex = $(this).attr('data-index');

                $(this).parents(':eq(2)').remove()
                $(`[name="image_mobile[]"][data-index="${removeIndex}"],[name="image_mobile_update[]"][data-index="${removeIndex}"]`)
                    .val('');
            });

        });
    </script>
@endsection
