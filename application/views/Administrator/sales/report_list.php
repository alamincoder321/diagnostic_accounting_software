<style>
    .v-select {
        margin-bottom: 5px;
        float: right;
        min-width: 200px;
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

    #priceList label {
        font-size: 13px;
        margin-top: 3px;
    }

    #priceList select {
        border-radius: 3px;
        padding: 0px;
        font-size: 13px;
    }

    #priceList .form-group {
        margin-right: 10px;
    }
</style>
<div id="priceList">
    <div class="row" style="border-bottom: 1px solid #ccc;padding: 5px 0;">
        <div class="col-md-12">
            <form class="form-inline" @submit.prevent="getReportList">
                <div class="form-group">
                    <label>Search Type</label>
                    <select class="form-control" v-model="searchType">
                        <option value="">All</option>
                        <option value="product">By Test</option>
                    </select>
                </div>

                <div class="form-group" style="display:none;" v-bind:style="{display: searchType == 'product' ? '' : 'none'}">
                    <label>Test</label>
                    <v-select v-bind:options="products" v-model="selectedProduct" label="Product_Name"></v-select>
                </div>

                <div class="form-group">
                    <input type="date" class="form-control" v-model="dateFrom">
                </div>

                <div class="form-group">
                    <input type="date" class="form-control" v-model="dateTo">
                </div>

                <div class="form-group" style="margin-top: -5px;">
                    <input type="submit" value="Search">
                </div>
            </form>
        </div>
    </div>

    <div class="row" style="display:none;margin-top: 15px;" v-bind:style="{display: reports.length > 0 ? '' : 'none'}">
        <div class="col-md-12 text-right" style="margin-bottom: 5px;">
            <a href="" @click.prevent="print"><i class="fa fa-print"></i> Print</a>
        </div>
        <div class="col-md-12">
            <div class="table-responsive" id="reportContent">
                <table class="table table-bordered table-condensed" id="priceListTable">
                    <thead>
                        <tr>
                            <th>Sl</th>
                            <th>Invoice</th>
                            <th>Date</th>
                            <th>Delivery Date</th>
                            <th>Bill Invoice</th>
                            <th>Patient Name</th>
                            <th>Test Name</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, sl) in reports">
                            <td>{{ sl + 1 }}</td>
                            <td>{{ item.invoice }}</td>
                            <td>{{ item.date | dateFormat('DD-MM-YYYY') }}</td>
                            <td>{{ item.delivery_date | dateFormat('DD-MM-YYYY') }}</td>
                            <td>{{ item.SaleMaster_InvoiceNo }}</td>
                            <td>{{ item.Customer_Name }}</td>
                            <td>{{ item.Product_Name }}</td>
                            <td>
                                <span v-if="item.is_delivery == 'yes'" class="badge badge-success">Delivered</span>
                                <span v-if="item.is_delivery == 'no'" class="badge badge-danger">Undelivered</span>
                            </td>
                            <td>
                                <span style="cursor: pointer;" v-if="item.is_delivery == 'no'" @click="deliveryReport(item)" class="badge badge-warning">Delivery</span>
                                <i @click="reportEdit(item.id)" class="text-info fa fa-edit" style="cursor: pointer;font-size: 14.5px;margin-right:5px;"></i>
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

<script src="<?php echo base_url(); ?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/vue-select.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>

<script>
    Vue.component('v-select', VueSelect.VueSelect);
    new Vue({
        el: '#priceList',
        data() {
            return {
                searchType: '',
                dateFrom: moment().format('YYYY-MM-DD'),
                dateTo: moment().format('YYYY-MM-DD'),
                reports: [],
                products: [],
                selectedProduct: null,
            }
        },
        filters: {
            dateFormat(dt, format) {
                return dt == null || dt == '' ? '' : moment(dt).format(format);
            }
        },
        created() {
            this.getProducts();
            this.getReportList();
        },
        methods: {
            openInvoice(id) {
                window.open(`/report_invoice_print/${id}`, '_blank');
            },
            reportEdit(id) {
                window.open(`/report_generate/${id}`, '_blank');
            },
            getProducts() {
                axios.get('/get_products').then(res => {
                    this.products = res.data;
                })
            },
            getReportList() {
                let data = {
                    testId: this.selectedProduct ? this.selectedProduct.Product_SlNo : '',
                    dateFrom: this.dateFrom,
                    dateTo: this.dateTo
                }
                axios.post('/get_report_list', data).then(res => {
                    this.reports = res.data;
                })
            },
            deleteData(reportId) {
                if (confirm('Are you sure you want to delete this report?')) {
                    axios.post('/delete_report_generate', {
                        reportId: reportId
                    }).then(res => {
                        alert(res.data.message);
                        if (res.data.success) {
                            this.getReportList();
                        }
                    });
                }
            },

            deliveryReport(item) {
                if (confirm('Are you sure you want to mark this report as delivered?')) {
                    axios.post('/report_delivery', {
                        reportId: item.id
                    }).then(res => {
                        alert(res.data.message);
                        if (res.data.success) {
                            this.getReportList();
                        }
                    });
                }
            },

            async print() {
                let reportContent = `
					<div class="container">
                        <div class="row">
                            <div class="col-xs-12">
                                <h3 style="text-align:center">Report List</h3>
                            </div>
                        </div>
						<div class="row">
							<div class="col-xs-12">
								${document.querySelector('#reportContent').innerHTML}
							</div>
						</div>
					</div>
				`;

                var reportWindow = window.open('', 'PRINT', `height=${screen.height}, width=${screen.width}, left=0, top=0`);
                reportWindow.document.write(`
					<?php $this->load->view('Administrator/reports/reportHeader.php'); ?>
				`);

                reportWindow.document.body.innerHTML += reportContent;

                reportWindow.focus();
                await new Promise(resolve => setTimeout(resolve, 1000));
                reportWindow.print();
                reportWindow.close();
            }
        }
    })
</script>