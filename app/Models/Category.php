<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    
    public function event()
    {
        return $this->belongsTo('App\Models\Event')->withTrashed();
    }

    public function criterias()
    {
        return $this->hasMany('App\Models\Criteria')->withTrashed();
	}
}
