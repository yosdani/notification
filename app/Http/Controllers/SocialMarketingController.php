<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SocialMarketingController extends Controller
{
    public function sendToSocialNetworks(Request $request)
    {
        // Validar datos de entrada
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
            'image' => 'nullable|url',
            'tags' => 'nullable|array'
        ]);

        try {
            $client = new Client();

            // URL del webhook de n8n
            $n8nWebhookUrl = env('N8N_WEBHOOK_URL');

            // Enviar datos a n8n
            $response = $client->post($n8nWebhookUrl, [
                'json' => [
                    'title' => $validated['title'],
                    'message' => $validated['message'],
                    'image' => $validated['image'] ?? null,
                    'tags' => $validated['tags'] ?? []
                ],
                'timeout' => 10
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Contenido enviado a n8n correctamente',
                'n8n_response' => json_decode($response->getBody(), true)
            ]);

        } catch (\Exception $e) {
            Log::error('Error enviando a n8n: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'No se pudo enviar el contenido a n8n'
            ], 500);
        }
    }


}
