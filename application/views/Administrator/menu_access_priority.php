<style>
    #userAccess * {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 14px;
    }

    #userAccess h2{
        font-size: 16px;
        font-weight: bold;
        border-top: 1px solid #ccc;
        border-bottom: 1px solid #ccc;
        text-transform: uppercase;
        padding: 5px;
    }

    #userAccess ul {
        list-style: none;
        margin-left: 17px;
    }
</style>
<div id="userAccess">
    <div class="row">
        <div class="col-md-12 text-center">
            <div>
                <h2>User Access</h2>
            </div>
        </div>
    </div>
    <div class="row" style="margin-bottom: 15px;">
        <div class="col-md-12">
            <input type="checkbox" @click="checkAll" id="selectAll"> <strong style="font-size: 16px;">Select All</strong>
        </div>
    </div>
    <div class="row" id="accessRow">
        <div class="col-md-3">
            <div class="group">
                <input type="checkbox" id="sales" class="group-head" @click="onClickGroupHeads"> <strong>Report Panel</strong>
                <ul ref="sales">
                    <li><input type="checkbox" class="access" value="bill_entry" v-model="access"> Bill Entry</li>
                    <li><input type="checkbox" class="access" value="salesrecord" v-model="access"> Bill Record</li>
                    <li><input type="checkbox" class="access" value="report_generate" v-model="access"> Report Generate</li>
                    <li><input type="checkbox" class="access" value="report_list" v-model="access"> Report List</li>
                    <li><input type="checkbox" class="access" value="report_invoice" v-model="access"> Report Invoice</li>
                    <li><input type="checkbox" class="access" value="dialysis" v-model="access"> Dialysis Entry</li>
                    <li><input type="checkbox" class="access" value="dialysisList" v-model="access"> Dialysis List</li>
                    <li><input type="checkbox" class="access" value="dialysis_invoice" v-model="access"> Dialysis Invoice</li>
                </ul>
            </div>

            <div class="group">
                <input type="checkbox" id="accounts" class="group-head" @click="onClickGroupHeads"> <strong>Accounts</strong>
                <ul ref="accounts">
                    <li><input type="checkbox" class="access" value="cashTransaction" v-model="access"> Cash Transactions</li>
                    <li><input type="checkbox" class="access" value="bank_transactions" v-model="access"> Bank Transactions</li>
                    <li><input type="checkbox" class="access" value="customerPaymentPage" v-model="access"> Customer Payment</li>
                    <li><input type="checkbox" class="access" value="supplierPayment" v-model="access"> Supplier Payment</li>
                    <li><input type="checkbox" class="access" value="cash_view" v-model="access"> Cash View</li>
                    <li><input type="checkbox" class="access" value="account" v-model="access"> Transaction Accounts</li>
                    <li><input type="checkbox" class="access" value="bank_accounts" v-model="access"> Bank Accounts</li>
                </ul>
            </div>
        </div>
        <div class="col-md-3">
            <div class="group">
                <input type="checkbox" id="salesReports" class="group-head" @click="onClickGroupHeads"> <strong>Bill Reports</strong>
                <ul ref="salesReports">
                    <li><input type="checkbox" class="access" value="salesinvoice" v-model="access"> Bill Invoice</li>
                    <li><input type="checkbox" class="access" value="customerDue" v-model="access"> Patient Due List</li>
                    <li><input type="checkbox" class="access" value="customerPaymentReport" v-model="access"> Patient Ledger</li>
                    <li><input type="checkbox" class="access" value="customer_payment_history" v-model="access"> Patient Payment History</li>
                    <li><input type="checkbox" class="access" value="customerlist" v-model="access"> Patient List</li>
                </ul>
            </div>

            <div class="group">
                <input type="checkbox" id="accountsReports" class="group-head" @click="onClickGroupHeads"> <strong>Accounts Reports</strong>
                <ul ref="accountsReports">
                    <li><input type="checkbox" class="access" value="TransactionReport" v-model="access"> Cash Transaction Report</li>
                    <li><input type="checkbox" class="access" value="bank_transaction_report" v-model="access"> Bank Transaction Report</li>
                    <li><input type="checkbox" class="access" value="cash_ledger" v-model="access"> Cash Ledger</li>
                    <li><input type="checkbox" class="access" value="bank_ledger" v-model="access"> Bank Ledger</li>
                    <li><input type="checkbox" class="access" value="cash_view" v-model="access"> Cash View</li>
                    <li><input type="checkbox" class="access" value="cashStatment" v-model="access"> Cash Statement</li>
                    <li><input type="checkbox" class="access" value="balance_sheet" v-model="access"> Balance Sheet</li>
                    <li><input type="checkbox" class="access" value="BalanceSheet" v-model="access"> Balance In Out</li>
                    <li><input type="checkbox" class="access" value="profitLoss" v-model="access"> Profit/Loss Report</li>
                    <li><input type="checkbox" class="access" value="day_book" v-model="access"> Day Book</li>
                </ul>
            </div>
        </div>
        <div class="col-md-3">
            <div class="group">
                <input type="checkbox" id="hrPayroll" class="group-head" @click="onClickGroupHeads"> <strong>HR & Payroll</strong>
                <ul ref="hrPayroll">
                    <li><input type="checkbox" class="access" value="salary_payment" v-model="access"> Salary Payment</li>
                    <li><input type="checkbox" class="access" value="employee" v-model="access"> Add Employee</li>
                    <li><input type="checkbox" class="access" value="emplists/all" v-model="access"> All Employee List</li>
                    <li><input type="checkbox" class="access" value="emplists/active" v-model="access"> Active Employee List</li>
                    <li><input type="checkbox" class="access" value="emplists/deactive" v-model="access"> Deactive Employee List</li>
                    <li><input type="checkbox" class="access" value="designation" v-model="access"> Add Designation</li>
                    <li><input type="checkbox" class="access" value="depertment" v-model="access"> Add Department</li>
                    <li><input type="checkbox" class="access" value="month" v-model="access"> Add Month</li>
                    <li><input type="checkbox" class="access" value="salary_payment_report" v-model="access"> Salary Payment Report</li>
                </ul>
            </div>
        </div>
        <div class="col-md-3">
            <div class="group">
                <input type="checkbox" id="admin" class="group-head" @click="onClickGroupHeads"> <strong>Administrator</strong>
                <ul ref="admin">
                    <li><input type="checkbox" class="access" value="sms" v-model="access"> Send SMS</li>
                    <li><input type="checkbox" class="access" value="product" v-model="access"> Test Entry</li>
                    <li><input type="checkbox" class="access" value="productlist" v-model="access"> Test List</li>
                    <li><input type="checkbox" class="access" value="product_ledger" v-model="access"> Test Ledger</li>
                    <li><input type="checkbox" class="access" value="customer" v-model="access"> Patient Entry</li>
                    <li><input type="checkbox" class="access" value="doctor" v-model="access"> Doctor Entry</li>
                    <li><input type="checkbox" class="access" value="doctorlist" v-model="access"> Doctor List</li>
                    <li><input type="checkbox" class="access" value="agent" v-model="access"> Agent Entry</li>
                    <li><input type="checkbox" class="access" value="agentlist" v-model="access"> Agent List</li>
                    <li><input type="checkbox" class="access" value="category" v-model="access"> Category Entry</li>
                    <li><input type="checkbox" class="access" value="subcategory" v-model="access"> SubTest Entry</li>
                    <li><input type="checkbox" class="access" value="room" v-model="access"> Room Entry</li>
                    <li><input type="checkbox" class="access" value="unit" v-model="access"> Unit Entry</li>
                    <li><input type="checkbox" class="access" value="area" v-model="access"> Add Area</li>
                    <li><input type="checkbox" class="access" value="companyProfile" v-model="access"> Company Profile</li>
                    <li><input type="checkbox" class="access" value="user" v-model="access"> Create User</li>
                    <li><input type="checkbox" class="access" value="database_backup" v-model="access"> Database Backup</li>
                    <li><input type="checkbox" class="access" value="graph" v-model="access"> Business View</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 text-right">
            <button class="btn btn-success" @click="addUserAccess">Save</button>
        </div>
    </div>
