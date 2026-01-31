<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>BetterDump v2 Debug Output</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;family=JetBrains+Mono:wght@400;500;600&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    
    <style>
        :root {
            --color-primary: #0d7ff2;
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
            --syntax-type: #d2a8ff; /* Same as bool/keyword */
            
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

        /* Header */
        header {
            height: 3.5rem;
            border-bottom: 1px solid var(--color-panel-border);
            background-color: rgba(13, 17, 23, 0.95);
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
            background-color: #21262d;
            border: 1px solid rgba(48, 54, 61, 0.5);
        }

        .metric-value {
            font-size: 0.75rem;
            font-weight: 600;
            color: #d1d5db; /* Gray 300 */
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
            color: #9ca3af; /* Gray 400 */
            cursor: pointer;
            transition: background-color 0.15s, color 0.15s;
        }

        .action-btn:hover {
            background-color: #21262d;
            color: white;
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
        .syntax-int { color: var(--syntax-int); }
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
            background-color: #0d1117;
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
            color: #d1d5db;
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }
        ::-webkit-scrollbar-track {
            background: #0d1117;
        }
        ::-webkit-scrollbar-thumb {
            background: #30363d;
            border-radius: 5px;
            border: 2px solid #0d1117;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #484f58;
        }
    </style>
</head>

<body>
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
                $filePath = htmlspecialchars($metadata->file, ENT_QUOTES, 'UTF-8');
                $line = htmlspecialchars($metadata->line, ENT_QUOTES, 'UTF-8');
                $editorLink = $editor === 'vscode' ? "vscode://file/{$filePath}:{$line}" : "phpstorm://open?file={$filePath}&line={$line}";
                ?>
                <a class="file-link" href="<?= $editorLink ?>">
                    <?= basename($filePath) ?>:<?= $line ?>
                </a>
                <?php if ($metadata->caller): ?>
                    <span class="caller-info">Called from <?= htmlspecialchars($metadata->caller, ENT_QUOTES, 'UTF-8') ?></span>
                <?php endif; ?>
            </div>
        </div>
        <!-- Right: Metrics & Actions -->
        <div class="header-right">
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
                <button class="action-btn" title="Search">
                    <span class="material-symbols-outlined" style="font-size: 20px;">search</span>
                </button>
                <button id="copy-output-btn" class="action-btn" title="Copy Output">
                    <span class="material-symbols-outlined" style="font-size: 20px;">content_copy</span>
                </button>
                <button id="collapse-all-btn" class="action-btn" title="Collapse All">
                    <span class="material-symbols-outlined" style="font-size: 20px;">unfold_less</span>
                </button>
            </div>
        </div>
    </header>
    <!-- Main Content Area -->
    <main>
        <div class="container">
            <?= $this->renderRepresentation($representation) ?>
            <div style="height: 5rem;"></div> <!-- Bottom Padding -->
        </div>
    </main>
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const collapseAllBtn = document.getElementById('collapse-all-btn');
            const copyOutputBtn = document.getElementById('copy-output-btn');
            const allDetails = document.querySelectorAll('details');

            let allExpanded = true;

            collapseAllBtn.addEventListener('click', () => {
                allExpanded = !allExpanded;
                allDetails.forEach(detail => {
                    detail.open = allExpanded;
                });
                collapseAllBtn.innerHTML = allExpanded ? '<span class="material-symbols-outlined" style="font-size: 20px;">unfold_less</span>' : '<span class="material-symbols-outlined" style="font-size: 20px;">unfold_more</span>';
                collapseAllBtn.title = allExpanded ? 'Collapse All' : 'Expand All';
            });

            copyOutputBtn.addEventListener('click', () => {
                alert('Copy to clipboard functionality is not implemented yet.');
            });
        });
    </script>
</body>
</html>
