@extends('web.layouts.app')

@section('title', 'Investor')
<style>
    @import url('https://fonts.maateen.me/solaiman-lipi/font.css');

    .bg-orange {
        background: #ff5d00 !important;
        color: #fff !important;
    }

    .investor-card {
        background: #fff;
        box-shadow: rgba(50, 50, 93, 0.25) 0px 6px 12px -2px, rgba(0, 0, 0, 0.3) 0px 3px 7px -3px;
        border: none;
        border-radius: 10px !important;
    }

    .btn.bg-orange:focus {
        outline: 0;
        box-shadow: 0 0 0 .2rem rgba(255, 93, 0, .25);
    }

    input.form-control {
        padding: 8px 14px;
    }

    .form-control {
        border-width: 2px;
    }

    .investor-card .card-header {
        border-radius: 10px 10px 0 0 !important;
    }

    .investor-card {
        font-family: 'SolaimanLipi', sans-serif;
    }

    .investment-options label {
        cursor: pointer;
    }
</style>
@section('content')
    <section class=" career">
        <div class="container " style="min-height: 100vh;">
            {{-- Bredcrumb start  --}}
            <nav class="breadcrumb custom-breadcrumb mt-3">
                <a class="breadcrumb-item" href="{{ route('home') }}">Home</a>
                <span class="breadcrumb-item active" aria-current="page">Investor</span>
            </nav>
            {{--  Bredcrumb End --}}
            <div class="section-heading-title position-relative z-30 text-center">
                <div>
                    <h1>Investor</h1>
                </div>
                <div class="heading-border"></div>
            </div>
            <div class="row  mt-2 mt-lg-3 mb-2">
                <div class="col-lg-5 order-2 order-lg-1">
                    <div class="card investor-card">
                        <div class="card-header py-3 bg-orange text-white">
                            <h4 class="mb-0 text-center">Investment Form</strong></h4>
                        </div>
                        <div class="card-body">

                            <form id="investorForm" action="{{ route('investor.store') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label">Your Name<span class="text-danger">*</span></label>
                                    <input type="text" name="name" value="{{ old('name') }}"
                                        class="form-control @error('name')
                                        is-invalid
                                    @enderror"
                                        placeholder="আপনার নাম লিখুন">
                                    @error('name')
                                        <div class="text-danger mt-2">{{ ucwords($message) }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Phone Number<span class="text-danger">*</span></label>
                                    <input type="text" value="{{ old('mobile_number') }}" name="mobile_number"
                                        class="form-control @error('mobile_number')
                                        is-invalid
                                    @enderror"
                                        placeholder="আপনার মোবাইল নাম্বার লিখুন">
                                    @error('mobile_number')
                                        <div class="text-danger mt-2">{{ ucwords($message) }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Your Address <span class="text-danger">*</span></label>
                                    <textarea style="resize: none;"
                                        class="form-control @error('address')
                                        is-invalid
                                    @enderror"
                                        name="address" id="" cols="30" rows="4" placeholder="আপনার ঠিকানা লিখুন">{{ old('address') }}</textarea>
                                    @error('address')
                                        <div class="text-danger mt-2">{{ ucwords($message) }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Your Occupation <small>(Optional)</small></label>
                                    <input type="text" value="{{ old('occupation') }}" name="occupation"
                                        class="form-control @error('occupation')
                                        is-invalid
                                    @enderror"
                                        placeholder="আপনার পেশা লিখুন">
                                    @error('occupation')
                                        <div class="text-danger mt-2">{{ ucwords($message) }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">ইনভেস্ট পরিমাণ লিখুন <span
                                            class="text-danger">*</span></label>
                                    <div class="investment-options">

                                        <label class="d-block cursor-pointer">
                                            <input type="radio" name="investment_amount" value="1-2"
                                                {{ old('investment_amount') == '1-2' ? 'checked' : '' }}>
                                            ১ থেকে ২ লাখ
                                        </label>

                                        <label class="d-block">
                                            <input type="radio" name="investment_amount" value="2-5"
                                                {{ old('investment_amount') == '2-5' ? 'checked' : '' }}>
                                            ২ থেকে ৫ লাখ
                                        </label>

                                        <label class="d-block">
                                            <input type="radio" name="investment_amount" value="5-7"
                                                {{ old('investment_amount') == '5-7' ? 'checked' : '' }}>
                                            ৫ থেকে ৭ লাখ
                                        </label>

                                        <label class="d-block">
                                            <input type="radio" name="investment_amount" value="7-10"
                                                {{ old('investment_amount') == '7-10' ? 'checked' : '' }}>
                                            ৭ থেকে ১০ লাখ
                                        </label>

                                        <label class="d-block">
                                            <input type="radio" name="investment_amount" value="10+"
                                                {{ old('investment_amount') == '10+' ? 'checked' : '' }}>
                                            ১০ লাখ+
                                        </label>

                                        @error('investment_amount')
                                            <div class="text-danger mt-2">{{ ucwords($message) }}</div>
                                        @enderror

                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Comment <small>(Optional)</small></label>
                                    <textarea style="resize: none;"
                                        class="form-control @error('comment')
                                        is-invalid
                                    @enderror"
                                        name="comment" id="" cols="30" rows="4" placeholder="আপনার মন্তব্য লিখুন">{{ old('comment') }}</textarea>
                                    @error('comment')
                                        <div class="text-danger mt-2">{{ ucwords($message) }}</div>
                                    @enderror
                                </div>

                                <div class="d-flex justify-content-between">

                                    <div class="d-flex align-items-center">
                                        <a class=" text-white text-small d-block border border-dotted p-2 rounded"
                                            target="_blank" title="Go Whatsapp for details"
                                            style="font-size: 15px; font-weight: 600"
                                            href="https://wa.me/8801934657964?text=Hello%2C%20I%20am%20interested%20in%20your%20investment%20program.%20Please%20share%20the%20details.">
                                            <img style="width: 40px;"
                                                src="{{ asset('assets/frontend/images/logo/whatsapp.png') }}"
                                                alt="whatsapp icon">
                                            <small class="ml-2 text-dark" style="font-weight: 600;"> বিস্তারিত জানতে মেসেজ
                                                করুন </small>
                                        </a>
                                    </div>

                                    <button type="submit" class="btn btn-sm bg-orange px-lg-5 py-2">
                                        Submit <i class="fa fa-paper-plane"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7 order-1 order-lg-2 mb-3 mb-lg-0">
                    <img class="img-rounded" style="max-width: 100%; height: auto;"
                        src="{{ asset('assets/frontend/img/invest.jpeg') }}" alt="investor image">
                </div>

            </div>
        </div>
    </section>
@endsection
<script>
    document.getElementById('investorForm').addEventListener('submit', function(e) {


        setTimeout(() => {
            let name = document.querySelector('[name="name"]').value;
            let mobile_number = document.querySelector('[name="mobile_number"]').value;
            let address = document.querySelector('[name="address"]').value;
            let occupation = document.querySelector('[name="occupation"]').value;
            let investment = document.querySelector('[name="investment_amount"]:checked')?.value;
            let comment = document.querySelector('[name="comment"]').value;

            let message = `Investment Data:

            Name: ${name}
            Phone: ${mobile_number}
            Address: ${address}
            Occupation: ${occupation}
            Investment: ${investment}
            Comment: ${comment}`;

            let phone = "8801402282117";
            let url = `https://wa.me/${phone}?text=${encodeURIComponent(message)}`;

            window.open(url, '_blank');
        }, 500);
    });
</script>
