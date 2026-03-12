@extends('admin.layouts.app')
@section('title', 'Terms & Condition')

@push('styles')
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                    <h4 class="page-title">Edit Terms & Condition</h4>
                    <div class="">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>
                            </li><!--end nav-item-->

                            </li><!--end nav-item-->
                            <li class="breadcrumb-item active">Terms & Conditon</li>
                        </ol>
                    </div>
                </div><!--end page-title-box-->
            </div><!--end col-->
        </div><!--end row-->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-0">
                    </div>
                    <div class="card-body pt-0">
                        <form action="{{ route('admin.update-terms-condition') }}" method="POST">
                            @csrf
                            <label for="terms">Terms & Conditon</label>
                            <textarea name="terms" class="summernote" id="terms" cols="30" rows="10">{!! $terms_condition['value'] !!}</textarea>
                            <div class="mt-4 text-center">
                                <button type="submit" class="btn btn-primary px-5">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div><!-- container -->




@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#terms').summernote();


        });
    </script>
@endpush
