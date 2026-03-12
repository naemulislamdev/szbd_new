<?php

namespace App\Http\Controllers\Backend;


// use App\CPU\ImageManager;

use App\CPU\FileManager;
use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use App\Model\SocialMedia;
use App\facebook_post;
use App\metaAdd;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BusinessSettingsController extends Controller
{
    public function index()
    {
        $google_store = BusinessSetting::where('type', 'download_app_google_store')->first();
        $apple_store = BusinessSetting::where('type', 'download_app_apple_store')->first();
        $admin_shop_banner = BusinessSetting::where('type', 'company_web_logo')->first();
        return view('admin.web_config.index', compact('google_store', 'apple_store', 'admin_shop_banner'));
    }

    // no need
    public function updateInfo2(Request $request)
    {


        if ($request['email_verification'] == 1) {
            $request['phone_verification'] = 0;
        } elseif ($request['phone_verification'] == 1) {
            $request['email_verification'] = 0;
        }

        //comapy shop banner
        $imgBanner = BusinessSetting::where(['type' => 'shop_banner'])->first()['value'];
        $imgBanner = isset($imgBanner) ? 'assets/storage/shop/' . $imgBanner : null;
        if ($request->has('shop_banner')) {

            $imgBanner = FileManager::updateFile('shop/', $imgBanner, $request->file('shop_banner'));

            DB::table('business_settings')->updateOrInsert(['type' => 'shop_banner'], [
                'value' => $imgBanner
            ]);
        }
        // comapny name
        DB::table('business_settings')->updateOrInsert(['type' => 'company_name'], [
            'value' => $request['company_name']
        ]);
        // company email
        DB::table('business_settings')->updateOrInsert(['type' => 'company_email'], [
            'value' => $request['company_email']
        ]);
        // company Phone
        DB::table('business_settings')->updateOrInsert(['type' => 'company_phone'], [
            'value' => $request['company_phone']
        ]);
        DB::table('business_settings')->updateOrInsert(['type' => 'company_hotline'], [
            'value' => $request['company_hotline']
        ]);
        // stock limit
        DB::table('business_settings')->updateOrInsert(['type' => 'stock_limit'], [
            'value' => $request['stock_limit']
        ]);
        //company copy right text
        DB::table('business_settings')->updateOrInsert(['type' => 'company_copyright_text'], [
            'value' => $request['company_copyright_text']
        ]);
        //company time zone
        DB::table('business_settings')->updateOrInsert(['type' => 'timezone'], [
            'value' => $request['timezone']
        ]);
        //country
        DB::table('business_settings')->updateOrInsert(['type' => 'country_code'], [
            'value' => $request['country']
        ]);
        //phone verification
        DB::table('business_settings')->updateOrInsert(['type' => 'phone_verification'], [
            'value' => $request['phone_verification']
        ]);
        //email verification
        DB::table('business_settings')->updateOrInsert(['type' => 'email_verification'], [
            'value' => $request['email_verification']
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'order_verification'], [
            'value' => $request['order_verification']
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'forgot_password_verification'], [
            'value' => $request['forgot_password_verification']
        ]);
        DB::table('business_settings')->updateOrInsert(['type' => 'decimal_point_settings'], [
            'value' => $request['decimal_point_settings']
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'shop_address'], [
            'value' => $request['shop_address']
        ]);


        //web logo
        $webLogo = BusinessSetting::where(['type' => 'company_web_logo'])->first()['value'];
        if ($request->has('company_web_logo')) {
            $webLogo = FileManager::updateFile('company/', 'assets/storage/company/' . $webLogo, $request->file('company_web_logo'));
            BusinessSetting::where(['type' => 'company_web_logo'])->update([
                'value' => $webLogo,
            ]);
        }

        //mobile logo
        $mobileLogo = BusinessSetting::where(['type' => 'company_mobile_logo'])->first()['value'];
        if ($request->has('company_mobile_logo')) {

            $mobileLogo = FileManager::updateFile('company/', 'assets/storage/company/' . $mobileLogo, $request->file('company_mobile_logo'));
            BusinessSetting::where(['type' => 'company_mobile_logo'])->update([
                'value' => $mobileLogo,
            ]);
        }
        //web footer logo
        $webFooterLogo = BusinessSetting::where(['type' => 'company_footer_logo'])->first();

        if ($request->has('company_footer_logo')) {
            // $webFooterLogo = ImageManager::update('company/', $webFooterLogo, 'png', $request->file('company_footer_logo'));
            $webFooterLogo = FileManager::updateFile('company/', 'assets/storage/company/' . $webFooterLogo['value'], $request->file('company_footer_logo'));
            BusinessSetting::where(['type' => 'company_footer_logo'])->update([
                'value' => $webFooterLogo,
            ]);
        }
        //fav icon
        $favIcon = BusinessSetting::where(['type' => 'company_fav_icon'])->first()['value'];
        if ($request->has('company_fav_icon')) {
            $favIcon = FileManager::updateFile('company/', 'assets/storage/' . $favIcon, $request->file('company_fav_icon'));
            BusinessSetting::where(['type' => 'company_fav_icon'])->update([
                'value' => $favIcon,
            ]);
        }

        //loader gif
        $loader_gif = BusinessSetting::where(['type' => 'loader_gif'])->first();
        if ($request->has('loader_gif')) {

            $loader_gif = FileManager::updateFile('company/', 'assets/storage/company' . $loader_gif['value'], $request->file('loader_gif'));
            BusinessSetting::updateOrInsert(['type' => 'loader_gif'], [
                'value' => $loader_gif,
            ]);
        }
        // web color setup
        $colors = BusinessSetting::where('type', 'colors')->first();
        if (isset($colors)) {
            BusinessSetting::where('type', 'colors')->update([
                'value' => json_encode(
                    [
                        'primary' => $request['primary'],
                        'secondary' => $request['secondary'],
                    ]
                ),
            ]);
        } else {
            DB::table('business_settings')->insert([
                'type' => 'colors',
                'value' => json_encode(
                    [
                        'primary' => $request['primary'],
                        'secondary' => $request['secondary'],
                    ]
                ),
            ]);
        }
        DB::table('business_settings')->updateOrInsert(['type' => 'announcement'], [
            'value' => json_encode(
                [
                    'status' => $request['announcement_status'],
                    'color' => $request['announcement_color'],
                    'text_color' => $request['text_color'],
                    'announcement' => $request['announcement'],
                ]
            ),
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'default_location'], [
            'value' => json_encode(
                [
                    'lat' => $request['latitude'],
                    'lng' => $request['longitude'],
                ]
            ),
        ]);

        //pagination
        $request->validate([
            'pagination_limit' => 'numeric',
        ]);
        DB::table('business_settings')->updateOrInsert(['type' => 'pagination_limit'], [
            'value' => $request['pagination_limit'],
        ]);


        return redirect()->back()->with('success', 'Company info updated successfully!');
    }
    public function updateInfo(Request $request)
    {
        // 1) Make email/phone verification mutually exclusive
        $email = (int) $request->input('email_verification', 0);
        $phone = (int) $request->input('phone_verification', 0);

        if ($email === 1) $phone = 0;
        if ($phone === 1) $email = 0;

        // 2) Simple scalar settings (single loop)
        $settings = [
            'company_name' => $request->input('company_name'),
            'company_email' => $request->input('company_email'),
            'company_phone' => $request->input('company_phone'),
            'company_hotline' => $request->input('company_hotline'),
            'stock_limit' => $request->input('stock_limit'),
            'company_copyright_text' => $request->input('company_copyright_text'),
            'timezone' => $request->input('timezone'),
            'country_code' => $request->input('country'),
            'phone_verification' => $phone,
            'email_verification' => $email,
            'order_verification' => $request->input('order_verification'),
            'forgot_password_verification' => $request->input('forgot_password_verification'),
            'decimal_point_settings' => $request->input('decimal_point_settings'),
            'shop_address' => $request->input('shop_address'),
        ];

        foreach ($settings as $type => $value) {
            DB::table('business_settings')->updateOrInsert(['type' => $type], ['value' => $value]);
        }

        // 3) Files (small helper)
        $this->updateSettingFile($request, 'shop_banner', 'shop/', 'assets/storage/shop/');
        $this->updateSettingFile($request, 'company_web_logo', 'company/', 'assets/storage/company/', true);
        $this->updateSettingFile($request, 'company_mobile_logo', 'company/', 'assets/storage/company/', true);
        $this->updateSettingFile($request, 'company_footer_logo', 'company/', 'assets/storage/company/', true);
        $this->updateSettingFile($request, 'company_fav_icon', 'company/', 'assets/storage/', true);
        $this->updateSettingFile($request, 'loader_gif', 'company/', 'assets/storage/company/', false, true);

        // 4) JSON settings (small helper)
        $this->updateSettingJson('colors', [
            'primary' => $request->input('primary'),
            'secondary' => $request->input('secondary'),
        ]);

        $this->updateSettingJson('announcement', [
            'status' => $request->input('announcement_status'),
            'color' => $request->input('announcement_color'),
            'text_color' => $request->input('text_color'),
            'announcement' => $request->input('announcement'),
        ]);

        $this->updateSettingJson('default_location', [
            'lat' => $request->input('latitude'),
            'lng' => $request->input('longitude'),
        ]);

        // 5) Validation + pagination
        $request->validate(['pagination_limit' => 'numeric']);
        DB::table('business_settings')->updateOrInsert(
            ['type' => 'pagination_limit'],
            ['value' => $request->input('pagination_limit')]
        );

        return back()->with('success', 'Company info updated successfully!');
    }

    private function updateSettingFile(
        Request $request,
        string $type,
        string $folder,
        string $oldPathPrefix,
        bool $useUpdate = false,
        bool $forceInsert = false
    ): void {
        if (!$request->hasFile($type)) return;

        $current = BusinessSetting::where('type', $type)->first();
        $oldValue = $current?->value;

        $oldPath = $oldValue ? $oldPathPrefix . $oldValue : null;

        $newValue = FileManager::updateFile($folder, $oldPath, $request->file($type));

        if ($forceInsert || !$useUpdate) {
            DB::table('business_settings')->updateOrInsert(['type' => $type], ['value' => $newValue]);
            return;
        }

        BusinessSetting::where('type', $type)->update(['value' => $newValue]);
    }

    private function updateSettingJson(string $type, array $payload): void
    {
        DB::table('business_settings')->updateOrInsert(
            ['type' => $type],
            ['value' => json_encode($payload)]
        );
    }

    public function update(Request $request, $name)
    {
        if ($name == 'download_app_apple_stroe') {
            $download_app_store = BusinessSetting::where('type', 'download_app_apple_stroe')->first();
            if (isset($download_app_store) == false) {
                DB::table('business_settings')->insert([
                    'type' => 'download_app_apple_stroe',
                    'value' => json_encode([
                        'status' => 1,
                        'link' => '',

                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('business_settings')->where(['type' => 'download_app_apple_stroe'])->update([
                    'type' => 'download_app_apple_stroe',
                    'value' => json_encode([
                        'status' => $request['status'],
                        'link' => $request['link'],

                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'download_app_google_stroe') {
            $download_app_store = BusinessSetting::where('type', 'download_app_google_stroe')->first();
            if (isset($download_app_store) == false) {
                DB::table('business_settings')->insert([
                    'type' => 'download_app_google_stroe',
                    'value' => json_encode([
                        'status' => 1,
                        'link' => '',

                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('business_settings')->where(['type' => 'download_app_google_stroe'])->update([
                    'type' => 'download_app_google_stroe',
                    'value' => json_encode([
                        'status' => $request['status'],
                        'link' => $request['link'],

                    ]),
                    'updated_at' => now(),
                ]);
            }
        }


        return redirect()->back()->with('success', 'App Store updated successfully!');
    }

    // Terms & conditon functions start
    public function terms_condition()
    {
        $terms_condition = BusinessSetting::where('type', 'terms_condition')->first();
        return view('admin.term_conditon.index', compact('terms_condition'));
    }
    public function updateTermsCondition(Request $data)
    {
        $data->validate([
            'terms' => 'required',
        ]);
        BusinessSetting::where('type', 'terms_condition')->update(['value' => $data->terms]);
        return redirect()->back()->with('success', 'Terms and Condition Updated successfully!');
    }
    // Terms & conditon functions end
    // Start Privacy Policy functions

    public function privacy_policy()
    {
        $privacy_policy = BusinessSetting::where('type', 'privacy_policy')->first();
        return view('admin.privacy_policy.index', compact('privacy_policy'));
    }

    public function privacy_policy_update(Request $data)
    {
        $validatedData = $data->validate([
            'value' => 'required',
        ]);
        BusinessSetting::where('type', 'privacy_policy')->update(['value' => $data->value]);
        return redirect()->back()->with('success', 'Privacy Policy Updated Successfully!');
    }
    // End Privacy Policy functions

    // about us  functions
    public function about_us()
    {
        $about_us = BusinessSetting::where('type', 'about_us')->first();
        return view('admin.about_us.index', compact('about_us'));
    }

    public function about_usUpdate(Request $data)
    {
        $validatedData = $data->validate([
            'about_us' => 'required',
        ]);
        BusinessSetting::where('type', 'about_us')->update(['value' => $data->about_us]);
        return back()->with('success', 'About Us updated successfully!');
    }
}
