<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \OwenIt\Auditing\Auditable as AuditingAiuditable;
use OwenIt\Auditing\Contracts\Auditable;

class ContasCorrente extends Model implements Auditable
{
    use HasFactory, AuditingAiuditable;
    // nome da tabela sempre no plural
    protected $table = 'contascorrentes';
    // indicar quais colunas devem receber dados, sempre que alterar tabela, deve descrever aqui
    protected $fillable = ['nome','agencia','conta','cod_compe','carteira'];

    public function movimento()
    {
        return $this->hasMany(Movimento::class);
    }
}
