<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

class HomeController extends Controller {
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	//protected $table = 'room';
	public function __construct(Request $request) {
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request) {
		//dd("here");
		// dd($request->session()->all());
		// return redirect()->route('test1')->with('sess', 'demo Session message');
		if (Auth::check()) {
			$currUser = Auth::user();
			if ($currUser->role_id == 1) {
				return redirect()->route('company.index');
			} else if ($currUser->role_id == 2) {
				return redirect()->route('user.index');
			} else if ($currUser->role_id == 3) {
				return redirect()->route('post.index');
			}
		}

		return view('template.default');
	}

	public function test(Request $request) {
		return redirect()->route('test1');
	}

	public function test1(Request $request) {
		dd($request->session()->all());
	}
}
