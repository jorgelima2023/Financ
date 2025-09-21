<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use \OwenIt\Auditing\Auditable as AuditingAiuditable;
use OwenIt\Auditing\Contracts\Auditable;

class Movimento extends Model implements Auditable
{
    use HasFactory, AuditingAiuditable; 

    // nome da tabela sempre no plural
    protected $table = 'movimentos';
    // indicar quais colunas devem receber dados, sempre que alterar tabela, deve descrever aqui
    // 'forma_pgto_id', 'vlr', 'dia', 'mes', 'ano',
    protected $fillable = [ 'tipo', 'forma_pgto_id', 
                            'tipo_lc',
                            'categoria_id', 
                            'descricao', 
                            'valor', 
                            'dt', 
                            'dcto'];

    /**
     * Get the phone associated with the user.
     *   return $this->belongsTo('App\Estado','estado_id');
     */
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function forma_pgto()
    {
        return $this->belongsTo(Forma_pgto::class);
    }


   }
