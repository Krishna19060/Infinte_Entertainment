<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Include MongoDB client library if you're using Composer for MongoDB
require 'vendor/autoload.php';

try {
    // Connect to MongoDB
    $client = new MongoDB\Client("mongodb://localhost:27017");
    $collection = $client->infinite->events;

    // Get the POST data from the client-side (in JSON format)
    $data = $_POST; // Since it's being sent via a regular form (not JSON), use $_POST directly

    // Check if the required fields are present
    if (
        isset($data['event_name']) && isset($data['organizer']) && isset($data['event_date']) &&
        isset($data['venue']) && isset($data['pax']) && isset($data['event_type']) &&
        isset($data['service_type']) && isset($data['duration']) && isset($data['status']) &&
        isset($data['remarks'])
    ) {
        // Prepare the event data for insertion
        $event = [
            'event_id' => $data['event_id'],
            'event_name' => $data['event_name'],
            'organizer' => $data['organizer'],
            'event_date' => $data['event_date'],
            'venue' => $data['venue'],
            'pax' => $data['pax'],
            'event_type' => $data['event_type'],
            'service_type' => $data['service_type'],
            'duration' => $data['duration'],
            'status' => $data['status'],
            'remarks' => $data['remarks']
        ];

        // Insert the event into the MongoDB collection
        $collection->insertOne($event);

        // Send a success response back to the client
        echo json_encode(['status' => 'success', 'message' => 'Event added successfully!']);
    } else {
        // Send an error response if any required fields are missing
        echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
    }
} catch (Exception $e) {
    // Handle MongoDB connection or insertion errors
    echo json_encode(['status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()]);
}
?>
