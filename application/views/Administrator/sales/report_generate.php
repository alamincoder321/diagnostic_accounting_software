<style>
    .v-select {
        margin-top: -2.5px;
        float: right;
        min-width: 220px;
        margin-left: 5px;
    }

    .v-select .dropdown-toggle {
        padding: 0px;
        height: 25px;
    }

    .v-select input[type=search],
    .v-select input[type=search]:focus {
        margin: 0px;
    }

    .v-select .vs__selected-options {
        overflow: hidden;
        flex-wrap: nowrap;
    }

    .v-select .selected-tag {
        margin: 2px 0px;
        white-space: nowrap;
        position: absolute;
        left: 0px;
    }

    .v-select .vs__actions {
        margin-top: -5px;
    }

    .v-select .dropdown-menu {
        width: auto;
        overflow-y: auto;
    }

    .card {
        border: 1px solid gray;
        border-radius: 5px;
        padding: 5px;
    }

    .card-header {
        background: gainsboro;
        padding: 10px 10px;
    }

    .card-header .card-title {
        margin: 0;
    }

    tr td,
    tr th {
        vertical-align: middle !important;
    }
</style>
<div id="reportGenerate">
    <div class="row" style="border: 1px solid gray; padding: 8px 0px; border-radius: 5px; box-shadow: 0px 0px 0px 3px #007ebb; margin: 0;">
        <div class="col-md-12">
            <form class="form-inline" id="searchForm">
                <div class="form-group">
                    <label style="margin: 0;margin-top: -5px;">Patient</label>
                    <v-select v-bind:options="customers" v-model="selectedCustomer" label="display_name" @input="onChangeCustomer" @search="onSearchCustomer"></v-select>
                </div>

                <div class="form-group">
                    <label style="margin: 0;margin-top: -5px;">Invoice</label>
                    <v-select v-bind:options="invoices" v-model="selectedInvoice" label="invoice_text" @input="onChangeInvoice" @search="onSearchSale"></v-select>
                </div>

                <div class="form-group">
                    <label style="margin: 0;margin-top: -5px;">Test Category Name</label>
                    <v-select v-bind:options="categories" v-model="selectedCategory" label="ProductCategory_Name" @input="getReportTest"></v-select>
                </div>

                <!-- <div class="form-group" style="margin-top: -2px;">
                    <input type="submit" value="Search">
                </div> -->
            </form>
        </div>
    </div>
    <div class="row" style="margin-top: 8px;">
        <div class="col-xs-12 col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"></h4>
                </div>
                <div class="card-body">
                    <table class="table table-hover table-bordered table-striped" style="margin-bottom: 0;">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Test Name</th>
                                <th>Result</th>
                                <th>Unit</th>
                                <th>Normal Range</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in carts" v-if="carts.length > 0" style="display: none;" :style="{display: carts.length > 0 ? '' : 'none'}">
                                <td v-text="index + 1"></td>
                                <td style="text-align: left;" v-text="`${item.name}`"></td>
                                <td>
                                    <input type="text" style="margin: 0;" class="form-control text-center" v-model="item.result" />
                                </td>
                                <td v-text="item.Unit_Name"></td>
                                <td v-text="item.normal_range"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-md-4">
            <div class="card" style="margin-bottom: 5px;">
                <div class="card-header">
                    <h4 class="card-title">Left Signature</h4>
                </div>
                <div class="card-body">
                    <table class="table table-hover table-bordered table-striped" style="margin-bottom: 0;">
                        <tbody>
                            <tr>
                                <td style="text-align: left;">Name</td>
                                <td style="text-align: right;">
                                    <v-select :options="doctors" style="width: 100%;margin-top:2px;" v-model="selectedLeftDoctor" label="display_name" @input="onChangeLeftDoctor"></v-select>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: left;">Degree</td>
                                <td style="text-align: right;">
                                    <input type="text" v-model="report.left_degree" class="form-control" style="margin-bottom: 0;" readonly>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: left;">Department</td>
                                <td style="text-align: right;">
                                    <input type="text" v-model="report.left_department" class="form-control" style="margin-bottom: 0;" readonly>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card" style="margin-bottom: 5px;">
                <div class="card-header">
                    <h4 class="card-title">Right Signature</h4>
                </div>
                <div class="card-body">
                    <table class="table table-hover table-bordered table-striped" style="margin-bottom: 0;">
                        <tbody>
                            <tr>
                                <td style="text-align: left;">Name</td>
                                <td style="text-align: right;">
                                    <v-select :options="doctors" style="width: 100%;margin-top:2px;" v-model="selectedRightDoctor" label="display_name" @input="onChangeRightDoctor"></v-select>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: left;">Degree</td>
                                <td style="text-align: right;">
                                    <input type="text" v-model="report.right_degree" class="form-control" style="margin-bottom: 0;" readonly>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: left;">Department</td>
                                <td style="text-align: right;">
                                    <input type="text" v-model="report.right_department" class="form-control" style="margin-bottom: 0;" readonly>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"></h4>
                </div>
                <div class="card-body">
                    <table class="table table-hover table-bordered table-striped" style="margin-bottom: 0;">
                        <tbody>
                            <tr>
                                <td style="text-align: left;">Date</td>
                                <td style="text-align: right;">
                                    <input type="date" v-model="report.date" class="form-control" style="margin-bottom: 0;">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <button type="button" @click="saveReport" style="width: 100%; padding: 10px 20px; background: #30b5f5; color: #fff; border: 1px solid #30b5f5;display: none;" :style="{display: report.id > 0 ? '' : 'none'}">
                                        Update Report
                                    </button>
                                    <button v-show="report.id == 0" type="button" @click="saveReport" style="width: 100%; padding: 10px 20px; background: #30b5f5; color: #fff; border: 1px solid #30b5f5;display:none;" :style="{display: report.id == 0 ? '' : 'none'}">
                                        Generate Report
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url(); ?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/vuejs-datatable.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/vue-select.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>

