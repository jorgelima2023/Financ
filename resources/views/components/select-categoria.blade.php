@props([
    'name' => 'categoria_id',
    'id' => 'categoria_id',
    'categorias' => [],
    'selected' => null
])

<select 
    name="categoria_id" 
    id="categoria_id" 
    class="form-select"
>

    <option value="">Selecione uma categoria</option>

    @foreach($categorias as $categoria)
        <option value="{{ $categoria->id }}">
            {{ $categoria->nome }}
        </option>
    @endforeach

</select>