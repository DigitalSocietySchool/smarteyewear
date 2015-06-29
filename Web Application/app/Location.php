<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class Location extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;


  public function area()
  {
    return $this->belongsTo('App\Area', 'area_id', 'id');
  }

  public function subcategorylocations()
  {
    return $this->hasMany('App\SubcategoryLocation', 'location_id', 'id');
  }

  public function checklists()
  {
    return $this->hasMany('App\Checklist', 'location_id', 'id');
  }

  public function subcategories()
  {
  	return $this->belongsToMany('App\Subcategory');
  }

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'locations';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'top_left', 'top_right', 'bottom_left', 'bottom_right'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [];

}