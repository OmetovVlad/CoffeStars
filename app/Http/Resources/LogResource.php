<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'coin_id' => $this->coin_id,
            'type_mining' => $this->type_mining,
            'type_source_id' => $this->type_source_id,
            'created_at' => $this->created_at,
        ];
    }
}
