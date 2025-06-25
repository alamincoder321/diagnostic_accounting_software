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
    <!-- <div class="row">
        <div class="col-xs-12 col-md-12 col-lg-12" style="border-bottom:1px #ccc solid;">
            <form class="form-inline">
                <div class="form-group">
                    <label>Search Type</label>
                    <select class="form-control" style="width:150px;" v-model="searchType" v-on:change="onChangeSearchType" style="padding:0px;">
                        <option value="">All</option>
                    </select>
                </div>

                <div class="form-group">
                    <input type="button" class="btn btn-primary" value="Show Report" v-on:click="getAgents" style="margin-top: -4px; border: 0px; padding: 3px 6px;">
                </div>
            </form>
        </div>
    </div> -->
    <div style="display:none;" v-bind:style="{display: agents.length > 0 ? '' : 'none'}">
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
                            <th>Agent Id</th>
                            <th>Agent Name</th>
                            <th>Contact No.</th>
                            <th>Address</th>
                            <th>Commission</th>
                        </thead>
                        <tbody>
                            <tr v-for="(agent, sl) in agents">
                                <td>{{ sl + 1 }}</td>
                                <td>{{ agent.Agent_Code }}</td>
                                <td>{{ agent.Agent_Name }}</td>
                                <td>{{ agent.Agent_Mobile }}</td>
                                <td>{{ agent.Agent_Address }}</td>
                                <td>{{ agent.commission }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div style="display:none;text-align:center;" v-bind:style="{display: agents.length > 0 ? 'none' : ''}">
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
                areas: [],
                selectedArea: null,
                agents: [],
            }
        },
        created() {
            this.getAgents();
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
            getAreas() {
                axios.get('/get_districts').then(res => {
                    this.areas = res.data;
                })
            },

            getAgents() {
                axios.post('/get_agents').then(res => {
                    this.agents = res.data;
                })
            },

            async printCustomerList() {
                let printContent = `
                    <div class="container">
                        <h4 style="text-align:center">Agent List</h4 style="text-align:center">
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