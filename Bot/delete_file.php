<?php
/**
 * Delete PDF File and its Chunks
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Configuration - MATCH THE SETTINGS IN api.php
define('DB_HOST', 'localhost');
define('DB_NAME', 'chemg2jn_biochem_chatbot');
define('DB_USER', 'chemg2jn_chatbot_user');
define('DB_PASS', 'NMP@4868');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['filename'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Filename required']);
    exit();
}

try {
    $db = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    $stmt = $db->prepare("DELETE FROM pdf_knowledge_base WHERE filename = ?");
    $stmt->execute([$input['filename']]);
    
    echo json_encode([
        'success' => true,
        'message' => 'File deleted successfully',
        'rows_deleted' => $stmt->rowCount()
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database error'
    ]);
}
?>
