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
        if (isset($data['stockID'], $data['quantity'], $data['supplyPrice'], $data['supplyPayment'], $data['supplyDate'], $data['supplyTime'])) {
            $stockID = intval($data['stockID']);
            $quantity = intval($data['quantity']);
            $supplyPrice = intval($data['supplyPrice']);
            $supplyPayment = $conn->real_escape_string($data['supplyPayment']);
            $supplyDate = $conn->real_escape_string($data['supplyDate']);
            $supplyTime = $conn->real_escape_string($data['supplyTime']);

            $sql = "INSERT INTO Supply (StockID, Quantity, SupplyPrice, SupplyPayment, SupplyDate, SupplyTime)
                    VALUES ($stockID, $quantity, $supplyPrice, '$supplyPayment', '$supplyDate', '$supplyTime')";

            if ($conn->query($sql) === TRUE) {
                echo json_encode(["message" => "Supply entry created successfully", "SupplyID" => $conn->insert_id]);
            } else {
                http_response_code(500);
                echo json_encode(["error" => "Error creating supply entry: " . $conn->error]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Invalid input"]);
        }
        break;

    case 'GET': // READ
        $supplyID = isset($_GET['supplyid']) ? intval($_GET['supplyid']) : null;

        if ($supplyID) {
            $sql = "SELECT * FROM Supply WHERE SupplyID = $supplyID";
        } else {
            $sql = "SELECT * FROM Supply";
        }

        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $supplies = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($supplies);
        } else {
            echo json_encode([]);
        }
        break;

    case 'PUT': // UPDATE
        if (isset($data['supplyID'], $data['quantity'], $data['supplyPrice'], $data['supplyPayment'], $data['supplyDate'], $data['supplyTime'])) {
            $supplyID = intval($data['supplyID']);
            $quantity = intval($data['quantity']);
            $supplyPrice = intval($data['supplyPrice']);
            $supplyPayment = $conn->real_escape_string($data['supplyPayment']);
            $supplyDate = $conn->real_escape_string($data['supplyDate']);
            $supplyTime = $conn->real_escape_string($data['supplyTime']);

            $sql = "UPDATE Supply
                    SET Quantity = $quantity, SupplyPrice = $supplyPrice, SupplyPayment = '$supplyPayment',
                        SupplyDate = '$supplyDate', SupplyTime = '$supplyTime'
                    WHERE SupplyID = $supplyID";

            if ($conn->query($sql) === TRUE) {
                echo json_encode(["message" => "Supply entry updated successfully"]);
            } else {
                http_response_code(500);
                echo json_encode(["error" => "Error updating supply entry: " . $conn->error]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Invalid input"]);
        }
        break;

    case 'DELETE': // DELETE
        if (isset($data['supplyID'])) {
            $supplyID = intval($data['supplyID']);

            $sql = "DELETE FROM Supply WHERE SupplyID = $supplyID";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(["message" => "Supply entry deleted successfully"]);
            } else {
                http_response_code(500);
                echo json_encode(["error" => "Error deleting supply entry: " . $conn->error]);
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
