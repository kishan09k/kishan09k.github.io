<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Upload PDFs for Students</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #0f766e 0%, #0891b2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .header {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .section {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .file-upload {
            border: 2px dashed #0891b2;
            border-radius: 8px;
            padding: 40px;
            text-align: center;
            cursor: pointer;
            transition: background 0.3s;
        }

        .file-upload:hover {
            background: #f0f9ff;
        }

        .file-upload input {
            display: none;
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
        }

        .btn:hover {
            background: #0e7490;
        }

        .btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .btn-danger {
            background: #dc2626;
        }

        .btn-danger:hover {
            background: #b91c1c;
        }

        .file-list {
            margin-top: 20px;
        }

        .file-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background: #f9fafb;
            border-radius: 6px;
            margin-bottom: 10px;
        }

        .status {
            padding: 12px;
            border-radius: 6px;
            margin: 15px 0;
        }

        .status.success {
            background: #d1fae5;
            color: #065f46;
        }

        .status.error {
            background: #fee2e2;
            color: #991b1b;
        }

        .status.info {
            background: #dbeafe;
            color: #1e40af;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        th {
            background: #f3f4f6;
            font-weight: 600;
        }

        .info-box {
            background: #fff3cd;
            border: 1px solid #fbbf24;
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
        }

        .info-box h3 {
            color: #92400e;
            margin-bottom: 8px;
        }

        .info-box p {
            color: #92400e;
            font-size: 14px;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔧 Instructor Admin Panel</h1>
            <p>Upload PDFs once - Students use them automatically</p>
        </div>

        <div class="info-box">
            <h3>📖 How This Works:</h3>
            <p>
                1. Upload your PDF lecture notes here<br>
                2. PDFs are processed in your browser and saved to database<br>
                3. Students visit the chatbot and PDFs are automatically loaded<br>
                4. Students don't need to upload anything - just ask questions!
            </p>
        </div>

        <div class="section">
            <h2>Upload PDF Lecture Notes</h2>
            <p style="color: #666; margin: 10px 0 20px 0;">Upload PDFs that will be available to all students</p>
            
            <div class="file-upload" id="fileUpload">
                <input type="file" id="fileInput" accept=".pdf" multiple>
                <p style="font-size: 48px;">📄</p>
                <p>Click to select PDF files or drag and drop here</p>
                <p style="color: #666; font-size: 14px; margin-top: 10px;">You can select multiple PDFs at once</p>
            </div>

            <div id="uploadStatus"></div>
            <div id="uploadedFiles" class="file-list"></div>
        </div>

        <div class="section">
            <h2>Uploaded PDFs</h2>
            <div id="statusMessage"></div>
            <table id="filesTable">
                <thead>
                    <tr>
                        <th>Filename</th>
                        <th>Chunks</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="filesTableBody">
                    <tr>
                        <td colspan="4" style="text-align: center; color: #666;">
                            Loading...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="section">
            <h2>📋 Instructions for Students</h2>
            <p style="line-height: 1.6; color: #333;">
                Share this URL with your students:<br>
                <strong>https://yourdomain.com/chatbot/knowledge-chatbot-smart.html</strong>
                <br><br>
                Students will:
                <br>• Visit the URL
                <br>• Click "Initialize Chat Assistant"
                <br>• Start asking questions immediately
                <br>• All PDFs you uploaded will be automatically loaded!
            </p>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

        const SAVE_CHUNKS_ENDPOINT = 'save_chunks.php';
        const GET_CHUNKS_ENDPOINT = 'get_chunks.php';
        const DELETE_FILE_ENDPOINT = 'delete_file.php';

        const fileUpload = document.getElementById('fileUpload');
        const fileInput = document.getElementById('fileInput');
        const uploadStatus = document.getElementById('uploadStatus');
        const uploadedFiles = document.getElementById('uploadedFiles');

        fileUpload.addEventListener('click', () => fileInput.click());
        fileInput.addEventListener('change', (e) => handleFiles(e.target.files));

        fileUpload.addEventListener('dragover', (e) => {
            e.preventDefault();
            fileUpload.style.background = '#f0f9ff';
        });

        fileUpload.addEventListener('dragleave', () => {
            fileUpload.style.background = '';
        });

        fileUpload.addEventListener('drop', (e) => {
            e.preventDefault();
            fileUpload.style.background = '';
            handleFiles(e.dataTransfer.files);
        });

        async function handleFiles(files) {
            for (let file of files) {
                if (file.type === 'application/pdf') {
                    await processPDF(file);
                }
            }
            loadUploadedFiles(); // Refresh the list
        }

        async function processPDF(file) {
            showStatus(`Processing ${file.name}...`, 'info');

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

                // Create chunks (300 words each)
                const chunks = createChunks(fullText, 300);
                
                // Prepare data to send to server
                const chunksData = chunks.map((content, index) => ({
                    chunkIndex: index,
                    content: content,
                    keywords: extractKeywords(content)
                }));

                // Save to database
                const response = await fetch(SAVE_CHUNKS_ENDPOINT, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        filename: file.name,
                        pageCount: pdf.numPages,
                        chunks: chunksData
                    })
                });

                const result = await response.json();

                if (result.success) {
                    showStatus(`✅ Successfully uploaded ${file.name} (${chunks.length} chunks)`, 'success');
                } else {
                    showStatus(`❌ Error uploading ${file.name}: ${result.error}`, 'error');
                }

            } catch (error) {
                showStatus(`❌ Error processing ${file.name}: ${error.message}`, 'error');
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
            const stopWords = new Set(['the', 'a', 'an', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for', 'of', 'with', 'by', 'from', 'as', 'is', 'was', 'are', 'were', 'be', 'been', 'being', 'have', 'has', 'had', 'do', 'does', 'did', 'will', 'would', 'should', 'could', 'may', 'might', 'can', 'this', 'that', 'these', 'those']);
            
            const words = text.toLowerCase()
                .replace(/[^\w\s]/g, ' ')
                .split(/\s+/)
                .filter(w => w.length > 3 && !stopWords.has(w));
            
            const wordFreq = {};
            words.forEach(word => {
                wordFreq[word] = (wordFreq[word] || 0) + 1;
            });
            
            return wordFreq;
        }

        function showStatus(message, type) {
            uploadStatus.innerHTML = `<div class="status ${type}">${message}</div>`;
        }

        async function loadUploadedFiles() {
            try {
                const response = await fetch(GET_CHUNKS_ENDPOINT);
                const data = await response.json();

                if (data.success) {
                    // Group chunks by filename
                    const fileGroups = {};
                    data.chunks.forEach(chunk => {
                        if (!fileGroups[chunk.filename]) {
                            fileGroups[chunk.filename] = [];
                        }
                        fileGroups[chunk.filename].push(chunk);
                    });

                    const tbody = document.getElementById('filesTableBody');
                    tbody.innerHTML = '';

                    if (Object.keys(fileGroups).length === 0) {
                        tbody.innerHTML = '<tr><td colspan="4" style="text-align: center; color: #666;">No PDFs uploaded yet</td></tr>';
                    } else {
                        Object.entries(fileGroups).forEach(([filename, chunks]) => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${filename}</td>
                                <td>${chunks.length} chunks</td>
                                <td><span style="color: #059669;">✓ Active</span></td>
                                <td>
                                    <button class="btn btn-danger" onclick="deleteFile('${filename}')" style="padding: 6px 12px; font-size: 14px;">
                                        Delete
                                    </button>
                                </td>
                            `;
                            tbody.appendChild(row);
                        });
                    }
                }
            } catch (error) {
                console.error('Error loading files:', error);
            }
        }

        async function deleteFile(filename) {
            if (!confirm(`Delete "${filename}" and all its chunks?`)) {
                return;
            }

            try {
                const response = await fetch(DELETE_FILE_ENDPOINT, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ filename })
                });

                const result = await response.json();

                if (result.success) {
                    showStatus(`✅ Deleted ${filename}`, 'success');
                    loadUploadedFiles();
                } else {
                    showStatus(`❌ Error deleting ${filename}`, 'error');
                }
            } catch (error) {
                showStatus(`❌ Error: ${error.message}`, 'error');
            }
        }

        // Load uploaded files on page load
        loadUploadedFiles();
    </script>
</body>
</html>
