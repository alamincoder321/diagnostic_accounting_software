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
                    <label style="margin: 0;margin-top: -5px;">Category</label>
                    <v-select v-bind:options="categories" v-model="selectedCategory" label="ProductCategory_Name" @input="getReportTest"></v-select>
                </div>

                <!-- <div class="form-group" style="margin-top: -2px;">
                    <input type="submit" value="Search">
                </div> -->
            </form>
        </div>
    </div>
    <div class="row" style="margin-top: 8px;">
        <div class="col-xs-12 col-md-9">
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
                                    <input type="text" class="form-control text-center" v-model="item.result" />
                                </td>
                                <td v-text="item.Unit_Name"></td>
                                <td v-text="item.normal_range"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-md-3">
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
                                    <input type="date" v-model="report.date" class="form-control">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <button type="button" @click="saveExchange" style="width: 100%;">Generate</button>
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
                products: [],
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
                    date: moment().format('YYYY-MM-DD'),
                    patient_id: "",
                    sale_id: "",
                    category_id: ""
                },
                categories: [],
                selectedCategory: null,
                carts: []
            }
        },
        created() {
            this.getCustomers();
            this.getSales();
        },
        methods: {
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
                    categoryId: this.selectedCategory.ProductCategory_SlNo
                }
                axios.post('/get_report_test', filter)
                    .then(res => {
                        this.carts = res.data;
                    })
            },

            saveExchange() {
                this.report.patient_id = this.selectedCustomer.Customer_SlNo;
                this.report.category_id = this.selectedCategory.ProductCategory_SlNo;
                this.report.sale_id = this.selectedInvoice.SaleMaster_SlNo;

                let data = {
                    report: this.report,
                    carts: this.carts
                }
                axios.post('/add_report_generate', data)
                    .then(res => {
                        if (res.data.success) {
                            alert(res.data.message);
                            this.clearData();
                        }
                    })
            },

            clearData() {
                this.report = {
                    date: moment().format('YYYY-MM-DD'),
                    patient_id: "",
                    sale_id: "",
                    category_id: ""
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
            }
        }
    })
</script>