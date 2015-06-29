<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class RandomlistLocation extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

  public function randomlist()
  {
    return $this->belongsTo('App\Randomlist', 'randomlist_id', 'id');
  }

  public function location()
  {
  	return $this->belongsTo('App\Location', 'location_id', 'id');
  }


  /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'location_randomlist';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['randomlist_id', 'user_id', 'location_id', 'visited'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [];

}
