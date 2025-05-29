<div id="unit">
	<form @submit.prevent="saveData">
		<div class="row" style="margin-top: 10px;margin-bottom:15px;border-bottom: 1px solid #ccc;padding-bottom:15px;">
			<div class="col-md-6 col-xs-12 col-md-offset-3">
				<div class="form-group clearfix">
					<label class="control-label col-md-4">Unit Name:</label>
					<div class="col-md-7">
						<input type="text" class="form-control" v-model="unit.Unit_Name" required>
					</div>
				</div>

				<div class="form-group clearfix">
					<div class="col-md-7 col-md-offset-4 text-right">
						<input type="submit" class="btn btn-success btn-sm" value="Save">
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
				<datatable :columns="columns" :data="units" :filter-by="filter" style="margin-bottom: 5px;">
					<template scope="{ row }">
						<tr>
							<td>{{ row.sl }}</td>
							<td>{{ row.Unit_Name }}</td>
							<td>
								<?php if ($this->session->userdata('accountType') != 'u') { ?>
									<button type="button" class="button edit" @click="editData(row)">
										<i class="fa fa-pencil"></i>
									</button>
									<button type="button" class="button" @click="deleteData(row.Unit_SlNo)">
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
<script src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>

<script>
	new Vue({
		el: '#unit',
		data() {
			return {
				unit: {
					Unit_SlNo: 0,
					Unit_Name: '',
				},
				units: [],

				columns: [{
						label: 'Sl',
						field: 'sl',
						align: 'center'
					},
					{
						label: 'Unit Name',
						field: 'Unit_Name',
						align: 'center'
					},
					{
						label: 'Action',
						align: 'center',
						filterable: false
					}
				],
				page: 1,
				per_page: 100,
				filter: ''
			}
		},
		created() {
			this.getData();
		},
		methods: {
			getData() {
				axios.get('/get_units').then(res => {
					this.units = res.data.map((item, index) => {
						item.sl = index + 1;
						return item;
					});
				})
			},

			saveData() {
				let url = '/insertunit';
				if (this.unit.Unit_SlNo != 0) {
					url = '/unitupdate';
				}

				axios.post(url, this.unit).then(res => {
					let r = res.data;
					alert(r.message);
					if (r.status) {
						this.resetForm();
						this.getData();
					}
				})
			},
			editData(unit) {
				let keys = Object.keys(this.unit);
				keys.forEach(key => {
					this.unit[key] = unit[key];
				})
			},
			deleteData(unitId) {
				let deleteConfirm = confirm('Are you sure?');
				if (deleteConfirm == false) {
					return;
				}
				axios.post('/unitdelete', {
					unitId: unitId
				}).then(res => {
					let r = res.data;
					alert(r.message);
					if (r.status) {
						this.getData();
					}
				})
			},
			resetForm() {
				this.unit = {
					Unit_SlNo: '',
					Unit_Name: ''
				}
			}
		}
	})
</script>