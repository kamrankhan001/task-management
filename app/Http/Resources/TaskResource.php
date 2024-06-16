<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'due_date' => $this->due_date,
            'priority' => $this->priority,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'user' => new UserResource($this->whenLoaded('user')),
            'group' => new GroupResource($this->whenLoaded('group')),
        ];
    }
}
