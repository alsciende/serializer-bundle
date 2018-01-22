<?php

namespace Alsciende\SerializerBundle\Service;

use Alsciende\SerializerBundle\Exception\UnknownTypeException;
use Alsciende\SerializerBundle\Service\Normalizer\NormalizerInterface;

/**
 * Description of NormalizerManager
 *
 * @author Alsciende <alsciende@icloud.com>
 */
class NormalizerManager
{
    /** @var NormalizerInterface $normalizers */
    private $normalizers;

    /**
     * NormalizerManager constructor.
     * @param NormalizerInterface[] $normalizers
     */
    public function __construct ($normalizers)
    {
        foreach ($normalizers as $normalizer) {
            $this->normalizers[$normalizer->supports()] = $normalizer;
        }
    }

    /**
     * @param string $className
     * @param array $propertyMap
     * @param array $data
     */
    public function denormalize($className, $propertyMap, $data)
    {
        $result = [];

        foreach ($propertyMap as $property => $type) {
            $normalizer = $this->normalizers[$type];
            if(!$normalizer instanceof NormalizerInterface) {
                throw new UnknownTypeException($type);
            }

            $result[$property] = $normalizer->normalize($className, $property, $data);
        }

        return $result;
    }
}