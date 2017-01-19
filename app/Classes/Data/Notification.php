<?php

namespace App\Classes\Data;

/** Class name: Notification
 *
 * This class stores a Notification.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class Notification{

	// PRIVATE DATA
	private $id;
	private $name;
	private $username;
	private $subject;
	private $message;
	private $time;
	private $seen;
	private $admin;
	private $route;

	// PUBLIC FUNCTIONS

	/** Function name: __construct
	 *
	 * This is the constructor for the Notification class.
	 *
	 * @param int $id - notification identifier
	 * @param string $name - sender name
	 * @param string $username - sender username
	 * @param string $subject - subject of notification
	 * @param string $message - text of the notification
	 * @param string $time - sending time
	 * @param bool $seen - already seen or not by the user
	 * @param bool $admin - administrator mode on or off
	 * @param string|null $route - sender source route
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function __construct(int $id, string $name, string $username, string $subject, string $message, string $time, bool $seen, bool $admin, string $route = null){
		$this->id = $id;
		$this->name = $name;
		$this->username = $username;
		$this->subject = $subject;
		$this->message = $message;
		$this->time = $time;
		$this->seen = $seen;
		$this->admin = $admin;
		$this->route = $route;
	}

	/** Function name: id
	 *
	 * This is the getter for id.
	 *
	 * @return int - The identifier of the notification.
	 */
	public function id() : int{
		return $this->id;
	}

	/** Function name: name
	 *
	 * This is the getter for name.
	 *
	 * @return string - The name of the notification sender.
	 */
	public function name() : string{
		return $this->name;
	}
	
	/** Function name: username
	 *
	 * This is the getter for username.
	 *
	 * @return string - The username of the notification sender.
	 */
	public function username() : string{
		return $this->username;
	}
	
	/** Function name: subject
	 *
	 * This is the getter for subject.
	 *
	 * @return string - The subject of the notification.
	 */
	public function subject() : string{
		return $this->subject;
	}
	
	/** Function name: message
	 *
	 * This is the getter for message.
	 *
	 * @return string - The message of the notification.
	 */
	public function message() : string{
		return $this->message;
	}
	
	/** Function name: time
	 *
	 * This is the getter for time.
	 *
	 * @return string - The time of the notification.
	 */
	public function time() : string{
		return $this->time;
	}
	
	/** Function name: isSeen
	 *
	 * This is the getter for seen.
	 *
	 * @return string - The seen state of the notification.
	 */
	public function isSeen() : bool{
		return $this->seen;
	}
	
	/** Function name: isAdmin
	 *
	 * This is the getter for admin.
	 *
	 * @return string - The admin of the notification.
	 */
	public function isAdmin() : bool{
		return $this->admin;
	}
	
	/** Function name: route
	 *
	 * This is the getter for route.
	 *
	 * @return string|null - The route of the notification.
	 */
	public function route(){
		return $this->route;
	}

}

?>