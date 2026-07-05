<?php

namespace App\CPU;

use Illuminate\Support\Facades\Cache;

class FcmHelper
{
    protected static function credentials()
    {
        $path = config('fcm.credentials_path');
        if (!$path || !file_exists($path)) {
            return null;
        }
        $json = json_decode(file_get_contents($path), true);
        return is_array($json) ? $json : null;
    }

    protected static function base64url($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public static function accessToken()
    {
        return Cache::remember('fcm_v1_access_token', 3300, function () {
            $cred = self::credentials();
            if (!$cred || empty($cred['client_email']) || empty($cred['private_key'])) {
                return null;
            }

            $now = time();
            $header = ['alg' => 'RS256', 'typ' => 'JWT'];
            $claim = [
                'iss'   => $cred['client_email'],
                'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
                'aud'   => 'https://oauth2.googleapis.com/token',
                'iat'   => $now,
                'exp'   => $now + 3600,
            ];

            $signingInput = self::base64url(json_encode($header)) . '.' . self::base64url(json_encode($claim));
            $signature = '';
            if (!openssl_sign($signingInput, $signature, $cred['private_key'], OPENSSL_ALGO_SHA256)) {
                return null;
            }
            $jwt = $signingInput . '.' . self::base64url($signature);

            $ch = curl_init('https://oauth2.googleapis.com/token');
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => http_build_query([
                    'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                    'assertion'  => $jwt,
                ]),
            ]);
            $res = curl_exec($ch);
            curl_close($ch);
            $data = json_decode($res, true);
            return $data['access_token'] ?? null;
        });
    }

    public static function sendToTopic($topic, $title, $body, $image = null, $link = null)
    {
        $token = self::accessToken();
        if (!$token) {
            return ['ok' => false, 'http' => 0, 'response' => 'no_access_token'];
        }

        $cred = self::credentials();
        $projectId = $cred['project_id'] ?? config('fcm.project_id');

        $notification = ['title' => (string) $title, 'body' => (string) $body];
        if ($image) {
            $notification['image'] = (string) $image;
        }

        $data = ['title' => (string) $title, 'body' => (string) $body];
        if ($image) {
            $data['image'] = (string) $image;
        }
        if ($link) {
            $data['link'] = (string) $link;
        }

        $message = [
            'message' => [
                'topic'        => $topic,
                'notification' => $notification,
                'data'         => $data,
            ],
        ];

        $ch = curl_init("https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send");
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json',
            ],
            CURLOPT_POSTFIELDS     => json_encode($message),
        ]);
        $res  = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $resp = json_decode($res, true);
        return ['ok' => $code === 200, 'http' => $code, 'response' => $resp];
    }
}
