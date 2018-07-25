<?php

namespace OpCache\Http\Controller;

use App\Http\Controllers\Controller;
use OpCache\OpCacheService;
use Illuminate\Support\Facades\Log;

class OpCacheController extends Controller
{
    public function clear()
    {
        return response()->json(['result' => OpCacheService::clear()]);
    }

    public function status()
    {
        return response()->json(['result' => OpCacheService::getStatus()]);
    }
}
