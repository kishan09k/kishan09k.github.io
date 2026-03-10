<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biochemical Engineering Knowledge Assistant</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #0f766e 0%, #0891b2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
        }

        .header {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .header h1 {
            color: #333;
            margin-bottom: 10px;
        }

        .header p {
            color: #666;
            font-size: 14px;
        }

        .badge {
            display: inline-block;
            background: #4caf50;
            color: white;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            margin-left: 10px;
        }

        .setup-section {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .setup-section h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
        }

        .form-group input[type="text"],
        .form-group input[type="password"],
        .form-group input[type="number"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #0891b2;
        }

        .file-upload {
            border: 2px dashed #0891b2;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            transition: background 0.3s;
        }

        .file-upload:hover {
            background: #f8f9ff;
        }

        .file-upload input {
            display: none;
        }

        .uploaded-files {
            margin-top: 15px;
        }

        .file-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px;
            background: #f5f5f5;
            border-radius: 6px;
            margin-bottom: 8px;
        }

        .file-item button {
            background: #ff4444;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }

        .btn {
            background: #0891b2;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            transition: background 0.3s;
        }

        .btn:hover {
            background: #0e7490;
        }

        .btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .chat-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            height: 600px;
            display: none;
            flex-direction: column;
        }

        .chat-container.active {
            display: flex;
        }

        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
        }

        .message {
            margin-bottom: 20px;
            display: flex;
            gap: 12px;
        }

        .message.user {
            flex-direction: row-reverse;
        }

        .message-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            flex-shrink: 0;
        }

        .message.user .message-avatar {
            background: #0891b2;
            color: white;
        }

        .message.assistant .message-avatar {
            background: #e0e0e0;
            color: #333;
        }

        .message-content {
            max-width: 70%;
            padding: 12px 16px;
            border-radius: 12px;
            line-height: 1.5;
        }

        .message.user .message-content {
            background: #0891b2;
            color: white;
            border-bottom-right-radius: 4px;
        }

        .message.assistant .message-content {
            background: #f5f5f5;
            color: #333;
            border-bottom-left-radius: 4px;
        }

        .source-reference {
            margin-top: 10px;
            padding: 8px;
            background: #e8f4f8;
            border-radius: 6px;
            font-size: 12px;
            color: #0277bd;
        }

        .chat-input-area {
            padding: 20px;
            border-top: 1px solid #e0e0e0;
        }

        .chat-input-container {
            display: flex;
            gap: 10px;
        }

        .chat-input-container input {
            flex: 1;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
        }

        .chat-input-container input:focus {
            outline: none;
            border-color: #0891b2;
        }

        .status-message {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .status-message.info {
            background: #e3f2fd;
            color: #1976d2;
        }

        .status-message.error {
            background: #ffebee;
            color: #c62828;
        }

        .status-message.success {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .loading {
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #0891b2;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .help-text {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }

        .stats-box {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
        }

        .stats-box h3 {
            font-size: 14px;
            color: #333;
            margin-bottom: 10px;
        }

        .stat-item {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            font-size: 13px;
            color: #666;
        }

        .info-box {
            background: #fff3cd;
            border: 1px solid #ffc107;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .info-box h3 {
            color: #856404;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .info-box p {
            color: #856404;
            font-size: 13px;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🧬 Biochemical Engineering Knowledge Assistant <span class="badge">SMART SEARCH</span></h1>
            <p>AI-powered assistant for Biochemical Engineering - Get instant answers from your lecture notes</p>
        </div>

        <div id="setupSection" class="setup-section">
            <h2>Setup Your Knowledge Base</h2>
            
            <div class="info-box">
                <h3>🎯 How This Works</h3>
                <p>Your instructor has pre-loaded all lecture notes. When you click "Initialize" below, the notes will be automatically loaded. No upload needed! You can also upload additional PDFs if you want to include your own notes.</p>
            </div>

            <!-- API Key field removed - using secure backend -->
            <div class="info-box" style="background: #e8f5e9; border-color: #4caf50;">
                <h3>🔒 Secure Connection</h3>
                <p style="color: #2e7d32;">This chatbot uses a secure backend server. No API key needed!</p>
            </div>

            <div class="form-group">
                <label for="courseName">Course Name (Optional)</label>
                <input type="text" id="courseName" value="Biochemical Engineering" placeholder="e.g., Biochemical Engineering">
            </div>

            <div class="form-group">
                <label for="chunkSize">Chunk Size (words per section)</label>
                <input type="number" id="chunkSize" value="300" min="100" max="1000">
                <p class="help-text">Smaller chunks = more precise search. Recommended: 200-400 words</p>
            </div>

            <div class="form-group">
                <label for="topChunks">Number of Relevant Sections to Send</label>
                <input type="number" id="topChunks" value="5" min="1" max="10">
                <p class="help-text">How many relevant chunks to send to AI. More = better context but uses more tokens</p>
            </div>

            <div class="form-group">
                <label>Upload Additional Notes (Optional)</label>
                <div class="file-upload" id="fileUpload">
                    <input type="file" id="fileInput" accept=".pdf" multiple>
                    <p>📄 Upload your own notes (optional)</p>
                    <p class="help-text">Instructor notes are pre-loaded. You can add your own PDFs here if needed.</p>
                </div>
                <div id="uploadedFiles" class="uploaded-files"></div>
            </div>

            <div id="statsBox" class="stats-box" style="display: none;">
                <h3>📊 Knowledge Base Statistics</h3>
                <div class="stat-item">
                    <span>Total Files:</span>
                    <span id="statFiles">0</span>
                </div>
                <div class="stat-item">
                    <span>Total Pages:</span>
                    <span id="statPages">0</span>
                </div>
                <div class="stat-item">
                    <span>Total Chunks:</span>
                    <span id="statChunks">0</span>
                </div>
                <div class="stat-item">
                    <span>Estimated Capacity:</span>
                    <span id="statCapacity">Excellent</span>
                </div>
            </div>

            <div id="statusMessage"></div>

            <button class="btn" id="initializeBtn">Initialize Chat Assistant</button>
        </div>

        <div id="chatContainer" class="chat-container">
            <div class="chat-messages" id="chatMessages"></div>
            <div class="chat-input-area">
                <div class="chat-input-container">
                    <input type="text" id="messageInput" placeholder="e.g., Explain enzyme kinetics or How does a CSTR work?">
                    <button class="btn" id="sendBtn">Send</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script>
        // Set up PDF.js worker
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

        // Configuration
        const API_ENDPOINT = 'api.php'; // Change this to your full URL if needed: 'https://yourdomain.com/api.php'
        const CHUNKS_ENDPOINT = 'get_chunks.php'; // Endpoint to get pre-loaded chunks
        let knowledgeChunks = [];
        let chunkSize = 300;
        let topChunks = 5;
        let fileStats = { files: 0, pages: 0, chunks: 0 };
        let loadedFromServer = false;

        // File upload handling
        const fileUpload = document.getElementById('fileUpload');
        const fileInput = document.getElementById('fileInput');
        const uploadedFiles = document.getElementById('uploadedFiles');
        const statusMessage = document.getElementById('statusMessage');

        fileUpload.addEventListener('click', () => fileInput.click());

        fileUpload.addEventListener('dragover', (e) => {
            e.preventDefault();
            fileUpload.style.background = '#f8f9ff';
        });

        fileUpload.addEventListener('dragleave', () => {
            fileUpload.style.background = '';
        });

        fileUpload.addEventListener('drop', (e) => {
            e.preventDefault();
            fileUpload.style.background = '';
            handleFiles(e.dataTransfer.files);
        });

        fileInput.addEventListener('change', (e) => {
            handleFiles(e.target.files);
        });

        async function handleFiles(files) {
            for (let file of files) {
                if (file.type === 'application/pdf') {
                    await processPDF(file);
                }
            }
        }

        async function processPDF(file) {
            showStatus('Processing ' + file.name + '...', 'info');

            try {
                const arrayBuffer = await file.arrayBuffer();
                const pdf = await pdfjsLib.getDocument({ data: arrayBuffer }).promise;
                let fullText = '';

                for (let i = 1; i <= pdf.numPages; i++) {
                    const page = await pdf.getPage(i);
                    const textContent = await page.getTextContent();
                    const pageText = textContent.items.map(item => item.str).join(' ');
                    fullText += pageText + ' ';
                }

                // Split into chunks
                const chunks = createChunks(fullText, chunkSize);
                
                chunks.forEach((chunk, index) => {
                    knowledgeChunks.push({
                        filename: file.name,
                        chunkIndex: index,
                        content: chunk,
                        keywords: extractKeywords(chunk)
                    });
                });

                fileStats.files++;
                fileStats.pages += pdf.numPages;
                fileStats.chunks += chunks.length;

                addFileToList(file.name, pdf.numPages, chunks.length);
                updateStats();
                showStatus('Successfully processed ' + file.name + ' (' + chunks.length + ' chunks created)', 'success');

            } catch (error) {
                showStatus('Error processing ' + file.name + ': ' + error.message, 'error');
            }
        }

        function createChunks(text, wordsPerChunk) {
            const words = text.split(/\s+/).filter(w => w.length > 0);
            const chunks = [];
            
            for (let i = 0; i < words.length; i += wordsPerChunk) {
                const chunk = words.slice(i, i + wordsPerChunk).join(' ');
                if (chunk.trim().length > 0) {
                    chunks.push(chunk);
                }
            }
            
            return chunks;
        }

        function extractKeywords(text) {
            // Convert to lowercase and remove common words
            const stopWords = new Set(['the', 'a', 'an', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for', 'of', 'with', 'by', 'from', 'as', 'is', 'was', 'are', 'were', 'be', 'been', 'being', 'have', 'has', 'had', 'do', 'does', 'did', 'will', 'would', 'should', 'could', 'may', 'might', 'can', 'this', 'that', 'these', 'those', 'i', 'you', 'he', 'she', 'it', 'we', 'they', 'what', 'which', 'who', 'when', 'where', 'why', 'how']);
            
            const words = text.toLowerCase()
                .replace(/[^\w\s]/g, ' ')
                .split(/\s+/)
                .filter(w => w.length > 3 && !stopWords.has(w));
            
            // Count word frequency
            const wordFreq = {};
            words.forEach(word => {
                wordFreq[word] = (wordFreq[word] || 0) + 1;
            });
            
            return wordFreq;
        }

        function findRelevantChunks(question, numChunks) {
            const questionKeywords = extractKeywords(question);
            
            // Score each chunk based on keyword overlap
            const scoredChunks = knowledgeChunks.map(chunk => {
                let score = 0;
                
                for (let keyword in questionKeywords) {
                    if (chunk.keywords[keyword]) {
                        score += questionKeywords[keyword] * chunk.keywords[keyword];
                    }
                }
                
                return { chunk, score };
            });
            
            // Sort by score and return top N
            scoredChunks.sort((a, b) => b.score - a.score);
            return scoredChunks.slice(0, numChunks).map(sc => sc.chunk);
        }

        function addFileToList(filename, pageCount, chunkCount) {
            const fileItem = document.createElement('div');
            fileItem.className = 'file-item';
            fileItem.innerHTML = `
                <span>📄 ${filename} (${pageCount} pages, ${chunkCount} chunks)</span>
                <button onclick="removeFile('${filename}')">Remove</button>
            `;
            uploadedFiles.appendChild(fileItem);
        }

        function removeFile(filename) {
            const removedChunks = knowledgeChunks.filter(c => c.filename === filename);
            knowledgeChunks = knowledgeChunks.filter(c => c.filename !== filename);
            
            fileStats.chunks -= removedChunks.length;
            fileStats.files--;
            
            const fileItems = uploadedFiles.querySelectorAll('.file-item');
            fileItems.forEach(item => {
                if (item.textContent.includes(filename)) {
                    item.remove();
                }
            });
            
            updateStats();
        }

        function updateStats() {
            document.getElementById('statsBox').style.display = 'block';
            document.getElementById('statFiles').textContent = fileStats.files;
            document.getElementById('statPages').textContent = fileStats.pages;
            document.getElementById('statChunks').textContent = fileStats.chunks;
            
            let capacity = 'Excellent';
            if (fileStats.chunks > 1000) capacity = 'Good';
            if (fileStats.chunks > 5000) capacity = 'Fair';
            if (fileStats.chunks > 10000) capacity = 'Consider reducing chunk size';
            
            document.getElementById('statCapacity').textContent = capacity;
        }

        function showStatus(message, type) {
            statusMessage.innerHTML = `<div class="status-message ${type}">${message}</div>`;
            setTimeout(() => {
                if (type === 'success') statusMessage.innerHTML = '';
            }, 3000);
        }

        // Initialize chat
        document.getElementById('initializeBtn').addEventListener('click', async () => {
            const courseName = document.getElementById('courseName').value.trim();
            chunkSize = parseInt(document.getElementById('chunkSize').value);
            topChunks = parseInt(document.getElementById('topChunks').value);

            // First, try to load chunks from server
            if (!loadedFromServer) {
                showStatus('Loading lecture notes from server...', 'info');
                try {
                    const response = await fetch(CHUNKS_ENDPOINT);
                    const data = await response.json();
                    
                    if (data.success && data.chunks.length > 0) {
                        knowledgeChunks = data.chunks;
                        loadedFromServer = true;
                        
                        // Calculate stats
                        const uniqueFiles = [...new Set(data.chunks.map(c => c.filename))];
                        fileStats.files = uniqueFiles.length;
                        fileStats.chunks = data.chunks.length;
                        
                        showStatus(`Loaded ${uniqueFiles.length} lecture note(s) with ${data.chunks.length} sections`, 'success');
                    }
                } catch (error) {
                    console.error('Could not load from server:', error);
                }
            }

            if (knowledgeChunks.length === 0) {
                showStatus('Please upload at least one PDF file', 'error');
                return;
            }

            // Hide setup, show chat
            document.getElementById('setupSection').style.display = 'none';
            document.getElementById('chatContainer').classList.add('active');

            // Add welcome message
            addMessage('assistant', `Welcome to the Biochemical Engineering Assistant! I've loaded ${fileStats.files} file(s) with ${fileStats.chunks} searchable sections covering topics like enzyme kinetics, bioreactor design, fermentation, cell culture, and downstream processing. Ask me anything about ${courseName || 'the course material'}!`);
        });

        // Chat functionality
        document.getElementById('sendBtn').addEventListener('click', sendMessage);
        document.getElementById('messageInput').addEventListener('keypress', (e) => {
            if (e.key === 'Enter') sendMessage();
        });

        async function sendMessage() {
            const input = document.getElementById('messageInput');
            const message = input.value.trim();

            if (!message) return;

            addMessage('user', message);
            input.value = '';

            // Show loading
            const loadingId = addMessage('assistant', '<span class="loading"></span> Searching knowledge base...');

            try {
                const response = await queryKnowledgeBase(message);
                removeMessage(loadingId);
                addMessage('assistant', response.answer, response.sources);
            } catch (error) {
                removeMessage(loadingId);
                addMessage('assistant', '❌ Error: ' + error.message);
            }
        }

        async function queryKnowledgeBase(question) {
            // Find relevant chunks
            const relevantChunks = findRelevantChunks(question, topChunks);
            
            if (relevantChunks.length === 0) {
                return {
                    answer: "I couldn't find any relevant information in the lecture notes to answer your question. Could you rephrase or ask about a different topic?",
                    sources: []
                };
            }

            // Build context from relevant chunks
            let context = 'You are a helpful Biochemical Engineering teaching assistant. Answer the student\'s question based ONLY on the following relevant sections from lecture notes. Provide clear explanations of concepts, equations, and processes. If the answer requires calculations, show the steps. If the answer is not in these sections, say so.\n\n';
            context += '=== RELEVANT LECTURE SECTIONS ===\n\n';
            
            relevantChunks.forEach((chunk, index) => {
                context += `[Section ${index + 1} from ${chunk.filename}]\n${chunk.content}\n\n`;
            });

            context += '=== END OF SECTIONS ===\n\n';
            context += 'Student question: ' + question;

            // Call our secure PHP backend
            const response = await fetch(API_ENDPOINT, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    model: 'llama-3.3-70b-versatile',
                    messages: [
                        {
                            role: 'system',
                            content: 'You are a helpful Biochemical Engineering teaching assistant. Answer questions based only on the provided lecture note sections. Provide clear, detailed explanations of engineering concepts, processes, and calculations. When relevant, explain the practical applications in biotechnology and bioprocessing.'
                        },
                        {
                            role: 'user',
                            content: context
                        }
                    ],
                    temperature: 0.3,
                    max_tokens: 1024
                })
            });

            if (!response.ok) {
                const error = await response.json();
                throw new Error(error.error || 'API request failed');
            }

            const data = await response.json();
            
            // Extract unique source files
            const sources = [...new Set(relevantChunks.map(c => c.filename))];
            
            return {
                answer: data.choices[0].message.content,
                sources: sources
            };
        }

        function addMessage(role, content, sources = []) {
            const messagesDiv = document.getElementById('chatMessages');
            const messageId = 'msg_' + Date.now();
            
            const messageDiv = document.createElement('div');
            messageDiv.className = 'message ' + role;
            messageDiv.id = messageId;
            
            const avatar = role === 'user' ? '👤' : '🤖';
            
            let sourceHtml = '';
            if (sources && sources.length > 0) {
                sourceHtml = `<div class="source-reference">📚 Sources: ${sources.join(', ')}</div>`;
            }
            
            messageDiv.innerHTML = `
                <div class="message-avatar">${avatar}</div>
                <div class="message-content">${content}${sourceHtml}</div>
            `;
            
            messagesDiv.appendChild(messageDiv);
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
            
            return messageId;
        }

        function removeMessage(messageId) {
            const message = document.getElementById(messageId);
            if (message) message.remove();
        }
    </script>
</body>
</html>
