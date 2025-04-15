<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Include MongoDB client library
require 'vendor/autoload.php';

try {
    // Connect to MongoDB
    $client = new MongoDB\Client("mongodb://localhost:27017");
    $collection = $client->infinite->events;

    // Get POST data
    $data = $_POST;

    // Validate required fields
    if (
        isset($data['event_id']) &&
        isset($data['event_date']) &&
        isset($data['venue']) &&
        isset($data['pax']) &&
        isset($data['service_type']) &&
        isset($data['duration']) &&
        isset($data['status']) &&
        isset($data['remarks'])
    ) {
        // Prepare update data
        $updateFields = [
            'event_date' => $data['event_date'],
            'venue' => $data['venue'],
            'pax' => $data['pax'],
            'service_type' => $data['service_type'],
            'duration' => $data['duration'],
            'status' => $data['status'],
            'remarks' => $data['remarks']
        ];

        // Perform update
        $result = $collection->updateOne(
            ['event_id' => $data['event_id']],
            ['$set' => $updateFields]
        );

        // Check result
        if ($result->getModifiedCount() > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Event updated successfully!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No changes made or invalid Event ID.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()]);
}
?>
