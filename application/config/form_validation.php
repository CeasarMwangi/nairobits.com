<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');


$config = array(

 'user_put' => array(
   array( 'field' => 'user_status', 'label' => 'user_status', 'rules' => 'trim|required|min_length[3]|max_length[32]' ),
   array( 'field' => 'password', 'label' => 'password', 'rules' => 'trim|required|min_length[8]|max_length[16]' ),
   array( 'field' => 'username', 'label' => 'username', 'rules' => 'trim|required|min_length[3]|max_length[32]|alpha' ),
   array( 'field' => 'user_category', 'label' => 'user_category', 'rules' => 'trim|required|min_length[5]|max_length[6]|alpha' ),
   array( 'field' => 'phone_number', 'label' => 'phone_number', 'rules' => 'trim|required|alpha_dash' ),
   array( 'field' => 'created_timestamp', 'label' => 'created_timestamp', 'rules' => 'trim' ),
      array( 'field' => 'email_address', 'label' => 'email_address', 'rules' => 'trim|required|valid_email' )
  ),
 'user_post' => array(
   array( 'field' => 'user_status', 'user_status' => 'name', 'rules' => 'trim|min_length[3]|max_length[32]' ),
   array( 'field' => 'username', 'label' => 'username', 'rules' => 'trim|min_length[3]|max_length[32]|alpha' ),
   array( 'field' => 'user_category', 'label' => 'user_category', 'rules' => 'trim|min_length[5]|max_length[6]|alpha' ),
   array( 'field' => 'phone_number', 'label' => 'phone_number', 'rules' => 'trim|alpha_dash' ),
   array( 'field' => 'email_address', 'label' => 'email_address', 'rules' => 'trim|valid_email' )
  ),
 'center_put' => array(
   array( 'field' => 'center_name', 'label' => 'center_Name', 'rules' => 'trim|required|min_length[3]|max_length[32]|alpha_numeric_spaces' ),
   array( 'field' => 'location', 'label' => 'Center Location', 'rules' => 'trim|required|min_length[3]|max_length[32]|alpha_numeric_spaces' ),
   array( 'field' => 'capacity', 'label' => 'Center Student Capacity', 'rules' => 'trim|required|integer|greater_than[0]|less_than[100]' )
  ),
 'center_post' => array(
   array( 'field' => 'center_name', 'label' => 'Name of the center', 'rules' => 'trim|min_length[3]|max_length[32]|alpha_numeric_spaces' ),
   array( 'field' => 'location', 'label' => 'Center Location', 'rules' => 'trim|min_length[3]|max_length[32]|alpha_numeric_spaces' ),
   array( 'field' => 'capacity', 'label' => 'Center Student Capacity', 'rules' => 'trim|integer|greater_than[0]|less_than[100]' )
  ),
 'itemcategory_put' => array(
   array( 'field' => 'category_name', 'label' => 'Category Name', 'rules' => 'trim|required|min_length[3]|max_length[32]|alpha_numeric_spaces' ),
   array( 'field' => 'description', 'label' => 'Description', 'rules' => 'trim|required|min_length[3]|max_length[500]|alpha_numeric_spaces' ),
   array( 'field' => 'type', 'label' => 'Item Type', 'rules' => 'trim|required|min_length[3]|max_length[8]|alpha' )
  ),
 'itemcategory_post' => array(
   array( 'field' => 'category_name', 'label' => 'Category Name', 'rules' => 'trim|min_length[3]|max_length[32]|alpha_numeric_spaces' ),
   array( 'field' => 'description', 'label' => 'Description', 'rules' => 'trim|min_length[3]|max_length[500]|alpha_numeric_spaces' ),
   array( 'field' => 'type', 'label' => 'Item Type', 'rules' => 'trim|min_length[3]|max_length[8]|alpha' )
  ),
 'asset_put' => array(
   array( 'field' => 'nairobitsbarcode', 'label' => 'nairobitsbarcode', 'rules' => 'trim|required|min_length[3]|max_length[32]|alpha_numeric' ),
   array( 'field' => 'serialnumber', 'label' => 'serialnumber', 'rules' => 'trim|required|min_length[3]|max_length[32]|alpha_numeric' ),
   array( 'field' => 'category_id', 'label' => 'category_id', 'rules' => 'trim|required|max_length[32]|numeric' ),
   array( 'field' => 'currentcenter_id', 'label' => 'currentcenter_id', 'rules' => 'trim|required|max_length[32]|numeric' ),
   array( 'field' => 'specifications', 'label' => 'specifications', 'rules' => 'trim|required|max_length[500]|alpha_numeric_spaces' ),
   array( 'field' => 'additional_information', 'label' => 'additional_information', 'rules' => 'trim|max_length[500]|alpha_numeric_spaces' ),
   array( 'field' => 'condition', 'label' => 'condition', 'rules' => 'trim|required|min_length[3]|max_length[16]|alpha' )
  ),
 'asset_post' => array(
   array( 'field' => 'nairobitsbarcode', 'label' => 'nairobitsbarcode', 'rules' => 'trim|min_length[3]|max_length[32]|alpha_numeric' ),
   array( 'field' => 'serialnumber', 'label' => 'serialnumber', 'rules' => 'trim|min_length[3]|max_length[32]|alpha_numeric' ),
   array( 'field' => 'category_id', 'label' => 'category_id', 'rules' => 'trim|max_length[32]|numeric' ),
   array( 'field' => 'currentcenter_id', 'label' => 'currentcenter_id', 'rules' => 'trim|max_length[32]|numeric' ),
   array( 'field' => 'specifications', 'label' => 'specifications', 'rules' => 'trim|max_length[500]|alpha_numeric_spaces' ),
   array( 'field' => 'additional_information', 'label' => 'additional_information', 'rules' => 'trim|max_length[500]|alpha_numeric_spaces' ),
   array( 'field' => 'condition', 'label' => 'condition', 'rules' => 'trim|min_length[3]|max_length[16]|alpha' )
  )
);
?>﻿