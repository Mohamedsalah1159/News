<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallary extends Model
{
    protected $table = 'gallary';
    protected $fillable = [
        'id',
        'name',
        'employee_id',
        'created_at',
        'updated_at'
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    // relations
    public function employee(){
        return $this->belongsTo('App\Models\Employee', 'employee_id', 'id');
    }
}
