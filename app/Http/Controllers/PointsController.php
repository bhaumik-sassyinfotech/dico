<?php
namespace App\Http\Controllers;

use App\CompanyPoint;
use App\Point;
use App\UserActivity;
use Auth;
use Carbon;
use Config;
use DB;
use Helpers;
use Illuminate\Http\Request;
use Redirect;
use Yajra\Datatables\Datatables;

class PointsController extends Controller {

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	//protected $table = 'room';
	public $folder;
	public function __construct(Request $request) {
		$this->middleware(function ($request, $next) {
			if (Auth::user()->role_id == 3) {
				return redirect('/index');
			}
			if (Auth::user()->role_id == 1) {
				$this->folder = 'superadmin';
			} else if (Auth::user()->role_id == 2) {
				$this->folder = 'companyadmin';
			} else if (Auth::user()->role_id == 3) {
				$this->folder = 'employee';
			}
			return $next($request);
		});

	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		//dd("here");
		return view($this->folder . '.points.index');
	}

	public function create() {
		return view($this->folder . '.points.create');
	}

	public function store(Request $request) {
		try {
			if (Auth::user()) {
				$this->validate($request, [
					'activity' => 'required|max:255|unique:point_master,activity',
					'points' => 'required|numeric',
				]);

				if (Auth::user()->role_id == 1) {
					$points = new Point;
				} else {
					$points = new CompanyPoint;
					$points->company_id = Auth::user()->company_id;
				}
				$points->activity = $request->input('activity');
				$points->points = $request->input('points');
				$points->notes = $request->input('notes');
				$points->created_at = Carbon\Carbon::now();
				if ($points->save()) {
					return redirect()->route('points.index')->with('success', 'Points ' . Config::get('constant.ADDED_MESSAGE'));
				} else {
					return redirect()->route('points.index')->with('err_msg', '' . Config::get('constant.TRY_MESSAGE'));
				}
			} else {
				return redirect('/index');
			}
		} catch (\exception $e) {
			return Redirect::back()->with('err_msg', $e->getMessage());
		}
	}

	public function edit($id) {
		// $obj = new Company;
		$id = Helpers::decode_url($id);
		if (Auth::user()) {
			if (Auth::user()->role_id == 1) {
				$points = new Point;
			} else {
				$points = new CompanyPoint;
			}
			$point = $points->where('id', $id)->first();
			return view($this->folder . '.points.edit', compact('point'));
		} else {
			return redirect('/index');
		}
	}

	public function update(Request $request, $id) {
		try {
			if (Auth::user()) {
				$this->validate($request, [
					'activity' => 'required|max:255|unique:point_master,activity,' . $id,
					'points' => 'required|numeric',
				]);
				$where = array('id' => $id);
				if (Auth::user()->role_id == 1) {
					$point = new Point;
				} else {
					$point = new CompanyPoint;
					$where['company_id'] = Auth::user()->company_id;
				}
				$postData = array('activity' => $request->input('activity'), 'points' => $request->input('points'), 'notes' => $request->input('notes'), 'updated_at' => Carbon\Carbon::now());

				$res = $point->where($where)->update($postData);
				if ($res) {
					return redirect()->route('points.index')->with('success', 'Points ' . Config::get('constant.UPDATE_MESSAGE'));
				} else {
					return redirect()->route('points.index')->with('err_msg', '' . Config::get('constant.TRY_MESSAGE'));
				}
			} else {
				return redirect('/index');
			}
		} catch (\exception $e) {
			return Redirect::back()->with('err_msg', $e->getMessage());
		}
	}

	public function get_points(Request $request) {
		//$company = new Company;
		//$res = $company->select('*',DB::raw('CASE WHEN allow_anonymous = "1" THEN "Yes" ELSE "No" END AS anonymous,CASE WHEN allow_add_admin = "1" THEN "Yes" ELSE "No" END AS add_admin'))
		//      ->whereNULL('deleted_at');
		if (Auth::user()) {
			if (Auth::user()->role_id == 1) {
				$points = new Point;
				$res = $points->select('*')->whereNULL('deleted_at');
			} else if (Auth::user()->role_id == 2) {
				$points = new CompanyPoint;
				$res = $points->select('*')->where('company_id', Auth::user()->company_id)->whereNULL('deleted_at');
			}
			return Datatables::of($res)->filter(function ($query) use ($request) {
				if ($request->has('activity')) {
					$query->where('activity', 'like', "%{$request->get('activity')}%");
				}
			})->addColumn('actions', function ($row) {
				return '<a href="' . route('points.edit', [Helpers::encode_url($row->id)]) . '" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
			})->rawColumns(['actions'])->make(true);
		} else {
			return redirect('/index');
		}
	}

