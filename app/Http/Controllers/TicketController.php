<?php

namespace App\Http\Controllers;

use App\Jobs\ClassifyTicketJob;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    public function index(Request $request) {

        $query = Ticket::query();

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('subject', 'like', "%{$request->search}%")
                ->orWhere('body', 'like', "%{$request->search}%");
            });
        }

        return $query->latest()
        ->paginate(10)
        ->appends($request->query());
    }

    public function store(Request $request) {
        $ticket = Ticket::create($request->only(['subject', 'body']));
        return response()->json($ticket, 201);
    }

    public function classify(Ticket $ticket) {

        dispatch(new ClassifyTicketJob($ticket));
        return response()->json(['message' => 'Classification queued.']);
    }

    public function show(Ticket $ticket) {
        return response()->json($ticket);
    }

    public function update(Request $request, Ticket $ticket) {

        $data = $request->validate([
            'status' => 'nullable|in:open,closed,pending',
            'category' => 'nullable|string',
            'note' => 'nullable|string',
        ]);

        // If category changed manually, mark as manually set
        if (isset($data['category']) && $data['category'] !== $ticket->category) {
            $ticket->category_manual = true;
        }
        
        $ticket->update($data);

        return response()->json(['message' => 'Ticket updated']);
    }

    public function stats(){
        $statusCounts = Ticket::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $categoryCounts = Ticket::select('category', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->pluck('total', 'category');

        return view('stats', [
            'statusCounts' => $statusCounts,
            'categoryCounts' => $categoryCounts,
        ]);
    }
}