<script>
    Vue.component('v-select', VueSelect.VueSelect);
    new Vue({
        el: '#reportGenerate',
        data() {
            return {
                categories: [],
                customers: [],
                selectedCustomer: {
                    Customer_SlNo: '',
                    Customer_Code: '',
                    Customer_Name: '',
                    display_name: 'select patient',
                    Customer_Mobile: '',
                    Customer_Address: '',
                    Customer_Type: '',
                },
                invoices: [],
                selectedInvoice: null,
                report: {
                    id: parseInt("<?= $id ?>"),
                    date: moment().format('YYYY-MM-DD'),
                    patient_id: "",
                    sale_id: "",
                    category_id: "",
                    left_doctor_id: "",
                    left_name: "",
                    left_degree: "",
                    left_department: "",
                    right_doctor_id: "",
                    right_name: "",
                    right_degree: "",
                    right_department: ""
                },
                categories: [],
                selectedCategory: null,
                carts: [],
                doctors: [],
                selectedLeftDoctor: null,
                selectedRightDoctor: null,
            }
        },
        async created() {
            this.getDoctors();
            this.getCustomers();
            if (this.report.id > 0) {
                await this.getGenerateReport();
            }
        },
        methods: {
            getDoctors() {
                axios.get('/get_doctors').then(res => {
                    this.doctors = res.data;
                })
            },
            async getCustomers() {
                await axios.post('/get_customers', {
                    forSearch: 'yes'
                }).then(res => {
                    this.customers = res.data;
                    this.customers.unshift({
                        Customer_SlNo: '',
                        Customer_Code: '',
                        Customer_Name: 'General Patient',
                        display_name: 'General Patient',
                        Customer_Mobile: '',
                        Customer_Address: '',
                        Customer_Type: 'G'
                    })
                })
            },
            async onSearchCustomer(val, loading) {
                if (val.length > 2) {
                    loading(true);
                    await axios.post("/get_customers", {
                            name: val,
                        })
                        .then(res => {
                            let r = res.data;
                            this.customers = r.filter(item => item.status == 'a')
                            loading(false)
                        })
                } else {
                    loading(false)
                    await this.getCustomers();
                }
            },
            onChangeCustomer() {
                this.selectedInvoice = null;
                this.sales = {
                    saleDetails: []
                }
                if (this.selectedCustomer == null) {
                    this.selectedCustomer = {
                        Customer_SlNo: '',
                        Customer_Code: '',
                        Customer_Name: '',
                        display_name: 'select patient',
                        Customer_Mobile: '',
                        Customer_Address: '',
                        Customer_Type: '',
                    };
                    return;
                }
                if (this.selectedCustomer.Customer_Type != '') {
                    this.getSales('yes');
                }
            },
            getSales(search = null) {
                let filter = {
                    customerId: this.selectedCustomer.Customer_SlNo != '' ? this.selectedCustomer.Customer_SlNo : null,
                }
                if (this.selectedCustomer.Customer_Type == 'G') {
                    filter.customerType = 'G';
                }
                if (search == null) {
                    filter.forSearch = 'yes';
                }
                axios.post('/get_sales', filter)
                    .then(res => {
                        this.invoices = res.data.sales;
                    })
            },
            async onSearchSale(val, loading) {
                if (val.length > 3) {
                    loading(true);
                    await axios.post("/get_sales", {
                            name: val,
                        })
                        .then(res => {
                            let r = res.data;
                            this.invoices = r.filter(item => item.status == 'a')
                            loading(false)
                        })
                } else {
                    loading(false)
                    await this.getSales();
                }
            },
            onChangeInvoice() {
                this.selectedCategory = null;
                this.carts = [];
                if (this.selectedInvoice == null) {
                    return;
                }
                let filter = {
                    customerId: this.selectedCustomer ? this.selectedCustomer.Customer_SlNo : null,
                    saleId: this.selectedInvoice ? this.selectedInvoice.SaleMaster_SlNo : null
                }
                axios.post('/get_sales_record', filter)
                    .then(res => {
                        this.categories = res.data[0].saleDetails;
                    })
            },
            getReportTest() {
                this.carts = [];
                if (this.selectedInvoice == null) {
                    return;
                }
                if (this.selectedCategory == null) {
                    return;
                }
                
                let filter = {
                    customerId: this.selectedCustomer ? this.selectedCustomer.Customer_SlNo : null,
                    saleId: this.selectedInvoice ? this.selectedInvoice.SaleMaster_SlNo : null,
                    categoryId: this.selectedCategory.ProductCategory_SlNo,
                    isGenerated: 'yes'
                }
                axios.post('/get_report_test', filter)
                    .then(res => {
                        this.carts = res.data;
                    })
            },

            onChangeLeftDoctor() {
                if (this.selectedLeftDoctor) {
                    this.report.left_doctor_id = this.selectedLeftDoctor.Doctor_SlNo;
                    this.report.left_name = this.selectedLeftDoctor.Doctor_Name;
                    this.report.left_degree = this.selectedLeftDoctor.specialization;
                    this.report.left_department = this.selectedLeftDoctor.Department_Name;
                } else {
                    this.report.left_name = '';
                    this.report.left_degree = '';
                    this.report.left_department = '';
                }
            },

            onChangeRightDoctor() {
                if (this.selectedRightDoctor) {
                    this.report.right_doctor_id = this.selectedRightDoctor.Doctor_SlNo;
                    this.report.right_name = this.selectedRightDoctor.Doctor_Name;
                    this.report.right_degree = this.selectedRightDoctor.specialization;
                    this.report.right_department = this.selectedRightDoctor.Department_Name;
                } else {
                    this.report.right_name = '';
                    this.report.right_degree = '';
                    this.report.right_department = '';
                }
            },

            saveReport() {
                this.report.patient_id = this.selectedCustomer ? this.selectedCustomer.Customer_SlNo : '';
                this.report.category_id = this.selectedCategory ? this.selectedCategory.ProductCategory_SlNo : '';
                this.report.sale_id = this.selectedInvoice ? this.selectedInvoice.SaleMaster_SlNo : '';
                if (this.report.test_id == "") {
                    alert("Please select a test category.");
                    return;
                }
                if (this.report.sale_id == "") {
                    alert("Please select a sale.");
                    return;
                }

                let data = {
                    report: this.report,
                    carts: this.carts
                }
                let url = this.report.id > 0 ? '/update_report_generate' : '/add_report_generate';

                axios.post(url, data)
                    .then(res => {
                        alert(res.data.message);
                        if (res.data.success) {
                            this.clearData();
                            window.history.pushState({}, '', '/report_generate');
                        }
                    })
            },

            clearData() {
                this.report = {
                    id: 0,
                    date: moment().format('YYYY-MM-DD'),
                    patient_id: "",
                    sale_id: "",
                    category_id: "",
                    left_name: "",
                    left_degree: "",
                    left_department: "",
                    right_name: "",
                    right_degree: "",
                    right_department: ""
                };
                this.carts = []
                this.selectedCategory = null;
                this.selectedInvoice = null;
                this.invoices = [];
                this.categories = [];
                this.selectedCustomer = {
                    Customer_SlNo: '',
                    Customer_Code: '',
                    Customer_Name: '',
                    display_name: 'select patient',
                    Customer_Mobile: '',
                    Customer_Address: '',
                    Customer_Type: '',
                }
                this.selectedLeftDoctor = null;
                this.selectedRightDoctor = null;
            },

            async getGenerateReport() {
                await axios.post('/get_report_list', {
                    reportId: this.report.id
                }).then(res => {
                    let data = res.data[0];
                    Object.keys(this.report).forEach(key => {
                        if (data[key] !== undefined) {
                            this.report[key] = data[key];
                        }
                    });
                    this.selectedCustomer = {
                        Customer_SlNo: data.patient_id,
                        Customer_Code: data.Customer_Code,
                        Customer_Name: data.Customer_Name,
                        display_name: data.Customer_Code == 'General Patient' ? 'General Patient' : `${data.Customer_Code} - ${data.Customer_Name} - ${data.Customer_Mobile}`,
                        Customer_Mobile: data.Customer_Mobile,
                        Customer_Address: data.Customer_Address,
                        Customer_Type: data.Customer_Code == 'General Patient' ? 'G' : 'retail',
                    };

                    setTimeout(() => {
                        this.selectedInvoice = this.invoices.find(invoice => invoice.SaleMaster_SlNo == data.sale_id);
                        this.selectedLeftDoctor = this.doctors.find(doctor => doctor.Doctor_SlNo == data.left_doctor_id);
                        this.selectedRightDoctor = this.doctors.find(doctor => doctor.Doctor_SlNo == data.right_doctor_id);
                    }, 1000);
                    setTimeout(() => {
                        this.selectedCategory = this.categories.find(product => product.ProductCategory_SlNo == data.category_id);
                    }, 2500);
                    setTimeout(() => {
                        this.carts = data.details.map(detail => ({
                            name: detail.name,
                            result: detail.result,
                            Unit_Name: detail.Unit_Name,
                            normal_range: detail.normal_range,
                            test_id: detail.test_id,
                            subtest_id: detail.subtest_id,
                            category_id: detail.category_id
                        }));
                    }, 3500);
                });
            }
        }
    })
</script>