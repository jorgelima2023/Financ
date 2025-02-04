<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Forma_pgto extends Model
{
    use HasFactory;
    // nome da tabela sempre no plural
    protected $table = 'forma_pgtos';
    // indicar quais colunas devem receber dados, sempre que alterar tabela, deve descrever aqui
    // nao precisa adicionar o ID
    protected $fillable = ['nome'];


}
