<?php
require 'connection.php';

// Set response header
header('Content-Type: application/json');

// Handle different request methods
$requestMethod = $_SERVER['REQUEST_METHOD'];

switch ($requestMethod) {
    case 'POST': // Add a new reservation
        addReservation($conn);
        break;
    case 'GET': // Fetch reservations
        fetchReservations($conn);
        break;
    case 'PUT': // Update an existing reservation
        updateReservation($conn);
        break;
    case 'DELETE': // Delete a reservation
        deleteReservation($conn);
        break;
    default:
        echo json_encode(["message" => "Unsupported request method."]);
        break;
}

// Add a reservation
function addReservation($conn)
{
    $data = json_decode(file_get_contents('php://input'), true);

    // Required fields
    $unitID = $data['UnitID'];
    $tableID = $data['TableID'];
    $customerID = $data['CustomerID'];
    $orderID = $data['OrderID'];
    $dishID = $data['DishID'];
    $dishName = $data['DishName'];
    $plates = $data['Plates'];
    $orderPrice = $data['OrderPrice'];
    $paymentStatus = $data['PaymentStatus'];
    $reservationDate = $data['ReservationDate'];
    $reservationTime = $data['ReservationTime'];

    try {
        // Check and insert/update dependent table entries
        checkAndInsert($conn, 'Unit', 'UnitID', $unitID, ['UnitName', 'UnitEmail', 'UnitPhone', 'UnitLocation', 'UnitBalance']);
        checkAndInsert($conn, 'Tables', 'TableID', $tableID, ['TableName', 'TableStatus'], 'UnitID', $unitID);
        checkAndInsert($conn, 'Customer', 'CustomerID', $customerID, ['Name1', 'Name2', 'PhoneNo', 'Email', 'Password', 'Gender', 'dLocation']);
        checkAndInsert($conn, 'Orders', 'OrderID', $orderID, ['DishName', 'DishPrice', 'Plates', 'OrderPrice', 'OrderDate', 'OrderTime', 'PaymentStatus', 'Served'], 'UnitID', $unitID);
        checkAndInsert($conn, 'Dishes', 'DishID', $dishID, ['DishName', 'DishDescription', 'DishPrice', 'Available'], 'UnitID', $unitID);

        // Insert reservation
        $sql = "INSERT INTO Reservation (UnitID, TableID, CustomerID, OrderID, DishID, DishName, Plates, OrderPrice, PaymentStatus, ReservationDate, ReservationTime)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiiisidssss", $unitID, $tableID, $customerID, $orderID, $dishID, $dishName, $plates, $orderPrice, $paymentStatus, $reservationDate, $reservationTime);
        $stmt->execute();

        echo json_encode(["message" => "Reservation added successfully.", "ReservationID" => $conn->insert_id]);
    } catch (Exception $e) {
        echo json_encode(["error" => $e->getMessage()]);
    }
}

// Fetch reservations
function fetchReservations($conn)
{
    $result = $conn->query("SELECT * FROM Reservation");
    $reservations = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($reservations);
}

// Update reservation
function updateReservation($conn)
{
    parse_str(file_get_contents('php://input'), $data);

    $reservationID = $data['ReservationID'];
    $columns = ['UnitID', 'TableID', 'CustomerID', 'OrderID', 'DishID', 'DishName', 'Plates', 'OrderPrice', 'PaymentStatus', 'ReservationDate', 'ReservationTime'];
    $updates = [];

    foreach ($columns as $column) {
        if (isset($data[$column])) {
            $updates[] = "$column = ?";
        }
    }

    if (!empty($updates)) {
        $sql = "UPDATE Reservation SET " . implode(', ', $updates) . " WHERE ReservationID = ?";
        $stmt = $conn->prepare($sql);
        $params = array_values(array_filter($data, fn($key) => in_array($key, $columns), ARRAY_FILTER_USE_KEY));
        $params[] = $reservationID;

        $types = str_repeat("s", count($params) - 1) . "i"; // Adjust parameter types
        $stmt->bind_param($types, ...$params);
        $stmt->execute();

        echo json_encode(["message" => "Reservation updated successfully."]);
    } else {
        echo json_encode(["error" => "No valid fields to update."]);
    }
}

// Delete reservation
function deleteReservation($conn)
{
    parse_str(file_get_contents('php://input'), $data);
    $reservationID = $data['ReservationID'];

    $stmt = $conn->prepare("DELETE FROM Reservation WHERE ReservationID = ?");
    $stmt->bind_param("i", $reservationID);
    $stmt->execute();

    echo json_encode(["message" => "Reservation deleted successfully."]);
}

// Check and insert/update dependent table entries
function checkAndInsert($conn, $table, $idColumn, $id, $columns, $foreignKey = null, $foreignValue = null)
{
    $placeholders = implode(', ', array_map(fn($col) => "$col = ?", $columns));
    $params = [$id];

    if ($foreignKey && $foreignValue) {
        $placeholders .= ", $foreignKey = ?";
        $params[] = $foreignValue;
    }

    $checkQuery = "SELECT * FROM $table WHERE $idColumn = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        // Insert new entry
        $insertColumns = implode(', ', array_merge([$idColumn], $columns, $foreignKey ? [$foreignKey] : []));
        $insertPlaceholders = implode(', ', array_fill(0, count($params), '?'));
        $insertQuery = "INSERT INTO $table ($insertColumns) VALUES ($insertPlaceholders)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param(str_repeat("s", count($params)), ...$params);
        $insertStmt->execute();
    }
}

$conn->close();
?>
