<?php

namespace Aksoyih;

use Throwable;
use ErrorException;

class ErrorHandler
{
    private bool $isRegistered = false;

    /**
     * Register the error and exception handlers.
     *
     * @return static
     */
    public function register(): static
    {
        if ($this->isRegistered) {
            return $this;
        }

        $this->registerExceptionHandler();
        $this->registerErrorHandler();
        $this->registerShutdownHandler();

        $this->isRegistered = true;

        return $this;
    }

    /**
     * Add a handler (Not implemented, provided for API compatibility).
     * BetterDump is currently the sole handler.
     *
     * @param mixed $handler
     * @return static
     */
    public function pushHandler(mixed $handler): static
    {
        // Future: Support handler stack
        return $this;
    }

    private function registerExceptionHandler(): void
    {
        set_exception_handler(function (Throwable $e) {
            $this->handleException($e);
        });
    }

    private function registerErrorHandler(): void
    {
        set_error_handler(function ($level, $message, $file = null, $line = null) {
            if (error_reporting() & $level) {
                throw new ErrorException($message, 0, $level, $file, $line);
            }
            return false;
        });
    }

    private function registerShutdownHandler(): void
    {
        register_shutdown_function(function () {
            $error = error_get_last();
            if ($error && $this->isFatalError($error['type'])) {
                $exception = new ErrorException(
                    $error['message'],
                    0,
                    $error['type'],
                    $error['file'],
                    $error['line']
                );
                $this->handleException($exception);
            }
        });
    }

    /**
     * Handle an exception and display it using BetterDump.
     */
    public function handleException(Throwable $exception): void
    {
        // Use BetterDump to render the exception.
        // cleanOutput=true clears the buffer (Blue Screen of Death style)
        BetterDump::dump($exception, 'Uncaught Exception', true);
    }

    private function isFatalError(int $type): bool
    {
        return in_array($type, [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE]);
    }
}
