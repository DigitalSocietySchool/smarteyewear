<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class ChecklistItem extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

  public function checklist()
  {
    return $this->belongsTo('App\Checklist', 'checklist_id', 'id');
  }

  public function subcategorylocation()
  {
    return $this->belongsTo('App\SubcategoryLocation', 'subcategorylocation_id', 'id');
  }

  public function subcategory()
  {
  	return $this->belongsTo('App\Subcategory', 'subcategory_id', 'id');
  }


  /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'checklist_items';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['checklist_id', 'subcategorylocation_id', 'grade', 'accepted'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [];

}
