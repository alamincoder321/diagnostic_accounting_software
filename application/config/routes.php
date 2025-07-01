<?php
defined('BASEPATH') or exit('No direct script access allowed');

$route['default_controller'] = 'Page';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['logout'] = 'Login/logout';

$route['Administrator'] = 'Administrator/Page';
$route['module/(:any)'] = 'Administrator/Page/module/$1';
$route['brachAccess/(:any)'] = 'Administrator/Login/brach_access/$1';
$route['getBrachAccess'] = 'Administrator/Login/branch_access_main_admin';

$route['get_categories'] = 'Administrator/Page/getCategories';
$route['category'] = 'Administrator/Page/add_category';
$route['insertcategory'] = 'Administrator/Page/insert_category';
$route['updatecategory'] = 'Administrator/Page/update_category';
$route['catdelete'] = 'Administrator/Page/catdelete';

$route['get_subcategories'] = 'Administrator/Page/getSubCategories';
$route['subcategory'] = 'Administrator/Page/add_subcategory';
$route['insertsubcategory'] = 'Administrator/Page/insert_subcategory';
$route['updatesubcategory'] = 'Administrator/Page/update_subcategory';
$route['subcatdelete'] = 'Administrator/Page/subcatdelete';

$route['unit'] = 'Administrator/Page/unit';
$route['insertunit'] = 'Administrator/Page/insert_unit';
$route['unitupdate'] = 'Administrator/Page/unitupdate';
$route['unitdelete'] = 'Administrator/Page/unitdelete';
$route['get_units'] = 'Administrator/Page/getUnits';

$route['area'] = 'Administrator/Page/area';
$route['insertarea'] = 'Administrator/Page/insert_area';
$route['areadelete'] = 'Administrator/Page/areadelete';
$route['areaupdate'] = 'Administrator/Page/areaupdate';
$route['get_districts'] = 'Administrator/Page/getDistricts';

// report route
$route['product'] = 'Administrator/Products';
$route['add_product'] = 'Administrator/Products/addProduct';
$route['update_product'] = 'Administrator/Products/updateProduct';
$route['delete_product'] = 'Administrator/Products/deleteProduct';
$route['productlist'] = 'Administrator/Reports/productlist';
$route['get_products']    =    'Administrator/Products/getProducts';

// agent route
$route['agent'] = 'Administrator/Agent';
$route['get_agents'] = 'Administrator/Agent/getAgents';
$route['add_agent'] = 'Administrator/Agent/addAgent';
$route['update_agent'] = 'Administrator/Agent/updateAgent';
$route['delete_agent'] = 'Administrator/Agent/deleteAgent';
$route['agentlist'] = 'Administrator/Agent/agentlist';

$route['room'] = 'Administrator/Page/room';
$route['get_rooms'] = 'Administrator/Page/getRooms';
$route['add_room'] = 'Administrator/Page/addRoom';
$route['update_room'] = 'Administrator/Page/updateRoom';
$route['delete_room'] = 'Administrator/Page/deleteRoom';

// docotr route
$route['doctor'] = 'Administrator/Doctor';
$route['get_doctors'] = 'Administrator/Doctor/getDoctors';
$route['add_doctor'] = 'Administrator/Doctor/addDoctor';
$route['update_doctor'] = 'Administrator/Doctor/updateDoctor';
$route['delete_doctor'] = 'Administrator/Doctor/deleteDoctor';
$route['doctorlist'] = 'Administrator/Doctor/doctorList';

