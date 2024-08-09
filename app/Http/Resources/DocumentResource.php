<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class DocumentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'file_name' => $this->file_name,
            'file_type' => $this->file_type,
            'size' => $this->size,
            'file_path' => $this->file_path,
            'file_url' => $this->getFileUrl(), // Example of a computed attribute
        ];
    }

    /**
     * Generate a URL for accessing the file.
     *
     * @return string
     */
    protected function getFileUrl(): string
    {
        // Generate a URL for the file stored in storage
        return Storage::url($this->file_path);
    }
}
