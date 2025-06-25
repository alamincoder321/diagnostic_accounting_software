<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Agent extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->branchId = $this->session->userdata('BRANCHid');
        $access = $this->session->userdata('userId');
        if ($access == '') {
            redirect("Login");
        }
        $this->load->model("Model_myclass", "mmc", TRUE);
        $this->load->model('Model_table', "mt", TRUE);
    }

    public function index()
    {
        $access = $this->mt->userAccess();
        if (!$access) {
            redirect(base_url());
        }
        $data['title'] = "Agent Entry";
        $data['agentCode'] = $this->mt->generateAgentCode();
        $data['content'] = $this->load->view('Administrator/add_agent', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function getAgents()
    {
        $data = json_decode($this->input->raw_input_stream);

        $clauses = "";
        $limit = "";
        if (isset($data->forSearch) && $data->forSearch != '') {
            $limit .= "limit 20";
        }
        if (isset($data->name) && $data->name != '') {
            $clauses .= " and c.Agent_Code like '$data->name%'";
            $clauses .= " or c.Agent_Name like '$data->name%'";
            $clauses .= " or c.Agent_Mobile like '$data->name%'";
        }

        $agents = $this->db->query("
            select
                c.*,
                concat_ws(' - ', c.Agent_Code, c.Agent_Name, c.Agent_Mobile) as display_name
            from tbl_agent c
            where c.status != 'd'
            and c.branch_id = ?
            $clauses
            order by c.Agent_SlNo desc
            $limit
        ", $this->session->userdata('BRANCHid'))->result();

        echo json_encode($agents);
    }

    public function getAgentDue()
    {
        $data = json_decode($this->input->raw_input_stream);

        $clauses = "";
        if (isset($data->agentId) && $data->agentId != null) {
            $clauses .= " and c.Agent_SlNo = '$data->agentId'";
        }
        if (isset($data->districtId) && $data->districtId != null) {
            $clauses .= " and c.area_ID = '$data->districtId'";
        }

        $dueResult = $this->mt->agentDue($clauses);

        echo json_encode($dueResult);
    }

    public function addAgent()
    {
        $res = ['success' => false, 'message' => ''];
        try {
            $agentObj = json_decode($this->input->post('data'));

            $agentCodeCount = $this->db->query("select * from tbl_agent where Agent_Code = ?", $agentObj->Agent_Code)->num_rows();
            if ($agentCodeCount > 0) {
                $agentObj->Agent_Code = $this->mt->generateAgentCode();
            }

            $agent = (array)$agentObj;
            unset($agent['Agent_SlNo']);
            $agent["branch_id"] = $this->session->userdata("BRANCHid");

            $res_message = "";
            $duplicateMobileQuery = $this->db->query("select * from tbl_agent where Agent_Mobile = ? and branch_id = ?", [$agentObj->Agent_Mobile, $this->session->userdata("BRANCHid")]);

            if ($duplicateMobileQuery->num_rows() > 0) {
                $duplicateagent = $duplicateMobileQuery->row();

                unset($agent['Agent_Code']);
                $agent["UpdateBy"]   = $this->session->userdata("FullName");
                $agent["UpdateTime"] = date("Y-m-d H:i:s");
                $agent["status"]     = 'a';
                $this->db->where('Agent_SlNo', $duplicateagent->Agent_SlNo)->update('tbl_agent', $agent);

                $agentObj->Agent_Code = $duplicateagent->Agent_Code;
                $res_message = 'Agent updated successfully';
            } else {
                $agent["AddBy"] = $this->session->userdata("FullName");
                $agent["AddTime"] = date("Y-m-d H:i:s");

                $this->db->insert('tbl_agent', $agent);
                $res_message = 'Agent added successfully';
            }

            $res = ['success' => true, 'message' => $res_message, 'agentCode' => $this->mt->generateAgentCode()];
        } catch (Exception $ex) {
            $res = ['success' => false, 'message' => $ex->getMessage()];
        }

        echo json_encode($res);
    }

    public function updateAgent()
    {
        $res = ['success' => false, 'message' => ''];
        try {
            $agentObj = json_decode($this->input->post('data'));

            $agentMobileCount = $this->db->query("select * from tbl_agent where Agent_Mobile = ? and Agent_SlNo != ? and branch_id = ?", [$agentObj->Agent_Mobile, $agentObj->Agent_SlNo, $this->session->userdata("BRANCHid")])->num_rows();

            if ($agentMobileCount > 0) {
                $res = ['success' => false, 'message' => 'Mobile number already exists'];
                echo Json_encode($res);
                exit;
            }
            $agent = (array)$agentObj;
            $agentId = $agentObj->Agent_SlNo;

            unset($agent["Agent_SlNo"]);
            $agent["branch_id"] = $this->session->userdata("BRANCHid");
            $agent["UpdateBy"] = $this->session->userdata("FullName");
            $agent["UpdateTime"] = date("Y-m-d H:i:s");

            $this->db->where('Agent_SlNo', $agentId)->update('tbl_agent', $agent);

            $res = ['success' => true, 'message' => 'Agent updated successfully', 'agentCode' => $this->mt->generateAgentCode()];
        } catch (Exception $ex) {
            $res = ['success' => false, 'message' => $ex->getMessage()];
        }

        echo json_encode($res);
    }

    public function deleteAgent()
    {
        $res = ['success' => false, 'message' => ''];
        try {
            $data = json_decode($this->input->raw_input_stream);

            $this->db->query("update tbl_agent set status = 'd' where Agent_SlNo = ?", $data->agentId);

            $res = ['success' => true, 'message' => 'Agent deleted'];
        } catch (Exception $ex) {
            $res = ['success' => false, 'message' => $ex->getMessage()];
        }

        echo json_encode($res);
    }

    public function agentlist()
    {
        $access = $this->mt->userAccess();
        if (!$access) {
            redirect(base_url());
        }
        $data['title'] = "Agent List";
        $data['content'] = $this->load->view("Administrator/reports/agentList", $data, true);
        $this->load->view("Administrator/index", $data);
    }
}
