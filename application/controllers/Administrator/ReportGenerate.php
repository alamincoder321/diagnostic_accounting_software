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
        $data['id'] = 0;
        $data['content'] = $this->load->view('Administrator/sales/report_generate', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function reportEdit($id)
    {
        $access = $this->mt->userAccess();
        if (!$access) {
            redirect(base_url());
        }
        $data['title'] = "Report Generate Update";
        $data['id'] = $id;
        $data['content'] = $this->load->view('Administrator/sales/report_generate', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function getReportTest()
    {
        $data = json_decode($this->input->raw_input_stream);
        $check = $this->db
            ->query("select
                    rpd.category_id,
                    rpd.test_id,
                    rpd.subtest_id,
                    ifnull(st.name, t.Product_Name) as name,
                    rpd.result,
                    ifnull(su.Unit_Name, u.Unit_Name) as Unit_Name,
                    ifnull(st.normal_range, t.normal_range) as normal_range
                    from tbl_report_generate_detail rpd
                    left join tbl_product t on t.Product_SlNo = rpd.test_id
                    left join tbl_unit u on u.Unit_SlNo = t.Unit_ID
                    left join tbl_subtest st on st.id = rpd.subtest_id
                    left join tbl_unit su on su.Unit_SlNo = st.unit_id
                    left join tbl_report_generate rp on rp.id = rpd.generate_id
                    where rp.category_id = ?
                    and rp.sale_id = ?
                    and rp.patient_id = ?", [$data->categoryId, $data->saleId, $data->customerId])->result();

        $sql = "
                SELECT
                t.ProductCategory_ID AS category_id,
                sd.Product_IDNo      AS test_id,
                NULL                 AS subtest_id,
                t.Product_Name       AS name,
                ''                   AS result,
                u.Unit_Name          AS Unit_Name,
                t.normal_range       AS normal_range
                FROM tbl_saledetails sd
                LEFT JOIN tbl_product t ON t.Product_SlNo = sd.Product_IDNo
                LEFT JOIN tbl_unit    u ON u.Unit_SlNo    = t.Unit_ID
                WHERE t.ProductCategory_ID = ? AND sd.SaleMaster_IDNo = ?

                UNION ALL

                SELECT
                t.ProductCategory_ID AS category_id,
                sd.Product_IDNo      AS test_id,
                st.id                AS subtest_id,
                st.name              AS name,
                ''                   AS result,
                su.Unit_Name AS Unit_Name,
                st.normal_range       AS normal_range
                FROM tbl_saledetails sd
                LEFT JOIN tbl_product  t  ON t.Product_SlNo = sd.Product_IDNo
                LEFT JOIN tbl_subtest  st ON st.test_id  = t.Product_SlNo
                LEFT JOIN tbl_unit     su ON su.Unit_SlNo   = st.unit_id
                WHERE t.ProductCategory_ID = ? AND sd.SaleMaster_IDNo = ?
                ";

        $test = $this->db->query($sql, [
            $data->categoryId,
            $data->saleId,
            $data->categoryId,
            $data->saleId
        ])->result();



        if (count($check) > 0) {
            $test = $check;
        }

        echo json_encode($test);
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
                ->where('category_id', $data->report->category_id)
                ->get('tbl_report_generate')
                ->row();
            if (empty($check)) {
                $report = (array)$data->report;
                unset($report['id']);
                unset($report['patient_id']);
                $report['patient_id'] = empty($data->report->patient_id) ? NULL : $data->report->patient_id;
                $report['invoice'] = $this->mt->generateReportInvoice();
                $report['AddBy'] = $this->session->userdata('FullName');
                $report['AddTime'] = date("Y-m-d H:i:s");
                $report['branch_id'] = $this->branchId;
                $this->db->insert("tbl_report_generate", $report);
                $reportId = $this->db->insert_id();

                foreach ($data->carts as $item) {
                    $detail = array(
                        'generate_id' => $reportId,
                        'category_id' => $item->category_id,
                        'test_id'     => $item->test_id,
                        'subtest_id'  => $item->subtest_id ?? NULL,
                        'result'      => $item->result
                    );
                    $this->db->insert('tbl_report_generate_detail', $detail);
                }
                $this->db->trans_commit();
                $msg = 'Report Generate Success';
                $msg = ['success' => true, 'message' => $msg];
            } else {
                $msg = 'Already Generated this report for this patient';
                $msg = ['success' => false, 'message' => $msg];
            }
        } catch (\Throwable $th) {
            $this->db->trans_rollback();
            $msg = ['success' => false, 'message' => 'Something went wrong'];
        }

        echo json_encode($msg);
    }
    public function updateReportGenerate()
    {
        $msg = ['success' => false, 'message' => ''];
        try {
            $this->db->trans_begin();
            $data = json_decode($this->input->raw_input_stream);

            $report               = (array)$data->report;
            unset($report['id']);
            unset($report['patient_id']);
            $report['patient_id'] = empty($data->report->patient_id) ? NULL : $data->report->patient_id;
            $report['UpdateBy']   = $this->session->userdata('FullName');
            $report['UpdateTime'] = date("Y-m-d H:i:s");
            $report['branch_id']  = $this->branchId;
            $this->db->where('id', $data->report->id)->update("tbl_report_generate", $report);

            $this->db->query("delete from tbl_report_generate_detail where generate_id = ?", [$data->report->id]);
            foreach ($data->carts as $item) {
                $detail = array(
                    'generate_id' => $data->report->id,
                    'category_id' => $item->category_id,
                    'test_id'     => $item->test_id,
                    'subtest_id'  => $item->subtest_id ?? NULL,
                    'result'      => $item->result
                );
                $this->db->insert('tbl_report_generate_detail', $detail);
            }
            $this->db->trans_commit();

            $msg = 'Report Generate Update Success';
            $msg = ['success' => true, 'message' => $msg];
        } catch (\Throwable $th) {
            $this->db->trans_rollback();
            $msg = ['success' => false, 'message' => 'Something went wrong'];
        }

        echo json_encode($msg);
    }

    public function reportInvoice()
    {
        $data['title']  = 'Report Invoice';
        $data['content'] = $this->load->view('Administrator/sales/report_invoice', $data, true);
        $this->load->view('Administrator/index', $data);
    }

    public function reportInvoicePrint($id)
    {
        $data['title']  = 'Report Invoice';
        $data['reportId']  = $id;
        $data['content'] = $this->load->view('Administrator/sales/reportAndreport', $data, true);
        $this->load->view('Administrator/index', $data);
    }

    public function reportlist()
    {
        $data['title']  = 'Report List';
        $data['content'] = $this->load->view('Administrator/sales/report_list', $data, true);
        $this->load->view('Administrator/index', $data);
    }

    public function getReportList()
    {
        $data = json_decode($this->input->raw_input_stream);

        $clauses = "";
        if (!empty($data->reportId)) {
            $clauses .= " and rp.id = '$data->reportId'";
        }
        if (!empty($data->dateFrom) && !empty($data->dateTo)) {
            $clauses .= " and rp.date between '$data->dateFrom' and '$data->dateTo'";
        }

        $reports = $this->db->query("select 
                rp.*, 
                ifnull(p.Customer_Code, 'General Patient') as Customer_Code, 
                ifnull(p.Customer_Name, sm.customerName) as Customer_Name, 
                ifnull(p.Customer_Address, sm.customerAddress) as Customer_Address, 
                ifnull(p.Customer_Mobile, sm.customerMobile) as Customer_Mobile, 
                pc.ProductCategory_Name,
                sm.SaleMaster_InvoiceNo,
                concat_ws('-', rp.invoice, ifnull(p.Customer_Name, sm.customerName)) as invoice_text
            from tbl_report_generate rp
            left join tbl_customer p on p.Customer_SlNo = rp.patient_id
            left join tbl_productcategory pc on pc.ProductCategory_SlNo = rp.category_id
            left join tbl_salesmaster sm on sm.SaleMaster_SlNo = rp.sale_id
            where rp.status = 'a'
            $clauses
            order by rp.id desc")->result();

        $reports = array_map(function ($report) {
            $report->details = $this->db
                ->select("rpd.category_id, rpd.test_id, rpd.subtest_id, ifnull(st.name, t.Product_Name) as name, rpd.result, ifnull(su.Unit_Name, u.Unit_Name) as Unit_Name, ifnull(st.normal_range, t.normal_range) as normal_range")
                ->join("tbl_product as t", "t.Product_SlNo = rpd.test_id", "left")
                ->join("tbl_unit as u", "u.Unit_SlNo = t.Unit_ID", "left")
                ->join("tbl_subtest as st", "st.id = rpd.subtest_id", "left")
                ->join("tbl_unit as su", "su.Unit_SlNo = st.Unit_ID", "left")
                ->where('rpd.generate_id', $report->id)
                ->get('tbl_report_generate_detail as rpd')
                ->result();

            return $report;
        }, $reports);

        echo json_encode($reports);
    }

    public function deleteReportGenerate()
    {
        $msg = ['success' => false, 'message' => ''];
        try {
            $data = json_decode($this->input->raw_input_stream);
            $this->db->where('id', $data->reportId)->delete('tbl_report_generate');
            $this->db->where('generate_id', $data->reportId)->delete('tbl_report_generate_detail');
            $msg = ['success' => true, 'message' => 'Report Generate Deleted Successfully'];
        } catch (\Throwable $th) {
            $msg = ['success' => false, 'message' => 'Something went wrong'];
        }
        echo json_encode($msg);
    }

    public function reportDelivery()
    {
        $msg = ['success' => false, 'message' => ''];
        try {
            $data = json_decode($this->input->raw_input_stream);
            $this->db->where('id', $data->reportId)->update(
                'tbl_report_generate',
                [
                    'is_delivery' => 'yes',
                    'delivery_date' => date('Y-m-d'),
                    'UpdateBy' => $this->session->userdata('FullName'),
                    'UpdateTime' => date('Y-m-d H:i:s')
                ]
            );
            $msg = ['success' => true, 'message' => 'Report Delivered Successfully'];
        } catch (\Throwable $th) {
            $msg = ['success' => false, 'message' => 'Something went wrong'];
        }
        echo json_encode($msg);
    }
}
