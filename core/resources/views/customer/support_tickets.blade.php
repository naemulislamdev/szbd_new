@extends('customer.layouts.master')

@section('title', 'My Support Tickets')

@push('c_css')
    <style>
        .marl {
            margin 'left': 7px;
        }

        tr td {
            padding: 3px 5px !important;
        }

        td button {
            padding: 3px 13px !important;
        }
    </style>
@endpush

@section('customer_content')

    <div class="modal fade rtl" id="open-ticket" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg  " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="modal-title font-nameA ">Submit new ticket</h5>
                        </div>
                        <div class="col-md-12" style=" color: #030303;  margin-top: 1rem;">
                            <span>You will get response.</span>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <form class="mt-3" method="post" action="{{ route('ticket-submit') }}" id="open-ticket">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="firstName">Subject</label>
                                <input type="text" class="form-control" id="ticket-subject" name="ticket_subject"
                                    required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <div class="">
                                    <label class="" for="inlineFormCustomSelect">Type</label>
                                    <select class="custom-select " id="ticket-type" name="ticket_type" required>
                                        <option value="Website problem">Website problem</option>
                                        <option value="Partner request">partner_request</option>
                                        <option value="Complaint">Complaint</option>
                                        <option value="Info inquiry">Info inquiry </option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="">
                                    <label class="" for="inlineFormCustomSelect">Priority</label>
                                    <select class="form-control custom-select" id="ticket-priority" name="ticket_priority"
                                        required>
                                        <option value>Choose priority</option>
                                        <option value="Urgent">Urgent</option>
                                        <option value="High">High</option>
                                        <option value="Medium">Medium</option>
                                        <option value="Low">Low</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="detaaddressils">Describe your issue</label>
                                <textarea class="form-control" rows="6" id="ticket-description" name="ticket_description"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer" style="padding: 0px!important;">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">close</button>
                            <button type="submit" class="btn btn-primary">Submit a ticket</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Title-->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="mb-0 headerTitle">Support ticket</h1>

                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#open-ticket">
                    Add new ticket
                </button>
            </div>

        </div>
    </div>
    <!-- Page Content-->
    <!-- Tickets list-->
    @php($allTickets = App\Models\SupportTicket::where('customer_id', auth('customer')->id())->get())
    <div class="card box-shadow-sm">
        <div style="overflow: auto">
            <table class="table">
                <thead>
                    <tr style="background: #6b6b6b">
                        <td class="tdBorder" style="width: 30%;">
                            <div class="py-2"><span class="d-block spandHeadO ">Topic</span></div>
                        </td>
                        <td class="tdBorder" style="width: 20%;">
                            <div class="py-2"><span class="d-block spandHeadO ">Submition date</span>
                            </div>
                        </td>
                        <td class="tdBorder" style="width: 15%;">
                            <div class="py-2"><span class="d-block spandHeadO">Type</span>
                            </div>
                        </td>
                        <td class="tdBorder" style="width: 9%;">
                            <div class="py-2">
                                <span class="d-block spandHeadO">
                                    Status
                                </span>
                            </div>
                        </td>
                        <td class="tdBorder" style="width: 9%;">
                            <div class="py-2">
                                <span class="d-block spandHeadO"><i class="fa fa-eye"></i></span>
                            </div>
                        </td>
                        <td class="tdBorder" style="width: 7%;">
                            <div class="py-2"><span class="d-block spandHeadO">Action </span></div>
                        </td>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($allTickets as $ticket)
                        <tr>
                            <td class="bodytr font-weight-bold" style="color: {{ $web_config['primary_color'] }}">
                                <span class="marl">{{ $ticket['subject'] }}</span>
                            </td>
                            <td class="bodytr">
                                <span>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $ticket['created_at'])->format('Y-m-d h:i A') }}</span>
                            </td>
                            <td class="bodytr"><span class="">{{ $ticket['type'] }}</span></td>
                            <td class="bodytr"><span class="">{{ $ticket['status'] }}</span></td>

                            <td class="bodytr">
                                <span class="">
                                    <a class="btn btn-primary btn-sm"
                                        href="{{ route('support-ticket.index', $ticket['id']) }}">View
                                    </a>
                                </span>
                            </td>

                            <td class="bodytr">
                                <a href="javascript:"
                                    onclick="Swal.fire({
                                               title: 'Do you want to delete this?',
                                               showDenyButton: true,
                                               showCancelButton: true,
                                               confirmButtonColor: '{{ $web_config['primary_color'] }}',
                                               cancelButtonColor: '{{ $web_config['secondary_color'] }}',
                                               confirmButtonText: `Yes`,
                                               denyButtonText: `Don't Delete`,
                                               }).then((result) => {
                                               if (result.value) {
                                               Swal.fire('Deleted!', '', 'success')
                                               location.href='{{ route('support-ticket.delete', ['id' => $ticket->id]) }}';
                                               } else{
                                               Swal.fire('Cancelled', '', 'info')
                                               }
                                               })"
                                    id="delete" class=" marl">
                                    <i class="fa fa-trash" style="font-size: 25px; color:#e81616;"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
