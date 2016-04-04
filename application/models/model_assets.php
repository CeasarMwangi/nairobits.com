
<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
* 
*/
class Model_assets extends MY_Model
{
	protected $_table = 'assets';
	protected $primary_key = 'asset_id';
	protected $return_type = 'array';

	protected $after_get = array('remove_sensitive_data');//function to call before get
	protected $before_create = array('prep_data');//function to call before create
	protected $before_update = array('update_timestamp');//function to call before update/delete


	protected function remove_sensitive_data($asset){
		//unset($asset['code']);
		return $asset;
	}

	//observer
	protected function prep_data($asset){
		$asset['created_timestamp']=date('Y-m-d H:i:s');
		return $asset;
	}

	//observer
	protected function update_timestamp($asset){
		$asset['updated_timestamp']=date('Y-m-d H:i:s');
		return $asset;
	}

}