<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Carbon\Carbon;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\CommentsResource;
use App\Http\Resources\UserResource;

class PostResource extends Resource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user = new UserResource($this->user);
        $this->setApiDate($this->created_at);
        return [
            'id'        => $this->id,
            'title'     => $this->title,
            'intro'     => $this->intro,
            'text'      => $this->text,
            'date'      => $this->setApiDate($this->created_at),
            'image'     => '/assets/img/blog/blog-1.jpg',
            'hero'      => '/assets/img/hero/hero.jpg',
            'comments'  => $this->comments->count(),
            'category'  => new CategoryResource($this->category),
            'user'      => $this->getUserDefault($user)
        ];
        // return parent::toArray($request);
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
