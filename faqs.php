<?php
// Include the database connection
require_once 'connection.php';

// Set the content type to JSON
header('Content-Type: application/json');

// Get the request method and payload
$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"), true);

switch ($method) {
    case 'POST': // CREATE
        if (isset($data['question'], $data['answer'])) {
            $question = $conn->real_escape_string($data['question']);
            $answer = $conn->real_escape_string($data['answer']);

            $sql = "INSERT INTO FAQs (Question, Answer) VALUES ('$question', '$answer')";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(["message" => "FAQ created successfully", "FAQID" => $conn->insert_id]);
            } else {
                http_response_code(500);
                echo json_encode(["error" => "Error creating FAQ: " . $conn->error]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Invalid input"]);
        }
        break;

    case 'GET': // READ
        $faqid = isset($_GET['faqid']) ? intval($_GET['faqid']) : null;
        if ($faqid) {
            $sql = "SELECT * FROM FAQs WHERE FAQID = $faqid";
        } else {
            $sql = "SELECT * FROM FAQs";
        }

        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $faqs = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($faqs);
        } else {
            echo json_encode([]);
        }
        break;

    case 'PUT': // UPDATE
        if (isset($data['faqid'], $data['question'], $data['answer'])) {
            $faqid = intval($data['faqid']);
            $question = $conn->real_escape_string($data['question']);
            $answer = $conn->real_escape_string($data['answer']);

            $sql = "UPDATE FAQs SET Question = '$question', Answer = '$answer' WHERE FAQID = $faqid";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(["message" => "FAQ updated successfully"]);
            } else {
                http_response_code(500);
                echo json_encode(["error" => "Error updating FAQ: " . $conn->error]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Invalid input"]);
        }
        break;

    case 'DELETE': // DELETE
        if (isset($data['faqid'])) {
            $faqid = intval($data['faqid']);

            $sql = "DELETE FROM FAQs WHERE FAQID = $faqid";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(["message" => "FAQ deleted successfully"]);
            } else {
                http_response_code(500);
                echo json_encode(["error" => "Error deleting FAQ: " . $conn->error]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Invalid input"]);
        }
        break;

    default:
        http_response_code(405); // Method Not Allowed
        echo json_encode(["error" => "Unsupported request method"]);
}

$conn->close();
?>
