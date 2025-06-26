<div id="testInvoice">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<test-invoice v-bind:report_id="reportId"></test-invoice>
		</div>
	</div>
</div>

<script src="<?php echo base_url();?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/components/testInvoice.js"></script>
<script src="<?php echo base_url();?>assets/js/moment.min.js"></script>
<script>
	new Vue({
		el: '#testInvoice',
		components: {
			testInvoice
		},
		data(){
			return {
				reportId: parseInt('<?php echo $reportId;?>')
			}
		}
	})
</script>