// patient route
$route['customer'] = 'Administrator/Customer';
$route['add_customer'] = 'Administrator/Customer/addCustomer';
$route['update_customer'] = 'Administrator/Customer/updateCustomer';
$route['customerlist'] = 'Administrator/Customer/customerlist';
$route['delete_customer'] = 'Administrator/Customer/deleteCustomer';
$route['get_customers'] = 'Administrator/Customer/getCustomers';
$route['get_customer_due'] = 'Administrator/Customer/getCustomerDue';
$route['get_customer_ledger'] = 'Administrator/Customer/getCustomerLedger';
$route['get_customer_payments'] = 'Administrator/Customer/getCustomerPayments';
$route['add_customer_payment'] = 'Administrator/Customer/addCustomerPayment';
$route['update_customer_payment'] = 'Administrator/Customer/updateCustomerPayment';
$route['delete_customer_payment'] = 'Administrator/Customer/deleteCustomerPayment';
$route['customerPaymentPage'] = 'Administrator/Customer/customerPaymentPage';
$route['customer_payment_history'] = 'Administrator/Customer/customerPaymentHistory';

// report entry
$route['bill_entry']        = 'Administrator/Sales/index';
$route['bill_entry/(:any)'] = 'Administrator/Sales/salesEdit/$1';
$route['salesinvoice']       = 'Administrator/Sales/sales_invoice';
$route['salesInvoicePrint']  = 'Administrator/Reports/sales_invoice';
$route['add_sales']          = 'Administrator/Sales/addSales';
$route['get_sales']          = 'Administrator/Sales/getSales';
$route['get_sales_record']   = 'Administrator/Sales/getSalesRecord';
$route['get_saledetails']    = 'Administrator/Sales/getSaleDetails';
$route['update_sales']       = 'Administrator/Sales/updateSales';
$route['delete_sales']       = 'Administrator/Sales/deleteSales';
$route['sale_invoice_print/(:any)'] = 'Administrator/Sales/saleInvoicePrint/$1';
$route['salesrecord'] = 'Administrator/Sales/sales_record';
$route['customerPaymentReport'] = 'Administrator/Customer/customer_payment_report';
$route['profitLoss'] = 'Administrator/Sales/profitLoss';
$route['get_profit_loss'] = 'Administrator/Sales/getProfitLoss';
$route['customerDue'] = 'Administrator/Customer/customer_due';
$route['paymentAndReport/(:any)'] = 'Administrator/Customer/paymentAndReport/$1';

// generate report
$route['report_generate']        = 'Administrator/ReportGenerate';
$route['report_generate/(:any)'] = 'Administrator/ReportGenerate/reportEdit/$1';
$route['get_report_test']        = 'Administrator/ReportGenerate/getReportTest';
$route['add_report_generate']    = 'Administrator/ReportGenerate/addReportGenerate';
$route['update_report_generate'] = 'Administrator/ReportGenerate/updateReportGenerate';
$route['delete_report_generate'] = 'Administrator/ReportGenerate/deleteReportGenerate';
$route['report_list']            = 'Administrator/ReportGenerate/reportlist';
$route['get_report_list']        = 'Administrator/ReportGenerate/getReportList';
$route['report_invoice']  = 'Administrator/ReportGenerate/reportInvoice';
$route['report_invoice_print/(:any)']  = 'Administrator/ReportGenerate/reportInvoicePrint/$1';
$route['report_delivery']        = 'Administrator/ReportGenerate/reportDelivery';

// dialysis route
$route['dialysis']        = 'Administrator/Dialysis';
$route['get_dialysis']    = 'Administrator/Dialysis/getDialysis';
$route['add_dialysis']    = 'Administrator/Dialysis/addDialysis';
$route['update_dialysis'] = 'Administrator/Dialysis/updateDialysis';
$route['delete_dialysis'] = 'Administrator/Dialysis/deleteDialysis';
$route['dialysisList']    = 'Administrator/Dialysis/dialysisList';
$route['dialysis_invoice']  = 'Administrator/Dialysis/dialysisInvoice';
$route['dialysis_invoice_print/(:any)']  = 'Administrator/Dialysis/dialysisInvoicePrint/$1';

