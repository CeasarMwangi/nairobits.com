<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Api extends REST_Controller {
    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        //load our helper class
        $this->load->helper('my_api'); //nb you dont include the _helper part in my_api_helper

    }

    //retrieve a single user with provided user id and status is active
    //http://localhost/nairobits.com/api/user/x
    //Method: GET
    //where x is the user id
    function user_get(){
        $user_id = $this->uri->segment(3);
        
        $this->load->model('model_users');
        $user = $this->model_users->get_by(array('user_id' => $user_id, 'user_status'=>'active'));


        if (isset($user['user_id'])) {
            $this->response(array('status'=>'success','message'=>$user));
        }
        else{
            $this->response(array('status'=>'failed','message'=>'No such user'),REST_Controller::HTTP_NOT_FOUND);
        }
        
    }


    //get all users whose status is active
    //get_many_by()
    function users_get(){
        $results=NULL;
        $this->load->model('model_users');
        $results = $this->model_users->get_many_by(array('user_status'=>'active'));


        if ($results != NULL) {
            $this->response(array('status'=>'success','message'=>$results));
        }
        else{
            $this->response(array('status'=>'failed','message'=>'No data fould'),REST_Controller::HTTP_NOT_FOUND);
        }
        
    }
 

    function userscenters_get(){

        $query = $this->db->query("SELECT users.user_id,users.full_name,users.username,users.phone_number,users.user_category,users.user_status,users.email_address,users.user_thumbnail,centers.center_name,centers.location FROM users INNER JOIN centers ON users.current_center_id=centers.center_id WHERE centers.center_status='active' AND users.user_status='active'");

        if ($query->num_rows()>0) {         
            $this->response(array('status'=>'success','message'=>$query->result()));
        }
        else{
            $this->response(array('status'=>'failed','message'=>'No data fould'),REST_Controller::HTTP_NOT_FOUND);
        }
        
    }

    //adding(inserting) a single user into the database
    function user_put(){
       
        $this->load->library('form_validation');
        $this->form_validation->set_data($this->put());

        $user_data = remove_unknown_fields($this->put(), $this->form_validation->get_field_names('user_put'));
        $this->form_validation->set_data($user_data);

        if ($this->form_validation->run('user_put') != false) {
            $this->load->model('Model_users');
            $exists_email = $this->Model_users->get_by(array('email_address'=> $this->put('email_address')));
            $exists_username = $this->Model_users->get_by(array('username'=> $this->put('username')));    

            if($exists_email){
                $this->response(array('status'=>'failure', 'message'=>'Email not available', REST_Controller::HTTP_CONFLICT));
            }
            if($exists_username){
                $this->response(array('status'=>'failure', 'message'=>'Username not available', REST_Controller::HTTP_CONFLICT));
            }
            //$user = $this->put();

            $user_id = $this->Model_users->insert($user_data);
            if(!$user_id){
                 $this->response(array('status'=>'failure', 'message'=>'Unexpected error occured while trying to create the user', REST_Controller::HTTP_INTERNAL_SERVER_ERROR));
            }else{
                 $this->response(array('status'=>'success', 'message'=>'created'));
            }

        }
        else{
            $this->response(array('status'=>'failure', 'message'=>$this->form_validation->get_errors_as_array()), REST_Controller::HTTP_BAD_REQUEST);
        }
        
    }


    //update the specified user
    function user_post(){
        $user_id = $this->uri->segment(3);
        
        $this->load->model('model_users');
        $user = $this->model_users->get_by(array('user_id' => $user_id, 'status'=>'active'));

        if (isset($user['user_id'])) 
        {
            $this->load->library('form_validation');
            $data = remove_unknown_fields($this->post(), $this->form_validation->get_field_names('user_post'));
            
            if($data != NULL){
                $this->form_validation->set_data($data);
                if($this->form_validation->run('user_post') != false)
                {
                    $this->load->model('model_users');

                    $safe_email = !isset($data['email_address']) || $data['email_address'] == $user['email_address'] || 
                    !$this->model_users->get_by(array('email_address' => $data['email_address']));

                    if(!$safe_email){
                        $this->response(array('status'=>'failure', 'message'=>'Email not available', REST_Controller::HTTP_CONFLICT));
                    }

                    $updated = $this->model_users->update($user_id,$data);
                    if(!$updated){
                         $this->response(array('status'=>'failure', 'message'=>'Unexpected error occured while trying to update the user', REST_Controller::HTTP_INTERNAL_SERVER_ERROR));
                    }else{
                         $this->response(array('status'=>'success', 'message'=>'updated'));
                    }
                }
                else
                {
                    $this->response(array('status'=>'failure', 'message'=>$this->form_validation->get_errors_as_array()), REST_Controller::HTTP_BAD_REQUEST);
                }
            }
            else
            {
                $this->response(array('status'=>'failure', 'message'=>"No request data"), REST_Controller::HTTP_BAD_REQUEST);
            }
        }
        else
        {
            $this->response(array('status'=>'failed','message'=>'No such user'),REST_Controller::HTTP_NOT_FOUND);
        }
        
    }

    //perform a soft delete on the user
    //simply set the status field to inactive
    function user_delete(){
        $user_id = $this->uri->segment(3);
        
        $this->load->model('model_users');
        $user = $this->model_users->get_by(array('user_id' => $user_id, 'status'=>'active'));


        if (isset($user['user_id'])) {
            $data['status'] = 'deleted';
            $deleted = $this->model_users->update($user_id,$data);
            
            if (!$deleted) {
                $this->response(array('status'=>'failure', 'message'=>'Unexpected error occured while trying to delete the user', REST_Controller::HTTP_INTERNAL_SERVER_ERROR));
            }
            else{
                $this->response(array('status'=>'failed','message'=>'deleted'));            
            }
        }
        else{
            $this->response(array('status'=>'failed','message'=>'No such user'),REST_Controller::HTTP_NOT_FOUND);
        }
        
    }


