<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ActivityPubActor;

final class ActivityPubSigner
{
    /**
     * @param array<string, string> $headers
     * @return array<string, string>
     */
    public function sign(ActivityPubActor $actor, string $method, string $url, string $body, array $headers = []): array
    {
        $date = gmdate('D, d M Y H:i:s').' GMT';
        $digest = 'SHA-256='.base64_encode(hash('sha256', $body, true));
        $path = parse_url($url, PHP_URL_PATH) ?: '/';
        $host = parse_url($url, PHP_URL_HOST) ?: '';
        $string = "(request-target): ".strtolower($method).' '.$path."\n".
            "host: {$host}\n".
            "date: {$date}\n".
            "digest: {$digest}";

        $signature = '';

        if ($actor->private_key) {
            openssl_sign($string, $rawSignature, $actor->private_key, OPENSSL_ALGO_SHA256);
            $signature = base64_encode($rawSignature);
        }

        return [
            ...$headers,
            'Host' => $host,
            'Date' => $date,
            'Digest' => $digest,
            'Signature' => sprintf(
                'keyId="%s",algorithm="rsa-sha256",headers="(request-target) host date digest",signature="%s"',
                url("/@{$actor->username}#main-key"),
                $signature,
            ),
        ];
    }
}

