{{-- 
    https://pt.stackoverflow.com/questions/344575/como-retornar-registros-relecionados-entre-tabelas-em-laravel
  
    https://groups.google.com/g/laravel-brasil/c/Wt6Q-eq2tIY
  
    --}}

@foreach($movimentos as $movimento)
<tr>
    <td>{{$movimento->descricao}}</td>
</tr>
    @foreach($movimento->categoria_id as $categoria)
        <tr>
            <td>{{$categoria->nome}}</td>
        </tr>
    @endforeach

@endforeach