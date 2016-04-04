
<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
*
*/
class Model_centers extends MY_Model
{
	protected $_table = 'centers';
	protected $primary_key = 'center_id';
	protected $return_type = 'array';

	protected $after_get = array('remove_sensitive_data');//function to call before get
	protected $before_create = array('prep_data');//function to call before create
	protected $before_update = array('updated_datetime');//function to call before update/delete


	/*
	remove sensitive data like password account # etc that you dont want to be sent to the user
	*/
	protected function remove_sensitive_data($center){
		//unset($center['center_id']);
		return $center;
	}

	// observer function
	//prepare data before insertion
	protected function prep_data($center){
		$center['created_datetime']=date('Y-m-d H:i:s');
		return $center;
	}

	//observer
	protected function updated_datetime($center){
		$center['updated_datetime']=date('Y-m-d H:i:s');
		return $center;
	}

}