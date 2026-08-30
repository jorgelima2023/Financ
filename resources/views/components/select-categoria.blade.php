@props([
    'name' => 'categoria_id',
    'id' => 'categoria_id',
    'categorias' => [],
    'selected' => null
])

<select 
    name="{{ $name }}" 
    id="{{ $id }}" 
    class="form-select"
>

    <option value="">Selecione uma categoria</option>

    @foreach($categorias as $categoria)
        <option 
            value="{{ $categoria->id }}"
            {{ old($name, $selected) == $categoria->id ? 'selected' : '' }}
        >
            {{ $categoria->nome }}
        </option>
    @endforeach

</select>