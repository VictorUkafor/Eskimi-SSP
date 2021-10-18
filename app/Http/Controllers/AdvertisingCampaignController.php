<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\AdvertisingCampaign;
use Auth, Validator, Storage;

class AdvertisingCampaignController extends Controller
{   
    
    private function generateUniqueUUID()
    {
    
        $uids = AdvertisingCampaign::pluck('uid')->toArray();
    
        $uid = Str::uuid()->toString();
    
        for ($x = 0; $x < count($uids); $x++) {
        if ($uids[$x] === $uid) {
            $uid = Str::uuid()->toString();
            $x = 0;
        }
        }
    
        return $uid;
    }


    public function create(Request $request){
        try {

            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'date_to' => 'required|date',
                'date_from' => 'required|date',
                'daily_budget' => 'required|numeric',
                'total_budget' => 'required|numeric',
                'images' => 'required|image',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                    'status' => false,
                ], 400);
            }


            $uid = $this->generateUniqueUUID(); 

            $images = [];

            foreach ($request->file('images') as $image) {
                $url = Storage::putFile("campaigns/$uid", $image);

                array_push($images, $url);
            }

            $campaign = new AdvertisingCampaign;
            $campaign->user_id = Auth::id();
            $campaign->uid = $uid;
            $campaign->name = $request->name;
            $campaign->date_to = $request->date_to;
            $campaign->date_from = $request->date_from;
            $campaign->daily_budget = $request->daily_budget;
            $campaign->totaly_budget = $request->total_budget; 
            $campaign->creative_upload = $images;            
            
            return response()->json([
                'project' => $campaign,
                'message' => 'Campaign added successfully',
                'status' => true,
            ], 201);

        }catch(Exception $e){
            return response()->json([
                'message' => $e->message(),
                'status' => false,
            ], 500);
        }

    }
}
