<style>
	#dayBook .buttons {
		margin-top: -5px;
	}
	.day-book-table {
		width: 100%;
		margin-bottom: 50px;
	}
	.day-book-table thead {
		background: #ebebeb;
		border-bottom: 1px solid black;
	}
	.day-book-table th {
		padding: 5px 10px;
		text-align: left;
	}
	.day-book-table td {
		padding: 0px 30px;
	}
	.day-book-table tr td:last-child {
		text-align: right;
		padding-right: 50px;
	}
	.day-book-table .main-heading {
		padding-left: 10px;
		font-weight: bold;
	}
	.day-book-table .sub-heading {
		padding-left: 20px;
		font-weight: bold;
	}
	.day-book-table .sub-value {
		padding-right: 10px!important;
		font-weight: bold;
	}
</style>
<div id="dayBook">
	<div class="row" style="border-bottom: 1px solid #ccc;">
		<div class="col-md-12">
			<form action="" class="form-inline" @submit.prevent="getDayBookData">
				<div class="form-group">
					<label for="">Date from</label>
					<input type="date" class="form-control" v-model="filter.dateFrom">
				</div>

				<div class="form-group">
					<label for="">to</label>
					<input type="date" class="form-control" v-model="filter.dateTo">
				</div>

				<div class="form-group buttons">
					<input type="submit" value="Search">
				</div>
			</form>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12" style="padding-top:15px;">
			<a href="" @click.prevent="print"><i class="fa fa-print"></i> Print</a>
		</div>
	</div>

	<div id="printContent">
		<div class="row">
			<div class="col-md-12">
				<div style="display:flex;">
					<div style="width:50%;border:1px solid black;position:relative;">
						<table class="day-book-table">
							<thead>
								<tr>
									<th>Description</th>
									<th>Amount</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="main-heading">Opening Balance</td>
									<td></td>
								</tr>
								<template v-if="openingBalance.cashBalance != null">
									<tr>
										<td class="sub-heading">Cash in Hand</td>
										<td class="sub-value">{{ openingBalance.cashBalance.cash_balance | decimal }}</td>
									</tr>
									<tr>
										<td>Cash</td>
										<td>{{ openingBalance.cashBalance.cash_balance | decimal }}</td>
									</tr>
								</template>
								<template v-if="openingBalance.bankBalance.length > 0">
									<tr>
										<td class="sub-heading">Bank Accounts</td>
										<td class="sub-value">{{ totalBankOpeningBalance }}</td>
									</tr>
									<template>
										<tr v-for="bankAccount in openingBalance.bankBalance">
											<td>{{ bankAccount.bank_name }} {{ bankAccount.account_name }} {{ bankAccount.account_number }}</td>
											<td>{{ bankAccount.balance | decimal }}</td>
										</tr>
									</template>
								</template>
			
								<tr>
									<td class="main-heading">Receipt</td>
									<td></td>
								</tr>
								<template v-if="sales.length > 0">
									<tr>
										<td class="sub-heading">Report Amount</td>
										<td class="sub-value">{{ totalSales }}</td>
									</tr>
									<tr v-for="sale in sales">
										<td>{{ sale.Customer_Name }}</td>
										<td>{{ sale.totalAmount | decimal }}</td>
									</tr>
								</template>
								<template v-if="receivedFromCustomers.length > 0">
									<tr>
										<td class="sub-heading">Patient Payment</td>
										<td class="sub-value">{{ totalReceivedFromCustomers }}</td>
									</tr>
									<tr v-for="payment in receivedFromCustomers">
										<td>{{ payment.Customer_Name }}</td>
										<td>{{ payment.totalAmount | decimal }}</td>
									</tr>
								</template>
								<template v-if="cashReceived.length > 0">
									<tr>
										<td class="sub-heading">Cash Received</td>
										<td class="sub-value">{{ totalCashReceived }}</td>
									</tr>
									<tr v-for="transaction in cashReceived">
										<td>{{ transaction.Acc_Name }}</td>
										<td>{{ transaction.totalAmount | decimal }}</td>
									</tr>
								</template>
								<template v-if="bankWithdraws.length > 0">
									<tr>
										<td class="sub-heading">Bank Withdraws <span style="color:red;">(Not Calculated)</span></td>
										<td class="sub-value">{{ totalBankWithdraw }}</td>
									</tr>
									<tr v-for="transaction in bankWithdraws">
										<td>{{ transaction.bank_name }} {{ transaction.account_name }} {{ transaction.account_number }}</td>
										<td>{{ transaction.totalAmount | decimal }}</td>
									</tr>
								</template>
								
								
								<template v-if="bankDeposits.length > 0">
									<tr>
										<td class="sub-heading">Bank Deposits <span style="color:red;">(Not Calculated)</span></td>
										<td class="sub-value">{{ totalBankDeposit }}</td>
									</tr>
									<tr v-for="transaction in bankDeposits">
										<td>{{ transaction.bank_name }} {{ transaction.account_name }} {{ transaction.account_number }}</td>
										<td>{{ transaction.totalAmount | decimal }}</td>
									</tr>
								</template>
								
							</tbody>
						</table>
						<div style="position:absolute;bottom:0px;left:0px;padding:5px 10px;display:none;width:100%;border-top:1px solid black;font-weight:bold;"
							v-bind:style="{display: _.isNumber(totalIn) ? 'flex' : 'none' }">
							<div style="width:50%;">Total</div>
							<div style="width:50%;text-align:right;">{{ totalIn | decimal }}</div>
						</div>
					</div>
					<div style="width:50%;border:1px solid black;border-left:none;position:relative;">
						<table class="day-book-table">
							<thead>
								<tr>
									<th>Description</th>
									<th>Amount</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="main-heading">Payment</td>
									<td></td>
								</tr>
								<template v-if="cashPaid.length > 0">
									<tr>
										<td class="sub-heading">Cash Paid</td>
										<td class="sub-value">{{ totalCashPaid }}</td>
									</tr>
									<tr v-for="transaction in cashPaid">
										<td>{{ transaction.Acc_Name }}</td>
										<td>{{ transaction.totalAmount | decimal }}</td>
									</tr>
								</template>
								<template v-if="paidToCustomers.length > 0">
									<tr>
										<td class="sub-heading">Paid to Patient</td>
										<td class="sub-value">{{ totalPaidToCustomers }}</td>
									</tr>
									<tr v-for="payment in paidToCustomers">
										<td>{{ payment.Customer_Name }}</td>
										<td>{{ payment.totalAmount | decimal }}</td>
									</tr>
								</template>
								<template v-if="employeePayments.length > 0">
									<tr>
										<td class="sub-heading">Employee Payments</td>
										<td class="sub-value">{{ totalEmployeePayments }}</td>
									</tr>
									<tr v-for="payment in employeePayments">
										<td>{{ payment.Employee_Name }}</td>
										<td>{{ payment.totalAmount | decimal }}</td>
									</tr>
								</template>
								
								<tr>
									<td class="main-heading">Closing Balance</td>
									<td></td>
								</tr>
								<template v-if="closingBalance.bankBalance.length > 0">
									<tr>
										<td class="sub-heading">Bank Accounts</td>
										<td class="sub-value">{{ totalBankClosingBalance }}</td>
									</tr>
									<template>
										<tr v-for="bankAccount in closingBalance.bankBalance">
											<td>{{ bankAccount.bank_name }} {{ bankAccount.account_name }} {{ bankAccount.account_number }}</td>
											<td>{{ bankAccount.balance | decimal }}</td>
										</tr>
									</template>
								</template>

								<template v-if="closingBalance.cashBalance != null">
									<tr>
										<td class="sub-heading">Cash in Hand</td>
										<td class="sub-value">{{ closingBalance.cashBalance.cash_balance | decimal }}</td>
									</tr>
									<tr>
										<td>Cash</td>
										<td>{{ closingBalance.cashBalance.cash_balance | decimal }}</td>
									</tr>
								</template>
							</tbody>
						</table>
						<div style="position:absolute;bottom:0px;left:0px;padding:5px 10px;display:none;width:100%;border-top:1px solid black;font-weight:bold;"
							v-bind:style="{display: _.isNumber(totalOut) ? 'flex' : 'none' }">
							<div style="width:50%;">Total</div>
							<div style="width:50%;text-align:right;">{{ totalOut | decimal }}</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="<?php echo base_url(); ?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/lodash.min.js"></script>

