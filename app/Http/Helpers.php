<?php
    
    use App\EmailTemplate;
    use App\PostView;
    use App\User;

//use DB;
    
    class Helpers
    {
        
        
        public static function getProfilePic($user_id)
        {
            $user = DB::table('users')
                ->join('user_profile' , 'users.id' , '=' , 'user_profile.user_id')
                ->select('users.*' , 'user_profile.*')
                ->where('users.id' , $user_id)
                ->first();
            
            return $user;
        }
        
        public static function isAdmin()
        {
            if ( Auth::guard('admin')->check() )
            {
                if ( Auth::guard('admin')->user()->is_admin == 1 )
                {
                    return Auth::guard('admin')->user()->role_id;
                }
                throw new Exception('Please admin login first.');
            }
        }
        
        public static function getAdminSetting($key = null)
        {
            if ( $key != null )
            {
                $key = DB::table('settings')->select('value')->where('key' , $key)->first();
                if ( !is_object($key) )
                {
                    return "";
                }
                
                return $key->value;
            }
            throw new Exception('Block identifier not define');
        }
        
        public static function getmeta($url)
        {
//$meta = Meta::findOrFail($url);
            $meta = DB::table('metas')->select('*')->where('url' , $url)->first();
//dd($meta);
            /* $data = array();
              $data['name'] = $user->fullname.' '.$user->lastname;
              $data['email'] = $user->email;
              $data['profile_pic'] = $user->profile_pic;
              $data['id'] = $id; */
            
            return $meta;
        }
        
        public static function checkLogin()
        {
            if ( Auth::check() )
            {
                $login = 1;
            } else
            {
                $login = 0;
            }
            
            return $login;
        }
        
        
        public static function getAdminPermission($key = null , $id = null)
        {
            
            if ( $key != null )
            {
                if ( is_null($id) )
                {
                    $id = Auth::guard('admin')->user()->role_id;
                }
                $my_val = DB::table('roles')->select('permissions')->where('id' , $id)->first();
                $my_val = json_decode($my_val->permissions , TRUE);
                //echo $my_val[$key]; die;
                if ( is_array($my_val) )
                {
                    if ( isset($my_val[ $key ]) && $my_val[ $key ] != '' )
                    {
                        return $my_val[ $key ];
                    } else
                    {
                        return 'false';
                    }
                }
                throw new Exception('User permission not define');
            }
        }
        
        
        public static function parse_template($template , $data , $allowed_fields)
        {
            $allowed_fields = rtrim(trim($allowed_fields),",");
            $allowed_fields = explode("," , $allowed_fields);
            if ( $template == '' )
            {
                return FALSE;
            }
            $l_delim = '{';
            $r_delim = '}';
            $errors  = [];
//            dd($data);
            foreach ( $data as $key => $val )
            {
//                echo var_dump($key)."<br>";
                if ( !is_array($val) && in_array($key , $allowed_fields) )
                {
                    $template = str_replace($l_delim . $key . $r_delim , (string) $val , $template);
                } else
                {
                    $errors[] = $key . " is not in the set of allowed fields";
//                    $template = str_replace($l_delim . $key . $r_delim , (string) $key , $template);
                }
            }
            $data = [ 'data' => $template , 'errors' => $errors ];
            
            return $data;
        }
        
        public static function getEmailTemplateBySlug($slug)
        {
            return EmailTemplate::where('slug' , $slug)->first();
        }
        
        /*public static function isAdmin() {
            if (Auth::check()) {
                if (Auth::user()->is_admin == 1) {
                    return Auth::user()->role_id;
                }
                throw new Exception('Please login first.');
            }
        }*/
        
        
        public static function postViews($post_id , $user_id)
        {
            PostView::firstOrCreate([ 'user_id' => $user_id , 'post_id' => $post_id , 'visitor_ip' => request()->ip() ]);
            
            return PostView::select(DB::RAW("count('id') as views"))->where('post_id' , $post_id)->groupBy('post_id')->first();
        }
        
        public static function testMail()
        {
            $to        = 'bhaumik.sassyinfotech@gmail.com';
            $from      = 'testacc2016@gmail.com';
            $reply_to  = 'bhaumik.sassyinfotech@gmail.com';
            $from_name = 'Test';
            $subject   = 'Test';
            $content   = 'Test';
            $path      = 'template/mail';
            
            $data = array( 'to'        => $to ,
                           'from'      => $from ,
                           'reply_to'  => $reply_to ,
                           'from_name' => $from_name ,
                           'subject'   => $subject ,
                           'message'   => $content ,
                           'path'      => $path ,
            );
            
            return Mail::send($path , [ 'data' => $data ] , function ($message) use ($data) {
                $message->subject($data[ 'subject' ]);
                $message->from($data[ 'from' ] , $data[ 'from_name' ]);
                //$message->bcc(array('rajesh.sassyinfotech@gmail.com','dilip.sassyinfotech@gmail.com','kishan.sassyinfotech@gmail.com'));
//                $message->to(array( 'rajesh.sassyinfotech@gmail.com' , 'dilip.sassyinfotech@gmail.com' , 'kishan.sassyinfotech@gmail.com' , 'divyesh.sassyinfotech@gmail.com' ));
                $message->to($data[ 'to' ]);
                //$message->attach($pathToFile, array $options = []);
            });
        }
        
        public static function sendMail($data)
        {
            // dd($data);
            $to        = isset($data[ 'to' ]) ? $data[ 'to' ] : env('MAIL_FROM_ADDRESS');
            $from      = isset($data[ 'from' ]) ? $data[ 'from' ] : env('MAIL_FROM_ADDRESS');
            $reply_to  = isset($data[ 'reply_to' ]) ? $data[ 'reply_to' ] : $from;
            $from_name = isset($data[ 'from_name' ]) ? $data[ 'from_name' ] : env('MAIL_FROM_NAME');
            $subject   = isset($data[ 'subject' ]) ? $data[ 'subject' ] : env('DEFAULT_MAIL_SUBJECT');
            $content   = isset($data[ 'message' ]) ? ($data[ 'message' ]) : env('DEFAULT_MAIL_CONTENT');
            $path      = 'template/mail';
            
            $data = array( 'to'        => $to ,
                           'from'      => $from ,
                           'reply_to'  => $reply_to ,
                           'from_name' => $from_name ,
                           'subject'   => $subject ,
                           'message'   => $content ,
                           'path'      => $path ,
            );
            
            return Mail::send($path , [ 'data' => $data ] , function ($message) use ($data) {
                $message->subject($data[ 'subject' ]);
                $message->from($data[ 'from' ] , $data[ 'from_name' ]);
                $message->to($data[ 'to' ]);
                //$message->attach($pathToFile, array $options = []);
            });
        }
    }

?>