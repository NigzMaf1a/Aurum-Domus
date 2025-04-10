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
        if (isset($data['unitName'], $data['unitEmail'], $data['unitPhone'], $data['unitLocation'], $data['unitBalance'])) {
            $unitName = $conn->real_escape_string($data['unitName']);
            $unitEmail = $conn->real_escape_string($data['unitEmail']);
            $unitPhone = $conn->real_escape_string($data['unitPhone']);
            $unitLocation = $conn->real_escape_string($data['unitLocation']);
            $unitBalance = intval($data['unitBalance']);
            $employees = isset($data['employees']) ? intval($data['employees']) : 0;

            $sql = "INSERT INTO Unit (UnitName, UnitEmail, UnitPhone, UnitLocation, UnitBalance, Employees)
                    VALUES ('$unitName', '$unitEmail', '$unitPhone', '$unitLocation', $unitBalance, $employees)";

            if ($conn->query($sql) === TRUE) {
                echo json_encode(["message" => "Unit created successfully", "UnitID" => $conn->insert_id]);
            } else {
                http_response_code(500);
                echo json_encode(["error" => "Error creating unit: " . $conn->error]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Invalid input"]);
        }
        break;

    case 'GET': // READ
        $unitID = isset($_GET['unitid']) ? intval($_GET['unitid']) : null;

        if ($unitID) {
            $sql = "SELECT * FROM Unit WHERE UnitID = $unitID";
        } else {
            $sql = "SELECT * FROM Unit";
        }

        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $units = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($units);
        } else {
            echo json_encode([]);
        }
        break;

    case 'PUT': // UPDATE
        if (isset($data['unitID'], $data['unitName'], $data['unitEmail'], $data['unitPhone'], $data['unitLocation'], $data['unitBalance'])) {
            $unitID = intval($data['unitID']);
            $unitName = $conn->real_escape_string($data['unitName']);
            $unitEmail = $conn->real_escape_string($data['unitEmail']);
            $unitPhone = $conn->real_escape_string($data['unitPhone']);
            $unitLocation = $conn->real_escape_string($data['unitLocation']);
            $unitBalance = intval($data['unitBalance']);
            $employees = isset($data['employees']) ? intval($data['employees']) : 0;

            $sql = "UPDATE Unit
                    SET UnitName = '$unitName', UnitEmail = '$unitEmail', UnitPhone = '$unitPhone',
                        UnitLocation = '$unitLocation', UnitBalance = $unitBalance, Employees = $employees
                    WHERE UnitID = $unitID";

            if ($conn->query($sql) === TRUE) {
                echo json_encode(["message" => "Unit updated successfully"]);
            } else {
                http_response_code(500);
                echo json_encode(["error" => "Error updating unit: " . $conn->error]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Invalid input"]);
        }
        break;

    case 'DELETE': // DELETE
        if (isset($data['unitID'])) {
            $unitID = intval($data['unitID']);

            $sql = "DELETE FROM Unit WHERE UnitID = $unitID";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(["message" => "Unit deleted successfully"]);
            } else {
                http_response_code(500);
                echo json_encode(["error" => "Error deleting unit: " . $conn->error]);
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
