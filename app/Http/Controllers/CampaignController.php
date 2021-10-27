<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use App\Models\AdvertisingCampaign;
use Auth, Validator, Storage, Log;

class CampaignController extends Controller
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
                'date_to' => 'required',
                'date_from' => 'required',
                'daily_budget' => 'required|numeric',
                'total_budget' => 'required|numeric',
                'images' => 'required',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg',

            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                    'status' => false,
                ], 400);
            }

            $uid = $this->generateUniqueUUID(); 

            foreach($request->images as $image) {
                $url = Storage::putFile("campaigns/$uid", $image);
                $images[] = $url;
            }

            $campaign = new AdvertisingCampaign;
            $campaign->user_id = Auth::id();
            $campaign->uid = $uid;
            $campaign->name = $request->name;
            $campaign->date_to = $request->date_to;
            $campaign->date_from = $request->date_from;
            $campaign->daily_budget = $request->daily_budget;
            $campaign->total_budget = $request->total_budget; 
            $campaign->creative_upload = $images; 
            $campaign->save();           
            
            return response()->json([
                'campaign' => $campaign,
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


    public function get(){
        try {

            $campaigns = AdvertisingCampaign::where('user_id', Auth::id())
            ->orderBy('id', 'desc')->paginate(10);           
            
            return response()->json([
                'campaigns' => $campaigns,
                'status' => true,
            ], 200);

        }catch(Exception $e){
            return response()->json([
                'message' => $e->message(),
                'status' => false,
            ], 500);
        }
    }


    public function update(Request $request, $uid){
        try {

            $campaign = AdvertisingCampaign::where([
                'uid' => $uid,
                'user_id' => Auth::id()
            ])->first();  

            if(!$campaign){
                return response()->json([
                    'errors' => 'Campaign could not be found',
                    'status' => false,
                ], 404);                
            }

            $campaign->name = $request->name ? $request->name : $campaign->name;
            $campaign->date_to = $request->date_to ? $request->date_to : $campaign->date_to;
            $campaign->date_from = $request->date_from ? $request->date_from : $campaign->date_from;
            $campaign->daily_budget = $request->daily_budget ? $request->daily_budget : $campaign->daily_budget;
            $campaign->total_budget = $request->total_budget ? $request->total_budget : $campaign->total_budget; 
            $campaign->save();           
            
            return response()->json([
                'campaign' => $campaign,
                'message' => 'Campaign updated successfully',
                'status' => true,
            ], 200);

        }catch(Exception $e){
            return response()->json([
                'message' => $e->message(),
                'status' => false,
            ], 500);
        }
    }


    public function deleteImage(Request $request){
        try {

            $validator = Validator::make($request->all(), [
                'image_path' => 'required',
                'uid' => 'required|string|exists:advertising_campaigns,uid',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                    'status' => false,
                ], 400);
            }

            $campaign = AdvertisingCampaign::where([
                'uid' => $request->uid,
                'user_id', Auth::id()
            ])->first();
            
            if(!$campaign){
                return response()->json([
                    'errors' => 'Campaign could not be found',
                    'status' => false,
                ], 404);                
            }

            $images = $campaign->creative_upload;
            $updated_images = [];

            if(in_array($request->image_path, $images)){
                $remove = Storage::delete($request->image_path);

                if($remove){
                    foreach($images as $image){
                        if($image != $request->image_path){
                            array_push($updated_images, $image);
                        }
                    }
                }

            } else {

                return response()->json([
                    'message' => 'Image could not be found',
                    'status' => false,
                ], 404);
            }

            $campaign->creative_upload = count($updated_images) ? 
            $updated_images : $request->creative_upload;

            $campaign->save();           
            
            return response()->json([
                'campaign' => $campaign,
                'message' => 'Image removed successfully',
                'status' => true,
            ], 200);

        }catch(Exception $e){
            return response()->json([
                'message' => $e->message(),
                'status' => false,
            ], 500);
        }
    }


    public function addImage(Request $request){
        try {

            $validator = Validator::make($request->all(), [
                'image' => 'required|image',
                'uid' => 'required|string|exists:advertising_campaigns,uid',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                    'status' => false,
                ], 400);
            }

            $campaign = AdvertisingCampaign::where([
                'uid' => $request->uid,
                'user_id', Auth::id()
            ])->first();  

            if(!$campaign){
                return response()->json([
                    'errors' => 'Campaign could not be found',
                    'status' => false,
                ], 404);                
            }

            $images = $campaign->creative_upload;
            if($request->file('image')) {
                $url = Storage::putFile("campaigns/$campaign->uid", $request->file('image'));
                array_push($images, $url);
            }

            $campaign->creative_upload = $images;
            $campaign->save();           
            
            return response()->json([
                'campaign' => $campaign,
                'message' => 'Image added successfully',
                'status' => true,
            ], 200);

        }catch(Exception $e){
            return response()->json([
                'message' => $e->message(),
                'status' => false,
            ], 500);
        }
    }


    public function getMedia(Request $request){
        $file = Storage::get($request->url);
        return new Response($file, 200);
    }

}
