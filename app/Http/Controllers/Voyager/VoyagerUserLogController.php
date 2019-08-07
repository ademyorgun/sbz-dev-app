<?php

namespace App\Http\Controllers\Voyager;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Models\Activity as ActivityModel;
use TCG\Voyager\Http\Controllers\VoyagerUserController as BaseVoyagerUserController;

class VoyagerUserLogController extends BaseVoyagerUserController
{
    /**
     * Get the user log
     * 
     * @param id integer
     * @return response
     */
    public function indexLog($id) {
        $userLog = ActivityModel::where('subject_id', '=', $id)->orderBy('created_at', 'desc')->paginate(12);

        return response()->json([
            'userLog' => $userLog,
        ]);
    }
}