/*end of user API methods */


/********************************************************************************\
    center API methods
    Base URL: http://localhost/nairobits.com/api/center
\********************************************************************************/
    //retrieve a single center information whose id is issued and status is active
    //http://localhost/nairobits.com/api/center/center_id
    //Method: GET
    //where center_id is the center id
    //Example: http://localhost/nairobits.com/api/center/4
    function center_get(){
        $center_id = $this->uri->segment(3);
        
        $this->load->model('model_centers');
        $center = $this->model_centers->get_by(array('center_id' => $center_id, 'status'=>'active'));


        if (isset($center['center_id'])) {
            $this->response(array('status'=>'success','message'=>$center));
        }
        else{
            $this->response(array('status'=>'failed','message'=>'No such center'),REST_Controller::HTTP_NOT_FOUND);
        }
        
    }


    //get all center whose status is active
    function centers_get(){
        $results=NULL;
        $this->load->model('model_centers');
        $results = $this->model_centers->get_many_by(array('status'=>'active'));

        if ($results != NULL) {
            $this->response(array('status'=>'success','message'=>$results));
        }
        else{
            $this->response(array('status'=>'failed','message'=>'No data fould'),REST_Controller::HTTP_NOT_FOUND);
        }
        
    }



    //adding(inserting) a single center into the database
    function center_put(){
       
        $this->load->library('form_validation');
        $this->form_validation->set_data($this->put());

        $center_data = remove_unknown_fields($this->put(), $this->form_validation->get_field_names('center_put'));
        $this->form_validation->set_data($center_data);

        if ($this->form_validation->run('center_put') != false) 
        {
            $this->load->model('Model_centers');

            $exists_name = $this->Model_centers->get_by(array('name'=> $this->put('name')));

            if($exists_name){
                $this->response(array('status'=>'failure', 'message'=>'Name not available', REST_Controller::HTTP_CONFLICT));
            }

            $center_id = $this->Model_centers->insert($center_data);
            if(!$center_id){
                 $this->response(array('status'=>'failure', 'message'=>'Unexpected error occured while trying to create the center', REST_Controller::HTTP_INTERNAL_SERVER_ERROR));
            }else{
                 $this->response(array('status'=>'success', 'message'=>'created'));
            }

        }
        else
        {
            $this->response(array('status'=>'failure', 'message'=>$this->form_validation->get_errors_as_array()), REST_Controller::HTTP_BAD_REQUEST);
        }
        
    }


    //update the specified center
    function center_post(){
        $center_id = $this->uri->segment(3);
        
        $this->load->model('model_centers');
        $center = $this->model_centers->get_by(array('center_id' => $center_id, 'status'=>'active'));

        if (isset($center['center_id'])) 
        {
            $this->load->library('form_validation');
            $data = remove_unknown_fields($this->post(), $this->form_validation->get_field_names('center_post'));
            
            if($data != NULL){
                $this->form_validation->set_data($data);
                if($this->form_validation->run('center_post') != false)
                {
                   // $this->load->model('model_centers');

                    $safe_name = !isset($data['name']) || $data['name'] == $center['name'] || 
                    !$this->model_centers->get_by(array('name' => $data['name']));

                    if(!$safe_name){
                        $this->response(array('status'=>'failure', 'message'=>'Name not available', REST_Controller::HTTP_CONFLICT));
                    }

                    $updated = $this->model_centers->update($center_id,$data);
                    if(!$updated){
                         $this->response(array('status'=>'failure', 'message'=>'Unexpected error occured while trying to update the center', REST_Controller::HTTP_INTERNAL_SERVER_ERROR));
                    }else{
                         $this->response(array('status'=>'success', 'message'=>'updated'));
                    }
                }
                else
                {
                    $this->response(array('status'=>'failure', 'message'=>$this->form_validation->get_errors_as_array()), REST_Controller::HTTP_BAD_REQUEST);
                }
            }
            else
            {
                $this->response(array('status'=>'failure', 'message'=>"No request data"), REST_Controller::HTTP_BAD_REQUEST);
            }
        }
        else
        {
            $this->response(array('status'=>'failed','message'=>'No such center'),REST_Controller::HTTP_NOT_FOUND);
        }
        
    }


    //perform a soft delete on the center
    //simply set the status field to inactive
    function center_delete(){
        $center_id = $this->uri->segment(3);
        
        $this->load->model('model_centers');
        $center = $this->model_centers->get_by(array('center_id' => $center_id, 'status'=>'active'));


        if (isset($center['center_id'])) {
            $data['status'] = 'deleted';
            $deleted = $this->model_centers->update($center_id,$data);
            
            if (!$deleted) {
                $this->response(array('status'=>'failure', 'message'=>'Unexpected error occured while trying to delete the center', REST_Controller::HTTP_INTERNAL_SERVER_ERROR));
            }
            else{
                $this->response(array('status'=>'failed','message'=>'center deleted'));            
            }
        }
        else{
            $this->response(array('status'=>'failed','message'=>'No such center'),REST_Controller::HTTP_NOT_FOUND);
        }
        
    }

