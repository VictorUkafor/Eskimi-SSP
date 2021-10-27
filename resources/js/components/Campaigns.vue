<template>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <b-alert show dismissible fade variant="success" v-if="message">{{message}}</b-alert>
            <b-alert show dismissible fade variant="danger" v-if="error">{{error}}</b-alert>

            <div class="card">
                <div class="card-header" 
                style="display:flex;justify-content:space-between;align-items:center">
                    <div>Campaigns</div>
                    <b-button variant="success" style="color:#fff" 
                    v-b-modal.createModal>Create Campaign</b-button>
                </div>

                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Starting Date</th>
                            <th scope="col">End Date</th>
                            <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(campaign, index) in campaigns.data" :key="campaign.id"
                            >
                            <th scope="row">{{(index+1) + ((page - 1)*campaigns.per_page)}}</th>
                            <td>{{campaign.name}}</td>
                            <td>{{campaign.date_from}}</td>
                            <td>{{campaign.date_to}}</td>
                            <td>
                                <b-button variant="success" v-b-modal.editModal
                                @click="editCampaign(index)">Update</b-button>

                                <b-button variant="primary" v-b-modal.showModal 
                                @click="viewCampaign(index)">View</b-button>
                            </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    
        <nav aria-label="Page navigation example" class="mt-3">
            <ul class="pagination">
                <li class="page-item">
                    <a class="page-link" v-if="campaigns.prev_page_url" 
                    @click="paginate(campaigns.current_page - 1)" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                        <span class="sr-only">Previous</span>
                    </a>
                </li>
                <li class="page-item">
                    <a class="page-link" v-if="campaigns.next_page_url"
                    @click="paginate(campaigns.current_page + 1)" aria-label="Next" >
                        <span aria-hidden="true">&raquo;</span>
                        <span class="sr-only">Next</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>


    <b-modal id="createModal" hide-footer>
        <create-campaign
        @sendMessage="sendMessage($event)" 
        @sendError="sendError($event)" 
        />
    </b-modal> 

    <b-modal id="editModal" hide-footer>
        <edit-campaign 
        :campaign="selectedCampaign"
        @sendMessage="sendMessage($event)" 
        @sendError="sendError($event)"  
        />
    </b-modal> 

    <b-modal id="showModal" hide-footer>
        <view-campaign :campaign="selectedCampaign" />
    </b-modal>

</div>
</template>

<script>
    import axios from 'axios';
    import ViewCampaign from './ViewCampaign.vue';
    import CreateCampaign from './CreateCampaign.vue';
    import EditCampaign from './EditCampaign.vue';


    export default {
        components: {
            ViewCampaign,
            CreateCampaign,
            EditCampaign
        },
        data(){
            return {
                campaigns: {},
                page: 1,
                loading: false,
                selectedCampaign: {},
                message: "",
                error: "",
            }
        },
        methods: {
            sendMessage(message){
                this.message = message;
                this.fetchCampaigns();

                const self = this;
                setTimeout(function(){ 
                    self.message = '';
                }, 3000);
            },
            sendError(error){
                this.error = error;

                const self = this;
                setTimeout(function(){ 
                    self.error = '';
                }, 3000);
            },
            viewCampaign(index){
                this.selectedCampaign = this.campaigns.data[index];
            },
            editCampaign(index){
                const campaign = this.campaigns.data[index];
                this.selectedCampaign = campaign;

                this.edit.campaignName = campaign.name;
                this.edit.dailyBudget = campaign.daily_budget;
                this.edit.totalBudget = campaign.total_budget;
                this.edit.startingDate = campaign.date_from;
                this.edit.endDate = campaign.date_to;
            },
            onPickFile(){
                this.$refs.fileInput.click();
            },
            onFilePicked (event) {
                const files = event.target.files;

                const images = [];
                Object.values(files).map(each => {
                    images.push(each);
                });

                this.create.images = images;

                this.$refs.fileInput.value = null;
            },
            fetchCampaigns(){
                axios.get(`/campaigns?page=${this.page}`)
                .then((response) => {
                    this.campaigns = response.data.campaigns;
                    console.log('this.camopaa', response.data.campaigns);
                }).catch((err) =>{
                    console.log('error....', err.response.data)
                });
            },
            paginate(page){
                this.page = page;
                this.fetchCampaigns();
                axios.get(`/campaigns?page=${this.page}`);
            },
        },
        mounted() {
            this.fetchCampaigns();
        }
    }
</script>
