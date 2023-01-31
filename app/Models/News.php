<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table = 'news';
    protected $fillable = [
        'id',
        'name',
        'desc',
        'user',
        'link',
        'const',
        'status',
        'user_id',
        'governorate_id',
        'government_id',
        'created_at',
        'updated_at',
        'initiative_id'
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    // get status and edit it
    public function getStatus(){
        return $this -> status == 0 ? 'News' : 'Article';
    }
    public function getGovernmentIdAttribute(){
        $government = Government::select('name')->where('id', $this->attributes['government_id'])->first();
        return $this->attributes['government_id'] == '' ? 'non have governmet' : $government->name;
    }
    public function getUserIdAttribute(){
        $user = User::select('name')->where('id', $this->attributes['user_id'])->first();
        return $this->attributes['user_id'] == '' ? 'non have user' : $user->name;
    }
    public function getInitiativeIdAttribute(){
        $initiative = Initiative::select('name')->where('id', $this->attributes['initiative_id'])->first();
        return $this->attributes['initiative_id'] == '' ? 'non have initiative' : $initiative->name;
    }
    public function getGovernorateIdAttribute(){
        $governorate = Governorate::select('name')->where('id', $this->attributes['governorate_id'])->first();
        return $this->attributes['governorate_id'] == '' ? 'non have governorate' : $governorate->name;
    }
    // relations
    public function governorate(){
        return $this->belongsTo('App\Models\Governorate', 'governorate_id', 'id');
    }
    public function source(){
        return $this->hasMany('App\Models\Source', 'news_s_id', 'id');
    }
    public function words(){
        return $this->hasMany('App\Models\Word', 'news_w_id', 'id');
    }
    public function idimage(){
        return $this->hasMany('App\Models\Idimage', 'news_i_id', 'id');
    }
    public function user(){
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
    public function initiative(){
        $this->belongsTo('App\Models\Initiative', 'initiative_id', 'id');
    }
    public function government(){
        $this->belongsTo('App\Models\Government', 'government_id', 'id');
    }
}
