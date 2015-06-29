<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class Checklist extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

  public function items()
  {
    return $this->hasMany('App\ChecklistItem', 'checklist_id', 'id');
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
	protected $table = 'checklists';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['location_id', 'user_id'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [];

}
