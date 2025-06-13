<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Define la relación muchos a muchos con Note.
     */
    public function notes()
    {
        return $this->belongsToMany(Note::class);
        // De forma similar al modelo Note, si tu tabla pivote no se llama 'note_tag'
        // o las claves foráneas no son 'note_id' y 'tag_id', tendrías que especificarlo.
    }
}
