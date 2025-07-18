<style>
	.v-select {
		margin-bottom: 5px;
	}

	.v-select .dropdown-toggle {
		padding: 0px;
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

	#branchDropdown .vs__actions button {
		display: none;
	}

	#branchDropdown .vs__actions .open-indicator {
		height: 15px;
		margin-top: 7px;
	}

	.add-button {
		padding: 2.8px;
		width: 100%;
		background-color: #0087bb;
		display: block;
		text-align: center;
		color: white;
		cursor: pointer;
		border-radius: 3px;
	}

	.add-button:hover {
		color: white;
	}

	.add-button:focus {
		color: white;
	}
</style>

<div id="sales" class="row">
	<div class="col-xs-12 col-md-12 col-lg-12" style="border-bottom:1px #ccc solid;margin-bottom:5px;">
		<div class="row">
			<div class="form-group">
				<label class="col-md-1 control-label no-padding-right"> Invoice no </label>
				<div class="col-md-2">
					<input type="text" id="invoiceNo" class="form-control" v-model="sales.invoiceNo" readonly />
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-1 control-label no-padding-right"> Doctor </label>
				<div class="col-md-2">
					<v-select :options="doctors" v-model="selectedDoctor" label="display_name" placeholder="Select Doctor"></v-select>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-1 control-label no-padding-right"> Added By </label>
				<div class="col-md-2">
					<input type="text" class="form-control" v-model="sales.salesBy" readonly />
				</div>
			</div>

			<div class="form-group">
				<div class="col-md-3">
					<input class="form-control" id="salesDate" type="date" v-model="sales.salesDate" v-bind:disabled="userType == 'u' ? true : false" />
				</div>
			</div>
		</div>
	</div>


	<div class="col-xs-12 col-md-9 col-lg-9">
		<div class="widget-box">
			<div class="widget-header">
				<h4 class="widget-title">Patient & Test Information</h4>
				<div class="widget-toolbar">
					<a href="#" data-action="collapse">
						<i class="ace-icon fa fa-chevron-up"></i>
					</a>

					<a href="#" data-action="close">
						<i class="ace-icon fa fa-times"></i>
					</a>
				</div>
			</div>

			<div class="widget-body">
				<div class="widget-main">

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-xs-4 control-label no-padding-right"> Patient </label>
								<div class="col-xs-8" style="display: flex;align-items:center;margin-bottom:5px;">
									<div style="width: 86%;">
										<v-select v-bind:options="customers" style="margin: 0;" label="display_name" v-model="selectedCustomer" v-on:input="customerOnChange" @search="onSearchCustomer"></v-select>
									</div>
									<div style="width: 13%;margin-left:2px;">
										<a href="<?= base_url('customer') ?>" class="add-button" target="_blank" title="Add New Customer"><i class="fa fa-plus" aria-hidden="true"></i></a>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-xs-4 control-label no-padding-right"> Name </label>
								<div class="col-xs-8">
									<input type="text" id="customerName" placeholder="Patient Name" class="form-control" v-model="selectedCustomer.Customer_Name" v-bind:disabled="selectedCustomer.Customer_Type == 'G' || selectedCustomer.Customer_Type == 'N' ? false : true" />
								</div>
							</div>

							<div class="form-group">
								<label class="col-xs-4 control-label no-padding-right"> Mobile </label>
								<div class="col-xs-8">
									<input type="text" id="mobileNo" placeholder="Mobile" class="form-control" v-model="selectedCustomer.Customer_Mobile" v-bind:disabled="selectedCustomer.Customer_Type == 'G' || selectedCustomer.Customer_Type == 'N' ? false : true" />
								</div>
							</div>

							<div class="form-group">
								<label class="col-xs-4 control-label no-padding-right"> Address </label>
								<div class="col-xs-8">
									<textarea id="address" style="height: 35px;" placeholder="Address" class="form-control" v-model="selectedCustomer.Customer_Address" v-bind:disabled="selectedCustomer.Customer_Type == 'G' || selectedCustomer.Customer_Type == 'N' ? false : true"></textarea>
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<form @submit.prevent="addToCart">
								<div class="form-group">
									<label class="col-xs-3 control-label no-padding-right"> Category </label>
									<div class="col-xs-9" style="display: flex;align-items:center;margin-bottom:5px;">
										<div style="width: 86%;">
											<v-select v-bind:options="categories" id="category" style="margin: 0;" v-model="selectedCategory" label="ProductCategory_Name" @input="onChangeCategory" placeholder="Select Category"></v-select>
										</div>
										<div style="width: 13%;margin-left:2px;">
											<a href="<?= base_url('category') ?>" class="add-button" target="_blank" title="Add New Category"><i class="fa fa-plus" aria-hidden="true"></i></a>
										</div>
									</div>
								</div>

								<div class="form-group">
									<label class="col-xs-3 control-label no-padding-right"> Test </label>
									<div class="col-xs-9" style="display: flex;align-items:center;margin-bottom:5px;">
										<div style="width: 86%;">
											<v-select v-bind:options="products" id="product" style="margin: 0;" v-model="selectedProduct" label="display_text" @input="productOnChange" @search="onSearchProduct"></v-select>
										</div>
										<div style="width: 13%;margin-left:2px;">
											<a class="add-button" href="/product" target="_blank"><i class="fa fa-plus" aria-hidden="true"></i></a>
										</div>
									</div>
								</div>

								<div class="form-group">
									<label class="col-xs-3 control-label no-padding-right"> Room </label>
									<div class="col-xs-9">
										<v-select v-bind:options="rooms" id="room" v-model="selectedRoom" @input="onChangeRoom" label="Room_Name" placeholder="select room"></v-select>
									</div>
								</div>

								<div class="form-group">
									<label class="col-xs-3 control-label no-padding-right"> Rate </label>
									<div class="col-xs-9">
										<input type="number" id="salesRate" ref="salesRate" placeholder="Rate" step="0.01" class="form-control" v-model="selectedProduct.Product_SellingPrice" v-on:input="productTotal" />
									</div>
								</div>

								<div class="form-group">
									<label class="col-xs-3 control-label no-padding-right"> Total </label>
									<div class="col-xs-9">
										<input type="text" id="productTotal" placeholder="Total" class="form-control" v-model="selectedProduct.total" readonly />
									</div>
								</div>

								<div class="form-group">
									<label class="col-xs-3 control-label no-padding-right"> </label>
									<div class="col-xs-9">
										<button type="submit" style="padding: 3px 6px; background: rgb(0, 126, 187) !important; border-color: rgb(0, 126, 187) !important; outline: none; border-radius: 6px;" class="btn pull-right">Add to Cart</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>


		<div class="col-xs-12 col-md-12 col-lg-12" style="padding-left: 0px;padding-right: 0px;">
			<div class="table-responsive">
				<table class="table table-bordered" style="color:#000;margin-bottom: 5px;">
					<thead>
						<tr class="">
							<th style="width:10%;color:#000;">Sl</th>
							<th style="width:15%;color:#000;">Category</th>
							<th style="width:25%;color:#000;">Test Name</th>
							<th style="width:25%;color:#000;">Room</th>
							<th style="width:8%;color:#000;">Rate</th>
							<th style="width:15%;color:#000;">Total</th>
							<th style="width:10%;color:#000;">Action</th>
						</tr>
					</thead>
					<tbody style="display:none;" v-bind:style="{display: cart.length > 0 ? '' : 'none'}">
						<tr v-for="(product, sl) in cart">
							<td>{{ sl + 1 }}</td>
							<td>{{ product.categoryName }}</td>
							<td>{{ product.name }} - {{ product.productCode }}</td>
							<td>{{ product.Room_Name }}</td>
							<td>{{ product.salesRate }}</td>
							<td>{{ product.total }}</td>
							<td><a href="" v-on:click.prevent="removeFromCart(sl)"><i class="fa fa-trash"></i></a></td>
						</tr>

						<tr>
							<td colspan="7"></td>
						</tr>

						<tr style="font-weight: bold;">
							<td colspan="4">Note</td>
							<td colspan="3">Total</td>
						</tr>

						<tr>
							<td colspan="4"><textarea style="width: 100%;font-size:13px;" placeholder="Note" v-model="sales.note"></textarea></td>
							<td colspan="3" style="padding-top: 15px;font-size:18px;">{{ sales.total }}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>


	<div class="col-xs-12 col-md-3 col-lg-3">
		<div class="widget-box">
			<div class="widget-header">
				<h4 class="widget-title">Amount Details</h4>
				<div class="widget-toolbar">
					<a href="#" data-action="collapse">
						<i class="ace-icon fa fa-chevron-up"></i>
					</a>

					<a href="#" data-action="close">
						<i class="ace-icon fa fa-times"></i>
					</a>
				</div>
			</div>

			<div class="widget-body">
				<div class="widget-main">
					<div class="row">
						<div class="col-xs-12">
							<div class="table-responsive">
								<table style="color:#000;margin-bottom: 0px;border-collapse: collapse;">
									<tr>
										<td>
											<div class="form-group">
												<label class="col-xs-12 control-label no-padding-right">Employee</label>
												<div class="col-xs-12">													
													<v-select v-bind:options="employees" v-model="selectedEmployee" label="Employee_Name" @input="onChangeEmployee" placeholder="Select Employee"></v-select>
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td>
											<div class="form-group">
												<label class="col-xs-12 control-label no-padding-right">SubTotal</label>
												<div class="col-xs-12">
													<input type="number" id="subTotal" class="form-control" v-model="sales.subTotal" readonly />
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td>
											<div class="form-group">
												<label class="col-xs-12 control-label no-padding-right">Discount</label>

												<div class="col-xs-4">
													<input type="number" id="discountPercent" class="form-control" v-model="discountPercent" v-on:input="calculateTotal" />
												</div>

												<label class="col-xs-1 control-label no-padding-right">%</label>

												<div class="col-xs-7">
													<input type="number" id="discount" class="form-control" v-model="sales.discount" v-on:input="calculateTotal" />
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td>
											<div class="form-group">
												<label class="col-xs-12 control-label no-padding-right">Total</label>
												<div class="col-xs-12">
													<input type="number" id="total" class="form-control" v-model="sales.total" readonly />
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td>
											<div class="form-group">
												<label class="col-xs-6 control-label">Cash</label>
												<label class="col-xs-6 control-label">Bank</label>
											</div>
										</td>
									</tr>
									<tr>
										<td>
											<div class="form-group">
												<div class="col-xs-6">
													<input type="number" id="cashPaid" class="form-control" v-model="sales.cashPaid" v-on:input="calculateTotal" />
												</div>
												<div class="col-xs-6">
													<input type="number" id="bankPaid" class="form-control" v-model="sales.bankPaid" v-on:input="calculateTotal" />
												</div>
											</div>
										</td>
									</tr>

									<tr v-if="sales.bankPaid > 0" style="display:none;" :style="{display: sales.bankPaid > 0 ? '' : 'none'}">
										<td>
											<div class="form-group">
												<label class="col-xs-12 control-label">Bank Account</label>
												<div class="col-xs-12">
													<v-select v-bind:options="banks" v-model="selectedBank" label="display_name" placeholder="Select Bank Name"></v-select>
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td>
											<div class="form-group">
												<label class="col-xs-12 control-label no-padding-right">Return Amount</label>
												<div class="col-xs-12">
													<input type="number" id="returnAmount" class="form-control" v-model="sales.returnAmount" readonly />
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td>
											<div class="form-group">
												<label class="col-xs-12 control-label">Due</label>
												<div class="col-xs-12">
													<input type="number" id="due" class="form-control" v-model="sales.due" readonly />
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td>
											<div class="form-group" style="margin-top: 6px;">
												<div class="col-xs-6">
													<input type="button" class="btn btn-default btn-sm" value="Sale" v-on:click="saveSales" v-bind:disabled="saleOnProgress ? true : false" style="background: rgb(0, 126, 187) !important; outline: none; border: 0px !important; color: rgb(255, 255, 255) !important; margin-top: 0px; width: 100%; padding: 7px 5px; font-weight: bold; border-radius: 5px;">
												</div>
												<div class="col-xs-6">
													<a class="btn btn-info btn-sm" v-bind:href="`/sales/${sales.isService == 'true' ? 'service' : 'product'}`" style="background: rgb(209, 91, 71) !important; border: 0px !important; margin-top: 0px; width: 100%; padding: 7px 5px; font-weight: bold; outline: none; border-radius: 5px;">Reset</a>
												</div>
											</div>
										</td>
									</tr>
								</table>
							</div>
						</div>
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
	Vue.component('v-select', VueSelect.VueSelect);
	new Vue({
		el: '#sales',
		data() {
			return {
				sales: {
					salesId: parseInt('<?php echo $salesId; ?>'),
					invoiceNo: '<?php echo $invoice; ?>',
					salesBy: '<?php echo $this->session->userdata("FullName"); ?>',
					salesFrom: '',
					salesDate: moment().format('YYYY-MM-DD'),
					customerId: '',
					employeeId: null,
					subTotal: 0,
					discount: 0,
					total: 0,
					bank_id: '',
					bankPaid: 0,
					cashPaid: 0,
					paid: 0,
					returnAmount: 0,
					due: 0,
					note: ''
				},
				discountPercent: 0,
				cart: [],
				rooms: [],
				selectedRoom: null,
				categories: [],
				selectedCategory: null,
				employees: [],
				selectedEmployee: null,
				doctors: [],
				selectedDoctor: null,
				banks: [],
				selectedBank: null,
				branches: [],
				selectedBranch: {
					brunch_id: "<?php echo $this->session->userdata('BRANCHid'); ?>",
					Brunch_name: "<?php echo $this->session->userdata('Brunch_name'); ?>"
				},
				customers: [],
				selectedCustomer: {
					Customer_SlNo: '',
					Customer_Code: '',
					Customer_Name: 'General Patient',
					display_name: 'General Patient',
					Customer_Mobile: '',
					Customer_Address: '',
					Customer_Type: 'G'
				},
				oldCustomerId: null,
				products: [],
				selectedProduct: {
					Product_SlNo: '',
					display_text: 'Select Test',
					Product_Name: '',
					quantity: 0,
					Product_Purchase_Rate: '',
					Product_SellingPrice: 0,
					total: 0
				},
				saleOnProgress: false,
				sales_due_on_update: 0,
				userType: '<?php echo $this->session->userdata("accountType"); ?>'
			}
		},
		async created() {
			await this.getBank();
			await this.getRoom();
			await this.getDoctor();
			await this.getCategory();
			await this.getEmployees();
			await this.getBranches();
			await this.getCustomers();
			this.getProducts();

			if (this.sales.salesId != 0) {
				await this.getSales();
			}
		},
		methods: {
			getBank() {
				axios.get('/get_bank_accounts').then(res => {
					this.banks = res.data.map(item => {
						item.display_name = `${item.bank_name} - ${item.account_number} - ${item.account_name}`;
						return item;
					});
				})
			},
			getRoom() {
				axios.get('/get_rooms').then(res => {
					this.rooms = res.data;
				})
			},
			getDoctor() {
				axios.get('/get_doctors').then(res => {
					this.doctors = res.data;
				})
			},
			getCategory() {
				axios.get('/get_categories').then(res => {
					this.categories = res.data;
				})
			},
			onChangeCategory() {
				if (this.selectedCategory == null) {
					return
				}
				this.clearProduct();
				this.getProducts();
			},

			getEmployees() {
				axios.get('/get_employees').then(res => {
					this.employees = res.data;
				})
			},
			onChangeEmployee() {
				if (this.selectedEmployee == null) {
					return;
				}
				this.getCustomers();
			},
			getBranches() {
				axios.get('/get_branches').then(res => {
					this.branches = res.data;
				})
			},
			async getCustomers() {
				await axios.post('/get_customers', {
					customerType: this.sales.salesType,
					employeeId: this.selectedEmployee == null ? '' : this.selectedEmployee.Employee_SlNo
				}).then(res => {
					this.customers = res.data;
					this.customers.unshift({
						Customer_SlNo: '',
						Customer_Code: '',
						Customer_Name: 'General Patient',
						display_name: 'General Patient',
						Customer_Mobile: '',
						Customer_Address: '',
						Customer_Type: 'G'
					});
				})
			},
			async onSearchCustomer(val, loading) {
				if (val.length > 2) {
					loading(true);
					await axios.post("/get_customers", {
							name: val,
						})
						.then(res => {
							let r = res.data;
							this.customers = r.filter(item => item.status == 'a')
							loading(false)
						})
				} else {
					loading(false)
					await this.getCustomers();
				}
			},
			async customerOnChange() {
				if (this.selectedCustomer == null) {
					this.selectedCustomer = {
						Customer_SlNo: '',
						Customer_Code: '',
						Customer_Name: 'General Patient',
						display_name: 'General Patient',
						Customer_Mobile: '',
						Customer_Address: '',
						Customer_Type: 'G'
					}
					return;
				}

				if (this.selectedCustomer.Customer_SlNo != '') {
					this.calculateTotal();
				}
			},

			getProducts() {
				axios.post('/get_products', {
					categoryId: this.selectedCategory == null ? "" : this.selectedCategory.ProductCategory_SlNo
				}).then(res => {
					this.products = res.data;
				})
			},
			async onSearchProduct(val, loading) {
				if (val.length > 2) {
					loading(true);
					await axios.post("/get_products", {
							name: val,
							categoryId: this.selectedCategory == null ? "" : this.selectedCategory.ProductCategory_SlNo
						})
						.then(res => {
							let r = res.data;
							this.products = r.filter(item => item.status == 'a');
							loading(false)
						})
				} else {
					loading(false)
					await this.getProducts();
				}
			},
			productTotal() {
				this.selectedProduct.total = (parseFloat(this.selectedProduct.quantity) * parseFloat(this.selectedProduct.Product_SellingPrice)).toFixed(2);
			},

			async productOnChange() {
				if (this.selectedProduct == null) {
					this.selectedProduct = {
						Product_SlNo: '',
						display_text: 'Select Test',
						Product_Name: '',
						quantity: 0,
						Product_Purchase_Rate: '',
						Product_SellingPrice: 0,
						total: 0
					}
					return;
				}
				if ((this.selectedProduct.Product_SlNo != '' || this.selectedProduct.Product_SlNo != 0)) {
					document.querySelector("#room [type='search']").focus();
				}

			},

			async onChangeRoom() {
				if (this.selectedProduct == null) {
					this.selectedProduct = {
						Product_SlNo: '',
						display_text: 'Select Test',
						Product_Name: '',
						quantity: 0,
						Product_Purchase_Rate: '',
						Product_SellingPrice: 0,
						total: 0
					}
					return;
				}
				if ((this.selectedProduct.Product_SlNo != '' || this.selectedProduct.Product_SlNo != 0)) {
					this.selectedProduct.quantity = 1;
					this.$refs.salesRate.focus();
					await this.productTotal();
				}
			},

			addToCart() {
				let product = {
					productId: this.selectedProduct.Product_SlNo,
					room_id: this.selectedRoom ? this.selectedRoom.Room_SlNo : '',
					Room_Name: this.selectedRoom ? this.selectedRoom.Room_Name : '',
					productCode: this.selectedProduct.Product_Code,
					categoryName: this.selectedProduct.ProductCategory_Name,
					name: this.selectedProduct.Product_Name,
					salesRate: this.selectedProduct.Product_SellingPrice,
					quantity: this.selectedProduct.quantity,
					total: this.selectedProduct.total,
					purchaseRate: this.selectedProduct.Product_Purchase_Rate
				}

				if (product.productId == '' || product.productId == null) {
					alert('Select Test');
					return;
				}
				if (product.room_id == '' || product.room_id == null) {
					alert('Select Room');
					return;
				}

				if (product.quantity == 0 || product.quantity == '') {
					alert('Enter quantity');
					return;
				}

				if (product.salesRate == 0 || product.salesRate == '') {
					alert('Enter rate');
					return;
				}

				let cartInd = this.cart.findIndex(p => p.productId == product.productId);
				if (cartInd > -1) {
					this.cart.splice(cartInd, 1);
				}

				this.cart.unshift(product);
				this.clearProduct();
				this.calculateTotal();
			},
			removeFromCart(ind) {
				this.cart.splice(ind, 1);
				this.calculateTotal();
			},
			clearProduct() {
				this.selectedProduct = {
					Product_SlNo: '',
					display_text: 'Select Test',
					Product_Name: '',
					quantity: 0,
					Product_Purchase_Rate: '',
					Product_SellingPrice: 0,
					total: 0
				}
				this.selectedRoom = null;
			},
			calculateTotal() {
				this.sales.subTotal = this.cart.reduce((prev, curr) => {
					return prev + parseFloat(curr.total)
				}, 0).toFixed(2);

				if (event.target.id == 'discountPercent') {
					this.sales.discount = ((parseFloat(this.sales.subTotal) * parseFloat(this.discountPercent)) / 100).toFixed(2);
				} else {
					this.discountPercent = (parseFloat(this.sales.discount) / parseFloat(this.sales.subTotal) * 100).toFixed(2);
				}
				this.sales.total = (parseFloat(this.sales.subTotal) - parseFloat(this.sales.discount)).toFixed(2);

				if (event.target.id == 'cashPaid' || event.target.id == 'bankPaid') {
					this.sales.paid = parseFloat(parseFloat(this.sales.cashPaid) + parseFloat(this.sales.bankPaid)).toFixed(2);
					if (parseFloat(this.sales.paid) > parseFloat(this.sales.total)) {
						this.sales.returnAmount = parseFloat(this.sales.paid - this.sales.total).toFixed(2);
						this.sales.due = 0;
					} else {
						this.sales.returnAmount = 0;
						this.sales.due = parseFloat(this.sales.total - this.sales.paid).toFixed(2);
					}
				} else {
					this.sales.cashPaid = this.sales.total;
					this.sales.bankPaid = 0;
					this.sales.paid = this.sales.total;
					this.sales.due = 0;
					this.sales.returnAmount = 0;
				}

			},
			async saveSales() {
				if (this.selectedCustomer == null) {
					alert('Select Patient');
					return;
				}
				if (this.cart.length == 0) {
					alert('Cart is empty');
					return;
				}
				if (this.selectedCustomer.Customer_Type == 'G' && parseFloat(this.sales.due) != 0) {
					alert('Due sale does not accept on general Patient');
					return;
				}

				if (this.sales.bankPaid > 0) {
					if (this.selectedBank == null) {
						alert('Select Bank');
						return;
					}
					this.sales.bank_id = this.selectedBank.account_id
				}

				let url = "/add_sales";
				if (this.sales.salesId != 0) {
					url = "/update_sales";
				}

				this.sales.employeeId = this.selectedEmployee ? this.selectedEmployee.Employee_SlNo : '';
				this.sales.doctorId = this.selectedDoctor ? this.selectedDoctor.Doctor_SlNo : '';
				this.sales.customerId = this.selectedCustomer.Customer_SlNo;
				this.sales.salesFrom = this.selectedBranch.brunch_id;
				let data = {
					sales: this.sales,
					cart: this.cart,
					customer: this.selectedCustomer
				}
				this.saleOnProgress = true;
				axios.post(url, data).then(async res => {
					let r = res.data;
					if (r.success) {
						let conf = confirm(`${r.message}, Do you want to view invoice?`);
						if (conf) {
							window.open('/sale_invoice_print/' + r.salesId, '_blank');
							await new Promise(r => setTimeout(r, 1000));
							window.location = '/bill_entry';
						} else {
							window.location = '/bill_entry';
						}
					} else {
						alert(r.message);
						this.saleOnProgress = false;
					}
				})
			},
			async getSales() {
				await axios.post('/get_sales', {
					salesId: this.sales.salesId
				}).then(res => {
					let r = res.data;
					let sales = r.sales[0];
					this.sales.salesBy = sales.AddBy;
					this.sales.salesFrom = sales.SaleMaster_branchid;
					this.sales.salesDate = sales.SaleMaster_SaleDate;
					this.sales.salesType = sales.SaleMaster_SaleType;
					this.sales.customerId = sales.SalseCustomer_IDNo;
					this.sales.employeeId = sales.Employee_SlNo;
					this.sales.subTotal = sales.SaleMaster_SubTotalAmount;
					this.sales.discount = sales.SaleMaster_TotalDiscountAmount;
					this.sales.total = sales.SaleMaster_TotalSaleAmount;
					this.sales.paid = sales.SaleMaster_PaidAmount;
					this.sales.cashPaid = sales.SaleMaster_cashPaid;
					this.sales.bankPaid = sales.SaleMaster_bankPaid;
					this.sales.doctor_id = sales.doctor_id;
					this.sales.bank_id = sales.bank_id;
					this.sales.due = sales.SaleMaster_DueAmount;
					this.sales.note = sales.SaleMaster_Description;

					this.oldCustomerId = sales.SalseCustomer_IDNo;
					this.discountPercent = parseFloat(this.sales.discount) * 100 / parseFloat(this.sales.subTotal);

					this.selectedEmployee = {
						Employee_SlNo: sales.employee_id,
						Employee_Name: sales.Employee_Name
					}
					if (sales.SaleMaster_bankPaid > 0) {
						this.selectedBank = {
							account_id: sales.bank_id,
							display_name: `${sales.bank_name} - ${sales.account_number} - ${sales.account_name}`
						}
					}

					this.selectedCustomer = {
						Customer_SlNo: sales.SalseCustomer_IDNo,
						Customer_Code: sales.Customer_Code,
						Customer_Name: sales.Customer_Name,
						display_name: sales.Customer_Type == 'G' ? 'General Patient' : `${sales.Customer_Code} - ${sales.Customer_Name}`,
						Customer_Mobile: sales.Customer_Mobile,
						Customer_Address: sales.Customer_Address,
						Customer_Type: sales.Customer_Type
					}

					r.saleDetails.forEach(product => {
						let cartProduct = {
							productCode: product.Product_Code,
							productId: product.Product_IDNo,
							categoryName: product.ProductCategory_Name,
							name: product.Product_Name,
							room_id: product.room_id,
							Room_Name: product.Room_Name,
							salesRate: product.SaleDetails_Rate,
							quantity: product.SaleDetails_TotalQuantity,
							total: product.SaleDetails_TotalAmount,
							purchaseRate: product.Purchase_Rate
						}
						this.cart.push(cartProduct);
					})

					setTimeout(() => {
						this.selectedDoctor = this.doctors.find(doc => doc.Doctor_SlNo == sales.doctor_id);
					}, 1500);
				})
			}
		}
	})
</script>