<?php

use App\Http\Controllers\Api\PayosWebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/payos/webhook', PayosWebhookController::class);
