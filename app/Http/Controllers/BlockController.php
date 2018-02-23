<?php

namespace App\Http\Controllers;

use Helpers;
use Illuminate\Http\Request;
use App\Blocks;
use DB;
use Illuminate\Support\Facades\Auth;
use Validator;
use Redirect;
use Config;
use Carbon;
use Yajra\Datatables\Datatables;

class BlockController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    //protected $table = 'room';
    public function __construct(Request $request) {
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role_id != 1) {
                return redirect('/index');
            }
            return $next($request);
        });
    }

    /**
     * Use the following function to get blog list design
     */
    public function index() {
        //dd("here");
        return view('superadmin.blog.index');
    }

    /**
     * Use the following function to get blog list from blog table
     */
    public function create() {
        
    }

    public function edit($id) {
        $id = Helpers::decode_url($id);
        $block = Blocks::findOrFail($id);
        return view('superadmin.blog.edit', compact('block'));
    }

    public function update(Request $request, $id) {
        try {
            
            $validator = Validator::make($request->all(), [
                        'slug_name' => 'required|unique:blocks,slug_name,' . $id,
                        'title' => 'required',
                        'description' => 'required'
            ]);
            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator)->withInput();
            }
           
            $block = Blocks::findOrFail($id);
            $block->title = $request->get('title', null);
            $block->slug_name = $request->get('slug_name', null);
            $block->description = $request->get('description', null);
            
            if ($block->save()) {
                return Redirect::route('blog.index')->with('success', __('label.adBlog') .' '. __('label.UPDATE_MESSAGE'));
            } else {
                return Redirect::route('blog.index')->with('error', '' .  __('label.UPDATE_MESSAGE'));
            }
        } catch (\exception $e) {
            dd($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function blockList(Request $request) {
        $block = new Blocks;
        $res = $block->select('*')
                        ->whereNULL('deleted_at');
        return Datatables::of($res)->addColumn('actions', function ( $row ) {
                    return '<a href="' . route('blog.edit', [Helpers::encode_url($row->id)]) . '" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                })->rawColumns(['actions','description','title'])->make(true);
    }

}

?>
