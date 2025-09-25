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
import Gantt from '../../node_modules/frappe-gantt/dist/frappe-gantt.es.js';
import '../../node_modules/frappe-gantt/dist/frappe-gantt.css';

// Optional: expose globally for inline scripts in Blade
window.Gantt = Gantt;