/********************************************************************************\
\********************************************************************************/

/*end of center API methods */

/*####################################################################################*/


/********************************************************************************\
    itemcategory API methods
    Base URL: http://localhost/nairobits.com/api/itemcategory
\********************************************************************************/
    //retrieve a single itemcategory information whose id is issued
    //http://localhost/nairobits.com/api/itemcategorys/itemcategory_id
    //Method: GET
    //where itemcategory_id is the itemcategory id
    //Example: http://localhost/nairobits.com/api/itemcategory/4
    function itemcategory_get(){
        $itemcategory_id = $this->uri->segment(3);
        
        $this->load->model('model_itemcategorys');
        $itemcategory = $this->model_itemcategorys->get_by(array('itemcategory_id' => $itemcategory_id));


        if (isset($itemcategory['itemcategory_id'])) {
            $this->response(array('status'=>'success','message'=>$itemcategory));
        }
        else{
            $this->response(array('status'=>'failed','message'=>'No such itemcategory'),REST_Controller::HTTP_NOT_FOUND);
        }
        
    }


    //get all itemcategory 
    function itemcategorys_get(){
        $results=NULL;
        $this->load->model('model_itemcategorys');
        $results = $this->model_itemcategorys->get_all();

        if ($results != NULL) {
            $this->response(array('status'=>'success','message'=>$results));
        }
        else{
            $this->response(array('status'=>'failed','message'=>'No data fould'),REST_Controller::HTTP_NOT_FOUND);
        }
        
    }

    //adding(inserting) a single itemcategory into the database
    function itemcategory_put(){
       
        $this->load->library('form_validation');
        $this->form_validation->set_data($this->put());

        $itemcategory_data = remove_unknown_fields($this->put(), $this->form_validation->get_field_names('itemcategory_put'));
        $this->form_validation->set_data($itemcategory_data);

        if ($this->form_validation->run('itemcategory_put') != false) 
        {
            $this->load->model('Model_itemcategorys');

            $exists_category_name = $this->Model_itemcategorys->get_by(array('category_name'=> $this->put('category_name')));

            if($exists_category_name){
                $this->response(array('status'=>'failure', 'message'=>'Category Name not available', REST_Controller::HTTP_CONFLICT));
            }

            $itemcategory_id = $this->Model_itemcategorys->insert($itemcategory_data);
            if(!$itemcategory_id){
                 $this->response(array('status'=>'failure', 'message'=>'Unexpected error occured while trying to create the item category', REST_Controller::HTTP_INTERNAL_SERVER_ERROR));
            }else{
                 $this->response(array('status'=>'success', 'message'=>'created'));
            }

        }
        else
        {
            $this->response(array('status'=>'failure', 'message'=>$this->form_validation->get_errors_as_array()), REST_Controller::HTTP_BAD_REQUEST);
        }
        
    }



    //update the specified itemcategory
    function itemcategory_post(){
        $itemcategory_id = $this->uri->segment(3);
        
        $this->load->model('model_itemcategorys');
        $itemcategory = $this->model_itemcategorys->get_by(array('itemcategory_id' => $itemcategory_id));

        if (isset($itemcategory['itemcategory_id'])) 
        {
            $this->load->library('form_validation');
            $data = remove_unknown_fields($this->post(), $this->form_validation->get_field_names('itemcategory_post'));
            
            if($data != NULL){
                $this->form_validation->set_data($data);
                if($this->form_validation->run('itemcategory_post') != false)
                {
                   // $this->load->model('model_itemcategorys');

                    $safe_name = !isset($data['category_name']) || $data['category_name'] == $itemcategory['category_name'] || 
                    !$this->model_itemcategorys->get_by(array('category_name' => $data['category_name']));

                    if(!$safe_name){
                        $this->response(array('status'=>'failure', 'message'=>'Category name not available', REST_Controller::HTTP_CONFLICT));
                    }

                    $updated = $this->model_itemcategorys->update($itemcategory_id,$data);
                    if(!$updated){
                         $this->response(array('status'=>'failure', 'message'=>'Unexpected error occured while trying to update the item category', REST_Controller::HTTP_INTERNAL_SERVER_ERROR));
                    }else{
                         $this->response(array('status'=>'success', 'message'=>'updated'));
                    }
                }
                else
                {
                    $this->response(array('status'=>'failure', 'message'=>$this->form_validation->get_errors_as_array()), REST_Controller::HTTP_BAD_REQUEST);
                }
            }
            else
            {
                $this->response(array('status'=>'failure', 'message'=>"No request data"), REST_Controller::HTTP_BAD_REQUEST);
            }
        }
        else
        {
            $this->response(array('status'=>'failed','message'=>'No such itemcategory'),REST_Controller::HTTP_NOT_FOUND);
        }
        
    }

    // delete the itemcategory
    function itemcategory_delete(){
        $itemcategory_id = $this->uri->segment(3);
        
        $this->load->model('model_itemcategorys');
        $itemcategory = $this->model_itemcategorys->get_by(array('itemcategory_id' => $itemcategory_id));


        if (isset($itemcategory['itemcategory_id'])) {

            $deleted = $this->model_itemcategorys->delete($itemcategory_id);
            
            if (!$deleted) {
                $this->response(array('status'=>'failure', 'message'=>'Unexpected error occured while trying to delete the itemcategory', REST_Controller::HTTP_INTERNAL_SERVER_ERROR));
            }
            else{
                $this->response(array('status'=>'failed','message'=>'itemcategory deleted'));            
            }
        }
        else{
            $this->response(array('status'=>'failed','message'=>'No such itemcategory'),REST_Controller::HTTP_NOT_FOUND);
        }
        
    }

