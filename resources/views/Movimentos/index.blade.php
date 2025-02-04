{{-- 
    https://pt.stackoverflow.com/questions/344575/como-retornar-registros-relecionados-entre-tabelas-em-laravel
  
    https://groups.google.com/g/laravel-brasil/c/Wt6Q-eq2tIY
  
    --}}

{{-- dd($movimentos) --}}

@forelse ( $movimentos as $movimento )
    id movimento: {{ $movimento->id }} <br>
    descricao: {{ $movimento->descricao }} <br>
    Data: {{ $movimento->dt }} <br>
    categoria_id: {{ $movimento->categoria_id }} <br>
    categoria_nome: {{ $movimento->categoria->nome }} <br>
    categoria_classif: {{ $movimento->categoria->classif }} <br> 
    forma_pgto_id:  {{ $movimento->forma_pgto_id }} <br>
    forma_pgto_nome: {{ $movimento->forma_pgto->nome }} <br> <br>
    
@empty
    <p style="color: #f00">Nenhum movimento encontrado!</p>
@endforelse