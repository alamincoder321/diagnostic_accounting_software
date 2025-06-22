<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Doctor extends CI_Controller
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
        $data['title'] = "Doctor Entry";
        $data['doctorCode'] = $this->mt->generateDoctorCode();
        $data['content'] = $this->load->view('Administrator/add_doctor', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function doctorList()
    {
        $access = $this->mt->userAccess();
        if (!$access) {
            redirect(base_url());
        }
        $data['title']  = 'Doctor List';
        $data['content'] = $this->load->view('Administrator/reports/doctorList', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function getDoctors()
    {
        $data = json_decode($this->input->raw_input_stream);

        $clauses = "";
        $limit = "";
        if (isset($data->forSearch) && $data->forSearch != '') {
            $limit .= "limit 20";
        }
        if (isset($data->name) && $data->name != '') {
            $clauses .= " and c.Doctor_Code like '$data->name%'";
            $clauses .= " or c.Doctor_Name like '$data->name%'";
            $clauses .= " or c.Doctor_Mobile like '$data->name%'";
        }

        $doctors = $this->db->query("
            select
                c.*,
                concat_ws(' - ', c.Doctor_Code, c.Doctor_Name, c.Doctor_Mobile) as display_name
            from tbl_doctor c
            where c.status != 'd'
            and c.Doctor_brunchid = ?
            $clauses
            order by c.Doctor_SlNo desc
            $limit
        ", $this->session->userdata('BRANCHid'))->result();
        echo json_encode($doctors);
    }

    public function getDoctorDue()
    {
        $data = json_decode($this->input->raw_input_stream);

        $clauses = "";
        if (isset($data->doctorId) && $data->doctorId != null) {
            $clauses .= " and c.Doctor_SlNo = '$data->doctorId'";
        }
        if (isset($data->districtId) && $data->districtId != null) {
            $clauses .= " and c.area_ID = '$data->districtId'";
        }

        $dueResult = $this->mt->doctorDue($clauses);

        echo json_encode($dueResult);
    }

    public function addDoctor()
    {
        $res = ['success' => false, 'message' => ''];
        try {
            $doctorObj = json_decode($this->input->post('data'));

            $doctorCodeCount = $this->db->query("select * from tbl_doctor where Doctor_Code = ?", $doctorObj->Doctor_Code)->num_rows();
            if ($doctorCodeCount > 0) {
                $doctorObj->Doctor_Code = $this->mt->generateDoctorCode();
            }

            $doctor = (array)$doctorObj;
            unset($doctor['Doctor_SlNo']);
            $doctor["Doctor_brunchid"] = $this->session->userdata("BRANCHid");

            $doctorId = null;
            $res_message = "";

            $duplicateMobileQuery = $this->db->query("select * from tbl_doctor where Doctor_Mobile = ? and Doctor_brunchid = ?", [$doctorObj->Doctor_Mobile, $this->session->userdata("BRANCHid")]);

            if ($duplicateMobileQuery->num_rows() > 0) {
                $duplicatedoctor = $duplicateMobileQuery->row();

                unset($doctor['Doctor_Code']);
                $doctor["UpdateBy"]   = $this->session->userdata("FullName");
                $doctor["UpdateTime"] = date("Y-m-d H:i:s");
                $doctor["status"]     = 'a';
                $this->db->where('Doctor_SlNo', $duplicatedoctor->Doctor_SlNo)->update('tbl_doctor', $doctor);

                $doctorId = $duplicatedoctor->Doctor_SlNo;
                $doctorObj->Doctor_Code = $duplicatedoctor->Doctor_Code;
                $res_message = 'Doctor updated successfully';
            } else {
                $doctor["AddBy"] = $this->session->userdata("FullName");
                $doctor["AddTime"] = date("Y-m-d H:i:s");

                $this->db->insert('tbl_doctor', $doctor);
                $doctorId = $this->db->insert_id();
                $res_message = 'Doctor added successfully';
            }


            if (!empty($_FILES)) {
                $config['upload_path'] = './uploads/doctors/';
                $config['allowed_types'] = 'gif|jpg|png';

                $imageName = $doctorObj->Doctor_Code;
                $config['file_name'] = $imageName;
                $this->load->library('upload', $config);
                $this->upload->do_upload('image');

                $config['image_library'] = 'gd2';
                $config['source_image'] = './uploads/doctors/' . $imageName;
                $config['new_image'] = './uploads/doctors/';
                $config['maintain_ratio'] = TRUE;
                $config['width']    = 200;
                $config['height']   = 200;

                $this->load->library('image_lib', $config);
                $this->image_lib->resize();

                $imageName = $doctorObj->Doctor_Code . $this->upload->data('file_ext');

                $this->db->query("update tbl_doctor set image_name = ? where Doctor_SlNo = ?", [$imageName, $doctorId]);
            }

            $res = ['success' => true, 'message' => $res_message, 'doctorCode' => $this->mt->generateDoctorCode()];
        } catch (Exception $ex) {
            $res = ['success' => false, 'message' => $ex->getMessage()];
        }

        echo json_encode($res);
    }

    public function updateDoctor()
    {
        $res = ['success' => false, 'message' => ''];
        try {
            $doctorObj = json_decode($this->input->post('data'));

            $doctorMobileCount = $this->db->query("select * from tbl_doctor where Doctor_Mobile = ? and Doctor_SlNo != ? and Doctor_brunchid = ?", [$doctorObj->Doctor_Mobile, $doctorObj->Doctor_SlNo, $this->session->userdata("BRANCHid")])->num_rows();

            if ($doctorMobileCount > 0) {
                $res = ['success' => false, 'message' => 'Mobile number already exists'];
                echo Json_encode($res);
                exit;
            }
            $doctor = (array)$doctorObj;
            $doctorId = $doctorObj->Doctor_SlNo;

            unset($doctor["Doctor_SlNo"]);
            $doctor["Doctor_brunchid"] = $this->session->userdata("BRANCHid");
            $doctor["UpdateBy"] = $this->session->userdata("FullName");
            $doctor["UpdateTime"] = date("Y-m-d H:i:s");

            $this->db->where('Doctor_SlNo', $doctorId)->update('tbl_doctor', $doctor);

            if (!empty($_FILES)) {
                $config['upload_path'] = './uploads/doctors/';
                $config['allowed_types'] = 'gif|jpg|png';

                $imageName = $doctorObj->Doctor_Code;
                $config['file_name'] = $imageName;
                $this->load->library('upload', $config);
                $this->upload->do_upload('image');

                $config['image_library'] = 'gd2';
                $config['source_image'] = './uploads/doctors/' . $imageName;
                $config['new_image'] = './uploads/doctors/';
                $config['maintain_ratio'] = TRUE;
                $config['width']    = 200;
                $config['height']   = 200;

                $this->load->library('image_lib', $config);
                $this->image_lib->resize();

                $imageName = $doctorObj->Doctor_Code . $this->upload->data('file_ext');

                $this->db->query("update tbl_doctor set image_name = ? where Doctor_SlNo = ?", [$imageName, $doctorId]);
            }

            $res = ['success' => true, 'message' => 'Doctor updated successfully', 'doctorCode' => $this->mt->generateDoctorCode()];
        } catch (Exception $ex) {
            $res = ['success' => false, 'message' => $ex->getMessage()];
        }

        echo json_encode($res);
    }

    public function deleteDoctor()
    {
        $res = ['success' => false, 'message' => ''];
        try {
            $data = json_decode($this->input->raw_input_stream);

            $this->db->query("update tbl_doctor set status = 'd' where Doctor_SlNo = ?", $data->doctorId);

            $res = ['success' => true, 'message' => 'Doctor deleted'];
        } catch (Exception $ex) {
            $res = ['success' => false, 'message' => $ex->getMessage()];
        }

        echo json_encode($res);
    }

    function doctor_due()
    {
        $access = $this->mt->userAccess();
        if (!$access) {
            redirect(base_url());
        }
        $data['title'] = 'Doctor Due';
        $data['content'] = $this->load->view('Administrator/due_report/doctor_due', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }
}
