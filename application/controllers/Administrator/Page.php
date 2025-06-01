<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Page extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->brunch = $this->session->userdata('BRANCHid');
        $access = $this->session->userdata('userId');
        if ($access == '') {
            redirect("Login");
        }
        $this->load->model('Billing_model');
        $this->load->model("Model_myclass", "mmc", TRUE);
        $this->load->model('Model_table', "mt", TRUE);
        date_default_timezone_set('Asia/Dhaka');
    }
    public function index()
    {
        $data['title'] = "Dashboard";
        $data['content'] = $this->load->view('Administrator/dashboard', $data, TRUE);
        $this->load->view('Administrator/master_dashboard', $data);
    }
    public function module($value)
    {
        $data['title'] = "Dashboard";

        $sdata['module'] = $value;
        $this->session->set_userdata($sdata);

        $data['content'] = $this->load->view('Administrator/dashboard', $data, TRUE);
        $this->load->view('Administrator/master_dashboard', $data);
    }

    // Product Category 
    public function getCategories()
    {
        $categories = $this->db->query("select * from tbl_productcategory where status = 'a'")->result();
        echo json_encode($categories);
    }

    public function add_category()
    {
        $access = $this->mt->userAccess();
        if (!$access) {
            redirect(base_url());
        }
        $data['title'] = "Add Category";
        $data['content'] = $this->load->view('Administrator/add_prodcategory', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }
    public function insert_category()
    {
        $data = json_decode($this->input->raw_input_stream);
        $query = $this->db->query("select * from tbl_productcategory where ProductCategory_Name = '$data->ProductCategory_Name'");

        if ($query->num_rows() > 0) {
            $msg = array("status" => false, "message" => "Already Exist this name");
            echo json_encode($msg);
        } else {
            $category = array(
                "ProductCategory_Name" => $data->ProductCategory_Name,
                "status"               => 'a',
                "AddBy"                => $this->session->userdata("FullName"),
                "AddTime"              => date("Y-m-d H:i:s")
            );
            $this->db->insert("tbl_productcategory", $category);

            $msg = array("status" => true, "message" => "Category insert successfully");
            echo json_encode($msg);
        }
    }

    public function update_category()
    {
        $data = json_decode($this->input->raw_input_stream);
        $query = $this->db->query("select * from tbl_productcategory where ProductCategory_Name = '$data->ProductCategory_Name' and ProductCategory_SlNo != ?", $data->ProductCategory_SlNo);

        if ($query->num_rows() > 0) {
            $msg = array("status" => false, "message" => "Already Exist this name");
            echo json_encode($msg);
        } else {
            $category = array(
                "ProductCategory_Name" => $data->ProductCategory_Name,
                "status"               => 'a',
                "AddBy"                => $this->session->userdata("FullName"),
                "AddTime"              => date("Y-m-d H:i:s")
            );
            $this->db->where('ProductCategory_SlNo', $data->ProductCategory_SlNo)->update("tbl_productcategory", $category);

            $msg = array("status" => true, "message" => "Category update successfully");
            echo json_encode($msg);
        }
    }
    public function catdelete()
    {
        $data = json_decode($this->input->raw_input_stream);
        $this->db->where("ProductCategory_SlNo", $data->categoryId)->update('tbl_productcategory', ['status' => 'd']);
        $msg = array("status" => true, "message" => "Category delete successfully");
        echo json_encode($msg);
    }

    // Sub Category 
    public function getSubCategories()
    {
        $subcategories = $this->db
            ->query("select sc.*,  
                pc.ProductCategory_Name,
                u.Unit_Name
                from tbl_subcategory sc 
                left join tbl_productcategory pc on pc.ProductCategory_SlNo = sc.category_id
                left join tbl_unit u on u.Unit_SlNo = sc.unit_id
                where sc.status = 'a'")->result();
        echo json_encode($subcategories);
    }

    public function add_subcategory()
    {
        $access = $this->mt->userAccess();
        if (!$access) {
            redirect(base_url());
        }
        $data['title'] = "Add SubCategory";
        $data['content'] = $this->load->view('Administrator/add_subcategory', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }
    public function insert_subcategory()
    {
        $data = json_decode($this->input->raw_input_stream);
        $query = $this->db->query("select * from tbl_subcategory where category_id = ? and name = '$data->name'", $data->category_id);

        if ($query->num_rows() > 0) {
            $msg = array("status" => false, "message" => "Already Exist this name");
            echo json_encode($msg);
        } else {
            $category = array(
                "category_id"  => $data->category_id,
                "unit_id"      => $data->unit_id,
                "name"         => $data->name,
                "normal_range" => $data->normal_range,
                "status"       => 'a',
                "AddBy"        => $this->session->userdata("FullName"),
                "AddTime"      => date("Y-m-d H:i:s")
            );
            $this->db->insert("tbl_subcategory", $category);

            $msg = array("status" => true, "message" => "SubCategory insert successfully");
            echo json_encode($msg);
        }
    }

    public function update_subcategory()
    {
        $data = json_decode($this->input->raw_input_stream);
        $query = $this->db->query("select * from tbl_subcategory where name = '$data->name' and id != ? and category_id = ?", [$data->id, $data->category_id]);

        if ($query->num_rows() > 0) {
            $msg = array("status" => false, "message" => "Already Exist this name");
            echo json_encode($msg);
        } else {
            $category = array(
                "category_id"  => $data->category_id,
                "unit_id"      => $data->unit_id,
                "name"         => $data->name,
                "normal_range" => $data->normal_range,
                "status"       => 'a',
                "AddBy"        => $this->session->userdata("FullName"),
                "AddTime"      => date("Y-m-d H:i:s")
            );
            $this->db->where('id', $data->id)->update("tbl_subcategory", $category);

            $msg = array("status" => true, "message" => "SubCategory update successfully");
            echo json_encode($msg);
        }
    }
    public function subcatdelete()
    {
        $data = json_decode($this->input->raw_input_stream);
        $this->db->where("id", $data->subcategoryId)->update('tbl_subcategory', ['status' => 'd']);
        $msg = array("status" => true, "message" => "SubCategory delete successfully");
        echo json_encode($msg);
    }

    //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^ Unit 
    public function unit()
    {
        $access = $this->mt->userAccess();
        if (!$access) {
            redirect(base_url());
        }
        $data['title'] = "Add Unit";
        $data['content'] = $this->load->view('Administrator/unit', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }
    public function insert_unit()
    {
        $data = json_decode($this->input->raw_input_stream);
        $query = $this->db->query("select Unit_Name from tbl_unit where Unit_Name = '$data->Unit_Name'");

        if ($query->num_rows() > 0) {
            $msg = array("status" => false, "message" => "Already Exist this name");
            echo json_encode($msg);
        } else {
            $unit = array(
                "Unit_Name" => $data->Unit_Name,
                "status"    => 'a',
                "AddBy"     => $this->session->userdata("FullName"),
                "AddTime"   => date("Y-m-d H:i:s")
            );
            $this->db->insert("tbl_unit", $unit);

            $msg = array("status" => true, "message" => "Unit insert successfully");
            echo json_encode($msg);
        }
    }
    public function unitupdate()
    {
        $data = json_decode($this->input->raw_input_stream);
        $query = $this->db->query("select Unit_Name from tbl_unit where Unit_SlNo != ? and Unit_Name = '$data->Unit_Name'", $data->Unit_SlNo);

        if ($query->num_rows() > 0) {
            $msg = array("status" => false, "message" => "Already Exist this name");
            echo json_encode($msg);
        } else {
            $unit = array(
                "Unit_Name" => $data->Unit_Name,
                "UpdateBy"     => $this->session->userdata("FullName"),
                "UpdateTime"   => date("Y-m-d H:i:s")
            );
            $this->db->where('Unit_SlNo', $data->Unit_SlNo)->update("tbl_unit", $unit);

            $msg = array("status" => true, "message" => "Unit update successfully");
            echo json_encode($msg);
        }
    }
    public function unitdelete()
    {
        $data = json_decode($this->input->raw_input_stream);
        $this->db->where("Unit_SlNo", $data->unitId)->update('tbl_unit', ['status' => 'd']);
        $msg = array("status" => true, "message" => "Unit delete successfully");
        echo json_encode($msg);
    }

    public function getUnits()
    {
        $units = $this->db->query("select * from tbl_unit where status = 'a'")->result();
        echo json_encode($units);
    }

    //======= Area ================ 
    public function area()
    {
        $access = $this->mt->userAccess();
        if (!$access) {
            redirect(base_url());
        }
        $data['title'] = "Add Area";
        $data['content'] = $this->load->view('Administrator/add_area', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }
    public function insert_area()
    {
        $data = json_decode($this->input->raw_input_stream);
        $query = $this->db->query("select District_Name from tbl_district where District_Name = '$data->District_Name'");

        if ($query->num_rows() > 0) {
            $msg = array("status" => false, "message" => "Already Exist this name");
            echo json_encode($msg);
        } else {
            $area = array(
                "District_Name"          => $data->District_Name,
                "AddBy"                  => $this->session->userdata("FullName"),
                "AddTime"                => date("Y-m-d H:i:s")
            );
            $this->db->insert('tbl_district', $area);
            $msg = array("status" => true, "message" => "Area entry successfully");
            echo json_encode($msg);
        }
    }
    public function areaupdate()
    {
        $data = json_decode($this->input->raw_input_stream);
        $query = $this->db->query("select District_Name from tbl_district where District_SlNo != ? and District_Name = '$data->District_Name'", $data->District_SlNo);

        if ($query->num_rows() > 0) {
            $msg = array("status" => false, "message" => "Already Exist this name");
            echo json_encode($msg);
        } else {
            $area = array(
                "District_Name" => $data->District_Name,
                "UpdateBy"      => $this->session->userdata("FullName"),
                "UpdateTime"    => date("Y-m-d H:i:s")
            );
            $this->db->where('District_SlNo', $data->District_SlNo)->update('tbl_district', $area);
            $msg = array("status" => true, "message" => "Area update successfully");
            echo json_encode($msg);
        }
    }
    public function areadelete()
    {
        $data = json_decode($this->input->raw_input_stream);
        $this->db->where("District_SlNo", $data->districtId)->update('tbl_district', ['status' => 'd']);
        $msg = array("status" => true, "message" => "Area delete successfully");
        echo json_encode($msg);
    }

    public function getDistricts()
    {
        $districts = $this->db->query("select * from tbl_district d where d.status = 'a'")->result();
        echo json_encode($districts);
    }

    //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
    //Company Profile

    public function getCompanyProfile()
    {
        $companyProfile = $this->db->query("select * from tbl_company order by Company_SlNo desc limit 1")->row();
        echo json_encode($companyProfile);
    }

    public function company_profile()
    {
        $access = $this->mt->userAccess();
        if (!$access) {
            redirect(base_url());
        }
        $data['title'] = "Company Profile";
        $data['selected'] = $this->db->query("
            select * from tbl_company order by Company_SlNo desc limit 1
        ")->row();
        $data['content'] = $this->load->view('Administrator/company_profile', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function company_profile_insert()
    {
        $id = $this->brunch;
        $inpt = $this->input->post('inpt', true);
        $fld = 'company_BrunchId';
        $this->load->library('upload');
        $config['upload_path'] = './uploads/company_profile_org/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = '10000';
        $config['image_width'] = '300';
        $config['image_height'] = '300';
        $this->upload->initialize($config);

        $data['Company_Name'] =  $this->input->post('Company_name', true);
        $data['Repot_Heading'] =  $this->input->post('Description', true);

        $xx = $this->mt->select_by_id("tbl_company", $id, $fld);

        $image = $this->upload->do_upload('companyLogo');
        $images = $this->upload->data();

        if ($image != "") {
            if ($xx['Company_Logo_thum'] && $xx['Company_Logo_org']) {
                unlink("./uploads/company_profile_thum/" . $xx['Company_Logo_thum']);
                unlink("./uploads/company_profile_org/" . $xx['Company_Logo_org']);
            }
            $data['Company_Logo_org'] = $images['file_name'];

            $config['image_library'] = 'gd2';
            $config['source_image'] = $this->upload->upload_path . $this->upload->file_name;
            $config['new_image'] = 'uploads/' . 'company_profile_thum/' . $this->upload->file_name;
            $config['maintain_ratio'] = FALSE;
            $config['width'] = 165;
            $config['height'] = 175;
            $this->load->library('image_lib', $config);
            $this->image_lib->resize();
            $data['Company_Logo_thum'] = $this->upload->file_name;
        } else {

            $data['Company_Logo_org'] = $xx['Company_Logo_org'];
            $data['Company_Logo_thum'] = $xx['Company_Logo_thum'];
        }
        $data['print_type'] = $inpt;
        $data['company_BrunchId'] = $this->brunch;
        $this->mt->save_data("tbl_company", $data, $id, $fld);
        $id = '1';
        redirect('Administrator/Page/company_profile');
        //$this->load->view('Administrator/company_profile');
    }

    public function company_profile_Update()
    {
        $data['Company_Name'] =  $this->input->post('Company_name', true);
        $data['Repot_Heading'] =  $this->input->post('Description', true);
        $data['print_type'] = $this->input->post('inpt', true);

        if (isset($_FILES['companyLogo']) && $_FILES['companyLogo']['error'] != UPLOAD_ERR_NO_FILE) {

            $files = glob('./uploads/company_profile_org/*');
            foreach ($files as $file) {
                if (is_file($file))
                    unlink($file);
            }

            $files = glob('./uploads/company_profile_thum/*');
            foreach ($files as $file) {
                if (is_file($file))
                    unlink($file);
            }

            $this->load->library('upload');
            $config = array();
            $config['upload_path']      = './uploads/company_profile_org/';
            $config['allowed_types']    = 'png|jpg|jpeg|gif';
            $config['max_size']         = '0';
            $config['file_name']        = 'company_logo';
            $config['overwrite']        = FALSE;

            $this->upload->initialize($config);
            $this->upload->do_upload('companyLogo');
            $image = $this->upload->data();

            $data['Company_Logo_org'] = $image['file_name'];

            $config['image_library'] = 'gd2';
            $config['source_image'] = $this->upload->upload_path . $this->upload->file_name;
            $config['new_image'] = 'uploads/company_profile_thum/' . $this->upload->file_name;
            $config['maintain_ratio'] = FALSE;
            $config['width'] = 200;
            $config['height'] = 200;
            $this->load->library('image_lib', $config);
            $this->image_lib->resize();
            $data['Company_Logo_thum'] = $this->upload->file_name;
        }
        $this->db->update('tbl_company', $data);
        redirect('Administrator/Page/company_profile');
    }

    //^^^^^^^^^^^^^^^^^^^^^
    // Brunch Name

    public function getBranches()
    {
        $branches = $this->db->query("
            select 
            *,
            case status
                when 'a' then 'Active'
                else 'Inactive'
            end as active_status
            from tbl_brunch
        ")->result();
        echo json_encode($branches);
    }

    public function getCurrentBranch()
    {
        $branch = $this->Billing_model->company_branch_profile($this->brunch);
        echo json_encode($branch);
    }

    public function changeBranchStatus()
    {
        $res = ['success' => false, 'message' => ''];
        try {
            $data = json_decode($this->input->raw_input_stream);
            $status = $this->db->query("select * from tbl_brunch where brunch_id = ?", $data->branchId)->row()->status;
            $status = $status == 'a' ? 'd' : 'a';
            $this->db->set('status', $status)->where('brunch_id', $data->branchId)->update('tbl_brunch');
            $res = ['success' => true, 'message' => 'Status changed'];
        } catch (Exception $ex) {
            $res = ['success' => false, 'message' => $ex->getMessage()];
        }

        echo json_encode($res);
    }

    public function brunch()
    {
        $access = $this->mt->userAccess();
        if (!$access) {
            redirect(base_url());
        }
        $data['title'] = "Add Brunch";
        $data['content'] = $this->load->view('Administrator/brunch/add_brunch', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }
    public function addBranch()
    {
        $res = ['success' => false, 'message' => ''];
        try {
            $branch = json_decode($this->input->raw_input_stream);

            $nameCount = $this->db->query("select * from tbl_brunch where Brunch_name = ?", $branch->name)->num_rows();
            if ($nameCount > 0) {
                $res = ['success' => false, 'message' => $branch->name . ' already exists'];
                echo json_encode($res);
                exit;
            }

            $newBranch = array(
                'Brunch_name' => $branch->name,
                'Brunch_title' => $branch->title,
                'Brunch_address' => $branch->address,
                'Brunch_sales' => '2',
                'add_by' => $this->session->userdata("FullName"),
                'add_time' => date('Y-m-d H:i:s'),
                'status' => 'a'
            );

            $this->db->insert('tbl_brunch', $newBranch);
            $res = ['success' => true, 'message' => 'Branch added'];
        } catch (Exception $ex) {
            $res = ['success' => false, 'message' => $ex->getMessage()];
        }

        echo json_encode($res);
    }

    public function updateBranch()
    {
        $res = ['success' => false, 'message' => ''];
        try {
            $branch = json_decode($this->input->raw_input_stream);

            $nameCount = $this->db->query("select * from tbl_brunch where Brunch_name = ? and brunch_id != ?", [$branch->name, $branch->branchId])->num_rows();
            if ($nameCount > 0) {
                $res = ['success' => false, 'message' => $branch->name . ' already exists'];
                echo json_encode($res);
                exit;
            }

            $newBranch = array(
                'Brunch_name' => $branch->name,
                'Brunch_title' => $branch->title,
                'Brunch_address' => $branch->address,
                'update_by' => $this->session->userdata("FullName")
            );

            $this->db->set($newBranch)->where('brunch_id', $branch->branchId)->update('tbl_brunch');
            $res = ['success' => true, 'message' => 'Branch updated'];
        } catch (Exception $ex) {
            $res = ['success' => false, 'message' => $ex->getMessage()];
        }

        echo json_encode($res);
    }

    public function brunch_edit()
    {
        $id = $this->input->post('edit');
        $query = $this->db->query("SELECT * from tbl_brunch where brunch_id = '$id'");
        $data['selected'] = $query->row();
        $this->load->view('Administrator/edit/brunch_edit', $data);
    }
    public function brunch_update()
    {
        $id = $this->input->post('id');
        $fld = 'brunch_id';
        $string = $this->input->post('brunchaddress');
        $data = array(
            "Brunch_name"        => $this->input->post('Brunchname', TRUE),
            "Brunch_title"       => $this->input->post('brunchtitle', TRUE),
            "Brunch_address"     => htmlentities($string),
            "Brunch_sales"       => $this->input->post('Access', TRUE),
            "status"            => 'a'
        );
        if ($this->mt->update_data("tbl_brunch", $data, $id, $fld)) {
            $t = true;
            echo json_encode($t);
        }
    }
    public function brunch_delete()
    {
        $id = $this->input->post('deleted');
        if ($this->mt->delete_data("tbl_brunch", $id, 'brunch_id')) {
            $t = true;
            echo json_encode($t);
        }
    }

    public function databaseBackup()
    {
        $access = $this->mt->userAccess();
        if (!$access) {
            redirect(base_url());
        }
        $data['title'] = "Database Backup";
        $data['content'] = $this->load->view('Administrator/database_backup', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }
}
