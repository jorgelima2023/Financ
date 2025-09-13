/*!
    * Start Bootstrap - SB Admin v7.0.7 (https://startbootstrap.com/template/sb-admin)
    * Copyright 2013-2023 Start Bootstrap
    * Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-sb-admin/blob/master/LICENSE)
    */
    // 
// Scripts
// 

window.addEventListener('DOMContentLoaded', event => {

    // Toggle the side navigation
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        // Uncomment Below to persist sidebar toggle between refreshes
        // if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
        //     document.body.classList.toggle('sb-sidenav-toggled');
        // }
        sidebarToggle.addEventListener('click', event => {
            event.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
        });
    }
});

// Explicação: checkbox-excluir
// 1-document.addEventListener('DOMContentLoaded', function () { ... })
// Isso garante que o código dentro da função só será executado depois que a página HTML estiver totalmente carregada.
// Isso evita erros, porque o JavaScript tenta acessar elementos que podem não estar disponíveis antes do carregamento completo.

// 2-const checkboxes = document.querySelectorAll('.checkbox-excluir');
// Aqui, o código seleciona todos os elementos da página que têm a classe checkbox-excluir.
// Esses elementos provavelmente são caixas de seleção (<input type="checkbox">) com essa classe.

// 3-checkboxes.forEach(checkbox => { ... })
// Para cada checkbox encontrado, o código vai adicionar um evento.
// forEach serve para percorrer cada item da lista checkboxes.

// 4-checkbox.addEventListener('change', function () { ... })
// Adiciona um evento que será executado sempre que o estado da checkbox mudar (ou seja, quando for marcada ou desmarcada).

// 5-const row = this.closest('tr');
// Dentro do evento, this se refere à checkbox que mudou.
// O método closest('tr') procura o elemento ancestral mais próximo que seja uma linha de tabela (<tr>).
// Ou seja, ele está pegando a linha da tabela onde a checkbox está.

// 6-if (this.checked) { ... } else { ... }
// Verifica se a checkbox está marcada (checked == true) ou desmarcada (checked == false).

// 7-row.classList.add('linha-excluir');
// Se a checkbox estiver marcada, adiciona a classe CSS linha-excluir à linha da tabela.
// Essa classe pode, por exemplo, mudar a aparência da linha (cor, riscar texto, esconder, etc).

// 8-row.classList.remove('linha-excluir');
// Se a checkbox estiver desmarcada, remove a classe linha-excluir da linha da tabela, revertendo a aparência.

// Resumo:
// Esse código serve para alterar o estilo visual da linha da tabela quando a checkbox associada 
// a ela é marcada ou desmarcada, usando a classe CSS linha-excluir. 
// Ele escuta mudanças nas checkboxes com a classe .checkbox-excluir e modifica a linha da tabela correspondente.



document.addEventListener('DOMContentLoaded', function () {
    const checkboxes = document.querySelectorAll('.checkbox-excluir');

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const row = this.closest('tr');
            if (this.checked) {
                row.classList.add('linha-excluir');
            } else {
                row.classList.remove('linha-excluir');
            }
        });
    });
});
