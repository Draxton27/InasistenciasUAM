import './bootstrap';

import Alpine from 'alpinejs';
import '../css/app.css';
import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import './confirm';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Alpine = Alpine;
window.Swal = Swal;

Alpine.start();

// Real-time notifications via Laravel Echo (Pusher or compatible)
if (typeof window !== 'undefined') {
	window.Pusher = Pusher;
	const key = import.meta.env.VITE_PUSHER_APP_KEY;
	const cluster = import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1';
	const host = import.meta.env.VITE_PUSHER_HOST; // if set => self-hosted (Soketi/websockets)
	const port = import.meta.env.VITE_PUSHER_PORT ?? (location.protocol === 'https:' ? 443 : 80);
	const scheme = import.meta.env.VITE_PUSHER_SCHEME ?? (location.protocol === 'https:' ? 'https' : 'http');

	const metaUser = document.querySelector('meta[name="user-id"]');
	const userId = (window.App && window.App.userId) || (metaUser ? metaUser.getAttribute('content') : null);

	if (key && userId) {
		const isSelfHosted = !!host;
		const echoOptions = isSelfHosted
			? {
				broadcaster: 'pusher',
				key,
				cluster,
				wsHost: host,
				wsPort: port,
				wssPort: port,
				forceTLS: scheme === 'https',
				enabledTransports: ['ws', 'wss'],
			}
			: {
				broadcaster: 'pusher',
				key,
				cluster,
				forceTLS: true,
			};

		// Initialize Echo
		window.Echo = new Echo(echoOptions);

		// Optional: debug connection
		try {
			const pusher = window.Echo.connector.pusher;
			pusher.connection.bind('connected', () => console.debug('Echo connected'));
			pusher.connection.bind('error', (err) => console.warn('Echo error', err));
		} catch {}

		// Listen to broadcast notifications
		const channel = window.Echo.private(`App.Models.User.${userId}`);
		channel.subscribed(() => console.debug('Subscribed to user channel'))
			.error(err => console.warn('Channel error', err))
			.notification((notification) => {
				// Normalize payload: Laravel broadcasts notifications with a `data` envelope
				const payload = notification && notification.data ? notification.data : notification;
				try {
					// Show toast
					if (window.Swal) {
						window.Swal.fire({
							toast: true,
							position: 'top-end',
							icon: payload.status === 'aceptada' ? 'success' : (payload.status === 'rechazada' ? 'error' : 'info'),
							title: payload.title || 'Notificación',
							text: payload.body || '',
							showConfirmButton: false,
							timer: 4000,
						});
					}
					// Increment badge if exists
					const badge = document.querySelector('[data-notifications-badge]');
					if (badge) {
						const val = parseInt(badge.textContent || '0', 10) || 0;
						badge.textContent = String(val + 1);
						badge.classList.remove('hidden');
					}
				} catch (e) {
					console.warn('Notification handling error:', e);
				}
			});
	}
}
