<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dialysis extends CI_Controller
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
        $data['title'] = "Dialysis Form Entry";
        $data['invoice'] = $this->mt->generateDialysisInvoice();
        $data['content'] = $this->load->view('Administrator/dialysis/add_dialysis', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function getDialysis()
    {
        $data = json_decode($this->input->raw_input_stream);

        $clauses = "";
        if (!empty($data->dialysisId)) {
            $clauses .= " and dl.id = '$data->dialysisId'";
        }

        $dialysis = $this->db
            ->query("select 
                dl.*,
                p.Customer_Code,
                p.Customer_Name,
                p.Customer_Mobile,
                p.Customer_Address,
                p.age,
                p.gender,
                concat_ws(' - ', dl.invoice, p.Customer_Name) as display_name
                from tbl_dialysis dl
                left join tbl_customer p on p.Customer_SlNo = dl.patient_id
                where dl.status != 'd'
                $clauses
                order by dl.id desc")->result();

        foreach ($dialysis as $key => $item) {
            $item->details = $this->db
                ->query("select
                        dld.*
                        from tbl_dialysis_details dld
                        where dld.status = 'a'
                        and dld.dialysis_id = ?", [$item->id])->result();
        }

        echo json_encode($dialysis);
    }

    public function addDialysis()
    {
        $res = ['success' => false, 'message' => ''];
        $this->db->trans_begin();
        try {
            $data = json_decode($this->input->raw_input_stream);

            $dialysis = (array)$data->dialysis;
            unset($dialysis['id']);
            $dialysis['AddBy']     = $this->session->userdata("FullName");
            $dialysis['AddTime']   = date("Y-m-d H:i:s");
            $dialysis['branch_id'] = $this->session->userdata("BRANCHid");

            $this->db->insert('tbl_dialysis', $dialysis);
            $dialysisId = $this->db->insert_id();

            foreach ($data->carts as $key => $cart) {
                $detail                = (array)$cart;
                $detail['dialysis_id'] = $dialysisId;
                $detail['AddBy']       = $this->session->userdata("FullName");
                $detail['AddTime']     = date("Y-m-d H:i:s");
                $detail['branch_id']   = $this->session->userdata("BRANCHid");
                $this->db->insert('tbl_dialysis_details', $detail);
            }

            $this->db->trans_commit();
            $res = ['success' => true, 'message' => 'Dialysis Form entry successful'];
        } catch (\Throwable $th) {
            $this->db->trans_rollback();
            $res = ['success' => true, 'message' => $th->getMessage()];
        }

        echo json_encode($res);
    }

    public function updateDialysis()
    {
        $res = ['success' => false, 'message' => ''];
        $this->db->trans_begin();
        try {
            $data       = json_decode($this->input->raw_input_stream);
            $dialysisId = $data->dialysis->id;

            $dialysis = (array)$data->dialysis;
            unset($dialysis['id']);
            $dialysis['UpdateBy']     = $this->session->userdata("FullName");
            $dialysis['UpdateTime']   = date("Y-m-d H:i:s");
            $this->db->where('id', $dialysisId)->update('tbl_dialysis', $dialysis);

            $this->db->where('dialysis_id', $dialysisId)->delete("tbl_dialysis_details");
            foreach ($data->carts as $key => $cart) {
                $detail                = (array)$cart;
                $detail['dialysis_id'] = $dialysisId;
                $detail['AddBy']       = $this->session->userdata("FullName");
                $detail['AddTime']     = date("Y-m-d H:i:s");
                $detail['UpdateBy']    = $this->session->userdata("FullName");
                $detail['UpdateTime']  = date("Y-m-d H:i:s");
                $detail['branch_id']   = $this->session->userdata("BRANCHid");
                $this->db->insert('tbl_dialysis_details', $detail);
            }

            $this->db->trans_commit();
            $res = ['success' => true, 'message' => 'Dialysis Form update successful'];
        } catch (\Throwable $th) {
            $this->db->trans_rollback();
            $res = ['success' => true, 'message' => $th->getMessage()];
        }

        echo json_encode($res);
    }

    public function deleteDialysis()
    {
        $res = ['success' => false, 'message' => ''];
        try {
            $data = json_decode($this->input->raw_input_stream);

            $this->db->set(['UpdateBy' => $this->session->userdata("FullName"), 'UpdateTime' => date("Y-m-d H:i:s"), 'status' => 'd'])->where('id', $data->dialysisId)->update('tbl_dialysis');
            $this->db->set(['UpdateBy' => $this->session->userdata("FullName"), 'UpdateTime' => date("Y-m-d H:i:s"), 'status' => 'd'])->where('dialysis_id', $data->dialysisId)->update('tbl_dialysis_details');

            $res = ['success' => true, 'message' => 'Dialysis deleted successfully'];
        } catch (Exception $ex) {
            $res = ['success' => false, 'message' => $ex->getMessage()];
        }

        echo json_encode($res);
    }

    public function dialysisList()
    {
        $access = $this->mt->userAccess();
        if (!$access) {
            redirect(base_url());
        }
        $data['title'] = "Dialysis List";
        $data['content'] = $this->load->view('Administrator/dialysis/dialysis_list', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function dialysisInvoice()
    {
        $data['title']   = 'Dialysis Invoice';
        $data['content'] = $this->load->view('Administrator/dialysis/dialysis_invoice', $data, true);
        $this->load->view('Administrator/index', $data);
    }
    
    public function dialysisInvoicePrint($id)
    {
        $data['title']      = 'Dialysis Invoice';
        $data['dialysisId'] = $id;
        $data['content']    = $this->load->view('Administrator/dialysis/dialysis_invoice_print', $data, true);
        $this->load->view('Administrator/index', $data);
    }
}
