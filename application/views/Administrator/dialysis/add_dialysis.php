<style>
    .v-select {
        margin-bottom: 5px;
    }

    .v-select .dropdown-toggle {
        padding: 0px;
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

    #branchDropdown .vs__actions button {
        display: none;
    }

    #branchDropdown .vs__actions .open-indicator {
        height: 15px;
        margin-top: 7px;
    }

    .add-button {
        padding: 2.8px;
        width: 100%;
        background-color: #0087bb;
        display: block;
        text-align: center;
        color: white;
        cursor: pointer;
        border-radius: 3px;
    }

    .add-button:hover {
        color: white;
    }

    .add-button:focus {
        color: white;
    }

    tr td {
        vertical-align: middle !important;
    }
</style>

<div id="dialysis" class="row">
    <div class="col-xs-12 col-md-12 col-lg-12" style="border-bottom:1px #ccc solid;margin-bottom:5px;">
        <div class="row">
            <div class="form-group">
                <label class="col-md-1 control-label no-padding-right"> Exist Invoice </label>
                <div class="col-md-3">
                    <v-select :options="existdialysis" v-model="selectedExistDialysis" label="display_name" @input="checkExistDialysis" placeholder="Select Invoice"></v-select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-1 control-label no-padding-right"> Invoice no </label>
                <div class="col-md-2">
                    <input type="text" id="invoice" class="form-control" v-model="dialysis.invoice" readonly />
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-1 control-label no-padding-right"> Added By </label>
                <div class="col-md-2">
                    <input type="text" class="form-control" value="<?= $this->session->userdata('FullName') ?>" readonly />
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-2">
                    <input class="form-control" id="date" type="date" v-model="dialysis.date" v-bind:disabled="userType == 'u' ? true : false" />
                </div>
            </div>
        </div>
    </div>


    <div class="col-xs-12 col-md-5 col-lg-5">
        <div class="widget-box">
            <div class="widget-header">
                <h4 class="widget-title">Patient & Others Information</h4>
                <div class="widget-toolbar">
                    <a href="#" data-action="collapse">
                        <i class="ace-icon fa fa-chevron-up"></i>
                    </a>

                    <a href="#" data-action="close">
                        <i class="ace-icon fa fa-times"></i>
                    </a>
                </div>
            </div>

            <div class="widget-body">
                <div class="widget-main">
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="form-group">
                                <label class="col-xs-4 control-label no-padding-right"> Patient </label>
                                <div class="col-xs-8" style="display: flex;align-items:center;margin-bottom:5px;">
                                    <div style="width: 86%;">
                                        <v-select v-bind:options="customers" style="margin: 0;" label="display_name" v-model="selectedCustomer" v-on:input="customerOnChange" @search="onSearchCustomer"></v-select>
                                    </div>
                                    <div style="width: 13%;margin-left:2px;">
                                        <a href="<?= base_url('customer') ?>" class="add-button" target="_blank" title="Add New Customer"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-xs-4 control-label no-padding-right"> Name </label>
                                <div class="col-xs-8">
                                    <input type="text" id="customerName" placeholder="Patient Name" class="form-control" v-model="selectedCustomer.Customer_Name" v-bind:disabled="selectedCustomer.Customer_Type == 'G' || selectedCustomer.Customer_Type == 'N' ? false : true" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-xs-4 control-label no-padding-right"> Mobile </label>
                                <div class="col-xs-8">
                                    <input type="text" id="mobileNo" placeholder="Mobile" class="form-control" v-model="selectedCustomer.Customer_Mobile" v-bind:disabled="selectedCustomer.Customer_Type == 'G' || selectedCustomer.Customer_Type == 'N' ? false : true" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-xs-4 control-label no-padding-right"> Address </label>
                                <div class="col-xs-8">
                                    <textarea id="address" style="height: 35px;" placeholder="Address" class="form-control" v-model="selectedCustomer.Customer_Address" v-bind:disabled="selectedCustomer.Customer_Type == 'G' || selectedCustomer.Customer_Type == 'N' ? false : true"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-xs-12" style="border-top: 2px solid gray;padding-top: 5px;">
                            <div class="form-group">
                                <label class="col-xs-4 control-label no-padding-right"> Dialysis Status </label>
                                <div class="col-xs-8">
                                    <select v-model="dialysis.dialysis_status" class="form-control" style="padding: 1px 3px;">
                                        <option value="Satisfactory">Satisfactory</option>
                                        <option value="Not Satisfactory">Not Satisfactory</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-md-7 col-lg-7">
        <div class="widget-box">
            <div class="widget-header">
                <h4 class="widget-title">Details Information</h4>
                <div class="widget-toolbar">
                    <a href="#" data-action="collapse">
                        <i class="ace-icon fa fa-chevron-up"></i>
                    </a>

                    <a href="#" data-action="close">
                        <i class="ace-icon fa fa-times"></i>
                    </a>
                </div>
            </div>

            <div class="widget-body">
                <div class="widget-main">
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="form-group">
                                <label class="col-xs-4 control-label no-padding-right"> Dialyzer Built By </label>
                                <div class="col-xs-8">
                                    <input type="text" class="form-control" v-model="dialysis.built_by" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-xs-4 control-label no-padding-right"> Supervised By Doctor </label>
                                <div class="col-xs-8">
                                    <input type="text" class="form-control" v-model="dialysis.supervised_by" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-xs-4 control-label no-padding-right"> Type of Dialyzer Reuse</label>
                                <div class="col-xs-8">
                                    <input type="text" class="form-control" v-model="dialysis.dialyzer_reuse" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-xs-4 control-label no-padding-right"> Normal Saline</label>
                                <div class="col-xs-3">
                                    <input type="text" class="form-control" v-model="dialysis.normal_saline" />
                                </div>
                                <label class="col-xs-1 control-label no-padding-right">UF</label>
                                <div class="col-xs-4">
                                    <input type="text" class="form-control" v-model="dialysis.uf" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-xs-4 control-label no-padding-right"> Duration</label>
                                <div class="col-xs-3">
                                    <input type="text" class="form-control" v-model="dialysis.duration" />
                                </div>
                                <label class="col-xs-1 control-label no-padding-right">Heparin</label>
                                <div class="col-xs-4">
                                    <input type="text" class="form-control" v-model="dialysis.heparin" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-xs-4 control-label no-padding-right"> Initial Dose</label>
                                <div class="col-xs-2">
                                    <input type="text" class="form-control" v-model="dialysis.initial_dose" />
                                </div>
                                <label class="col-xs-3 control-label no-padding-right">Blood Transfusion</label>
                                <div class="col-xs-3">
                                    <input type="text" class="form-control" v-model="dialysis.blood_transfusion" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-md-12 col-lg-12">
        <div class="table-responsive">
            <table class="table table-bordered" style="color:#000;margin-bottom: 5px;">
                <thead>
                    <tr>
                        <th style="color:#000;">Time</th>
                        <th style="color:#000;">BP</th>
                        <th style="color:#000;">Palse</th>
                        <th style="color:#000;">Temp</th>
                        <th style="color:#000;">Art Press</th>
                        <th style="color:#000;">Vein Press</th>
                        <th style="color:#000;">Blood Flow</th>
                        <th style="color:#000;">Dial Flow</th>
                        <th style="color:#000;">Dial Temp</th>
                        <th style="color:#000;">Dial Cond</th>
                        <th style="color:#000;">Medicine</th>
                        <th style="color:#000;">
                            <button type="button" @click="addToCart">
                                <i class="fa fa-plus"></i>
                            </button>
                        </th>
                    </tr>
                </thead>
                <tbody style="display:none;" v-bind:style="{display: carts.length > 0 ? '' : 'none'}">
                    <tr v-for="(cart, sl) in carts">
                        <td>
                            <input type="time" v-model="cart.time" class="form-control text-center" style="margin: 0;" />
                        </td>
                        <td>
                            <input type="text" v-model="cart.bp" class="form-control text-center" style="margin: 0;" />
                        </td>
                        <td>
                            <input type="text" v-model="cart.palse" class="form-control text-center" style="margin: 0;" />
                        </td>
                        <td>
                            <input type="text" v-model="cart.temperature" class="form-control text-center" style="margin: 0;" />
                        </td>
                        <td>
                            <input type="text" v-model="cart.art_press" class="form-control text-center" style="margin: 0;" />
                        </td>
                        <td>
                            <input type="text" v-model="cart.vein_press" class="form-control text-center" style="margin: 0;" />
                        </td>
                        <td>
                            <input type="text" v-model="cart.blood_flow" class="form-control text-center" style="margin: 0;" />
                        </td>
                        <td>
                            <input type="text" v-model="cart.dial_flow" class="form-control text-center" style="margin: 0;" />
                        </td>
                        <td>
                            <input type="text" v-model="cart.dial_temperature" class="form-control text-center" style="margin: 0;" />
                        </td>
                        <td>
                            <input type="text" v-model="cart.dial_condition" class="form-control text-center" style="margin: 0;" />
                        </td>
                        <td>
                            <input type="text" v-model="cart.medicine" class="form-control text-center" style="margin: 0;" />
                        </td>
                        <td>
                            <a href="" v-on:click.prevent="removeCart(sl)"><i class="fa fa-trash"></i></a>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="12"><button type="button" @click="saveFormData">Save Form</button></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<script src="<?php echo base_url(); ?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/vue-select.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>

