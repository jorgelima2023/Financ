<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movimento extends Model
{
    use HasFactory; 
    // nome da tabela sempre no plural
    protected $table = 'movimentos';
    // indicar quais colunas devem receber dados, sempre que alterar tabela, deve descrever aqui
    protected $fillable = ['dia', 'mes', 'ano', 'categorias', 'descricao', 'vlr', 'valor', 'forma_pgtos', 'dt', 'dcto'];
   }
