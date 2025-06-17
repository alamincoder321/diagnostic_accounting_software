<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class ReportGenerate extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->branchId = $this->session->userdata('BRANCHid');
        $access = $this->session->userdata('userId');
        if ($access == '') {
            redirect("Login");
        }
        $this->load->model('Model_table', "mt", TRUE);
    }

    public function index()
    {
        $access = $this->mt->userAccess();
        if (!$access) {
            redirect(base_url());
        }
        $data['title'] = "Report Generate";
        $data['content'] = $this->load->view('Administrator/sales/report_generate', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function getReportTest()
    {
        $data = json_decode($this->input->raw_input_stream);
        $check = $this->db
            ->query("select
                    ifnull(rpd.subcategory_id, sc.id) as subcategory_id, 
                    sc.name,
                    rpd.result,
                    u.Unit_Name,
                    sc.normal_range
                    from tbl_report_generate_detail rpd
                    left join tbl_subcategory sc on sc.id = rpd.subcategory_id
                    left join tbl_unit u on u.Unit_SlNo = sc.unit_id
                    left join tbl_report_generate rp on rp.id = rpd.generate_id
                    where rp.test_id = ?
                    and rp.sale_id = ?
                    and rp.patient_id = ?", [$data->testId, $data->saleId, $data->customerId])->result();

        $category = $this->db
            ->select("sc.id as subcategory_id, sc.name, '' as result, u.Unit_Name, sc.normal_range")
            ->join("tbl_unit as u", "u.Unit_SlNo = sc.unit_id", "left")
            ->where('sc.test_id', $data->testId)
            ->get('tbl_subcategory as sc')
            ->result();

        if (count($check) > 0) {
            $category = $check;
        }
        echo json_encode($category);
    }

    public function addReportGenerate()
    {
        $msg = ['success' => false, 'message' => ''];
        try {
            $this->db->trans_begin();
            $data = json_decode($this->input->raw_input_stream);

            $check = $this->db
                ->where('sale_id', $data->report->sale_id)
                ->where('patient_id', $data->report->patient_id)
                ->where('test_id', $data->report->test_id)
                ->get('tbl_report_generate')
                ->row();
            if (empty($check)) {
                $report = (array)$data->report;
                $report['AddBy'] = $this->session->userdata('FullName');
                $report['AddTime'] = date("Y-m-d H:i:s");
                $report['branch_id'] = $this->branchId;
                $this->db->insert("tbl_report_generate", $report);
                $reportId = $this->db->insert_id();

                foreach ($data->carts as $item) {
                    $detail = array(
                        'generate_id'    => $reportId,
                        'subcategory_id' => $item->subcategory_id,
                        'result'         => $item->result
                    );
                    $this->db->insert('tbl_report_generate_detail', $detail);
                }
                $msg = 'Report Generate Success';
            } else {
                $report               = (array)$data->report;
                $report['UpdateBy']   = $this->session->userdata('FullName');
                $report['UpdateTime'] = date("Y-m-d H:i:s");
                $report['branch_id']  = $this->branchId;
                $this->db->where('id', $check->id)->update("tbl_report_generate", $report);

                $this->db->query("delete from tbl_report_generate_detail where generate_id = ?", [$check->id]);
                foreach ($data->carts as $item) {
                    $detail = array(
                        'generate_id' => $check->id,
                        'subcategory_id' => $item->subcategory_id,
                        'result' => $item->result
                    );
                    $this->db->insert('tbl_report_generate_detail', $detail);
                }
                $msg = 'Report Generate Update Success';
            }


            $this->db->trans_commit();
            $msg = ['success' => true, 'message' => $msg];
        } catch (\Throwable $th) {
            $this->db->trans_rollback();
            $msg = ['success' => false, 'message' => 'Something went wrong'];
        }

        echo json_encode($msg);
    }
}
