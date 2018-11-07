<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sala extends Model
{

    /**
     * Get the unidade that owns the sala.
     */
    public function unidade()
    {
        return $this->belongsTo('App\Models\Unidade');
    }
}
