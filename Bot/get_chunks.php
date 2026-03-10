<?php
/**
 * Get PDF Chunks Endpoint
 * Returns all pre-loaded PDF chunks for the chatbot
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

// Configuration - MATCH THE SETTINGS IN api.php
define('DB_HOST', 'localhost');
define('DB_NAME', 'chemg2jn_biochem_chatbot');
define('DB_USER', 'chemg2jn_chatbot_user');
define('DB_PASS', 'NMP@4868');

try {
    $db = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    // Get all chunks
    $stmt = $db->query("
        SELECT filename, chunk_index, content, keywords, page_count
        FROM pdf_knowledge_base
        ORDER BY filename, chunk_index
    ");
    
    $chunks = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $chunks[] = [
            'filename' => $row['filename'],
            'chunkIndex' => (int)$row['chunk_index'],
            'content' => $row['content'],
            'keywords' => json_decode($row['keywords'], true) ?: []
        ];
    }
    
    echo json_encode([
        'success' => true,
        'chunks' => $chunks,
        'total' => count($chunks)
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database error'
    ]);
}
?>