/*end of itemcategory API methods */


/********************************************************************************\
    asset API methods
    Base URL: http://localhost/nairobits.com/api/asset
\********************************************************************************/
    //retrieve a single asset information whose id is issued and status is active
    //http://localhost/nairobits.com/api/asset/asset_id
    //Method: GET
    //where asset_id is the asset id
    //Example: http://localhost/nairobits.com/api/asset/4
    function asset_get(){
        $asset_id = $this->uri->segment(3);
        
        $this->load->model('model_assets');
        $asset = $this->model_assets->get_by(array('asset_id' => $asset_id, 'status'=>'active'));


        if (isset($asset['asset_id'])) {
            $this->response(array('status'=>'success','message'=>$asset));
        }
        else{
            $this->response(array('status'=>'failed','message'=>'No such asset'),REST_Controller::HTTP_NOT_FOUND);
        }
        
    }


    //get all assets 
    function assets_get(){
        $results=NULL;
        $this->load->model('model_assets');
        $results = $this->model_assets->get_many_by(array('status'=>'active'));

        if ($results != NULL) {
            $this->response(array('status'=>'success','message'=>$results));
        }
        else{
            $this->response(array('status'=>'failed','message'=>'No data fould'),REST_Controller::HTTP_NOT_FOUND);
        }
        
    }

    //adding(inserting) a single asset into the database
    function asset_put(){
       
        $this->load->library('form_validation');
        $this->form_validation->set_data($this->put());

        $asset_data = remove_unknown_fields($this->put(), $this->form_validation->get_field_names('asset_put'));
        $this->form_validation->set_data($asset_data);

        if ($this->form_validation->run('asset_put') != false) 
        {
            $this->load->model('Model_assets');

            $exists_nairobitsbarcode = $this->Model_assets->get_by(array('nairobitsbarcode'=> $this->put('nairobitsbarcode')));
            $exists_serialnumber = $this->Model_assets->get_by(array('serialnumber'=> $this->put('serialnumber')));


            if($exists_nairobitsbarcode){
                $this->response(array('status'=>'failure', 'message'=>'nairobitsbarcode not available', REST_Controller::HTTP_CONFLICT));
            }

            if($exists_serialnumber){
                $this->response(array('status'=>'failure', 'message'=>'serialnumber not available', REST_Controller::HTTP_CONFLICT));
            }


            $asset_id = $this->Model_assets->insert($asset_data);
            if(!$asset_id){
                 $this->response(array('status'=>'failure', 'message'=>'Unexpected error occured while trying to create the asset', REST_Controller::HTTP_INTERNAL_SERVER_ERROR));
            }else{
                 $this->response(array('status'=>'success', 'message'=>'created'));
            }

        }
        else
        {
            $this->response(array('status'=>'failure', 'message'=>$this->form_validation->get_errors_as_array()), REST_Controller::HTTP_BAD_REQUEST);
        }
        
    }


    //update the specified asset
    function asset_post(){
        $asset_id = $this->uri->segment(3);
        
        $this->load->model('model_assets');
        $asset = $this->model_assets->get_by(array('asset_id' => $asset_id));

        if (isset($asset['asset_id'])) 
        {
            $this->load->library('form_validation');
            $data = remove_unknown_fields($this->post(), $this->form_validation->get_field_names('asset_post'));
            
            if($data != NULL){
                $this->form_validation->set_data($data);
                if($this->form_validation->run('asset_post') != false)
                {
                   // $this->load->model('model_assets');

                    $safe_nairobitsbarcode = !isset($data['nairobitsbarcode']) || $data['nairobitsbarcode'] == $asset['nairobitsbarcode'] || 
                    !$this->model_assets->get_by(array('nairobitsbarcode' => $data['nairobitsbarcode']));

                    $safe_serialnumber = !isset($data['serialnumber']) || $data['serialnumber'] == $asset['serialnumber'] || 
                    !$this->model_assets->get_by(array('serialnumber' => $data['serialnumber']));


                    if(!$safe_nairobitsbarcode){
                        $this->response(array('status'=>'failure', 'message'=>'nairobitsbarcode not available', REST_Controller::HTTP_CONFLICT));
                    }
                    if(!$safe_serialnumber){
                        $this->response(array('status'=>'failure', 'message'=>'serialnumber not available', REST_Controller::HTTP_CONFLICT));
                    }


                    $updated = $this->model_assets->update($asset_id,$data);
                    if(!$updated){
                         $this->response(array('status'=>'failure', 'message'=>'Unexpected error occured while trying to update the asset', REST_Controller::HTTP_INTERNAL_SERVER_ERROR));
                    }else{
                         $this->response(array('status'=>'success', 'message'=>'updated'));
                    }
                }
                else
                {
                    $this->response(array('status'=>'failure', 'message'=>$this->form_validation->get_errors_as_array()), REST_Controller::HTTP_BAD_REQUEST);
                }
            }
            else
            {
                $this->response(array('status'=>'failure', 'message'=>"No request data"), REST_Controller::HTTP_BAD_REQUEST);
            }
        }
        else
        {
            $this->response(array('status'=>'failed','message'=>'No such asset'),REST_Controller::HTTP_NOT_FOUND);
        }
        
    }



    // delete the asset
    function asset_delete(){
        $asset_id = $this->uri->segment(3);
        
        $this->load->model('model_assets');
        $asset = $this->model_assets->get_by(array('asset_id' => $asset_id));


        if (isset($asset['asset_id'])) {

            $deleted = $this->model_assets->delete($asset_id);
            
            if (!$deleted) {
                $this->response(array('status'=>'failure', 'message'=>'Unexpected error occured while trying to delete the asset', REST_Controller::HTTP_INTERNAL_SERVER_ERROR));
            }
            else{
                $this->response(array('status'=>'failed','message'=>'asset deleted'));            
            }
        }
        else{
            $this->response(array('status'=>'failed','message'=>'No such asset'),REST_Controller::HTTP_NOT_FOUND);
        }
        
    }


















    //http://localhost/nairobits.com/api/test
    //Method: GET
    function test_get(){
        $this->response("Hello world from rst api");
    }    

    /*
	* function to handle GET request to the 
	* student resource
    */
    //http://localhost/nairobits.com/api/student?id=x
    //Method: GET
    //where x can be:- 1 or 2 or 3 

/*
    function student_get(){
        $id = $this->get('id');
        $students = array(
            1 => array('fName'=>'John', 'lName'=>'Doe'),
            2 => array('fName'=>'John', 'lName'=>'Walker'),
            3 => array('fName'=>'Nelson', 'lName'=>'Mandera')
        );
    	//$this->response($students);
        if (isset($students[$id])) {
            $this->response(array('status'=>'success','message'=>$students[$id]));
        }
        else{
            $this->response(array('status'=>'failed','message'=>'No such student'));
        }
        
    }
*/
    //http://localhost/nairobits.com/api/student/x
    //Method: GET
    //where x can be:- 1 or 2 or 3
    function student_get(){
        $id = $this->uri->segment(3);
        $students = array(
            1 => array('fName'=>'John', 'lName'=>'Doe'),
            2 => array('fName'=>'John', 'lName'=>'Walker'),
            3 => array('fName'=>'Nelson', 'lName'=>'Mandera')
        );
        //$this->response($students);
        if (isset($students[$id])) {
            $this->response(array('status'=>'success','message'=>$students[$id]));
        }
        else{
            $this->response(array('status'=>'failed','message'=>'No such student'),REST_Controller::HTTP_NOT_FOUND);
        }
        
    }
}