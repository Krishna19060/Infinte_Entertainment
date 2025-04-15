<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

require 'vendor/autoload.php';

try {
    // Connect to MongoDB
    $client = new MongoDB\Client("mongodb://localhost:27017");
    $collection = $client->infinite->events;

    // Get POST data
    if (isset($_POST['event_id'])) {
        $eventId = $_POST['event_id'];

        $result = $collection->deleteOne(['event_id' => $eventId]);

        if ($result->getDeletedCount() > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Event deleted successfully!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Event not found or already deleted.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Missing event_id.']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()]);
}
?>
