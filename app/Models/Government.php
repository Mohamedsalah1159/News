<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Government extends Model
{
    protected $table= 'governments';
    protected $fillable = [
        'id',
        'name',
        'governmentStatus',
        'registration_status',
        'created_at',
        'updated_at'
    ];
    protected $hidden = [
        'created_at',
        'updated_at'  
    ];
    public function getGovernmentStatusAttribute(){
        return $this->attributes['governmentStatus'] == 0 ? 'Basic Government' : 'Sub Government';
    }
    public function getRegistrationAttribute(){
        return $this->attributes['registration_status'] == 0 ? 'non-register' : 'Can register';
    }
    //relations 
    public function news(){
        return $this->hasMany('App\Models\News', 'government_id', 'id');
    }
    public function employee(){
        return $this->hasMany('App\Models\Employee', 'government_id', 'id');
    }
}
