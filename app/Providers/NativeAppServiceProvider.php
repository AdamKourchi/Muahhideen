<?php
namespace App\Providers;

use Native\Laravel\Facades\Window;
use Native\Laravel\Contracts\ProvidesPhpIni;
use Symfony\Component\Process\Process;

class NativeAppServiceProvider implements ProvidesPhpIni
{
    protected $serverProcess;

    /**
     * Executed once the native application has been booted.
     * Use this method to open windows, register global shortcuts, etc.
     */
    public function boot(): void
    {
        // Start Laravel server in background
        $this->startLaravelServer();
        
        // Open the main window
        Window::open();
    }

    /**
     * Return an array of php.ini directives to be set.
     */
    public function phpIni(): array
    {
        return [
        ];
    }

    /**
     * Start the Laravel development server
     */
    protected function startLaravelServer(): void
    {
        $this->serverProcess = new Process(['php', 'artisan', 'serve','--port=8000']);
        $this->serverProcess->start();
        
        // Wait a bit to make sure the server has time to start
        sleep(1);
    }

    /**
     * Handle shutdown to stop the server
     */
    public function __destruct()
    {
        // Ensure we clean up the server process
        if ($this->serverProcess && $this->serverProcess->isRunning()) {
            $this->serverProcess->stop();
        }
    }
}