<?php namespace App\Events;

use App\Events\Event;

use Illuminate\Queue\SerializesModels;

class newChecklistEntry extends Event {

	use SerializesModels;

	public $entry;

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct($entry)
	{
		//
		$this->entry = $entry;
	}

}
