const testInvoice = Vue.component('test-invoice', {
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
                        <strong>Patient Id:</strong> {{ report.Customer_Code }}<br>
                        <strong>Name: </strong>{{ report.Customer_Name }}<br>
                        <strong>Mobile: </strong>{{ report.Customer_Mobile }}<br>
                        <strong>Address: </strong>{{ report.Customer_Address }}<br>
                        <strong>Age: </strong>{{ report.age }}<br>
                        <strong>Gender: </strong>{{ report.gender }}<br>
                    </div>
                    <div class="col-xs-5 mobile-second-section">
                        <strong>Invoice No.:</strong> {{ report.SaleMaster_InvoiceNo }}<br>
                        <strong>Report by:</strong> {{ report.AddBy }}<br>
                        <strong>Report Date:</strong> {{ formatDateTime(report.date, 'DD-MM-YYYY') }} {{ formatDateTime(report.AddTime, 'h:mm a') }}
                        <strong>Delivery Date:</strong> {{ formatDateTime(report.delivery_date, 'DD-MM-YYYY') }} {{ formatDateTime(report.UpdateTime, 'h:mm a') }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 text-center">
                        <div _h098asdh>
                            {{ report.ProductCategory_Name }}
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
                <div class="row" style="margin-bottom:5px;padding-bottom:6px;margin-top:80px;">
                    <div class="col-xs-6">
                        <strong> 
                            {{report.left_name}}<br> 
                            {{report.left_degree}}<br> 
                            {{report.left_department}} 
                        <strong>
                    </div>
                    <div class="col-xs-6 text-right">
                        <strong> 
                            {{report.right_name}}<br> 
                            {{report.right_degree}}<br> 
                            {{report.right_department}} 
                        <strong>
                    </div>
                </div>
            </div>
        </div>
    `,
    props: ['report_id'],
    data() {
        return {
            report: {},
            cart: [],
            style: null,
            companyProfile: null,
            currentBranch: null
        }
    },
    created() {
        this.setStyle();
        this.getReports();
        this.getCurrentBranch();
    },
    methods: {
        getReports() {
            axios.post('/get_report_list', { reportId: this.report_id }).then(res => {
                this.report = res.data[0];
                this.cart = res.data[0].details;
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
                    background-color: #e0e0e0;
                    font-weight: bold;
                    font-size: 15px;
                    margin-bottom: 5px;
                    padding: 5px;
                    margin-top: 15px;
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
                                        ${invoiceContent}
                                    </div>
                                </div>
                            </div>
                        </body>
                    </html>
                `);
            } else {
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