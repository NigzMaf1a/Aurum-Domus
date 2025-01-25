<?php
// Include the database connection
require_once 'connection.php';

// Set the content type to JSON
header('Content-Type: application/json');

// Get the request method and payload
$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"), true);

// Function to send JSON responses
function respond($status, $message, $data = null) {
    http_response_code($status);
    echo json_encode(["message" => $message, "data" => $data]);
    exit;
}

// Supported child tables for validation
$validTables = ['Finance', 'Deposit', 'Salary', 'Withdrawal', 'Supply', 'Payment'];

switch ($method) {
    case 'POST': // CREATE
        if (isset($data['transactionType']) && strtolower($data['transactionType']) === 'finance') {
            // Create a new Finance record
            $regID = $data['regID'];
            $amount = $data['amount'];
            $total = $data['total'];
            $balance = $data['balance'];
            $transactionType = $data['transactionType'];

            $sql = "INSERT INTO Finance (RegID, Amount, Total, Balance, TransactionType) 
                    VALUES ('$regID', '$amount', '$total', '$balance', '$transactionType')";

            if ($conn->query($sql) === TRUE) {
                respond(201, "Finance record created successfully.", ["FinanceID" => $conn->insert_id]);
            } else {
                respond(500, "Error creating Finance record: " . $conn->error);
            }
        } elseif (isset($data['transactionType'])) {
            // Handle child table creation
            $transactionType = strtolower($data['transactionType']);

            switch ($transactionType) {
                case 'deposit':
                    $customerID = $data['customerID'];
                    $financeID = $data['financeID'];
                    $name1 = $data['name1'];
                    $name2 = $data['name2'];
                    $phoneNo = $data['phoneNo'];
                    $depositAmount = $data['depositAmount'];
                    $depositDate = $data['depositDate'];
                    $depositTime = $data['depositTime'];
                    $depositStatus = $data['depositStatus'];

                    $sql = "INSERT INTO Deposit (CustomerID, FinanceID, Name1, Name2, PhoneNo, DepositAmount, DepositDate, DepositTime, DepositStatus) 
                            VALUES ('$customerID', '$financeID', '$name1', '$name2', '$phoneNo', '$depositAmount', '$depositDate', '$depositTime', '$depositStatus')";

                    break;

                case 'salary':
                    $financeID = $data['financeID'];
                    $regID = $data['regID'];
                    $salaryAmount = $data['salaryAmount'];
                    $salaryPaid = $data['salaryPaid'];
                    $salaryReceived = $data['salaryReceived'];
                    $salaryDate = $data['salaryDate'];
                    $salaryTime = $data['salaryTime'];

                    $sql = "INSERT INTO Salary (FinanceID, RegID, SalaryAmount, SalaryPaid, SalaryReceived, SalaryDate, SalaryTime) 
                            VALUES ('$financeID', '$regID', '$salaryAmount', '$salaryPaid', '$salaryReceived', '$salaryDate', '$salaryTime')";

                    break;

                case 'withdrawal':
                    $managerID = $data['managerID'];
                    $financeID = $data['financeID'];
                    $name1 = $data['name1'];
                    $name2 = $data['name2'];
                    $phoneNo = $data['phoneNo'];
                    $withdrawalAmount = $data['withdrawalAmount'];
                    $withdrawalDate = $data['withdrawalDate'];
                    $withdrawalTime = $data['withdrawalTime'];
                    $withdrawalStatus = $data['withdrawalStatus'];

                    $sql = "INSERT INTO Withdrawal (ManagerID, FinanceID, Name1, Name2, PhoneNo, WithdrawalAmount, WithdrawalDate, WithdrawalTime, WithdrawalStatus) 
                            VALUES ('$managerID', '$financeID', '$name1', '$name2', '$phoneNo', '$withdrawalAmount', '$withdrawalDate', '$withdrawalTime', '$withdrawalStatus')";

                    break;

                case 'supply':
                    $stockID = $data['stockID'];
                    $quantity = $data['quantity'];
                    $supplyPrice = $data['supplyPrice'];
                    $supplyPayment = $data['supplyPayment'];
                    $supplyDate = $data['supplyDate'];
                    $supplyTime = $data['supplyTime'];

                    $sql = "INSERT INTO Supply (StockID, Quantity, SupplyPrice, SupplyPayment, SupplyDate, SupplyTime) 
                            VALUES ('$stockID', '$quantity', '$supplyPrice', '$supplyPayment', '$supplyDate', '$supplyTime')";

                    break;

                case 'payment':
                    $financeID = $data['financeID'];
                    $customerID = $data['customerID'];
                    $orderID = $data['orderID'];
                    $name1 = $data['name1'];
                    $name2 = $data['name2'];
                    $paymentType = $data['paymentType'];
                    $paymentAmount = $data['paymentAmount'];
                    $paymentDate = $data['paymentDate'];
                    $paymentTime = $data['paymentTime'];

                    $sql = "INSERT INTO Payment (FinanceID, CustomerID, OrderID, Name1, Name2, PaymentType, PaymentAmount, PaymentDate, PaymentTime) 
                            VALUES ('$financeID', '$customerID', '$orderID', '$name1', '$name2', '$paymentType', '$paymentAmount', '$paymentDate', '$paymentTime')";

                    break;

                default:
                    respond(400, "Invalid transaction type for POST.");
            }

            if ($conn->query($sql) === TRUE) {
                respond(201, ucfirst($transactionType) . " record created successfully.", [$transactionType . "ID" => $conn->insert_id]);
            } else {
                respond(500, "Error creating $transactionType record: " . $conn->error);
            }
        } else {
            respond(400, "Invalid request body for POST.");
        }
        break;

    case 'GET': // READ
        if (isset($_GET['table']) && isset($_GET['id'])) {
            $table = $conn->real_escape_string($_GET['table']);
            $id = intval($_GET['id']);

            if (!in_array($table, $validTables)) {
                respond(400, "Invalid table specified.");
            }

            $primaryKey = $table . 'ID';
            $sql = "SELECT * FROM $table WHERE $primaryKey = $id";

            $result = $conn->query($sql);
            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                respond(200, "Record fetched successfully.", $row);
            } else {
                respond(404, "Record not found.");
            }
        } else {
            respond(400, "Table and ID are required for GET.");
        }
        break;

    case 'PUT': // UPDATE
        if (isset($data['table'], $data['id'], $data['updates'])) {
            $table = $conn->real_escape_string($data['table']);
            $id = intval($data['id']);
            $updates = $data['updates'];

            $validTables = ['Finance', 'Deposit', 'Salary', 'Withdrawal', 'Supply', 'Payment'];
            if (!in_array($table, $validTables)) {
                respond(400, "Invalid table specified.");
            }

            $primaryKey = $table . 'ID';
            $setClause = [];
            foreach ($updates as $column => $value) {
                $safeValue = $conn->real_escape_string($value);
                $setClause[] = "$column = '$safeValue'";
            }
            $setClauseStr = implode(', ', $setClause);

            $sql = "UPDATE $table SET $setClauseStr WHERE $primaryKey = $id";
            if ($conn->query($sql) === TRUE) {
                respond(200, "Record updated successfully.");
            } else {
                respond(500, "Error updating record: " . $conn->error);
            }
        } else {
            respond(400, "Table, ID, and updates are required for PUT.");
        }
        break;

    case 'DELETE': // DELETE
        if (isset($data['table'], $data['id'])) {
            $table = $conn->real_escape_string($data['table']);
            $id = intval($data['id']);

            $validTables = ['Finance', 'Deposit', 'Salary', 'Withdrawal', 'Supply', 'Payment'];
            if (!in_array($table, $validTables)) {
                respond(400, "Invalid table specified.");
            }

            $primaryKey = $table . 'ID';
            $sql = "DELETE FROM $table WHERE $primaryKey = $id";

            if ($conn->query($sql) === TRUE) {
                respond(200, "Record deleted successfully.");
            } else {
                respond(500, "Error deleting record: " . $conn->error);
            }
        } else {
            respond(400, "Table and ID are required for DELETE.");
        }
        break;


    default:
        respond(405, "Unsupported request method.");
}

$conn->close();
?>
