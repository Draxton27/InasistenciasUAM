import './bootstrap';

import Alpine from 'alpinejs';
import '../css/app.css';
import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import './confirm';

window.Alpine = Alpine;
window.Swal = Swal;

Alpine.start();
