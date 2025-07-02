const dialysisInvoice = Vue.component('dialysis-invoice', {
    template: `
        <div>
            <div class="row">
                <div class="col-xs-12" style="text-align: right; border-bottom: 1px solid gray; margin-bottom: 10px;">
                    <a href="" v-on:click.prevent="print"><i class="fa fa-print"></i> Print</a>
                </div>
            </div>
            
            <div id="invoiceContent">
                <div class="row" style="margin-bottom: 12px;margin-left: 0; margin-right: 0;display: flex ; align-items: center;">
                    <div class="col-xs-7 invoice-header">
                        DIALYSIS RECORD SHEET
                    </div>
                    <div class="col-xs-5" style="padding-right:0;">
                        <table _a584de>
                            <tr>
                                <td style="text-align:left;padding-left: 6px;width:120px;"><strong>Patient ID:</strong></td>
                                <td style="text-align:left;padding-left: 6px;">{{report.Customer_Code}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row" style="margin-bottom: 15px;margin-left: 0; margin-right: 0;display:flex;">
                    <div style="width:50%;">
                        <div style="width:100%; border-bottom: 1.5px dashed black; padding-left: 0;">
                            <strong>Patient: </strong>{{report.Customer_Name}}
                        </div>
                    </div>
                    <div style="width:23%;">
                        <div style="width:70%; border-bottom: 1.5px dashed black;margin-left: 25px;">
                            <strong>Age: </strong>{{report.age}}
                        </div>
                    </div>
                    <div style="width:25%;">
                        <div style="width:100%; border-bottom: 1.5px dashed black;">
                            <strong>Phone: </strong>{{report.Customer_Mobile}}
                        </div>
                    </div>
                </div>
                <div class="row">                    
                    <div class="col-xs-6">
                        <table _a584de>
                            <tr>
                                <td style="text-align:left;">Dialysis No</td>
                                <td colspan="2" style="text-align:left;">{{report.invoice}}</td>
                            </tr>
                            <tr>
                                <td style="text-align:left;">Date</td>
                                <td colspan="2" style="text-align:left;">{{dateFormat(report.date, 'DD/MM/YYYY')}}</td>
                            </tr>
                            <tr>
                                <td style="text-align:left;">Time:</td>
                                <td style="text-align:left;">Hour: {{dateFormat(cart[0]?.time, 'hh')}}</td>
                                <td style="text-align:left;">Minute: {{dateFormat(cart[0]?.time, 'mm A')}}</td>
                            </tr>
                            <tr>
                                <td style="text-align:left;"></td>
                                <td style="text-align:center;background:#dfdfdf;font-weight: 700;">Pre Dialysis</td>
                                <td style="text-align:center;background:#dfdfdf;font-weight: 700;">Post Dialysis</td>
                            </tr>
                            <tr>
                                <td style="text-align:left;">BP (Supine)</td>
                                <td style="text-align:left;" v-text="report.pre_bp"></td>
                                <td style="text-align:left;" v-text="report.post_bp"></td>
                            </tr>
                            <tr>
                                <td style="text-align:left;">Weight</td>
                                <td style="text-align:left;" v-text="report.pre_weight"></td>
                                <td style="text-align:left;" v-text="report.post_weight"></td>
                            </tr>
                            <tr>
                                <td style="text-align:left;">Weight Gain</td>
                                <td style="text-align:left;" v-text="report.pre_weight_gain"></td>
                                <td style="text-align:left;" v-text="report.pre_weight_gain"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-xs-6">
                        <table _a584de>
                            <tr>
                                <td style="text-align:left;" colspan="2">
                                    Dialyzer Built By: {{report.built_by}}
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align:left;" colspan="2">
                                    Supervised By: {{report.supervised_by}}
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align:left;" colspan="2">
                                    Type of Dialyzer Reuse: {{report.dialyzer_reuse}}
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align:left;" colspan="2">Normal Saline: {{report.normal_saline}}</td>
                            </tr>
                            <tr>
                                <td style="text-align:left;" colspan="2">UF: {{report.uf}}</td>
                            </tr>
                            <tr>
                                <td style="text-align:left;" colspan="2">Duration: {{report.duration}}</td>
                            </tr>
                            <tr>
                                <td style="text-align:left;">Heparin: {{report.heparin}}</td>
                                <td style="text-align:left;">Initial Dose: {{report.initial_dose}}</td>
                            </tr>
                            <tr>
                                <td style="text-align:left;" colspan="2">Blood Transfusion: {{report.blood_transfusion}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row" style="margin-top: 3px;">
                    <div class="col-xs-12">
                        <table _a584de>
                            <thead>
                                <tr>
                                    <td>Time</td>
                                    <td>BP</td>
                                    <td>Palse</td>
                                    <td>Temp</td>
                                    <td>Art Press</td>
                                    <td>Vein Press</td>
                                    <td>Blood Flow</td>
                                    <td>Dial Flow</td>
                                    <td>Dial Temp</td>
                                    <td>Dial Cond</td>
                                    <td>Medicine</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, sl) in cart">
                                    <td>{{ dateFormat(item.time, 'hh:mm A') }}</td>
                                    <td>{{ item.bp }}</td>
                                    <td>{{ item.palse }}</td>
                                    <td>{{ item.temperature }}</td>
                                    <td>{{ item.art_press }}</td>
                                    <td>{{ item.vein_press }}</td>
                                    <td>{{ item.blood_flow }}</td>
                                    <td>{{ item.dial_flow }}</td>
                                    <td>{{ item.dial_temperature }}</td>
                                    <td>{{ item.dial_condition }}</td>
                                    <td>{{ item.medicine }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row" style="margin-top: 15px;">
                    <div class="col-xs-5">
                        <table style="width: 100%;">
                            <tr>
                                <td style="padding-bottom: 18px;" colspan="2">Virus:</td>
                            </tr>
                            <tr>
                                <td colspan="2">HbsAg</td>
                            </tr>
                            <tr>
                                <td colspan="2">Anti HCV</td>
                            </tr>
                            <tr>
                                <td style="width:120px;">Dialysis Status</td>
                                <td style="border: 1px solid gray;text-align:center;" v-text="report.dialysis_status"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-xs-2"></div>
                    <div class="col-xs-5">
                        <table style="width: 100%;">
                            <tr>
                                <td style="padding-bottom: 18px;">Complications:</td>
                            </tr>
                            <tr>
                                <td>
                                    Hypotension / Shock / HTN / Fever <br> 
                                    Headache / Vertigo / Vomiting <br>
                                    Muscle Cramps / Restlessness <br>
                                    Chest pain / Shortness of Breath
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    `,
    props: ['dialysis_id'],
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
        this.getDialysis();
        this.getCurrentBranch();
    },
    methods: {
        dateFormat(val, format) {
            if (!val) return '';
            const formatsToTry = ['HH:mm', 'hh:mm A', 'YYYY-MM-DD HH:mm:ss', moment.ISO_8601];
            const parsed = moment(val, formatsToTry, true);

            return parsed.isValid()
                ? parsed.format(format)
                : 'Invalid date';
        },
        getDialysis() {
            axios.post('/get_dialysis', { dialysisId: this.dialysis_id }).then(res => {
                this.report = res.data[0];
                this.cart = res.data[0].details;
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
                .invoice-header{
                    border: 1px solid gray;
                    border-radius: 15px;
                    border-top-left-radius: 0;
                    border-bottom-left-radius: 0;
                    padding: 5px 20px;
                    padding-left: 8px;
                    font-size: 28px;
                    font-weight: 700;
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
                                        margin: 25px 18px !important;
                                    }    
                                }
                            </style>
                        </head>
                        <body>
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-xs-12 text-center" style="margin-bottom: 5px;border-bottom: 1px; solid gray;">
                                        <img src="/assets/images/header.jpg" alt="Logo" style="width: 100%; height: 120px; border: 1px solid #ccc; padding: 2px; border-radius: 5px; margin-bottom: 6px;" />
                                    </div>
                                    <div class="col-xs-12">
                                        ${invoiceContent}
                                    </div>
                                </div>
                            </div>
                            <div class="container-fluid" style="width: 100%;position:fixed;left: 0; bottom:0;">
                                <div class="col-xs-6">
                                    <span>DOCTOR'S NAME & SIGNATURE</span>
                                </div>
                                <div class="col-xs-6">
                                    <span>Others (If Any)........................................................</span>
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
                            </style>
                        </head>
                        <body>
                            <div class="row">
                                <div class="col-xs-12 text-center" style="margin-bottom: 5px;border-bottom: 1px; solid gray;">
                                    <img src="/assets/images/header.jpg" alt="Logo" style="width: 100%; height: 120px; border: 1px solid #ccc; padding: 2px; border-radius: 5px; margin-bottom: 6px;" />
                                </div>
                                <div class="col-xs-12">
                                    ${invoiceContent}
                                </div>
                            </div>
                            <div style="width: 100%;position:fixed;left: 0; bottom:0;">
                                <div class="col-xs-6">
                                    <span>DOCTOR'S NAME & SIGNATURE</span>
                                </div>
                                <div class="col-xs-6">
                                    <span>Others (If Any).........................................</span>
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