<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use \OwenIt\Auditing\Auditable as AuditingAiuditable;
use OwenIt\Auditing\Contracts\Auditable;

class Forma_pgto extends Model implements Auditable
{
    use HasFactory, AuditingAiuditable;
    // nome da tabela sempre no plural
    protected $table = 'forma_pgtos';
    // indicar quais colunas devem receber dados, sempre que alterar tabela, deve descrever aqui
    // nao precisa adicionar o ID
    protected $fillable = ['nome'];

    public function movimento()
    {
        return $this->hasMany(Movimento::class);
    }
}
