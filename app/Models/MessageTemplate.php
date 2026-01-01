<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MessageTemplate extends Model
{
    protected $fillable = [
        'tenant_id',
        'name',
        'category',
        'language',
        'status',
        'content',
        'whatsapp_template_id'
    ];

    protected $casts = [
        'content' => 'array',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
