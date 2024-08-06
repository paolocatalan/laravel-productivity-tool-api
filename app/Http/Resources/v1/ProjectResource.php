<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (string)$this->id,
            'attributes' => [
                'title' => $this->title,
                'description' => $this->description,
                'objective' => $this->objective,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date
            ],
            'relationships' => [
                'id' => (string)$this->user->id,
                'manager' => $this->user->name,
                'manager email' => $this->user->email
            ]
        ];
    }
}
