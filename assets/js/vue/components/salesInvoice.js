const salesInvoice = Vue.component('sales-invoice', {
    template: `
        <div>
            <div class="row">
                <div class="col-xs-12" style="text-align: right; border-bottom: 1px solid gray; margin-bottom: 10px;">
                    <a href="" v-on:click.prevent="print"><i class="fa fa-print"></i> Print</a>
                </div>
            </div>
            
            <div id="invoiceContent">
                <div class="row" style="border: 1px solid #979797; margin: 0; margin-bottom: 5px; border-radius: 5px;padding-top: 5px; padding-bottom: 5px;">
                    <div class="col-xs-7">
                        <strong>Patient Id:</strong> {{ sales.Customer_Code }}<br>
                        <strong>Name: </strong>{{ sales.Customer_Name }}<br>
                        <strong>Mobile: </strong>{{ sales.Customer_Mobile }}<br>
                        <strong>Address: </strong>{{ sales.Customer_Address }}<br>
                        <strong>Age: </strong>{{ sales.age }}<br>
                        <strong>Gender: </strong>{{ sales.gender }}<br>
                    </div>
                    <div class="col-xs-5 mobile-second-section">
                        <strong>Invoice:</strong> {{ sales.SaleMaster_InvoiceNo }}<br>
                        <strong>Added By:</strong> {{ sales.AddBy }}<br>
                        <strong>Date:</strong> {{ sales.SaleMaster_SaleDate }} {{ sales.AddTime | formatDateTime('h:mm a') }}<br>
                        <span v-if="sales.Employee_Name"> <strong>Employee:</strong> {{ sales.Employee_Name }} </span>
                        <p style="margin:0;" v-if="sales.Doctor_Name"> <strong>Doctor:</strong> {{ sales.Doctor_Name }}</p>
                        <span v-if="sales.Doctor_Name"> <strong>Specialized:</strong> {{ sales.specialization }} </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <table _a584de>
                            <thead>
                                <tr>
                                    <td>Test Name</td>
                                    <td align="right">Rate</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(product, sl) in cart">
                                    <td style="text-align:left;padding-left: 7px;">{{ product.Product_Name }}</td>
                                    <td align="right">{{ product.SaleDetails_Rate }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-7 text-left">
                        <div v-if="sales.SaleMaster_bankPaid > 0" style="margin:0; margin-top:10px; border-bottom: 1px solid gray; padding-bottom: 5px;">
                            <table _a584de>
                                <tr>
                                    <td style="font-weight:700;">Sl</td>
                                    <td style="font-weight:700;">Bank Name</td>
                                    <td style="font-weight:700;">Amount</td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>{{sales.bank_name}} - {{sales.account_number}} - {{sales.account_name}}</td>
                                    <td>{{sales.SaleMaster_bankPaid}}</td>
                                </tr>
                            </table>
                        </div>
                        <strong>In Word: </strong> {{ convertNumberToWords(sales.SaleMaster_TotalSaleAmount) }} <br><br>
                        <strong>Note: </strong> {{ sales.SaleMaster_Description }}
                    </div>
                    <div class="col-xs-5">
                        <table _t92sadbc2>
                            <tr>
                                <td><strong>Sub Total:</strong></td>
                                <td style="text-align:right">{{ sales.SaleMaster_SubTotalAmount }}</td>
                            </tr>
                            <tr>
                                <td><strong>Discount (-):</strong></td>
                                <td style="text-align:right">{{ sales.SaleMaster_TotalDiscountAmount }}</td>
                            </tr>
                            <tr><td colspan="2" style="border-bottom: 1px solid #ccc"></td></tr>
                            <tr>
                                <td><strong>Total:</strong></td>
                                <td style="text-align:right">{{ sales.SaleMaster_TotalSaleAmount }}</td>
                            </tr>
                            <tr>
                                <td><strong>Paid:</strong></td>
                                <td style="text-align:right">{{ sales.SaleMaster_PaidAmount }}</td>
                            </tr>
                            <tr v-show="sales.returnAmount > 0">
                                <td><strong>Return Amount:</strong></td>
                                <td style="text-align:right">{{ sales.returnAmount }}</td>
                            </tr>
                            <tr><td colspan="2" style="border-bottom: 1px solid #ccc"></td></tr>
                            <tr>
                                <td><strong>Due:</strong></td>
                                <td style="text-align:right">{{ sales.SaleMaster_DueAmount }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row" style="margin-top: 80px;">
                    <div class="col-xs-6">
                        <span style="text-decoration:overline;">Received by</span>
                    </div>
                    <div class="col-xs-6 text-right">
                        <span style="text-decoration:overline;">Authorized by</span>
                    </div>
                </div>
            </div>
        </div>
    `,
    props: ['sales_id'],
    data() {
        return {
            sales: {
                SaleMaster_InvoiceNo: null,
                SalseCustomer_IDNo: null,
                SaleMaster_SaleDate: null,
                Customer_Name: null,
                Customer_Address: null,
                Customer_Mobile: null,
                SaleMaster_TotalSaleAmount: null,
                SaleMaster_TotalDiscountAmount: null,
                SaleMaster_TaxAmount: null,
                SaleMaster_Freight: null,
                SaleMaster_SubTotalAmount: null,
                SaleMaster_PaidAmount: null,
                SaleMaster_DueAmount: null,
                SaleMaster_Previous_Due: null,
                SaleMaster_Description: null,
                AddBy: null
            },
            cart: [],
            style: null,
            companyProfile: null,
            currentBranch: null
        }
    },
    filters: {
        formatDateTime(dt, format) {
            return dt == '' || dt == null ? '' : moment(dt).format(format);
        }
    },
    created() {
        this.setStyle();
        this.getSales();
        this.getCurrentBranch();
    },
    methods: {
        getSales() {
            axios.post('/get_sales', { salesId: this.sales_id }).then(res => {
                this.sales = res.data.sales[0];
                this.cart = res.data.saleDetails;
            })
        },
        getCurrentBranch() {
            axios.get('/get_current_branch').then(res => {
                this.currentBranch = res.data;
            })
        },
        setStyle() {
            this.style = document.createElement('style');
            this.style.innerHTML = `
                div[_h098asdh]{
                    /*background-color:#e0e0e0;*/
                    font-weight: bold;
                    font-size:15px;
                    margin-bottom:15px;
                    padding: 5px;
                    border-top: 1px dotted #454545;
                    border-bottom: 1px dotted #454545;
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
                .mobile-second-section{
                    text-align: right;
                }
            `;
            document.head.appendChild(this.style);
        },
        convertNumberToWords(amountToWord) {
            var words = new Array();
            words[0] = '';
            words[1] = 'One';
            words[2] = 'Two';
            words[3] = 'Three';
            words[4] = 'Four';
            words[5] = 'Five';
            words[6] = 'Six';
            words[7] = 'Seven';
            words[8] = 'Eight';
            words[9] = 'Nine';
            words[10] = 'Ten';
            words[11] = 'Eleven';
            words[12] = 'Twelve';
            words[13] = 'Thirteen';
            words[14] = 'Fourteen';
            words[15] = 'Fifteen';
            words[16] = 'Sixteen';
            words[17] = 'Seventeen';
            words[18] = 'Eighteen';
            words[19] = 'Nineteen';
            words[20] = 'Twenty';
            words[30] = 'Thirty';
            words[40] = 'Forty';
            words[50] = 'Fifty';
            words[60] = 'Sixty';
            words[70] = 'Seventy';
            words[80] = 'Eighty';
            words[90] = 'Ninety';
            amount = amountToWord == null ? '0.00' : amountToWord.toString();
            var atemp = amount.split(".");
            var number = atemp[0].split(",").join("");
            var n_length = number.length;
            var words_string = "";
            if (n_length <= 9) {
                var n_array = new Array(0, 0, 0, 0, 0, 0, 0, 0, 0);
                var received_n_array = new Array();
                for (var i = 0; i < n_length; i++) {
                    received_n_array[i] = number.substr(i, 1);
                }
                for (var i = 9 - n_length, j = 0; i < 9; i++, j++) {
                    n_array[i] = received_n_array[j];
                }
                for (var i = 0, j = 1; i < 9; i++, j++) {
                    if (i == 0 || i == 2 || i == 4 || i == 7) {
                        if (n_array[i] == 1) {
                            n_array[j] = 10 + parseInt(n_array[j]);
                            n_array[i] = 0;
                        }
                    }
                }
                value = "";
                for (var i = 0; i < 9; i++) {
                    if (i == 0 || i == 2 || i == 4 || i == 7) {
                        value = n_array[i] * 10;
                    } else {
                        value = n_array[i];
                    }
                    if (value != 0) {
                        words_string += words[value] + " ";
                    }
                    if ((i == 1 && value != 0) || (i == 0 && value != 0 && n_array[i + 1] == 0)) {
                        words_string += "Crores ";
                    }
                    if ((i == 3 && value != 0) || (i == 2 && value != 0 && n_array[i + 1] == 0)) {
                        words_string += "Lakhs ";
                    }
                    if ((i == 5 && value != 0) || (i == 4 && value != 0 && n_array[i + 1] == 0)) {
                        words_string += "Thousand ";
                    }
                    if (i == 6 && value != 0 && (n_array[i + 1] != 0 && n_array[i + 2] != 0)) {
                        words_string += "Hundred and ";
                    } else if (i == 6 && value != 0) {
                        words_string += "Hundred ";
                    }
                }
                words_string = words_string.split("  ").join(" ");
            }
            return words_string + ' only';
        },
        async print() {
            let invoiceContent = document.querySelector('#invoiceContent').innerHTML;
            let printWindow = window.open('', 'PRINT', `width=${screen.width}, height=${screen.height}, left=0, top=0`);
            if (this.currentBranch.print_type == '1') {
                printWindow.document.write(`
                    <html>
                        <head>
                            <title>Invoice</title>
                            <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
                            <style>
                                body, table{
                                    font-size:15px;
                                }
                                .invoice-header{
                                    border: 1px solid gray; 
                                    border-radius: 15px; 
                                    padding: 5px 20px;
                                }                                
                                @media print{
                                    @page{
                                        padding: 5px !important;
                                        margin: 16px 16px !important;
                                    }                                  
                                    .invoice-copy {
                                        page-break-after: always;
                                    }    
                                }
                            </style>
                        </head>
                        <body>
                            <div class="container-fluid invoice-copy">
                                <div class="row">
                                    <div class="col-xs-12 text-center" style="margin-bottom: 5px;border-bottom: 1px; solid gray;">
                                        <img src="/assets/images/header.jpg" alt="Logo" style="width: 100%; height: 120px; border: 1px solid #ccc; padding: 2px; border-radius: 5px; margin-bottom: 6px;" />
                                    </div>
                                    <div class="col-xs-12">
                                        <div class="text-center" style="margin-bottom: 10px;">
                                            <span class="invoice-header">Patient Copy</span>
                                        </div>
                                        ${invoiceContent}
                                    </div>
                                </div>
                            </div>
                            <div class="container-fluid invoice-copy">
                                <div class="row">
                                    <div class="col-xs-12 text-center" style="margin-bottom: 5px;border-bottom: 1px; solid gray;">
                                        <img src="/assets/images/header.jpg" alt="Logo" style="width: 100%; height: 120px; border: 1px solid #ccc; padding: 2px; border-radius: 5px; margin-bottom: 6px;" />
                                    </div>
                                    <div class="col-xs-12">
                                        <div class="text-center" style="margin-bottom: 10px;">
                                            <span class="invoice-header">Reception Copy</span>
                                        </div>
                                        ${invoiceContent}
                                    </div>
                                </div>
                            </div>
                            <div class="container-fluid invoice-copy">
                                <div class="row">
                                    <div class="col-xs-12 text-center" style="margin-bottom: 5px;border-bottom: 1px; solid gray;">
                                        <img src="/assets/images/header.jpg" alt="Logo" style="width: 100%; height: 120px; border: 1px solid #ccc; padding: 2px; border-radius: 5px; margin-bottom: 6px;" />
                                    </div>
                                    <div class="col-xs-12">
                                        <div class="text-center" style="margin-bottom: 10px;">
                                            <span class="invoice-header">Lab Copy</span>
                                        </div>
                                        ${invoiceContent}
                                    </div>
                                </div>
                            </div>
                        </body>
                    </html>
                `);
            }else{
                printWindow.document.write(`
                    <!DOCTYPE html>
                    <html lang="en">
                        <head>
                            <meta charset="UTF-8">
                            <meta name="viewport" content="width=device-width, initial-scale=1.0">
                            <meta http-equiv="X-UA-Compatible" content="ie=edge">
                            <title>Invoice</title>
                            <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
                            <style>
                                html, body{
                                    width:500px!important;
                                }
                                body, table{
                                    font-size: 13px;
                                }
                                .invoice-header{
                                    border: 1px solid gray; 
                                    border-radius: 15px; 
                                    padding: 5px 20px;
                                }                                
                                @media print{                                
                                    .invoice-copy {
                                        page-break-after: always;
                                    }    
                                }
                            </style>
                        </head>
                        <body>
                            <div class="row invoice-copy">
                                <div class="col-xs-12 text-center" style="margin-bottom: 5px;border-bottom: 1px; solid gray;">
                                    <img src="/assets/images/header.jpg" alt="Logo" style="width: 100%; height: 120px; border: 1px solid #ccc; padding: 2px; border-radius: 5px; margin-bottom: 6px;" />
                                </div>
                                <div class="col-xs-12">
                                    <div class="text-center" style="margin-bottom: 10px;">
                                        <span class="invoice-header">Patient Copy</span>
                                    </div>
                                    ${invoiceContent}
                                </div>
                            </div>
                            <div class="row invoice-copy">
                                <div class="col-xs-12 text-center" style="margin-bottom: 5px;border-bottom: 1px; solid gray;">
                                    <img src="/assets/images/header.jpg" alt="Logo" style="width: 100%; height: 120px; border: 1px solid #ccc; padding: 2px; border-radius: 5px; margin-bottom: 6px;" />
                                </div>
                                <div class="col-xs-12">
                                    <div class="text-center" style="margin-bottom: 10px;">
                                        <span class="invoice-header">Reception Copy</span>
                                    </div>
                                    ${invoiceContent}
                                </div>
                            </div>
                            <div class="row invoice-copy">
                                <div class="col-xs-12 text-center" style="margin-bottom: 5px;border-bottom: 1px; solid gray;">
                                    <img src="/assets/images/header.jpg" alt="Logo" style="width: 100%; height: 120px; border: 1px solid #ccc; padding: 2px; border-radius: 5px; margin-bottom: 6px;" />
                                </div>
                                <div class="col-xs-12">
                                    <div class="text-center" style="margin-bottom: 10px;">
                                        <span class="invoice-header">Lab Copy</span>
                                    </div>
                                    ${invoiceContent}
                                </div>
                            </div>
                        </body>
                    </html>
				`);
            }
            
            let invoiceStyle = printWindow.document.createElement('style');
            invoiceStyle.innerHTML = this.style.innerHTML;
            printWindow.document.head.appendChild(invoiceStyle);
            printWindow.moveTo(0, 0);

            printWindow.focus();
            await new Promise(resolve => setTimeout(resolve, 1000));
            printWindow.print();
            printWindow.close();
        }
    }
})