<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = true;
    protected $fillable = [
        'id',
        'user_id',
        'note_id',
        'status',
        'transaction_time',
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function note(): BelongsTo
    {
        return $this->belongsTo(Note::class, 'note_id', 'id');
    }
}
