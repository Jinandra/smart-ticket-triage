<?php

namespace App\Http\Controllers;

use App\Jobs\ClassifyTicketJob;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request) {
        return Ticket::latest()->get();
    }

    public function store(Request $request) {
        $ticket = Ticket::create($request->only(['subject', 'body']));
        return response()->json($ticket, 201);
    }

    public function classify(Ticket $ticket) {

        dispatch(new ClassifyTicketJob($ticket));
        return response()->json(['message' => 'Classification queued.']);
    }
}
