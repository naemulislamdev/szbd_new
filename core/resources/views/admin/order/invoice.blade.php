<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>invoice</title>
    <meta http-equiv="Content-Type" content="text/html;" />
    <meta charset="UTF-8">
    <style media="all">
        * {
            margin: 0;
            padding: 0;
            line-height: 1.3;
            font-family: sans-serif;
            color: #333542;
        }

        /* IE 6 */
        * html .footer {
            position: absolute;
            top: expression((0-(footer.offsetHeight)+(document.documentElement.clientHeight ? document.documentElement.clientHeight : document.body.clientHeight)+(ignoreMe=document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop))+'px');
        }

        body {
            font-size: .875rem;
        }

        .gry-color *,
        .gry-color {
            color: #333542;
        }

        table {
            width: 100%;
        }

        table th {
            font-weight: normal;
        }

        table.padding th {
            padding: .5rem .7rem;
        }

        table.padding td {
            padding: .7rem;
        }

        table.sm-padding td {
            padding: .2rem .7rem;
        }

        .border-bottom td,
        .border-bottom th {
            border-bottom: 0px solid{{ $web_config['primary_color'] }};
            ;
        }

        .col-12 {
            width: 100%;
        }

        [class*='col-'] {
            float: left;
            /*border: 1px solid #F3F3F3;*/
        }

        .row:after {
            content: ' ';
            clear: both;
            display: block;
        }

        .wrapper {
            width: 100%;
            height: auto;
            margin: 0 auto;
        }

        .header-height {
            height: 15px;
            border: 1px{{ $web_config['primary_color'] }};
            background: {{ $web_config['primary_color'] }};
        }

        .content-height {
            display: flex;
        }

        .customers {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        table.customers {
            background-color: #FFFFFF;
        }

        table.customers>tr {
            background-color: #FFFFFF;
        }

        table.customers tr>td {
            border-top: 5px solid #FFF;
            border-bottom: 5px solid #FFF;
        }

        .header {
            border: 1px solid #ecebeb;
        }

        .customers th {
            /*border: 1px solid #A1CEFF;*/
            padding: 8px;
        }

        .customers td {
            /*border: 1px solid #F3F3F3;*/
            padding: 14px;
        }

        .customers th {
            color: white;
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
        }

        .bg-primary {
            /*font-weight: bold !important;*/
            font-size: 0.95rem !important;
            text-align: left;
            color: white;
            {{-- background-color:  {{$web_config['primary_color']}}; --}} background-color: {{ $web_config['primary_color'] }};
        }

        .bg-secondary {
            /*font-weight: bold !important;*/
            font-size: 0.95rem !important;
            text-align: left;
            color: #333542 !important;
            background-color: #E6E6E6;
        }

        .big-footer-height {
            height: 250px;
            display: block;
        }

        .table-total {
            font-family: Arial, Helvetica, sans-serif;
        }

        .table-total th,
        td {
            text-align: left;
            padding: 10px;
        }

        .footer-height {
            height: 75px;
        }

        .for-th {
            color: white;
            {{-- border: 1px solid  {{$web_config['primary_color']}}; --}}
        }

        .for-th-font-bold {
            /*font-weight: bold !important;*/
            font-size: 0.95rem !important;
            text-align: left !important;
            color: #333542 !important;
            background-color: #E6E6E6;
        }

        .for-tb {
            margin: 10px;
        }

        .for-tb td {
            /*margin: 10px;*/
            border-style: hidden;
        }


        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .small {
            font-size: .85rem;
        }

        .currency {}

        .strong {
            font-size: 0.95rem;
        }

        .bold {
            font-weight: bold;
        }

        .for-footer {
            position: relative;
            left: 0;
            bottom: 0;
            width: 100%;
            background-color: rgb(214, 214, 214);
            height: auto;
            margin: auto;
            text-align: center;
        }

        .flex-start {
            display: flex;
            justify-content: flex-start;
        }

        .flex-end {
            display: flex;
            justify-content: flex-end;
        }

        .flex-between {
            display: flex;
            justify-content: space-between;
        }

        .inline {
            display: inline;
        }

        .content-position {
            padding: 15px 40px;
        }

        .content-position-y {
            padding: 0px 40px;
        }

        .triangle {
            width: 0;
            height: 0;

            border: 22px solid{{ $web_config['primary_color'] }};
            ;

            border-top-color: transparent;
            border-bottom-color: transparent;
            border-right-color: transparent;
        }

        .triangle2 {
            width: 0;
            height: 0;
            border: 22px solid white;
            border-top-color: white;
            border-bottom-color: white;
            border-right-color: white;
            border-left-color: transparent;
        }

        .h1 {
            font-size: 2em;
            margin-block-start: 0.67em;
            margin-block-end: 0.67em;
            margin-inline-start: 0px;
            margin-inline-end: 0px;
            font-weight: bold;
        }

        .h2 {
            font-size: 1.5em;
            margin-block-start: 0.83em;
            margin-block-end: 0.83em;
            margin-inline-start: 0px;
            margin-inline-end: 0px;
            font-weight: bold;
        }

        .h4 {
            margin-block-start: 1.33em;
            margin-block-end: 1.33em;
            margin-inline-start: 0px;
            margin-inline-end: 0px;
            font-weight: bold;
        }

        .montserrat-normal-600 {
            font-family: Montserrat;
            font-style: normal;
            font-weight: 600;
            font-size: 18px;
            line-height: 6px;
            /* or 150% */


            color: #363B45;
        }

        .montserrat-bold-700 {
            font-family: Montserrat;
            font-style: normal;
            font-weight: 700;
            font-size: 18px;
            line-height: 6px;
            /* or 150% */


            color: #363B45;
        }

        .text-white {
            color: white !important;
        }

        .bs-0 {
            border-spacing: 0;
        }
    </style>
</head>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<body onload="window.print()">
    @php
        use App\Models\BusinessSetting;
        $company_phone = BusinessSetting::where('type', 'company_phone')->first()->value;
        $company_email = BusinessSetting::where('type', 'company_email')->first()->value;
        $company_name = BusinessSetting::where('type', 'company_name')->first()->value;
        $company_web_logo = BusinessSetting::where('type', 'company_web_logo')->first()->value;
        $company_mobile_logo = BusinessSetting::where('type', 'company_mobile_logo')->first()->value;
    @endphp
    <div class="row">
        <section>
            <table class="content-position-y" style="width: 100%">
                <tr>
                    @if ($order->shippingAddress)
                        <td valign="top">
                            <span class="h2" style="margin: 0px;">Shipping to: </span>
                            <div class="h4 montserrat-normal-600">
                                <p style=" margin-top: 6px; margin-bottom:0px;">
                                    {{ $order->shippingAddress ? $order->shippingAddress['contact_person_name'] : $order->customer['f_name'] . ' ' . $order->customer['l_name'] }}
                                </p>
                                <p style=" margin-top: 6px; margin-bottom:0px;">
                                    {{ $order->shippingAddress ? $order->shippingAddress['phone'] : $order->customer['phone'] }}
                                </p>
                                <p style=" margin-top: 6px; margin-bottom:0px;">
                                    {{ $order->shippingAddress ? $order->shippingAddress['address'] : '' }}</p>
                                <p style=" margin-top: 6px; margin-bottom:0px;">
                                    {{ $order->shippingAddress ? $order->shippingAddress['city'] : '' }}
                                    {{ $order->shippingAddress ? $order->shippingAddress['zip'] : '' }}</p>
                                <p style=" margin-top: 6px; margin-bottom:0px;"> invoice: #{{ $order->id }}</p>
                                <p style=" margin-top: 6px; margin-bottom:0px;">
                                    <span class="inline h4">
                                        <strong
                                            style="color: #030303; ">{{ date('d-m-Y h:i:s a', strtotime($order['created_at'])) }}</strong>
                                    </span>
                                </p>

                            </div>
                        </td>
                        <td valign="top" style="text-align: right">
                            <span class="h2" style="margin-left:10px;">Company Information: </span>
                            <p style="margin-left: 10px; margin-top: 6px; margin-bottom:0px;"><i
                                    class="fa fa-phone text-white"></i>phone
                                : {{ \App\Models\BusinessSetting::where('type', 'company_phone')->first()->value }}</p>

                            <p style="margin-left: 10px; margin-top: 6px; margin-bottom:0px;"><i
                                    class="fa fa-envelope text-white" aria-hidden="true"></i> email
                                : {{ $company_email }}</p>
                            <p style="margin-left: 10px; margin-top: 6px; margin-bottom:0px;"><i
                                    class="fa fa-map-marker" aria-hidden="true"></i> address
                                : 45, Probal Tower, Ring Road, Mohammadpur. </p>

                        </td>
                    @else
                        <td valign="top">
                            <span class="h2" style="margin: 0px;">customer_info: </span>
                            <div class="h4 montserrat-normal-600">
                                <p style=" margin-top: 6px; margin-bottom:0px;">
                                    {{ $order->customer != null ? $order->customer['f_name'] . ' ' . $order->customer['l_name'] : 'Name not found' }}
                                </p>
                                @if (isset($order->customer) && $order->customer['id'] != 0)
                                    <p style=" margin-top: 6px; margin-bottom:0px;">
                                        {{ $order->customer != null ? $order->customer['email'] : 'Email not found' }}</p>
                                    <p style=" margin-top: 6px; margin-bottom:0px;">
                                        {{ $order->customer != null ? $order->customer['phone'] : 'Phone not found' }}</p>
                                @endif
                                <p style=" margin-top: 6px; margin-bottom:0px;">invoice: #{{ $order->id }}</p>
                                <p style=" margin-top: 6px; margin-bottom:0px;">
                                    <span class="inline h4">
                                        <strong
                                            style="color: #030303; ">{{ date('d-m-Y h:i:s a', strtotime($order['created_at'])) }}</strong>
                                    </span>
                                </p>
                            </div>
                        </td>
                    @endif

                </tr>
            </table>
        </section>
    </div>
    {{-- </table> --}}

    <br>

    <div class="row" style="margin: 20px 0; display:block; height:auto !important ;">
        <div class=" content-height content-position-y" style="">
            <table class="customers bs-0">
                <thead>
                    <tr class="for-th" style=" border: 1px solid #2D7BFF;margin-top: 5px">
                        <th class="for-th for-th-font-bold" style="color: black">No.</th>
                        <th class="for-th for-th-font-bold " style="color: black">Item description</th>
                        <th class="for-th bg-secondary for-th-font-bold" style="color: black">
                            Unit price
                        </th>
                        <th class="for-th for-th-font-bold" style="color: black">
                            Qty
                        </th>
                        <th class="for-th for-th-font-bold" style="color: black">
                            Total
                        </th>
                    </tr>
                </thead>
                @php
                    $subtotal = 0;
                    $total = 0;
                    $sub_total = 0;
                    $total_tax = 0;
                    $total_shipping_cost = 0;
                    $total_discount_on_product = 0;
                    $ext_discount = 0;
                @endphp
                <tbody>
                    @foreach ($order->details as $key => $details)
                        @php $subtotal=($details['price'])*$details->qty @endphp
                        <tr class="for-tb" style=" border: 1px solid #2D7BFF;">
                            <td class="for-tb " style=" border: 1px solid #2D7BFF;">{{ $key + 1 }}</td>
                            <td class="for-tb" style=" border: 1px solid #2D7BFF;">
                                {{ $details['product'] ? $details['product']->name : '' }}
                                <br>
                                variation' : {{ $details['variant'] }}
                            </td>
                            <td class="for-tb " style=" border: 1px solid #2D7BFF;">{{ $details['price'] }}</td>
                            <td class="for-tb" style=" border: 1px solid #2D7BFF;">{{ $details->qty }}</td>
                            <td class="for-tb" style=" border: 1px solid #2D7BFF;">{{ $subtotal }}</td>
                        </tr>

                        @php
                            $sub_total += $details['price'] * $details['qty'];
                            $total_tax += $details['tax'];
                            $total_shipping_cost += $details->shipping ? $details->shipping->cost : 0;
                            $total_discount_on_product += $details['discount'];
                            $total += $subtotal;
                        @endphp
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>
    <?php

    if ($order['extra_discount_type'] == 'percent') {
        $ext_discount = ($sub_total / 100) * $order['extra_discount'];
    } else {
        $ext_discount = $order['extra_discount'];
    }

    ?>
    @php($shipping = $order['shipping_cost'])
    <div class="content-position-y" style=" display:block; height:auto !important;margin-top: 10px">
        <table style="width: 100%;">
            <tr>
                <th style="text-align: left; vertical-align: text-top; width:40%;">
                    @if (!empty($order->socialpage['name']))
                        <h3>Social Page : {{ @$order->socialpage['name'] }}</h3>
                    @endif
                    <hr>
                    <h4 style="color: #130505 !important; margin:0px;">Payment details</h4>
                    <p style="color: #414141 !important ; padding-top:5px;">Payment Status :
                        {{ $order->payment_status }}
                        , {{ date('y-m-d', strtotime($order['created_at'])) }}</p>

                    <p style="color: #414141 !important ; padding-top:5px;">
                        , Paid by: {{ $order->payment_method }}</p>
                    @if ($order->order_type == 'POS')
                        <span>
                            Courier : {{ $order->delivery_service_name }}
                        </span>
                    @endif
                </th>
                <th style="text-align: right;width:60%;">
                    <table style="width: 96%;margin-left:31%; display: inline "
                        class="text-right sm-padding strong bs-0">
                        <tbody>

                            <tr>
                                <th class="gry-color text-left"><b>Sub total</b></th>
                                <td>{{ $sub_total }}</td>
                            </tr>
                            <tr>
                                <th class="gry-color text-left text-uppercase"><b>Tax</b>
                                </th>
                                <td>{{ $total_tax }}</td>
                            </tr>
                            @if ($order->order_type == 'default_type')
                                <tr>
                                    <th class="gry-color text-left"><b>Shipping</b></th>
                                    <td>{{ $shipping }}</td>
                                </tr>
                            @endif
                            <tr>
                                <th class="gry-color text-left"><b>Coupon discount</b>
                                </th>
                                <td>
                                    - {{ $order->discount_amount }} </td>
                            </tr>
                            @if ($order->order_type == 'POS')
                                <tr>
                                    <th class="gry-color text-left"><b>Extra discount</b>
                                    </th>
                                    <td>
                                        - {{ $ext_discount }} </td>
                                </tr>
                            @endif
                            <tr class="border-bottom">
                                <th class="gry-color text-left"><b>Discount on product</b>
                                </th>
                                <td>
                                    - {{ $total_discount_on_product }} </td>
                            </tr>
                            <tr class="border-bottom">
                                <th class="gry-color text-left"><b>Advance ( {{ $order->advance_payment_method }}
                                        )</b></th>
                                <td>
                                    - {{ $order->advance_amount }} </td>
                            </tr>
                            <tr class="border-bottom">
                                <th class="gry-color text-left"><b>Shipping Cost </b></th>
                                <td>
                                    + {{ round($order->shipping_cost, 2) }} </td>
                            </tr>
                            <tr class="bg-primary" style="background-color: #2D7BFF">
                                <th class="text-left"><b>total</b></th>
                                <td>
                                    <b> {{ $order->order_amount - $order->advance_amount }}</b>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </th>
            </tr>
        </table>
    </div>

</body>

</html>
