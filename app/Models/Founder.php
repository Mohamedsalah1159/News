<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Founder extends Model
{
    use HasFactory;
    protected $table = 'founders';
    protected $fillable = [
        'id',
        'name',
        'desc',
        'img',
        'created_at',
        'updated_at'
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    public function getImgAttribute(){
        return asset('founders/' . $this->attributes['img']);
    }
}
