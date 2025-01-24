<?php
// feedback.php - Endpoint for managing the Feedback table

// Include the database connection file
require 'connection.php';

// Set content type to JSON
header('Content-Type: application/json');

// Parse the request method
$method = $_SERVER['REQUEST_METHOD'];

// Response array
$response = [];

// Handle each request method
switch ($method) {
    case 'GET':
        if (isset($_GET['FeedbackID'])) {
            // Retrieve a single feedback record by ID
            $feedbackID = intval($_GET['FeedbackID']);
            $sql = "SELECT * FROM Feedback WHERE FeedbackID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $feedbackID);
            $stmt->execute();
            $result = $stmt->get_result();
            $response = $result->fetch_assoc();
        } else {
            // Retrieve all feedback records
            $sql = "SELECT * FROM Feedback";
            $result = $conn->query($sql);
            $response = $result->fetch_all(MYSQLI_ASSOC);
        }
        break;

    case 'POST':
        // Create a new feedback record
        $data = json_decode(file_get_contents('php://input'), true);
        $sql = "INSERT INTO Feedback (CustomerID, Email, Comments, Response, Rating) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('isssi', $data['CustomerID'], $data['Email'], $data['Comments'], $data['Response'], $data['Rating']);

        if ($stmt->execute()) {
            $response = ['message' => 'Feedback created successfully.', 'FeedbackID' => $conn->insert_id];
        } else {
            http_response_code(400);
            $response = ['error' => 'Failed to create feedback.', 'details' => $conn->error];
        }
        break;

    case 'PUT':
        // Update an existing feedback record
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['FeedbackID'])) {
            http_response_code(400);
            $response = ['error' => 'FeedbackID is required for updates.'];
            break;
        }

        $sql = "UPDATE Feedback SET CustomerID = ?, Email = ?, Comments = ?, Response = ?, Rating = ? WHERE FeedbackID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('isssii', $data['CustomerID'], $data['Email'], $data['Comments'], $data['Response'], $data['Rating'], $data['FeedbackID']);

        if ($stmt->execute()) {
            $response = ['message' => 'Feedback updated successfully.'];
        } else {
            http_response_code(400);
            $response = ['error' => 'Failed to update feedback.', 'details' => $conn->error];
        }
        break;

    case 'DELETE':
        // Delete a feedback record
        if (!isset($_GET['FeedbackID'])) {
            http_response_code(400);
            $response = ['error' => 'FeedbackID is required for deletion.'];
            break;
        }

        $feedbackID = intval($_GET['FeedbackID']);
        $sql = "DELETE FROM Feedback WHERE FeedbackID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $feedbackID);

        if ($stmt->execute()) {
            $response = ['message' => 'Feedback deleted successfully.'];
        } else {
            http_response_code(400);
            $response = ['error' => 'Failed to delete feedback.', 'details' => $conn->error];
        }
        break;

    default:
        http_response_code(405);
        $response = ['error' => 'Method not allowed.'];
        break;
}

// Send the response as JSON
echo json_encode($response);

// Close the connection
$conn->close();
?>
