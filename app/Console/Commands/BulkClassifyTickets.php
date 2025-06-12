<?php

namespace App\Console\Commands;

use App\Jobs\ClassifyTicketJob;
use App\Models\Ticket;
use Illuminate\Console\Command;

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
            dispatch(new ClassifyTicketJob($ticket));
            $this->line("Queued classification for ticket #{$ticket->id}");
        }

        $this->info("Done. {$tickets->count()} ticket(s) queued for classification.");
    }
}
