<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class SubcategoryLocation extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;


  public function subcategory()
  {
    return $this->belongsTo('App\Subcategory', 'subcategory_id', 'id');
  }

  public function location()
  {
    return $this->belongsTo('App\Location', 'location_id', 'id');
  }

  public function checklistitems()
  {
    return $this->hasMany('App\ChecklistItem', 'subcategorylocation_id', 'id');
  }
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'location_subcategory';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['subcategory_id', 'location_id'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [];

}
