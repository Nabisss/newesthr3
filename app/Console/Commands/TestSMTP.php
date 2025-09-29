<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestSMTP extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Run with: php artisan mail:test
     */
    protected $signature = 'mail:test';

    /**
     * The console command description.
     */
    protected $description = 'Send a test email to check SMTP configuration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            Mail::raw('SMTP test email content.', function ($message) {
                $message->to('03.markjohn.30@gmail.com')
                        ->subject('SMTP Test from Laravel');
            });

            $this->info('✅ Test email sent successfully!');
        } catch (\Exception $e) {
            $this->error('❌ Failed to send test email: ' . $e->getMessage());
        }
    }
}
