<style scoped>
	.v-select {
		margin-bottom: 5px;
	}

	.v-select.open .dropdown-toggle {
		border-bottom: 1px solid #ccc;
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

	#subcategory label {
		font-size: 13px;
	}

	#subcategory select {
		border-radius: 3px;
	}

	#subcategory .add-button {
		padding: 2.5px;
		width: 28px;
		background-color: #298db4;
		display: block;
		text-align: center;
		color: white;
	}
</style>
<div id="subcategory">
	<form @submit.prevent="saveData">
		<div class="row" style="margin-top: 10px;margin-bottom:15px;border-bottom: 1px solid #ccc;padding-bottom:15px;">
			<div class="col-md-6 col-xs-12 col-md-offset-3">
				<div class="form-group clearfix">
					<label class="control-label col-md-4">Test Name:</label>
					<div class="col-md-7">
						<v-select v-bind:options="products" v-model="selectedTest" label="Product_Name"></v-select>
					</div>
					<div class="col-md-1" style="padding:0;margin-left: -15px;"><a href="/product" target="_blank" class="add-button"><i class="fa fa-plus"></i></a></div>
				</div>
				<div class="form-group clearfix">
					<label class="control-label col-md-4">Name:</label>
					<div class="col-md-7">
						<input type="text" class="form-control" v-model="subcategory.name" required>
					</div>
				</div>
				<div class="form-group clearfix">
					<label class="control-label col-md-4">Unit:</label>
					<div class="col-md-7">
						<v-select v-bind:options="units" v-model="selectedUnit" label="Unit_Name"></v-select>
					</div>
					<div class="col-md-1" style="padding:0;margin-left: -15px;"><a href="/unit" target="_blank" class="add-button"><i class="fa fa-plus"></i></a></div>
				</div>

				<div class="form-group clearfix">
					<label class="control-label col-md-4">Normal Range:</label>
					<div class="col-md-7">
						<input type="text" class="form-control" v-model="subcategory.normal_range" required>
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
				<datatable :columns="columns" :data="subcategories" :filter-by="filter" style="margin-bottom: 5px;">
					<template scope="{ row }">
						<tr>
							<td>{{ row.sl }}</td>
							<td>{{ row.Product_Name }}</td>
							<td>{{ row.name }}</td>
							<td>{{ row.normal_range }}</td>
							<td>{{ row.Unit_Name }}</td>
							<td>
								<?php if ($this->session->userdata('accountType') != 'u') { ?>
									<button type="button" class="button edit" @click="editData(row)">
										<i class="fa fa-pencil"></i>
									</button>
									<button type="button" class="button" @click="deleteData(row.id)">
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
		el: '#subcategory',
		data() {
			return {
				subcategory: {
					id: 0,
					name: '',
					normal_range: ''
				},
				subcategories: [],

				products: [],
				selectedTest: null,
				units: [],
				selectedUnit: null,

				columns: [{
						label: 'Sl',
						field: 'sl',
						align: 'center'
					},
					{
						label: 'Category',
						field: 'ProductCategory_Name',
						align: 'center'
					},
					{
						label: 'Name',
						field: 'name',
						align: 'center'
					},
					{
						label: 'Normal Range',
						field: 'normal_range',
						align: 'center'
					},
					{
						label: 'Unit',
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
			this.getUnit();
			this.getProducts();
			this.getData();
		},
		methods: {
			getUnit() {
				axios.get('/get_units').then(res => {
					this.units = res.data;
				})
			},
			getProducts() {
				axios.get('/get_products').then(res => {
					this.products = res.data;
				})
			},
			getData() {
				axios.get('/get_subcategories').then(res => {
					this.subcategories = res.data.map((item, index) => {
						item.sl = index + 1;
						return item;
					});
				})
			},

			saveData() {
				if(this.selectedTest == null){
					alert("Select category");
					return;
				}
				this.subcategory.test_id = this.selectedTest.Product_SlNo;
				this.subcategory.unit_id = this.selectedUnit ? this.selectedUnit.Unit_SlNo : "";
				let url = '/insertsubcategory';
				if (this.subcategory.id != 0) {
					url = '/updatesubcategory';
				}
				axios.post(url, this.subcategory).then(res => {
					let r = res.data;
					alert(r.message);
					if (r.status) {
						this.resetForm();
						this.getData();
					}
				})
			},
			editData(subcategory) {
				let keys = Object.keys(this.subcategory);
				keys.forEach(key => {
					this.subcategory[key] = subcategory[key];
				})

				this.selectedTest = this.products.find(item => item.Product_SlNo == subcategory.test_id)
				this.selectedUnit = this.units.find(item => item.Unit_SlNo == subcategory.unit_id)
			},
			deleteData(subcategoryId) {
				let deleteConfirm = confirm('Are you sure?');
				if (deleteConfirm == false) {
					return;
				}
				axios.post('/subcatdelete', {
					subcategoryId: subcategoryId
				}).then(res => {
					let r = res.data;
					alert(r.message);
					if (r.status) {
						this.getData();
					}
				})
			},
			resetForm() {
				this.subcategory = {
					id: '',
					name: '',
					normal_range: ''
				}
			}
		}
	})
</script>