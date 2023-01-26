<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model
{
    use SoftDeletes;
    protected $table = 'exams';
    protected $fillable = [
        'id',
        'name',
        'appointment',
        'time',
        'appointment_time',
        'user_id',
        'created_at',
        'updated_at'
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    // relations
    public function user(){
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
    public function questions(){
        return $this->hasMany('App\Models\Question', 'exam_id', 'id');
    }
    public function examUsers(){
        return $this->hasMany('App\Models\ExamUser', 'exam_id', 'id');
    }
}
