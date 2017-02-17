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
	
	private $country;
	private $city;
	private $shire;
	private $address;
	private $postal_code;
	
	private $phone;
	private $reason;
	private $personal_data;
	
	private $registration_verification_status;
	private $registration_verification_date;
	private $registration_code;

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
	 * @param string $country - address part, country
	 * @param string $city - address part, city
	 * @param string $shire - address part, shire/region
	 * @param string $address - address part, address
	 * @param string $postal_code - address part, postal code
	 * @param string|null $reason - user's reason of registration
	 * @param PersonalData|null $personal_data - user's personal data (collegist data)
	 * @param string $phone_number - user's phone number
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function __construct(int $id, string $name, string $username, string $password, string $email, string $registration_date, StatusCode $status, string $last_online = null, string $language, bool $registered, bool $registration_verification_status, string $registration_verification_date = null, string $registration_code, string $country, string $city, string $shire, string $address, string $postal_code, string $reason = null, PersonalData $personal_data = null, string $phone_number){
		$this->id = $id;
		$this->name = $name;
		$this->username = $username;
		$this->password = $password;
		$this->email = $email;
		$this->registration_date = $registration_date;
		$this->status = $status;
		$this->last_online = $last_online;
		$this->language = $language;
		$this->country = $country;
		$this->city = $city;
		$this->shire = $shire;
		$this->address = $address;
		$this->postal_code = $postal_code;
		$this->registered = $registered;
		$this->registration_verification_status = $registration_verification_status;
		$this->registration_verification_date = $registration_verification_date;
		$this->registration_code = $registration_code;
		$this->reason = $reason;
		$this->personal_data = $personal_data;
		$this->phone = $phone_number;
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
	 * @return StatusCode - The status of the user.
	 */
	public function status() : StatusCode{
		return $this->status;
	}
	
	/** Function name: verificationDate
	 *
	 * This is the getter for registration_verification_date.
	 *
	 * @return date - The registration verification date of the user.
	 */
	public function verificationDate(){
		return $this->registration_verification_date;
	}
	
	/** Function name: verified
	 *
	 * This is the getter for registration_verification_status.
	 *
	 * @return bool - User verification status.
	 */
	public function verified() : bool{
		return $this->registration_verification_status;
	}
	
	/** Function name: registrationCode
	 *
	 * This is the getter for registration_code.
	 *
	 * @return string - The registration code of the user.
	 */
	public function registrationCode() : string{
		return $this->registration_code;
	}
	
	/** Function name: country
	 *
	 * This is the getter for country.
	 *
	 * @return string - The country part of the user's address.
	 */
	public function country() : string{
		return $this->country;
	}
	
	/** Function name: city
	 *
	 * This is the getter for city.
	 *
	 * @return string - The city part of the user's address.
	 */
	public function city() : string{
		return $this->city;
	}
	
	/** Function name: shire
	 *
	 * This is the getter for shire.
	 *
	 * @return string - The shire part of the user's address.
	 */
	public function shire() : string{
		return $this->shire;
	}
	
	/** Function name: address
	 *
	 * This is the getter for address.
	 *
	 * @return string - The address part of the user's address.
	 */
	public function address() : string{
		return $this->address;
	}
	
	/** Function name: postalCode
	 *
	 * This is the getter for postal_code.
	 *
	 * @return string - The postal code part of the user's address.
	 */
	public function postalCode() : string{
		return $this->postal_code;
	}
	
	/** Function name: phoneNumber
	 *
	 * This is the getter for phone.
	 *
	 * @return string - The user's phone number.
	 */
	public function phoneNumber() : string{
		return $this->phone;
	}
	
	/** Function name: reason
	 *
	 * This is the getter for reason.
	 *
	 * @return string|null - The user's reason of registration.
	 */
	public function reason(){
		return $this->reason;
	}
	
	/** Function name: collegistData
	 *
	 * This is the getter for personal_data.
	 *
	 * @return PersonalData|null - The user's collegist data.
	 */
	public function collegistData(){
		return $this->personal_data;
	}
	
	/** Function name: __toString
	 *
	 * This is for identifying as a string.
	 *
	 * @return string - The username identifier.
	 */
	public function __toString(){
		return $this->username;
	}

}