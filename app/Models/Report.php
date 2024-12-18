<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{

    public $timestamps = false;

    protected $table = 'report';

    protected $fillable = [
        'post_id',
        'user_id',
        'comment'
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function create(array $attributes): self
    {
        return new self($attributes);
    }
}
