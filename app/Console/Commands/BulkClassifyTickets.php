<?php

namespace App\Console\Commands;

use App\Jobs\ClassifyTicketJob;
use App\Models\Ticket;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\RateLimiter;

class BulkClassifyTickets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tickets:bulk-classify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Queue AI classification jobs for all unclassified tickets';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tickets = Ticket::whereNull('category')->get();

        if ($tickets->isEmpty()) {
            $this->info('No unclassified tickets found.');
            return;
        }

        foreach ($tickets as $ticket) {
            $key = 'classify-ticket-' . $ticket->id;

            if (RateLimiter::remaining($key, 1)) {
                RateLimiter::hit($key, 60); // 1 per 60 seconds per ticket ID
                ClassifyTicketJob::dispatch($ticket);
                $this->info("Queued classification for ticket ID {$ticket->id}");
            } else {
                $this->warn("Rate limit hit for ticket ID {$ticket->id}, skipping...");
            }
        }

        $this->info("Done. {$tickets->count()} ticket(s) queued for classification.");
    }
}
