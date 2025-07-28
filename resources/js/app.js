import './bootstrap';
import * as bootstrap from 'bootstrap';

import Litepicker from 'litepicker';

document.addEventListener('DOMContentLoaded', () => {
    const picker = new Litepicker({
    element: document.getElementById('date-range'),
        singleMode: false, // To select both start and end date
        format: 'YYYY-MM-DD',
        autoApply: true,
        setup: (picker) => {
            picker.on('selected', () => {
                // Find closest parent <form> and submit it
                picker.element.closest('form').submit();
            });
        }
    });
});