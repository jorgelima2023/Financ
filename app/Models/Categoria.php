<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;
    // nome da tabela sempre no plural
    protected $table = 'categorias';

    // indicar quais colunas devem receber dados, sempre que alterar tabela, deve descrever aqui
    // não precisa colocar o campo ID
    protected $fillable = ['nome', 'classif', 'soma'];
}
