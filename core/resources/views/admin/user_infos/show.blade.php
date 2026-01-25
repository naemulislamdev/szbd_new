<div class="container-fluid text-left">

    <div class="row mb-2">
        <div class="col-5 font-weight-bold">Name</div>
        <div class="col-7">: {{ $item->name }}</div>
    </div>

    <div class="row mb-2">
        <div class="col-5 font-weight-bold">Email</div>
        <div class="col-7">: {{ $item->email ?? 'N/A' }}</div>
    </div>

    <div class="row mb-2">
        <div class="col-5 font-weight-bold">Phone</div>
        <div class="col-7">: {{ $item->phone }}</div>
    </div>

    <div class="row mb-2">
        <div class="col-5 font-weight-bold">Address</div>
        <div class="col-7">: {{ $item->address ?? 'N/A' }}</div>
    </div>

    <hr>

    <div class="col-12 row mb-3 px-0">
        <div class="col-5 font-weight-bold">Date</div>
        <div class="col-7">
            :
            {{ \Carbon\Carbon::parse($item->created_at)->format('d F Y g.i A') }}
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-5 font-weight-bold">Status</div>
        <div class="col-7">
            :
            {!! $item->status == 1
                ? '<span class="badge badge-success">Seen</span>'
                : '<span class="badge badge-primary">Unseen</span>' !!}
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-5 font-weight-bold">Order Process</div>
        <div class="col-7">
            :
            @if ($item->order_process === 'pending')
                <span class="badge badge-warning">Pending</span>
            @elseif ($item->order_process === 'completed')
                <span class="badge badge-success">Confirmed</span>
            @else
                <span class="badge badge-secondary">{{ ucfirst($item->order_process) }}</span>
            @endif
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-5 font-weight-bold">Order Status</div>
        <div class="col-7">: {{ ucfirst($item->order_status) }}</div>
    </div>


    <div class="row mb-2">
        <div class="col-5 font-weight-bold">Order Status Note</div>
        <div class="col-7">: {{ $item->order_note ?? 'N/A' }}</div>
    </div>

 @if ($item->confirmed_by)
    @php
        $confirmed = json_decode($item->confirmed_by);
    @endphp

    @if ($confirmed)
        <div class="row mb-2">
            <div class="col-5 font-weight-bold">Order Status Confirmed By</div>
            <div class="col-7">
                : <span class="badge badge-success">
                    {{ $confirmed->user ?? 'Unknown' }} => Date: {{ $confirmed->time ?? 'N/A' }}
                </span>
            </div>
        </div>
    @endif
@endif

@if ($item->canceled_by)
    @php
        $canceled = json_decode($item->canceled_by);
    @endphp

    @if ($canceled)
        <div class="row mb-2">
            <div class="col-5 font-weight-bold">Order Status Canceled By</div>
            <div class="col-7">
                : <span class="badge badge-danger">
                    {{ $canceled->user ?? 'Unknown' }} => Date: {{ $canceled->time ?? 'N/A' }}
                </span>
            </div>
        </div>
    @endif
@endif



    {{-- Product Details --}}
    <div class="row mb-2">
        <div class="col-12 font-weight-bold mb-1">Product Details</div>
        <div class="col-12">

            @php
                $product_details = json_decode($item->product_details, true);
            @endphp

            @if (is_array($product_details) && count($product_details) > 0)

                {{-- Multiple products --}}
                @if (isset($product_details[0]) && is_array($product_details[0]))
                    @foreach ($product_details as $p)
                        <div class="border rounded p-2 mb-2">
                            <strong>Product ID:</strong> {{ $p['id'] ?? 'N/A' }} <br>

                            @if (!empty($p['variant']))
                                <strong>Variant:</strong> {{ $p['variant'] }} <br>
                            @endif

                            @if (!empty($p['variations']) && is_array($p['variations']))
                                <strong>Variations:</strong>
                                @foreach ($p['variations'] as $k => $v)
                                    {{ ucfirst($k) }}: {{ ucfirst($v) }}@if (!$loop->last)
                                        ,
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    @endforeach

                    {{-- Single product --}}
                @else
                    <div class="border rounded p-2">
                        <strong>Product ID:</strong> {{ $product_details['product_id'] ?? 'N/A' }} <br>

                        @if (!empty($product_details['color']))
                            <strong>Color:</strong> {{ $product_details['color'] }} <br>
                        @endif

                        @if (!empty($product_details['choice_8']))
                            <strong>Size:</strong> {{ $product_details['choice_8'] }} <br>
                        @endif
                    </div>
                @endif
            @else
                <p>No product details available</p>
            @endif

        </div>
    </div>

    <div class="row border">
        <div class="col-12">
            <div class="mt-3">
                <h4>Order Note:</h4>

                <ul id="noteList" class="list-unstyled d-flex flex-column">
                    @if ($item->multiple_note)
                        @foreach (json_decode($item->multiple_note, true) as $note)
                            <li style="text-align: left;  text-wrap: wrap; line-height: 20px"
                                class="badge badge-soft-primary mb-2 py-2">
                                {{ $note['note'] }}
                                <span class="text-muted">({{ $note['time'] }} -> Note by:
                                    {{ $note['user'] }})</span>
                            </li>
                        @endforeach

                    @endif
                </ul>
            </div>
        </div>
        <div class="col-12">
            <form id="orderNoteForm" style="padding-top: 20px;">
                @csrf
                <input type="hidden" name="id" value="{{ $item['id'] }}">
                <label>Add Note</label>
                <div class="input-group mb-3">
                    <input autofocus required type="text" class="form-control" name="multiple_note[]"
                        placeholder="Enter New note">
                    <button class="btn btn-primary" type="submit">Submit</button>
                </div>

                @error('multiple_note')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </form>

        </div>
    </div>
