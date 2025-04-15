<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require 'vendor/autoload.php';

try {
    $client = new MongoDB\Client("mongodb://localhost:27017");
    $collection = $client->infinite->events;

    // Fetch all documents
    $events = $collection->find([]);

    $eventArray = [];

    foreach ($events as $event) {
        $eventArray[] = [
            'event_id' => $event['event_id'] ?? '',
            'event_name' => $event['event_name'] ?? '',
            'organizer' => $event['organizer'] ?? '',
            'event_date' => $event['event_date'] ?? '',
            'venue' => $event['venue'] ?? '',
            'pax' => $event['pax'] ?? '',
            'event_type' => $event['event_type'] ?? '',
            'service_type' => $event['service_type'] ?? '',
            'duration' => $event['duration'] ?? '',
            'status' => $event['status'] ?? '',
            'remarks' => $event['remarks'] ?? ''
        ];
    }

    echo json_encode($eventArray);

} catch (Exception $e) {
    echo json_encode(['error' => 'Unable to fetch events: ' . $e->getMessage()]);
}
?>
