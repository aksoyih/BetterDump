<?php

namespace Aksoyih\Integrations\Laravel;

use Aksoyih\BetterDump;
use Illuminate\Support\ServiceProvider;

class BetterDumpServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->configureEditor();
    }

    private function configureEditor(): void
    {
        // specific configuration for BetterDump
        $editor = config('better-dump.editor');

        // Fallback to Ignition configuration if available
        if (!$editor) {
            $editor = config('ignition.editor');
        }
        
        // Fallback to generic app editor config
        if (!$editor) {
            $editor = config('app.editor');
        }

        if ($editor) {
            BetterDump::setEditor($editor);
        }
    }

    public function register(): void
    {
        // Future: Register config publishing
    }
}
