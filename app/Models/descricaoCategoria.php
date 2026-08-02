<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class descricaoCategoria extends Model implements Auditable
{
    use HasFactory,  AuditingAiuditable;
    //
    // nome da tabela sempre no plural
    protected $table = 'descricao_categorias';
    // indicar quais colunas devem receber dados, sempre que alterar tabela, deve descrever aqui
    // nao precisa adicionar o ID
    protected $fillable = ['descricao_categoria', 'categoria_id'];
}