//user entry
$route['user'] = 'Administrator/User_management';
$route['get_users'] = 'Administrator/User_management/getUsers';
$route['get_all_users'] = 'Administrator/User_management/getAllUsers';
$route['add_user'] = 'Administrator/User_management/user_Insert';
$route['update_user'] = 'Administrator/User_management/userupdate';
$route['delete_user'] = 'Administrator/User_management/userDelete';
$route['change_user_status'] = 'Administrator/User_management/userstatusChange';
$route['check_username'] = 'Administrator/User_management/check_user_name';
$route['access/(:any)'] = 'Administrator/User_management/user_access/$1';
$route['get_user_access'] = 'Administrator/User_management/getUserAccess';
$route['profile'] = 'Administrator/User_management/profile';
$route['profile_update'] = 'Administrator/User_management/profileUpdate';
$route['password_change'] = 'Administrator/User_management/password_change';
$route['define_access/(:any)'] = 'Administrator/User_management/define_access/$1';
$route['add_user_access'] = 'Administrator/User_management/addUserAccess';
$route['user_activity'] = 'Administrator/User_management/userActivity';
$route['get_user_activity'] = 'Administrator/User_management/getUserActivity';
$route['delete_user_activity'] = 'Administrator/User_management/deleteUserActivity';

$route['brunch'] = 'Administrator/Page/brunch';
$route['add_branch'] = 'Administrator/Page/addBranch';
$route['update_branch'] = 'Administrator/Page/updateBranch';
$route['brunchEdit'] = 'Administrator/Page/brunch_edit';
$route['brunchUpdate'] = 'Administrator/Page/brunch_update';
$route['brunchDelete'] = 'Administrator/Page/brunch_delete';
$route['get_branches'] = 'Administrator/Page/getBranches';
$route['get_current_branch'] = 'Administrator/Page/getCurrentBranch';
$route['change_branch_status'] = 'Administrator/Page/changeBranchStatus';

$route['companyProfile'] = 'Administrator/Page/company_profile';
$route['company_profile_Update'] = 'Administrator/Page/company_profile_Update';
$route['company_profile_insert'] = 'Administrator/Page/company_profile_insert';
$route['get_company_profile'] = 'Administrator/Page/getCompanyProfile';

$route['employee'] = 'Administrator/employee';
$route['get_employees'] = 'Administrator/Employee/getEmployees';
$route['employeeInsert'] = 'Administrator/Employee/employee_insert/';
$route['emplists/(:any)'] = 'Administrator/Employee/emplists/$1';
$route['employeeEdit/(:any)'] = 'Administrator/Employee/employee_edit/$1';
$route['employeeUpdate'] = 'Administrator/Employee/employee_Update';
$route['employeeDelete'] = 'Administrator/Employee/employee_Delete';
$route['employeeActive'] = 'Administrator/Employee/active';

//salary Payment
$route['salary_payment']                = 'Administrator/Employee/employeePayment';
$route['check_payment_month']           = 'Administrator/Employee/checkPaymentMonth';
$route['get_payments']                  = 'Administrator/Employee/getPayments';
$route['get_salary_details']            = 'Administrator/Employee/getSalaryDetails';
$route['add_salary_payment']            = 'Administrator/Employee/saveSalaryPayment';
$route['update_salary_payment']         = 'Administrator/Employee/updateSalaryPayment';
$route['salary_payment_report']         = 'Administrator/Employee/employeePaymentReport';
$route['delete_payment']                = 'Administrator/Employee/deletePayment';

$route['designation'] = 'Administrator/Employee/designation/';
$route['insertDesignation'] = 'Administrator/Employee/insert_designation';
$route['designationedit/(:any)'] = 'Administrator/Employee/designationedit/$1';
$route['designationUpdate'] = 'Administrator/Employee/designationupdate/';
$route['designationdelete'] = 'Administrator/Employee/designationdelete';

