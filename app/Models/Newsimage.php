<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Newsimage extends Model
{
    protected $table = 'newsimages';
    protected $fillable = [
        'id',
        'name',
        'created_at',
        'updated_at'
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    public function getNameAttribute(){
        return asset('newsimages/' . $this->attributes['img']);
    }
    //relations
    public function idImages(){
        return $this->hasOne('App\Models\Idimage', 'id_image', 'id');
    }
}
