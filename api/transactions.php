<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];

function sendJsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    echo json_encode($data);
    exit;
}

switch($method) {
    case 'GET':
        try {
            if (isset($_GET['ids'])) {
                // Fetch specific transactions by IDs
                $ids = explode(',', $conn->real_escape_string($_GET['ids']));
                $ids = array_map('intval', $ids); // Ensure all values are integers
                $idsList = implode(',', $ids);
                
                $sql = "SELECT * FROM transactions WHERE id IN ($idsList) ORDER BY transactionDate DESC";
            } elseif (isset($_GET['id'])) {
                // Fetch single transaction
                $id = intval($_GET['id']);
                $sql = "SELECT * FROM transactions WHERE id = $id";
            } else {
                // Fetch aggregated transaction data
                $sql = "SELECT 
                        YEAR(transactionDate) as year,
                        MONTH(transactionDate) as month,
                        COUNT(*) as total_transactions,
                        SUM(CAST(amount AS DECIMAL(10,2))) as total_amount,
                        GROUP_CONCAT(id) as transaction_ids
                        FROM transactions 
                        GROUP BY YEAR(transactionDate), MONTH(transactionDate)
                        ORDER BY year DESC, month DESC";
            }
            
            error_log("SQL Query: " . $sql);
            
            $result = $conn->query($sql);
            if (!$result) {
                throw new Exception("Query failed: " . $conn->error);
            }
            
            $data = [];
            while ($row = $result->fetch_assoc()) {
                // Convert numeric fields to appropriate types
                if (isset($row['total_amount'])) {
                    $row['total_amount'] = floatval($row['total_amount']);
                    $row['total_transactions'] = intval($row['total_transactions']);
                    $row['month'] = intval($row['month']);
                    $row['year'] = intval($row['year']);
                }
                if (isset($row['amount'])) {
                    $row['amount'] = floatval($row['amount']);
                    $row['status'] = intval($row['status']);
                }
                $data[] = $row;
            }
            
            sendJsonResponse($data);
            
        } catch (Exception $e) {
            error_log("Error in GET request: " . $e->getMessage());
            sendJsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
        break;

    case 'POST':
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            
            // Validate required fields
            // $requiredFields = ['productID', 'productName', 'amount', 'customerName', 'status', 'transactionDate'];
            // foreach ($requiredFields as $field) {
            //     if (!isset($data[$field]) || empty($data[$field])) {
            //         throw new Exception("Missing required field: $field");
            //     }
            // }
            
            // Validate and sanitize input
            $productID = $conn->real_escape_string($data['productID']);
            $productName = $conn->real_escape_string($data['productName']);
            $amount = floatval($data['amount']);
            if ($amount <= 0) {
                throw new Exception("Amount must be greater than 0");
            }
            $customerName = $conn->real_escape_string($data['customerName']);
            $status = intval($data['status']);
            if (!in_array($status, [0, 1])) {
                throw new Exception("Invalid status value");
            }
            $transactionDate = $conn->real_escape_string($data['transactionDate']);
            $createBy = $conn->real_escape_string($data['createBy'] ?? 'system');

            $stmt = $conn->prepare("INSERT INTO transactions (productID, productName, amount, customerName, status, transactionDate, createBy) VALUES (?, ?, ?, ?, ?, ?, ?)");
            
            if (!$stmt) {
                throw new Exception("Prepare statement failed: " . $conn->error);
            }
            
            $stmt->bind_param("ssdsiss", 
                $productID,
                $productName,
                $amount,
                $customerName,
                $status,
                $transactionDate,
                $createBy
            );
            
            if (!$stmt->execute()) {
                throw new Exception("Execute failed: " . $stmt->error);
            }
            
            sendJsonResponse([
                'status' => 'success',
                'message' => 'Transaction added successfully',
                'id' => $stmt->insert_id
            ]);
            
        } catch (Exception $e) {
            sendJsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
        break;

    case 'DELETE':
        try {
            if (!isset($_GET['id'])) {
                throw new Exception("No transaction ID provided");
            }
            
            $id = intval($_GET['id']);
            $stmt = $conn->prepare("DELETE FROM transactions WHERE id = ?");
            
            if (!$stmt) {
                throw new Exception("Prepare statement failed: " . $conn->error);
            }
            
            $stmt->bind_param("i", $id);
            
            if (!$stmt->execute()) {
                throw new Exception("Delete failed: " . $stmt->error);
            }
            
            if ($stmt->affected_rows === 0) {
                throw new Exception("Transaction not found");
            }
            
            sendJsonResponse([
                'status' => 'success',
                'message' => 'Transaction deleted successfully'
            ]);
            
        } catch (Exception $e) {
            sendJsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
        break;

        case 'PUT':
            try {
                $data = json_decode(file_get_contents("php://input"), true);
                
                // Validate and sanitize input
                $id = intval($data['id']);
                $productID = $conn->real_escape_string($data['productID']);
                $productName = $conn->real_escape_string($data['productName']);
                $amount = floatval($data['amount']);
                if ($amount <= 0) {
                    throw new Exception("Amount must be greater than 0");
                }
                $customerName = $conn->real_escape_string($data['customerName']);
                $status = intval($data['status']);
                if (!in_array($status, [0, 1])) {
                    throw new Exception("Invalid status value");
                }
                $transactionDate = $conn->real_escape_string($data['transactionDate']);
                $modifiedBy = $conn->real_escape_string($data['modifiedBy'] ?? 'system');
                
                $stmt = $conn->prepare("UPDATE transactions SET 
                    productID = ?, 
                    productName = ?, 
                    amount = ?, 
                    customerName = ?, 
                    status = ?, 
                    transactionDate = ?,
                    modifiedBy = ?,
                    modifiedDate = CURRENT_TIMESTAMP
                    WHERE id = ?");
                
                if (!$stmt) {
                    throw new Exception("Prepare statement failed: " . $conn->error);
                }
                
                $stmt->bind_param("ssdsissi", 
                    $productID,
                    $productName,
                    $amount,
                    $customerName,
                    $status,
                    $transactionDate,
                    $modifiedBy,
                    $id
                );
                
                if (!$stmt->execute()) {
                    throw new Exception("Execute failed: " . $stmt->error);
                }
                
                if ($stmt->affected_rows === 0) {
                    throw new Exception("No transaction was updated. Transaction might not exist.");
                }
                
                sendJsonResponse([
                    'status' => 'success',
                    'message' => 'Transaction updated successfully'
                ]);
                
            } catch (Exception $e) {
                sendJsonResponse([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ], 500);
            }
            break;
        
    default:
        sendJsonResponse([
            'status' => 'error',
            'message' => 'Method not allowed'
        ], 405);
}

$conn->close();
?>