$route['depertment'] = 'Administrator/Employee/depertment';
$route['insertDepertment'] = 'Administrator/Employee/insert_depertment';
$route['depertmentdelete'] = 'Administrator/Employee/depertmentdelete/';
$route['depertmentedit/(:any)'] = 'Administrator/Employee/depertmentedit/$1';
$route['depertmentupdate'] = 'Administrator/Employee/depertmentupdate';

$route['month'] = 'Administrator/Employee/month';
$route['insertMonth'] = 'Administrator/Employee/insert_month';
$route['editMonth/(:any)'] = 'Administrator/Employee/editMonth/$1';
$route['updateMonth'] = 'Administrator/Employee/updateMonth';
$route['get_months'] = 'Administrator/Employee/getMonths';

$route['get_cash_transactions'] = 'Administrator/Account/getCashTransactions';
$route['cashTransaction'] = 'Administrator/Account/cash_transaction';
$route['get_cash_transaction_code'] = 'Administrator/Account/getCashTransactionCode';
$route['add_cash_transaction'] = 'Administrator/Account/addCashTransaction';
$route['update_cash_transaction'] = 'Administrator/Account/updateCashTransaction';
$route['delete_cash_transaction'] = 'Administrator/Account/deleteCashTransaction';
$route['transactionEdit'] = 'Administrator/Account/cash_transaction_edit';
$route['viewTransaction/(:any)'] = 'Administrator/Account/viewTransaction/$1';

$route['account'] = 'Administrator/Account';
$route['add_account'] = 'Administrator/Account/addAccount';
$route['accountEdit'] = 'Administrator/Account/account_edit';
$route['update_account'] = 'Administrator/Account/updateAccount';
$route['delete_account'] = 'Administrator/Account/deleteAccount';
$route['get_accounts'] = 'Administrator/Account/getAccounts';
$route['get_cash_and_bank_balance'] = 'Administrator/Account/getCashAndBankBalance';

$route['TransactionReport'] = 'Administrator/Account/all_transaction_report';
$route['TransactionReportSearch'] = 'Administrator/Account/transaction_report_search';
$route['transactionReportPrint'] = 'Administrator/Reports/transaction_report_print';
$route['bank_transaction_report'] = 'Administrator/Account/bankTransactionReprot';
$route['get_other_income_expense'] = 'Administrator/Account/getOtherIncomeExpense';

$route['cashView'] = 'Administrator/Account/cash_view';
$route['cash_ledger'] = 'Administrator/Account/cashLedger';
$route['get_cash_ledger'] = 'Administrator/Account/getCashLedger';
$route['cashStatment'] = 'Administrator/Reports/cashStatment';
$route['cashStatmentList'] = 'Administrator/Reports/cashStatmentList';
$route['day_book'] = 'Administrator/Reports/dayBook';

$route['BalanceSheet'] = 'Administrator/Reports/balanceSheet';
$route['balance_sheet'] = 'Administrator/Reports/balance_sheet';
$route['get_balance_sheet'] = 'Administrator/Reports/getBalanceSheet';
$route['balanceSheetList'] = 'Administrator/Reports/balanceSheetList';
$route['balanceSheetListPrint'] = 'Administrator/Reports/balanceSheetListPrint';

/* $route['addgeneric'] = 'Setting/addgeneric'; */
$route['userName'] = 'Administrator/User_management/all_user_name';

// Banks
$route['bank_accounts'] = 'Administrator/Account/bankAccounts';
$route['add_bank_account'] = 'Administrator/Account/addBankAccount';
$route['update_bank_account'] = 'Administrator/Account/updateBankAccount';
$route['get_bank_accounts'] = 'Administrator/Account/getBankAccounts';
$route['change_account_status'] = 'Administrator/Account/changeAccountStatus';

// Bank Transactions
$route['bank_transactions'] = 'Administrator/Account/bankTransactions';
$route['add_bank_transaction'] = 'Administrator/Account/addBankTransaction';
$route['update_bank_transaction'] = 'Administrator/Account/updateBankTransaction';
$route['get_bank_transactions'] = 'Administrator/Account/getBankTransactions';
$route['get_all_bank_transactions'] = 'Administrator/Account/getAllBankTransactions';
$route['remove_bank_transaction'] = 'Administrator/Account/removeBankTransaction';
$route['get_bank_balance'] = 'Administrator/Account/getBankBalance';

