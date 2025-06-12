// import './bootstrap';

const { createApp, ref, onMounted } = Vue;

createApp({
    setup() {
        const tickets = ref([]);
        const form = ref({ subject: '', body: '' });
        const loading = ref(false);

        const getCsrfToken = () =>
            document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

        const loadTickets = async () => {
            loading.value = true;
            const res = await fetch('/api/tickets');
            tickets.value = await res.json();
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

        onMounted(loadTickets);

        return { tickets, form, loading, submitTicket, classify };
    }
}).mount('#app');

