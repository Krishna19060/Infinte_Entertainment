<?php
// Allow cross-origin requests
header("Access-Control-Allow-Origin: *");  // Or specify the origin, e.g., "http://localhost"
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
require 'vendor/autoload.php';

header('Content-Type: application/json');


$client = new MongoDB\Client("mongodb://localhost:27017");
$collection = $client->infinite->contact;

$cursor = $collection->find([], ['sort' => ['date_submitted' => -1]]);
$data = [];

foreach ($cursor as $doc) {
    $data[] = [
        'name' => $doc['name'] ?? '',
        'email' => $doc['email'] ?? '',
        'phone' => $doc['phone'] ?? '',
        'company' => $doc['company'] ?? '',
        'event_type' => $doc['event_type'] ?? '',
        'service_type' => $doc['service_type'] ?? '',
        'message' => $doc['message'] ?? '',
        'date_submitted' => $doc['date_submitted'] ? $doc['date_submitted']->toDateTime()->format('Y-m-d H:i:s') : ''

    ];
}

echo json_encode(['data' => $data]);
