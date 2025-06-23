// import './bootstrap';

const { createApp, ref, onMounted, watch, computed } = Vue;

createApp({
    setup() {
        const tickets = ref([]);
        const form = ref({ subject: '', body: '' });
        const loading = ref(false);
        const selectedTicket = ref(null);
        const currentPage = ref(1);
        const statusFilter = ref('all');
        const categoryFilter = ref('all');
        const searchTerm = ref('');
        const pagination = ref({ current: 1, last: 1 });

        const changePage = (page) => {
            if (page >= 1 && page <= pagination.value.last) {
                currentPage.value = page;
                loadTickets();
            }
        };

        const paginationRange = computed(() => {
            const total = pagination.value.last;
            const current = pagination.value.current;
            const delta = 2; // how many pages to show before/after current
            const pages = [];

            for (let i = Math.max(1, current - delta); i <= Math.min(total, current + delta); i++) {
                pages.push(i);
            }

            return pages;
        });

        const getCsrfToken = () =>
            document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

        const loadTickets = async () => {

            loading.value = true;
            const params = new URLSearchParams({
                page: currentPage.value,
                status: statusFilter.value || '',
                category: categoryFilter.value || '',
                search: searchTerm.value || ''
            });

            const res = await fetch(`/api/tickets?${params.toString()}`);
            const data = await res.json();
            tickets.value = data.data.map(ticket => ({
                ...ticket,
                category: ticket.category ?? '',
            }));
            pagination.value = {
                current: data.current_page,
                last: data.last_page
            };
            loading.value = false;
        };

        const submitTicket = async () => {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const res = await fetch('/api/tickets', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken()
                },
                body: JSON.stringify(form.value)
            });
            if (res.ok) {
                form.value.subject = '';
                form.value.body = '';
                await loadTickets();
            }
        };

        const classify = async (ticket) => {

            ticket.loading = true;
            await fetch(`/api/tickets/${ticket.id}/classify`, { 
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': getCsrfToken()
                } 
            });
            setTimeout(loadTickets, 1500); // Poll for update after delay
        };

        const updateTicket = async (ticket) => {
            await fetch(`/api/tickets/${ticket.id}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken()
                },
                body: JSON.stringify({
                    status: ticket.status,
                    category: ticket.category,
                    note: ticket.note
                })
            });

            // Refresh the main list if user is in detail view
            if (selectedTicket.value) {
                await loadTickets();
            }
        };

        const loadTicketDetail = async (id) => {
            const res = await fetch(`/api/tickets/${id}`);
            const data = await res.json();
            selectedTicket.value = {
                ...data,
                category: data.category ?? '',
            };
        };
    
        watch([statusFilter, categoryFilter, searchTerm], loadTickets);

        onMounted(loadTickets);

        return {            
            tickets, 
            form, 
            loading,             
            selectedTicket,             
            statusFilter, 
            categoryFilter, 
            searchTerm,            
            pagination, 
            paginationRange,
            submitTicket, 
            classify, 
            updateTicket, 
            loadTicketDetail, 
            changePage,
        };
    }
}).mount('#app');

