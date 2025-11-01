import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Ensure CSRF token header is sent for POST (e.g., /broadcasting/auth)
const csrf = document.querySelector('meta[name="csrf-token"]');
if (csrf) {
	window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrf.getAttribute('content');
}
