<style>
    .v-select {
        margin-bottom: 5px;
        width: 250px;
    }

    .v-select .dropdown-toggle {
        padding: 0px;
    }

    .v-select input[type=search],
    .v-select input[type=search]:focus {
        margin: 0px;
    }

    .v-select .selected-tag {
        margin: 0px;
    }
</style>

<div id="dialysisInvoiceReport" class="row">
    <div class="col-xs-12 col-md-12 col-lg-12" style="border-bottom:1px #ccc solid;margin-bottom:5px;">
        <div class="form-group" style="margin-top:10px;">
            <label class="col-sm-1 col-sm-offset-2 control-label no-padding-right"> Invoice no </label>
            <label class="col-sm-1 control-label no-padding-right"> : </label>
            <div class="col-sm-3">
                <v-select v-bind:options="dialysis" label="display_name" v-model="selectedInvoice" v-on:input="viewInvoice" placeholder="Select Invoice"></v-select>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-2">
                <input type="button" class="btn btn-primary" value="Show Report" v-on:click="viewInvoice" style="margin-top:0px;width:150px;display: none;">
            </div>
        </div>
    </div>
    <div class="col-md-8 col-md-offset-2">
        <br>
        <dialysis-invoice v-bind:dialysis_id="selectedInvoice.id" v-if="showInvoice"></dialysis-invoice>
    </div>
</div>



<script src="<?php echo base_url(); ?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/vue-select.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/components/dialysisInvoice.js"></script>

<script>
    Vue.component('v-select', VueSelect.VueSelect);
    new Vue({
        el: '#dialysisInvoiceReport',
        data() {
            return {
                dialysis: [],
                selectedInvoice: null,
                showInvoice: false
            }
        },
        created() {
            this.getDialysis();
        },
        methods: {
            getDialysis() {
                axios.get("/get_dialysis").then(res => {
                    this.dialysis = res.data;
                })
            },
            async viewInvoice() {
                this.showInvoice = false;
                await new Promise(r => setTimeout(r, 500));
                this.showInvoice = true;
            }
        }
    })
</script>