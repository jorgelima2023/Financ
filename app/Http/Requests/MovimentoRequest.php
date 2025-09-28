<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MovimentoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
         // form => name="tipo_lcto" 1-Receita , 0-Despesa

        //         name="tipo"      "O" Obrigacao , "P" Provisao

        return [
            'data' => 'required|date',
            //'tipo_lc' => 'required|boolean',     // 1-Receita , 0-Despesa
            //'tipo_lc' => 'required|in:1,0',
            //'tipo' => 'required|in:O,P',   // "O" Obrigacao , "P" Provisao
            'categoria_id' => 'required|exists:categorias,id',
            'descricao' => 'required|string|max:150',
            'dcto' => 'required|string|max:50',
            'valor' => 'required|max:10',
        ];
    }

    public function messages(): array
    {
        return [
            'data.required' => 'Campo data obrigatorio',
            //'tipo_lc.required' => 'Campo tipo lançamento obrigatorio, informar:Receita ou Despesa',
            //'tipo.required' => 'Campo tipo obrigatorio, informar:Obrigação ou Provisão',
            'categoria_id.required' => 'Campo Categoria obrigatorio',
            'descricao.required' => 'Campo Descrição obrigatorio',
            'dcto.required' => 'Campo Documento obrigatorio',
            'valor.max' => 'Campo Valor so pode ter no maximo 8 numeros',
        ];
        // 'price.numeric' => 'Campo preco deve ter apenas numeros',
    }
}
