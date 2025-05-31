<style>
    #doctors .add-button {
        padding: 2.5px;
        width: 28px;
        background-color: #298db4;
        display: block;
        text-align: center;
        color: white;
    }

    #doctors .add-button:hover {
        background-color: #41add6;
        color: white;
    }

    #doctors input[type="file"] {
        display: none;
    }

    #doctors .custom-file-upload {
        border: 1px solid #ccc;
        display: inline-block;
        padding: 5px 12px;
        cursor: pointer;
        margin-top: 5px;
        background-color: #298db4;
        border: none;
        color: white;
    }

    #doctors .custom-file-upload:hover {
        background-color: #41add6;
    }

    #doctorImage {
        height: 100%;
    }
</style>
<div id="doctors">
    <form @submit.prevent="saveData">
        <div class="row" style="margin-top: 10px;margin-bottom:15px;border-bottom: 1px solid #ccc;padding-bottom:15px;">
            <div class="col-md-5">
                <div class="form-group clearfix">
                    <label class="control-label col-md-4">Doctor Id:</label>
                    <div class="col-md-7">
                        <input type="text" class="form-control" v-model="doctor.Doctor_Code" required readonly>
                    </div>
                </div>

                <div class="form-group clearfix">
                    <label class="control-label col-md-4">Doctor Name:</label>
                    <div class="col-md-7">
                        <input type="text" class="form-control" v-model="doctor.Doctor_Name" required>
                    </div>
                </div>

                <div class="form-group clearfix">
                    <label class="control-label col-md-4">Mobile:</label>
                    <div class="col-md-7">
                        <input type="text" class="form-control" v-model="doctor.Doctor_Mobile" required>
                    </div>
                </div>

                <div class="form-group clearfix">
                    <label class="control-label col-md-4">Specialization:</label>
                    <div class="col-md-7">
                        <input type="text" class="form-control" v-model="doctor.specialization">
                    </div>
                </div>

                <div class="form-group clearfix">
                    <label class="control-label col-md-4">Address:</label>
                    <div class="col-md-7">
                        <input type="text" class="form-control" v-model="doctor.Doctor_Address">
                    </div>
                </div>
            </div>

            <div class="col-md-5">


                <div class="form-group clearfix">
                    <label class="control-label col-md-4">Email:</label>
                    <div class="col-md-7">
                        <input type="text" class="form-control" v-model="doctor.Doctor_Email" />
                    </div>
                </div>

                <div class="form-group clearfix">
                    <label class="control-label col-md-4">Age:</label>
                    <div class="col-md-7">
                        <input type="number" min="0" step="any" class="form-control" v-model="doctor.age" />
                    </div>
                </div>

                <div class="form-group clearfix">
                    <label class="control-label col-md-4">Gender:</label>
                    <div class="col-md-7">
                        <select class="form-control" v-model="doctor.gender">
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="others">Others</option>
                        </select>
                    </div>
                </div>

                <div class="form-group clearfix">
                    <label class="control-label col-md-4">Commission (%):</label>
                    <div class="col-md-7">
                        <input type="number" min="0" step="any" class="form-control" v-model="doctor.commission" />
                    </div>
                </div>

                <div class="form-group clearfix">
                    <label for="" class="col-md-4"></label>
                    <div class="col-md-7" style="display: flex; align-items:center;justify-content:space-between;">
                        <label for="status" style="margin: 0;">
                            <input type="checkbox" id="status" :true-value="'a'" :false-value="'p'" v-model="doctor.status" />
                            <span>Is Active</span>
                        </label>
                        <input type="submit" class="btn btn-success btn-sm" value="Save">
                    </div>
                </div>
            </div>
            <div class="col-md-2 text-center;">
                <div class="form-group clearfix">
                    <div style="width: 100px;height:100px;border: 1px solid #ccc;overflow:hidden;">
                        <img id="doctorImage" v-if="imageUrl == '' || imageUrl == null" src="/assets/no_image.gif">
                        <img id="doctorImage" v-if="imageUrl != '' && imageUrl != null" v-bind:src="imageUrl">
                    </div>
                    <div style="text-align:center;">
                        <label class="custom-file-upload">
                            <input type="file" @change="previewImage" />
                            Select Image
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="row">
        <div class="col-sm-12 form-inline">
            <div class="form-group">
                <label for="filter" class="sr-only">Filter</label>
                <input type="text" class="form-control" v-model="filter" placeholder="Filter">
            </div>
        </div>
        <div class="col-md-12">
            <div class="table-responsive">
                <datatable :columns="columns" :data="doctors" :filter-by="filter" style="margin-bottom: 5px;">
                    <template scope="{ row }">
                        <tr>
                            <td>{{ row.sl }}</td>
                            <td>{{ row.Doctor_Code }}</td>
                            <td>{{ row.Doctor_Name }}</td>
                            <td>{{ row.Doctor_Mobile }}</td>
                            <td>{{ row.specialization }}</td>
                            <td>{{ row.age }}</td>
                            <td>{{ row.gender }}</td>
                            <td>{{ row.commission }}</td>
                            <td>
                                <?php if ($this->session->userdata('accountType') != 'u') { ?>
                                    <button type="button" class="button edit" @click="editDoctor(row)">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                    <button type="button" class="button" @click="deleteDoctor(row.Doctor_SlNo)">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                <?php } ?>
                            </td>
                        </tr>
                    </template>
                </datatable>
                <datatable-pager v-model="page" type="abbreviated" :per-page="per_page" style="margin-bottom: 50px;"></datatable-pager>
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
        el: '#doctors',
        data() {
            return {
                doctor: {
                    Doctor_SlNo: 0,
                    Doctor_Code: '<?php echo $doctorCode; ?>',
                    Doctor_Name: '',
                    Doctor_Mobile: '',
                    Doctor_Email: '',
                    Doctor_Address: '',
                    specialization: '',
                    age: '',
                    gender: 'male',
                    commission: 0,
                    status: 'a'
                },
                doctors: [],
                imageUrl: '',
                selectedFile: null,

                columns: [{
                        label: 'Sl',
                        field: 'sl',
                        align: 'center',
                        filterable: false
                    },
                    {
                        label: 'Doctor Id',
                        field: 'Doctor_Code',
                        align: 'center',
                        filterable: false
                    },
                    {
                        label: 'Doctor Name',
                        field: 'Doctor_Name',
                        align: 'center'
                    },
                    {
                        label: 'Contact Number',
                        field: 'Doctor_Mobile',
                        align: 'center'
                    },
                    {
                        label: 'Specialization',
                        field: 'specialization',
                        align: 'center'
                    },
                    {
                        label: 'Age',
                        field: 'age',
                        align: 'center'
                    },
                    {
                        label: 'Gender',
                        field: 'gender',
                        align: 'center'
                    },
                    {
                        label: 'Commission',
                        field: 'commission',
                        align: 'center'
                    },
                    {
                        label: 'Action',
                        align: 'center',
                        filterable: false
                    }
                ],
                page: 1,
                per_page: 10,
                filter: ''
            }
        },
        created() {
            this.getDoctors();
        },
        methods: {
            getDoctors() {
                axios.get('/get_doctors').then(res => {
                    this.doctors = res.data.map((item, index) => {
                        item.sl = index + 1;
                        return item;
                    });
                })
            },
            previewImage(event) {
                const WIDTH = 200;
                const HEIGHT = 200;
                if (event.target.files[0]) {
                    let reader = new FileReader();
                    reader.readAsDataURL(event.target.files[0]);
                    reader.onload = (ev) => {
                        let img = new Image();
                        img.src = ev.target.result;
                        img.onload = async e => {
                            let canvas = document.createElement('canvas');
                            canvas.width = WIDTH;
                            canvas.height = HEIGHT;
                            const context = canvas.getContext("2d");
                            context.drawImage(img, 0, 0, canvas.width, canvas.height);
                            let new_img_url = context.canvas.toDataURL(event.target.files[0].type);
                            this.imageUrl = new_img_url;
                            const resizedImage = await new Promise(rs => canvas.toBlob(rs, 'image/jpeg', 1))
                            this.selectedFile = new File([resizedImage], event.target.files[0].name, {
                                type: resizedImage.type
                            });
                        }
                    }
                } else {
                    event.target.value = '';
                }
            },
            saveData() {
                let url = '/add_doctor';
                if (this.doctor.Doctor_SlNo != 0) {
                    url = '/update_doctor';
                }

                let fd = new FormData();
                fd.append('image', this.selectedFile);
                fd.append('data', JSON.stringify(this.doctor));

                axios.post(url, fd).then(res => {
                    let r = res.data;
                    alert(r.message);
                    if (r.success) {
                        this.resetForm();
                        this.doctor.Doctor_Code = r.doctorCode;
                        this.getDoctors();
                    }
                })
            },
            editDoctor(doctor) {
                let keys = Object.keys(this.doctor);
                keys.forEach(key => {
                    this.doctor[key] = doctor[key];
                })
                if (doctor.image_name == null || doctor.image_name == '') {
                    this.imageUrl = null;
                } else {
                    this.imageUrl = '/uploads/doctors/' + doctor.image_name;
                }
            },
            deleteDoctor(doctorId) {
                let deleteConfirm = confirm('Are you sure?');
                if (deleteConfirm == false) {
                    return;
                }
                axios.post('/delete_doctor', {
                    doctorId: doctorId
                }).then(res => {
                    let r = res.data;
                    alert(r.message);
                    if (r.success) {
                        this.getDoctors();
                    }
                })
            },
            resetForm() {
                this.doctor = {
                    Doctor_SlNo: 0,
                    Doctor_Code: '<?php echo $doctorCode; ?>',
                    Doctor_Name: '',
                    Doctor_Mobile: '',
                    Doctor_Email: '',
                    Doctor_Address: '',
                    specialization: '',
                    age: '',
                    gender: 'male',
                    commission: 0,
                    status: 'a'
                }
                this.imageUrl = '';
                this.selectedFile = null;
            }
        }
    })
</script>