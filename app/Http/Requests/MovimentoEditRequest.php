<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MovimentoEditRequest extends FormRequest
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
        return [
            'editdt' => 'required|date',
            //'tipo_lc' => 'required|boolean',     // 1-Receita , 0-Despesa
            //'tipo_lc' => 'required|in:1,0',
            //'tipo' => 'required|in:O,P',   // "O" Obrigacao , "P" Provisao
            'eforma_pgto_id' => 'required|exists:forma_pgtos,id',
            'editcategoria_id' => 'required|exists:categorias,id',
            'editDescricao' => 'required|string|max:150',
            'editdcto' => 'required|string|max:50',
            'editValor' => 'required|max:10',
        ];
    }

    public function messages(): array
    {
        return [
            'editdt.required' => 'Campo data obrigatorio',
            //'tipo_lc.required' => 'Campo tipo lançamento obrigatorio, informar:Receita ou Despesa',
            //'tipo.required' => 'Campo tipo obrigatorio, informar:Obrigação ou Provisão',
            'eforma_pgto_id.required' => 'Campo Forma pagamento obrigatorio',
            'editcategoria_id.required' => 'Campo Categoria obrigatorio',
            'editDescricao.required' => 'Campo Descrição obrigatorio',
            'editdcto.required' => 'Campo Documento obrigatorio',
            'editValor.max' => 'Campo Valor so pode ter no maximo 8 numeros',
        ];
        // 'price.numeric' => 'Campo preco deve ter apenas numeros',
    }
}
