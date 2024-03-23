<?php

namespace App\Http\Controllers;

use App\Console\Commands\SendNewsletterCommand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class NewsletterController extends Controller
{
    public function send(Request $request) {
        $request->validate([
            'emails' => 'array'
        ]);

        $emails = isset($request->all()['emails']) ? $request->all()['emails'] : null;

        Artisan::call(SendNewsletterCommand::class, ['emails' => $emails]);

        return response()->json([
            'message' => 'Proceso realizado exitosamente'
        ]);
    }
}