	public function pointsIndex(Request $request) {

		return view($this->folder . '.points.pointsListing');
	}

	public function pointsListing(Request $request) {
		$pointsDataQuery = UserActivity::select(DB::raw('user_id ,activity_id , sum(points) as pts'));
		if ($request->has('user_id')) {
			$pointsDataQuery = $pointsDataQuery->where('user_id', $request->input('user_id'));
		}
		$pointsData = $pointsDataQuery->with('user_detail')->groupBy('activity_id', 'user_id')->get()->toArray();
		$points_listing = [];

		if (count($pointsData) && !empty($pointsData)) {
			foreach ($pointsData as $point) {

				$id = $point['user_detail']['id'];
				$role_id = $point['user_detail']['role_id'];
				if ($role_id > 1) {
					if (isset($points_listing[$id])) {
						$points_listing[$id]['activity'][$point['activity_id']] = array('user_id' => $point['user_id'],
							'activity_id' => $point['activity_id'],
							'points' => $point['pts']);
					} else {
						$points_listing[$id] = array('user_detail' => $point['user_detail']);
						$points_listing[$id]['activity'][$point['activity_id']] = array('user_id' => $point['user_id'],
							'activity_id' => $point['activity_id'],
							'points' => $point['pts']);
					}
				}
			}
		}
		// return $points_listing;
		$sum = 0;
		return Datatables::of($points_listing)->addColumn('name', function ($row) use (&$sum) {
			$sum = 0;
			return '<a href="' . url('view_profile/' . Helpers::encode_url($row['user_detail']['id'])) . '">' . $row['user_detail']['name'] . '</a>';
		})->addColumn('post', function ($row) use (&$sum) {
			$idea_points = 0;

			$idea_id = 1;

			if (isset($row['activity'][$idea_id])) {
				$idea_points = $row['activity'][$idea_id]['points'];
				$sum += $idea_points;
			}
			return '<p>' . $idea_points . '</p>';
		})->addColumn('approved', function ($row) use (&$sum) {
			$idea_points = 0;

			$idea_id = 5;

			if (isset($row['activity'][$idea_id])) {
				$idea_points = $row['activity'][$idea_id]['points'];
				$sum += $idea_points;
			}

			return '<p>' . $idea_points . '</p>';

		})->addColumn('answers', function ($row) use (&$sum) {
			$idea_points = 0;

			$idea_id = 7;

			if (isset($row['activity'][$idea_id])) {
				$idea_points = $row['activity'][$idea_id]['points'];
				$sum += $idea_points;
			}

			return '<p>' . $idea_points . '</p>';
		})->addColumn('solutions', function ($row) use (&$sum) {
			$idea_points = 0;

			$idea_id = 6;

			if (isset($row['activity'][$idea_id])) {
				$idea_points = $row['activity'][$idea_id]['points'];
				$sum += $idea_points;
			}

			return '<p>' . $idea_points . '</p>';
		})->addColumn('comments', function ($row) use (&$sum) {
			$idea_points = 0;

			$idea_id = 3;

			if (isset($row['activity'][$idea_id])) {
				$idea_points = $row['activity'][$idea_id]['points'];
				$sum += $idea_points;
			}

			return '<p>' . $idea_points . '</p>';
		})->addColumn('likes', function ($row) use (&$sum) {
			$idea_points = 0;

			$idea_id = 4;

			if (isset($row['activity'][$idea_id])) {
				$idea_points = $row['activity'][$idea_id]['points'];
				$sum += $idea_points;
			}

			return '<p>' . $idea_points . '</p>';
		})->addColumn('vote', function ($row) use (&$sum) {
			$idea_points = 0;

			$idea_id = 2;

			if (isset($row['activity'][$idea_id])) {
				$idea_points = $row['activity'][$idea_id]['points'];
				$sum += $idea_points;
			}

			return '<p>' . $idea_points . '</p>';
		})->addColumn('total', function ($row) use (&$sum) {

			return '<p>' . $sum . '</p>';
		})->rawColumns(['name', 'post', 'approved', 'answers', 'solutions', 'comments', 'likes', 'vote', 'total'])->make(true);
	}
}
?>
