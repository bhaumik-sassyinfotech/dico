<?php
    
    namespace App\Http\Controllers;
    
    use App\SecurityQuestion;
    use App\User;
    use App\UserSecurityQuestion;
    use Carbon\Carbon;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    
    class UserSecurityQuestionController extends Controller
    {
        /**
         * Display a listing of the resource.
         *
         * @return \Illuminate\Http\Response
         */
        public $folder;
    public function __construct(Request $request)
    {
        $this->middleware(function ($request, $next) {
            
            if(Auth::user()->role_id == 1) {
                $this->folder = 'superadmin';
            }else if(Auth::user()->role_id == 2) {
                $this->folder = 'companyadmin';
            }else if(Auth::user()->role_id == 3) {
                $this->folder = 'employee';
            }
            return $next($request);
        });
        
    }
        public function index()
        {
            //
        }
        
        /**
         * Show the form for creating a new resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function create()
        {
            //
        }
        
        /**
         * Store a newly created resource in storage.
         *
         * @param  \Illuminate\Http\Request $request
         *
         * @return \Illuminate\Http\Response
         */
        public function store(Request $request)
        {
            //
        }
        
        /**
         * Display the specified resource.
         *
         * @param  \App\UserSecurityQuestion $userSecurityQuestion
         *
         * @return \Illuminate\Http\Response
         */
        public function show(UserSecurityQuestion $userSecurityQuestion)
        {
            //
        }
        
        /**
         * Show the form for editing the specified resource.
         *
         * @param  \App\UserSecurityQuestion $userSecurityQuestion
         *
         * @return \Illuminate\Http\Response
         */
        public function edit(UserSecurityQuestion $userSecurityQuestion)
        {
            //
        }
        
        /**
         * Update the specified resource in storage.
         *
         * @param  \Illuminate\Http\Request  $request
         * @param  \App\UserSecurityQuestion $userSecurityQuestion
         *
         * @return \Illuminate\Http\Response
         */
        public function update(Request $request , UserSecurityQuestion $userSecurityQuestion)
        {
            //
        }
        
        /**
         * Remove the specified resource from storage.
         *
         * @param  \App\UserSecurityQuestion $userSecurityQuestion
         *
         * @return \Illuminate\Http\Response
         */
        public function destroy(UserSecurityQuestion $userSecurityQuestion)
        {
            //
        }
        
        public function firstLogin(Request $request)
        {
            if ( $request->isMethod('POST') )
            {
                $auth = Auth::user();
                
//                return $request;
                $now = Carbon::now();
                $data = [
                            [ 'question_id' => $request->question_1 , 'answer' => "$request->answer_1" , 'user_id' => $auth->id , 'created_at' => $now , 'updated_at' => $now ] ,
                            [ 'question_id' => $request->question_2 , 'answer' => "$request->answer_2" , 'user_id' => $auth->id , 'created_at' => $now , 'updated_at' => $now ] ,
                            [ 'question_id' => $request->question_3 , 'answer' => "$request->answer_3" , 'user_id' => $auth->id , 'created_at' => $now , 'updated_at' => $now ]
                        ];
                $result = UserSecurityQuestion::insert($data);
                if($result)
                {
                    $user              = User::find($auth->id);
                    $user->first_login = 1;
                    $file = $request->file('profile_picture');
//                    dd($request->all());
                    if ( $file != "" )
                    {
                        $fileName        = $file->getClientOriginalName();
                        $extension       = $file->getClientOriginalExtension() ? : 'png';
                        $folderName      = 'uploads'.DIRECTORY_SEPARATOR.'profile_pic'.DIRECTORY_SEPARATOR;
                        $destinationPath = public_path($folderName);
                        $safeName        = str_random(10).'.'.$extension;
//                        dd($destinationPath);
                        $file->move($destinationPath, $safeName);
                        $user->profile_image = $safeName;
                    }
                    $user->save();
                    return redirect()->route('company.index');
                }
            }
            $questions = SecurityQuestion::all();
            
            return view($this->folder.'.security_question.add' , compact('questions'));
        }
    }
