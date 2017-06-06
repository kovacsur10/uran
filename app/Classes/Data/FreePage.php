<?php

namespace App\Classes\Data;

/** Class name: FreePage
 *
 * This class stores an ECnet user's free printing pages.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class FreePage{
	
	// PRIVATE DATA
	private $count;
	private $until;
	
	// PUBLIC FUNCTIONS
	
	/** Function name: __construct
	 *
	 * This is the constructor for the Faculty class.
	 *
	 * @param int $count - free pages count
	 * @param datetime $until - valid until this date
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function __construct(int $count, string $until){
		$this->count = $count;
		$this->until = $until;
	}
	
	/** Function name: count
	 *
	 * This is the getter for count.
	 *
	 * @return int - The count of the free pages.
	 */
	public function count() : int{
		return $this->count;
	}
	
	/** Function name: until
	 *
	 * This is the getter for until.
	 *
	 * @return string - The datetime until the user can use the free pages..
	 */
	public function until() : string{
		return $this->until;
	}
	
}