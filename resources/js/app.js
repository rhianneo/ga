/**
 * Laravel and dependencies
 */
import './bootstrap';

/**
 * Alpine.js for frontend interactivity
 */
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

/**
 * Frappe Gantt
 * Import via relative path to node_modules
 */
import Gantt from 'frappe-gantt/dist/frappe-gantt.es.js';
import 'frappe-gantt/dist/frappe-gantt.css';

// Optional: expose globally for inline scripts in Blade
window.Gantt = Gantt;

/**
 * Axios for making API requests
 */
import axios from 'axios';
window.axios = axios;

/**
 * Set default Axios headers
 */
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Additional JS functionality can go here, if needed
