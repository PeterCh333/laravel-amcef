<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DocumentCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Use the DocumentResource to transform each item in the collection
        return [
            'data' => $this->collection->map(function ($document) {
                return new DocumentResource($document);
            }),
            'meta' => [
                'total' => $this->collection->count(),
            ],
        ];
    }
}
