<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asistente extends Model
{
    protected $fillable = [
        'nombre',
        'email',
        'telefono',
        'evento_id',
    ];

    public function evento(): BelongsTo
    {
        return $this->belongsTo(Evento::class);
    }
}
