<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helper\Caching;

use \App\Models\Users;

use \App\Http\Resources\UserCollection;
use \App\Http\Resources\UserResource;

class UserController extends Controller
{
    public function getUsers() {
        $cache = new Caching();
        $cache->setPrefix('USERS_GETUSERS');
        if ($cache->hasData()) {
            return $cache->getData();
        } else {
            $model = Users::OnlyActive()
            ->orderBy('id', 'DESC')
            ->get();
            if (!$model->isEmpty()) {
                $collection = new UserCollection($model);
                $cache->setData($collection);
                return $collection;
            } else {
                return $this->returnDefault(false);
            }
        }
    }

    public function getUser($id) {
        $cache = new Caching();
        $cache->setPrefix('USERS_GETUSER_NO_' . $id);
        if ($cache->hasData()) {
            return $cache->getData();
        }
        //
        $model = Users::OnlyActive()
            ->orderBy('id', 'DESC')
            ->where('id', $id)
            ->first();

        if (!empty($model)) {
            $collection = new UserResource($model);
            return $collection;
        } else {
            return $this->returnDefault(true);
        }
    }

    public function searchUser(Request $request) {
        $data = $request->all();
        $searchValue = $data['data'];
        $model = Users::OnlyActive()
        ->orderBy('id', 'DESC')
        ->where('name', 'like', '%' . $searchValue. '%')
        ->get();
        if (!$model->isEmpty()) {
            $collection = new UserCollection($model);
            return $collection;
        } else {
            return $this->returnDefault(true);
        }
    }

    private function returnDefault($error = true) {
        if ($error) {
            return abort(404);
        } else {
            return [
                'data' => [],
                'count' => 0
            ];
        }
    }
}
