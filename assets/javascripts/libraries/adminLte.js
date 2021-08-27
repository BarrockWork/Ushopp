/**
 * AdminLTE template
 * https://adminlte.io/themes/dev/AdminLTE/index.html
 */

// Import admin lte css
import '../../styles/css/libraries/adminlte.css';

// Import jQuery from adminlte module
const $ = require('admin-lte/plugins/jquery/jquery.min');

// Set jQuery globally
global.$ = global.jQuery = $;

// Import bootstrap bundle from adminlte module
require('admin-lte/plugins/bootstrap/js/bootstrap.bundle.min');

// Import datatables plugins
require('admin-lte/plugins/datatables/jquery.dataTables.min');
require('admin-lte/plugins/datatables-bs4/js/dataTables.bootstrap4.min');
require('admin-lte/plugins/datatables-responsive/js/dataTables.responsive.min');
require('admin-lte/plugins/datatables-responsive/js/responsive.bootstrap4.min');
require('admin-lte/plugins/datatables-buttons/js/dataTables.buttons.min');
require('admin-lte/plugins/datatables-buttons/js/buttons.bootstrap4.min');
require('admin-lte/plugins/jszip/jszip.min');
require('admin-lte/plugins/pdfmake/pdfmake.min');
require('admin-lte/plugins/pdfmake/vfs_fonts');
require('admin-lte/plugins/datatables-buttons/js/buttons.html5.min');
require('admin-lte/plugins/datatables-buttons/js/buttons.print.min');
require('admin-lte/plugins/datatables-buttons/js/buttons.colVis.min');

// chartjs plugin
require('admin-lte/plugins/chart.js/Chart.min');

// Import adminlte module
require('admin-lte');

