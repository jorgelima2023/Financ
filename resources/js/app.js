// importar o arquivo bootstrap
import './bootstrap';
// importar o js do leiaute
import './scripts_sbadmin';

// importar tomselect combo option
import TomSelect from 'tom-select';
import 'tom-select/dist/css/tom-select.bootstrap5.css';

//window.TomSelect = TomSelect;

import Chart from 'chart.js/auto';
window.Chart = Chart;

// carregar select option combo componente tomselect
// dropdownParent: 'body'  
// Para usar o Tom Select dentro de um modal Bootstrap 5, 
//   o principal ajuste é informar ao Tom Select que o dropdown deve ser anexado ao próprio modal. 
// Isso evita o problema de a lista aparecer atrás do modal.
//  '#categoria' e o id da categoria do select option 

document.addEventListener('DOMContentLoaded', () => {

    const categoria = document.querySelector('#categoria');

    if (categoria) {
        new TomSelect(categoria, {
            create: false,
            placeholder: 'Digite para pesquisar...',
            allowEmptyOption: true,
            dropdownParent: 'body' 
        });
    }

    const modal = document.querySelector('#editModal');

        modal.addEventListener('show.bs.modal', function (event) {

        const button = event.relatedTarget;

        const categoriaId = button.getAttribute('data-categoria_id');

        const select = document.querySelector('#editcategoria_id');

        const tomSelect = select.tomselect;

        if (tomSelect) {
            tomSelect.setValue(categoriaId);
        }

    });
});