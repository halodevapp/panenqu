<?php $page_title = 'Edit Store'; ?>

@extends('layouts.main')

@section('page-title', $page_title)

@section('page-css')
    <link rel="stylesheet" href="/assets/adminlte/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <style>
        #map {
            height: 400px;
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
                            <li class="breadcrumb-item"><a href="{{ route('store.index') }}">Master Store</a></li>
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
                            <form action="{{ route('store.update', ['store' => $store->id]) }}" class="form-horizontal"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('patch')
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group row">
                                                <label for="store_name" class="col-sm-2 col-form-label">Store Name</label>
                                                <div class="col-sm-10">
                                                    <input type="text"
                                                        class="form-control @error('store_name') is-invalid @enderror"
                                                        id="store_name" name="store_name" placeholder="Store Name"
                                                        value="{{ old('store_name', $store->store_name) }}">
                                                    <span id="store_name_error" class="error invalid-feedback">
                                                        @error('store_name')
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
                                                <label for="store_type_id" class="col-sm-2 col-form-label">Store
                                                    Type</label>
                                                <div class="col-sm-10">
                                                    <select id="store_type_id" name="store_type_id"
                                                        class="form-control select2 select2-hidden-accessible @error('store_type_id') is-invalid @enderror"
                                                        style="width: 100%;" aria-hidden="true">
                                                        <option value="">Choose Store Type</option>
                                                        @foreach ($storeTypes as $storeType)
                                                            <option value="{{ $storeType->id }}"
                                                                {{ old('store_type_id', $store->store_type_id) == $storeType->id ? 'selected' : '' }}>
                                                                {{ $storeType->type_name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <span id="store_type_id_error" class="error invalid-feedback">
                                                        @error('store_type_id')
                                                            {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row offline d-none">
                                        <div class="col-lg-12">
                                            <div class="form-group row">
                                                <label for="store_phone" class="col-sm-2 col-form-label">Phone
                                                    Number</label>
                                                <div class="col-sm-10">
                                                    <input type="text"
                                                        class="form-control @error('store_phone') is-invalid @enderror"
                                                        id="store_phone" name="store_phone" placeholder="Phone Number"
                                                        value="{{ old('store_phone', $store->store_phone) }}">
                                                    <span id="store_phone_error" class="error invalid-feedback">
                                                        @error('store_phone')
                                                            {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row offline d-none">
                                        <div class="col-lg-12">
                                            <div class="form-group row">
                                                <label for="store_location" class="col-sm-2 col-form-label">Store
                                                    Location</label>
                                                <div class="col-sm-10">
                                                    <textarea class="form-control @error('store_location') is-invalid @enderror" id="store_location" name="store_location"
                                                        placeholder="Store Location" rows="5">{{ old('store_location', $store->store_location) }}</textarea>
                                                    <span id="store_location_error" class="error invalid-feedback">
                                                        @error('store_location')
                                                            {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row offline d-none">
                                        <div class="col-lg-12">
                                            <div class="form-group row">
                                                <label for="maps_location" class="col-sm-2 col-form-label">Maps
                                                    Location</label>
                                                <div class="col-sm-10">
                                                    <input class="d-none" type="text" id="latitude" name="latitude"
                                                        value="{{ old('latitude', $store->store_latitude) }}">
                                                    <input class="d-none" type="text" id="longitude" name="longitude"
                                                        value="{{ old('latitude', $store->store_longitude) }}">
                                                    <input type="text" class="form-control w-50 mt-2"
                                                        id="search_maps_location" name="search_maps_location"
                                                        placeholder="Search location"
                                                        value="{{ old('search_maps_location') }}">
                                                    <div id="map"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row online d-none">
                                        <div class="col-lg-12">
                                            <div class="form-group row">
                                                <label for="store_link" class="col-sm-2 col-form-label">Store Link</label>
                                                <div class="col-sm-10">
                                                    <input type="text"
                                                        class="form-control @error('store_link') is-invalid @enderror"
                                                        id="store_link" name="store_link" placeholder="Store Link"
                                                        value="{{ old('store_link', $store->store_link) }}">
                                                    <span id="store_link_error" class="error invalid-feedback">
                                                        @error('store_link')
                                                            {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row online d-none">
                                        <div class="col-lg-12">
                                            <div class="form-group row">
                                                <label for="store_image" class="col-sm-2 col-form-label">Store
                                                    Images</label>
                                                <div class="col-sm-10">
                                                    <div class="custom-file">
                                                        <input type="file"
                                                            class="custom-file-input @error('store_image') is-invalid @enderror"
                                                            data-index="0" name="store_image[]">
                                                        <label class="custom-file-label" for="store_image">Choose
                                                            file</label>
                                                        <span id="store_image_error" class="error invalid-feedback">
                                                            @error('store_image')
                                                                {{ $message }}
                                                            @enderror
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="store_image_preview" class="row row-cols-2 row-cols-md-4 online d-none">
                                        @foreach ($store->storeImageOnline as $image)
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
                                <div class="card-footer">
                                    <a href="{{ route('store.index') }}" class="btn btn-secondary">Back</a>
                                    <button id="submit_store" type="submit"
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
    <script type="text/javascript" src="/assets/ckeditor/ckeditor.js"></script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDc1wQ6upTGzloLNSO1x4n7DNxxEENhwJE&callback=initMap&v=weekly&libraries=places"
        defer></script>
    <script>
        let map;
        let markers = [];
        let myLatlng = {
            lat: isNaN(parseFloat($('#latitude').val())) ? -6.2275685098177975 : parseFloat($('#latitude').val()),
            lng: isNaN(parseFloat($('#longitude').val())) ? 106.80613175435577 : parseFloat($('#longitude').val())
        };

        function initMap() {
            map = new google.maps.Map(document.getElementById("map"), {
                center: myLatlng,
                zoom: 15,
            });

            const initMarker = new google.maps.Marker({
                // The below line is equivalent to writing:
                // position: new google.maps.LatLng(-34.397, 150.644)
                position: myLatlng,
                map: map,
            });

            markers.push(initMarker);

            map.addListener("click", (e) => {
                setMapOnAll(null);
                placeMarkerAndPanTo(e.latLng, map);
            });

            // Create the search box and link it to the UI element.
            const input = document.getElementById("search_maps_location");
            const searchBox = new google.maps.places.SearchBox(input);

            map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
            // Bias the SearchBox results towards current map's viewport.
            map.addListener("bounds_changed", () => {
                searchBox.setBounds(map.getBounds());
            });

            // Listen for the event fired when the user selects a prediction and retrieve
            // more details for that place.
            searchBox.addListener("places_changed", () => {
                const places = searchBox.getPlaces();

                if (places.length == 0) {
                    return;
                }

                // Clear out the old markers.
                setMapOnAll(null);

                // For each place, get the icon, name and location.
                const bounds = new google.maps.LatLngBounds();

                places.forEach((place) => {
                    if (!place.geometry || !place.geometry.location) {
                        console.log("Returned place contains no geometry");
                        return;
                    }
                    // Create a marker for each place.
                    placeMarkerAndPanTo(place.geometry.location, map);

                    if (place.geometry.viewport) {
                        // Only geocodes have viewport.
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);
            });
        }

        function placeMarkerAndPanTo(latLng, map) {
            $('#latitude').val(latLng.lat());
            $('#longitude').val(latLng.lng());
            const marker = new google.maps.Marker({
                position: latLng,
                map: map,
            });
            map.panTo(latLng);
            markers.push(marker);

        }

        function setMapOnAll(map) {
            for (let i = 0; i < markers.length; i++) {
                markers[i].setMap(map);
            }
        }

        window.initMap = initMap;
    </script>

    <script>
        $(function() {
            $('#store_type_id').select2({});

            $('#store_type_id').change(function() {
                if ($(this).val() == 1) {
                    $('.online').slideUp();
                    $('.offline').slideDown().removeClass('d-none');
                } else if ($(this).val() == 2) {
                    $('.online').slideDown().removeClass('d-none');
                    $('.offline').slideUp();
                } else {
                    $('.online').slideUp();
                    $('.offline').slideUp();
                }
            });

            $('#store_type_id').trigger('change');

            $('#submit_store').click(function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Simpan Store ?',
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
                        $('#submit_store').parents('form').submit();
                    }
                })
            });
        });

        $(function() {
            CKEDITOR.replace('store_location', {
                removePlugins: "exportpdf"
            });
        });

        $(function() {
            var indexImages = 0;
            var isMultiple = false;

            $(`[name="store_image[]"][data-index="${indexImages}"]`).on('change', function() {
                readURL(this);

                if (isMultiple == false) {
                    $('#store_image_preview').html('');
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
                    <label class="custom-file-label" for="store_image">Choose file</label>
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
                    <label class="custom-file-label" for="store_image">Choose file</label>
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
                    .appendTo('#store_image_preview');
            }

            function readURL(input) {
                if (input.files && input.files[0]) {
                    reader.fileName = input.files[0].name;
                    reader.index = $(input).attr('data-index');
                    reader.readAsDataURL(input.files[0]);
                }
            }

            $('#store_image_preview').on('click', '.remove_preview', function() {
                var removeIndex = $(this).attr('data-index');

                $(this).parents(':eq(2)').remove()
                $(`[name="store_image[]"][data-index="${removeIndex}"]`).val('');
            });

        });
    </script>
@endsection
