// importar o arquivo bootstrap
import './bootstrap';
// importar o js do leiaute
import './scripts_sbadmin';

// importar tomselect combo option
import TomSelect from 'tom-select';
import 'tom-select/dist/css/tom-select.bootstrap5.css';

window.TomSelect = TomSelect;

import Chart from 'chart.js/auto';
window.Chart = Chart;

// carregar select option combo componente tomselect
document.addEventListener('DOMContentLoaded', () => {

    const categoria = document.querySelector('#categoria');

    if (categoria) {
        new TomSelect(categoria, {
            create: false,
            placeholder: 'Digite para pesquisar...',
            allowEmptyOption: true
        });
    }

});