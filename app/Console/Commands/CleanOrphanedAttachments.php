<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CleanOrphanedAttachments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clean-orphaned-attachments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    // app/Console/Commands/CleanOrphanedAttachments.php
public function handle()
{
    $storage = Storage::disk('public');
    $files = $storage->files('attachments');
    
    foreach ($files as $file) {
        $message = Message::where('attachment_path', $file)->exists();
        
        if (!$message) {
            $storage->delete($file);
            $this->info("Deleted orphaned file: {$file}");
        }
    }
}
}
