<?php
class SimpleJWT {
    private static $secret = "";

    public static function set_secret($secret) {
      self::$secret = $secret;
    }
    
    // Encode data menjadi JWT token
    public static function encode($data) { // default 30 hari
        $header = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);
        $payload = json_encode($data);
        
        $base64_header = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64_payload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
        
        $signature = hash_hmac('sha256', "$base64_header.$base64_payload", self::$secret, true);
        $base64_signature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        
        return "$base64_header.$base64_payload.$base64_signature";
    }
    
    // Decode dan verifikasi JWT token
    public static function decode($token) {
        $parts = explode('.', $token);
        if (count($parts) !== 3) return null;
        
        list($base64_header, $base64_payload, $base64_signature) = $parts;
        
        // Verifikasi signature
        $expected_signature = hash_hmac('sha256', "$base64_header.$base64_payload", self::$secret, true);
        $expected_base64 = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($expected_signature));
        
        if (!hash_equals($expected_base64, $base64_signature)) {
            return null; // Signature tidak valid
        }
        
        // Decode payload
        $payload = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $base64_payload)), true);
        
        // // Cek expiry
        // if (isset($payload['exp']) && $payload['exp'] < time()) {
        //     return null; // Token kadaluarsa
        // }
        
        return $payload;
    }
}
?>