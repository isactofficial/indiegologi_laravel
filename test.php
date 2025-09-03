<?php
require __DIR__ . '/vendor/autoload.php'; // load library composer

use Google\Cloud\Dialogflow\V2\SessionsClient;
use Google\Cloud\Dialogflow\V2\TextInput;
use Google\Cloud\Dialogflow\V2\QueryInput;

putenv('GOOGLE_APPLICATION_CREDENTIALS=' . __DIR__ . '/storage/app/private/indiegologi-chatbot-fb80b434d02e.json');

$projectId = 'indiegologi-chatbot';
$sessionId = uniqid();
$text = 'Halo';
$languageCode = 'id';

// Buat client session
$sessionsClient = new SessionsClient([
    'credentials' => __DIR__ . '/storage/app/private/indiegologi-chatbot-fb80b434d02e.json'
]);
$session = $sessionsClient->sessionName($projectId, $sessionId);

// Buat query input
$textInput = new TextInput();
$textInput->setText($text);
$textInput->setLanguageCode($languageCode);

$queryInput = new QueryInput();
$queryInput->setText($textInput);

// Kirim request ke Dialogflow
$response = $sessionsClient->detectIntent($session, $queryInput);
$queryResult = $response->getQueryResult();

echo "User: $text\n";
echo "Bot: " . $queryResult->getFulfillmentText() . "\n";

$sessionsClient->close();
