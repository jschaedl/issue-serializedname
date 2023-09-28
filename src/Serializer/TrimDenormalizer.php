<?php

declare(strict_types=1);

namespace App\Serializer;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class TrimDenormalizer implements DenormalizerInterface
{
    public function __construct(
        private readonly ObjectNormalizer $normalizer,
    ) {
    }

    public function denormalize(mixed $data, string $type, string $format = null, array $context = [])
    {
        if (is_string($data)) {
            $data = trim($data);
        } elseif (is_array($data)) {
            array_walk_recursive($data, function (&$value) {
                if (is_string($value)) {
                    $value = trim($value);
                }
            });
        }

        return $this->normalizer->denormalize($data, $type, $format, $context);
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null)
    {
        return true;
    }
}
