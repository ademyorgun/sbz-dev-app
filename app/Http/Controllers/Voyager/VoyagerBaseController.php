<?php

namespace App\Http\Controllers\Voyager;

use Illuminate\Http\Request;
use TCG\Voyager\Http\Controllers\VoyagerBaseController as BaseVoyagerBaseController;

class VoyagerBaseController extends BaseVoyagerBaseController
{
    /**
     * 
     * 
     */
    public function filter(Request $request) {
        return response()->json([
            'title' => 'test',
            'content' => 'test',
        ], 200);
    }
}
