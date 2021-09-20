<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{

   	use SoftDeletes;

	protected $dates = ['deleted_at','created_at'];

	//protected $hidden = ['status','created_at','updated_at','deleted_at'];


    public function backOfficeNotes(){
        return $this->belongsToMany('App\Models\Percentage','notes_back_office')->withTrashed()->withPivot('note');
    }

    public function votes(){
        return $this->belongsToMany('App\Models\Voter','notes_mobile')->withPivot(['note','criteria_id']);
    }

    public function judges(){
        return $this->belongsToMany('App\Models\Judge','notes_mobile')->withPivot(['note','criteria_id']);
    }

    public function questions(){
        return $this->belongsToMany('App\Models\Project','questions')->withPivot(['id','question','status'])->withTimestamps();
    }

    public function event()
    {
        return $this->belongsTo('App\Models\Event')->withTrashed();
    }

}
