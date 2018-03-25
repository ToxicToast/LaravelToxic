<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \App\Models\SystemLogger;

class LogController extends Controller
{
    public function getLog() {
        $model = SystemLogger::orderBy('id', 'DESC')
            ->get();
        if (!$model->isEmpty()) {
            return [
                'data'  => $model,
                'count' => $model->count()
            ];
        } else {
            return $this->returnDefault(false);
        }
    }

    public function setLog(Request $request) {
        try {
            $logArray = $request->all();
            $modelArray = [
                'text'          => $logArray['text'],
                'ip_address'    => $request->ip(),
                'log_level'     => $logArray['log_level'],
                'user_id'       => $logArray['user_id'] ? $logArray['user_id'] : 0
            ];
            $model = new SystemLogger($modelArray);
            $model->save();
            return response($model, 200);
        } catch (\Exception $e) {
            return response($e, 400);
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
