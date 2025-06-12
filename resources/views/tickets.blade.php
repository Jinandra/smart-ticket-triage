<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Smart Ticket Triage</title>
    <link rel="stylesheet" href="{{ asset('build/assets/app-BQLXBd2v.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div id="app">
        <div class="ticket-form">
            <h2>Submit a Ticket</h2>
            <form @submit.prevent="submitTicket">
                <input v-model="form.subject" placeholder="Subject" required />
                <textarea v-model="form.body" placeholder="Body" required></textarea>
                <button type="submit" class="btn">Submit</button>
            </form>
        </div>

        <div class="ticket-list ticket-form">
            <h2>Tickets</h2>
            <div v-if="loading" class="ticket-list__loading">Loading...</div>
            <div v-else>
                <div 
                    v-for="ticket in tickets" 
                    :key="ticket.id" 
                    class="ticket-list__item"
                    :class="{ 'ticket-list__item--classified': ticket.category }"
                >
                    <div class="ticket-list__subject">@{{ ticket.subject }}</div>
                    <div class="ticket-list__body">@{{ ticket.body }}</div>
                    <div class="ticket-list__meta">
                        <span v-if="ticket.category">@{{ ticket.category }} (@{{ (ticket.confidence * 100).toFixed(1) }}%)</span>
                        <button class="btn" @click="classify(ticket)" v-else>Classify</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
    <script src="{{ asset('/build/assets/app-YyRTs_FV.js') }}"></script>
</body>
</html>
