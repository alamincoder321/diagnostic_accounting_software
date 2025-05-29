<?php
$userID =  $this->session->userdata('userId');
$CheckSuperAdmin = $this->db->where('UserType', 'm')->where('User_SlNo', $userID)->get('tbl_user')->row();

$CheckAdmin = $this->db->where('UserType', 'a')->where('User_SlNo', $userID)->get('tbl_user')->row();

$userAccessQuery = $this->db->where('user_id', $userID)->get('tbl_user_access');
$access = [];
if ($userAccessQuery->num_rows() != 0) {
	$userAccess = $userAccessQuery->row();
	$access = json_decode($userAccess->access);
}

$module = $this->session->userdata('module');
if ($module == 'dashboard' or $module == '') {
?>
	<ul class="nav nav-list">
		<li class="active">
			<!-- module/dashboard -->
			<a href="<?php echo base_url(); ?>">
				<i class="menu-icon fa fa-th"></i>
				<span class="menu-text"> Dashboard </span>
			</a>
			<b class="arrow"></b>
		</li>

		<li class="">
			<a href="<?php echo base_url(); ?>module/SalesModule">
				<i class="menu-icon fa fa-book" style="font-size:23px;"></i>
				<span class="menu-text"> Reports Panel </span>
			</a>
			<b class="arrow"></b>
		</li>

		<li class="">
			<!--  -->
			<a href="<?php echo base_url(); ?>module/AccountsModule">
				<i class="menu-icon fa fa fa-money" style="font-size: 16px;"></i>
				<span class="menu-text"> Manage Accounts </span>
			</a>
			<b class="arrow"></b>
		</li>

		<li class="">
			<!-- module/HRPayroll -->
			<a href="<?php echo base_url(); ?>module/HRPayroll">
				<i class="menu-icon fa fa-users"></i>
				<span class="menu-text"> Manage HR </span>
			</a>
			<b class="arrow"></b>
		</li>

		<li class="">
			<a href="<?php echo base_url(); ?>module/Administration">
				<i class="menu-icon fa fa-university"></i>
				<span class="menu-text"> Administration </span>
			</a>
			<b class="arrow"></b>
		</li>

		<li class="">
			<a href="<?php echo base_url(); ?>graph">
				<i class="menu-icon fa fa-bar-chart"></i>
				<span class="menu-text"> Business View </span>
			</a>
			<b class="arrow"></b>
		</li>
	</ul>
<?php } elseif ($module == 'Administration') { ?>
	<ul class="nav nav-list">
		<li class="active">
			<a href="<?php echo base_url(); ?>module/dashboard">
				<i class="menu-icon fa fa-th"></i>
				<span class="menu-text"> Dashboard </span>
			</a>
			<b class="arrow"></b>
		</li>
		<li>
			<a href="<?php echo base_url(); ?>module/Administration" style="background:gray !important;" class="module_title">
				<span>Administration</span>
			</a>
		</li>

		<!-- <?php if (array_search("sms", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>sms">
					<i class="menu-icon fa fa-mobile"></i>
					<span class="menu-text"> Send SMS </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?> -->
		
		<?php if (array_search("product", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>product">
					<i class="menu-icon fa fa-plus-circle"></i>
					<span class="menu-text"> Report Entry </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>
		
		<?php if (array_search("productlist", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>productlist">
					<i class="menu-icon fa fa-list"></i>
					<span class="menu-text"> Report List </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>
		
		<?php if (array_search("customer", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>customer">
					<i class="menu-icon fa fa-user-plus"></i>
					<span class="menu-text"> Patient Entry </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>
		
		<?php if (array_search("doctor", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>doctor">
					<i class="menu-icon fa fa-user-md"></i>
					<span class="menu-text"> Doctor Entry </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>
		
		<?php if (array_search("category", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>category">
					<i class="menu-icon fa fa-plus-circle"></i>
					<span class="menu-text"> Category Entry </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>
		
		<?php if (array_search("unit", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>unit">
					<i class="menu-icon fa fa-plus-circle"></i>
					<span class="menu-text"> Unit Entry </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>
		
		<?php if (array_search("area", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>area">
					<i class="menu-icon fa fa-globe"></i>
					<span class="menu-text"> Area Entry </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if ($this->session->userdata('BRANCHid') == 1 && (isset($CheckSuperAdmin) || isset($CheckAdmin))) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>companyProfile">
					<i class="menu-icon fa fa-bank"></i>
					<span class="menu-text"> Company Profile </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>user">
					<i class="menu-icon fa fa-user-plus"></i>
					<span class="menu-text"> Create User </span>
				</a>
			</li>
		<?php endif; ?>

		<?php if (isset($CheckSuperAdmin) && $this->session->userdata('BRANCHid') == 1) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>user_activity">
					<i class="menu-icon fa fa-list"></i>
					<span class="menu-text"> User Activity</span>
				</a>
			</li>
		<?php endif; ?>

		<?php if (array_search("database_backup", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>database_backup">
					<i class="menu-icon fa fa-database"></i>
					<span class="menu-text"> Database Backup </span>
				</a>
			</li>
		<?php endif; ?>

	</ul><!-- /.nav-list -->

<?php } elseif ($module == 'SalesModule') { ?>
	<ul class="nav nav-list">

		<li class="active">
			<a href="<?php echo base_url(); ?>module/dashboard">
				<i class="menu-icon fa fa-th"></i>
				<span class="menu-text"> Dashboard </span>
			</a>

			<b class="arrow"></b>
		</li>
		<li>
			<a href="<?php echo base_url(); ?>module/SalesModule" style="background:gray !important;" class="module_title">
				<span> Reports Panel </span>
			</a>
		</li>

		<?php if (array_search("reportEntry", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>reportEntry">
					<i class="menu-icon bi bi-journal-text"></i>
					<span class="menu-text"> Report Generate </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>


		<?php if (array_search("salesrecord", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>salesrecord">
					<i class="menu-icon fa fa-list"></i>
					<span class="menu-text"> Report Record </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (array_search("salesinvoice", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>salesinvoice">
					<i class="menu-icon fa fa-file-text"></i>
					<span class="menu-text"> Report Invoice </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>
	</ul>

<?php } elseif ($module == 'AccountsModule') { ?>
	<ul class="nav nav-list">
		<li class="active">
			<a href="<?php echo base_url(); ?>module/dashboard">
				<i class="menu-icon fa fa-th"></i>
				<span class="menu-text"> Dashboard </span>
			</a>

			<b class="arrow"></b>
		</li>
		<li>
			<a href="<?php echo base_url(); ?>module/AccountsModule" style="background:gray !important;" class="module_title">
				<span> Manage Account </span>
			</a>
		</li>

		<?php if (array_search("cashTransaction", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>cashTransaction">
					<i class="menu-icon fa fa-medkit"></i>
					<span class="menu-text"> Cash Transaction </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (array_search("bank_transactions", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li>
				<a href="<?php echo base_url(); ?>bank_transactions">
					<i class="menu-icon fa fa-dollar"></i>
					<span class="menu-text"> Bank Transactions </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (array_search("customerPaymentPage", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>customerPaymentPage">
					<i class="menu-icon fa fa-money"></i>
					<span class="menu-text"> Payment Received</span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (array_search("cash_view", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>cash_view">
					<i class="menu-icon fa fa-money"></i>
					<span class="menu-text">Cash View</span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<!-- <?php if (
					array_search("loan_transactions", $access) > -1
					|| array_search("loan_view", $access) > -1
					|| array_search("loan_transaction_report", $access) > -1
					|| array_search("loan_ledger", $access) > -1
					|| array_search("loan_accounts", $access) > -1
					|| isset($CheckSuperAdmin) || isset($CheckAdmin)
				) : ?>

			<li class="">
				<a href="<?php echo base_url(); ?>" class="dropdown-toggle">
					<i class="menu-icon fa fa-file"></i>
					<span class="menu-text"> Loan </span>

					<b class="arrow fa fa-angle-down"></b>
				</a>

				<b class="arrow"></b>

				<ul class="submenu">
					<?php if (array_search("loan_transactions", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>loan_transactions">
								<i class="menu-icon fa fa-caret-right"></i>
								Loan Transection
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("loan_view", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>loan_view">
								<i class="menu-icon fa fa-caret-right"></i>
								Loan View
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("loan_transaction_report", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>loan_transaction_report">
								<i class="menu-icon fa fa-caret-right"></i>
								Loan Transaction Report
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("loan_ledger", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>loan_ledger">
								<i class="menu-icon fa fa-caret-right"></i>
								Loan Ledger
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("loan_accounts", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>loan_accounts">
								<i class="menu-icon fa fa-caret-right"></i>
								Loan Account
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>
				</ul>
			</li>
		<?php endif; ?> -->

		<?php if (
			array_search("investment_transactions", $access) > -1
			|| array_search("investment_transaction_report", $access) > -1
			|| array_search("investment_view", $access) > -1
			|| array_search("investment_ledger", $access) > -1
			|| array_search("investment_account", $access) > -1
			|| isset($CheckSuperAdmin) || isset($CheckAdmin)
		) : ?>

			<li class="">
				<a href="<?php echo base_url(); ?>" class="dropdown-toggle">
					<i class="menu-icon fa fa-file"></i>
					<span class="menu-text"> Investment </span>

					<b class="arrow fa fa-angle-down"></b>
				</a>

				<b class="arrow"></b>

				<ul class="submenu">
					<?php if (array_search("investment_transactions", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>investment_transactions">
								<i class="menu-icon fa fa-caret-right"></i>
								Investment Transection
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("investment_view", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>investment_view">
								<i class="menu-icon fa fa-caret-right"></i>
								Investment View
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("investment_transaction_report", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>investment_transaction_report">
								<i class="menu-icon fa fa-caret-right"></i>
								Investment Transaction Report
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("investment_ledger", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>investment_ledger">
								<i class="menu-icon fa fa-caret-right"></i>
								Investment Ledger
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("investment_account", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>investment_account">
								<i class="menu-icon fa fa-caret-right"></i>
								Investment Account
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>
				</ul>
			</li>
		<?php endif; ?>

		<?php if (
			array_search("account", $access) > -1
			|| array_search("bank_accounts", $access) > -1
			|| isset($CheckSuperAdmin) || isset($CheckAdmin)
		) : ?>

			<li>
				<a href="" class="dropdown-toggle">
					<i class="menu-icon fa fa-bank"></i>
					<span class="menu-text"> Account Head </span>

					<b class="arrow fa fa-angle-down"></b>
				</a>

				<b class="arrow"></b>

				<ul class="submenu">
					<?php if (array_search("account", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li>
							<a href="<?php echo base_url(); ?>account">
								<i class="menu-icon fa fa-caret-right"></i>
								Transaction Accounts
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>
					<?php if (array_search("bank_accounts", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li>
							<a href="<?php echo base_url(); ?>bank_accounts">
								<i class="menu-icon fa fa-caret-right"></i>
								Bank Accounts
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>
				</ul>
			</li>
		<?php endif; ?>

		<?php if (
			array_search("TransactionReport", $access) > -1
			|| array_search("bank_transaction_report", $access) > -1
			|| array_search("cash_ledger", $access) > -1
			|| array_search("bank_ledger", $access) > -1
			|| array_search("cashStatment", $access) > -1
			|| array_search("BalanceSheet", $access) > -1
			|| array_search("balance_sheet", $access) > -1
			|| array_search("day_book", $access) > -1
			|| isset($CheckSuperAdmin) || isset($CheckAdmin)
		) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>" class="dropdown-toggle">
					<i class="menu-icon fa fa-file"></i>
					<span class="menu-text"> Reports </span>

					<b class="arrow fa fa-angle-down"></b>
				</a>

				<b class="arrow"></b>

				<ul class="submenu">
					<?php if (array_search("TransactionReport", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>TransactionReport">
								<i class="menu-icon fa fa-caret-right"></i>
								Cash Transaction Report
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("bank_transaction_report", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>bank_transaction_report">
								<i class="menu-icon fa fa-caret-right"></i>
								Bank Transaction Report
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("cash_ledger", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>cash_ledger">
								<i class="menu-icon fa fa-caret-right"></i>
								Cash Ledger
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("bank_ledger", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>bank_ledger">
								<i class="menu-icon fa fa-caret-right"></i>
								Bank Ledger
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("cashStatment", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>cashStatment">
								<i class="menu-icon fa fa-caret-right"></i>
								Cash Statement
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("balance_sheet", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>balance_sheet">
								<i class="menu-icon fa fa-caret-right"></i>
								Balance Sheet
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("BalanceSheet", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>BalanceSheet">
								<i class="menu-icon fa fa-caret-right"></i>
								Balance In Out
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("day_book", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>day_book">
								<i class="menu-icon fa fa-caret-right"></i>
								Day Book
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

				</ul>
			</li>
		<?php endif; ?>


	</ul><!-- /.nav-list -->
<?php } elseif ($module == 'HRPayroll') { ?>
	<ul class="nav nav-list">
		<li class="active">
			<a href="<?php echo base_url(); ?>module/dashboard">
				<i class="menu-icon fa fa-th"></i>
				<span class="menu-text"> Dashboard </span>
			</a>

			<b class="arrow"></b>
		</li>
		<li>
			<a href="<?php echo base_url(); ?>module/HRPayroll" style="background:gray !important;" class="module_title">
				<span>HR & Payroll</span>
			</a>
		</li>

		<?php if (array_search("salary_payment", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>salary_payment">
					<i class="menu-icon fa fa-money"></i>
					<span class="menu-text"> Salary Payment </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (array_search("employee", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>employee">
					<i class="menu-icon fa fa-users"></i>
					<span class="menu-text"> Add Employee </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (array_search("designation", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>designation">
					<i class="menu-icon fa fa-binoculars"></i>
					<span class="menu-text"> Add Designation </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (array_search("depertment", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>depertment">
					<i class="menu-icon fa fa-plus-square"></i>
					<span class="menu-text"> Add Department </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (array_search("month", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>month">
					<i class="menu-icon fa fa-calendar"></i>
					<span class="menu-text"> Add Month </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (
			array_search("emplists/all", $access) > -1
			|| array_search("emplists/active", $access) > -1
			|| array_search("emplists/deactive", $access) > -1
			|| array_search("salary_payment_report", $access) > -1
			|| isset($CheckSuperAdmin) || isset($CheckAdmin)
		) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>" class="dropdown-toggle">
					<i class="menu-icon fa fa-file"></i>
					<span class="menu-text"> Report </span>
					<b class="arrow fa fa-angle-down"></b>
				</a>

				<b class="arrow"></b>

				<ul class="submenu">

					<?php if (array_search("emplists/all", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>emplists/all">
								<i class="menu-icon fa fa-caret-right"></i>
								All Employee List
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("emplists/active", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>emplists/active">
								<i class="menu-icon fa fa-caret-right"></i>
								Active Employee List
							</a>
							<b class="arrow"></b>
						</li>
						<?php endif; ?><?php if (array_search("emplists/deactive", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>emplists/deactive">
								<i class="menu-icon fa fa-caret-right"></i>
								Deactive Employee List
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("salary_payment_report", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>salary_payment_report">
								<i class="menu-icon fa fa-caret-right"></i>
								Salary Payment Report
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

				</ul>
			</li>
		<?php endif; ?>
	</ul><!-- /.nav-list -->
<?php } ?>