<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Percentage extends Model
{
    use SoftDeletes;

	protected $dates = ['deleted_at'];
	// protected $hidden = ['pivot'];
	 /**
     * Get the Criteria for the Percentage.
     */
    public function criterias()
    {
        return $this->hasMany('App\Models\Criteria')->withTrashed();
    }


	 /**
     * Get the NotesBackoffice for the Percentage.
     */
    public function notesBackOffice()
    {
    	return $this->belongsToMany('App\Models\Project','notes_back_office')->withTrashed()->withPivot('note');
    }

    public function event()
    {
        return $this->belongsTo('App\Models\Event')->withTrashed();
    }


}
