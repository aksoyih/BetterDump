<?php
echo '
<style>
    body {
        background-color: #1e1e1e;
        color: #abb2bf;
        font-family: Consolas, Menlo, monospace;
        margin: 0;
        padding: 20px;
    }

    .debug-output {
        background-color: #282c34;
        color: #abb2bf;
        border: 1px solid #61dafb;
        border-radius: 8px;
        padding: 20px;
        margin: 20px 0;
        word-wrap: break-word;
        white-space: pre-wrap;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        font-size: 14px;
        line-height: 1.6;
        position: relative;
    }

    .debug-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .control-button {
        background: #2c313a;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 11px;
        cursor: pointer;
        color: #61dafb;
        border: 1px solid #3a3f4b;
        transition: background-color 0.2s;
    }

    .control-button:hover {
        background: #353b45;
    }

    .debug-output pre {
        margin: 0;
        padding: 0;
    }

    .line-numbers {
        float: left;
        margin-right: 10px;
        text-align: right;
        color: #636d83;
        padding-right: 10px;
        border-right: 1px solid #3a3f4b;
        user-select: none;
    }

    .code-block {
        display: inline-block;
        white-space: pre-wrap;
    }

    .debug-header {
        font-size: 10px;
        color: #61dafb;
        text-align: right;
    }

    .string {
        color: #98c379;
    }

    .number {
        color: #d19a66;
    }

    .boolean {
        color: #c678dd;
    }

    .null {
        color: #e06c75;
    }

    .array {
        color: #61afef;
    }

    .object {
        color: #56b6c2;
    }

    .collapsible {
        cursor: pointer;
        padding: 2px 8px;
        background: #2c313a;
        border-radius: 4px;
        display: inline-block;
        margin: 2px 0;
    }

    .content {
        display: none;
        padding-left: 20px;
    }

    .debug-info {
        background: #2c313a;
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 4px;
        font-size: 12px;
        display: none;
    }
</style>';