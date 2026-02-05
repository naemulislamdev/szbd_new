@extends('web.layouts.app')
@section('title', 'Complain')
@section('content')
    <style>
        .complain-box {
            box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
            border-radius: 15px;
            overflow: hidden;
        }

        .complain-title {
            background: #f26d21;
            color: #fff;
            padding: 5px 0px;
            margin-bottom: 10px;
            border-radius: 7px;
        }

        .complain-title h3 {
            margin: 0;
            text-align: center;
        }
    </style>
    <section class="complain-section my-4">
        <div class="container">
            <div class="row">
                <div class="col-md-10 mx-auto">
                    <div class="row">
                        <div class="col">
                            <div class="complain-title">
                                <h3>Drop Us a Message</h3>
                            </div>
                        </div>
                    </div>
                    <div class="complain-box">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="consult-img">
                                    <img src="{{ asset('assets/frontend/img/complain.jpg') }}" alt="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-sec p-2">
                                    <form action="{{ route('customer.complain.store') }}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <label>Name <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control"
                                                placeholder="Enter your name">
                                            @error('name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Phone <span class="text-danger">*</span></label>
                                                    <input type="number" name="phone" id="phone" class="form-control"
                                                        placeholder="Enter your phone">
                                                        <span id="phoneFeedback" class="small text-danger"></span>
                                                    @error('phone')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Attached file</label>
                                            <input type="file" name="image" class="form-control"
                                                accept=".jpg,.jpeg,.png">
                                            @error('image')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>Complain Details <span class="text-danger">*</span></label>
                                            <textarea class="form-control" name="complain_details" placeholder="Enter complain details"></textarea>
                                            @error('complain_details')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn common-btn">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
<script>
        document.getElementById('phone').addEventListener('input', function() {
            const phoneInput = this.value;
            const phoneFeedback = document.getElementById('phoneFeedback');
            const regex = /^(01[3-9]\d{8})$/;

            if (phoneInput === '') {
                phoneFeedback.textContent = '';
            } else if (!regex.test(phoneInput)) {
                phoneFeedback.classList.add('text-danger');
                phoneFeedback.textContent = 'Please enter a valid Bangladeshi phone number (e.g. 0171XXXXXXX)';
            } else {
                phoneFeedback.textContent = 'Valid phone number!';
                phoneFeedback.classList.remove('text-danger');
                phoneFeedback.classList.add('text-success');
            }
        });

        // Also validate when the field loses focus
        document.getElementById('phone').addEventListener('blur', function() {
            const phoneInput = this.value;
            const phoneFeedback = document.getElementById('phoneFeedback');
            const regex = /^(01[3-9]\d{8})$/;

            if (phoneInput === '') {
                phoneFeedback.textContent = 'Phone number is required';
            } else if (!regex.test(phoneInput)) {
                phoneFeedback.textContent = 'Please enter a valid Bangladeshi phone number (e.g. 0171XXXXXXX)';
            }
        });
    </script>
@endpush
