<?php

namespace App\Transformers\Serializer;

use League\Fractal\Serializer\ArraySerializer;

/**
 * Class ResponseSerializer
 * @package App\Transformers\Serializer
 * @author  Mark Joshua Fajardo <mjt.fajardo@gmail.com>
 * @since   2022.05.26
 */
class ResponseSerializer extends ArraySerializer
{
    /**
     * Serialize a collection.
     * @param string       $resourceKey
     * @param array<mixed> $data
     * @return array{data: array<mixed>}
     */
    public function collection($resourceKey, array $data): array
    {
        return ['data' => $data];
    }

    /**
     * Serialize an item.
     * @param string       $resourceKey
     * @param array<mixed> $data
     * @return array<mixed>
     */
    public function item($resourceKey, array $data): array
    {
        return $data;
    }

    /**
     * Serialize null resource.
     * @return array{data: array<null>}
     */
    public function null(): array
    {
        return ['data' => []];
    }
}
