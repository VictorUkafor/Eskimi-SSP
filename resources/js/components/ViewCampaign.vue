<template>
    <div>
        <b-carousel
            id="carousel-1"
            :interval="4000"
            controls
            indicators
            background="#ababab"
            img-width="1024"
            img-height="480"
            style="text-shadow: 1px 1px 2px #333;"
        >
            <b-carousel-slide v-for="(url, index) in campaign.creative_upload" :key="index"
            :img-src="`/get-media?url=${url}`">
            </b-carousel-slide>
        </b-carousel>

        <div class="pt-3">
            <div class="form-group">
                <label for="exampleFormControlInput1"><b>Campaign Name</b></label>
                <p>{{campaign.name}}</p>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="exampleFormControlInput1"><b>Daily Budget</b></label>
                    <p>${{campaign.daily_budget}}</p>
                </div>
                <div class="form-group col-md-6">
                    <label for="exampleFormControlInput1"><b>Total Budget</b></label>
                    <p>${{campaign.total_budget}}</p>
                </div>                   
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="exampleFormControlInput1"><b>Starting Date</b></label>
                    <p>{{campaign.date_from}}</p>
                </div>
                <div class="form-group col-md-6">
                    <label for="exampleFormControlInput1"><b>Ending Date</b></label>
                    <p>{{campaign.date_to}}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        props: ["campaign"],
    }
</script>
