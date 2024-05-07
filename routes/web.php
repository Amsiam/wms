<?php

use App\Models\WasteBin;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

use function Pest\Laravel\json;

Route::get('/', function () {
    return redirect("/admin");
});


Route::get("/waste-bin/{id}/{fill}",function(Request $request,$id,$fill) {

    if(!$id){
        return response()->json([
            "success" => false,
            "message" => "Bin number is empty"
        ]);
    }

    if(!$fill){
        return response()->json([
            "success" => false,
            "message" => "Fill percentage is empty"
        ]);
    }

   $wasteBin =  WasteBin::where("bin_number",$id)->first();

   if(!$wasteBin){
    return response()->json([
        "success" => false,
        "message" => "Waste Bin not found"
    ]);
   }

   $wasteBin->update(["fill" => $fill, "last_update" => now()]);


    return response()->json([
        "success" => true
    ]);

});
