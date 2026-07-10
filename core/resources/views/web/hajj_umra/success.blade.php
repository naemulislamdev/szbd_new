@extends('web.layouts.app')

@section('title', 'আবেদন সফলভাবে জমা হয়েছে | ' . $web_config['name']->value)

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">

                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body p-5">

                        <div class="mb-4">
                            <i class="fa-solid fa-circle-check text-success" style="font-size: 80px;"></i>
                        </div>

                        <h2 class="fw-bold text-success mb-3">
                            আবেদন সফলভাবে জমা হয়েছে
                        </h2>

                        <p class="text-muted mb-4">
                            আপনার ফ্রি উমরাহ/হজ আবেদনটি সফলভাবে গ্রহণ করা হয়েছে।
                            যাচাই-বাছাই শেষে নির্বাচিত প্রার্থীদের সাথে ফোনে যোগাযোগ করা হবে।
                        </p>

                        <a href="{{ route('home') }}" class="btn btn-success px-4">
                            <i class="fa fa-home me-1"></i>
                            হোম পেজে ফিরে যান
                        </a>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
