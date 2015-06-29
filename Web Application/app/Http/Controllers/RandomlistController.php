<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Area;
use App\Location;
use App\SubcategoryLocation;
use App\Randomlist;
use App\User;
use App\Checklist;

use DB;
use Auth;
use Log;

use Carbon\Carbon;

class RandomlistController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

  public function todo()
  {
    $randomlist = Randomlist::firstOrFail();
    $locations = $randomlist->getLocations()->where(['user_id' => Auth::user()->id, 'visited' => 0])->get();
    $locationCount = count($locations);
    $area_id = $locations[1]->area_id;
    $areas = ["112A", "112B", "112C", "112D"];
    $locationList = ["63vv","66vv","68vv","70vv","71ww","72ww","72xx","72yy","73nn","73oo","73pp","73qq","73ww","73yy","74nn","74oo","74pp","74uu","74xx","75oo","75rr","75ww","75xx","76rr","76uu","76ww","76xx","77rr","77tt","77vv","77ww","77xx","77yy","78rr","78uu","78vv","78ww"];
    $list = [];
    $current_item = [];
    foreach( $locations as $location ){
      $areaName = Area::find($location->area_id)->name;
        if( (in_array($location->name, $locationList) > 0 AND $areaName == "112A") OR ($areaName != "112A")){
          if( !isset($list[$areaName]) ){
            $list[$areaName] = [];
          }
          array_push($list[$areaName], $location);
        }
    }
    $data = [
      'todo_list' => $list,
      'count'     => $locationCount
    ];
    return view('todo', $data);
  }

  public function updated(Request $request)
  {
    $data = $request->all();
    $locationCount = $data['locationCount'];
    $randomlist = Randomlist::where('completed_at', 0)->first();
    $items = $randomlist->locations()->where(['visited' => 0, 'user_id' => \Auth::user()->id])->get();
    $response = ["updated"  => False ];
    if( count( $items) < $locationCount ){
      $lastSubmit = Checklist::orderBy("created_at", "desc")->first();
      $response = [
        "updated" => True,
        "location"  => $lastSubmit->location->name
      ];
    }
    return response()->json($response, 200);
  }

  /**
   * Show the application dashboard to the user.
   *
   * @return Response
   */
  public function generateByGroup($id)
  {
    $area = Area::findOrFail($id);
    return response()->json($area, 200);
  }

  /**
   * Show the application dashboard to the user.
   *
   * @return Response
   */
  public function generateListByArea()
  {
    $areas = Area::all();
    $listOfLocations = [];
    $randomlist = new Randomlist();
    $randomlist_data = [
      "start_date"  => Carbon::now()->toDateTimeString(),
      "end_date"    => Carbon::now()->addMonth()
    ];

    $randomlist->fill($randomlist_data)->save();

    foreach( $areas as $area ){
      $locations = $area->locations->all();
      $locationCount = count($locations);
      $amountToGet = ceil($locationCount * 0.1);
      if( $amountToGet > 0 ){
        shuffle($locations);
        $list = array_rand($locations, $amountToGet);
        for($i=1;$i<count($list);$i++){
          $location = $locations[$list[$i]];

          array_push($listOfLocations, [
            "randomlist_id"   => $randomlist->id,
            "location_id"     => $location->id,
            "user_id"         => 3,
            "visited"         => 0
          ]);
        }
      }
    }
    DB::table('location_randomlist')->insert($listOfLocations);
    return response()->json($listOfLocations, 200);
  }

  /**
   * Show the application dashboard to the user.
   *
   * @return Response
   */
  public function generateListsByArea(){
    // Define groups of areas
    $regions = [
      "0" =>    ["112A", "112B", "112C", "112D", "112E", "112F"],
      "1" =>    ["101", "102", "103", "104", "105", "106"],
      "2" =>    ["107", "108", "109", "110", "111", "121", "122", "123"]
    ];
    // Get the areas for each group
    $regionAreas = [
      "0"   =>  Area::whereIn("name", $regions["0"])->get(),
      "1"   =>  Area::whereIn("name", $regions["1"])->get(),
      "2"   =>  Area::whereIn("name", $regions["2"])->get()
    ];
    // Get users
    $users = User::where('role', 'field')->get();
    // To be able to shuffle we need to change to array
    $users = iterator_to_array($users);
    // Shuffle the users so that they don't get the same region every month
    shuffle($users);
    // Create a new random list
    $randomlist = Randomlist::create([
        "start_date"  => Carbon::now()->toDateTimeString(),
        "end_date"    => Carbon::now()->addMonth()
    ]);
    // For each region -> get areas locations and create a new checklist
    foreach( $regionAreas as $key=>$areas ){
      $locations = [];
      $randomListLocations = [];
      $user = $users[$key];
      foreach( $areas as $area ){
        $areaLocations  = $area->locations->all();
        $locations = array_merge($locations, $areaLocations);
      };
      $locationCount  = count($locations);
      $amountToGet    = ceil($locationCount * 0.1);
      shuffle($locations);
      $list = array_rand($locations, $amountToGet);
      for($i=1;$i<count($list);$i++){
        $location = $locations[$list[$i]];
        array_push($randomListLocations, [
          "randomlist_id"   => $randomlist->id,
          "location_id"     => $location->id,
          "user_id"         => $user->id,
          "visited"         => 0
        ]);
      };
      DB::table('location_randomlist')->insert($randomListLocations);
    }
    return response()->json("ok", 200);
  }

  /**
   * Show the application dashboard to the user.
   *
   * @return Response
   */
  public function getByLocation()
  {
    $locationsToUse = SubcategoryLocation::all();
    $dataToReturn = array();
    $locations = array();
    foreach($locationsToUse as $subcatlocation) {
      $locations[] = $subcatlocation->location;
    }
    foreach($locations as $location) {
      $dataToReturn[$location->area_id][] = $location;
    }
    return response()->json($dataToReturn, 200);
  }

}
