<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'is_important',
    ];

    /**
     * Define la relación inversa uno a muchos con User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define la relación muchos a muchos con Tag.
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
        // Si tu tabla pivote no se llama 'note_tag' o las claves foráneas
        // no son 'note_id' y 'tag_id', tendrías que especificarlo así:
        // return $this->belongsToMany(Tag::class, 'nombre_tabla_pivote', 'clave_foranea_nota', 'clave_foranea_etiqueta');
    }
}
