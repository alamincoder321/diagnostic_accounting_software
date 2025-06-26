<?php
class Graph extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $access = $this->session->userdata('userId');
        $this->branchId = $this->session->userdata('BRANCHid');
        if ($access == '') {
            redirect("Login");
        }
        $this->load->model('Model_table', "mt", TRUE);
    }

    public function graph()
    {
        $access = $this->mt->userAccess();
        if (!$access) {
            redirect(base_url());
        }
        $data['title'] = "Business View";
        $data['content'] = $this->load->view('Administrator/graph/graph', $data, true);
        $this->load->view('Administrator/index', $data);
    }

    public function getGraphData()
    {
        // Monthly Record
        $monthlyRecord = [];
        $year = date('Y');
        $month = date('m');
        $dayNumber = date('t', mktime(0, 0, 0, $month, 1, $year));
        for ($i = 1; $i <= $dayNumber; $i++) {
            $date = $year . '-' . $month . '-' . sprintf("%02d", $i);
            $query = $this->db->query("
                    select ifnull(sum(sm.SaleMaster_TotalSaleAmount), 0) as sales_amount 
                    from tbl_salesmaster sm 
                    where sm.SaleMaster_SaleDate = ?
                    and sm.Status = 'a'
                    and sm.SaleMaster_branchid = ?
                    group by sm.SaleMaster_SaleDate
                ", [$date, $this->branchId]);

            $amount = 0.00;

            if ($query->num_rows() == 0) {
                $amount = 0.00;
            } else {
                $amount = $query->row()->sales_amount;
            }
            $sale = [sprintf("%02d", $i), $amount];
            array_push($monthlyRecord, $sale);
        }

        $yearlyRecord = [];
        for ($i = 1; $i <= 12; $i++) {
            $yearMonth = $year . sprintf("%02d", $i);
            $query = $this->db->query("
                    select ifnull(sum(sm.SaleMaster_TotalSaleAmount), 0) as sales_amount 
                    from tbl_salesmaster sm 
                    where extract(year_month from sm.SaleMaster_SaleDate) = ?
                    and sm.Status = 'a'
                    and sm.SaleMaster_branchid = ?
                    group by extract(year_month from sm.SaleMaster_SaleDate)
                ", [$yearMonth, $this->branchId]);

            $amount = 0.00;
            $monthName = date("M", mktime(0, 0, 0, $i, 10));

            if ($query->num_rows() == 0) {
                $amount = 0.00;
            } else {
                $amount = $query->row()->sales_amount;
            }
            $sale = [$monthName, $amount];
            array_push($yearlyRecord, $sale);
        }

        // Sales text for marquee
        $sales_text = $this->db->query("
                select 
                    concat(
                        'Invoice: ', sm.SaleMaster_InvoiceNo,
                        ', Customer: ', c.Customer_Code, ' - ', c.Customer_Name,
                        ', Amount: ', sm.SaleMaster_TotalSaleAmount,
                        ', Paid: ', sm.SaleMaster_PaidAmount,
                        ', Due: ', sm.SaleMaster_DueAmount
                    ) as sale_text
                from tbl_salesmaster sm 
                join tbl_customer c on c.Customer_SlNo = sm.SalseCustomer_IDNo
                where sm.Status = 'a'
                and sm.SaleMaster_branchid = ?
                order by sm.SaleMaster_SlNo desc limit 20
            ", $this->branchId)->result();

        // Today's Sale
        $todaysSale = $this->db->query("
                select 
                    ifnull(sum(ifnull(sm.SaleMaster_TotalSaleAmount, 0)), 0) as total_amount
                from tbl_salesmaster sm
                where sm.Status = 'a'
                and sm.SaleMaster_SaleDate = ?
                and sm.SaleMaster_branchid = ?
            ", [date('Y-m-d'), $this->branchId])->row()->total_amount;

        // This Month's Sale
        $thisMonthSale = $this->db->query("
                select 
                    ifnull(sum(ifnull(sm.SaleMaster_TotalSaleAmount, 0)), 0) as total_amount
                from tbl_salesmaster sm
                where sm.Status = 'a'
                and month(sm.SaleMaster_SaleDate) = ?
                and year(sm.SaleMaster_SaleDate) = ?
                and sm.SaleMaster_branchid = ?
            ", [$month, $year, $this->branchId])->row()->total_amount;

        // Today's Cash Collection
        $todaysCollection = $this->db->query("
                select 
                ifnull((
                    select sum(ifnull(sm.SaleMaster_PaidAmount, 0)) 
                    from tbl_salesmaster sm
                    where sm.Status = 'a'
                    and sm.SaleMaster_branchid = " . $this->branchId . "
                    and sm.SaleMaster_SaleDate = '" . date('Y-m-d') . "'
                ), 0) +
                ifnull((
                    select sum(ifnull(cp.CPayment_amount, 0)) 
                    from tbl_customer_payment cp
                    where cp.CPayment_status = 'a'
                    and cp.CPayment_TransactionType = 'CR'
                    and cp.CPayment_brunchid = " . $this->branchId . "
                    and cp.CPayment_date = '" . date('Y-m-d') . "'
                ), 0) +
                ifnull((
                    select sum(ifnull(ct.In_Amount, 0)) 
                    from tbl_cashtransaction ct
                    where ct.status = 'a'
                    and ct.Tr_branchid = " . $this->branchId . "
                    and ct.Tr_date = '" . date('Y-m-d') . "'
                ), 0) as total_amount
            ")->row()->total_amount;

        // Cash Balance
        $cashBalance = $this->mt->getTransactionSummary()->cash_balance;

        // Top Customers
        $topCustomers = $this->db->query("
                select 
                c.Customer_Name as customer_name,
                ifnull(sum(sm.SaleMaster_TotalSaleAmount), 0) as amount
                from tbl_salesmaster sm 
                join tbl_customer c on c.Customer_SlNo = sm.SalseCustomer_IDNo
                where sm.SaleMaster_branchid = ?
                group by sm.SalseCustomer_IDNo
                order by amount desc 
                limit 10
            ", $this->branchId)->result();

        // Top Products
        $topProducts = $this->db->query("
                select 
                    p.Product_Name as product_name,
                    ifnull(sum(sd.SaleDetails_TotalQuantity), 0) as sold_quantity
                from tbl_saledetails sd
                join tbl_product p on p.Product_SlNo = sd.Product_IDNo
                group by sd.Product_IDNo
                order by sold_quantity desc
                limit 5
            ")->result();

        // Customer Due
        $todaycustomerDueResult = $this->mt->todaycustomerDue();
        $todaycustomerDue = array_sum(array_map(function ($due) {
            return $due->dueAmount;
        }, $todaycustomerDueResult));
       
        $customerDueResult = $this->mt->customerDue();
        $customerDue = array_sum(array_map(function ($due) {
            return $due->dueAmount;
        }, $customerDueResult));

        // Bank balance
        $bankTransactions = $this->mt->getBankTransactionSummary();
        $bankBalance = array_sum(array_map(function ($bank) {
            return $bank->balance;
        }, $bankTransactions));


        //this month profit loss
        $sales = $this->db->query("
                select 
                    sm.*
                from tbl_salesmaster sm
                where sm.SaleMaster_branchid = ? 
                and sm.Status = 'a'
                and month(sm.SaleMaster_SaleDate) = ?
                and year(sm.SaleMaster_SaleDate) = ?
            ", [$this->branchId, $month, $year])->result();

        foreach ($sales as $sale) {
            $sale->saleDetails = $this->db->query("
                    select
                        sd.*,
                        (sd.Purchase_Rate * sd.SaleDetails_TotalQuantity) as purchased_amount,
                        (select sd.SaleDetails_TotalAmount - purchased_amount) as profit_loss
                    from tbl_saledetails sd
                    where sd.SaleMaster_IDNo = ?
                ", $sale->SaleMaster_SlNo)->result();
        }

        $profits = array_reduce($sales, function ($prev, $curr) {
            return $prev + array_reduce($curr->saleDetails, function ($p, $c) {
                return $p + $c->profit_loss;
            });
        });


        $total_discount = array_reduce($sales, function ($prev, $curr) {
            return $prev + $curr->SaleMaster_TotalDiscountAmount;
        });


        $other_income_expense = $this->db->query("
                select
                (
                    select ifnull(sum(ct.In_Amount), 0)
                    from tbl_cashtransaction ct
                    where ct.Tr_branchid = '$this->branchId'
                    and ct.status = 'a'
                    and month(ct.Tr_date) = '$month'
                    and year(ct.Tr_date) = '$year'
                ) as income,
            
                (
                    select ifnull(sum(ct.Out_Amount), 0)
                    from tbl_cashtransaction ct
                    where ct.Tr_branchid = '$this->branchId'
                    and ct.status = 'a'
                    and month(ct.Tr_date) = '$month'
                    and year(ct.Tr_date) = '$year'
                ) as expense,            
                (
                    select ifnull(sum(ep.total_payment_amount), 0)
                    from tbl_employee_payment ep
                    where ep.branch_id = '$this->branchId'
                    and ep.status = 'a'
                    and month(ep.payment_date) = '$month'
                    and year(ep.payment_date) = '$year'
                ) as employee_payment
            ")->row();

        $net_profit = (
            $profits +
            $other_income_expense->income
        ) - (
            $total_discount +
            $other_income_expense->expense +
            $other_income_expense->employee_payment
        );


        $total_doctor = $this->db->select('count(*) as total_doctor')
            ->where('status', 'a')
            ->get('tbl_doctor')
            ->row()->total_doctor;
        $total_patient = $this->db->select('count(*) as total_patient')
            ->where('status', 'a')
            ->get('tbl_customer')
            ->row()->total_patient;
        $today_patient = $this->db->select('count(*) as today_patient')
            ->where('status', 'a')
            ->where('DATE(AddTime)', date('Y-m-d'))
            ->get('tbl_customer')
            ->row()->today_patient;
        $total_agent = $this->db->select('count(*) as total_agent')
            ->where('status', 'a')
            ->get('tbl_agent')
            ->row()->total_agent;
        $total_report = $this->db->select('count(*) as total_report')
            ->where('status', 'a')
            ->where('is_delivery', 'yes')
            ->get('tbl_report_generate')
            ->row()->total_report;
        $today_report = $this->db->select('count(*) as today_report')
            ->where('status', 'a')
            ->where('is_delivery', 'yes')
            ->where('delivery_date', date('Y-m-d'))
            ->get('tbl_report_generate')
            ->row()->today_report;
        $total_test = $this->db->select('count(*) as total_test')
            ->where('status', 'a')
            ->get('tbl_report_generate')
            ->row()->total_test;
        $today_test = $this->db->select('count(*) as today_test')
            ->where('status', 'a')
            ->where('delivery_date', date('Y-m-d'))
            ->get('tbl_report_generate')
            ->row()->today_test;
        
        $total_expense = $this->db->select('ifnull(sum(Out_Amount), 0) as amount')
            ->where('status', 'a')
            ->get('tbl_cashtransaction')
            ->row()->amount;
        $today_expense = $this->db->select('ifnull(sum(Out_Amount), 0) as amount')
            ->where('status', 'a')
            ->where('Tr_date', date('Y-m-d'))
            ->get('tbl_cashtransaction')
            ->row()->amount;

        $responseData = [
            'monthly_record'     => $monthlyRecord,
            'yearly_record'      => $yearlyRecord,
            'sales_text'         => $sales_text,
            'todays_sale'        => $todaysSale,
            'this_month_sale'    => $thisMonthSale,
            'todays_collection'  => $todaysCollection,
            'cash_balance'       => $cashBalance,
            'top_customers'      => $topCustomers,
            'top_products'       => $topProducts,
            'today_customer_due' => $todaycustomerDue,
            'customer_due'       => $customerDue,
            'bank_balance'       => $bankBalance,
            'this_month_profit'  => $net_profit,
            'total_patient'      => $total_patient,
            'today_patient'      => $today_patient,
            'total_doctor'       => $total_doctor,
            'total_agent'        => $total_agent,
            'today_report'       => $today_report,
            'total_report'       => $total_report,
            'today_test'         => $today_test,
            'total_test'         => $total_test,
            'today_expense'      => $today_expense,
            'total_expense'      => $total_expense,
        ];

        echo json_encode($responseData, JSON_NUMERIC_CHECK);
    }
}
