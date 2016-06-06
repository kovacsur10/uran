<?php

namespace App\Classes;

use DB;

class User{
	protected $user;
    protected $permissions;
	
	public function __construct($id){
		$this->user = $this->getUserData($id);
		$this->permissions = $this->getPermissions($id);
	}
	
	public function user(){
		return $this->user;
	}
	
	public function permissions(){
		return $this->permissions;
	}
	
	public function permitted($what){
		$i = 0;
		while($i < count($this->permissions) && $this->permissions[$i]->permission_name != $what){
			$i++;
		}
		return $i < count($this->permissions);
	}
	
	protected function getUserData($id){
		return DB::table('users')->where('id', '=', $id)
								 ->first();
	}
	
	protected function getPermissions($id){
		return DB::table('permissions')->join('user_permissions', 'permissions.id', '=', 'user_permissions.permission_id')
									   ->select('permission_name')
									   ->where('user_permissions.user_id', '=', $id)
									   ->get();
	}
}
