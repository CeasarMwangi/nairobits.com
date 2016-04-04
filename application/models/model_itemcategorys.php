
<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
*
*/
class Model_itemcategorys extends MY_Model
{
	protected $_table = 'itemcategorys';
	protected $primary_key = 'itemcategory_id';
	protected $return_type = 'array';

	protected $after_get = array('remove_sensitive_data');//function to call before get
	protected $before_create = array('prep_data');//function to call before create
	protected $before_update = array('updated_timestamp');//function to call before update/delete


	/*
	remove sensitive data like password account # etc that you dont want to be sent to the user
	*/
	protected function remove_sensitive_data($itemcategory){
		//unset($itemcategory['itemcategory_id']);
		return $itemcategory;
	}

	// observer function
	//prepare data before insertion
	protected function prep_data($itemcategory){
		$itemcategory['created_at']=date('Y-m-d H:i:s');
		return $itemcategory;
	}

	//observer
	protected function updated_timestamp($itemcategory){
		$itemcategory['updated_at']=date('Y-m-d H:i:s');
		return $itemcategory;
	}

}