@extends('admin.layouts.app')
@section('title', 'Roles Management')

@push('styles')
    <style>
        label {
            font-weight: 500;
            cursor: pointer;
        }
    </style>
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-12">
                <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                    <h4 class="page-title">Role Edit</h4>
                    <div class="">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>
                            </li><!--end nav-item-->
                            <li class="breadcrumb-item"><a href="{{ route('admin.role_permission.list') }}">Roles</a>
                            </li><!--end nav-item-->

                            </li><!--end nav-item-->
                            <li class="breadcrumb-item active">Role Edit</li>
                        </ol>
                    </div>
                </div><!--end page-title-box-->
            </div><!--end col-->

        </div><!--end row-->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="row ">
                            <h4 class="card-title">Role Edit Form</h4>
                            <form action="{{ route('admin.role_permission.update', $role->id) }}" method="POST">
                                @csrf
                                <div class="mt-3">
                                    <label for="name" class="form-label">Role Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ $role->name}}"
                                        placeholder="Ex: Admin" required>
                                </div>
                                <div class="mt-3">
                                    <h5 class="fw-bold">Update Permissions</h5>

                                    <div>
                                        @foreach ($modules as $module)
                                            <div class="card permission-item">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center">
                                                        <h4 class="card-title">{{ $module->title }}</h4>
                                                        <div class="form-check ms-5 d-flex">
                                                            <div>
                                                                <input class="form-check-input all_access" type="checkbox"
                                                                    value="order_view" name="module_access[]">
                                                                <label class="form-check-label" for="all_access">
                                                                    Access All
                                                                </label>
                                                            </div>
                                                            <div class="d-flex ms-5">
                                                                @php
                                                                    $permissions = json_decode($module->actions, true);
                                                                    $moduleAccess = json_decode($role->module_access, true);
                                                                @endphp
                                                                @foreach ($permissions as $permission)
                                                                    <div class="form-check me-2">
                                                                        <input class="form-check-input permission-checkbox"
                                                                            type="checkbox" value="{{ $permission }}"
                                                                            id="{{ $module->slug}}_{{ $permission }}" name="module_access[]" {{ in_array($permission, $moduleAccess) ? 'checked' : '' }}>
                                                                        <label for="{{ $module->slug}}_{{ $permission }}" class="form-check-label">
                                                                            {{ ucfirst(str_replace('_', ' ', $permission)) }}
                                                                        </label>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>
                                        @endforeach
                                        <div class="mt-4">
                                            <button type="submit" class="btn btn-primary">Update Role</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div><!--end card-header-->
                    <div class="card-body pt-0">
                        <div id="loader" style="display:none; text-align:center; margin-bottom:10px;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div><!-- container -->

@endsection
@push('scripts')
    <script>
        $(document).ready(function() {

            $('.all_access').on('change', function() {

                let card = $(this).closest('.permission-item');

                card.find('.permission-checkbox').prop('checked', $(this).is(':checked'));

            });

        });
    </script>
@endpush
