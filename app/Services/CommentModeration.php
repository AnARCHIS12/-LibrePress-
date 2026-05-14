<?php

declare(strict_types=1);

namespace App\Services;

final class CommentModeration
{
    /**
     * @return array{status:string,is_spam:bool,reason:?string}
     */
    public function inspect(string $body, ?string $honeypot): array
    {
        if (filled($honeypot)) {
            return ['status' => 'rejected', 'is_spam' => true, 'reason' => 'honeypot'];
        }

        $linkCount = preg_match_all('/https?:\/\//i', $body);

        if ($linkCount >= 3) {
            return ['status' => 'pending', 'is_spam' => true, 'reason' => 'too_many_links'];
        }

        foreach ((array) config('librepress.comments.blocked_words', []) as $word) {
            if ($word !== '' && str_contains(strtolower($body), strtolower((string) $word))) {
                return ['status' => 'pending', 'is_spam' => true, 'reason' => 'blocked_word'];
            }
        }

        return ['status' => 'approved', 'is_spam' => false, 'reason' => null];
    }
}

