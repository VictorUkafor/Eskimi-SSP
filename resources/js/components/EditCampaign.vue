<template>
    <form>
        <div class="form-group">
            <label for="exampleFormControlInput1">Campaign Name</label>
            <input type="text" class="form-control" 
            placeholder="Campaign Name" v-model="edit.campaignName">
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="exampleFormControlInput1">Daily Budget</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">$</div>
                    </div>
                    <input type="text" class="form-control" 
                    placeholder="Daily Budget" v-model="edit.dailyBudget">
                </div>
            </div>
            <div class="form-group col-md-6">
                <label for="exampleFormControlInput1">Total Budget</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">$</div>
                    </div>
                    <input type="text" class="form-control" 
                    placeholder="Total Budget" v-model="edit.totalBudget">
                </div>
            </div>                   
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="exampleFormControlInput1">Starting Date</label>
                <input type="date" class="form-control" placeholder="Starting Date"
                v-model="edit.startingDate">
            </div>
            <div class="form-group col-md-6">
                <label for="exampleFormControlInput1">Ending Date</label>
                <input type="date" class="form-control" placeholder="Ending Date"
                v-model="edit.endDate">
            </div>
        </div>

        <div class="d-flex justify-content-end pt-2">
            <b-button class="mr-2" variant="secondary"
            ref="removeBtn"  
            @click="$bvModal.hide('editModal')">Cancel</b-button>
            <button type="button" class="btn btn-primary" 
            @click="updateCampaign()">{{loading ? 'Loading...' : 'Update'}}</button>
        </div> 
    </form>
</template>

<script>
    import axios from 'axios';

    export default {
        data(){
            return {
                loading: false,
                uid: '',
                edit: {
                    campaignName: "",
                    dailyBudget: "",
                    totalBudget: "",
                    startingDate: "",
                    endDate: "",
                    errors: {
                        dailyBudget: "",
                        totalBudget: "",
                    }
                }
            }
        },
        props: ["campaign"],
        methods: {
            updateCampaign(){
                this.loading = true;

                const body = {
                    name: this.edit.campaignName,
                    date_to: this.edit.endDate,
                    date_from: this.edit.startingDate,
                    daily_budget: parseFloat(this.edit.dailyBudget).toFixed(2),
                    total_budget: parseFloat(this.edit.totalBudget).toFixed(2),
                };

                axios.put(`/campaigns/${this.uid}`, body)
                .then((response) => {
                    this.loading = false;
                    this.$emit("sendMessage", "Campaign Updated successfully");
                    this.$refs.removeBtn.click();
                }).catch((err) =>{
                    this.loading = false;
                    this.$emit("sendError", "Campaign could not be updated");
                    this.$refs.removeBtn.click();
                });
            },
        },
        mounted(){
            this.uid = this.campaign.uid;
            this.edit.campaignName = this.campaign.name;
            this.edit.dailyBudget = this.campaign.daily_budget;
            this.edit.totalBudget = this.campaign.total_budget;
            this.edit.startingDate = this.campaign.date_from;
            this.edit.endDate = this.campaign.date_to;
        }
    }
</script>
