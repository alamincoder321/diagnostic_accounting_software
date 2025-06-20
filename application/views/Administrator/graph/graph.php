<style>
    .widgets {
        width: 100%;
        min-height: 100px;
        padding: 8px;
        box-shadow: 0px 1px 2px #454545;
        border-radius: 3px;
        text-align: center;
    }
    .widgets .widget-icon {
        width: 40px;
        height: 40px;
        padding-top: 8px;
        border-radius: 50%;
        color: white;
    }
    .widgets .widget-content {
        flex-grow: 2;
        font-weight: bold;
    }
    .widgets .widget-content .widget-text {
        font-size: 13px;
        color: #6f6f6f;
    }
    .widgets .widget-content .widget-value {
        font-size: 16px;
    }

    .custom-table-bordered,
    .custom-table-bordered>tbody>tr>td, 
    .custom-table-bordered>tbody>tr>th, 
    .custom-table-bordered>tfoot>tr>td, 
    .custom-table-bordered>tfoot>tr>th, 
    .custom-table-bordered>thead>tr>td, 
    .custom-table-bordered>thead>tr>th{
        border: 1px solid #224079;
    }
</style>
<div id="graph">
    <div class="row" v-if="showData" style="display:none;" v-bind:style="{ display: showData ? '' : 'none' }">
        <div class="col-md-12">
            <marquee scrollamount="3" onmouseover="this.stop();" onmouseout="this.start();" direction="left" height="30" bgcolor="#224079" style="color:white;padding-top:5px;margin-bottom: 15px;">{{ salesText }}</marquee>
        </div>
    </div>
    <div class="row" v-if="showData" style="display:none;" v-bind:style="{ display: showData ? '' : 'none' }">
        <div class="col-md-2  col-xs-6">
            <div class="widgets" style="border-top: 5px solid #1c8dff;">
                <div class="widget-icon" style="background-color: #1c8dff;text-align:center;">
                    <i class="fa fa-shopping-cart fa-2x"></i>
                </div>
                
                <div class="widget-content">
                    <div class="widget-text">Today's Bill</div>
                    <div class="widget-value"><?php echo $this->session->userdata('Currency_Name');?> {{ todaysSale | decimal }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-2  col-xs-6">
            <div class="widgets" style="border-top: 5px solid #666633;">
                <div class="widget-icon" style="background-color: #666633;text-align:center;">
                    <i class="fa fa-money fa-2x"></i>
                </div>
                
                <div class="widget-content">
                    <div class="widget-text">Collection</div>
                    <div class="widget-value"><?php echo $this->session->userdata('Currency_Name');?> {{ todaysCollection | decimal }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-2  col-xs-6">
            <div class="widgets" style="border-top: 5px solid #008241;">
                <div class="widget-icon" style="background-color: #008241;text-align:center;">
                    <i class="fa fa-shopping-cart fa-2x"></i>
                </div>
                
                <div class="widget-content">
                    <div class="widget-text">Monthly Bill</div>
                    <div class="widget-value"><?php echo $this->session->userdata('Currency_Name');?> {{ thisMonthSale | decimal }}</div>
                </div>
            </div>
        </div>
        
        <div class="col-md-2  col-xs-6">
            <div class="widgets" style="border-top: 5px solid #ae0000;">
                <div class="widget-icon" style="background-color: #ae0000;text-align:center;">
                    <i class="fa fa-dollar fa-2x"></i>
                </div>
                
                <div class="widget-content">
                    <div class="widget-text">Cash Balance</div>
                    <div class="widget-value"><?php echo $this->session->userdata('Currency_Name');?> {{ cashBalance | decimal }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-2  col-xs-6">
            <div class="widgets" style="border-top: 5px solid #663300;">
                <div class="widget-icon" style="background-color: #663300;text-align:center;">
                    <i class="fa fa-dollar fa-2x"></i>
                </div>
                
                <div class="widget-content">
                    <div class="widget-text">Bank Balance</div>
                    <div class="widget-value"><?php echo $this->session->userdata('Currency_Name');?> {{ bankBalance | decimal }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-2  col-xs-6">
            <div class="widgets" style="border-top: 5px solid #663300;">
                <div class="widget-icon" style="background-color: #663300;text-align:center;">
                    <i class="fa fa-line-chart fa-2x"></i>
                </div>
                
                <div class="widget-content">
                    <div class="widget-text">Monthly Profit</div>
                    <div class="widget-value"><?php echo $this->session->userdata('Currency_Name');?> {{ thisMonthProfit | decimal }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="margin-top:20px;margin-bottom: 25px;">
        <div class="col-md-12" v-if="salesGraph == 'monthly'">
            <h3 class="text-center">This Month's Bill</h3>
            <sales-chart
            type="ColumnChart"
            :data="salesData"
            :options="salesChartOptions"
            />
        </div>
        <div class="col-md-12" v-else>
            <h3 class="text-center">This Year's Bill</h3>
            <sales-chart
            type="ColumnChart"
            :data="yearlySalesData"
            :options="yearlySalesChartOptions"
            />
        </div>
        <div class="col-md-12 text-center">
            <div class="btn-group" role="group" aria-label="...">
                <button type="button" class="btn btn-primary" @click="salesGraph = 'monthly'">Monthly</button>
                <button type="button" class="btn btn-warning" @click="salesGraph = 'yearly'">Yearly</button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <h3 class="text-center">Top Test</h3>
            <top-product-chart
            type="PieChart"
            :data="topProducts"
            :options="topProductsOptions"
            />
        </div>
        <div class="col-md-4 col-md-offset-2">
            <table class="table custom-table-bordered">
                <thead>
                    <tr>
                        <td class="text-center" colspan="2" style="background-color: #224079;color: white;font-weight: 900;">Top Patient</td>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="customer in topCustomers">
                        <td width="75%">{{customer.customer_name}}</td>
                        <td width="25%">{{customer.amount}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="<?php echo base_url();?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url();?>assets/js/moment.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/components/vue-google-charts.browser.js"></script>

<script>
    let googleChart = VueGoogleCharts.GChart;
    new Vue({
        el: '#graph',
        components: {
            'sales-chart': googleChart,
            'top-product-chart': googleChart,
            'top-customer-chart': googleChart
        },
        filters: {
            decimal(value) {
                return value == null || value == '' ? '0.00' : parseFloat(value).toFixed(2);
            }
        },
        data () {
            return {
                salesData: [
                    ['Date', 'Bills']
                ],
                salesChartOptions: {
                    chart: {
                        title: 'Bills',
                        subtitle: "This month's sales data",
                    }
                },
                yearlySalesData: [
                    ['Month', 'Bills']
                ],
                yearlySalesChartOptions: {
                    chart: {
                        title: 'Bills',
                        subtitle: "This year's sales data",
                    }
                },
                topProducts: [
                    ['Test', 'Quantity']
                ],
                topProductsOptions: {
                    chart: {
                        title: 'Top Test',
                        subtitle: "Top tests sold this month",
                    }
                },
                topCustomers : [],
                salesText: '',
                todaysSale: 0,
                thisMonthSale: 0,
                todaysCollection: 0,
                cashBalance: 0,
                customerDue: 0,
                bankBalance: 0,
                thisMonthProfit: 0,
                showData: false,
                salesGraph: 'monthly'
            }
        },
        created(){
            this.getGraphData();
            setInterval(() => {
                this.getGraphData();
            }, 10000);
        },
        methods: {
            getGraphData(){
                axios.get('/get_graph_data').then(res => {
                    this.salesData = [
                        ['Date', 'Bills']
                    ]
                    res.data.monthly_record.forEach(d => {
                        this.salesData.push(d);
                    })

                    this.yearlySalesData = [
                        ['Month', 'Bills']
                    ]
                    res.data.yearly_record.forEach(d => {
                        this.yearlySalesData.push(d);
                    })

                    this.salesText = res.data.sales_text.map(sale => {
                        return sale.sale_text;
                    }).join(' | ');

                    this.todaysSale         = res.data.todays_sale;
                    this.thisMonthSale      = res.data.this_month_sale;
                    this.todaysCollection   = res.data.todays_collection;
                    this.cashBalance        = res.data.cash_balance;
                    this.customerDue        = res.data.customer_due;
                    this.bankBalance        = res.data.bank_balance;
                    this.thisMonthProfit    = res.data.this_month_profit;
                    this.topCustomers       = res.data.top_customers;

                    this.topProducts = [
                        ['Test', 'Quantity']
                    ]
                    res.data.top_products.forEach(p => {
                        this.topProducts.push([p.product_name, parseFloat(p.sold_quantity)]);
                    })

                    this.showData = true;
                })
            }
        }
    })
</script>
