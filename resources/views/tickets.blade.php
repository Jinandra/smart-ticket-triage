<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Smart Ticket Triage</title>
    <link rel="stylesheet" href="{{ asset('build/assets/app-B2MWpwNs.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div id="app">
        <div class="ticket-form" v-if="!selectedTicket">
            <h2>Submit a Ticket</h2>
            <form @submit.prevent="submitTicket">
                <input v-model="form.subject" placeholder="Subject" required />
                <textarea v-model="form.body" placeholder="Body" required></textarea>
                <button type="submit" class="btn">Submit</button>
            </form>
            <a href="{{route('stats')}}"><button type="button" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-r mt-2">Stats</button></a>
        </div>

        <div class="ticket-filters ticket-form" v-if="!selectedTicket">
            <h3>Filter Tickets</h3>
            <div class="ticket-form">
                <input v-model="searchTerm" placeholder="Search tickets..." @change="loadTickets" />
            </div>
            <label>Status:
                <select v-model="statusFilter" class="w-48 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    <option value="all">All</option>
                    <option value="open">Open</option>
                    <option value="pending">Pending</option>
                    <option value="closed">Closed</option>
                </select>
            </label>

            <label class="ml-4">Category:
                <select v-model="categoryFilter" class="w-48 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    <option value="all">All</option>
                    <option>Billing</option>
                    <option>Bug</option>
                    <option>Feature Request</option>
                    <option>General</option>
                </select>
            </label>
        </div>

        <!-- Ticket List -->
        <div class="ticket-list ticket-form" v-if="!selectedTicket">
            <h2>Tickets</h2>
            <div v-if="loading" class="ticket-list__loading">Loading...</div>
            <div v-else>
                <div 
                    v-for="ticket in tickets" 
                    :key="ticket.id" 
                    class="ticket-list__item"
                    :class="{ 'ticket-list__item--classified': ticket.category }"
                >
                    <div class="ticket-list__subject cursor-pointer" @click="selectedTicket = ticket">
                        <p><strong>Subject:</strong> @{{ ticket.subject }}</p>
                    </div>
                    <div class="ticket-list__body">
                        <p><strong>Body:</strong> @{{ ticket.body }}</p>
                    </div>
                    <div class="ticket-list__meta">
                        <div>
                            <label>Category:</label>
                            <select v-model="ticket.category" class="ml-3 w-48 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                <option :value="''">-- select --</option>
                                <option>Billing</option>
                                <option>Bug</option>
                                <option>Feature Request</option>
                                <option>General</option>
                            </select>
                        </div>
                        <div>
                            <label>Status:</label>
                            <select v-model="ticket.status" class="ml-3 w-48 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                <option value="open">Open</option>
                                <option value="pending">Pending</option>
                                <option value="closed">Closed</option>
                            </select>
                        </div>
                        <div>
                            <label>Note:</label>
                            <textarea v-model="ticket.note" placeholder="Internal note"></textarea>
                        </div>
                        <span v-if="ticket.confidence">
                            <strong>@{{ ticket.category }}</strong> (@{{ (ticket.confidence * 100).toFixed(1) }}%)
                        </span>
                        <button class="btn" @click="classify(ticket)" v-else>Classify</button>
                        <button class="btn ml-3" @click="updateTicket(ticket)">Save</button>                        
                        <button class="btn ml-3" @click="loadTicketDetail(ticket.id)">View</button>
                    </div>
                </div>
                <div class="pagination flex justify-center mt-2">
                    <button 
                        class="mx-[3px]" 
                        :disabled="pagination.current === 1" 
                        @click="changePage(pagination.current - 1)"
                    >
                        Previous
                    </button>

                    <button 
                        v-for="page in paginationRange" 
                        :key="page" 
                        :class="['mx-[3px]', { 'active': page === pagination.current }]"
                        @click="changePage(page)"
                    >
                        @{{ page }}
                    </button>

                    <button 
                        class="mx-[3px]" 
                        :disabled="pagination.current === pagination.last" 
                        @click="changePage(pagination.current + 1)"
                    >
                        Next
                    </button>
                </div>
            </div>
        </div>

        <!-- Ticket Detail View -->
        <div v-if="selectedTicket" class="ticket-detail ticket-form">
            <h2>Ticket Details</h2>
            <p><strong>Subject:</strong> @{{ selectedTicket.subject }}</p>
            <p><strong>Body:</strong> @{{ selectedTicket.body }}</p>

            <div>
                <label>Category:</label>
                <select v-model="selectedTicket.category" class="ml-3 w-48 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    <option :value="''">-- select --</option>
                    <option>Billing</option>
                    <option>Bug</option>
                    <option>Feature Request</option>
                    <option>General</option>
                </select>
            </div>

            <div>
                <label>Status:</label>
                <select v-model="selectedTicket.status" class="ml-3 w-48 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    <option value="open">Open</option>
                    <option value="pending">Pending</option>
                    <option value="closed">Closed</option>
                </select>
            </div>

            <div>
                <label>Note:</label><br>
                <textarea v-model="selectedTicket.note"></textarea>
            </div>

            <div v-if="selectedTicket.explanation">
                <p><strong>Explanation:</strong> @{{ selectedTicket.explanation }}</p>
                <p><strong>Confidence:</strong> @{{ (selectedTicket.confidence * 100).toFixed(1) }}%</p>
            </div>

            <span v-if="selectedTicket.confidence">
                <strong>@{{ selectedTicket.category }}</strong> (@{{ (selectedTicket.confidence * 100).toFixed(1) }}%)
            </span>
            <button class="btn" @click="classify(selectedTicket)" v-else>Run Classification</button>

            <button class="btn ml-3" @click="updateTicket(selectedTicket)">Save</button>
            <button class="btn ml-3" @click="selectedTicket = null">Back</button>
        </div>

    </div>

    <script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
    <script src="{{ asset('/build/assets/app-Df3sLLiR.js') }}"></script>
</body>
</html>
