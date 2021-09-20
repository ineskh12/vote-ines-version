<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Criteria extends Model
{
    use SoftDeletes;

	protected $dates = ['deleted_at'];


	/**
     * Get the Percentage that owns the Criteria.
     */
    public function percentage()
    {
        return $this->belongsTo('App\Models\Percentage')->withTrashed();
    }

    public function votes(){
        return $this->belongsToMany('App\Models\Project','notes_mobile')->withTrashed()->withPivot('note');
    }

    public function event()
    {
        return $this->belongsTo('App\Models\Event')->withTrashed();
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category')->withTrashed();
    }
}
