<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Location;
use App\Subcategory;
use App\SubcategoryLocation;
use App\Category;

use DB;
use Illuminate\Http\Request;

class LocationController extends Controller {

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

  public function manage()
  {
    return view("locationmanager");
  }

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
    $locations = Location::all();
		return response()->json($locations, 200);
	}

  /**
   * Show the application dashboard to the user.
   *
   * @return Response
   */
  public function show($id)
  {
    $location = Location::findOrFail($id);
    return response()->json($location, 200);
  }

  /**
   * Show the application dashboard to the user.
   *
   * @return Response
   */
  public function getArea($id)
  {
    $area = Location::find($id)->area;
    return response()->json($area, 200);
  }

  /**
   * Show the application dashboard to the user.
   *
   * @return Response
   */
  public function getAllLocations()
  {
    $locations = Location::all();

    return response()->json($locations, 200);
  }

  /**
   * Show the application dashboard to the user.
   *
   * @return Response
   */
  public function getLocationByName($id)
  {
    $location = Location::where('name', '=', $id)->first();
    if(empty($location)) {
      $location = null;
    }
    return response()->json($location, 200);
  }

  public function searchLocationByName(Request $request)
  {
    $name = $request->input('query');
    $locations = Location::where('area_id', '15')->where('name', 'LIKE', '%'. $name .'%')->limit(5)->get();
    $suggestions = [];

    foreach( $locations as $location) 
    {
      $a = [
        "value"  => $location->name,
        "data"    => $location->id
      ];
      array_push($suggestions, $a);
    };

    $returnJSON = [
      "suggestions" => $suggestions
    ];

    return response()->json($returnJSON, 200);
  }

  /**
   * Show the application dashboard to the user.
   *
   * @return Response
   */
  // public function getSubcategoryLocationByName($id)
  // {
  //   $subcategorylocations = Location::find($id)->subcategorylocations;
  //   $list = [];
  //   if(empty($subcategorylocations)) {
  //     $subcategorylocations = null;
  //   }
  //   foreach( $subcategorylocations as $subcatlocation ){
  //     $subcat = Subcategory::find($subcatlocation->subcategory_id);
  //     $cat = $subcat->category;
  //     if( !isset($list[$cat->name]) ){
  //       $list[$cat->name] = $cat;
  //       $list[$cat->name]['subcategorie'] = [];
  //     }
  //     array_push($list[$cat->name]['subcategorie'], $subcat);
  //   }
  //   return response()->json($list, 200);
  // }

  public function getSubcategoryLocationByName($id)
  {
    $users = Category::with(['subcategories.subcategorylocations' => function($query) use ($id)
    {
        $query->where('location_id', '=', $id);
    }])->orderBy('id', 'asc')->get();
    foreach($users as $key => $item) {
      if(count($item->subcategories) == 0) {
        unset($users[$key]);
      } else {
        foreach($item->subcategories as $subcat) {
          if(count($subcat->subcategorylocations) == 0) {
            unset($users[$key]);
          }
        }
      }
    }
    return response()->json($users, 200);
  }

  /**
   * Show the application dashboard to the user.
   *
   * @return Response
   */
  public function getGrades($id)
  {
    $location = Location::findOrFail($id);
    //$subcategories = $location->subcategories()->join("location_subcategory", "subcategory.id", "=", "location_subcategory.subcategory_id");
    $subcategories = DB::table("location_subcategory")->where("location_id", $id)->join("subcategories", "location_subcategory.subcategory_id", "=", "subcategories.id")->select("subcategories.code", "location_subcategory.accepted_grade")->get();
    $subcategories = [
      "grades" => $subcategories
    ];
    return response()->json($subcategories, 200);
  }

  public function showSubcatLocations(){
    //$subcatlocations = SubcategoryLocation::all()->groupBy("location_id");
    $subcatlocations = SubcategoryLocation::select('name')->distinct()->join('locations', 'location_subcategory.location_id', '=', 'locations.id')->get();
    $locations = [];
    foreach($subcatlocations as $subcatlocation ){
      $locations [] = $subcatlocation->name;
    }
    return response()->json($locations, 200);
  }

}
