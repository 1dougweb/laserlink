<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class TestFileManager extends Command
{
    protected $signature = 'file-manager:test';
    protected $description = 'Test file manager functionality';

    public function handle()
    {
        $this->info('Testing File Manager...');
        
        // Test storage directory
        $storagePath = storage_path('app/public');
        $this->info("Storage path: {$storagePath}");
        
        // Check if storage directory exists
        if (!is_dir($storagePath)) {
            $this->error('Storage directory does not exist!');
            return;
        }
        
        // List files in storage
        $files = Storage::disk('public')->allFiles();
        $this->info('Files in storage: ' . count($files));
        
        foreach ($files as $file) {
            $this->line("- {$file}");
        }
        
        // Test creating a test directory
        $testDir = 'test-uploads';
        if (!Storage::disk('public')->exists($testDir)) {
            Storage::disk('public')->makeDirectory($testDir);
            $this->info("Created test directory: {$testDir}");
        } else {
            $this->info("Test directory already exists: {$testDir}");
        }
        
        // Test file manager route
        $this->info('Testing file manager route...');
        $response = $this->call('route:list', ['--name' => 'file-manager']);
        
        $this->info('File manager test completed!');
    }
}