</div>

<script src="<?php echo base_url(); ?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/vue-select.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>

<script>
    new Vue({
        el: '#userAccess',
        data() {
            return {
                userId: parseInt('<?php echo $userId;?>'),
                access: []
            }
        },
        mounted() {
            let accessCheckboxes = document.querySelectorAll('.access');
            accessCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('click', () => {
                    this.makeChecked();
                })
            })
        },
        async created(){
            await axios.post('/get_user_access', {userId: this.userId}).then(res => {
                let r = res.data;
                if(r != ''){
                    this.access = JSON.parse(r);
                }
            })
            this.makeChecked();
        },
        methods: {
            makeChecked(){
                groups = document.querySelectorAll('.group');
                groups.forEach(group => {
                    let groupHead = group.querySelector('.group-head');
                    let accessCheckboxes = group.querySelectorAll('ul li input').length;
                    let checkedAccessCheckBoxes = group.querySelectorAll('ul li input:checked').length;
                    if(accessCheckboxes == checkedAccessCheckBoxes){
                        groupHead.checked = true;
                    } else {
                        groupHead.checked = false;
                    }
                })

                let selectAllCheckbox = document.querySelector('#selectAll');
                let totalAccessCheckboxes = document.querySelectorAll('.access').length;
                let totalCheckedAccessCheckBoxes = document.querySelectorAll('.access:checked').length;

                if(totalAccessCheckboxes == totalCheckedAccessCheckBoxes){
                    selectAllCheckbox.checked = true;
                } else {
                    selectAllCheckbox.checked = false;
                }
            },
            async onClickGroupHeads() {
                let groupHead = event.target;
                let ul = groupHead.parentNode.querySelector('ul');
                let accessCheckboxes = ul.querySelectorAll('li input');

                if(groupHead.checked){
                    accessCheckboxes.forEach(checkbox => {
                        this.access.push(checkbox.value);
                    })
                } else {
                    accessCheckboxes.forEach(checkbox => {
                        let ind = this.access.findIndex(a => a == checkbox.value);
                        this.access.splice(ind, 1);
                    })
                }
                this.access = this.access.filter((v, i, a) => a.indexOf(v) === i);
                await new Promise(r => setTimeout(r, 200));
                this.makeChecked();
            },
            async checkAll(){
                if(event.target.checked){
                    let accessCheckboxes = document.querySelectorAll('.access');
                    accessCheckboxes.forEach(checkbox => {
                        this.access.push(checkbox.value)
                    })
                } else {
                    this.access = [];
                }
                this.access = this.access.filter((v, i, a) => a.indexOf(v) === i);
                await new Promise(r => setTimeout(r, 200));
                this.makeChecked();
            },
            addUserAccess(){
                let data = {
                    userId: this.userId,
                    access: this.access
                }
                axios.post('/add_user_access', data).then(res => {
                    let r = res.data;
                    alert(r.message);
                })
            }
        }
    })
</script>