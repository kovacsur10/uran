<?php

namespace App\Classes\Data;

/** Class name: User
 *
 * This class stores a website User.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class User{

	// PRIVATE DATA
	private $id;
	private $name;
	private $username;
	private $password;
	private $email;
	private $registration_date;
	private $status;
	private $last_online;
	private $language;
	private $registered;

	// PUBLIC FUNCTIONS

	/** Function name: __construct
	 *
	 * This is the constructor for the User class.
	 *
     * @param int $id - user's identifier
	 * @param string $name - user's name
	 * @param string $username - user's text identifier
	 * @param string $password - user's password
	 * @param string $email - user's e-mail address
	 * @param string $registration_date - datetime of registration
	 * @param StatusCode $status - user status
	 * @param string|null $last_online - datetime of last login
	 * @param string $language - default language of the user
	 * @param bool $registered - registration status
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function __construct(int $id, string $name, string $username, string $password, string $email, string $registration_date, StatusCode $status, string $last_online = null, string $language, bool $registered){
		$this->id = $id;
		$this->name = $name;
		$this->username = $username;
		$this->password = $password;
		$this->email = $email;
		$this->registration_date = $registration_date;
		$this->status = $status;
		$this->last_online = $last_online;
		$this->language = $language;
		$this->registered = $registered;
		
	}

	/** Function name: id
	 *
	 * This is the getter for id.
	 *
	 * @return int - The identifier of the user.
	 */
	public function id() : int{
		return $this->id;
	}

	/** Function name: name
	 *
	 * This is the getter for name.
	 *
	 * @return string - The name of the user.
	 */
	public function name() : string{
		return $this->name;
	}
	
	/** Function name: username
	 *
	 * This is the getter for username.
	 *
	 * @return string - The username of the user.
	 */
	public function username() : string{
		return $this->username;
	}
	
	/** Function name: password
	 *
	 * This is the getter for password.
	 *
	 * @return string - The password of the user.
	 */
	public function password() : string{
		return $this->password;
	}
	
	/** Function name: email
	 *
	 * This is the getter for email.
	 *
	 * @return string - The email of the user.
	 */
	public function email() : string{
		return $this->email;
	}
	
	/** Function name: registrationDate
	 *
	 * This is the getter for registration_date.
	 *
	 * @return string - The registration_date of the user.
	 */
	public function registrationDate() : string{
		return $this->registration_date;
	}
	
	/** Function name: lastOnline
	 *
	 * This is the getter for last_online.
	 *
	 * @return string|null - The last online time of the user.
	 */
	public function lastOnline(){
		return $this->last_online;
	}
	
	/** Function name: language
	 *
	 * This is the getter for language.
	 *
	 * @return string - The language of the user.
	 */
	public function language() : string{
		return $this->language;
	}
	
	/** Function name: registered
	 *
	 * This is the getter for registered.
	 *
	 * @return bool - The registered of the user.
	 */
	public function registered() : bool{
		return $this->registered;
	}
	
	/** Function name: status
	 *
	 * This is the getter for status.
	 *
	 * @return bool - The status of the user.
	 */
	public function status() : StatusCode{
		return $this->status;
	}

}