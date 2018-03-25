<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Carbon\Carbon;

use App\Http\Resources\CommentsCollection;
use App\Http\Resources\RolesCollection;

class UserResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $this->setApiDate($this->created_at);
        if ($this->hasRole('Banned')) {
            return [
                'id'            => $this->id,
                'username'      => '---Banned---',
                'username_raw'  => $this->name,
                'role'          => $this->getHighestRole($this),
                'date'          => $this->setApiDate($this->deleted_at),
                'about'         => 'This User has been Banned',
                'image'         => '/assets/img/user/avatar.jpg',
                'hero'          => '/assets/img/hero/hero.jpg',
                'streams'       => 0,
                'toasts'        => 0,
                'chatlines'     => 0
            ];
        }
        return [
            'id'        => $this->id,
            'username'  => $this->name,
            'about'     => $this->about,
            'date'      => $this->setApiDate($this->created_at),
            'role'      => $this->getHighestRole($this),
            'comments'  => $this->comments->count(),
            'groups'    => new RolesCollection($this->roles),
            'image'     => '/assets/img/user/avatar.jpg',
            'hero'      => '/assets/img/hero/hero.jpg',
            'streams'   => 0,
            'toasts'    => 0,
            'chatlines' => 0
        ];
        // return parent::toArray($request);
    }

    private function setApiDate($date) {
        $newDate = new Carbon($date);
        $newDateString = $newDate->diffForHumans();
        return $newDateString;
    }

    private function getHighestRole($user) {
        $highest = '';
        if ($user->hasRole('Banned')) {
            $highest = 'Banned';
        } elseif ($user->hasRole('Bot')) {
            $highest = 'Bot';
        } elseif ($user->hasRole('Broadcaster')) {
            $highest = 'Broadcaster';
        } elseif ($user->hasRole('Moderator')) {
            $highest = 'Moderator';
        } elseif ($user->hasRole('Subscriber')) {
            $highest = 'Subscriber';
        } elseif ($user->hasRole('Viewer')) {
            $highest = 'Viewer';
        }
        return $highest;
    }
}
