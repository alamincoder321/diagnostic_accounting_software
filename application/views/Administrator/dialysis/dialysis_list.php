<style scoped>
    .v-select {
        margin-top: -2.5px;
        float: right;
        min-width: 180px;
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
</style>
<div id="productList">
    <div class="row">
        <div class="col-xs-12 col-md-12 col-lg-12" style="border-bottom:1px #ccc solid;">
            <form class="form-inline">
                <div class="form-group">
                    <label>Search Type</label>
                    <select class="form-control" style="width:150px;" v-model="searchType" v-on:change="onChangeSearchType" style="padding:0px;">
                        <option value="">All</option>
                        <option value="customer">By Patient</option>
                    </select>
                </div>
                <div class="form-group" style="display: none" v-bind:style="{display: searchType == 'customer' ? '' : 'none'}">
                    <label>Select Patient</label>
                    <v-select v-bind:options="customers" v-model="selectedCustomer" label="display_name"></v-select>
                </div>

                <div class="form-group">
                    <input type="button" class="btn btn-primary" value="Show Report" v-on:click="getDialysis" style="margin-top: -4px; border: 0px; padding: 3px 6px;">
                </div>
            </form>
        </div>
    </div>
    <div style="display:none;" v-bind:style="{display: dialysis.length > 0 ? '' : 'none'}">
        <div class="row">
            <div class="col-md-12">
                <a href="" v-on:click.prevent="print">
                    <i class="fa fa-print"></i> Print
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive" id="reportTable">
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Invoice</th>
                                <th>Date</th>
                                <th>Patient Name</th>
                                <th style="text-align: right;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, sl) in dialysis">
                                <td style="text-align:center;">{{ sl + 1 }}</td>
                                <td>{{ item.invoice }}</td>
                                <td>{{ item.date | dateFrom('DD/MM/YYYY') }}</td>
                                <td>{{ item.Customer_Name }}</td>
                                <td>
                                    <i @click="openInvoice(item.id)" class="text-info fa fa-file-text" style="cursor: pointer;font-size: 14.5px;margin-right:5px;"></i>
                                    <i @click="deleteData(item.id)" class="text-danger fa fa-trash" style="cursor: pointer;font-size: 16px;"></i>
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
<script src="<?php echo base_url(); ?>assets/js/vue/vue-select.min.js"></script>

<script>
    Vue.component('v-select', VueSelect.VueSelect);
    new Vue({
        el: '#productList',
        data() {
            return {
                searchType: '',
                customers: [],
                selectedCustomer: null,
                areas: [],
                dialysis: [],
            }
        },
        methods: {
            openInvoice(id) {
                window.open(`/dialysis_invoice_print/${id}`, '_blank');
            },
            onChangeSearchType() {
                this.selectedCustomer = null;
                if (this.searchType == 'customer') {
                    this.getCategory();
                }
            },
            getCategory() {
                axios.get('/get_customers').then(res => {
                    this.customers = res.data;
                })
            },
            getDialysis() {
                let filter = {
                    customerId: this.selectedCustomer == null ? null : this.selectedCustomer.Customer_SlNo
                }
                axios.post('/get_dialysis', filter).then(res => {
                    this.dialysis = res.data;
                })
            },
            deleteData(dialysisId) {
                if (confirm('Are you sure you want to delete this report?')) {
                    axios.post('/delete_dialysis', {
                        dialysisId: dialysisId
                    }).then(res => {
                        alert(res.data.message);
                        if (res.data.success) {
                            this.getDialysis();
                        }
                    });
                }
            },
            async print() {
                let reportContent = `
					<div class="container">
                        <div class="row">
                            <div class="col-xs-12">
                                <h4 style="text-align:center">Test List</h4 style="text-align:center">
                            </div>
                        </div>
					</div>
					<div class="container">
						<div class="row">
							<div class="col-xs-12">
								${document.querySelector('#reportTable').innerHTML}
							</div>
						</div>
					</div>
				`;

                var mywindow = window.open('', 'PRINT', `width=${screen.width}, height=${screen.height}`);
                mywindow.document.write(`
					<?php $this->load->view('Administrator/reports/reportHeader.php'); ?>
				`);

                mywindow.document.body.innerHTML += reportContent;

                mywindow.focus();
                await new Promise(resolve => setTimeout(resolve, 1000));
                mywindow.print();
                mywindow.close();
            }
        }
    })
</script>