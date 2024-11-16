<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PorductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            "id" => $this->id,
            "name" => $this->name ?? null,
            "quantity" => $this->quantity ?? null,
            "price" => $this->price ?? null,
            "category" =>new CategoryResource($this->whenLoaded('category')) ?? null,
        ];
    }
}
