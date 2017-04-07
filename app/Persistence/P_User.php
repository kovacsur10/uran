<?php

namespace App\Persistence;

use DB;
use App\Classes\Data\StatusCode;
use App\Classes\Data\Permission;
use App\Classes\Data\User;
use App\Classes\Data\PersonalData;
use App\Classes\Data\Faculty;
use App\Classes\Data\Workshop;
use App\Classes\Data\PermissionGroup;
use App\Classes\Database;
use App\Classes\Data\LanguageExam;

/** Class name: P_User
 *
 * This class is the database persistence layer class
 * for the user related tables.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class P_User{
	
	/** Function name: saveSession
	 *
	 * This function saves the session data for the user.
	 *
	 * @param int $userId - user's identifier
	 * @param arrayofMixed $dataToSave - the saveable data
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function saveSession($userId, $dataToSave){ //TODO: test
		if($userId !== null && $dataToSave !== null){
			Database::transaction(function() use($userId, $dataToSave){
				DB::table('saved_session_data')
					->where('userid', '=', $userId)
					->delete();
				foreach($dataToSave as $key => $value){
					DB::table('saved_session_data')
						->insert([
							'userid' => $userId,
							'key' => $key,
							'value' => $value
						]);
				}
			});
		}
	}
	
	/** Function name: loadSession
	 *
	 * This function loads the saved session data for the user.
	 *
	 * @param int $userId - user's identifier
	 * @return array of mixed - the loadable session data
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function loadSession($userId){ //TODO: test
		if($userId !== null){
			$retData = [];
			$data = DB::table('saved_session_data')
				->where('userid', '=', $userId)
				->get();
			foreach($data as $row){
				$retData[$row->key] = $row->value;
			}
			return $retData;
		}
	}
	
	/** Function name: getUserWorkshops
	 *
	 * This function returns the requested user's
	 * workshops.
	 *
	 * @param int $userId - user's identifier
	 * @return array of Workshop
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getUserWorkshops($userId){
		$workshopsData = DB::table('user_workshops')
			->join('workshops', 'workshops.id', '=', 'user_workshops.workshop_id')
			->where('user_workshops.user_id', '=', $userId)
			->select('workshops.id as id', 'workshops.name as name')
			->get();
		$workshops = [];
		foreach($workshopsData as $workshop){
			$workshops[] = new Workshop($workshop->id, $workshop->name);
		}
		return $workshops;
	}
	
	/** Function name: setUserWorkshops
	 *
	 * This function sets the user's workshops.
	 *
	 * @param int $userId - user's identifier
	 * @param arrayOfInt $workshopIds - user's worshops' identifiers
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function setUserWorkshops($userId, $workshopIds){
		if($workshopIds !== null){
			Database::transaction(function() use($userId, $workshopIds){
				DB::table('user_workshops')
					->where('user_id', '=', $userId)
					->delete();
				foreach($workshopIds as $workshopId){
					DB::table('user_workshops')
						->insert([
							'user_id' => $userId,
							'workshop_id' => $workshopId
						]);
				}
			});
		}
	}
	
	/** Function name: getUserFaculties
	 *
	 * This function returns the requested user's
	 * faculties.
	 *
	 * @param int $userId - user's identifier
	 * @return array of Faculty
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getUserFaculties($userId){	
		$facultiesData = DB::table('user_faculties')
			->join('faculties', 'faculties.id', '=', 'user_faculties.faculty_id')
			->select('faculties.id as id', 'faculties.name as name')
			->where('user_faculties.user_id', '=', $userId)
			->get();
		$faculties = [];
		foreach($facultiesData as $faculty){
			$faculties[] = new Faculty($faculty->id, $faculty->name);
		}
		return $faculties;
	}
	
	/** Function name: setUserFaculties
	 *
	 * This function sets the user's faculties.
	 *
	 * @param int $userId - user's identifier
	 * @param arrayOfInt $facultiesIds - user's faculties' identifiers
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function setUserFaculties($userId, $facultiesIds){
		if($facultiesIds !== null){
			Database::transaction(function() use($userId, $facultiesIds){
				DB::table('user_faculties')
					->where('user_id', '=', $userId)
					->delete();
				foreach($facultiesIds as $facultyId){
					DB::table('user_faculties')
						->insert([
								'user_id' => $userId,
								'faculty_id' => $facultyId
						]);
				}
			});
		}
	}
	
	/** Function name: getUserLanguageExams
	 *
	 * This function returns the requested user's
	 * language exams.
	 *
	 * @param int $userId - user's identifier
	 * @return arrayOfLanguageExam
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getUserLanguageExams($userId){ //TODO: test
		$examsData = DB::table('language_exams')
			->join('fonal_languages', 'fonal_languages.id', '=', 'language_exams.language')
			->join('language_exam_levels', 'language_exam_levels.id', '=', 'language_exams.level')
			->select('language_exams.id as id', 'fonal_languages.language as language', 'language_exams.resolved as resolved', 'language_exam_levels.level as level')
			->where('language_exams.user_id', '=', $userId)
			->get();
		$exams = [];
		foreach($examsData as $exam){
			$examFilesData = DB::table('language_exam_files')
				->where('examid', '=', $exam->id)
				->pluck('image');
			$exams[] = new LanguageExam($exam->id, $exam->language, $exam->level, $exam->resolved, $examFilesData);
		}
		return $exams;
	}

	/** Function name: addLanguageExamImage
	 *
	 * This function saves a language exam into the database.
	 *
	 * @param int $languageExamId - language exam identifier
	 * @param string $imageLocationPath - path to the image location
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function addLanguageExamImage(int $languageExamId, string $imageLocationPath){ //TODO: test
		DB::table('language_exam_files')
			->insert([
					'examid' => $languageExamId,
					'image' => $imageLocationPath
			]);
	}
	
	/** Function name: getUsersWithPermission
	 *
	 * This function returns those users, who
	 * have the requested permission.
	 *
	 * @param text $permissionName - permission text identifier
	 * @return array of User
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getUsersWithPermission($permissionName){
		$getUsers = DB::table('users')
			->join('registrations', 'user_id', '=', 'users.id')
			->join('user_permissions', 'user_permissions.user_id', '=', 'users.id')
			->join('permissions', 'permissions.id', '=', 'user_permissions.permission_id')
			->join('user_status_codes', 'user_status_codes.id', '=', 'users.status')
			->where('permissions.permission_name', 'LIKE', $permissionName)
			->where('users.registered', '=', true)
			->select('users.*', 'registrations.*', 'user_status_codes.status_name as status_name')
			->distinct()
			->get();
		$users = [];
		foreach($getUsers as $user){
			if($user->neptun !== null){
				$faculties = P_User::getUserFaculties($user->id);
				$workshops = P_User::getUserWorkshops($user->id);
				$exams = P_User::getUserLanguageExams($user->id);
				$collegistData = new PersonalData($user->neptun, $user->city_of_birth, $user->date_of_birth, $user->name_of_mother, $user->high_school, $user->year_of_leaving_exam, $user->from_year, $faculties, $workshops, $exams);
			}else{
				$collegistData = null;
			}
			$users[] = new User($user->id, $user->name, $user->username, $user->password, $user->email, $user->registration_date, new StatusCode($user->status, $user->status_name), $user->last_online, $user->language, $user->registered, $user->verified, $user->verification_date, $user->code, $user->country, $user->city, $user->shire, $user->address, $user->postalcode, $user->reason, $collegistData, $user->phone, $user->unsubscribe_from_alumni);
		}
		//get groups
		$getUsers = DB::table('users')
			->join('registrations', 'user_id', '=', 'users.id')
			->join('user_groups', 'user_groups.user_id', '=', 'users.id')
			->join('groups', 'groups.id', '=', 'user_groups.group_id')
			->join('group_permissions', 'group_permissions.group_id', '=', 'groups.id')
			->join('permissions', 'permissions.id', '=', 'group_permissions.permission_id')
			->join('user_status_codes', 'user_status_codes.id', '=', 'users.status')
			->where('permissions.permission_name', 'LIKE', $permissionName)
			->where('users.registered', '=', true)
			->select('users.*', 'registrations.*', 'user_status_codes.status_name as status_name')
			->distinct()
			->get();
		foreach($getUsers as $user){
			if($user->neptun !== null){
				$faculties = P_User::getUserFaculties($user->id);
				$workshops = P_User::getUserWorkshops($user->id);
				$exams = P_User::getUserLanguageExams($user->id);
				$collegistData = new PersonalData($user->neptun, $user->city_of_birth, $user->date_of_birth, $user->name_of_mother, $user->high_school, $user->year_of_leaving_exam, $user->from_year, $faculties, $workshops, $exams);
			}else{
				$collegistData = null;
			}
			$newUser = new User($user->id, $user->name, $user->username, $user->password, $user->email, $user->registration_date, new StatusCode($user->status, $user->status_name), $user->last_online, $user->language, $user->registered, $user->verified, $user->verification_date, $user->code, $user->country, $user->city, $user->shire, $user->address, $user->postalcode, $user->reason, $collegistData, $user->phone, $user->unsubscribe_from_alumni);
			if(!in_array($newUser, $users)){
				$users[] = $newUser;
			}
		}
		return $users;
	}
	
	/** Function name: getUserPermissions
	 *
	 * This function returns the available permissions
	 * for the requested user.
	 *
	 * @param int $userId - user's identifier
	 * @return array of Permission
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getUserPermissions($userId){
		$getPermissions = DB::table('permissions')
			->join('user_permissions', 'permissions.id', '=', 'user_permissions.permission_id')
			->select('permissions.id as id', 'permission_name', 'permissions.description as description')
			->where('user_permissions.user_id', '=', $userId)
			->orderBy('id', 'asc')
			->get();
		$permissions = [];
		foreach($getPermissions as $permission){
			array_push($permissions, new Permission($permission->id, $permission->permission_name, $permission->description));
		}
		return $permissions;
	}
	
	/** Function name: removePermissionsForUser
	 *
	 * This function removes all of the permissions
	 * possessed by the requested user.
	 *
	 * @param int $userId - user's identifier
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function removePermissionsForUser($userId){
		DB::table('user_permissions')
			->where('user_id', '=', $userId)
			->delete();
	}
	
	/** Function name: addPermissionForUser
	 *
	 * This function adds a new permissions
	 * for the requested user.
	 *
	 * @param int $userId - user's identifier
	 * @param int $permissionId - permission identifier
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function addPermissionForUser($userId, $permissionId){
		DB::table('user_permissions')
			->insert([
					'user_id' => $userId,
					'permission_id' => $permissionId
			]);
	}
	
	/** Function name: getUsersWithGroup
	 *
	 * This function returns those users, who
	 * have the requested group.
	 *
	 * @param int $groupId - group identifier
	 * @return array of User
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getUsersWithGroup($groupId){
		//get groups
		$getUsers = DB::table('users')
			->join('registrations', 'user_id', '=', 'users.id')
			->join('user_groups', 'user_groups.user_id', '=', 'users.id')
			->join('groups', 'groups.id', '=', 'user_groups.group_id')
			->join('user_status_codes', 'user_status_codes.id', '=', 'users.status')
			->where('groups.id', '=', $groupId)
			->where('users.registered', '=', true)
			->select('users.*', 'registrations.*', 'user_status_codes.status_name as status_name')
			->distinct()
			->get();
		$users = [];
		foreach($getUsers as $user){
			if($user->neptun !== null){
				$faculties = P_User::getUserFaculties($user->id);
				$workshops = P_User::getUserWorkshops($user->id);
				$exams = P_User::getUserLanguageExams($user->id);
				$collegistData = new PersonalData($user->neptun, $user->city_of_birth, $user->date_of_birth, $user->name_of_mother, $user->high_school, $user->year_of_leaving_exam, $user->from_year, $faculties, $workshops, $exams);
			}else{
				$collegistData = null;
			}
			$users[] = new User($user->id, $user->name, $user->username, $user->password, $user->email, $user->registration_date, new StatusCode($user->status, $user->status_name), $user->last_online, $user->language, $user->registered, $user->verified, $user->verification_date, $user->code, $user->country, $user->city, $user->shire, $user->address, $user->postalcode, $user->reason, $collegistData, $user->phone, $user->unsubscribe_from_alumni);
		}
		return $users;
	}
	
	/** Function name: getUsersWithStatus
	 *
	 * This function returns those users, who
	 * have the requested status.
	 *
	 * @param text $statusName - status name identifier
	 * @return array of User
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getUsersWithStatus($statusName){
		$getUsers = DB::table('users')
			->join('registrations', 'user_id', '=', 'users.id')
			->join('user_status_codes', 'user_status_codes.id', '=', 'users.status')
			->where('users.registered', '=', true)
			->where('user_status_codes.status_name', 'LIKE', "%".$statusName."%")
			->select('users.*', 'registrations.*', 'user_status_codes.status_name as status_name')
			->distinct()
			->get();
		$users = [];
		foreach($getUsers as $user){
			if($user->neptun !== null){
				$faculties = P_User::getUserFaculties($user->id);
				$workshops = P_User::getUserWorkshops($user->id);
				$exams = P_User::getUserLanguageExams($user->id);
				$collegistData = new PersonalData($user->neptun, $user->city_of_birth, $user->date_of_birth, $user->name_of_mother, $user->high_school, $user->year_of_leaving_exam, $user->from_year, $faculties, $workshops, $exams);
			}else{
				$collegistData = null;
			}
			$users[] = new User($user->id, $user->name, $user->username, $user->password, $user->email, $user->registration_date, new StatusCode($user->status, $user->status_name), $user->last_online, $user->language, $user->registered, $user->verified, $user->verification_date, $user->code, $user->country, $user->city, $user->shire, $user->address, $user->postalcode, $user->reason, $collegistData, $user->phone, $user->unsubscribe_from_alumni);
		}
		return $users;
	}
	
	/** Function name: getExtraAlumniMembers
	 *
	 * This function return extra alumni mailing list members.
	 *
	 * @return array of User
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getExtraAlumniMembers(){
		$getUsers = DB::table('alumni_list_extra_members')
			->get();
		$users = [];
		foreach($getUsers as $user){
			$collegistData = null;
			$users[] = new User(0, $user->name, "", "", $user->email, "", new StatusCode(0, ""), "", "", false, false, "", "", "", "", "", "", "", "", $collegistData, "", false);
		}
		return $users;
	}
	
	/** Function name: getUserPermissionGroups
	 *
	 * This function returns the available groups
	 * for the requested user.
	 *
	 * @param int $userId - user's identifier
	 * @return array of PermissionGroup
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getUserPermissionGroups($userId){
		$groupsRaw = DB::table('user_groups')
			->join('groups', 'groups.id', '=', 'user_groups.group_id')
			->where('user_groups.user_id', '=', $userId)
			->select('groups.*')
			->get();
		$groups = [];
		foreach($groupsRaw as $group){
			$rawPermissions = DB::table('permissions')
				->join('group_permissions', 'group_permissions.permission_id', '=', 'permissions.id')
				->where('group_permissions.group_id', '=', $group->id)
				->select('permissions.*')
				->get();
			$permissions = [];
			foreach($rawPermissions as $permission){
				$permissions[] = new Permission($permission->id, $permission->permission_name, $permission->description);
			}
			$groups[] = new PermissionGroup($group->id, $group->group_name, $permissions);
		}
		return $groups;
	}
	
	/** Function name: setUserPermissionGroups
	 *
	 * This function sets the user the provided
	 * permission groups. The rest will be deleted,
	 * if they were set.
	 *
	 * @param int $userId - user's identifier
	 * @param arrayOfInt $permissionGroupIds - array of permission group identifiers
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function setUserPermissionGroups($userId, $permissionGroupIds){
		Database::transaction(function() use($userId, $permissionGroupIds){
			DB::table('user_groups')
				->where('user_id', '=', $userId)
				->delete();
			foreach($permissionGroupIds as $groupId){
				DB::table('user_groups')
					->insert([
							'user_id' => $userId,
							'group_id' => $groupId
					]);
			}
		});
	}
	
	/** Function name: updateUserLoginTime
	 * 
	 * This function updates the user's
	 * last login time to the requested value.
	 * 
	 * @param text $username - user text identifier
	 * @param datetime $datetime - login time
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function updateUserLoginTime($username, $datetime){
		DB::table('users')
			->where('username', 'LIKE', $username)
			->update([
				'last_online' => $datetime
			]);
	}
	
	/** Function name: updateUserPassword
	 * 
	 * This function updates the requested user's
	 * password for the given value.
	 * 
	 * @param text $username - user text identifier
	 * @param text $password - new password
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function updateUserPassword($username, $password){
		DB::table('users')
			->where('username', 'LIKE', $username)
			->update([
				'password' => $password
			]);
	}
	
	/** Function name: updateUserLanguage
	 *
	 * This function updates the requested user's
	 * default language.
	 *
	 * @param text $userId - user's identifier
	 * @param text $langId - language identifier
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function updateUserLanguage($userId, $langId){
		DB::table('users')
			->where('id', '=', $userId)
			->update([
					'language' => $langId
			]);
	}
	
	/** Function name: getUserById
	 *
	 * This function returns the user, who
	 * has the requested user identifier.
	 *
	 * @param int $userId - user's identifier
	 * @return User|null
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getUserById($userId){
		$user = DB::table('users')
			->join('user_status_codes', 'user_status_codes.id', '=', 'users.status')
			->join('registrations', 'registrations.user_id', '=', 'users.id')
			->where('users.id', '=', $userId)
			->when($userId !== 0, function($query){
				return $query->where('users.registered', '=', true);
			})
			->select('users.*', 'registrations.*', 'user_status_codes.status_name as status_name')
			->first();
		if(isset($user->neptun) && $user->neptun !== null){
			$faculties = P_User::getUserFaculties($user->id);
			$workshops = P_User::getUserWorkshops($user->id);
			$exams = P_User::getUserLanguageExams($user->id);
			$collegistData = new PersonalData($user->neptun, $user->city_of_birth, $user->date_of_birth, $user->name_of_mother, $user->high_school, $user->year_of_leaving_exam, $user->from_year, $faculties, $workshops, $exams);
		}else{
			$collegistData = null;
		}
		return $user === null ? null : new User($user->id, $user->name, $user->username, $user->password, $user->email, $user->registration_date, new StatusCode($user->status, $user->status_name), $user->last_online, $user->language, $user->registered, $user->verified, $user->verification_date, $user->code, $user->country, $user->city, $user->shire, $user->address, $user->postalcode, $user->reason, $collegistData, $user->phone, $user->unsubscribe_from_alumni);
	}
	
	/** Function name: getUserByUsername
	 *
	 * This function returns the user, who
	 * has the requested username as text
	 * identifier value.
	 * 
	 * @param text $username - user's text identifier
	 * @return User|null
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getUserByUsername($username){
		$user = DB::table('users')
			->join('user_status_codes', 'user_status_codes.id', '=', 'users.status')
			->join('registrations', 'registrations.user_id', '=', 'users.id')
			->where('users.username', 'LIKE', $username)
			->when($username != "admin", function($query){
				return $query->where('users.registered', '=', true);
			})
			->select('users.*', 'registrations.*', 'user_status_codes.status_name as status_name')
			->first();
		if($user->neptun !== null){
			$faculties = P_User::getUserFaculties($user->id);
			$workshops = P_User::getUserWorkshops($user->id);
			$exams = P_User::getUserLanguageExams($user->id);
			$collegistData = new PersonalData($user->neptun, $user->city_of_birth, $user->date_of_birth, $user->name_of_mother, $user->high_school, $user->year_of_leaving_exam, $user->from_year, $faculties, $workshops, $exams);
		}else{
			$collegistData = null;
		}
		return $user === null ? null : new User($user->id, $user->name, $user->username, $user->password, $user->email, $user->registration_date, new StatusCode($user->status, $user->status_name), $user->last_online, $user->language, $user->registered, $user->verified, $user->verification_date, $user->code, $user->country, $user->city, $user->shire, $user->address, $user->postalcode, $user->reason, $collegistData, $user->phone, $user->unsubscribe_from_alumni);
	}

	/** Function name: getUsers
	 * 
	 * This function returns user data. First
	 * it skip the requested number of user, then
	 * it returns maximum the requested number of
	 * users.
	 * 
	 * @param int $skipped - first n skipped user
	 * @param int $taken - maximum returned users
	 * @return array of Users
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getUsers($skipped = 0, $taken = -1){
		$getUsers = DB::table('users')
			->join('user_status_codes', 'user_status_codes.id', '=', 'users.status')
			->join('registrations', 'registrations.user_id', '=', 'users.id')
			->where('registered', '=', true)
			->select('users.*', 'registrations.*', 'user_status_codes.status_name as status_name')
			->orderBy('name', 'asc')
			->skip($skipped)
			->when($taken > -1, function($query) use ($taken){
				return $query->take($taken);
			})
			->get();
		$users = [];
		foreach($getUsers as $user){
			if($user->neptun !== null){
				$faculties = P_User::getUserFaculties($user->id);
				$workshops = P_User::getUserWorkshops($user->id);
				$exams = P_User::getUserLanguageExams($user->id);
				$collegistData = new PersonalData($user->neptun, $user->city_of_birth, $user->date_of_birth, $user->name_of_mother, $user->high_school, $user->year_of_leaving_exam, $user->from_year, $faculties, $workshops, $exams);
			}else{
				$collegistData = null;
			}
			array_push($users, new User($user->id, $user->name, $user->username, $user->password, $user->email, $user->registration_date, new StatusCode($user->status, $user->status_name), $user->last_online, $user->language, $user->registered, $user->verified, $user->verification_date, $user->code, $user->country, $user->city, $user->shire, $user->address, $user->postalcode, $user->reason, $collegistData, $user->phone, $user->unsubscribe_from_alumni));
		}
		return $users;
	}
	
	/** Function name: getStatusCodes
	 *
	 * This function returns the existing status
	 * codes of a user from the database.
	 *
	 * @return array of StatusCode
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getStatusCodes(){
		$getStatusCodes = DB::table('user_status_codes')
			->orderBy('id', 'asc')
			->get();
		$statusCodes = [];
		foreach($getStatusCodes as $statusCode){
			array_push($statusCodes, new StatusCode($statusCode->id, $statusCode->status_name));
		}
		return $statusCodes;
	}
	/** Function name: getStatusCodeByName
	 *
	 * This function returns the requested status.
	 *
	 * @param text $statusName - status text identifier
	 * @return StatusCode|null
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getStatusCodeByName($statusName){
		$statusCode = DB::table('user_status_codes')
			->where('status_name', 'LIKE', $statusName)
			->first();
		return $statusCode === null ? null : new StatusCode($statusCode->id, $statusCode->status_name);
	}
	
//REGISTRATION USER

	/** Function name: getRegistrationUserById
	 *
	 * This function returns the requested registration
	 * user.
	 *
	 * @param int $userId - user's identifier
	 * @return User|null
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getRegistrationUserById($userId){	
		$user = DB::table('users')
			->join('registrations', 'registrations.user_id', '=', 'users.id')
			->join('user_status_codes', 'user_status_codes.id', '=', 'users.status')
			->where('users.registered', '=', false)
			->where('users.id', '=', $userId)
			->select('users.*', 'registrations.*', 'user_status_codes.status_name as status_name')
			->first();
		if($user->neptun !== null){
			$faculties = P_User::getUserFaculties($user->id);
			$workshops = P_User::getUserWorkshops($user->id);
			$exams = P_User::getUserLanguageExams($user->id);
			$collegistData = new PersonalData($user->neptun, $user->city_of_birth, $user->date_of_birth, $user->name_of_mother, $user->high_school, $user->year_of_leaving_exam, $user->from_year, $faculties, $workshops, $exams);
		}else{
			$collegistData = null;
		}
		return $user === null ? null : new User($user->id, $user->name, $user->username, $user->password, $user->email, $user->registration_date, new StatusCode($user->status, $user->status_name), $user->last_online, $user->language, $user->registered, $user->verified, $user->verification_date, $user->code, $user->country, $user->city, $user->shire, $user->address, $user->postalcode, $user->reason, $collegistData, $user->phone, $user->unsubscribe_from_alumni);
	}
	
	/** Function name: getRegistrationUserIdForUsername
	 *
	 * This function returns the requested registration
	 * user's identifier.
	 *
	 * @param text $username - user's text identifier
	 * @return int|null
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getRegistrationUserIdForUsername($username){
		return DB::table('users')
			->where('users.registered', '=', false)
			->where('users.username', 'LIKE', $username)
			->value('id');
	}
	
	/** Function name: getRegistrationUsers
	 *
	 * This function returns all of the registration
	 * users.
	 *
	 * @return array of User
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getRegistrationUsers(){
		$getUsers = DB::table('users')
			->join('registrations', 'registrations.user_id', '=', 'users.id')
			->join('user_status_codes', 'user_status_codes.id', '=', 'users.status')
			->where('users.registered', '=', false)
			->where('users.id', '!=', 0)
			->orderBy('users.name', 'asc')
			->select('registrations.*', 'user_status_codes.*', 'users.*', 'users.id as id')
			->get();
		$users = [];
		foreach($getUsers as $user){
			if($user->neptun !== null){
				$faculties = P_User::getUserFaculties($user->id);
				$workshops = P_User::getUserWorkshops($user->id);
				$exams = P_User::getUserLanguageExams($user->id);
				$collegistData = new PersonalData($user->neptun, $user->city_of_birth, $user->date_of_birth, $user->name_of_mother, $user->high_school, $user->year_of_leaving_exam, $user->from_year, $faculties, $workshops, $exams);
			}else{
				$collegistData = null;
			}
			array_push($users, new User($user->id, $user->name, $user->username, $user->password, $user->email, $user->registration_date, new StatusCode($user->status, $user->status_name), $user->last_online, $user->language, $user->registered, $user->verified, $user->verification_date, $user->code, $user->country, $user->city, $user->shire, $user->address, $user->postalcode, $user->reason, $collegistData, $user->phone, $user->unsubscribe_from_alumni));
		}
		return $users;
	}
	
	/** Function name: addRegistrationData
	 * 
	 * This function adds a new user line with
	 * the given data to the database.
	 * 
	 * @param text $username
	 * @param text $password
	 * @param text $email
	 * @param text $name
	 * @param text $country
	 * @param text $shire
	 * @param text $postalCode
	 * @param text $address
	 * @param text $city
	 * @param text $reason
	 * @param text $phoneNumber
	 * @param text $defaultLanguage
	 * @param text $cityOfBirth
	 * @param datetime $dateOfBirth
	 * @param text $nameOfMother
	 * @param int $yearOfLeavingExam
	 * @param text $highSchool
	 * @param text $neptun
	 * @param int $applicationYear
	 * @param arrayOfInt $faculties
	 * @param arrayOfInt $workshops
	 * @param datetime $date
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function addRegistrationData($username, $password, $email, $name, $country, $shire, $postalCode, $address, $city, $reason, $phoneNumber, $defaultLanguage, $cityOfBirth, $dateOfBirth, $nameOfMother, $yearOfLeavingExam, $highSchool, $neptun, $applicationYear, $faculties, $workshops, $date){
		Database::transaction(function() use($username, $password, $email, $name, $country, $shire, $postalCode, $address, $city, $reason, $phoneNumber, $defaultLanguage, $cityOfBirth, $dateOfBirth, $nameOfMother, $yearOfLeavingExam, $highSchool, $neptun, $applicationYear, $faculties, $workshops, $date){
			DB::table('users')
				->insert([
						'username' => $username,
						'password' => $password,
						'email' => $email,
						'name' => $name,
						'registration_date' => $date,
						'country' => $country,
						'shire' => $shire,
						'postalcode' => $postalCode,
						'address' => $address,
						'city' => $city,
						'reason' => $reason,
						'phone' => $phoneNumber,
						'language' => $defaultLanguage,
						'city_of_birth' => $cityOfBirth,
						'date_of_birth' => $dateOfBirth,
						'name_of_mother' => $nameOfMother,
						'year_of_leaving_exam' => $yearOfLeavingExam,
						'high_school' => $highSchool,
						'neptun' => $neptun,
						'from_year' => $applicationYear,
				]);
			$userId = P_User::getRegistrationUserIdForUsername($username);
			P_User::setUserFaculties($userId, $faculties);
			P_User::setUserWorkshops($userId, $workshops);
		});
	}
	
	/** Function name: addRegistrationCodeEntry
	 * 
	 * This function creates an entry with the
	 * requested code and user id to the registrations
	 * database table.
	 * 
	 * @param int $userId - user's identifier
	 * @param text $code - registration code
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function addRegistrationCodeEntry($userId, $code){
		DB::table('registrations')
			->insert([
					'user_id' => $userId,
					'code' => $code,
			]);
	}

	/** Function name: verifyRegistrationUser
	 *
	 * This function returns all of the registration
	 * users.
	 *
	 * @param text $code - verification code
	 * @param datetime $time - verification time
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function verifyRegistrationUser($code, $time){
		DB::table('registrations')
			->where('code', 'LIKE', $code)
			->where('verified', '=', false)
			->update([
					'verified' => true,
					'verification_date' => $time
			]);
	}
	
	/** Function name: promoteRegistrationUserToUser
	 * 
	 * This function sets the user registration flag to
	 * 'true' and updates the valid data.
	 * 
	 * @param int $userId
	 * @param text $country
	 * @param text $shire
	 * @param text $postalCode
	 * @param text $address
	 * @param text $city
	 * @param text $phone
	 * @param text $reason
	 * @param text $cityOfBirth
	 * @param datetime $dateOfBirth
	 * @param text $nameOfMother
	 * @param int $yearOfLeavingExam
	 * @param text $highSchool
	 * @param text $neptunCode
	 * @param int $applicationYear
	 * @param arrayOfInt $faculties
	 * @param arrayOfInt $workshops
	 * @param int $status
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function promoteRegistrationUserToUser($userId, $country, $shire, $postalCode, $address, $city, $phone, $reason, $cityOfBirth, $dateOfBirth, $nameOfMother, $yearOfLeavingExam, $highSchool, $neptunCode, $applicationYear, $faculties, $workshops, $status){
		Database::transaction(function() use($userId, $country, $shire, $postalCode, $address, $city, $phone, $reason, $cityOfBirth, $dateOfBirth, $nameOfMother, $yearOfLeavingExam, $highSchool, $neptunCode, $applicationYear, $faculties, $workshops, $status){
			DB::table('users')
				->where('id', '=', $userId)
				->where('registered', '=', false)
				->update([
						'registered' => true,
						'country' => $country,
						'shire' => $shire,
						'postalcode' => $postalCode,
						'address' => $address,
						'city' => $city,
						'phone' => $phone,
						'reason' => $reason,
						'city_of_birth' => $cityOfBirth,
						'name_of_mother' => $nameOfMother,
						'date_of_birth' => $dateOfBirth,
						'year_of_leaving_exam' => $yearOfLeavingExam,
						'high_school' => $highSchool,
						'neptun' => $neptunCode,
						'from_year' => $applicationYear,
						'status' => $status,
				]);
			P_User::setUserFaculties($userId, $faculties);
			P_User::setUserWorkshops($userId, $workshops);
		});
	}
	
	/** Function name: removeRegistrationUser
	 *
	 * This function removes the requested 
	 * registration user.
	 *
	 * @param int $userId - user's identifier
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function removeRegistrationUser($userId){
		DB::table('users')
			->where('id', '=', $userId)
			->where('registered', '=', false)
			->where('id', '!=', 0)
			->delete();
	}
}
