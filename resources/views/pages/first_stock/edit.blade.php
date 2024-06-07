@extends('layouts.app')

@section('title', 'First Stock Edit')

@push('style')
<!-- CSS Libraries -->
<link rel="stylesheet" href="{{ asset('library/bootstrap-daterangepicker/daterangepicker.css') }}">
<link rel="stylesheet" href="{{ asset('library/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
<link rel="stylesheet" href="{{ asset('library/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('library/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}">
@endpush

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Advanced Forms</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Forms</a></div>
                <div class="breadcrumb-item">First Stock</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    @include('layouts.alert')
                </div>
            </div>
            <h2 class="section-title">First Stock</h2>


            <div class="card">
                <form action="{{ route('first_stock.update', $data) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-header">
                        <h4>Input Text</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Product Name</label>
                            <select class="form-control" name="product_id" required>
                                <option value="" selected disabled>-- Select Product --</option>
                                @foreach($product as $key => $value)
                                <option value="{{ $value->id }}" @if($data->product_id == $value->id) selected @endif>{{ $value->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>First Stock</label>
                            <input type="text" value="{{ $data->stock }}" class="form-control @error('stock')
                                is-invalid
                            @enderror" name="stock">
                            @error('stock')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>

        </div>
    </section>
</div>
@endsection

@push('scripts')
@endpush