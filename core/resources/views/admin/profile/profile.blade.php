@extends('admin.layouts.app')
@section('title', 'Admin Profile')
@push('styles')
    <style>
        .img-bg {
            background-image: url('{{ asset('assets/backend/images/admin-profile-bg.jpg') }}');
            background-position: 0 20%;
            background-repeat: no-repeat;
            background-size: cover;
            height: 180px;
        }
    </style>
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                    <h4 class="page-title">Profile</h4>
                    <div class="">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="#">Mifty</a>
                            </li><!--end nav-item-->
                            <li class="breadcrumb-item"><a href="#">Pages</a>
                            </li><!--end nav-item-->
                            <li class="breadcrumb-item active">Profile</li>
                        </ol>
                    </div>
                </div><!--end page-title-box-->
            </div><!--end col-->
        </div><!--end row-->
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body p-4  rounded text-center img-bg">
                    </div>
                    <div class="position-relative">
                        <div class="shape overflow-hidden text-card-bg">
                            <svg viewBox="0 0 2880 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0 48H1437.5H2880V0H2160C1442.5 52 720 0 720 0H0V48Z" fill="currentColor"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="card-body mt-n6">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="d-flex align-items-center">
                                    <div class="position-relative">
                                        <img src="{{ asset('assets/storage/profile/' . auth('admin')->user()->image) }}"
                                            alt="" class="rounded-circle img-fluid" style="width: 65px;">
                                    </div>
                                    <div class="flex-grow-1 text-truncate ms-3 align-self-end">
                                        <h5 class="m-0 fs-3 fw-bold text-white">{{ auth('admin')->user()->name }}</h5>
                                        <p class="text-muted mb-0">@
                                            {{ auth('admin')->user()->getRoleNames()->first() ?? '' }}</p>
                                    </div><!--end media body-->
                                </div><!--end media-->
                                <div class="mt-3">
                                    <div class="text-body mb-2  d-flex align-items-center"><i
                                            class="iconoir-language fs-20 me-1 text-muted"></i><span
                                            class="text-body fw-semibold">Language :</span> Bangla / English / French / Spanish</div>
                                    <div class="text-muted mb-2 d-flex align-items-center"><i
                                            class="iconoir-mail-out fs-20 me-1"></i><span
                                            class="text-body fw-semibold">Email :</span><a href="#"
                                            class="text-primary text-decoration-underline">{{ auth('admin')->user()->email }}</a></div>
                                    <div class="text-body mb-3 d-flex align-items-center"><i
                                            class="iconoir-phone fs-20 me-1 text-muted"></i><span
                                            class="text-body fw-semibold">Phone :</span> {{ auth('admin')->user()->phone }}</div>
                                </div>
                            </div><!--end col-->
                        </div><!--end row-->
                    </div><!--end card-body-->
                </div><!--end card-->

            </div> <!--end col-->
            <div class="col-lg-8">
                <div class="bg-primary-subtle p-2 border-dashed border-primary rounded mb-3">
                    <img src="assets/images/extra/party.gif" alt="" class="d-inline-block me-1" height="30">
                    <span class="text-primary fw-semibold">Rosa Dodson's</span><span class="text-primary fw-normal"> best
                        performance from last year</span>
                </div>
                <div class="row g-3">
                    <div class="col-md-6 col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <i class="iconoir-dollar-circle fs-24 align-self-center text-info me-2"></i>
                                    <div class="flex-grow-1 text-truncate">
                                        <p class="text-dark mb-0 fw-semibold fs-13">Total Cost</p>
                                        <h3 class="mt-1 mb-0 fs-18 fw-bold">0 <span class="fs-11 text-muted fw-normal">New
                                                365 Days</span> </h3>
                                    </div><!--end media body-->
                                </div>
                            </div><!--end card-body-->
                        </div> <!--end card-body-->
                    </div><!--end col-->
                    <div class="col-md-6 col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <i class="iconoir-cart fs-24 align-self-center text-blue me-2"></i>
                                    <div class="flex-grow-1 text-truncate">
                                        <p class="text-dark mb-0 fw-semibold fs-13">Total Order</p>
                                        <h3 class="mt-1 mb-0 fs-18 fw-bold">0 <span class="fs-11 text-muted fw-normal">Order
                                                365 Days</span> </h3>
                                    </div><!--end media body-->
                                </div>
                            </div><!--end card-body-->
                        </div> <!--end card-body-->
                    </div><!--end col-->

                    <div class="col-md-6 col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <i class="iconoir-thumbs-up fs-24 align-self-center text-primary me-2"></i>
                                    <div class="flex-grow-1 text-truncate">
                                        <p class="text-dark mb-0 fw-semibold fs-13">Completed</p>
                                        <h3 class="mt-1 mb-0 fs-18 fw-bold">0 <span class="fs-11 text-muted fw-normal">Comp.
                                                Order 365 Days</span> </h3>
                                    </div><!--end media body-->
                                </div>
                            </div><!--end card-body-->
                        </div> <!--end card-->
                    </div><!--end col-->
                    <div class="col-md-6 col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <i class="iconoir-xmark-circle fs-24 align-self-center text-danger me-2"></i>
                                    <div class="flex-grow-1 text-truncate">
                                        <p class="text-dark mb-0 fw-semibold fs-13">Cancled</p>
                                        <h3 class="mt-1 mb-0 fs-18 fw-bold">0 <span
                                                class="fs-11 text-muted fw-normal">Canc.Order 365 Days</span> </h3>
                                    </div><!--end media body-->
                                </div>
                            </div><!--end card-body-->
                        </div> <!--end card-body-->
                    </div><!--end col-->
                </div><!--end row-->
                <ul class="nav nav-tabs mb-3" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link fw-medium" data-bs-toggle="tab" href="#settings" role="tab"
                            aria-selected="false">Profile Settings</a>
                    </li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane p-3 active" id="settings" role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h4 class="card-title">Personal Information</h4>
                                    </div><!--end col-->
                                </div> <!--end row-->
                            </div><!--end card-header-->
                            <div class="card-body pt-0">
                                <form action="{{ route('admin.profile.update') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group mb-3 row">
                                        <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">Name
                                            <span class="text-danger">*</span></label>
                                        <div class="col-lg-9 col-xl-8">
                                            <input class="form-control" value="{{ auth('admin')->user()->name }}"
                                                type="text" name="name" placeholder="Enter your full name">
                                            @error('name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group mb-3 row">
                                        <label
                                            class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">Contact
                                            Phone <span class="text-danger">*</span></label>
                                        <div class="col-lg-9 col-xl-8">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="las la-phone"></i></span>
                                                <input type="text" class="form-control" name="phone"
                                                    value="{{ auth('admin')->user()->phone }}"
                                                    placeholder="Enter your phone" aria-describedby="basic-addon1">
                                                @error('phone')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mb-3 row">
                                        <label
                                            class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">Email
                                            Address <span class="text-danger">*</span></label>
                                        <div class="col-lg-9 col-xl-8">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="las la-at"></i></span>
                                                <input type="text" class="form-control" name="email"
                                                    value="{{ auth('admin')->user()->email }}"
                                                    placeholder="Enter your email" aria-describedby="basic-addon1">
                                                @error('email')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mb-3 row">
                                        <label
                                            class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">Profile</label>
                                        <div class="col-lg-9 col-xl-8">
                                            <input class="form-control" type="file" name="profile_image">
                                            @error('profile_image')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-9 col-xl-8 offset-lg-3">
                                            <button type="submit" class="btn btn-primary">Save</button>
                                            <button type="button" class="btn btn-danger">Cancel</button>
                                        </div>
                                    </div>
                                </form>
                            </div><!--end card-body-->
                        </div><!--end card-->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Change Password</h4>
                            </div><!--end card-header-->
                            <div class="card-body pt-0">
                                <form action="{{ route('admin.profile.password') }}" method="POST">
                                    @csrf
                                    <div class="form-group mb-3 row">
                                        <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">New
                                            Password</label>
                                        <div class="col-lg-9 col-xl-8">
                                            <input class="form-control" type="password" name="password"
                                                placeholder="New Password">
                                            @error('password')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group mb-3 row">
                                        <label
                                            class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">Confirm
                                            Password</label>
                                        <div class="col-lg-9 col-xl-8">
                                            <input class="form-control" type="password" name="confirm_password"
                                                placeholder="Re-Password">
                                            @error('confirm_password')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-9 col-xl-8 offset-lg-3">
                                            <button type="submit" class="btn btn-primary">Change Password</button>
                                            <button type="button" class="btn btn-danger">Cancel</button>
                                        </div>
                                    </div>
                                </form>
                            </div><!--end card-body-->
                        </div><!--end card-->
                    </div>
                </div>
            </div> <!-- end col -->
        </div><!--end row-->

    </div><!-- container -->
@endsection
