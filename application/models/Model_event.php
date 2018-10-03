<?php

class Model_event extends CI_Model {

    private $tablename, $userId, $roleId, $user_type, $company_id = "";
    var $cData; /* created user details (Array type) */
    var $uData;  /* updated user details (Array type) */
    var $order = array('ev.event_id' => 'desc');
    var $column_order = array('event_invoice', 'incident_id', 'part.client_title', 'clnt.client_title', 'gettype.title', 'devi.device_name', 'serv.service_description', 'ev.event_text', 'severity.severity', 'Duration', 'ev.event_start_time');
    var $column_order_history = array('event_invoice', 'part.client_title', 'clnt.client_title', 'gettype.title', 'devi.device_name', 'serv.service_description', 'ev.event_text', 'severity.severity', 'Duration', 'ev.event_start_time', 'ev.event_end_time');

    public function __construct() {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
    }

    private function _get_datatables_query() {
        $postdata = file_get_contents("php://input");
        $_POST = json_decode($postdata, 'true');       
        $type = "";
        $permalink = array(2, 3);
        $this->db->select("tic.ticket_id,tic.incident_id,gettype.title as getway_title,ev.event_id,ev.event_invoice,ev.event_text as event_text_evt,SUBSTR(ev.event_text, 1, 30) as event_text_small,ev.event_start_time,ev.event_end_time,ev.event_created_time,ev.event_description,ev.client_id,clnt.client_title,devi.device_name,serv.service_description as service_name,severity.severity,group.title as domain_name,eventsta.event_state,TIMEDIFF(NOW(), ev.event_start_time) as Duration,severity.color_code,ev.is_suppressed,part.client_title as partner_name");
        $this->db->from('events as ev');
        $this->db->join('client as part', 'part.id = ev.partner_id');
        $this->db->join('client as clnt', 'clnt.id = ev.client_id');
        $this->db->join('devices as devi', 'devi.device_id = ev.device_id');
        $this->db->join('services as serv', 'serv.service_id = ev.service_id');
        $this->db->join('severities as severity', 'severity.id = ev.severity_id');
        $this->db->join('group as group', 'group.id = ev.domain_id');
        $this->db->join('client_gateways as cgat', 'cgat.id = ev.gateway_id');
        $this->db->join('gateway_types as gettype', 'gettype.gatewaytype_uuid = cgat.gateway_type');
        $this->db->join('tickets as tic', 'ev.ticket_id = tic.ticket_id', 'left');
        $this->db->join('event_states eventsta', 'eventsta.id = ev.event_state_id');
        $this->db->where_not_in('ev.severity_id', $permalink);
        
        $this->db->where('ev.event_end_time', '0000-00-00 00:00:00');

        if (isset($_POST['search']['value'])) {
            $this->db->group_start();
            $this->db->like('part.client_title', $_POST['search']['value']);
            $this->db->like('clnt.client_title', $_POST['search']['value']);
            $this->db->or_like('gettype.title', $_POST['search']['value']);
            $this->db->or_like('devi.device_name', $_POST['search']['value']);
            $this->db->or_like('serv.service_description', $_POST['search']['value']);
            $this->db->or_like('severity.severity', $_POST['search']['value']);
            $this->db->or_like('ev.event_invoice', $_POST['search']['value']);
            $this->db->or_like('ev.event_text', $_POST['search']['value']);
            $this->db->or_like('tic.incident_id', $_POST['search']['value']);
            $this->db->group_end();
        }

        if (isset($_POST['order'])) { // here order processing
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function get_datatables() {

        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
//        echo $this->db->last_query();
//        exit;
        $data = array();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                if ($row->is_suppressed == 'N') {
                    // pr($row);
                    $showTickeicon = "";
                    if ($row->ticket_id == "") {
                        $showTickeicon = '    <a title="manual ticket" class="manual_ticket mticket_' . $row->event_id . '" evtid=' . $row->event_id . '  href="javascript:void(0);"><i class="fa fa-ticket" aria-hidden="true"></i></a>';
                    }
                    $row->event_suppress_action = '<a title="Suppress" href="javascript:void(0);" class="suppress_action" evtid=' . $row->event_id . '><i class="fa fa-clock-o" aria-hidden="true"></i></a>' . $showTickeicon . '</span>';
                    $event_description = json_decode($row->event_description, 'true');
                    $state_str = (isset($event_description['state_str'])) ? $event_description['state_str'] : '';
                    $event_description = $state_str . '  -  ' . $event_description['service_description'] . '  -  ' . $event_description['output'];
                    $row->service_name = '<span title="' . $row->service_name . '">' . $row->service_name . '</span>';
                    $row->event_description = '<span title="' . $event_description . '">' . $event_description . '</span>';
                    $row->event_text = '<span  title="' . $row->event_text_evt . '">' . $row->event_text_small . '</span>';
                    $row->severity = '<span class="severityEvent" style=" text-align:center;width:90px;display: block;background-color: ' . $row->color_code . '; ">' . $row->severity . '</span>';
                    
                    $data[] = $row;
                }
            }
        }
        return $data;
    }

    public function count_filtered() {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
    
    
    /////////////////////////////////////////////////////////////////////////
    
      private function _get_shoping_datatables_query() {
        $postdata = file_get_contents("php://input");
        $_POST = json_decode($postdata, 'true');       
        $type = "";
        $permalink = array(2, 3);
        $this->db->select("tic.ticket_id,tic.incident_id,gettype.title as getway_title,ev.event_id,ev.event_invoice,ev.event_text as event_text_evt,SUBSTR(ev.event_text, 1, 30) as event_text_small,ev.event_start_time,ev.event_end_time,ev.event_created_time,ev.event_description,ev.client_id,clnt.client_title,devi.device_name,serv.service_description as service_name,severity.severity,group.title as domain_name,eventsta.event_state,TIMEDIFF(NOW(), ev.event_start_time) as Duration,severity.color_code,ev.is_suppressed,part.client_title as partner_name");
        $this->db->from('events as ev');
        $this->db->join('client as part', 'part.id = ev.partner_id');
        $this->db->join('client as clnt', 'clnt.id = ev.client_id');
        $this->db->join('devices as devi', 'devi.device_id = ev.device_id');
        $this->db->join('services as serv', 'serv.service_id = ev.service_id');
        $this->db->join('severities as severity', 'severity.id = ev.severity_id');
        $this->db->join('group as group', 'group.id = ev.domain_id');
        $this->db->join('client_gateways as cgat', 'cgat.id = ev.gateway_id');
        $this->db->join('gateway_types as gettype', 'gettype.gatewaytype_uuid = cgat.gateway_type');
        $this->db->join('tickets as tic', 'ev.ticket_id = tic.ticket_id', 'left');
        $this->db->join('event_states eventsta', 'eventsta.id = ev.event_state_id');
        $this->db->where_not_in('ev.severity_id', $permalink);  
        $this->db->where('ev.event_end_time', '0000-00-00 00:00:00');
    }

    public function get_shoping_datatables() {
        $this->_get_shoping_datatables_query();
        $limit = 20;        
        if ($_GET['page'] != -1){
            $total = $limit*$_GET['page'];
        }
        $this->db->limit($limit,$total);
        $query = $this->db->get();
        //echo $this->db->last_query();
        return $query->result();
    }

    public function count_shoping_filtered() {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
    
    

 
}

?>