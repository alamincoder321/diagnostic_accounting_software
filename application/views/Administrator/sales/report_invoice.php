<div id="chalan">
    <div class="row" style="display:none;" v-bind:style="{display: cart.length > 0 ? '' : 'none'}">
        <div class="col-md-8 col-md-offset-2">
            <div class="row" style="margin-bottom: 8px; border-bottom: 1px solid #ccc;">
                <div class="col-xs-12 text-right">
                    <a href="" v-on:click.prevent="print"><i class="fa fa-print"></i> Print</a>
                </div>
            </div>

            <div id="invoiceContent">                
                <div class="row">
                    <div class="col-xs-8">
                        <strong>Patient Id:</strong> {{ report.Customer_Code }}<br>
                        <strong>Name:</strong> {{ report.Customer_Name }}<br>
                        <strong>Address:</strong> {{ report.Customer_Address }}<br>
                        <strong>Mobile:</strong> {{ report.Customer_Mobile }}
                    </div>
                    <div class="col-xs-4 text-right">
                        <strong>Invoice No.:</strong> {{ report.SaleMaster_InvoiceNo }}<br>
                        <strong>Report by:</strong> {{ report.AddBy }}<br>
                        <strong>Report Date:</strong> {{ formatDateTime(report.date, 'DD-MM-YYYY') }} {{ formatDateTime(report.AddTime, 'h:mm a') }}
                        <strong>Delivery Date:</strong> {{ formatDateTime(report.delivery_date, 'DD-MM-YYYY') }} {{ formatDateTime(report.UpdateTime, 'h:mm a') }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 text-center">
                        <div _h098asdh>
                            {{ report.Product_Name }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <table _a584de>
                            <thead>
                                <tr>
                                    <td>Test Name</td>
                                    <td>Result</td>
                                    <td>Unit</td>
                                    <td>Normal Range</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, sl) in cart">
                                    <td style="text-align: left;">{{ item.name }}</td>
                                    <td style="font-weight: 700;">{{ item.result }}</td>
                                    <td>{{ item.Unit_Name }}</td>
                                    <td>{{ item.normal_range }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url(); ?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/vue-select.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>

<script>
    new Vue({
        el: '#chalan',
        data() {
            return {
                report: {
                    id: parseInt('<?php echo $id; ?>')
                },
                cart: [],
                style: null,
                companyProfile: null,
                currentBranch: null
            }
        },
        created() {
            this.setStyle();
            this.getReports();
            this.getCompanyProfile();
            this.getCurrentBranch();
        },
        methods: {
            getReports() {
                axios.post('/get_report_list', {
                    reportId: this.report.id
                }).then(res => {
                    this.report = res.data[0];
                    this.cart = res.data[0].details;
                })
            },
            getCompanyProfile() {
                axios.get('/get_company_profile').then(res => {
                    this.companyProfile = res.data;
                })
            },
            getCurrentBranch() {
                axios.get('/get_current_branch').then(res => {
                    this.currentBranch = res.data;
                })
            },
            formatDateTime(datetime, format) {
                return moment(datetime).format(format);
            },
            setStyle() {
                this.style = document.createElement('style');
                this.style.innerHTML = `
                div[_h098asdh]{
                    background-color:#e0e0e0;
                    font-weight: bold;
                    font-size:15px;
                    margin-top:5px;
                    margin-bottom: 7px;
                    padding: 5px;
                }
                div[_d9283dsc]{
                    padding-bottom:25px;
                    border-bottom: 1px solid #ccc;
                    margin-bottom: 15px;
                }
                table[_a584de]{
                    width: 100%;
                    text-align:center;
                }
                table[_a584de] thead{
                    font-weight:bold;
                }
                table[_a584de] td{
                    padding: 3px;
                    border: 1px solid #ccc;
                }
                table[_t92sadbc2]{
                    width: 100%;
                }
                table[_t92sadbc2] td{
                    padding: 2px;
                }
            `;
                document.head.appendChild(this.style);
            },
            async print() {
                let reportContent = `
                    <style>
                        @media print{
                                @page{
                                    padding: 5px !important;
                                    margin: 16px 16px !important;
                                }  
                            }
                        }
                    </style>
					<div class="container">
						<div class="row">
							<div class="col-xs-12">
								${document.querySelector('#invoiceContent').innerHTML}
							</div>
						</div>
                        <div class="row" style="margin-bottom:5px;padding-bottom:6px;margin-top:75px;">
                            <div class="col-xs-6">
                                <strong> 
                                    ${this.report.left_name}<br> 
                                    ${this.report.left_degree}<br> 
                                    ${this.report.left_department} 
                                <strong>
                            </div>
                            <div class="col-xs-6 text-right">
                                <strong> 
                                    ${this.report.right_name}<br> 
                                    ${this.report.right_degree}<br> 
                                    ${this.report.right_department} 
                                <strong>
                            </div>
                        </div>
					</div>
                    
				`;

                var reportWindow = window.open('', 'PRINT', `height=${screen.height}, width=${screen.width}`);
                reportWindow.document.write(`
					<?php $this->load->view('Administrator/reports/reportHeader.php'); ?>
				`);

                reportWindow.document.body.innerHTML += reportContent;

                if (this.searchType == '' || this.searchType == 'user') {
                    let rows = reportWindow.document.querySelectorAll('.record-table tr');
                    rows.forEach(row => {
                        row.lastChild.remove();
                    })
                }

                let invoiceStyle = reportWindow.document.createElement('style');
                invoiceStyle.innerHTML = this.style.innerHTML;
                reportWindow.document.head.appendChild(invoiceStyle);

                reportWindow.focus();
                await new Promise(resolve => setTimeout(resolve, 1000));
                reportWindow.print();
                reportWindow.close();
            }
        }
    })
</script>