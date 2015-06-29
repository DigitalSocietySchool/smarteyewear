<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Checklist;
use App\ChecklistItem;
use App\Category;
use App\Subcategory;
use App\Location;
use App\SubcategoryLocation;
use App\Randomlist;

use App\Events\newChecklistEntry;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use DB;
use Log;
use LRedis;
use Event;
use Storage;

class ChecklistController extends Controller {

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

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
    $checklists = Checklist::orderBy("created_at", 'desc')->with('items')->get();
    $data = [
      "checklists" => $checklists,
      "count"      => count($checklists)
    ];

		return view('checklists', $data);
	}

  /**
   * Show the application dashboard to the user.
   *
   * @return Response
   */
  public function getProblems()
  {
    $checklists = Checklist::orderBy("created_at", 'desc')->with('items')->get();
    $checklist = $checklists[0];
    $items = $checklist->items;
    $grades = ["AA", "A", "B", "C", "D"];
    $coords = [];
    foreach ($items as $item) {
      $grade = $item->grade;
      $subcatlocation = $item->subcategorylocation;
      $isProblem = array_search($grade, $grades) > array_search($subcatlocation->accepted_grade, $grades);
      if ( $isProblem ){
        $location = $subcatlocation->location;
        $x1 = (float) explode(',', $location->top_left)[1];
        $x2 = (float) explode(',', $location->top_right)[1];
        $y1 = (float) explode(',', $location->top_left)[0];
        $y2 = (float) explode(',', $location->bottom_left)[0];
        $center = [
          "x" => $x1 + (($x2 - $x1) / 2),
          "y" => $y1 + (($y2 - $y1) / 2)
        ];
        $coords[] = $center;
      }
    }

    return response()->json($coords, 200);
  }

  /**
   * Show the application dashboard to the user.
   *
   * @return Response
   */
  public function show($id)
  {
    $checklist = Checklist::findOrFail($id);
    return response()->json($checklist, 200);
  }

  /**
   * Show the application dashboard to the user.
   *
   * @return Response
   */
  public function create(Request $request)
  {
    $items = [];
    $data = $request->all();

    $checklist = new Checklist;
    $checklist_data = [
      'user_id' => $data['user_id'],
      'location_id' => $data['location_id']
    ];
    $checklist->fill($checklist_data)->save();

    foreach($data['data'] as $item) {
      $subcategory = Subcategory::where('code', '=', $item['code'])->firstOrFail();
      $subcatlocation = $subcategory->subcategorylocations()->where('location_id', '=', $data['location_id'])->first();
      if( count($subcatlocation) ){
        $url = 0;
        $storage = Storage::disk('checklist');

        if( isset($item['image']) ){
          $image_base64 = $item['image'];
          $image = base64_decode($image_base64);
          $upload_path =  $checklist->id;
          $filename = 'image-' . $subcatlocation->id.'.png';
          if( !file_exists('checklist_images/'.$upload_path) ){
            $storage->makeDirectory($upload_path);
          }
          $url = $upload_path . '/' . $filename;
          $storage->put($url, $image);
          chmod('checklist_images/'.$url, 0775);
        }
        $items[] = array(
        'checklist_id' => $checklist->id,
        'grade' => $item['rating'], 
        'subcategory_id' => $subcategory->id,
        'subcategorylocation_id'  => $subcatlocation->id,
        'image_url' => $url
        );
      }
    }

    if( count($items) ){
      DB::table('checklist_items')->insert($items);
    }
    else{
      $checklist->forceDelete();
    }

    $return = (object) array(
      "status" => "OK"
    );

    // Trigger event to update the route
    Event::fire(new newChecklistEntry($checklist));
    return response()->json($return, 200);
  }

  public function localCreate($data){
    $items = [];

    $checklist = new Checklist;
    $checklist_data = [
      'user_id' => $data['user_id'],
      'location_id' => $data['location_id']
    ];
    $checklist->fill($checklist_data)->save();

    foreach($data['data'] as $item) {
      $subcategory = Subcategory::where('code', '=', $item['code'])->firstOrFail();
      $subcatlocation = $subcategory->subcategorylocations()->where('location_id', '=', $data['location_id'])->first();
      if( count($subcatlocation) ){
        $items[] = array(
        'checklist_id' => $checklist->id,
        'grade' => $item['rating'], 
        'subcategory_id' => $subcategory->id,
        'subcategorylocation_id'  => $subcatlocation->id);
      }
    }
    
    DB::table('checklist_items')->insert($items);
    $response = Event::fire(new newChecklistEntry($checklist));
    // trigger socket event
    //this->getNewChecklist($checklist->id);
    //return response()->json($return, 200);
  }

   /**
   * Show the application dashboard to the user.
   *
   * @return Response
   */
  public function addsubcats()
  {
    $json = file_get_contents('http://145.92.221.8/glasskit/public/112_optim.json');
    $obj = json_decode($json, true);
    $subcategories = array();
    $tempData = array();
    foreach($obj['list'] as $key => $item) {
      foreach($item as $rowKey => $row) {
        $tempData[] = array(
          'category_id' => $key,
          'code' => $rowKey,
          'name_nl' => $row
          );
      }
    }
    DB::table('subcategories')->insert($tempData);
    return response()->json($tempData, 200);
  }

     /**
   * Show the application dashboard to the user.
   *
   * @return Response
   */
  public function addsubcatlocation()
  {
    $json = file_get_contents('http://145.92.221.8/glasskit/public/subcat_location.json');
    $obj = json_decode($json, true);
    $subcategories = array();
    $tempData = array();
    foreach($obj as $key => $item) {
      $location = Location::where('name', '=', $key)->first();
      if(!empty($location)) {
        foreach($item as $rowKey => $row) {
          $subcategory = SubCategory::where('code', '=', $rowKey)->first();
          if(!empty($subcategory)) {
            $tempData[] = array(
            'subcategory_id' => $subcategory['id'],
            'accepted_grade' => $row,
            'location_id' => $location->id,
            );
          } else {
            $tempData[] = $rowKey;
          }
        }
      }
    }
  DB::table('locations_subcategory')->insert($tempData);
    return response()->json($tempData, 200);
  }
     /**
   * Show the application dashboard to the user.
   *
   * @return Response
   */
  public function getNewChecklist($id=null)
  { 
    $locations = ["63vv","66vv","68vv","70vv","71ww","72ww","72xx","72yy","73nn","73oo","73pp","73qq","73ww","73yy","74nn","74oo","74pp","74uu","74xx","75oo","75rr","75ww","75xx","76rr","76uu","76ww","76xx","77rr","77tt","77vv","77ww","77xx","77yy","78rr","78uu","78vv","78ww"];
    $randomlist = Randomlist::where("completed_at", 0)->first();
    $location = $randomlist->locations()->where('visited', 0)->with('location')->get();
    foreach( $location as $loc ){
      if( in_array($loc->location->name, $locations) ){
        $location = $loc;
        break;
      }
    }
    $subcategories = $loc->location->subcategories;
    $data = [
      "user_id"       => \Auth::user()->id,
      "location_id"   => $loc->location->id,
      "data"          => []
    ];
    foreach( $subcategories as $subcat ){
      $check = [
        "code"    => $subcat->code,
        "rating"  => "C"
      ];
      array_push($data['data'], $check);
    }
    // $subcategories = $location->subcategories;
    $this->localCreate($data);
    return response()->json($data, 200);
    // Fire new entry event
    // return response()->json($response, 200);
  }

  public function generateDemoItems(){
    $items = [];

    for($i=0;$i<1000; $i++){
      $id = rand( 500, 700);
      $subcatlocation = Subcategorylocation::find($id);
      $data = [
        "checklist_id" => 1,
        "subcategory_id"  => $subcatlocation->subcategory_id,
        "subcategorylocation_id"  => $id,
        "grade"     => "C",
        "image_url" => "1/image-860.png"
      ];

      $items[] = $data;
    }

    DB::table('checklist_items')->insert($items);

  }

}
