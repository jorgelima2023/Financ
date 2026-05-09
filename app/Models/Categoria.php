<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use \OwenIt\Auditing\Auditable as AuditingAiuditable;
use OwenIt\Auditing\Contracts\Auditable;

class Categoria extends Model implements Auditable
{
    use HasFactory, AuditingAiuditable;

    // nome da tabela sempre no plural
    protected $table = 'categorias';

    // indicar quais colunas devem receber dados, sempre que alterar tabela, deve descrever aqui
    // não precisa colocar o campo ID
    protected $fillable = ['nome', 'classif', 'soma', 'lcto', 'ativo'];

    // return $this->hasMany('App\Cidade','estado_id');
    public function movimento()
    {
        return $this->hasMany(Movimento::class);
    }
}