<script>
	new Vue({
		el: '#dayBook',
		data() {
			return {
				filter: {
					dateFrom: moment().format('YYYY-MM-DD'),
					dateTo: moment().format('YYYY-MM-DD')
				},
				openingBalance: {
					bankBalance: [],
					cashBalance: 0.00
				},
				closingBalance: {
					bankBalance: [],
					cashBalance: 0.00
				},
				sales: [],
				asset_sales: [],
				asset_purchases: [],
				purchases: [],
				receivedFromCustomers: [],
				paidToCustomers: [],
				receivedFromSuppliers: [],
				paidToSuppliers: [],
				cashReceived: [],
				cashPaid: [],
				bankDeposits: [],
				bankWithdraws: [],
				loanReceives: [],
				loanPayments: [],
				loanInitials: [],
				investReceives: [],
				investPayments: [],
				employeePayments: []
			}
		},
		filters: {
			decimal(value) {
				return value == null ? 0.00 : parseFloat(value).toFixed(2);
			}
		},
		computed: {
			totalBankOpeningBalance(){
				return this.openingBalance.bankBalance.reduce((prev, curr) => {
					return prev + parseFloat(curr.balance)
				}, 0).toFixed(2);
			},
			totalBankClosingBalance(){
				return this.closingBalance.bankBalance.reduce((prev, curr) => {
					return prev + parseFloat(curr.balance)
				}, 0).toFixed(2);
			},
			totalSales() {
				return this.sales.reduce((prev, curr) => {
					return prev + parseFloat(curr.totalAmount)
				}, 0).toFixed(2);
			},
			totalAssetSales() {
				return this.asset_sales.reduce((prev, curr) => {
					return prev + parseFloat(curr.totalAmount)
				}, 0).toFixed(2);
			},
			totalAssetPurchases() {
				return this.asset_purchases.reduce((prev, curr) => {
					return prev + parseFloat(curr.totalAmount)
				}, 0).toFixed(2);
			},
			totalPurchase() {
				return this.purchases.reduce((prev, curr) => {
					return prev + parseFloat(curr.totalAmount)
				}, 0).toFixed(2);
			},
			totalReceivedFromCustomers() {
				return this.receivedFromCustomers.reduce((prev, curr) => {
					return prev + parseFloat(curr.totalAmount)
				}, 0).toFixed(2);
			},
			totalPaidToCustomers() {
				return this.paidToCustomers.reduce((prev, curr) => {
					return prev + parseFloat(curr.totalAmount)
				}, 0).toFixed(2);
			},
			totalReceivedFromSuppliers() {
				return this.receivedFromSuppliers.reduce((prev, curr) => {
					return prev + parseFloat(curr.totalAmount)
				}, 0).toFixed(2);
			},
			totalPaidToSuppliers() {
				return this.paidToSuppliers.reduce((prev, curr) => {
					return prev + parseFloat(curr.totalAmount)
				}, 0).toFixed(2);
			},
			totalCashReceived() {
				return this.cashReceived.reduce((prev, curr) => {
					return prev + parseFloat(curr.totalAmount)
				}, 0).toFixed(2);
			},
			totalCashPaid() {
				return this.cashPaid.reduce((prev, curr) => {
					return prev + parseFloat(curr.totalAmount)
				}, 0).toFixed(2);
			},
			totalBankDeposit() {
				return this.bankDeposits.reduce((prev, curr) => {
					return prev + parseFloat(curr.totalAmount)
				}, 0).toFixed(2);
			},
			totalLoanPayment() {
				return this.loanPayments.reduce((prev, curr) => {
					return prev + parseFloat(curr.totalAmount)
				}, 0).toFixed(2);
			},
			totalInvestPayment() {
				return this.investPayments.reduce((prev, curr) => {
					return prev + parseFloat(curr.totalAmount)
				}, 0).toFixed(2);
			},
			totalBankWithdraw() {
				return this.bankWithdraws.reduce((prev, curr) => {
					return prev + parseFloat(curr.totalAmount)
				}, 0).toFixed(2);
			},
			totalLoanReceived() {
				return this.loanReceives.reduce((prev, curr) => {
					return prev + parseFloat(curr.totalAmount)
				}, 0).toFixed(2);
			},
			totalInvestReceived() {
				return this.investReceives.reduce((prev, curr) => {
					return prev + parseFloat(curr.totalAmount)
				}, 0).toFixed(2);
			},
			totalEmployeePayments(){
				return this.employeePayments.reduce((prev, curr) => {
					return prev + parseFloat(curr.totalAmount)
				}, 0).toFixed(2);
			},
			totalInitialLoan(){
				return this.loanInitials.reduce((prev, curr) => {
					return prev + parseFloat(curr.initial_balance)
				}, 0).toFixed(2);
			},
			totalIn(){
				return parseFloat(this.openingBalance.cashBalance.cash_balance) +
					parseFloat(this.totalBankOpeningBalance) +  
					parseFloat(this.totalSales) + 
					parseFloat(this.totalLoanReceived) + 
					parseFloat(this.totalInvestReceived) + 
					parseFloat(this.totalAssetSales) + 
					parseFloat(this.totalInitialLoan) + 
					parseFloat(this.totalReceivedFromCustomers) + 
					parseFloat(this.totalReceivedFromSuppliers) + 
					parseFloat(this.totalCashReceived);
			},
			totalOut(){
				return parseFloat(this.totalPurchase) +
					parseFloat(this.totalPaidToCustomers) +
					parseFloat(this.totalPaidToSuppliers) +
					parseFloat(this.totalCashPaid) +
					parseFloat(this.totalLoanPayment) +
					parseFloat(this.totalInvestPayment) +
					parseFloat(this.totalAssetPurchases) +
					parseFloat(this.totalEmployeePayments) +
					parseFloat(this.closingBalance.cashBalance.cash_balance) +
					parseFloat(this.totalBankClosingBalance);
			},
			cashBalance(){
				return parseFloat(this.totalIn) -  parseFloat(this.totalOut);
			}			
		},
		created(){
			this.getDayBookData();
		},
		methods: {
			getDayBookData() {
				this.getOpeningBalance();
				this.getClosingBalance();
				this.getSales();
				this.getReceivedFromCustomers();
				this.getPaidToCustomers();
				this.getCashReceived();
				this.getCashPaid();
				this.getBankDeposits();
				this.getBankWithdraws();
				this.getEmployeePayments();
			},

			getOpeningBalance(){
				axios.post('/get_cash_and_bank_balance', {date: this.filter.dateFrom}).then(res => {
					this.openingBalance = res.data;
				})
			},

			getClosingBalance(){
				axios.post('/get_cash_and_bank_balance', {date: moment(this.filter.dateTo).add(1, 'days').format('YYYY-MM-DD')}).then(res => {
					this.closingBalance = res.data;
				})
			},

			getSales() {
				axios.post('/get_sales', this.filter)
					.then(res => {
						let sales = res.data.sales.filter(sale => sale.SaleMaster_PaidAmount > 0);
						sales = _.groupBy(sales, 'SalseCustomer_IDNo');
						sales = _.toArray(sales);
						sales = sales.map(sale => {
							sale[0].totalAmount = sale.reduce((p, c) => { return p + parseFloat(c.SaleMaster_PaidAmount) }, 0);
							return sale[0];
						})
						this.sales = sales;
					})
			},

			getReceivedFromCustomers() {
				let filter = {
					dateFrom: this.filter.dateFrom,
					dateTo: this.filter.dateTo,
					paymentType: 'received'
				}
				axios.post('/get_customer_payments', filter)
					.then(res => {
						let payments = res.data;
						payments = _.groupBy(payments, 'CPayment_customerID');
						payments = _.toArray(payments);
						payments = payments.map(payment => {
							payment[0].totalAmount = payment.reduce((p, c) => { return p + parseFloat(c.CPayment_amount) }, 0);
							return payment[0];
						})
						this.receivedFromCustomers = payments;
					})
			},

			getPaidToCustomers() {
				let filter = {
					dateFrom: this.filter.dateFrom,
					dateTo: this.filter.dateTo,
					paymentType: 'paid'
				}
				axios.post('/get_customer_payments', filter)
					.then(res => {
						let payments = res.data;
						payments = _.groupBy(payments, 'CPayment_customerID');
						payments = _.toArray(payments);
						payments = payments.map(payment => {
							payment[0].totalAmount = payment.reduce((p, c) => { return p + parseFloat(c.CPayment_amount) }, 0);
							return payment[0];
						})
						this.paidToCustomers = payments;
					})
			},

			getCashReceived() {
				let filter = {
					dateFrom: this.filter.dateFrom,
					dateTo: this.filter.dateTo,
					transactionType: 'received'
				}
				axios.post('/get_cash_transactions', filter)
					.then(res => {
						let transactions = res.data;
						transactions = _.groupBy(transactions, 'Acc_SlID');
						transactions = _.toArray(transactions);
						transactions = transactions.map(transaction => {
							transaction[0].totalAmount = transaction.reduce((p, c) => { return p + parseFloat(c.In_Amount) }, 0);
							return transaction[0];
						})
						this.cashReceived = transactions;
					})
			},

			getCashPaid() {
				let filter = {
					dateFrom: this.filter.dateFrom,
					dateTo: this.filter.dateTo,
					transactionType: 'paid'
				}
				axios.post('/get_cash_transactions', filter)
					.then(res => {
						let transactions = res.data;
						transactions = _.groupBy(transactions, 'Acc_SlID');
						transactions = _.toArray(transactions);
						transactions = transactions.map(transaction => {
							transaction[0].totalAmount = transaction.reduce((p, c) => { return p + parseFloat(c.Out_Amount) }, 0);
							return transaction[0];
						})
						this.cashPaid = transactions;
					})
			},

			getBankDeposits() {
				let filter = {
					dateFrom: this.filter.dateFrom,
					dateTo: this.filter.dateTo,
					transactionType: 'deposit'
				}
				axios.post('/get_bank_transactions', filter)
					.then(res => {
						let transactions = res.data;
						transactions = _.groupBy(transactions, 'account_id');
						transactions = _.toArray(transactions);
						transactions = transactions.map(transaction => {
							transaction[0].totalAmount = transaction.reduce((p, c) => { return p + parseFloat(c.amount) }, 0);
							return transaction[0];
						})
						this.bankDeposits = transactions;
					})
			},
			getBankWithdraws() {
				let filter = {
					dateFrom: this.filter.dateFrom,
					dateTo: this.filter.dateTo,
					transactionType: 'withdraw'
				}
				axios.post('/get_bank_transactions', filter)
					.then(res => {
						let transactions = res.data;
						transactions = _.groupBy(transactions, 'account_id');
						transactions = _.toArray(transactions);
						transactions = transactions.map(transaction => {
							transaction[0].totalAmount = transaction.reduce((p, c) => { return p + parseFloat(c.amount) }, 0);
							return transaction[0];
						})
						this.bankWithdraws = transactions;
					})
			},
			// getLoanPayments() {
			// 	let filter = {
			// 		dateFrom: this.filter.dateFrom,
			// 		dateTo: this.filter.dateTo,
			// 		transactionType: 'Payment'
			// 	}
			// 	axios.post('/get_loan_transactions', filter)
			// 		.then(res => {
			// 			let transactions = res.data;
			// 			transactions = _.groupBy(transactions, 'account_id');
			// 			transactions = _.toArray(transactions);
			// 			transactions = transactions.map(transaction => {
			// 				transaction[0].totalAmount = transaction.reduce((p, c) => { return p + parseFloat(c.amount) }, 0);
			// 				return transaction[0];
			// 			})
			// 			this.loanPayments = transactions;
			// 		})
			// },

			// getInvestPayments() {
			// 	let filter = {
			// 		dateFrom: this.filter.dateFrom,
			// 		dateTo: this.filter.dateTo,
			// 		transactionType: 'Payment'
			// 	}
			// 	axios.post('/get_investment_transactions', filter)
			// 		.then(res => {
			// 			let transactions = res.data;
			// 			transactions = _.groupBy(transactions, 'account_id');
			// 			transactions = _.toArray(transactions);
			// 			transactions = transactions.map(transaction => {
			// 				transaction[0].totalAmount = transaction.reduce((p, c) => { return p + parseFloat(c.amount) }, 0);
			// 				return transaction[0];
			// 			})
			// 			this.investPayments = transactions;
			// 		})
			// },

			// getLoanReceived() {
			// 	let filter = {
			// 		dateFrom: this.filter.dateFrom,
			// 		dateTo: this.filter.dateTo,
			// 		transactionType: 'Receive'
			// 	}
			// 	axios.post('/get_loan_transactions', filter)
			// 		.then(res => {
			// 			let transactions = res.data;
			// 			transactions = _.groupBy(transactions, 'account_id');
			// 			transactions = _.toArray(transactions);
			// 			transactions = transactions.map(transaction => {
			// 				transaction[0].totalAmount = transaction.reduce((p, c) => { return p + parseFloat(c.amount) }, 0);
			// 				return transaction[0];
			// 			})
			// 			this.loanReceives = transactions;
			// 		})

			// 	axios.post('/get_loan_initial_balance', this.filter).then(res => {
			// 		this.loanInitials = res.data.accounts;
			// 	})
			// },
			// getInvestReceived() {
			// 	let filter = {
			// 		dateFrom: this.filter.dateFrom,
			// 		dateTo: this.filter.dateTo,
			// 		transactionType: 'Receive'
			// 	}
			// 	axios.post('/get_investment_transactions', filter)
			// 		.then(res => {
			// 			let transactions = res.data;
			// 			transactions = _.groupBy(transactions, 'account_id');
			// 			transactions = _.toArray(transactions);
			// 			transactions = transactions.map(transaction => {
			// 				transaction[0].totalAmount = transaction.reduce((p, c) => { return p + parseFloat(c.amount) }, 0);
			// 				return transaction[0];
			// 			})
			// 			this.investReceives = transactions;
			// 		})
			// },

			getEmployeePayments(){
				axios.post('/get_salary_details', this.filter)
				.then(res => { 
					let payments = res.data;
					payments = _.groupBy(payments, 'employee_id');
					payments = _.toArray(payments);
					payments = payments.map(payment => {
						payment[0].totalAmount = payment.reduce((p, c) => { return p + parseFloat(c.payment) }, 0);
						return payment[0];
					})
					this.employeePayments = payments;

				})
			},

			async print(){
				let printContent = `
					<div class="container">
						<h4 style="text-align:center">Receipt and Payment</h4 style="text-align:center">
						<div class="row">
							<div class="col-xs-12 text-center">
								<strong>Statement from</strong> ${this.filter.dateFrom} <strong>to</strong> ${this.filter.dateTo}
							</div>
						</div>
					</div>
					<div class="container">
						${document.querySelector('#printContent').innerHTML}
					</div>
				`;

				var printWindow = window.open('', 'PRINT', `width=${screen.width}, height=${screen.height}`);
				printWindow.document.write(`
					<?php $this->load->view('Administrator/reports/reportHeader.php');?>
				`);

				printWindow.document.body.innerHTML += printContent;
				printWindow.document.head.innerHTML += `
					<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
					<style>
						.day-book-table {
							width: 100%;
							margin-bottom: 50px;
						}
						.day-book-table thead {
							background: #ebebeb;
							border-bottom: 1px solid black;
						}
						.day-book-table th {
							padding: 5px 10px;
							text-align: left;
						}
						.day-book-table td {
							padding: 0px 30px;
						}
						.day-book-table tr td:last-child {
							text-align: right;
							padding-right: 50px;
						}
						.day-book-table .main-heading {
							padding-left: 10px;
							font-weight: bold;
						}
						.day-book-table .sub-heading {
							padding-left: 20px;
							font-weight: bold;
						}
						.day-book-table .sub-value {
							padding-right: 10px!important;
							font-weight: bold;
						}
					</style>
				`;

				printWindow.focus();
				await new Promise(resolve => setTimeout(resolve, 1000));
				printWindow.print();
				printWindow.close();
			}
		}
	})
</script>
