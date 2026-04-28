<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    /**
     * La tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'comments';

    /**
     * La tabla no tiene columna updated_at.
     *
     * @var string|null
     */
    const UPDATED_AT = null;

    /**
     * Los atributos que son asignables en masa.
     *
     * @var list<string>
     */
    protected $fillable = [
        'resource_id',
        'user_id',
        'comment',
    ];

    /**
     * Relación: Un comentario pertenece a un usuario.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación: Un comentario pertenece a un recurso.
     *
     * @return BelongsTo
     */
    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }
}
