<?php

namespace Database\Seeders;

use App\Models\Ticket;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tickets = [
            ['Issue with last invoice', 'I was charged twice for last month\'s subscription. Can someone look into this?'],
            ['Contact number', 'Can you provide a phone number to talk to someone?'],
            ['File upload fails', 'Trying to upload a document and it keeps failing at 90%.'],
            ['Do you offer discounts?', 'Are there any offers for non-profits or startups?'],
            ['Credit card declined', 'My payment didn’t go through even though my card is active.'],
            ['Credit card declined', 'My payment didn’t go through even though my card is active.'],
            ['Need invoice for tax', 'Can I get a PDF invoice for my recent payment for accounting purposes?'],
            ['Incorrect billing cycle', 'My account is billed weekly instead of monthly. I’d like this corrected.'],
            ['Multi-user access', 'We need the ability to share accounts with our team members.'],
            ['Need invoice for tax', 'Can I get a PDF invoice for my recent payment for accounting purposes?'],
            ['Refund request', 'I canceled my plan last week, but still got charged. Please issue a refund.'],
            ['Custom notification settings', 'Please allow us to control which notifications we get.'],
            ['Settings not saving', 'Changes to my preferences aren’t being saved. It resets every time I refresh.'],
            ['Office hours?', 'What are your customer service hours?'],
            ['Do you offer discounts?', 'Are there any offers for non-profits or startups?'],
            ['Custom notification settings', 'Please allow us to control which notifications we get.'],
            ['How do I change my email?', 'I want to update the email associated with my account.'],
            ['Can’t reset password', 'Password reset link takes me to a 404 page.'],
            ['Search not working', 'Searching any keyword gives no results, even when data exists.'],
            ['How do I change my email?', 'I want to update the email associated with my account.'],
            ['Credit card declined', 'My payment didn’t go through even though my card is active.'],
            ['Multi-user access', 'We need the ability to share accounts with our team members.'],
            ['How do I change my email?', 'I want to update the email associated with my account.'],
            ['Office hours?', 'What are your customer service hours?'],
            ['Export to Excel', 'Can you add a way to export data to Excel or CSV format?'],
            ['Refund request', 'I canceled my plan last week, but still got charged. Please issue a refund.'],
            ['Settings not saving', 'Changes to my preferences aren’t being saved. It resets every time I refresh.'],
            ['Incorrect billing cycle', 'My account is billed weekly instead of monthly. I’d like this corrected.'],
            ['Credit card declined', 'My payment didn’t go through even though my card is active.'],
            ['Search not working', 'Searching any keyword gives no results, even when data exists.'],
        ];

        foreach ($tickets as $ticket) {
            Ticket::create([
                'subject' => $ticket[0],
                'body' => $ticket[1],
            ]);
        }
    }
}
