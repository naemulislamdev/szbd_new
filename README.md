# ShoppingZone BD - Backend API Stabilization

This document outlines the critical fixes and optimizations implemented to stabilize the backend API and ensure seamless compatibility with the production Flutter mobile application.

## 🔗 Quick Links
- **Branch:** `tahmids_backend`
- **Main App Directory:** `/core`

---

## 🛠️ Key Changes & Rationale

### 1. Data Integrity & Crash Prevention (`P0`)
- **[Helpers.php](core/app/CPU/Helpers.php)**: 
  - **Change**: Added strict null-safety and fallbacks (e.g., `?? []`) to `set_data_format()` for fields like `images`, `colors`, `variation`, `choice_options`, and `attributes`.
  - **Rationale**: The Flutter app uses strict type-casting (`.cast<String>()`). Encountering a `null` value instead of an empty list caused immediate runtime crashes on the home screen and product details.
- **[ConfigController.php](core/app/Http/Controllers/api/v1/ConfigController.php)**: 
  - **Change**: Cast `exchange_rate` to `(double)` with a default of `1`.
  - **Rationale**: Flutter's currency parsing failed when receiving `null` or raw string values.

### 2. Missing APIs & Method Mismatches (`P1`)
- **[ProductController.php](core/app/Http/Controllers/api/v1/ProductController.php)**:
  - **Implemented `get_home_categories()`**: This endpoint was being called by the Flutter app but did not exist on the backend, leading to 500 errors.
- **[api.php](core/routes/api/v1/api.php)**:
  - **Correction**: Updated several routes (`recivedotp`, `coupon/apply`, `chat/send-message`) from `GET` to `any` or `POST` to match the frontend expectations.
  - **Cleanup**: Removed duplicate unauthenticated order routes that represented both a logic redundancy and a potential security risk.

### 3. Response Structure Alignment
- **[BannerController.php](core/app/Http/Controllers/api/v1/BannerController.php)**:
  - **Change**: Refactored `get_banners` to return a flat array rather than a grouped object.
  - **Rationale**: Aligns with the `BannerModel` in Flutter, preventing parsing errors.
- **[CustomerController.php](core/app/Http/Controllers/api/v1/CustomerController.php)**:
  - **Change**: Fixed `address_list` to return a list (Collection) instead of just the first object.
  - **Change**: Added `state` and `country` fields to address creation to match the `AddressModel`.

### 4. Security & Best Practices
- **[PassportAuthController.php](core/app/Http/Controllers/api/v1/auth/PassportAuthController.php)**:
  - **Change**: Moved hardcoded SMS API keys to environment variables (`env()`). 
  - **Change**: Removed the plain-text `otp` field from API responses to prevent interception.
  - **Change**: Fixed new-user registration by ensuring a hashed password is set during OTP verification.
  - **Rationale**: Security hardening and ensuring credentials are not leaked in source control. Previously, new users were created without a password, leading to broken account states.

---

## 🚀 Post-Deployment Instructions

To finalize these changes on the production server, follow these steps:

### 1. Update Environment Variables (`.env`)
Add the following keys to your production `.env` file within the `core` directory:
```env
# SMS OTP Configuration
SMS_API_URL=http://188.138.41.146:7788/sendtext
SMS_API_KEY=YOUR_API_KEY
SMS_SECRET_KEY=YOUR_SECRET_KEY
SMS_CALLER_ID=YOUR_CALLER_ID
```

### 2. Update Database Schema
Run the following SQL to ensure the `shipping_addresses` table correctly supports the new fields required by the Flutter app:
```sql
ALTER TABLE shipping_addresses ADD COLUMN state VARCHAR(255) NULL AFTER phone;
ALTER TABLE shipping_addresses ADD COLUMN country VARCHAR(255) NULL AFTER state;
```

### 3. Clear Backend Cache
After pulling the new code, clear the Laravel caches to apply changes:
```bash
php artisan config:cache
php artisan route:cache
```

---
