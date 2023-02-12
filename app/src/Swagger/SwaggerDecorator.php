<?php

namespace App\Swagger;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class SwaggerDecorator implements NormalizerInterface
{
    /** @var NormalizerInterface */
    private $decorated;

    /**
     * SwaggerDecorator constructor.
     */
    public function __construct(NormalizerInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $docs = $this->decorated->normalize($object, $format, $context);

        // Override title
        $docs['info']['title'] = 'API documentation for Stirling Blue';
        foreach ($docs['paths'] as $key => $path) {
            foreach ($path as $method => $values) {
                if ('post' === $method || 'put' === $method) {
                    $docs['paths'][$key][$method]['responses']['400']['description'] = '
                    {
                      "type": "https://tools.ietf.org/html/rfc2616#section-10",
                      "title": "An error occurred",
                      "detail": "property: message",
                      "violations": [
                        {
                          "propertyPath": "property",
                          "message": "message",
                          "error_key": "ERROR_KEY"
                        }
                      ]
                    }
                    ';
                }
            }
        }

        return $docs;
    }

    public function supportsNormalization($data, $format = null)
    {
        return $this->decorated->supportsNormalization($data, $format);
    }
}
