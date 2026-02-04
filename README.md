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

### Global Error Handling

BetterDump can act as your application's global error and exception handler, providing a beautiful "Blue Screen of Death" when your code crashes, even if `display_errors` is off in `php.ini`.

```php
use Aksoyih\ErrorHandler;

(new ErrorHandler())->register();

// Now even fatal errors or unhandled exceptions will be rendered by BetterDump
throw new Exception("Oops!");
```

### JSON Output Mode

For debugging API responses, you can switch to JSON mode. This will output the dump as a formatted JSON response with `Content-Type: application/json` headers.

```php
use Aksoyih\BetterDump;

// Enable JSON mode
BetterDump::outputJson(true);

bd($data);
```

### Laravel Integration

BetterDump comes with a zero-config Service Provider for Laravel.

1.  **Auto-Discovery:** The package is automatically discovered.
2.  **Configuration:** It respects your `config('app.editor')` or `config('ignition.editor')` settings.
3.  **Safety:** It automatically disables itself in `production` environments based on `APP_ENV`.

To publish the configuration (Optional):

```bash
php artisan vendor:publish --provider="Aksoyih\Integrations\Laravel\BetterDumpServiceProvider"
```

### Configuration

You can configure the editor to open files in. The default is `phpstorm`.

```php
use Aksoyih\BetterDump;

// Supported editors: 'vscode', 'phpstorm'
BetterDump::setEditor('vscode');
```

You can also set a local root directory for editor links. This is useful when
your runtime paths are relative or inside a container.

```php
use Aksoyih\BetterDump;

BetterDump::setRootDirectory('/path/to/your/project');
```

### Production Safety

BetterDump is disabled by default in production environments (`APP_ENV=production` or `APP_ENV=prod`) to prevent accidental leakage of sensitive data.

To force enable it in production:

```php
use Aksoyih\BetterDump;

BetterDump::allowProduction(true);
```

### Keyboard Shortcuts

| Shortcut | Action |
| :--- | :--- |
| `Cmd+K` / `Ctrl+K` | Toggle Search |
| `/` | Focus Search |
| `Escape` | Close Search / Close Stack Trace |
| `Cmd+Click` | Open File in IDE |

## Features

- **Beautiful UI:** Modern, zero-dependency interface with Light/Dark mode support.
- **Search:** Built-in real-time fuzzy search to filter keys and values.
- **Code Snippet Preview:** Automatically displays the code surrounding an error or dump location with syntax highlighting.
- **Smart Helpers:** `bd()` (Dump & Continue) and `bdd()` (Dump & Die).
- **Stack Trace:** View the full execution history leading to the dump.
- **LLM-Ready Copy:** Copy the dump output as clean JSON with context, perfect for pasting into ChatGPT or Claude.
- **Deep Linking:** Clickable file paths that open directly in your IDE (PhpStorm/VSCode).
- **Image Previews:** Hover over image URLs to see a thumbnail.
- **Recursion Detection:** Safely handles circular references.
- **Performance Metrics:** Shows execution time and memory usage.

## License

MIT License