<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Carbon\Carbon;

use App\Http\Resources\UserResource;

class CommentsResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user = new UserResource($this->user);
        return [
            'id'    => $this->id,
            'text'  => $this->text,
            'date'  => $this->setApiDate($this->created_at),
            'user'  => $this->getUserDefault($user)
        ];
    }

    private function setApiDate($date) {
        $newDate = new Carbon($date);
        $newDateString = $newDate->diffForHumans();
        return $newDateString;
    }

    private function getUserDefault($user) {
        try {
            if (!empty($user->name)) {
                return $user;
            } else {
                return [
                    'id'        => '0',
                    'username'  => 'Guest',
                    'about'     => 'Guest',
                    'date'      => $this->setApiDate(''),
                ];
            }
        } catch (\Exception $e) {
            return [
                'id'        => '0',
                'username'  => 'Guest',
                'about'     => 'Guest',
                'date'      => $this->setApiDate(''),
            ];
        }
    }
}
