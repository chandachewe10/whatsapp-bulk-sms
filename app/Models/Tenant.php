<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    protected $fillable = ['name', 'domain'];

    public function whatsappConfig(): HasOne
    {
        return $this->hasOne(WhatsAppConfig::class);
    }

    public function templates(): HasMany
    {
        return $this->hasMany(MessageTemplate::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}
