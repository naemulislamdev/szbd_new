@extends('web.layouts.app')

@section('title', 'Contact Us')

@push('css_or_js')
   <!-- Open Graph -->
<meta property="og:title" content="Contact {{ strip_tags($web_config['name']->value) }}">
<meta property="og:description"
      content="{{ substr(strip_tags($web_config['about']->value), 0, 100) }}">
<meta property="og:image"
      content="{{ asset('assets/storage/company/'.$web_config['web_logo']->value) }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:type" content="website">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Contact {{ strip_tags($web_config['name']->value) }}">
<meta name="twitter:description"
      content="{{ substr(strip_tags($web_config['about']->value), 0, 100) }}">
<meta name="twitter:image"
      content="{{ asset('assets/storage/company/'.$web_config['web_logo']->value) }}">
<meta name="twitter:url" content="{{ url()->current() }}">


    <style>
        .headerTitle {
            font-size: 25px;
            font-weight: 700;
            margin-top: 2rem;
        }

        .for-contac-image {
            padding: 6%;
            width: 100%;
        }

        .for-send-message {
            padding: 26px;
            margin-bottom: 2rem;
            margin-top: 2rem;
            box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 12px;
        }

        @media (max-width: 600px) {
            .sidebar_heading {
                background: {{ $web_config['primary_color'] }}
            }

            .headerTitle {

                font-weight: 700;
                margin-top: 1rem;
            }

            .sidebar_heading h1 {
                text-align: center;
                color: aliceblue;
                padding-bottom: 17px;
                font-size: 19px;
            }
        }
    </style>
@endpush
@section('content')
    <div class="container rtl">
        <div class="row">
            <div class="col-md-12 sidebar_heading text-center mb-2">
                <h1 class="h3  mb-0 folot-left headerTitle">Contact us</h1>
            </div>
        </div>
    </div>

    <!-- Split section: Map + Contact form-->
    <div class="container rtl" style="text-align: left;">
        <div class="row no-gutters">
            <div class="col-lg-6 iframe-full-height-wrap ">
                <img style="" class="for-contac-image" src="{{ asset('assets/frontend/png/contact.png') }}"
                    alt="">
            </div>
            <div class="col-lg-6 for-send-message px-4 px-xl-5  box-shadow-sm">
                <h2 class="h4 mb-4 text-center" style="color: #030303; font-weight:600;">
                    Send us a message</h2>
                <form action="{{ route('contact.store') }}" method="POST" id="getResponse">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Your name</label>
                                <input class="form-control name" name="name" type="text" value="{{ old('name') }}"
                                    placeholder="John Doe" required>

                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="cf-email">Email address</label>
                                <input class="form-control email" name="email" type="email" value="{{ old('email') }}"
                                    placeholder="johndoe@email.com" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="cf-phone">Your phone</label>
                                <input class="form-control mobile_number" type="text" name="mobile_number"
                                    value="{{ old('mobile_number') }}"
                                    placeholder="Contact Number" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="cf-subject">Subject:</label>
                                <input class="form-control subject" type="text" name="subject"
                                    value="{{ old('subject') }}" placeholder="Short title"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="cf-message">Message</label>
                                <textarea class="form-control message" name="message" rows="6" required>{{ old('subject') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <button class="btn btn-primary" type="submit">Send message</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
