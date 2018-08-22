<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Welcome extends CI_Controller {

    function __construct() {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
        $this->load->helper('url');
    }

 

    public function events() {
       
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
                $nestedData[] = $events->event_invoice;               
                $nestedData[] = $events->partner_name;
                $nestedData[] = $client;
               
                $data[] = $nestedData;
            }
        }
       
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => 0,
            "recordsFiltered" => $this->Model_event->count_filtered(),
            "data" => $result,
        );
        echo json_encode($output);
        exit;
    }
    
    
    public function shoping() {
       
        $this->load->model("Model_event");
        $result = $this->Model_event->get_shoping_datatables();

        $output = array(
            "totalItems" => $this->Model_event->count_filtered(),
            "users" => $result,
        );
        echo json_encode($output);
        exit;
    }
    
     

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */