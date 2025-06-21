<style>
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

	#agents label {
		font-size: 13px;
	}

	#agents select {
		border-radius: 3px;
	}

	#agents .add-button {
		padding: 2.5px;
		width: 28px;
		background-color: #298db4;
		display: block;
		text-align: center;
		color: white;
	}

	#agents .add-button:hover {
		background-color: #41add6;
		color: white;
	}
	tr td{
		vertical-align: middle !important;
	}
</style>
<div id="agents">
	<form @submit.prevent="saveData">
		<div class="row" style="margin-top: 10px;margin-bottom:15px;border-bottom: 1px solid #ccc;padding-bottom:15px;">
			<div class="col-md-5 col-md-offset-1">
				<div class="form-group clearfix">
					<label class="control-label col-md-4">Agent Id:</label>
					<div class="col-md-7">
						<input type="text" class="form-control" v-model="agent.Agent_Code" required readonly>
					</div>
				</div>

				<div class="form-group clearfix">
					<label class="control-label col-md-4">Agent Name:</label>
					<div class="col-md-7">
						<input type="text" class="form-control" v-model="agent.Agent_Name" required>
					</div>
				</div>
				<div class="form-group clearfix">
					<label class="control-label col-md-4">Mobile:</label>
					<div class="col-md-7">
						<input type="text" class="form-control" v-model="agent.Agent_Mobile" required>
					</div>
				</div>
			</div>
			
			<div class="col-md-5">
				<div class="form-group clearfix">
					<label class="control-label col-md-4">Address:</label>
					<div class="col-md-7">
						<input type="text" class="form-control" v-model="agent.Agent_Address">
					</div>
				</div>
				<div class="form-group clearfix">
					<label class="control-label col-md-4">Commission:</label>
					<div class="col-md-7">
						<input type="number" step="any" min="0" class="form-control" v-model="agent.commission" />
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
				<datatable :columns="columns" :data="agents" :filter-by="filter" style="margin-bottom: 5px;">
					<template scope="{ row }">
						<tr>
							<td>{{ row.sl }}</td>
							<td>{{ row.Agent_Code }}</td>
							<td>{{ row.Agent_Name }}</td>
							<td>{{ row.Agent_Mobile }}</td>
							<td>{{ row.Agent_Address }}</td>
							<td>{{ row.commission }}</td>
							<td>
								<?php if ($this->session->userdata('accountType') != 'u') { ?>
									<button type="button" class="button edit" @click="editData(row)">
										<i class="fa fa-pencil"></i>
									</button>
									<button type="button" class="button" @click="deleteData(row.Agent_SlNo)">
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
		el: '#agents',
		data() {
			return {
				agent: {
					Agent_SlNo: 0,
					Agent_Code: '<?php echo $agentCode; ?>',
					Agent_Name: '',
					Agent_Mobile: '',
					Agent_Address: '',
					commission: 0
				},
				agents: [],
				districts: [],
				selectedDistrict: null,
				imageUrl: '',
				selectedFile: null,

				columns: [{
						label: 'Sl',
						field: 'sl',
						align: 'center',
						filterable: false
					},
					{
						label: 'Agent Id',
						field: 'Agent_Code',
						align: 'center',
						filterable: false
					},
					{
						label: 'Agent Name',
						field: 'Agent_Name',
						align: 'center'
					},
					{
						label: 'Contact Number',
						field: 'Agent_Mobile',
						align: 'center'
					},
					{
						label: 'Address',
						field: 'Agent_Address',
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
			this.getAgents();
		},
		methods: {
			getAgents() {
				axios.get('/get_agents').then(res => {
					this.agents = res.data.map((item, index) => {
						item.sl = index + 1;
						return item;
					});
				})
			},
			
			saveData() {
				let url = '/add_agent';
				if (this.agent.Agent_SlNo != 0) {
					url = '/update_agent';
				}

				let fd = new FormData();
				fd.append('data', JSON.stringify(this.agent));

				axios.post(url, fd).then(res => {
					let r = res.data;
					alert(r.message);
					if (r.success) {
						this.resetForm();
						this.agent.Agent_Code = r.agentCode;
						this.getAgents();
					}
				})
			},
			editData(agent) {
				let keys = Object.keys(this.agent);
				keys.forEach(key => {
					this.agent[key] = agent[key];
				})
			},
			deleteData(agentId) {
				let deleteConfirm = confirm('Are you sure?');
				if (deleteConfirm == false) {
					return;
				}
				axios.post('/delete_agent', {
					agentId: agentId
				}).then(res => {
					let r = res.data;
					alert(r.message);
					if (r.success) {
						this.getAgents();
					}
				})
			},
			resetForm() {
				this.agent = {
					Agent_SlNo: 0,
					Agent_Code: '<?php echo $agentCode; ?>',
					Agent_Name: '',
					Agent_Mobile: '',
					Agent_Address: '',
					commission: 0
				}
			}
		}
	})
</script>