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
    tr td{
        vertical-align: middle !important;
    }
</style>
<div id="customerListReport">
    <div class="row">
        <div class="col-xs-12 col-md-12 col-lg-12" style="border-bottom:1px #ccc solid;">
            <form class="form-inline">
                <div class="form-group">
                    <label>Search Type</label>
                    <select class="form-control" style="width:150px;" v-model="searchType" v-on:change="onChangeSearchType" style="padding:0px;">
                        <option value="">All</option>
                    </select>
                </div>

                <div class="form-group">
                    <input type="button" class="btn btn-primary" value="Show Report" v-on:click="getDoctors" style="margin-top: -4px; border: 0px; padding: 3px 6px;">
                </div>
            </form>
        </div>
    </div>
    <div style="display:none;" v-bind:style="{display: doctors.length > 0 ? '' : 'none'}">
        <div class="row">
            <div class="col-md-12">
                <a href="" @click.prevent="printCustomerList"><i class="fa fa-print"></i> Print</a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive" id="printContent">
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <th>Sl</th>
                            <th>Image</th>
                            <th>Doctor Id</th>
                            <th>Doctor Name</th>
                            <th>Specialization</th>
                            <th>Contact No.</th>
                            <th>Age</th>
                            <th>Gender</th>
                            <th>Address</th>
                        </thead>
                        <tbody>
                            <tr v-for="(doctor, sl) in doctors">
                                <td>{{ sl + 1 }}</td>
                                <td>
                                    <a :href="doctor.imgSrc">
                                        <img :src="doctor.imgSrc" style="width: 35px;height:35px;border:1px solid gray;border-radius: 5px;" />
                                    </a>
                                </td>
                                <td>{{ doctor.Doctor_Code }}</td>
                                <td>{{ doctor.Doctor_Name }}</td>
                                <td>{{ doctor.specialization }}</td>
                                <td>{{ doctor.Doctor_Mobile }}</td>
                                <td>{{ doctor.age }}</td>
                                <td>{{ doctor.gender }}</td>
                                <td>{{ doctor.Doctor_Address }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div style="display:none;text-align:center;" v-bind:style="{display: doctors.length > 0 ? 'none' : ''}">
        No records found
    </div>
</div>

<script src="<?php echo base_url(); ?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/vue-select.min.js"></script>

<script>
    Vue.component('v-select', VueSelect.VueSelect);
    new Vue({
        el: '#customerListReport',
        data() {
            return {
                searchType: '',
                employees: [],
                selectedEmployee: null,
                areas: [],
                selectedArea: null,
                doctors: [],
            }
        },
        created() {
            this.getDoctors();
        },
        methods: {
            onChangeSearchType() {
                this.selectedEmployee = null;
                this.selectedArea = null;
                if (this.searchType == 'area') {
                    this.getAreas();
                } else if (this.searchType == 'employee') {
                    this.getEmployees();
                }
            },
            getEmployees() {
                axios.get('/get_employees').then(res => {
                    this.employees = res.data.map(item => {
                        item.display_name = `${item.Employee_Name} - ${item.Employee_ID}`;
                        return item;
                    });
                })
            },
            getAreas() {
                axios.get('/get_districts').then(res => {
                    this.areas = res.data;
                })
            },

            getDoctors() {
                let filter = {
                    areaId: this.selectedArea == null ? null : this.selectedArea.District_SlNo,
                    employeeId: this.selectedEmployee == null ? null : this.selectedEmployee.Employee_SlNo
                }
                axios.post('/get_doctors', filter).then(res => {
                    this.doctors = res.data.map((item, index) => {
                        item.imgSrc = item.image_name ? '/uploads/doctors/' + item.image_name : '/assets/no_image.gif';
                        return item;
                    });
                })
            },

            async printCustomerList() {
                let printContent = `
                    <div class="container">
                        <h4 style="text-align:center">Doctor List</h4 style="text-align:center">
						<div class="row">
							<div class="col-xs-12">
								${document.querySelector('#printContent').innerHTML}
							</div>
						</div>
                    </div>
                `;

                let printWindow = window.open('', '', `width=${screen.width}, height=${screen.height}`);
                printWindow.document.write(`
                    <?php $this->load->view('Administrator/reports/reportHeader.php'); ?>
                `);

                printWindow.document.body.innerHTML += printContent;
                printWindow.focus();
                await new Promise(r => setTimeout(r, 1000));
                printWindow.print();
                printWindow.close();
            }
        }
    })
</script>