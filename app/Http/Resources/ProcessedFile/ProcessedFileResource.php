<?php

namespace App\Http\Resources\ProcessedFile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProcessedFileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'original_filename' => $this->original_filename,
            'processing_status' => trans('processed-file-status.' . $this->status->value),
        ];
    }
}
