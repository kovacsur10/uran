<?php

namespace App\Classes\Interfaces;

/** Class name: Pageable
 *
 * This class gives a pagination class for
 * arrays.
 *
 * Functionality:
 *
 * Functions that can throw exceptions:
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class Pageable{
	
	/** Function name: toPages
	 *
	 * This function returns the requested count
	 * of data from the requested identifier.
	 *
	 * @param array $data - data
	 * @param int $from - first data identifier
	 * @param int $count - data count
	 * @return array of part of the data
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	protected function toPages($data, $from = 0, $count = 50){
		if($data === null || $from === null || $count === null || $from < 0 || count($data) < $from || $count <= 0){
			return [];
		}else if($count === 0 || (count($data) < $from + $count)){
			return array_slice($data, $from, count($data) - $from);
		}else{
			return array_slice($data, $from, $count);
		}
	}
	
}