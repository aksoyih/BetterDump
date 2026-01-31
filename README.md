# BetterDump V2

A professional-grade debugging library for PHP that provides a high-fidelity visual representation of variables, tailored for modern web development environments.

![Screenshot](https://i.imgur.com/your-screenshot.png)

## Installation

```bash
composer require aksoyih/better-dump
```

## Usage

```php
// Simple usage
bd($variable);

// Multiple variables
bd($var1, $var2, $var3);

// Labeled dump
bd($user, 'User Object');
```

### JSON Output Mode

For debugging API responses, you can switch to JSON mode. This will output the dump as a formatted JSON response with `Content-Type: application/json` headers.

```php
use Aksoyih\BetterDump;

// Enable JSON mode
BetterDump::outputJson(true);

bd($data);
```

### Configuration

You can configure the editor to open files in. The default is `phpstorm`.

```php
use Aksoyih\BetterDump;

// Supported editors: 'vscode', 'phpstorm'
BetterDump::setEditor('vscode');
```

## Features

- **Beautiful UI:** Modern, zero-dependency interface with syntax highlighting.
- **Search:** Built-in real-time search to filter keys and values.
- **Stack Trace:** View the full execution history leading to the dump.
- **LLM-Ready Copy:** Copy the dump output as clean JSON with context, perfect for pasting into ChatGPT or Claude.
- **Deep Linking:** Clickable file paths that open directly in your IDE (PhpStorm/VSCode).
- **Image Previews:** Hover over image URLs to see a thumbnail.
- **Recursion Detection:** Safely handles circular references.
- **Performance Metrics:** Shows execution time and memory usage.

## License

MIT License