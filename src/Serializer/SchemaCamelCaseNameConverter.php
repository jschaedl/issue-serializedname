<?php

namespace App\Serializer;

use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;

class SchemaCamelCaseNameConverter extends CamelCaseToSnakeCaseNameConverter implements NameConverterInterface
{
    /**
     * @param null|array<string> $attributes
     */
    public function __construct(?array $attributes = null, bool $lowerCamelCase = true)
    {
        parent::__construct($attributes, $lowerCamelCase);
    }

    public function normalize(string $propertyName): string
    {
        $propertyName = parent::normalize($propertyName);

        if ($propertyName === 'schema') {
            return '$schema';
        }

        return $propertyName;
    }

    public function denormalize(string $propertyName): string
    {
        $propertyName = parent::denormalize($propertyName);

        if (substr($propertyName, 0, 1) === '$') {
            return substr($propertyName, 1);
        }

        return $propertyName;
    }
}
