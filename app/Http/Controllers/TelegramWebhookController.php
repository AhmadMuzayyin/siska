<?php

namespace App\Http\Controllers;

use App\Services\TelegramService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\Update;
use Throwable;

class TelegramWebhookController extends Controller
{
    /**
     * Handle incoming webhook requests from Telegram.
     */
    public function handle(Request $request, TelegramService $telegramService): JsonResponse
    {
        Log::info('Telegram Webhook Request Received', [
            'has_callback' => $request->has('callback_query'),
            'has_message' => $request->has('message'),
            'callback_data' => $request->input('callback_query.data'),
            'message_text' => $request->input('message.text'),
        ]);

        // 1. Verify webhook secret token if configured
        $configuredSecret = config('services.telegram.webhook_secret');
        if (! empty($configuredSecret)) {
            $incomingSecret = $request->header('X-Telegram-Bot-Api-Secret-Token');
            if ($incomingSecret !== $configuredSecret) {
                Log::warning('Telegram Webhook Unauthorized Secret Token', [
                    'expected' => $configuredSecret ? '***' : null,
                    'incoming' => $incomingSecret ? '***' : null,
                ]);

                return response()->json(['error' => 'Unauthorized'], 401);
            }
        }

        try {
            $update = new Update($request->all());
            $telegramService->processUpdate($update);

            return response()->json(['status' => 'ok']);
        } catch (Throwable $e) {
            Log::error('Telegram Webhook Exception: '.$e->getMessage(), [
                'exception' => $e,
                'payload' => $request->all(),
            ]);

            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }
}
