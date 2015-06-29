<?php namespace App\Http\Controllers;

use App\Area;

class AreaController extends Controller {

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
    $areas = Area::all();
		return response()->json($areas, 200);
	}

  /**
   * Show the application dashboard to the user.
   *
   * @return Response
   */
  public function show($id)
  {
    $area = Area::findOrFail($id);
    return response()->json($area, 200);
  }

  /**
   * Show the application dashboard to the user.
   *
   * @return Response
   */
  public function locations($id)
  {
    $locations = Area::find($id)->locations;
    return response()->json($locations, 200);
  }

}
