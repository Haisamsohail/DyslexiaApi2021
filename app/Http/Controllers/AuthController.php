<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;
use App\User;
use App\ChildStudent;
use App\studentactivelevel;
use App\Studentpoint;
use App\Http\Helper\ResponseBuilder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    /**
     * @var \Tymon\JWTAuth\JWTAuth
     */
    protected $jwt;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    public function postLogin(Request $request)
    {
        $userChildStudent = false;
        $this->validate($request, [
            'email'    => 'required|email|max:255',
            'password' => 'required'
        ]);
        // dd($this->jwt->attempt($request->only('email', 'password')));

        try {

            if (! $token = $this->jwt->attempt($request->only('email', 'password'))) 
            {
                $FindObject =  ChildStudent::where('email','=',$request->input('email'))->get();
                
                if(!($FindObject->isEmpty()))
                {
                    if(Hash::check($request->input('password'), $FindObject[0]->password))
                    {
                        $datastudentactive_ = studentactivelevel::Join('skill_level_headings', 'studentactivelevels.LevelID', '=', 'skill_level_headings.id')
                        ->join('chapters', 'studentactivelevels.ChapterID', '=', 'chapters.id')
                        ->where(['studentactivelevels.StudentID' => $FindObject[0]->id, 'studentactivelevels.Status' => 1])
                        ->select("skill_level_headings.LevelNumber", "skill_level_headings.LevelHeading", "studentactivelevels.ChapterID", "studentactivelevels.WordsCount", "studentactivelevels.Points","studentactivelevels.LevelID","studentactivelevels.ChapterID","chapters.chapter")->get();
                        
                         $FindCount =  Studentpoint::where(['StudentID' => $FindObject[0]->id, 'Answer' => '1'])->get();
                        // $status = true;
                        // $Message = count($FindCount);

                        $userChildStudent = true;
                        $Status = true;
                        $Data = [
                                    'id' => $FindObject[0]->id,
                                    'ParentID' => $FindObject[0]->ParentID,
                                    'FirstName' => $FindObject[0]->FirstName,
                                    'LastName' => $FindObject[0]->LastName,
                                    'Gender' => $FindObject[0]->Gender,
                                    'email' => $FindObject[0]->email,
                                    'profilepicture' => $FindObject[0]->profilepicture,
                                    'LevelID' => $datastudentactive_[0]->LevelID,
                                    'ChapterID' => $datastudentactive_[0]->ChapterID,
                                    'ChapterNumber' => $datastudentactive_[0]->ChapterNumber,
                                    'ChapterNumber' => $datastudentactive_[0]->chapter,
                                    'WordsCount' => count($FindCount),
                                    'Points' => $datastudentactive_[0]->Points,
                                    'LevelNumber' => $datastudentactive_[0]->LevelNumber,
                                    'LevelHeading' => $datastudentactive_[0]->LevelHeading
                                ];
                    }
                    else
                    {
                        return response()->json(['user_not_found'], 404);
                    }
                }
                else
                {
                    return response()->json(['user_not_found'], 404);
                    //dd("Exist");
                }

            }

        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], 500);

        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], 500);

        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent' => $e->getMessage()], 500);

        }
        $data = [
              'access_token' => $token,
              'token_type' => 'bearer',
              'expires_in' => auth('api')->factory()->getTTL() * 60
          ];

        if($userChildStudent)
        {
            return ResponseBuilder::resultChildLogin($Status, $userChildStudent, $Data);
        }
        else
        {
            return $this->createNewToken($token);
        }

    }


    protected function createNewToken($token){
            // dd(auth()->user()->email);
            // dd(auth()->user()->Status);
            if(auth()->user()->Status != 1)
            {
                return response()->json([
                    // 'access_token' => "NA",
                    // 'token_type' => 'bearer',
                    // 'expires_in' => auth()->factory()->getTTL() * 60,
                    'user' => "In-Active User",
                    'Status' => "In-Active",
                    'Code' => "403",
                ]);

            }
            else
            {
                return response()->json([
                    'access_token' => $token,
                    'userChildStudent' => false,
                    'token_type' => 'bearer',
                    'expires_in' => auth()->factory()->getTTL() * 60,
                    'user' => auth()->user()
                ]);

            }        
    }


    public function registerUser(Request $_Request)
    {
        //..    dd($_Request->input());
        $this->validate($_Request, 
            [
                'UserName'=>'required',
                'email'=>'required',
                'password'=>'required',
                'UserTypeID'=>'required',
                'HearAboutUsId'=>'required'
            ]);
        $UserDys = new User(
            [
                "UserName" => $_Request->input('UserName'),
                "email" => $_Request->input('email'),
                "password" => Hash::make($_Request->input('password')),
                "UserTypeID"       => $_Request->input('UserTypeID'),
                "GradeLevelTaught" => $_Request->input('GradeLevelTaught'),
                "HearAboutUsId" => $_Request->input('HearAboutUsId'),
                "HearAboutUsOther" => $_Request->input('HearAboutUsOther'),
                "SchoolName" => $_Request->input('SchoolName'),
                "Suburb" => $_Request->input('Suburb'),
                "PostCode" => $_Request->input('PostCode'),
                "State" => $_Request->input('State')
            ]);
        
        try 
        {
            if($UserDys->save())
            {
                $status = true;
                $Message = "Data Save Success";
                $errorCode = 0;
            }
            else
            {
                $status = false;
                $Message = "Data Not Saved Success";
                $errorCode = 0;
            }
        } 
        catch(\Illuminate\Database\QueryException $e)
        {
            $errorCode = $e->errorInfo[1];
            if($errorCode == '1062')
            {
                $status = false;
                $errorCode = $errorCode;
                $Message = $e->errorInfo[2];
                //..    dd($e);
            }
        }
        return ResponseBuilder::result($status, $Message, $errorCode);
    }

    public function ListUsers()
    {
        if(User::all()->isEmpty())
        {
            $data =  "No Record Found";
            $status = false;
        }
        else
        {
            $data =  User::all()->sortBy("UserName");
            //..    $data =  User::all()->sortByDesc("UserName");
            $status = true;     
        }
        return ResponseBuilder::resultList($data);
        //..    return ResponseBuilder::result($status, $data);
    }

    public function ParentsList()
    {
        $ids = [6];
        if(User::all()->isEmpty())
        {
            $data =  "No Record Found";
            $status = false;
        }
        else;
        {
            $data = User::where('UserTypeID','=',$ids)
                ->Join('hearabouts', 'users.HearAboutUsId', '=', 'hearabouts.id')
                ->select("users.id", "users.UserName", "users.email", "users.HearAboutUsOther", "users.Suburb", "users.PostCode", "users.State", "users.Status", "hearabouts.HearAboutUsName")->get();
            //..    $data =  User::where('UserTypeID','=',$ids)->get();
            //..    $data =  User::all()->sortBy("UserName");
            //..    $data =  User::all()->sortByDesc("UserName");
            $status = true;     
        }
        return ResponseBuilder::resultList($data);
        //..    return ResponseBuilder::result($status, $data);
    }

    public function TeachersList()
    {

        $ids = [8];
        if(User::all()->isEmpty())
        {
            $data =  "No Record Found";
            $status = false;
        }
        else;
        {

            //  $data = User::where('UserTypeID','=',$ids)
            //      ->rightJoin('hearabouts', 'Users.HearAboutUsId', '=', 'hearabouts.id')
            //      ->select()->get();
                 
            
            $data = User::where('UserTypeID','=',$ids)
                ->Join('hearabouts', 'users.HearAboutUsId', '=', 'hearabouts.id')
                ->select("users.id", "users.UserName", "users.email", "users.HearAboutUsOther", "users.SchoolName", "users.GradeLevelTaught", "users.State", "users.Status", "hearabouts.HearAboutUsName")->get();
           // $data =  User::where('UserTypeID','=',$ids)->Hearabout;
            //..    $data =  User::all()->sortBy("UserName");
            //..    $data =  User::all()->sortByDesc("UserName");
            // dd($data);
            $status = true;     
        }
        return ResponseBuilder::resultList($data);
        //..    return ResponseBuilder::result($status, $data);
    }

    public function logout(Request $request)
    {
        auth()->logout();
        return response()->json([
            'code'   => $this->successStatus,
            'status' => true,
            'message'=> "Logout Success",
            'data'   => []
        ], $this->successStatus);
    }

    public function ApproveUnApproveUser(Request $_Request)
    {
        //..    dd($_Request->input());
        $this->validate($_Request, 
            [
                'id'=>'required',
                'Status'=>'required'
            ]);

                $UserDys = new User(
            [
                "id" => $_Request->input('id'),
                "State" => $_Request->input('State')
            ]);

        $UserObject = User::find($_Request->input('id'));
        $UserObject->Status = $_Request->input('Status');
        
        try 
        {
            if($UserObject->save())
            {
                $status = true;
                $Message = "Data Save Success";
                $errorCode = 0;
            }
            else
            {
                $status = false;
                $Message = "Data Not Saved Success";
                $errorCode = 0;
            }
        } 
        catch(\Illuminate\Database\QueryException $e)
        {
            $errorCode = $e->errorInfo[1];
            if($errorCode == '1062')
            {
                $status = false;
                $errorCode = $errorCode;
                $Message = $e->errorInfo[2];
                //..    dd($e);
            }
        }
        return ResponseBuilder::result($status, $Message, $errorCode);
    }



    public function mail() 
    {
        $data = array 
         ('name'=>"Arunkumar");
        Mail::send('mail', $data, function($message) {
        $message->to('haisamsohail@gmail.com', 'Haisam')->subject('Test Email From Haisam Sys');
        $message->from('haisamsohail@gmail.com','Haisam Sohail');
        });
        echo "Email Sent. Check your inbox.";
    }



}