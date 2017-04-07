<?php

namespace App\Classes\Layout;

use App\Classes\Layout\Permissions;
use App\Classes\Data\Permission;
use App\Classes\Notifications;
use App\Persistence\P_User;
use App\Exceptions\UserNotFoundException;
use App\Classes\Interfaces\Pageable;
use App\Classes\Logger;
use App\Exceptions\ValueMismatchException;
use App\Exceptions\DatabaseException;
use App\Classes\Data\User as UserData;
use App\Classes\Data\StatusCode;
use Illuminate\Http\UploadedFile;
use App\Exceptions\FileException;

/** Class name: User
 *
 * This class handles the default
 * user database functionality.
 *
 * Functionality:
 * 		- get user data
 * 		- get user permissions
 * 		- get all user's data
 * 		- get notifications for user
 * 
 * Functions that can throw exceptions:
 * 
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class User extends Pageable{
	
// PRIVATE DATA
	
	private $user;
	private $permissions;
	private $notifications;
	private $unreadNotificationCount;

// PUBLIC FUNCTIONS
	
	/** Function name: __construct
	 *
	 * The constructor for the User class.
	 * 
	 * @param int $userId - user's identifier
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function __construct($userId = 0){
		try{
			$this->user = $this->getUserData($userId);
		}catch(\Exception $ex){
			$this->user = null;
		}
		$tmpPermissions = new Permissions();
		$this->permissions = $tmpPermissions->getForUser($userId);
		try{
			$this->notifications = Notifications::getNotifications($userId);
			$this->unreadNotificationCount = Notifications::getUnreadNotificationCount($userId);
		}catch(\Exception $ex){
			$this->notifications = [];
			$this->unreadNotificationCount = 0;
		}
	}
	
	/** Function name: user
	 *
	 * Getter function for user.
	 * 
	 * @return User
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function user(){
		return $this->user;
	}
	
	/** Function name: users
	 *
	 * This function returns a part
	 * of the users from the first
	 * requested user.
	 * 
	 * @param int $from - identifier of first users
	 * @param int $count - count of users
	 * @return array of Users
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function users($from = 0, $count = 50){
		try{
			$users = P_User::getUsers($from, $count);
		}catch(\Exception $ex){
			$users = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'users' was not successful! ".$ex->getMessage());
		}
		return $users;
	}
	
	/** Function name: permissions
	 *
	 * Getter function for permissions.
	 * 
	 * @return array of Permissions
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function permissions(){
		return $this->permissions;
	}
	
	/** Function name: notificationCount
	 *
	 * Getter function for notification count.
	 * 
	 * @return int - count
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function notificationCount(){
		return count($this->notifications);
	}
	
	/** Function name: unreadNotificationCount
	 *
	 * Getter function for unread notifications.
	 *
	 * @return array of Notifications
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function unreadNotificationCount(){
		return $this->unreadNotificationCount;
	}
	
	/** Function name: notifications
	 *
	 * This function returns the notifications.
	 * 
	 * @param int $from - first notification
	 * @param int $count - count of notifications
	 * @return array of Notifications
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function notifications($from = 0, $count = 5){
		return $this->toPages($this->notifications, $from, $count);
	}
		
	/** Function name: permitted
	 *
	 * This function indicates whether
	 * the current user has a the requested
	 * permission or not.
	 * 
	 * @param text $what - permission short identifier
	 * @return bool - permitted or not
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function permitted($what){
		foreach($this->permissions as $permission){
			if($permission->name() === $what){
				return true;
			}
		}
		return false;
	}
	
	/** Function name: getUserData
	 *
	 * This function returns the
	 * requested user's data.
	 * 
	 * @param int $userId - user's identifier
	 * @return User|null - user data
	 * 
	 * @throws UserNotFoundException when the user cannot be found or a database error occurs.
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function getUserData($userId){
		try{
			$user = P_User::getUserById($userId);
		}catch(\Exception $ex){
			$user = null;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
		}
		if($user === null){
			throw new UserNotFoundException();
		}
		return $user;
	}
	
	/** Function name: getUserDataByUsername
	 *
	 * This function returns the requested user's full data.
	 * Not only the user table, but it joins a lot more table
	 * and gives all the informations stored in the database
	 * about the target. (Excluded the modules.)
	 * 
	 * @param text $username - user's name
	 * @return User the requested user's data
	 * 
	 * @throws UserNotFoundException when the user cannot be found or a database error occurs.
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function getUserDataByUsername($username){
		if($username === '' || $username === null){
			throw new UserNotFoundException();
		}
		try{
			$user = P_User::getUserByUsername($username);
		}catch(\Exception $ex){
			$user = null;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
		}
		if($user === null){
			throw new UserNotFoundException();
		}
		return $user;
	}
	
	/** Function name: saveUserLanguage
	 *
	 * This function updates the user default language.
	 * 
	 * @param text $lang - language identifier
	 * 
	 * @throws ValueMismatchException when the provided language code in not supported.
	 * @throws DatabaseException when the update fails.
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function saveUserLanguage($lang){
		if($lang == 'hu_HU' || $lang == 'en_US'){
			try{
				P_User::updateUserLanguage($this->user->id(), $lang);
			}catch(\Exception $ex){
				Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
				throw new DatabaseException("Language update failed!");
			}
		}else{
			throw new ValueMismatchException("The given language is not supported!");
		}
	}
	
	/** Function name: getForMembraMailingList
	 *
	 * This function returns the user's who should be
	 * the member of the membraCollegii mailing list.
	 *
	 * @param text|null $onTheList - the actual list members
	 * @return arrayOfUser - the should be members of the mailing list
	 *
	 * @throws DatabaseException when the user getting phase fails.
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function getForMembraMailingList($onTheList){
		$parsed = User::preprocessListMembers($onTheList);
		try{
			$usersIntern = P_User::getUsersWithStatus('intern');
			$usersExtern = P_User::getUsersWithStatus('extern');
			$usersScholarship = P_User::getUsersWithStatus('scholarship');
			$users = array_merge($usersIntern, $usersExtern, $usersScholarship);
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
			throw new DatabaseException("Could not get the requested users!");
		}
		return User::calculateSublists($users, $parsed);
	}
	
	/** Function name: getForAlumniMailingList
	 *
	 * This function returns the user's who should be
	 * the member of the alumni mailing list.
	 *
	 * @param text|null $onTheList - the actual list members
	 * @return arrayOfUser - the should be members of the mailing list
	 *
	 * @throws DatabaseException when the user getting phase fails.
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function getForAlumniMailingList($onTheList){
		$parsed = User::preprocessListMembers($onTheList);
		try{
			$usersAlumniSource = P_User::getUsersWithStatus('alumni');
			$usersAlumni = [];
			foreach($usersAlumniSource as $user){
				if($user->subscribedToAlumniList()){
					$usersAlumni[] = $user;
				}
			}
			$usersExtra = P_User::getExtraAlumniMembers();
			$users = array_merge($usersAlumni, $usersExtra);
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
			throw new DatabaseException("Could not get the requested users!");
		}
		return User::calculateSublists($users, $parsed);
	}
	
	/** Function name: getForRgMailingList
	 *
	 * This function returns the user's who should be
	 * the member of the rendszergazda mailing list.
	 * 
	 * The return value is:
	 * Array(
	 *   "new" => arrayOfUser,
	 *   "remove" = arrayOfUser,
	 *   "alreadyMember" => arrayOfUser
	 * )
	 *
	 * @param text|null $onTheList - the actual list members
	 * @return array - the should be members of the mailing list
	 *
	 * @throws DatabaseException when the user getting phase fails.
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function getForRgMailingList($onTheList){
		$parsed = User::preprocessListMembers($onTheList);
		try{
			$users = P_User::getUsersWithStatus('intern');
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
			throw new DatabaseException("Could not get the requested users!");
		}
		return User::calculateSublists($users, $parsed);
	}
	
	
	/** Function name: isIntern
	 *
	 * This function returns whether the actual user is
	 * an intern or not.
	 *
	 * @return bool - intern or not
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function isIntern(){ //TODO: test
		$statusName = $this->user->status()->statusName();
		return ($statusName === "intern" || $statusName == "sixth year intern");
	}
	
	/** Function name: isLivingIn
	 *
	 * This function returns whether the actual user is
	 * living in the dormitory or not.
	 *
	 * @return bool - living in or not
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function isLivingIn(){ //TODO: test
		$statusName = $this->user->status()->statusName();
		return ($statusName === "intern" || $statusName == "sixth year intern" || $statusName == "visitor");
	}
	
	/** Function name: isCollegist
	 *
	 * This function returns whether the actual user is
	 * a collegist or not.
	 *
	 * @return bool - collegist or not
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function isCollegist(){ //TODO: test
		$statusName = $this->user->status()->statusName();
		return ($statusName === "intern" || $statusName == "sixth year intern" || $statusName == "extern" || $statusName === "scholarship");
	}
	
	/** Function name: uploadLanguageExamPicture
	 *
	 * This function uploads a new language exam.
	 * 
	 * @param int $examid - language exam identifier
	 * @param UploadedFile $file - the language exam picture
	 * 
	 * @throws FileException if the file upload method fails or the file is too big or the MIME type is wrong.
	 * @throws ValueMismatchException if the input values are not valid values.
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function uploadLanguageExamPicture(int $examid, UploadedFile $file){
		if($file === null || $examid === null){
			throw new ValueMismatchException("The input values cannot be null!");
		}
		if(!$file->isValid()){
			throw new FileException("An error occured during the upload stage: [".$file->getError()."] ".$file->getErrorMessage());
		}
		if($file->getClientSize() > 2097152){
			throw new FileException("File size is too big!");
		}
		$extension = $file->getClientOriginalExtension();
		$guessedExtension = $file->guessClientExtension();
		if($extension !== $guessedExtension){
			Logger::warning("Extension mismatch with guessed one! uploaded: ".$extension." guessed: ".$guessedExtension, "", "", "data/languageexam/upload");
		}
		if($extension !== "png" && $extension !== "jpg" && $extension !== "jpeg" && $extension !== "bmp" && $extension !== "pdf"){
			throw new FileException("Wrong extension type!");
		}
		try{
			$generatedFileName = $this->user->username()."_langexam_".time().".".$extension;
			$file->move("/var/www/uran/storage/app/languageexams", $generatedFileName);
			P_User::addLanguageExamImage($examid, $generatedFileName);
		}catch(\Illuminate\Database\QueryException $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
			Logger::error("There was a problem at uploading a language exam file!", "", $generatedFileName, "data/languageexam/upload");
			throw new FileException("Could not upload the file!");
		}catch(\Exception $ex){
			throw new FileException("Could not upload the file!");
		}
	}
	
// PRIVATE FUNCTIONS
	/** Function name: preprocessListMembers
	 *
	 * This function returns a parsed list, from the
	 * provided data list.
	 *
	 * @param text|null $onTheList - the actual list members
	 * @return arrayOfUser - the given list, parsed
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	private static function preprocessListMembers($onTheList){
		if($onTheList === null || is_array($onTheList)){
			return [];
		}
		preg_match_all("/(?:(.*) <(.*@.*)>)/", html_entity_decode($onTheList), $matched);
		$returnArray = [];
		if(count($matched[1]) === count($matched[2])){
			for($i = 0; $i < count($matched[1]); $i++){
				$returnArray[$matched[1][$i]] = $matched[2][$i];
			}
		}
		return $returnArray;
	}
	
	/** Function name: calculateSublists
	 *
	 * This function returns the difference of the current
	 * users and the parsed values.
	 *
	 * Array(
	 *   "new" => arrayOfUser,
	 *   "remove" = arrayOfUser,
	 *   "alreadyMember" => arrayOfUser
	 * )
	 *
	 * @param arrayOfUser $users - the actual users, who should be on the list
	 * @param arrayOfValues $parsed - the parsed user values, they were on the list
	 * @return array - the sublists (add, remove, stay)
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	private static function calculateSublists($users, $parsed){
		$alreadyOnTheList = [];
		$newToTheList = [];
		$deleteable = [];
		foreach($users as $user){
			if($parsed === [] || in_array($user->email(), $parsed)){
				$alreadyOnTheList[] = $user;
			}else{
				$newToTheList[] = $user;
			}
		}
		foreach($parsed as $name => $email){
			if(!User::emailInUsers($email, $users)){
				$deleteable[] = new UserData(0, $name, "", "", $email, "", new StatusCode(0, ""), "", "", false, false, "", "", "", "", "", "", "", "", null, "", false);
			}
		}
		return [
				"new" => $newToTheList,
				"remove" => $deleteable,
				"alreadyMember" => $alreadyOnTheList
		];
	}
	
	/** Function name: emailInUsers
	 *
	 * This function returns whethet a provided email
	 * address is in an array of users or not.
	 *
	 * @param text $email - the searched email address
	 * @param arrayOfUsers $users - the users haystack
	 * @return boolean - found or not
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	private static function emailInUsers($email, $users){
		foreach($users as $user){
			if($email === $user->email()){
				return true;
			}
		}
		return false;
	}
}
