<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'description'
    ];

    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    public function members()
    {
        return $this->belongsToMany(User::class);
    }

    public function getStorageUsedAttribute()
    {
        $totalSizeInBytes = $this->videos()->sum('size');
        return $this->formatBytes($totalSizeInBytes);
    }

    private function formatBytes($bytes)
    {
        $sizes = ['B', 'KB', 'MB', 'GB', 'TB'];
        $factor = floor((strlen($bytes) - 1) / 3);

        return sprintf("%.2f", $bytes / pow(1024, $factor)) . ' ' . $sizes[$factor];
    }

    public function getLastVideoUploadedAtAttribute()
    {
        // obter a data do último vídeo enviado
        return $this->videos()->latest()->first()?->created_at;
    }
}
