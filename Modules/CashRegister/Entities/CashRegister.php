<?php

namespace Modules\CashRegister\Entities;

use Illuminate\Database\Eloquent\Model;

class CashRegister extends Model
{
    protected $guarded = [];

    public function sessions()
    {
        return $this->hasMany(CashRegisterSession::class);
    }
}


