<?php
header("Content-Type: application/json");
include_once 'connection.php';

// Check the request method
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST': // Create a new roll call entry
        $data = json_decode(file_get_contents("php://input"), true);
        $sql = "INSERT INTO RollCall (RegID, UnitID, Name1, Name2, PhoneNo, Email, RollCallStatus, RollCallDate, RollCallTime) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "iisssssss",
            $data['RegID'],
            $data['UnitID'],
            $data['Name1'],
            $data['Name2'],
            $data['PhoneNo'],
            $data['Email'],
            $data['RollCallStatus'],
            $data['RollCallDate'],
            $data['RollCallTime']
        );

        if ($stmt->execute()) {
            echo json_encode(["message" => "Roll call entry created successfully.", "RollCallID" => $stmt->insert_id]);
        } else {
            echo json_encode(["error" => "Error creating roll call entry: " . $stmt->error]);
        }
        break;

    case 'GET': // Retrieve roll call entry(ies)
        if (isset($_GET['RollCallID'])) {
            $RollCallID = intval($_GET['RollCallID']);
            $sql = "SELECT * FROM RollCall WHERE RollCallID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $RollCallID);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();
            echo json_encode($data);
        } else {
            $sql = "SELECT * FROM RollCall";
            $result = $conn->query($sql);
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            echo json_encode($data);
        }
        break;

    case 'PUT': // Update roll call entry
        $data = json_decode(file_get_contents("php://input"), true);
        $sql = "UPDATE RollCall 
                SET RegID = ?, UnitID = ?, Name1 = ?, Name2 = ?, PhoneNo = ?, Email = ?, RollCallStatus = ?, RollCallDate = ?, RollCallTime = ?
                WHERE RollCallID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "iisssssssi",
            $data['RegID'],
            $data['UnitID'],
            $data['Name1'],
            $data['Name2'],
            $data['PhoneNo'],
            $data['Email'],
            $data['RollCallStatus'],
            $data['RollCallDate'],
            $data['RollCallTime'],
            $data['RollCallID']
        );

        if ($stmt->execute()) {
            echo json_encode(["message" => "Roll call entry updated successfully."]);
        } else {
            echo json_encode(["error" => "Error updating roll call entry: " . $stmt->error]);
        }
        break;

    case 'DELETE': // Delete roll call entry
        if (isset($_GET['RollCallID'])) {
            $RollCallID = intval($_GET['RollCallID']);
            $sql = "DELETE FROM RollCall WHERE RollCallID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $RollCallID);

            if ($stmt->execute()) {
                echo json_encode(["message" => "Roll call entry deleted successfully."]);
            } else {
                echo json_encode(["error" => "Error deleting roll call entry: " . $stmt->error]);
            }
        } else {
            echo json_encode(["error" => "RollCallID is required for deletion."]);
        }
        break;

    default:
        echo json_encode(["error" => "Invalid request method."]);
        break;
}

$conn->close();
?>
