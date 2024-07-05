<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlayerResource extends JsonResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'sur_name' => $this->sur_name,
            'username' => $this->username,
            'balance' => $this->balance,
            'gfc' => $this->gfc,
            'language_code' => $this->language_code,
            'is_premium' => $this->is_premium,
            'card' => $this->card,
            'invited' => $this->invited,
            'created_at' => $this->created_at,
            'referals' => $this->referrals_tree,
//            'referals' => PlayerResource::collection($this->invitedUser),
//            'logs' => LogResource::collection($this->logs)
        ];
    }
}
