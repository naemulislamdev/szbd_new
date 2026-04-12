<?php

namespace App\Http\Controllers\api\v1\auth;


use App\CPU\CartManager;
use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PassportAuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'f_name' => 'required',
            'l_name' => 'required',
            'email' => 'required|unique:users',
            'phone' => 'required|unique:users',
            'password' => 'required|min:8',
        ], [
            'f_name.required' => 'The first name field is required.',
            'l_name.required' => 'The last name field is required.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $temporary_token = Str::random(40);
        $user = User::create([
            'name' => $request->f_name .' '. $request->l_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'is_active' => 1,
            'password' => bcrypt($request->password),
            'temporary_token' => $temporary_token,
        ]);

        $phone_verification = Helpers::get_business_settings('phone_verification');
        $email_verification = Helpers::get_business_settings('email_verification');
        if ($phone_verification && !$user->is_phone_verified) {
            return response()->json(['temporary_token' => $temporary_token], 200);
        }
        if ($email_verification && !$user->is_email_verified) {
            return response()->json(['temporary_token' => $temporary_token], 200);
        }

        $token = $user->createToken('LaravelAuthApp')->accessToken;
        return response()->json(['token' => $token], 200);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $user_id = $request['email'];
        if (filter_var($user_id, FILTER_VALIDATE_EMAIL)) {
            $medium = 'email';
        } else {
            $count = strlen(preg_replace("/[^\d]/", "", $user_id));
            if ($count >= 9 && $count <= 15) {
                $medium = 'phone';
            } else {
                $errors = [];
                array_push($errors, ['code' => 'email', 'message' => 'Invalid email address or phone number']);
                return response()->json([
                    'errors' => $errors
                ], 403);
            }
        }

        $data = [
            $medium => $user_id,
            'password' => $request->password
        ];

        $user = User::where([$medium => $user_id])->first();

        if (isset($user) && $user->is_active && auth()->attempt($data)) {
            $user->temporary_token = Str::random(40);
            $user->save();

            $phone_verification = Helpers::get_business_settings('phone_verification');
            $email_verification = Helpers::get_business_settings('email_verification');
            if ($phone_verification && !$user->is_phone_verified) {
                return response()->json(['temporary_token' => $user->temporary_token], 200);
            }
            if ($email_verification && !$user->is_email_verified) {
                return response()->json(['temporary_token' => $user->temporary_token], 200);
            }

            $token = auth()->user()->createToken('LaravelAuthApp')->accessToken;
            $user1=auth()->user();
             CartManager::cart_to_db();
            return response()->json(['token' => $token,'session_cart' => session('offline_cart')
], 200);
        } else {
            $errors = [];
            array_push($errors, ['code' => 'auth-001', 'message' => 'Customer_not_found_or_Account_has_been_suspended']);
            return response()->json([
                'errors' => $errors
            ], 401);
        }
    }

    public function sendOtp(Request $request){
        // dd($request);
    $otp = rand(1000,9999);
    $tToken = Str::random(40);
    $customerCheck = User::where('phone',$request->phone)->first();
    if($customerCheck){
        $user = User::where('phone','=',$request->phone)->update(['otp' => $otp,'temporary_token' => $tToken]);

    // send otp to mobile no using sms apitemporary_token

        // $number="0$request->mobile;";
        // $text="Your verification code is $otp ";
        // echo "?masking=Flingex&userName=Flingex&password=b57d05174707d151a9369e79af41a5c5&MsgType=TEXT&receiver=$number&message=$text";
        // $url = "http://api.boom-cast.com/boomcast/WebFramework/boomCastWebService/externalApiSendTextMessage.php";
        // dd($url);
        //  http://188.138.41.146:7788/sendtext?apikey=3d280e4da66f9c17&secretkey=b171edf5&callerID=Sajerbela&toUser=01739921850&messageContent=MESSAGE
        // $url = "http://66.45.237.70/api.php";
        $url = env('SMS_API_URL', 'http://188.138.41.146:7788/sendtext');

        $number=$request->phone;

        $text="Your OTP is $otp Regards sajerbela.com";

        $data= array(
        'apikey'=> env('SMS_API_KEY', ''),
        'secretkey'=> env('SMS_SECRET_KEY', ''),
        'callerID'=> env('SMS_CALLER_ID', 'sajerbela'),
        'toUser'=>"$number",
        'messageContent'=>"$text"
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $smsresult = curl_exec($ch);
        curl_close($ch);


        if (isset($customerCheck)) {


            return response()->json(['message' => 'OTP sent to your phone number!','success'=>'1'
], 200);
        } else {
            $errors = [];
            array_push($errors, ['code' => 'auth-001', 'message' => 'Customer_not_found_or_Account_has_been_suspended']);
            return response()->json([
                'errors' => $errors
            ], 401);
        }

    }
    else{
        $customer = new User;
        $customer->f_name=$request->phone."F";
        $customer->email=$request->phone."@sajerbela.com";
        $customer->phone=$request->phone;
        $customer->otp=$otp;
        $customer->password = bcrypt($request->phone);
        $customer->temporary_token = $tToken;
        $customer->save();

        $url = env('SMS_API_URL', 'http://sms.dinisoftbd.com:7790/sendtext');
        $number=$request->phone;
        $text="Your OTP is $otp Regards sajerbela.com";

        $data= array(
        'apikey'=> env('SMS_API_KEY', ''),
        'secretkey'=> env('SMS_SECRET_KEY', ''),
        'callerID'=> env('SMS_CALLER_ID', 'sajerbela'),
        'toUser'=>"$number",
        'messageContent'=>"$text"
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $smsresult = curl_exec($ch);
        curl_close($ch);


            if (isset($customer)) {


            return response()->json(['message' => 'OTP sent to your phone number!','success'=>'1'
], 200);
        } else {
            $errors = [];
            array_push($errors, ['code' => 'auth-001', 'message' => 'Customer_not_found_or_Account_has_been_suspended']);
            return response()->json([
                'errors' => $errors
            ], 401);
        }
    }
    }

    public function recivedOTP(Request $request){
        if($request->otp){
            $finduser = User::where('otp',$request->otp)->first();

            if (!$finduser) {
                return response()->json([
                    'errors' => [['code' => 'auth-001', 'message' => 'Invalid OTP.']]
                ], 401);
            }

            auth()->guard('customer')->login($finduser);
            $token = $finduser->createToken('LaravelAuthApp')->accessToken;

            // Clear OTP after successful verification
            $finduser->otp = null;
            $finduser->save();

            CartManager::cart_to_db();
            return response()->json(['message' => 'login successfully','success'=>'1', 'token' => $token], 200);

        } else {
            $errors = [];
            array_push($errors, ['code' => 'auth-001', 'message' => 'OTP is required.']);
            return response()->json([
                'errors' => $errors
            ], 401);
        }
    }
}
