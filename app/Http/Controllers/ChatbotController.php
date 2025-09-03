<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Cloud\Dialogflow\Cx\V3\SessionsClient;
use Google\Cloud\Dialogflow\Cx\V3\QueryInput;
use Google\Cloud\Dialogflow\Cx\V3\TextInput;

class ChatbotController extends Controller
{
    public function sendMessage(Request $request)
    {
        // ... (method ini tidak berubah) ...
        $request->validate(['message' => 'required|string']);
        $userMessage = $request->input('message');
        $sessionId = $request->session()->getId();
        try {
            $botResponse = $this->detectIntent($userMessage, $sessionId);
            return response()->json(['reply' => $botResponse]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Dialogflow Error: ' . $e->getMessage());
            return response()->json(['error' => 'Maaf, saya sedang mengalami gangguan.'], 500);
        }
    }

    private function detectIntent($text, $sessionId)
    {
        // --- [PERBAIKAN UTAMA ADA DI SINI] ---

        // 1. Definisikan path ke file JSON Anda secara langsung.
        //    Fungsi base_path() akan otomatis mendapatkan path root proyek Anda.
       $credentialsPath = base_path('storage/app/private/indiegologi-chatbot-fb80b434d02e.json');

$sessionsClient = new SessionsClient([
    'credentials' => env('GOOGLE_APPLICATION_CREDENTIALS')
]);


        // --- Sisanya tetap sama ---
        $projectId = env('GOOGLE_CLOUD_PROJECT_ID');
        $locationId = 'asia-southeast1';
        $agentId = '3358f14b-505a-43ea-86cd-d707d00e51f';

        $sessionName = $sessionsClient->sessionName($projectId, $locationId, $agentId, $sessionId);

        $textInput = new TextInput();
        $textInput->setText($text);

        $queryInput = new QueryInput();
        $queryInput->setText($textInput);
        $queryInput->setLanguageCode('id');

        $response = $sessionsClient->detectIntent($sessionName, $queryInput);
        $queryResult = $response->getQueryResult();

        $responseText = '';
        if ($queryResult->getResponseMessages()) {
            $fulfillmentMessages = $queryResult->getResponseMessages();
            foreach ($fulfillmentMessages as $message) {
                if ($message->getText()) {
                    $responseText .= $message->getText()->getText()->offsetGet(0) . "\n";
                }
            }
        }
        
        $sessionsClient->close();

        return $responseText ?: "Maaf, saya tidak mengerti pesan Anda.";
    }
}