<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Database\Eloquent;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Document
 *
 * Represents a document in the application.
 *
 * @property int $id            The primary key of the document.
 * @property string $file_name  The name of the file.
 * @property string $file_type  The MIME type of the file (e.g., 'image/jpeg').
 * @property int $size          The size of the file in bytes.
 * @property string $file_path  The path where the file is stored.
 *
 * @mixin Eloquent
 */
class Document extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'file_name',
        'file_type',
        'size',
        'file_path',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
