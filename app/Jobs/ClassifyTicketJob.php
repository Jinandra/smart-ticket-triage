<?php

namespace App\Jobs;

use App\Models\Ticket;
use App\Services\TicketClassifier;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;

class ClassifyTicketJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public Ticket $ticket)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(TicketClassifier $classifier): void {
        $result = $classifier->classify($this->ticket->subject, $this->ticket->body);

        if ($this->ticket->category_manual) {
            // Preserve category, only update explanation & confidence
            $this->ticket->update([
                'explanation' => $result['explanation'] ?? null,
                'confidence' => $result['confidence'] ?? null,
            ]);
        } else {
            $this->ticket->update($result);
        }
    }
}
