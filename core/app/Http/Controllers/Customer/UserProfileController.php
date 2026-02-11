<?php

namespace App\Http\Controllers\Customer;

use App\CPU\FileManager;
use App\CPU\OrderManager;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ShippingAddress;
use App\Models\SupportTicket;
use App\Models\Wishlist;
use App\Models\RefundRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;

class UserProfileController extends Controller
{
    public function user_account(Request $request)
    {
        if (auth('customer')->check()) {
            $customerDetail = User::where('id', auth('customer')->id())->first();
            return view('customer.profile', compact('customerDetail'));
        } else {
            return redirect()->route('home');
        }
    }

    public function user_update(Request $request)
    {
        // ---------- Validation ----------
        $request->validate([
            'name' => 'required|string|max:255',
            'phone'  => 'nullable|regex:/^(01[3-9]\d{8})$/',
            'password' => 'nullable|min:6',
            'con_password' => 'same:password',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user = auth('customer')->user();

        // ---------- Image Update ----------
        if ($request->hasFile('image')) {
            $imageName = FileManager::updateFile(
                'profile/',
                $user->image,
                $request->file('image')
            );
        } else {
            $imageName = $user->image;
        }

        // ---------- Base Update Data ----------
        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'image'  => $imageName,
        ];

        // ---------- Password Update (Optional) ----------
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        // ---------- Update User ----------
        if (auth('customer')->check()) {
            User::where('id', $user->id)->update($updateData);
            return back()->with('success', 'Profile updated successfully');
        }

        return redirect()->back();
    }

    public function accountLogout()
    {
        auth()->guard('customer')->logout();
        return to_route('home')->with('info', 'Logout  successfully!!');
    }

    public function account_address()
    {
        if (auth('customer')->check()) {
            $shippingAddresses = ShippingAddress::where('customer_id', auth('customer')->id())->get();
            return view('customer.address', compact('shippingAddresses'));
        } else {
            return redirect()->route('home');
        }
    }

    public function address_store(Request $request)
    {
        $address = [
            'customer_id' => auth('customer')->check() ? auth('customer')->id() : null,
            'contact_person_name' => $request->name,
            'address_type' => 'home',
            'address' => $request->address,
            'city' => $request->city,
            'zip' => $request->zip,
            'phone' => $request->phone
        ];
        ShippingAddress::create($address);
        return back()->with('success', 'New address added successfully');
    }
    public function address_edit(Request $request, $id)
    {
        $shippingAddress = ShippingAddress::where('customer_id', auth('customer')->id())->find($id);
        if (isset($shippingAddress)) {
            return view('customer.account-address-edit', compact('shippingAddress'));
        } else {
            return back()->with('warning', 'Access denied');
        }
    }

    public function address_update(Request $request)
    {
        $updateAddress = [
            'contact_person_name' => $request->name,
            'address_type' => 'home',
            'address' => $request->address,
            'city' => $request->city,
            'zip' => $request->zip,
            'phone' => $request->phone
        ];
        if (auth('customer')->check()) {
            ShippingAddress::where('id', $request->id)->update($updateAddress);
            return redirect()->back();
        } else {
            return redirect()->back();
        }
    }

    public function address_delete(Request $request)
    {
        if (auth('customer')->check()) {
            ShippingAddress::destroy($request->id);
            return back()->with('success', 'Address deleted successfully');
        } else {
            return back()->with('warning', 'Access denied');
        }
    }

    public function account_oder()
    {
        $orders = Order::where('customer_id', auth('customer')->id())->orderBy('id', 'DESC')->paginate(15);
        return view('customer.orders', compact('orders'));
    }

    public function account_order_details(Request $request)
    {
        $order = Order::find($request->id);
        return view('customer.order_details', compact('order'));
    }

    public function account_wishlist()
    {
        if (auth('customer')->check()) {
            $wishlists = Wishlist::where('customer_id', auth('customer')->id())->get();
            return view('customer.wishlist', compact('wishlists'));
        } else {
            return redirect()->route('home');
        }
    }

    public function account_tickets()
    {
        if (auth('customer')->check()) {
            $supportTickets = SupportTicket::where('customer_id', auth('customer')->id())->get();
            return view('customer.support_tickets', compact('supportTickets'));
        } else {
            return redirect()->route('home');
        }
    }

    public function ticket_submit(Request $request)
    {
        $request->validate([
            'ticket_subject' => 'required',
            'ticket_type' => 'required',
            'ticket_priority' => 'required',
            'ticket_description' => 'required',
        ]);
        $ticket = [
            'subject' => $request['ticket_subject'],
            'type' => $request['ticket_type'],
            'customer_id' => auth('customer')->check() ? auth('customer')->id() : null,
            'priority' => $request['ticket_priority'],
            'description' => $request['ticket_description']
        ];
        SupportTicket::create($ticket);
        return back()->with('success', 'Ticket submitted successfully');
    }

    public function single_ticket(Request $request)
    {
        $ticket = SupportTicket::where('id', $request->id)->first();
        return view('customer.ticket-view', compact('ticket'));
    }

    public function comment_submit(Request $request, $id)
    {
        DB::table('support_tickets')->where(['id' => $id])->update([
            'status' => 'open',
            'updated_at' => now(),
        ]);

        DB::table('support_ticket_convs')->insert([
            'customer_message' => $request->comment,
            'support_ticket_id' => $id,
            'position' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return back();
    }

    public function support_ticket_close($id)
    {
        DB::table('support_tickets')->where(['id' => $id])->update([
            'status' => 'close',
            'updated_at' => now(),
        ]);
        return redirect('/account-tickets');
    }


    public function support_ticket_delete(Request $request)
    {

        if (auth('customer')->check()) {
            $support = SupportTicket::find($request->id);
            $support->delete();
            return redirect()->back();
        } else {
            return redirect()->back();
        }
    }

    public function track_order()
    {
        return view('web.order_tracking_page');
    }

    public function track_order_result(Request $request)
    {
        dd('here');
        $user =  auth('customer')->user();
        if (!isset($user)) {
            $user_id = User::where('phone', $request->phone_number)->first()->id;
            $orderDetails = Order::where('id', $request['order_id'])->whereHas('details', function ($query) use ($user_id) {
                $query->where('customer_id', $user_id);
            })->first();
        } else {
            if ($user->phone == $request->phone_number) {
                $orderDetails = Order::where('id', $request['order_id'])->whereHas('details', function ($query) {
                    $query->where('customer_id', auth('customer')->id());
                })->first();
            }
            if ($request->from_order_details == 1) {
                $orderDetails = Order::where('id', $request['order_id'])->whereHas('details', function ($query) {
                    $query->where('customer_id', auth('customer')->id());
                })->first();
            }
        }
        // dd($orderDetails);

        if (isset($orderDetails)) {
            return view('web-views.order-tracking', compact('orderDetails'));
        }

        return to_route('track-order.index')->with('Error', 'Invalid Order Id or Phone Number');
    }

    public function track_last_order()
    {
        $orderDetails = OrderManager::track_order(Order::where('customer_id', auth('customer')->id())->latest()->first()->id);

        if ($orderDetails != null) {
            return view('web-views.order-tracking', compact('orderDetails'));
        } else {
            return redirect()->route('track-order.index')->with('Error', 'Invalid Order Id or Phone Number');
        }
    }

    public function order_cancel($id)
    {
        $order = Order::where(['id' => $id])->first();
        if ($order['payment_method'] == 'cash_on_delivery' && $order['order_status'] == 'pending') {
            OrderManager::stock_update_on_order_status_change($order, 'canceled');
            Order::where(['id' => $id])->update([
                'order_status' => 'canceled'
            ]);
            return back()->with('success', 'Order canceled successfully');
        }
        return back()->with('error', 'Status not changable now');
    }

    public function generate_invoice($id)
    {
        $order = Order::with('shipping')->where('id', $id)->first();
        $data["email"] = $order->customer["email"];
        $data["order"] = $order;

        return view('customer.partials.invoice', compact('order'));
        $pdf = PDF::loadView('customer.partials.invoice', $data);
        return $pdf->download($order->id . '.pdf');
    }
    public function refund_details($id)
    {
        $order_details = OrderDetail::find($id);

        $refund = RefundRequest::where('customer_id', auth('customer')->id())
            ->where('order_details_id', $order_details->id)->first();

        return view('web-views.users-profile.refund-details', compact('order_details', 'refund'));
    }

    public function submit_review($id)
    {
        $order_details = OrderDetail::find($id);
        return view('web-views.users-profile.submit-review', compact('order_details'));
    }
}
