<div id="dialysisInvoice">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<dialysis-invoice v-bind:dialysis_id="dialysisId"></dialysis-invoice>
		</div>
	</div>
</div>

<script src="<?php echo base_url();?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/components/dialysisInvoice.js"></script>
<script src="<?php echo base_url();?>assets/js/moment.min.js"></script>
<script>
	new Vue({
		el: '#dialysisInvoice',
		components: {
			dialysisInvoice
		},
		data(){
			return {
				dialysisId: parseInt('<?php echo $dialysisId;?>')
			}
		}
	})
</script>