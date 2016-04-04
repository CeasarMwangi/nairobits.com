
<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
* 
*/
class Model_users extends MY_Model
{
	protected $_table = 'users';
	protected $primary_key = 'user_id';
	protected $return_type = 'array';

	protected $after_get = array('remove_sensitive_data');//function to call before get
	protected $before_create = array('prep_data');//function to call before create
	protected $before_update = array('update_timestamp');//function to call before update/delete


	protected function remove_sensitive_data($user){
		unset($user['password']);
		return $user;
	}

	//observer
	protected function prep_data($user){
		$user['password']=md5($user['password']);
		$user['created_timestamp']=date('Y-m-d H:i:s');
		return $user;
	}

	//observer
	protected function update_timestamp($user){
		$user['updated_timestamp']=date('Y-m-d H:i:s');
		return $user;
	}

}