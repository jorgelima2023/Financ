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

/*
 carregar select option combo componente tomselect
 dropdownParent: 'body'  
 Para usar o Tom Select dentro de um modal Bootstrap 5, 
   o principal ajuste é informar ao Tom Select que o dropdown deve ser anexado ao próprio modal. 
 Isso evita o problema de a lista aparecer atrás do modal.
  '#categoria' e o id da categoria do select option 

  assim voce tem:
#categoria → pesquisa no formulário normal
#editcategoria_id → pesquisa dentro do modal
data-categoria_id → define automaticamente a categoria do movimento ao editar
apenas um DOMContentLoaded

ajustes: 
importado tomselect npm
adicionado tomselect app.js
adicionado ao css: styles_admin.css    
    .ts-dropdown {  
        z-index: 9999 !important;  
    }

*/


document.addEventListener('DOMContentLoaded', () => {

    //console.log('APP.JS carregado');
    
    // =====================================================
    // SELECT NORMAL
    // =====================================================

    const categoria = document.querySelector('#categoria');

    if (categoria) {

        new TomSelect(categoria, {
            create: false,
            placeholder: 'Digite para pesquisar...',
            allowEmptyOption: true,
            dropdownParent: 'body'
        });
        //console.log('TomSelect #categoria criado');
    }


    // =====================================================
    // MODAL DE EDIÇÃO
    // =====================================================

    const modal = document.querySelector('#editModal');

    if (modal) {

        modal.addEventListener('shown.bs.modal', function (event) {

            const button = event.relatedTarget;

            if (!button) {
                return;
            }

            // Pega a categoria do botão
            const categoriaId =
                button.getAttribute('data-categoria_id');

            //console.log('Categoria selecionada:', categoriaId);


            // Pega o select
            const select =
                document.querySelector('#editcategoria_id');

            if (!select) {
                //console.log('Select editcategoria_id não encontrado');
                return;
            }


            // Se ainda não foi transformado em Tom Select,
            // cria agora
            if (!select.tomselect) {

                new TomSelect(select, {

                    create: false,

                    placeholder: 'Digite para pesquisar...',

                    allowEmptyOption: true,

                    dropdownParent: 'body'

                });

            }


            // Pega a instância do Tom Select
            const tomSelect =
                select.tomselect;


            // Seleciona a categoria do movimento
            if (categoriaId) {

                tomSelect.setValue(categoriaId);

            } else {

                tomSelect.clear();

            }

        });

    }

});

/*

document.addEventListener('DOMContentLoaded', () => {

    //console.log('APP.JS carregado');

    // Select normal
    const categoria = document.querySelector('#categoria');

    if (categoria) {

        new TomSelect(categoria, {
            create: false,
            placeholder: 'Digite para pesquisar...',
            allowEmptyOption: true,
            dropdownParent: 'body'
        });

        //console.log('TomSelect #categoria criado');
    }


    // Select do modal
    const editCategoria =
        document.querySelector('#editcategoria_id');

    // console.log(
    //     'editcategoria:',
    //     editCategoria
    // );

    if (editCategoria) {

        const tsEditCategoria =
            new TomSelect(editCategoria, {

                create: false,

                placeholder: 'Digite para pesquisar...',

                allowEmptyOption: true,

                dropdownParent: 'body'

            });
//document.querySelector('#editcategoria_id').tomselect;
//document.querySelectorAll('#editcategoria_id').length;
        // console.log(
        //     'TomSelect #editcategoria_id criado',
        //     tsEditCategoria
        // );
        
    }

});
*/
