<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Debug Output</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;family=JetBrains+Mono:wght@400;500;600&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    
    <style>
        :root {
            /* Common Base */
            --color-primary: #0d7ff2;
            
            /* Dark Theme (Default) */
            --color-bg-dark: #101922;
            --color-panel-dark: #0d1117;
            --color-panel-border: #30363d;
            --color-panel-hover: rgba(255,255,255,0.04);
            --color-text-main: #e6edf3;
            --color-text-comment: #8b949e;
            
            --syntax-string: #7ee787;
            --syntax-int: #ffa657;
            --syntax-bool: #d2a8ff;
            --syntax-null: #ff7b72;
            --syntax-key: #e6edf3;
            --syntax-type: #d2a8ff;
            
            --badge-public-text: rgba(74, 222, 128, 0.8);
            --badge-public-bg: rgba(74, 222, 128, 0.1);
            --badge-public-border: rgba(74, 222, 128, 0.2);
            
            --badge-protected-text: rgba(250, 204, 21, 0.8);
            --badge-protected-bg: rgba(250, 204, 21, 0.1);
            --badge-protected-border: rgba(250, 204, 21, 0.2);
            
            --badge-private-text: rgba(248, 113, 113, 0.8);
            --badge-private-bg: rgba(248, 113, 113, 0.1);
            --badge-private-border: rgba(248, 113, 113, 0.2);
        }

        html.light {
            --color-bg-dark: #ffffff;
            --color-panel-dark: #f6f8fa;
            --color-panel-border: #d0d7de;
            --color-panel-hover: rgba(0,0,0,0.04);
            --color-text-main: #1f2328;
            --color-text-comment: #656d76;
            
            --syntax-string: #116329;
            --syntax-int: #953800;
            --syntax-bool: #8250df;
            --syntax-null: #cf222e;
            --syntax-key: #1f2328;
            --syntax-type: #8250df;

            --badge-public-text: #1a7f37;
            --badge-public-bg: #dafbe1;
            --badge-public-border: rgba(26, 127, 55, 0.2);
            
            --badge-protected-text: #9a6700;
            --badge-protected-bg: #fff8c5;
            --badge-protected-border: rgba(154, 103, 0, 0.2);
            
            --badge-private-text: #cf222e;
            --badge-private-bg: #ffebe9;
            --badge-private-border: rgba(207, 34, 46, 0.2);
        }
        
        /* Ensure specific elements respect the theme */
        body {
            background-color: var(--color-bg-dark);
            color: var(--color-text-main);
            font-family: 'Inter', sans-serif;
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            overflow: hidden;
        }

        .bd-wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Header */
        header {
            height: 3.5rem;
            border-bottom: 1px solid var(--color-panel-border);
            background-color: var(--color-panel-dark);
            backdrop-filter: blur(4px);
            position: sticky;
            top: 0;
            z-index: 50;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
        }

        .header-left, .header-right {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            overflow: hidden;
        }

        .header-right {
            margin-left: auto; /* Push to right */
            flex-shrink: 0;
        }

        .icon-box {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 2rem;
            height: 2rem;
            border-radius: 0.25rem;
            background-color: rgba(13, 127, 242, 0.1);
            color: var(--color-primary);
            flex-shrink: 0;
        }

        .file-info {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .file-link {
            color: var(--color-primary);
            text-decoration: none;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.875rem;
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            transition: color 0.15s;
        }

        .file-link:hover {
            color: #60a5fa; /* Blue 400 */
            text-decoration: underline;
        }

        .caller-info {
            font-size: 0.75rem;
            color: var(--color-text-comment);
        }

        .metrics {
            display: none;
            align-items: center;
            gap: 0.5rem;
            margin-right: 0.5rem;
        }

        @media (min-width: 768px) {
            .metrics {
                display: flex;
            }
        }

        .metric-pill {
            display: flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            background-color: var(--color-panel-hover);
            border: 1px solid var(--color-panel-border);
        }

        .metric-value {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--color-text-main);
            font-family: 'JetBrains Mono', monospace;
        }

        .divider {
            height: 1.5rem;
            width: 1px;
            background-color: var(--color-panel-border);
            margin: 0 0.25rem;
            display: none;
        }

        @media (min-width: 768px) {
            .divider {
                display: block;
            }
        }

        .actions {
            display: flex;
            gap: 0.25rem;
        }

        .action-btn {
            width: 2rem;
            height: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 9999px;
            background: transparent;
            border: none;
            color: var(--color-text-comment);
            cursor: pointer;
            transition: background-color 0.15s, color 0.15s;
        }

        .action-btn:hover {
            background-color: var(--color-panel-hover);
            color: var(--color-text-main);
        }

        /* Main Content */
        main {
            flex: 1;
            overflow: auto;
            padding: 1rem;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.875rem; /* 14px */
            line-height: 1.5;
        }

        @media (min-width: 640px) {
            main {
                padding: 2rem;
            }
        }

        .container {
            max-width: 72rem; /* 6xl */
            margin: 0 auto;
        }

        /* Dump Items */
        .bd-details {
            margin-bottom: 0.25rem;
        }
        
        .bd-details summary {
            list-style: none;
        }
        
        .bd-details summary::-webkit-details-marker {
            display: none;
        }

        .bd-summary {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0 0.5rem;
            margin: 0 -0.5rem;
            border-radius: 0.25rem;
            cursor: pointer;
            user-select: none;
            transition: background-color 0.75s;
        }

        .bd-summary:hover, .bd-row:hover {
            background-color: var(--color-panel-hover);
        }

        .bd-arrow {
            font-size: 1rem;
            color: var(--color-text-comment);
            transition: transform 0.15s;
        }

        .bd-details[open] > .bd-summary .bd-arrow {
            transform: rotate(90deg);
        }

        .bd-summary-content {
            display: flex;
            align-items: baseline;
            gap: 0.5rem;
        }

        .bd-content {
            padding-left: 0.5rem;
            border-left: 1px solid rgba(48, 54, 61, 0.3);
            margin-left: 0.4375rem; /* 7px */
            margin-top: 0.25rem;
        }
        
        /* Specialized indent for array items to align nicely */
        .bd-content .bd-content {
             padding-left: 1.5rem; /* indentation for nested items */
        }

        .bd-row {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.125rem 0.5rem;
            margin: 0 -0.5rem;
            border-radius: 0.25rem;
            position: relative;
        }

        .bd-badge-wrapper {
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 2rem;
        }

        .bd-badge {
            font-size: 0.625rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.125rem 0.375rem;
            border-radius: 9999px;
            border: 1px solid transparent;
        }

        .bd-badge-public {
            color: var(--badge-public-text);
            background-color: var(--badge-public-bg);
            border-color: var(--badge-public-border);
        }

        .bd-badge-protected {
            color: var(--badge-protected-text);
            background-color: var(--badge-protected-bg);
            border-color: var(--badge-protected-border);
        }

        .bd-badge-private {
            color: var(--badge-private-text);
            background-color: var(--badge-private-bg);
            border-color: var(--badge-private-border);
        }

        .bd-property {
            flex: 1;
            display: flex;
            gap: 0.5rem;
        }

        /* Syntax Highlighting */
        .syntax-string { color: var(--syntax-string); }
        .syntax-int,
        .syntax-integer,
        .syntax-double { color: var(--syntax-int); }
        .syntax-bool { color: var(--syntax-bool); font-weight: bold; }
        .syntax-null { color: var(--syntax-null); font-style: italic; }
        .syntax-key { color: var(--syntax-key); }
        .syntax-type { color: var(--syntax-type); font-weight: bold; }
        .syntax-comment { color: var(--color-text-comment); }
        .syntax-operator { color: var(--color-text-comment); }

        /* Footer */
        footer {
            height: 2rem;
            border-top: 1px solid var(--color-panel-border);
            background-color: var(--color-panel-dark);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1rem;
            font-size: 0.75rem;
            color: var(--color-text-comment);
            user-select: none;
        }
        
        .footer-left {
            display: flex;
            gap: 1rem;
        }
        
        .footer-item {
            display: flex;
            align-items: center;
            gap: 0.375rem;
            cursor: pointer;
        }
        
        .footer-item:hover {
            color: var(--color-text-main);
        }

        /* Search Input */
        .search-container {
            display: none; /* Hidden by default */
            margin-right: 0.5rem;
        }

        .search-container.active {
            display: block;
        }

        .search-input {
            background-color: var(--color-panel-hover);
            border: 1px solid var(--color-panel-border);
            color: var(--color-text-main);
            border-radius: 0.25rem;
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            font-family: 'Inter', sans-serif;
            outline: none;
            width: 150px;
            transition: width 0.2s;
        }

        .search-input:focus {
            border-color: var(--color-primary);
            width: 200px;
        }

        .bd-highlight {
            background-color: rgba(250, 204, 21, 0.25); /* Yellow highlight */
            color: #fff;
            border-radius: 2px;
        }

        .bd-hidden {
            display: none !important;
        }

        /* Image Preview */
        .bd-image-link {
            position: relative;
            cursor: help;
        }

        .bd-preview {
            display: none;
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background-color: var(--color-panel-dark);
            border: 1px solid var(--color-panel-border);
            padding: 0.25rem;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5), 0 4px 6px -2px rgba(0, 0, 0, 0.3);
            z-index: 100;
            margin-bottom: 0.5rem;
            width: max-content;
            max-width: 300px;
        }

        .bd-preview img {
            display: block;
            max-width: 100%;
            max-height: 200px;
            border-radius: 0.25rem;
        }

        .bd-image-link:hover .bd-preview {
            display: block;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(2px);
            z-index: 100;
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background-color: var(--color-panel-dark);
            border: 1px solid var(--color-panel-border);
            border-radius: 0.5rem;
            width: 90%;
            max-width: 800px;
            max-height: 80vh;
            display: flex;
            flex-direction: column;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5), 0 10px 10px -5px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            padding: 1rem;
            border-bottom: 1px solid var(--color-panel-border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 {
            margin: 0;
            font-size: 1rem;
            font-weight: 600;
            color: var(--color-text-main);
        }

        .modal-body {
            padding: 1rem;
            overflow-y: auto;
        }

        .trace-item {
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(48, 54, 61, 0.3);
            font-family: 'JetBrains Mono', monospace;
        }

        .trace-item:last-child {
            border-bottom: none;
        }

        .trace-file {
            font-size: 0.75rem;
            color: var(--color-text-comment);
            margin-bottom: 0.25rem;
            display: block;
        }

        .trace-method {
            color: var(--color-text-main);
            font-size: 0.875rem;
        }

        .trace-arg {
            color: var(--color-text-comment);
        }
    </style>
</head>

<body>
    <div id="<?= $dumpId ?>" class="bd-wrapper">
    <!-- Top Navigation Bar -->
    <header>
        <!-- Left: Context -->
        <div class="header-left">
            <div class="icon-box">
                <span class="material-symbols-outlined" style="font-size: 20px;">terminal</span>
            </div>
            <?php if ($metadata->label): ?>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <span style="font-size: 0.875rem; font-weight: 600; color: #9ca3af;"><?= htmlspecialchars($metadata->label, ENT_QUOTES, 'UTF-8') ?>:</span>
                </div>
            <?php endif; ?>
            <div class="file-info">
                <?php
                // Clean up file path for display and editor linking
                // Removing common container/server prefixes to reduce noise
                $rawFilePath = $metadata->file;
                $cleanFilePath = \Aksoyih\Utils\PathCleaner::clean($rawFilePath);
                
                $filePath = htmlspecialchars($cleanFilePath, ENT_QUOTES, 'UTF-8');
                $line = htmlspecialchars($metadata->line, ENT_QUOTES, 'UTF-8');
                
                // For the editor link, we use the cleaned path as requested. 
                // Note: Users might need path mapping if their local path differs significantly.
                $editorLink = $editor === 'vscode' ? "vscode://file/{$cleanFilePath}:{$line}" : "phpstorm://open?file={$cleanFilePath}&line={$line}";
                ?>
                <a class="file-link" href="<?= $editorLink ?>">
                    <?= basename($cleanFilePath) ?>:<?= $line ?>
                </a>
                <?php if ($metadata->caller): ?>
                    <span class="caller-info">Called from <?= htmlspecialchars($metadata->caller, ENT_QUOTES, 'UTF-8') ?></span>
                <?php endif; ?>
            </div>
        </div>
        <!-- Right: Metrics & Actions -->
        <div class="header-right">
            <!-- Search Input -->
            <div class="search-container" id="<?= $dumpId ?>_search-container">
                <input type="text" id="<?= $dumpId ?>_search-input" class="search-input" placeholder="Search..." autocomplete="off">
            </div>

            <!-- Metrics Pills -->
            <div class="metrics">
                <div class="metric-pill">
                    <span class="material-symbols-outlined" style="font-size: 14px; color: var(--color-text-comment);">schedule</span>
                    <span class="metric-value"><?= round($metadata->executionTime, 2) ?>ms</span>
                </div>
                <div class="metric-pill">
                    <span class="material-symbols-outlined" style="font-size: 14px; color: var(--color-text-comment);">memory</span>
                    <span class="metric-value"><?= $this->formatBytes($metadata->peakMemoryUsage) ?></span>
                </div>
            </div>
            <div class="divider"></div>
            <!-- Action Buttons -->
            <div class="actions">
                <button id="<?= $dumpId ?>_theme-toggle-btn" class="action-btn" title="Toggle Theme">
                    <span class="material-symbols-outlined" style="font-size: 20px;">light_mode</span>
                </button>
                <button id="<?= $dumpId ?>_search-toggle-btn" class="action-btn" title="Search">
                    <span class="material-symbols-outlined" style="font-size: 20px;">search</span>
                </button>
                <button id="<?= $dumpId ?>_trace-btn" class="action-btn" title="Stack Trace">
                    <span class="material-symbols-outlined" style="font-size: 20px;">history</span>
                </button>
                <button id="<?= $dumpId ?>_copy-output-btn" class="action-btn" title="Copy Output">
                    <span class="material-symbols-outlined" style="font-size: 20px;">content_copy</span>
                </button>
                <button id="<?= $dumpId ?>_collapse-all-btn" class="action-btn" title="Collapse All">
                    <span class="material-symbols-outlined" style="font-size: 20px;">unfold_less</span>
                </button>
            </div>
        </div>
    </header>
    <!-- Main Content Area -->
    <main>
        <div class="container" id="<?= $dumpId ?>_dump-container">
            <?= $this->renderRepresentation($representation) ?>
            <div style="height: 5rem;"></div> <!-- Bottom Padding -->
        </div>
    </main>

    <!-- Stack Trace Modal -->
    <div id="<?= $dumpId ?>_trace-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Stack Trace</h3>
                <button id="<?= $dumpId ?>_close-trace-btn" class="action-btn">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <div class="modal-body" id="<?= $dumpId ?>_trace-list"></div>
        </div>
    </div>

    <!-- Footer Status -->
    <footer>
        <div class="footer-left">
            <div class="footer-item">
                <span class="material-symbols-outlined" style="font-size: 14px;">bug_report</span>
                <span>PHP <?= PHP_VERSION ?></span>
            </div>
            <!-- Framework detection could go here -->
        </div>
        <div>
            BetterDump <span style="color: var(--color-primary); font-weight: bold;">v2.0</span>
        </div>
    </footer>

    <script id="<?= $dumpId ?>_raw-data" type="application/json">
        <?= json_encode([
            'meta' => $metadata,
            'data' => $representation
        ], JSON_INVALID_UTF8_SUBSTITUTE | JSON_PARTIAL_OUTPUT_ON_ERROR) ?>
    </script>

    <script>
        (function() {
            const dumpId = '<?= $dumpId ?>';
            const collapseAllBtn = document.getElementById(dumpId + '_collapse-all-btn');
            const copyOutputBtn = document.getElementById(dumpId + '_copy-output-btn');
            const searchToggleBtn = document.getElementById(dumpId + '_search-toggle-btn');
            const themeToggleBtn = document.getElementById(dumpId + '_theme-toggle-btn');
            const traceBtn = document.getElementById(dumpId + '_trace-btn');
            
            const searchContainer = document.getElementById(dumpId + '_search-container');
            const searchInput = document.getElementById(dumpId + '_search-input');
            const dumpContainer = document.getElementById(dumpId + '_dump-container');
            const traceModal = document.getElementById(dumpId + '_trace-modal');
            const traceList = document.getElementById(dumpId + '_trace-list');
            const closeTraceBtn = document.getElementById(dumpId + '_close-trace-btn');
            
            const allDetails = dumpContainer.querySelectorAll('details');
            let allExpanded = true;

            // Theme Logic
            function setTheme(theme) {
                const html = document.documentElement;
                const icon = themeToggleBtn.querySelector('.material-symbols-outlined');
                
                if (theme === 'light') {
                    html.classList.remove('dark');
                    html.classList.add('light');
                    icon.textContent = 'dark_mode';
                    localStorage.setItem('bd_theme', 'light');
                } else {
                    html.classList.remove('light');
                    html.classList.add('dark');
                    icon.textContent = 'light_mode';
                    localStorage.setItem('bd_theme', 'dark');
                }
            }
            
            // Initialize Theme
            const savedTheme = localStorage.getItem('bd_theme') || 'dark';
            setTheme(savedTheme);
            
            themeToggleBtn.addEventListener('click', () => {
                const currentTheme = document.documentElement.classList.contains('light') ? 'light' : 'dark';
                setTheme(currentTheme === 'light' ? 'dark' : 'light');
            });

            // Collapse/Expand All
            collapseAllBtn.addEventListener('click', () => {
                allExpanded = !allExpanded;
                allDetails.forEach(detail => {
                    detail.open = allExpanded;
                });
                collapseAllBtn.innerHTML = allExpanded ? '<span class="material-symbols-outlined" style="font-size: 20px;">unfold_less</span>' : '<span class="material-symbols-outlined" style="font-size: 20px;">unfold_more</span>';
                collapseAllBtn.title = allExpanded ? 'Collapse All' : 'Expand All';
            });

            // Stack Trace Logic
            traceBtn.addEventListener('click', () => {
                traceModal.classList.add('active');
                if (traceList.innerHTML.trim() === '') {
                    const rawDataEl = document.getElementById(dumpId + '_raw-data');
                    if (rawDataEl) {
                        const debugData = JSON.parse(rawDataEl.textContent);
                        renderTrace(debugData.meta.trace);
                    }
                }
            });

            closeTraceBtn.addEventListener('click', () => {
                traceModal.classList.remove('active');
            });

            traceModal.addEventListener('click', (e) => {
                if (e.target === traceModal) {
                    traceModal.classList.remove('active');
                }
            });

            function renderTrace(trace) {
                if (!trace || !Array.isArray(trace) || trace.length === 0) {
                    traceList.innerHTML = '<div class="trace-item" style="text-align: center; color: var(--color-text-comment);">No stack trace available</div>';
                    return;
                }

                let html = '';
                trace.forEach((item, index) => {
                    const file = item.file ? item.file.replace(/^\/var\/www\/(html\/)?/, '') : 'internal';
                    const line = item.line ? `:${item.line}` : '';
                    let method = item.function;
                    if (item.class) {
                        method = `${item.class}${item.type}${item.function}`;
                    }
                    
                    html += `
                        <div class="trace-item">
                            <span class="trace-file">#${index} ${file}${line}</span>
                            <div class="trace-method">
                                ${method}<span class="trace-arg">()</span>
                            </div>
                        </div>
                    `;
                });
                traceList.innerHTML = html;
            }

            // Helper to reconstruct clean data from Representation AST
            function reconstructData(node) {
                if (!node) return null;
                
                // Scalar
                if (node.hasOwnProperty('type') && node.hasOwnProperty('value') && !node.hasOwnProperty('id')) {
                    return node.value;
                }
                
                // Array
                if (node.hasOwnProperty('items') && node.hasOwnProperty('count')) {
                    const result = Array.isArray(node.items) ? [] : {};
                    for (const key in node.items) {
                        result[key] = reconstructData(node.items[key]);
                    }
                    return result;
                }
                
                // Object
                if (node.hasOwnProperty('className') && node.hasOwnProperty('properties')) {
                    const obj = { '@class': node.className };
                    if (Array.isArray(node.properties)) {
                        node.properties.forEach(prop => {
                             obj[prop.name] = reconstructData(prop.value);
                        });
                    }
                    return obj;
                }
                
                // Resource
                if (node.hasOwnProperty('type') && node.hasOwnProperty('id')) {
                    return `resource(${node.type}) #${node.id}`;
                }
                
                if (Object.keys(node).length === 0) {
                    return '...'; 
                }

                return node;
            }

            // Copy Output
            copyOutputBtn.addEventListener('click', async () => {
                try {
                    const rawDataEl = document.getElementById(dumpId + '_raw-data');
                    if (!rawDataEl) throw new Error('No debug data found');
                    
                    const debugData = JSON.parse(rawDataEl.textContent);
                    const cleanData = reconstructData(debugData.data);
                    
                    // Clean path helper (shared with PHP via PathCleaner)
                    const cleanPathPattern = new RegExp(<?= json_encode(\Aksoyih\Utils\PathCleaner::patternForJs()) ?>);
                    const cleanPath = (path) => path ? path.replace(cleanPathPattern, '') : 'internal';
                    
                    // Build context
                    let llmContext = `Context: ${cleanPath(debugData.meta.file)}:${debugData.meta.line}\n` +
                                     (debugData.meta.caller ? `Caller: ${debugData.meta.caller}\n` : '');

                    // Build Trace
                    if (debugData.meta.trace && Array.isArray(debugData.meta.trace) && debugData.meta.trace.length > 0) {
                        llmContext += '\nStack Trace:\n';
                        debugData.meta.trace.forEach((item, index) => {
                            const file = cleanPath(item.file);
                            const line = item.line ? `:${item.line}` : '';
                            let method = item.function;
                            if (item.class) {
                                method = `${item.class}${item.type}${item.function}`;
                            }
                            llmContext += `#${index} ${file}${line} ${method}()\n`;
                        });
                    }

                    llmContext += `\nDumped Data:\n\`\`\`json\n${JSON.stringify(cleanData, null, 2)}\n\`\`\``;

                    await navigator.clipboard.writeText(llmContext);
                    
                    // Visual feedback
                    const originalIcon = copyOutputBtn.innerHTML;
                    copyOutputBtn.innerHTML = '<span class="material-symbols-outlined" style="font-size: 20px; color: #4ade80;">check</span>';
                    
                    setTimeout(() => {
                        copyOutputBtn.innerHTML = originalIcon;
                    }, 2000);
                    
                } catch (err) {
                    console.error('Failed to copy: ', err);
                    alert('Failed to copy to clipboard');
                }
            });

            // Search Toggle
            searchToggleBtn.addEventListener('click', () => {
                toggleSearch();
            });
            
            function toggleSearch() {
                searchContainer.classList.toggle('active');
                if (searchContainer.classList.contains('active')) {
                    searchInput.focus();
                } else {
                    searchInput.value = '';
                    performSearch('');
                    searchInput.blur();
                }
            }
            
            // Keyboard Shortcuts
            document.addEventListener('keydown', (e) => {
                // Toggle Search: Cmd+K or Ctrl+K
                if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
                    e.preventDefault();
                    if (!searchContainer.classList.contains('active')) {
                        toggleSearch();
                    } else {
                        searchInput.focus();
                    }
                }
                
                // Focus Search: '/' (if not typing in input)
                if (e.key === '/' && document.activeElement !== searchInput) {
                    e.preventDefault();
                    if (!searchContainer.classList.contains('active')) {
                        toggleSearch();
                    }
                    searchInput.focus();
                }
                
                // Close Search: Escape
                if (e.key === 'Escape') {
                    if (searchContainer.classList.contains('active')) {
                        toggleSearch();
                    }
                    if (traceModal.classList.contains('active')) {
                        traceModal.classList.remove('active');
                    }
                }
            });

            // Search Logic
            searchInput.addEventListener('input', (e) => {
                performSearch(e.target.value);
            });

            function performSearch(term) {
                term = term.toLowerCase().trim();
                
                // Clear previous highlights
                const highlighted = dumpContainer.querySelectorAll('.bd-highlight');
                highlighted.forEach(el => {
                    const parent = el.parentNode;
                    parent.replaceChild(document.createTextNode(el.textContent), el);
                    parent.normalize(); // Merge adjacent text nodes
                });
                
                if (!term) {
                    dumpContainer.querySelectorAll('.bd-hidden').forEach(el => el.classList.remove('bd-hidden'));
                    return;
                }

                const allRows = dumpContainer.querySelectorAll('.bd-row');
                allRows.forEach(row => row.classList.add('bd-hidden'));
                
                // We will walk the DOM text nodes
                const treeWalker = document.createTreeWalker(dumpContainer, NodeFilter.SHOW_TEXT, {
                    acceptNode: (node) => {
                         // Skip scripts, styles, and empty text
                         if (node.parentElement.tagName === 'SCRIPT' || node.parentElement.tagName === 'STYLE') return NodeFilter.FILTER_REJECT;
                         if (!node.textContent.trim()) return NodeFilter.FILTER_SKIP;
                         return NodeFilter.FILTER_ACCEPT;
                    }
                }, false);
                
                const nodesToHighlight = [];
                while(treeWalker.nextNode()) {
                    const node = treeWalker.currentNode;
                    if (node.textContent.toLowerCase().includes(term)) {
                         nodesToHighlight.push(node);
                    }
                }

                if (nodesToHighlight.length === 0) return;

                nodesToHighlight.forEach(node => {
                    // Highlight the text
                    const span = document.createElement('span');
                    span.innerHTML = node.textContent.replace(new RegExp(`(${term.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi'), '<span class="bd-highlight">$1</span>');
                    node.parentNode.replaceChild(span, node);

                    // Reveal parents
                    let current = span.parentElement;
                    while (current && current !== dumpContainer) {
                        if (current.classList.contains('bd-row')) {
                            current.classList.remove('bd-hidden');
                        }
                        if (current.tagName === 'DETAILS') {
                            current.open = true;
                            current.classList.remove('bd-hidden');
                        }
                        current = current.parentElement;
                    }
                });
            }
        })();
    </script>
    </div>
</body>
</html>
