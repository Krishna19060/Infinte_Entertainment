<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Include MongoDB client library if you're using Composer for MongoDB
require 'vendor/autoload.php';

$client = new MongoDB\Client("mongodb://localhost:27017");

// Use different collections based on form type
$collection = null;
$formType = isset($_POST['form_type']) ? $_POST['form_type'] : ''; // Check which form was submitted

$response = [
    "success" => false,
    "message" => "Something went wrong."
];

// Check for POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        if ($formType === 'event') {
            // Event Form Submission
            $collection = $client->infinite->events;
            // Prepare event data
            $data = [
                'event_id' => $_POST['event_id'],
                'event_name' => $_POST['event_name'],
                'organizer' => $_POST['organizer'],
                'event_date' => $_POST['event_date'],
                'venue' => $_POST['venue'],
                'pax' => $_POST['pax'],
                'event_type' => $_POST['event_type'],
                'service_type' => $_POST['service_type'],
                'duration' => $_POST['duration'],
                'status' => $_POST['status'],
                'remarks' => $_POST['remarks']
            ];
            
            // Insert event data into MongoDB
            $insertResult = $collection->insertOne($data);
            if ($insertResult->getInsertedCount() == 1) {
                $response['success'] = true;
                $response['message'] = "Event added successfully!";
            } else {
                $response['message'] = "Failed to add event.";
            }
        } else {
            // Contact Form Submission
            $collection = $client->infinite->contact;
            // Prepare contact data
            $data = [
                'name' => $_POST['name'],
                'phone' => $_POST['country_code'] . $_POST['phone'],
                'company' => $_POST['company'],
                'email' => $_POST['email'],
                'event_type' => $_POST['event_type'],
                'service_type' => $_POST['service_type'],
                'message' => $_POST['message'],
                'date_submitted' => new MongoDB\BSON\UTCDateTime()
            ];

            // Insert contact data into MongoDB
            $insertResult = $collection->insertOne($data);
            if ($insertResult->getInsertedCount() == 1) {
                $response['success'] = true;
                $response['message'] = "Thank you! We'll get back to you soon.";
            } else {
                $response['message'] = "Failed to submit contact request.";
            }
        }
    } catch (Exception $e) {
        $response['message'] = "Error: " . $e->getMessage();
    }
}

echo json_encode($response);
?>
