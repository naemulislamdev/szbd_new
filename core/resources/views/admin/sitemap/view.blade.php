@extends('admin.layouts.app')
@section('title', 'Sitemap Download')

@push('styles')
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                    <h4 class="page-title">Sitemap</h4>
                    <div class="">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>
                            </li><!--end nav-item-->

                            </li><!--end nav-item-->
                            <li class="breadcrumb-item active">Sitemap</li>
                        </ol>
                    </div>
                </div><!--end page-title-box-->
            </div><!--end col-->
        </div><!--end row-->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-0">

                    </div><!--end card-header-->
                    <div class="card-body pt-0 text-center">
                        <a href="{{ route('admin.sitemap-download') }}" class="btn btn-primary btn-lg"><i
                                style="font-size: 25px" class="las la-file-download"></i>
                            Download Sitemap</a>
                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div><!-- container -->



@endsection
@push('scripts')
@endpush