<script>
    Vue.component('v-select', VueSelect.VueSelect);
    new Vue({
        el: '#dialysis',
        data() {
            return {
                dialysis: {
                    id: "",
                    invoice: "<?= $invoice ?>",
                    date: moment().format('YYYY-MM-DD'),
                    built_by: "",
                    supervised_by: "",
                    dialyzer_reuse: "",
                    normal_saline: "",
                    uf: "",
                    duration: "",
                    heparin: "",
                    initial_dose: "",
                    blood_transfusion: "",
                    dialysis_status: "Satisfactory"
                },
                customers: [],
                selectedCustomer: {
                    Customer_SlNo: '',
                    Customer_Code: '',
                    Customer_Name: 'Select Patient',
                    display_name: 'Select Patient',
                    Customer_Mobile: '',
                    Customer_Address: ''
                },

                carts: [{
                    time: "",
                    bp: "",
                    palse: "",
                    temperature: "",
                    art_press: "",
                    vein_press: "",
                    blood_flow: "",
                    dial_flow: "",
                    dial_temperature: "",
                    dial_condition: "",
                    medicine: ""
                }],
                cartInfo: {
                    time: "",
                    bp: "",
                    palse: "",
                    temperature: "",
                    art_press: "",
                    vein_press: "",
                    blood_flow: "",
                    dial_flow: "",
                    dial_temperature: "",
                    dial_condition: "",
                    medicine: ""
                },

                existdialysis: [],
                selectedExistDialysis: null,

                oldInvoice: "<?= $invoice; ?>",
                onProgress: false,
                userType: '<?= $this->session->userdata("accountType"); ?>'
            }
        },
        async created() {
            await this.getCustomers();
            await this.getExistDialysis();
        },
        methods: {
            async getCustomers() {
                await axios.post('/get_customers').then(res => {
                    this.customers = res.data;
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
            async customerOnChange() {
                if (this.selectedCustomer == null) {
                    this.selectedCustomer = {
                        Customer_SlNo: '',
                        Customer_Code: '',
                        Customer_Name: 'Select Patient',
                        display_name: 'Select Patient',
                        Customer_Mobile: '',
                        Customer_Address: ''
                    }
                    return;
                }
            },

            addToCart() {
                this.carts.push(this.cartInfo);
            },

            removeCart(sl) {
                if (!confirm("Are you sure?")) return;
                this.carts.splice(sl, 1);
            },

            saveFormData() {
                if (this.selectedCustomer == null) {
                    alert("Please select patient");
                    return;
                }
                this.dialysis.patient_id = this.selectedCustomer ? this.selectedCustomer.Customer_SlNo : "";
                let data = {
                    dialysis: this.dialysis,
                    carts: this.carts
                }
                let url = this.dialysis.id == "" ? "/add_dialysis" : "/update_dialysis"

                axios.post(url, data)
                    .then(res => {
                        if (res.data.success) {
                            alert(res.data.message);
                            this.clearData();
                        }
                    })
            },

            clearData() {
                this.dialysis = {
                    id: "",
                    invoice: this.oldInvoice,
                    date: moment().format('YYYY-MM-DD'),
                    built_by: "",
                    supervised_by: "",
                    dialyzer_reuse: "",
                    normal_saline: "",
                    uf: "",
                    duration: "",
                    heparin: "",
                    initial_dose: "",
                    blood_transfusion: "",
                    dialysis_status: "Satisfactory"
                };
                this.selectedCustomer = {
                    Customer_SlNo: '',
                    Customer_Code: '',
                    Customer_Name: 'Select Patient',
                    display_name: 'Select Patient',
                    Customer_Mobile: '',
                    Customer_Address: ''
                }
                this.carts = [{
                    time: "",
                    bp: "",
                    palse: "",
                    temperature: "",
                    art_press: "",
                    vein_press: "",
                    blood_flow: "",
                    dial_flow: "",
                    dial_temperature: "",
                    dial_condition: "",
                    medicine: ""
                }]
                this.selectedExistDialysis = null;
            },

            async getExistDialysis() {
                await axios.get("/get_dialysis").then(res => {
                    this.existdialysis = res.data;
                })
            },

            async checkExistDialysis() {
                if (this.selectedExistDialysis == null) {
                    this.clearData();
                    return;
                }
                await axios.post("/get_dialysis", {
                    dialysisId: this.selectedExistDialysis.id
                }).then(res => {
                    let dialysis = res.data[0];
                    Object.keys(this.dialysis).forEach(key => {
                        this.dialysis[key] = dialysis[key];
                    })

                    setTimeout(() => {
                        this.selectedCustomer = this.customers.find(customer => customer.Customer_SlNo == dialysis.patient_id);
                    }, 1500);

                    this.carts = [];
                    dialysis.details.forEach(item => {
                        let detail = {
                            time: item.time,
                            bp: item.bp,
                            palse: item.palse,
                            temperature: item.temperature,
                            art_press: item.art_press,
                            vein_press: item.vein_press,
                            blood_flow: item.blood_flow,
                            dial_flow: item.dial_flow,
                            dial_temperature: item.dial_temperature,
                            dial_condition: item.dial_condition,
                            medicine: item.medicine
                        }
                        this.carts.push(detail);
                    })
                })
            }
        }
    })
</script>