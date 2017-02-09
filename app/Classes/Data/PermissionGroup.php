<?php

namespace App\Classes\Data;

/** Class name: PermissionGroup
 *
 * This class stores a site permissio group.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class PermissionGroup{

	// PRIVATE DATA
	private $id;
	private $name;
	private $permissions;

	// PUBLIC FUNCTIONS

	/** Function name: __construct
	 *
	 * This is the constructor for the PermissionGroup class.
	 *
	 * @param int $id - group identifier
	 * @param string $name - group name
	 * @param arrayOfPermission $permissions - permissions in the group
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function __construct(int $id, string $name, $permissions){
		$this->id = $id;
		$this->name = $name;
		$this->permissions = $permissions;
	}

	/** Function name: id
	 *
	 * This is the getter for id.
	 *
	 * @return int - The identifier of the group.
	 */
	public function id() : int{
		return $this->id;
	}

	/** Function name: name
	 *
	 * This is the getter for name.
	 *
	 * @return string - The name of the group.
	 */
	public function name() : string{
		return $this->name;
	}
	
	/** Function name: permissions
	 *
	 * This is the getter for permissions.
	 *
	 * @return array of Permission - The name of the group.
	 */
	public function permissions(){
		return $this->permissions;
	}

}