<?php
namespace App\Http\Controllers;
use App\Helper\JWTToken;
use App\Mail\OTPmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Mockery\Exception;

class userController extends Controller
{
    function userRegistrationPage():View{
        return view('auth.pages.registration-page');
    }
    function userLoginPage():View{
        return view('auth.pages.login-page');
    }
    function sendOtpCodePage():View{
        return view('auth.pages.send-otp');
    }
    function verifyOtpPage():View{
        return view('auth.pages.verify-otp');
    }
    function resetPasswordPage():View{
        return view('auth.pages.reset-pass');
    }

    function profilePage():view
    {
        return view('auth.dashboard.profile-page');
    }


    public function userRegistration(Request $request)
    {
        try {
            User::create([
                'firstName' => $request->input('firstName'),
                'lastName' => $request->input('lastName'),
                'email' => $request->input('email'),
                'mobile' => $request->input('mobile'),
                'password' => $request->input('password'),
            ]);
            return response()->json([
                'status'=>'success',
                'message'=>'User registration successful'
            ],'200');
        }
        catch (Exception $e){
            return response()->json([
                'status'=>'failed',
                'message'=>'User registration failed'
            ],'200');
        }
    }
    function userLogin(Request $request){
        $count=User::where('email','=',$request->input('email'))
            ->where('password','=',$request->input('password'))
            ->select('id')->first();

        if($count!==null){
            $token=JWTToken::createToken($request->input('email'),$count->id);
            return response()->json([
                'status'=>'success',
                'message'=>'User login successful'
                ],200)->cookie('token',$token,60*24*30);
        }
        else{
            return response()->json([
                'status'=>'failed',
                'message'=>'User login failed'
            ],'401');
        }
    }
    function sendOtpCode(Request $request){
        $email = $request->input('email');
        $otp = rand(1000,9999);
        $count= User::where('email','=',$email)->count();
        if ($count==1){
            Mail::to($email)->send(new OTPmail($otp));
            User::where('email','=',$email)->update(['otp'=>$otp]);
            return response()->json([
                'status'=>'success',
                'message'=>'OTP send to your email successfully',
            ],'200');
        }
        else{
            return response()->json([
                'status'=>'failed',
                'message'=>'unauthorized'
            ],'401');
        }
    }
    function verifyOtp(Request $request){
        $email = $request->input('email');
        $otp = $request->input('otp');
        $count = User::where('email','=',$email)->
        where('otp','=',$otp)->count();
        if ($count==1){

            User::where('email','=',$email)->update(['otp'=>'0']);

            $token = jwtToken::CreateTokenForSetPassword($request->input('email'));
            return response()->json([
                'status'=>'success',
                'message'=>'Otp verification successful',
            ],'200')->cookie('token',$token,60*24*30);
        }

        else{
            return response()->json([
                'status'=>'failed',
                'message'=>'unauthorized'
            ],'401');
        }
    }
    function resetPassword(Request $request){
        try {
            $email = $request->header('email');
            $password = $request->input('password');
            User::where('email', '=', $email)->update(['password' => $password]);
            return response()->json([
                'status' => 'success',
                'message' => 'Request successful',
            ], '200');
        }
        catch (Exception $exception){
            return response()->json([
                'status'=>'failed',
                'message'=>'unauthorized'
            ],'401');
        }
    }
    function userProfile(Request $request){
        $email=$request->header('email');
        $user=User::where('email','=',$email)->first();
        return response()->json([
            'status' => 'success',
            'message' => 'Request Successful',
            'data' => $user
        ],200);
    }
    function updateProfile(Request $request){
        try{
            $email=$request->header('email');
            $firstName=$request->input('firstName');
            $lastName=$request->input('lastName');
            $mobile=$request->input('mobile');
            $password=$request->input('password');
            User::where('email','=',$email)->update([
                'firstName'=>$firstName,
                'lastName'=>$lastName,
                'mobile'=>$mobile,
                'password'=>$password
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Request Successful',
            ],200);

        }catch (Exception $exception){
            return response()->json([
                'status' => 'fail',
                'message' => 'Something Went Wrong',
            ],200);
        }
    }
    function userLogout(){
        return redirect('/userLogin')->cookie('token','',-1);
    }
}


