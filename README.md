# BetterDump

A professional-grade debugging library for PHP that provides a high-fidelity visual representation of variables, tailored for modern web development environments.

![Screenshot](https://halukaksoy.dev/content/images/size/w1000/2026/02/Screenshot-2026-02-01-at-13.08.53.png)

## Installation

```bash
composer require aksoyih/better-dump
```

## Usage

```php
// Dump and continue execution
bd($variable);

// Dump and die (stop execution)
bdd($variable);

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

### Production Safety

BetterDump is disabled by default in production environments (`APP_ENV=production` or `APP_ENV=prod`) to prevent accidental leakage of sensitive data.

To force enable it in production:

```php
use Aksoyih\BetterDump;

BetterDump::allowProduction(true);
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