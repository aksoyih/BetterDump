<?php

namespace Aksoyih\Tests;

use Aksoyih\BetterDump;
use PHPUnit\Framework\TestCase;

class BetterDumpTest extends TestCase
{
    protected function tearDown(): void
    {
        // Reset state
        BetterDump::allowProduction(false);
        BetterDump::outputJson(false);
        putenv('APP_ENV=local');
        unset($_ENV['APP_ENV']);
    }

    public function testHelpersExist()
    {
        $this->assertTrue(function_exists('bd'));
        $this->assertTrue(function_exists('bdd'));
    }

    public function testSetEditor()
    {
        // We can't easily assert the private property, but we can verify no error is thrown
        BetterDump::setEditor('vscode');
        $this->assertTrue(true);
    }

    public function testDumpProductionGuard()
    {
        // Mock Production
        putenv('APP_ENV=production');
        
        // Capture Output
        ob_start();
        BetterDump::dump(['test']);
        $output = ob_get_clean();

        // Should be empty because we are in production and allowProduction is false (default)
        $this->assertEmpty($output, 'Dump should be silent in production');
    }

    public function testDumpAllowedInProduction()
    {
        // Mock Production
        putenv('APP_ENV=production');
        BetterDump::allowProduction(true);
        
        // Capture Output
        ob_start();
        BetterDump::dump(['test'], 'Label', true);
        $output = ob_get_clean();

        // Should NOT be empty
        $this->assertNotEmpty($output, 'Dump should output when explicitly allowed in production');
        $this->assertStringContainsString('Label', $output);
    }

    public function testDumpInLocal()
    {
        putenv('APP_ENV=local');
        
        ob_start();
        BetterDump::dump(['test']);
        $output = ob_get_clean();

        $this->assertNotEmpty($output);
    }
}
