<?php namespace App\Handlers\Events;

use App\Events\newChecklistEntry;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;

use App\Randomlist;
use App\RandomlistLocation;

use Log;

class updateTodoList {

	/**
	 * Create the event handler.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//
	}

	/**
	 * Handle the event.
	 *
	 * @param  newChecklistEntry  $event
	 * @return void
	 */
	public function handle(newChecklistEntry $event)
	{

		$checklist = $event->entry;
		$randomlist = Randomlist::where('completed_at', 0)->first();
		// Get the randomlistlocation
	    $randomlistlocation = $randomlist->locations()->where("location_id", "=", $checklist->location_id)->first();
	    if( count($randomlistlocation) ){
	      	$randomlistlocation->visited = 1;
	      	$randomlistlocation->save();
	    }

	}

}
