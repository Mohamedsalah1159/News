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
        'folder_id',
        'created_at',
        'updated_at'
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    public function getNameAttribute(){
        return asset('gallary/' . $this->attributes['img']);
    }
    // relations
    public function employee(){
        return $this->belongsTo('App\Models\Employee', 'employee_id', 'id');
    }
    public function folder(){
        return $this->belongsTo('App\Models\Folder', 'folder_id', 'id');
    }

}