$route['cash_view'] = 'Administrator/Account/cashView';
$route['bank_ledger'] = 'Administrator/Account/bankLedger';

// Graph
$route['graph'] = 'Administrator/Graph/graph';
$route['get_graph_data'] = 'Administrator/Graph/getGraphData';

// SMS
$route['sms'] = 'Administrator/SMS';
$route['send_sms'] = 'Administrator/SMS/sendSms';
$route['send_bulk_sms'] = 'Administrator/SMS/sendBulkSms';
$route['sms_settings'] = 'Administrator/SMS/smsSettings';
$route['get_sms_settings'] = 'Administrator/SMS/getSmsSettings';
$route['save_sms_settings'] = 'Administrator/SMS/saveSmsSettings';

$route['user_login'] = 'Login/userLogin';
$route['database_backup'] = 'Administrator/Page/databaseBackup';


// Loan
$route['loan_transactions'] = 'Administrator/Loan/loanTransactions';
$route['get_loan_transactions'] = 'Administrator/Loan/getLoanTransactions';
$route['get_loan_initial_balance'] = 'Administrator/Loan/getLoanInitialBalance';
$route['add_loan_transaction'] = 'Administrator/Loan/addLoanTransaction';
$route['update_loan_transaction'] = 'Administrator/Loan/updateLoanTransaction';
$route['remove_loan_transaction'] = 'Administrator/Loan/removeLoanTransaction';
$route['get_loan_balance'] = 'Administrator/Loan/getLoanBalance';
$route['loan_view'] = 'Administrator/Loan/loanView';
$route['loan_transaction_report'] = 'Administrator/Loan/loanTransactionReprot';
$route['get_all_loan_transactions'] = 'Administrator/Loan/getAllLoanTransactions';
$route['loan_ledger'] = 'Administrator/Loan/loanLedger';


//loan account
$route['loan_accounts'] = 'Administrator/Loan/loanAccounts';
$route['add_loan_account'] = 'Administrator/Loan/addLoanAccount';
$route['update_loan_account'] = 'Administrator/Loan/updateLoanAccount';
$route['get_loan_accounts'] = 'Administrator/Loan/getLoanAccounts';
$route['change_loan_account_status'] = 'Administrator/Loan/changeLoanAccountStatus';


//investment
$route['investment_transactions'] = 'Administrator/Invest/investmentTransactions';
$route['get_investment_transactions'] = 'Administrator/Invest/getInvestmentTransactions';
$route['add_investment_transaction'] = 'Administrator/Invest/addInvestmentTransaction';
$route['update_investment_transaction'] = 'Administrator/Invest/updateInvestmentTransaction';
$route['remove_investment_transaction'] = 'Administrator/Invest/removeInvestmentTransaction';
$route['get_investment_balance'] = 'Administrator/Invest/getInvestmentBalance';
$route['investment_view'] = 'Administrator/Invest/investmentView';
$route['investment_transaction_report'] = 'Administrator/Invest/investmentTransactionReprot';
$route['get_all_investment_transactions'] = 'Administrator/Invest/getAllInvestmentTransactions';
$route['investment_ledger'] = 'Administrator/Invest/investmentLedger';


//investment account
$route['investment_account'] = 'Administrator/Invest/investmentAccount';
$route['add_investment_account'] = 'Administrator/Invest/addInvestmentAccount';
$route['update_investment_account'] = 'Administrator/Invest/updateInvestmentAccount';
$route['delete_investment_account'] = 'Administrator/Invest/deleteInvestmentAccount';
$route['get_investment_accounts'] = 'Administrator/Invest/getInvestmentAccounts';
