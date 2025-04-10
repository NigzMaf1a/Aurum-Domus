<?php
header("Content-Type: application/json");

include 'connection.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'create':
        createOrder($conn);
        break;

    case 'read':
        readOrder($conn);
        break;

    case 'update':
        updateOrder($conn);
        break;

    case 'delete':
        deleteOrder($conn);
        break;

    case 'list':
        listOrders($conn);
        break;

    default:
        echo json_encode(["error" => "Invalid action"]);
        break;
}

$conn->close();

function createOrder($conn) {
    $data = json_decode(file_get_contents("php://input"), true);

    $stmt = $conn->prepare("INSERT INTO Orders (UnitID, CustomerID, DishID, DishName, DishPrice, Plates, OrderPrice, OrderDate, OrderTime, PaymentStatus, Served) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiisdidssss",
        $data['UnitID'],
        $data['CustomerID'],
        $data['DishID'],
        $data['DishName'],
        $data['DishPrice'],
        $data['Plates'],
        $data['OrderPrice'],
        $data['OrderDate'],
        $data['OrderTime'],
        $data['PaymentStatus'],
        $data['Served']
    );

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "OrderID" => $conn->insert_id]);
    } else {
        echo json_encode(["error" => $stmt->error]);
    }

    $stmt->close();
}

function readOrder($conn) {
    $OrderID = isset($_GET['OrderID']) ? intval($_GET['OrderID']) : 0;

    $stmt = $conn->prepare("SELECT * FROM Orders WHERE OrderID = ?");
    $stmt->bind_param("i", $OrderID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode(["error" => "Order not found"]);
    }

    $stmt->close();
}

function updateOrder($conn) {
    $data = json_decode(file_get_contents("php://input"), true);
    $OrderID = isset($data['OrderID']) ? intval($data['OrderID']) : 0;

    $stmt = $conn->prepare("UPDATE Orders SET UnitID = ?, CustomerID = ?, DishID = ?, DishName = ?, DishPrice = ?, Plates = ?, OrderPrice = ?, OrderDate = ?, OrderTime = ?, PaymentStatus = ?, Served = ? WHERE OrderID = ?");
    $stmt->bind_param("iiisdidssssi",
        $data['UnitID'],
        $data['CustomerID'],
        $data['DishID'],
        $data['DishName'],
        $data['DishPrice'],
        $data['Plates'],
        $data['OrderPrice'],
        $data['OrderDate'],
        $data['OrderTime'],
        $data['PaymentStatus'],
        $data['Served'],
        $OrderID
    );

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["error" => $stmt->error]);
    }

    $stmt->close();
}

function deleteOrder($conn) {
    $OrderID = isset($_GET['OrderID']) ? intval($_GET['OrderID']) : 0;

    $stmt = $conn->prepare("DELETE FROM Orders WHERE OrderID = ?");
    $stmt->bind_param("i", $OrderID);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["error" => $stmt->error]);
    }

    $stmt->close();
}

function listOrders($conn) {
    $result = $conn->query("SELECT * FROM Orders");

    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }

    echo json_encode($orders);
}
