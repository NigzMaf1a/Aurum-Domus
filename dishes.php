<?php

header('Content-Type: application/json');

include 'connection.php';

$action = isset($_GET['action']) ? $_GET['action'] : null;

switch ($action) {
    case 'create':
        // Create a new dish
        $data = json_decode(file_get_contents('php://input'), true);
        $UnitID = $data['UnitID'];
        $DishName = $data['DishName'];
        $DishDescription = $data['DishDescription'];
        $DishPrice = $data['DishPrice'];
        $Available = $data['Available'];

        $sql = "INSERT INTO Dishes (UnitID, DishName, DishDescription, DishPrice, Available) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issds", $UnitID, $DishName, $DishDescription, $DishPrice, $Available);

        if ($stmt->execute()) {
            echo json_encode(["success" => "Dish created successfully", "DishID" => $stmt->insert_id]);
        } else {
            echo json_encode(["error" => "Failed to create dish: " . $stmt->error]);
        }
        $stmt->close();
        break;

    case 'read':
        // Retrieve a dish by ID
        $DishID = $_GET['DishID'];
        $sql = "SELECT * FROM Dishes WHERE DishID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $DishID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo json_encode($result->fetch_assoc());
        } else {
            echo json_encode(["error" => "Dish not found"]);
        }
        $stmt->close();
        break;

    case 'update':
        // Update a dish
        $data = json_decode(file_get_contents('php://input'), true);
        $DishID = $data['DishID'];
        $UnitID = $data['UnitID'];
        $DishName = $data['DishName'];
        $DishDescription = $data['DishDescription'];
        $DishPrice = $data['DishPrice'];
        $Available = $data['Available'];

        $sql = "UPDATE Dishes SET UnitID = ?, DishName = ?, DishDescription = ?, DishPrice = ?, Available = ? WHERE DishID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issdsi", $UnitID, $DishName, $DishDescription, $DishPrice, $Available, $DishID);

        if ($stmt->execute()) {
            echo json_encode(["success" => "Dish updated successfully"]);
        } else {
            echo json_encode(["error" => "Failed to update dish: " . $stmt->error]);
        }
        $stmt->close();
        break;

    case 'delete':
        // Delete a dish
        $DishID = $_GET['DishID'];
        $sql = "DELETE FROM Dishes WHERE DishID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $DishID);

        if ($stmt->execute()) {
            echo json_encode(["success" => "Dish deleted successfully"]);
        } else {
            echo json_encode(["error" => "Failed to delete dish: " . $stmt->error]);
        }
        $stmt->close();
        break;

    case 'list':
        // List all dishes
        $sql = "SELECT * FROM Dishes";
        $result = $conn->query($sql);

        $dishes = [];
        while ($row = $result->fetch_assoc()) {
            $dishes[] = $row;
        }
        echo json_encode($dishes);
        break;

    default:
        echo json_encode(["error" => "Invalid action"]);
}

$conn->close();

?>
