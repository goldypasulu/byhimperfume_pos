@extends('layouts.app')

@section('title', 'Promotions')

@push('style')
<!-- CSS Libraries -->
<link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Promotions</h1>
            <div class="section-header-button">
                <a href="{{ route('promotionBundle.create') }}" class="btn btn-primary">Add New</a>
            </div>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Promotions</a></div>
                <div class="breadcrumb-item">All Promotions Bundle</div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    @include('layouts.alert')
                </div>
            </div>


            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>All Promotions Bundle</h4>
                        </div>
                        <div class="card-body">

                            <div class="float-right">
                                <form method="GET" action="{{ route('promotionBundle.index') }}">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Search" name="name">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="clearfix mb-3"></div>

                            <div class="table-responsive">
                                <table class="table-striped table">
                                    <tr>

                                        <th>Name</th>
                                        <th>Bundle</th>
                                        <th>Price</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Action</th>
                                    </tr>
                                    @foreach ($data as $value)
                                    <tr>

                                        <td>{{ $value->name_promotion }}</td>
                                        <td>{{ $value->name_product_a.' & '.$value->name_product_b }}</td>
                                        <td>{{ $value->price }}</td>
                                        <td>{{ $value->from_date }}</td>
                                        <td>{{ $value->to_date }}</td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <a href='{{ route('promotionBundle.edit', $value->id) }}' class="btn btn-sm btn-info btn-icon">
                                                    <i class="fas fa-edit"></i>
                                                    Edit
                                                </a>

                                                <form action="{{ route('promotionBundle.destroy', $value->id) }}" method="POST" class="ml-2">
                                                    <input type="hidden" name="_method" value="DELETE" />
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                                    <button class="btn btn-sm btn-danger btn-icon confirm-delete">
                                                        <i class="fas fa-times"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach


                                </table>
                            </div>
                            <div class="float-right">
                                {{ $data->withQueryString()->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<!-- JS Libraies -->
<script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>

<!-- Page Specific JS File -->
<script src="{{ asset('js/page/features-posts.js') }}"></script>
@endpush