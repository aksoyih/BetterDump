# BetterDump V2

A professional-grade debugging library for PHP that provides a high-fidelity visual representation of variables, tailored for modern web development environments.

![Screenshot](https://i.imgur.com/your-screenshot.png) <!-- TODO: Replace with actual screenshot -->

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

### Configuration

You can configure the editor to open files in. The default is `vscode`.

```php
use Aksoyih\BetterDump;

// Supported editors: 'vscode', 'phpstorm'
BetterDump::setEditor('phpstorm');
```

## Features

- Modern, beautiful syntax highlighting
- Collapsible arrays and objects with depth limit
- Recursion detection
- File path linking to your editor (VSCode & PhpStorm)
- Execution time and memory usage metrics
- "Called from" trace to quickly identify the source
- Zero-dependency, inlined UI

## License

MIT License