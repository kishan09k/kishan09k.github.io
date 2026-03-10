<?php
/**
 * Save PDF Chunks to Database
 * Called from admin_upload.html
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

if (!$input || !isset($input['filename']) || !isset($input['chunks'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid request format']);
    exit();
}

try {
    $db = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    // Create table if it doesn't exist
    $db->exec("
        CREATE TABLE IF NOT EXISTS pdf_knowledge_base (
            id INT AUTO_INCREMENT PRIMARY KEY,
            filename VARCHAR(255) NOT NULL,
            chunk_index INT NOT NULL,
            content TEXT NOT NULL,
            keywords TEXT,
            page_count INT DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_filename (filename),
            INDEX idx_chunk_index (chunk_index)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    
    // Delete existing chunks for this filename
    $stmt = $db->prepare("DELETE FROM pdf_knowledge_base WHERE filename = ?");
    $stmt->execute([$input['filename']]);
    
    // Insert new chunks
    $stmt = $db->prepare("
        INSERT INTO pdf_knowledge_base (filename, chunk_index, content, keywords, page_count) 
        VALUES (?, ?, ?, ?, ?)
    ");
    
    foreach ($input['chunks'] as $chunk) {
        $stmt->execute([
            $input['filename'],
            $chunk['chunkIndex'],
            $chunk['content'],
            json_encode($chunk['keywords']),
            $input['pageCount'] ?? 0
        ]);
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Chunks saved successfully',
        'chunks_saved' => count($input['chunks'])
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database error: ' . $e->getMessage()
    ]);
}
?>
