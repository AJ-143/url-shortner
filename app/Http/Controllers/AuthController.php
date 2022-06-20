<?php

namespace App\Http\Controllers;
use App\Models\ShortUrls;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Exception;

class AuthController extends Controller
{
    public function index(Request $request)
    {
        return view('login');
    }

    public function register(Request $request)
    {
        return view('register');
    }

    public function registration(Request $request)
    {
        if($request->ajax()){
            $rules = [
                'name'          => 'required',
                'email'         => 'required|email',
                'password' => 'required|min:6', 
                'cpassword' => 'required|same:password|min:6',
            ];
            $messages = [
                'name.required'         => 'Name is required',
                'email.required'        => 'Email is required',
                'password.required'     => 'Password is required',
                'cpassword.required'    => 'Confirm Password is required'
            ];
            $validation = Validator::make($request->all(), $rules, $messages);
            if ($validation->fails()) {
                return response()->json(['success' => false, 'message' => $validation->errors()->first()]);
            }
            try {
                $user = new User();
                $user->name = $request->name;
                $user->email = $request->email;
                $user->password = Hash::make($request->password);
                $user->save();

                return response()->json(['status' => true, 'message' => 'Thanks for registering!']);
            } catch (\Exception $e) {
                return response()->json(['status' => false, 'message' => 'Something went wrong!']);
            }
        }
    }

    public function dashboard(Request $request)
    {
        $url_count = ShortUrls::orderBy('id','desc')->count();
        
        return view('dashboard', compact('url_count'));
    }

    public function login(Request $request)
    {
        if($request->ajax()){
            try{
                $validation = Validator::make($request->all(),[
                    'email'    => 'required|email',
                    'password' => 'required',
                ]);
                if($validation->fails()){
                    return response()->json(['status' => false, 'message' => $validation->errors()->first()]);
                }
                if(auth()->guard('web')->attempt(['email'=>$request->email,'password'=> $request->password])){
                    $user = Auth()->guard('web')->user();
                    return response()->json(['status' => true, 'message' => 'Logged in successfully!']);
                    // return redirect('/dashboard');
                }else{
                    return response()->json(['status' => false, 'message' => 'Invalid Credentails!']);
                }
            }catch(\Exception $e){
                return response()->json(['status'=> false,'response'=>$e->getMessage()]);
            }
        }
    }

    public function logout(Request $request) 
    {
        return redirect('/')->with(Auth::logout());
    }
}
