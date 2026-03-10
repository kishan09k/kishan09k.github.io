<?php
/**
 * Secure Chatbot API Handler
 * This file sits on your server and proxies requests to Groq API
 * Keeps your API key secure and allows usage tracking
 */

// Enable error reporting for debugging (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 0); // Set to 0 in production

// CORS headers - Allow requests from your domain
header('Access-Control-Allow-Origin: *'); // Change * to your domain in production
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

// ============================================
// CONFIGURATION - EDIT THESE VALUES
// ============================================

// Your Groq API Key (keep this secret!)
define('GROQ_API_KEY', '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');

// Database configuration (optional but recommended for tracking)
define('DB_HOST', 'localhost');
define('DB_NAME', 'chemg2jn_biochem_chatbot');
define('DB_USER', 'chemg2jn_chatbot_user');
define('DB_PASS', 'NMP@4868');

// Usage limits per IP address (security)
define('MAX_REQUESTS_PER_HOUR', 50);
define('MAX_REQUESTS_PER_DAY', 200);

// Enable database logging (set to false if you don't want to track usage)
define('ENABLE_DB_LOGGING', true);

// ============================================
// END CONFIGURATION
// ============================================

// Get request data
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['messages'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request format']);
    exit();
}

// Get user's IP address
$user_ip = $_SERVER['REMOTE_ADDR'];

// Check rate limiting
if (ENABLE_DB_LOGGING) {
    try {
        $db = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
            DB_USER,
            DB_PASS,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        
        // Check hourly limit
        $stmt = $db->prepare("
            SELECT COUNT(*) as count 
            FROM chatbot_logs 
            WHERE ip_address = ? 
            AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)
        ");
        $stmt->execute([$user_ip]);
        $hourly_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        if ($hourly_count >= MAX_REQUESTS_PER_HOUR) {
            http_response_code(429);
            echo json_encode([
                'error' => 'Rate limit exceeded. Please try again later.',
                'retry_after' => 3600
            ]);
            exit();
        }
        
        // Check daily limit
        $stmt = $db->prepare("
            SELECT COUNT(*) as count 
            FROM chatbot_logs 
            WHERE ip_address = ? 
            AND created_at > DATE_SUB(NOW(), INTERVAL 1 DAY)
        ");
        $stmt->execute([$user_ip]);
        $daily_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        if ($daily_count >= MAX_REQUESTS_PER_DAY) {
            http_response_code(429);
            echo json_encode([
                'error' => 'Daily limit exceeded. Please try again tomorrow.',
                'retry_after' => 86400
            ]);
            exit();
        }
        
    } catch (PDOException $e) {
        // Database connection failed - continue without logging
        // In production, you might want to log this error
        error_log("Database error: " . $e->getMessage());
    }
}

// Prepare request to Groq API
$groq_request = [
    'model' => $input['model'] ?? 'llama-3.3-70b-versatile',
    'messages' => $input['messages'],
    'temperature' => $input['temperature'] ?? 0.3,
    'max_tokens' => $input['max_tokens'] ?? 1024
];

// Make request to Groq API
$ch = curl_init('https://api.groq.com/openai/v1/chat/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($groq_request));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . GROQ_API_KEY
]);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Log the request if database is enabled
if (ENABLE_DB_LOGGING && isset($db)) {
    try {
        $question = '';
        if (isset($input['messages']) && is_array($input['messages'])) {
            foreach ($input['messages'] as $msg) {
                if ($msg['role'] === 'user') {
                    $question = substr($msg['content'], 0, 500); // Store first 500 chars
                    break;
                }
            }
        }
        
        $response_data = json_decode($response, true);
        $tokens_used = $response_data['usage']['total_tokens'] ?? 0;
        
        $stmt = $db->prepare("
            INSERT INTO chatbot_logs 
            (ip_address, question, tokens_used, http_status, created_at) 
            VALUES (?, ?, ?, ?, NOW())
        ");
        $stmt->execute([$user_ip, $question, $tokens_used, $http_code]);
        
    } catch (PDOException $e) {
        // Log insertion failed - continue anyway
        error_log("Log insertion error: " . $e->getMessage());
    }
}

// Return the response from Groq
http_response_code($http_code);
echo $response;
?>
