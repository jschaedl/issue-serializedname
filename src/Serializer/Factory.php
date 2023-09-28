<?php

declare(strict_types=1);

namespace App\Serializer;

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\PropertyInfo\Extractor\ConstructorExtractor;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\BackedEnumNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class Factory
{
    public function __invoke(): SerializerInterface
    {
        $phpDocExtractor = new PhpDocExtractor();
        $typeExtractor = new PropertyInfoExtractor(
            typeExtractors: [ new ConstructorExtractor([$phpDocExtractor]), $phpDocExtractor,]
        );

        $objectNormalizer = new ObjectNormalizer(
            nameConverter: new MetadataAwareNameConverter(
                new ClassMetadataFactory(
                    new AnnotationLoader(
                        new AnnotationReader(),
                    ),
                ),
                new SchemaCamelCaseNameConverter(),
            ),
            propertyTypeExtractor: $typeExtractor,
            defaultContext: [ AbstractObjectNormalizer::SKIP_NULL_VALUES => true ]
        );
        $normalizers = [new DateTimeNormalizer(), new BackedEnumNormalizer(), new ArrayDenormalizer(), new TrimDenormalizer($objectNormalizer), $objectNormalizer];

        $jsonEncoder = new JsonEncoder();
        $encoders = [$jsonEncoder];

        return new Serializer($normalizers, $encoders);
    }
}
