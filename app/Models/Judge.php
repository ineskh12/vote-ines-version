<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Judge extends Model
{
   
   	use SoftDeletes;

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];
    
    public function event()
    {
        return $this->belongsTo('App\Models\Event')->withTrashed();
    }

    public function votes(){
        return $this->belongsToMany('App\Models\Project', 'notes_mobile')->withTrashed()->withPivot('note');
    }

    public function questions(){
        return $this->belongsToMany('App\Models\Project', 'questions')->withPivot(['id', 'question', 'status'])->withTimestamp();
    }

}
