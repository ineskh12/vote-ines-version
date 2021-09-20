<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
   	use SoftDeletes;

	protected $dates = ['date_from', 'date_to', 'created_at', 'deleted_at'];

	public function projects()
    {
        return $this->hasMany('App\Models\Project')->withTrashed();
	}

	public function percentages()
    {
        return $this->hasMany('App\Models\Percentage')->withTrashed();
	}

	public function criterias()
    {
        return $this->hasMany('App\Models\Criteria')->withTrashed();
	}

	public function categories()
    {
        return $this->hasMany('App\Models\Category')->withTrashed();
    }

	public function setDateFromAttribute(string $date)
	{
		$this->attributes['date_from'] = $date;
	}
}
