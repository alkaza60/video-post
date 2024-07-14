<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 
        'file_path',
        'duration', 
        'size', 
        'status',
        'uploaded_at',
        'collection_id',
        'user_id'
    ];

    // Accessor para formatar a duração
    public function getFormattedDurationAttribute()
    {
        $duration = $this->attributes['duration'];

        if ($duration < 3600) {
            return gmdate('i \m\i\n', $duration);
        }

        return gmdate('H:i \h', $duration);
    }

    // Accessor para formatar o tamanho
    public function getFormattedSizeAttribute()
    {
        $size = $this->attributes['size'];

        return number_format($size / (1024 * 1024), 2) . ' MB';
    }

    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'tag_video');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
