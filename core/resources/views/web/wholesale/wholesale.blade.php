@extends('layouts.front-end.app')

@section('title', 'Wholesale')
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
</style>
@section('content')
    <section class="py-3 career">
        <div class="container " style="min-height: 100vh;">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-3 mb-lg-0">
                    <img class="rounded" style="max-width: 100%; height: auto;" src="{{asset('assets/front-end/img/wholesale.jpg')}}" alt="investor image">
                </div>

                <div class="col-lg-6 ">
                    <div class="card investor-card">
                         <div class="card-header py-3 bg-orange text-white">
                                <h4 class="mb-0 text-center">Wholesale Form</strong></h4>
                            </div>
                        <div class="card-body">

                            <form action="{{route('wholesale.store')}}" method="POST">
                                @csrf

                                <div class="mb-3">
                                     <label class="form-label">Your Name<span class="text-danger">*</span></label>
                                    <input type="text" name="name" value="{{old('name')}}" class="form-control @error('name')
                                        is-invalid
                                    @enderror"
                                        placeholder="আপনার নাম লিখুন">
                                        @error('name')
                                            <div class="text-danger mt-2">{{ ucwords($message) }}</div>
                                        @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Phone Number<span class="text-danger">*</span></label>
                                    <input type="text" value="{{old('phone')}}" name="phone" class="form-control @error('phone')
                                        is-invalid
                                    @enderror"
                                        placeholder="আপনার মোবাইল নাম্বার লিখুন">
                                        @error('phone')
                                            <div class="text-danger mt-2">{{ ucwords($message) }}</div>
                                        @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Your Address</label>
                                    <textarea style="resize: none;" class="form-control @error('address')
                                        is-invalid
                                    @enderror" name="address" id="" cols="30" rows="6" placeholder="আপনার ঠিকানা লিখুন">{{old('address')}}</textarea>
                                    @error('address')
                                            <div class="text-danger mt-2">{{ ucwords($message) }}</div>
                                        @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Your Occupation </label>
                                    <input type="text" value="{{old('occupation')}}" name="occupation" class="form-control @error('occupation')
                                        is-invalid
                                    @enderror"
                                        placeholder="আপনার পেশা লিখুন">
                                        @error('occupation')
                                            <div class="text-danger mt-2">{{ ucwords($message) }}</div>
                                        @enderror
                                </div>

                                <div class="mb-3">

                                     <label class="form-label">Product Quantity</label>
                                    <input type="number" value="{{old('product_quantity')}}" name="product_quantity" class="form-control @error('product_quantity')
                                        is-invalid
                                    @enderror"
                                        placeholder="পণ্যের পরিমাণ লিখুন">
                                        @error('product_quantity')
                                            <div class="text-danger mt-2">{{ ucwords($message) }}</div>
                                        @enderror
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn bg-orange px-5 py-2">
                                      Submit <i class="fa fa-paper-plane"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection

