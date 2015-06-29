<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class Randomlist extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

  public function locations()
  {
    return $this->hasMany('App\RandomlistLocation', 'randomlist_id', 'id');
  }

  public function getLocations(){
  	return $this->belongsToMany("App\Location")->orderBy("area_id");
  }


  /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'randomlists';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['completed_at', 'start_date', 'end_date'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [];

}
