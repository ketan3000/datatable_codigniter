<?php

defined('BASEPATH') OR exit('No direct script access allowed');
// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';

/**
 * DLF propkeep application
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Ketan kumar chauhan
 * @license         MIT
 * @link            http://52.39.23.134/dlfpropkeep8476/api/webapi/
 */
class Webapi extends REST_Controller {

    function __construct() {
// Construct the parent class
        parent::__construct();
        //$this->load->helper('email_moblie_helper');
        header('Access-Control-Allow-Origin: *');
    }

    /* start Custom function */

    public function alluser_get() {
       $headers = apache_request_headers();
       //print_r($headers);exit;
        $this->db->select('*');
        $this->db->from('users');
        $query = $this->db->get();
        //echo $this->db->last_query();exit;
        if ($query->num_rows() > 0) {
            $userRecord = $query->result();
            
            $this->set_response(['status' => TRUE, 'resultSet' => $userRecord], REST_Controller::HTTP_OK);
        } else {
            $this->set_response(['status' => FALSE, 'message' => 'User could not be found'], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
    }

    public function user_get() {
        $id = (int) $this->get('userid'); //exit;
        if ($id != 0) {
            $this->db->select('*');
            $this->db->from('users');
            $this->db->where('id', $id);
            $query = $this->db->get();
            if ($query->num_rows() > 0) {
                $userRecord = $query->row();                
                $this->set_response(['status' => TRUE, 'resultSet' => $userRecord], REST_Controller::HTTP_OK);
            } else {
                $this->set_response(['status' => FALSE, 'message' => 'User could not be found'], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
        } else {
            $this->response(['status' => FALSE, 'message' => 'UserId not found'], REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
    }

    public function addUser_post() {

        if ($this->post('name') != "" && $this->post('phone') != "" && $this->post('password') != "" && $this->post('email') != "") {
            $postUserData = array(
                'name' => $this->post('name'),
                'phone' => $this->post('phone'),
                'email' => $this->post('email'),
                'password' => md5($this->post('password')),
                'created_on' => date('Y-m-d H:i:s'),
            );
            if ($this->db->insert('users', $postUserData)) {
                $user_id = $this->db->insert_id();
                $this->set_response(['status' => TRUE, 'lastId' => $user_id, 'message' => 'Data Inserted Successfully'], REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
            } else {
                $this->response(['status' => FALSE, 'message' => 'Something wents wrong !!'], REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
            }
        } else {
            $this->response(['status' => FALSE, 'message' => 'Please enter required field'], REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
    }

    public function updateUser_put() {
        if ($this->put('name') != "" && $this->put('phone') != "" && $this->put('id') != "" && $this->put('email') != "") {
            $id = $this->put('id');
            $postUserData = array(
                'name' => $this->put('name'),
                'phone' => $this->put('phone'),
                'email' => $this->put('email'),
                'updated_on' => date('Y-m-d H:i:s'),
            );
            $this->db->where('id', $id);
            if ($this->db->update('users', $postUserData)) {
                $this->set_response(['status' => TRUE, 'message' => 'Data Updated Successfully'], REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
            } else {
                $this->response(['status' => FALSE, 'message' => 'Something wents wrong !!'], REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
            }
        } else {
            $this->response(['status' => FALSE, 'message' => 'Please enter required field'], REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
    }

    public function deleteUser_delete($id) {
        
        if($id){
            //delete post           
             $this->db->where('id', $id);   
            if($this->db->delete('users')){
                //set the response and exit
                $this->response([
                    'status' => TRUE,
                    'message' => 'User has been removed successfully.'
                ], REST_Controller::HTTP_OK);
            }else{
                //set the response and exit
                $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
            }
        }else{
            //set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => 'No user were found.'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    
    }
    
    public function events_post() {
        echo "hello";exit;
        $this->load->model("Model_event");
        $result = $this->Model_event->get_datatables();
        $data = array();
        if (isset($result) && !empty($result)) {
            foreach ($result as $events) {  // preparing an array
                if ($events->partner_name == $events->client_title) {
                    $client = "NA";
                } else {
                    $client = $events->client_title;
                }

                $nestedData = array();
                $nestedData[] = '<a href="javascript:void(0);" class="coupon_question" evtid=' . $events->event_id . ' >' . $events->event_invoice . '</a>';
                $nestedData[] = $events->ticketDetails;
                $nestedData[] = $events->partner_name;
                $nestedData[] = $client;
                $nestedData[] = $events->getway_title;
                $nestedData[] = $events->device_name;
                $nestedData[] = $events->service_name;
                $nestedData[] = $events->event_text;
                $nestedData[] = $events->severity;
                $nestedData[] = $events->Duration;
                $nestedData[] = $events->event_start_time;
                $nestedData[] = $events->event_description;
                $nestedData[] = $events->event_suppress_action;
                $data[] = $nestedData;
            }
        }
        //pr($data);

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => 0,
            "recordsFiltered" => $this->Model_event->count_filtered(),
            "data" => $data,
        );
    }

}
