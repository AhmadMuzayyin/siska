<?php

namespace App\Http\Controllers\Api;

use App\Actions\RfidScanAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RfidScanController extends Controller
{
    public function __invoke(Request $request, RfidScanAction $action): JsonResponse
    {
        $data = $request->validate([
            'rfid_uid' => ['required', 'string'],
        ]);

        $result = $action->handle($data['rfid_uid']);

        return response()->json([
            'status' => $result->status,
            'message' => $result->message,
        ]);
    }
}
