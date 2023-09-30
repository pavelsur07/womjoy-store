<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Service\Imgproxy;

use Exception;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class Signature
{
    /**
     * @throws Exception
     */
    public function __construct(
        #[Autowire('%env(IMGPROXY_KEY)%')]
        private string $key,

        #[Autowire('%env(IMGPROXY_SALT)%')]
        private string $salt,
        private readonly int $signatureSize = 0,
    ) {
        $this->key = @pack("H*", $key) ?: throw new Exception('Key expected to be hex-encoded string');
        $this->salt = @pack("H*", $salt) ?: throw new Exception('Salt expected to be hex-encoded string');
    }

    public function sing(string $path): string
    {
        $sha256 = hash_hmac('sha256', $this->salt . $path, $this->key, true);

        if ($this->signatureSize) {
            $sha256 = substr($sha256, 0, $this->signatureSize);
        }

        return rtrim(strtr(base64_encode($sha256), '+/', '-_'), '=');
    }
}
