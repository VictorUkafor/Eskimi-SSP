<template>
    <form>
        <div class="form-group">
            <label for="exampleFormControlInput1">Campaign Name</label>
            <input type="text" class="form-control"
            placeholder="Campaign Name" v-model.trim="create.campaignName">

            <b-alert class="mt-2" show dismissible fade variant="danger" v-if="create.errors.campaignName">
                <a href="#" class="alert-link">{{create.errors.campaignName}}</a>
            </b-alert>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="exampleFormControlInput1">Daily Budget</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">$</div>
                    </div>
                    <input type="text" class="form-control" 
                    placeholder="Daily Budget" v-model="create.dailyBudget">
                </div>
            </div>
            <div class="form-group col-md-6">
                <label for="exampleFormControlInput1">Total Budget</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">$</div>
                    </div>
                    <input type="text" class="form-control" 
                    placeholder="Total Budget" v-model="create.totalBudget">
                </div>
            </div> 
        </div>

        <b-alert class="mt-1" show dismissible fade variant="danger" v-if="create.errors.totalBudget">
            <a href="#" class="alert-link">{{create.errors.totalBudget}}</a>
        </b-alert>

        <b-alert class="mt-1" show dismissible fade variant="danger" v-if="create.errors.dailyBudget">
            <a href="#" class="alert-link">{{create.errors.dailyBudget}}</a>
        </b-alert> 

        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="exampleFormControlInput1">Starting Date</label>
                <input type="date" class="form-control" 
                placeholder="Starting Date" v-model="create.startingDate">
            </div>
            <div class="form-group col-md-6">
                <label for="exampleFormControlInput1">End Date</label>
                <input type="date" class="form-control" 
                placeholder="Ending Date" v-model="create.endDate">
            </div>
        </div>

        <b-alert class="mt-1" show dismissible fade variant="danger" v-if="create.errors.endDate">
            <a href="#" class="alert-link">{{create.errors.endDate}}</a>
        </b-alert>

        <b-alert class="mt-1" show dismissible fade variant="danger" v-if="create.errors.startingDate">
            <a href="#" class="alert-link">{{create.errors.startingDate}}</a>
        </b-alert>

        <div class="form-group">
            <button type="button" @click="onPickFile" 
            class="btn btn-success" style="width:100%"
            >Click to upload banner images</button>
            
            <input type="file" style="display: none" ref="fileInput" 
            accept="image/*" @change="onFilePicked" multiple />
        </div>

        <b-alert class="mt-1" show dismissible fade variant="danger" v-if="create.errors.images">
            <a href="#" class="alert-link">{{create.errors.images}}</a>
        </b-alert>

        <div class="d-flex justify-content-end pt-2">
            <b-button class="mr-2" variant="secondary" 
            @click="$bvModal.hide('createModal')" ref="removeBtn2">Cancel</b-button>
            <button type="button" class="btn btn-primary" 
            @click="createCampaign">{{loading ? 'Loading...' : 'Create'}}</button>
        </div>                     
    </form>
</template>

<script>
   import axios from 'axios';

    export default {
        data(){
            return {
                loading: false,
                create: {
                    campaignName: "",
                    dailyBudget: "",
                    totalBudget: "",
                    startingDate: "",
                    endDate: "",
                    images: "",
                    errors: {
                        campaignName: "",
                        dailyBudget: "",
                        totalBudget: "",
                        startingDate: "",
                        endDate: "",  
                        images: ''                      
                    }
                },
            }
        },
        methods: {
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
            createCampaign(){
                const { 
                    campaignName, 
                    dailyBudget, 
                    totalBudget, 
                    startingDate, 
                    endDate,
                    images,
                } = this.create;

                const onlyNumbers = /^((\.\d+)|(\d+(\.\d+)?))$/;

                if(!campaignName){
                    this.create.errors.campaignName = 'Campaign name is required';
                    return
                }else{
                    this.create.errors.campaignName = '';
                }

                if(!dailyBudget){
                    this.create.errors.dailyBudget = 'Daily budget is required';
                    return
                }else{
                    this.create.errors.dailyBudget = '';
                }

                if(dailyBudget && !dailyBudget.match(onlyNumbers)){
                    this.create.errors.dailyBudget = 'Daily budget should contain only digits';
                    return
                }else{
                    this.create.errors.dailyBudget = '';
                }

                if(!totalBudget){
                    this.create.errors.totalBudget = 'Total budget is required';
                    return
                }else{
                    this.create.errors.totalBudget = '';
                }

                if(totalBudget && !totalBudget.match(onlyNumbers)){
                    this.create.errors.totalBudget = 'Total budget should contain only digits';
                    return
                }else{
                    this.create.errors.totalBudget = '';
                }

                if(parseFloat(dailyBudget) > parseFloat(totalBudget)){
                    this.create.errors.totalBudget = 'Total budget must be greater than daily budget';
                    return
                }else{
                    this.create.errors.totalBudget = '';
                }

                if(!startingDate){
                    this.create.errors.startingDate = 'Starting date is required';
                    return;
                }else{
                    this.create.errors.startingDate = '';
                }

                if(!endDate){
                    this.create.errors.endDate = 'End date is required';
                    return;
                }else{
                    this.create.errors.endDate = '';
                }

                if(!images.length){
                    this.create.errors.images = 'Please upload atleast one image';
                    return;
                }else{
                    this.create.errors.images = '';
                }

                console.log('daily', dailyBudget);

                this.loading = true;

                const body = new FormData();
                body.append("name", campaignName);
                body.append("date_to", endDate);
                body.append("date_from", startingDate);
                body.append("daily_budget", parseFloat(dailyBudget).toFixed(2));
                body.append("total_budget", parseFloat(totalBudget).toFixed(2));
                images.map(img => body.append("images[]", img));
                      
                axios.post('/campaigns', body, {
                    headers: { "Content-Type": "multipart/form-data" },
                })
                .then((response) => {
                    this.loading = false;
                    this.$emit("sendMessage", "Campaign added successfully");
                    this.$refs.removeBtn2.click();
                }).catch((err) => {
                    this.loading = false;
                    this.$emit("sendError", "Campaign could be created");
                    this.$refs.removeBtn2.click();
                });
            }

        },
    }
</script>
