<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Movimento extends Model
{
    use HasFactory; 

    // nome da tabela sempre no plural
    protected $table = 'movimentos';
    // indicar quais colunas devem receber dados, sempre que alterar tabela, deve descrever aqui
    protected $fillable = [ 'tipo,', 'dia', 'mes', 'ano', 
                            'categoria_id', 
                            'forma_pgto_id',
                            'descricao', 
                            'vlr', 'valor', 
                            'dt', 'dcto'];

    /**
     * Get the phone associated with the user.
     *   return $this->belongsTo('App\Estado','estado_id');
     */
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }




   }
