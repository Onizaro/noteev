<?php
class AuthMiddleware {
    public static function verifyToken($token) {
        $authServiceUrl = "http://auth-service/checkAuth.php";

        if (!$token) {
            error_log("AuthMiddleware: Aucun token fourni.");
            return null;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $authServiceUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer " . $token
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5); // Timeout pour éviter les blocages

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            error_log("AuthMiddleware: Erreur cURL -> " . $curlError);
            return null;
        }

        if ($httpCode !== 200) {
            error_log("AuthMiddleware: Auth échoué avec code HTTP " . $httpCode);
            return null;
        }

        $decodedResponse = json_decode($response, true);
        if (!$decodedResponse) {
            error_log("AuthMiddleware: Réponse invalide ou JSON mal formé.");
            return null;
        }

        return $decodedResponse;
    }
}
?>
