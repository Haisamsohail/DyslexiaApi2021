<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;
use App\ChildStudent;
use App\User;
use App\studentactivelevel;
use App\SkillLevelHeading;
use App\chapter;
use App\Http\Helper\ResponseBuilder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Exception;

class ChildStudentController extends Controller
{
  protected $jwt;
    protected $errorCode = 0;
	public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }


    public function RegisterChildStudent(Request $_Request)
    {
        
        $FindObject =  User::where('email','=',$_Request->input('email'))->get();

        if(!($FindObject->isEmpty()))
        {
            $status = false;
            $Message = "Email Already Exists";
            $errorCode = 1062;
        }
        else
        {
            if($_Request->input('id') > 0)
            {
                $UserObject = ChildStudent::find($_Request->input('id'));
                $UserObject->ParentID = $_Request->input('ParentID');
                $UserObject->FirstName = $_Request->input('FirstName');
                $UserObject->LastName = $_Request->input('LastName');
                $UserObject->Age = $_Request->input('Age');
                $UserObject->Gender = $_Request->input('Gender');
                $UserObject->email = $_Request->input('email');
                $UserObject->password = Hash::make($_Request->input('password'));
                $UserObject->Age = $_Request->input('Age');
                $UserObject->profilepicture = $_Request->input('profilepicture');
                $UserObject->Status = $_Request->input('Status');
            }
            else
            {
                $this->validate($_Request, 
                [
                    'ParentID'=>'required',
                    'FirstName'=>'required',
                    'LastName'=>'required',
                    'Age'=>'required',
                    'Gender'=>'required',
                    'email'=>'required',
                    'password'=>'required',
                    'profilepicture'=>'required',
                    'Status'=>'required',
                ]);
                
                $UserObject = new ChildStudent(
                [
                    "ParentID" => $_Request->input('ParentID'),
                    "FirstName" => $_Request->input('FirstName'),
                    "LastName" => $_Request->input('LastName'),
                    "Age" => $_Request->input('Age'),
                    "Gender" => $_Request->input('Gender'),
                    "email" => $_Request->input('email'),
                    "password" => Hash::make($_Request->input('password')),
                    "profilepicture"       => $_Request->input('profilepicture'),
                    "Status"       => $_Request->input('Status')
                ]);    
            }            

            try 
            {
                if($UserObject->save())
                {
                    if($_Request->input('id') == null)
                    {
                        $LevelID_ = SkillLevelHeading::where('LevelNumber','=','1')->get()[0]->id;
                        $ChapterID_ = chapter::where(['levelId' => $LevelID_ , 'chapter' => 1])->get()[0]->id;

                        $Object_studentactivelevel = new studentactivelevel(
                        [
                            "StudentID" => $UserObject->id,
                            "LevelID" => $LevelID_,
                            "ChapterID" => $ChapterID_,
                            "WordsCount" => 0,
                            "Points" => 0
                        ]);
                        $Object_studentactivelevel->save();
                    }

                    

                    $status = true;
                    $Message = "Data Save Success";
                    $errorCode = 0;
                }
                else
                {
                //      dd("B");
                    $status = false;
                    $Message = "Data Not Saved Success";
                    $errorCode = 0;
                }
            //..    return ResponseBuilder::result($status, $Message, $errorCode);

            } 
            catch(\Illuminate\Database\QueryException $e)
            {
               //dd($e->errorInfo);

                $errorCode = $e->errorInfo[1];
                //  dd($errorCode);
                if($errorCode == '1062')
                {
                    $status = false;
                    $errorCode = $errorCode;
                    $Message = $e->errorInfo[2];
                }
            }
        }

        return ResponseBuilder::result($status, $Message, $errorCode);
    }


    public function RegisterChildStudentUpdate(Request $_Request)
    {
        
        if($_Request->input('id') > 0)
        {
            $UserObject = ChildStudent::find($_Request->input('id'));
            $UserObject->FirstName = $_Request->input('FirstName');
            $UserObject->LastName = $_Request->input('LastName');
        }
        
        try 
        {
            if($UserObject->save())
            {
            //      dd("A");
                $status = true;
                $Message = "Data Save Success";
                $errorCode = 0;
            }
            else
            {
            //      dd("B");
                $status = false;
                $Message = "Data Not Saved Success";
                $errorCode = 0;
            }
        //..    return ResponseBuilder::result($status, $Message, $errorCode);

        } 
        catch(\Illuminate\Database\QueryException $e)
        {
           //dd($e->errorInfo);

            $errorCode = $e->errorInfo[1];
            //  dd($errorCode);
            if($errorCode == '1062')
            {
                $status = false;
                $errorCode = $errorCode;
                $Message = $e->errorInfo[2];
            }
        }
        return ResponseBuilder::result($status, $Message, $errorCode);
    }



    public function ChildStudentList($ParentID)
    {
		$data =  ChildStudent::where('ParentID','=',$ParentID)->get();
		return ResponseBuilder::resultList($data);
    }

    public function ChildStudentBYID($id)
    {
        $data =  ChildStudent::where('id','=',$id)->get();
        return ResponseBuilder::resultList($data);
    }

    public function ChildStudentLogin(Request $_Request)
    {
        $this->validate($_Request, 
            [
                'email'=>'required',
                'password'=>'required'
            ]);

        $_Request->input('email');
        $_Request->input('password');

        $data =  ChildStudent::where(['email' => $_Request->input('email')])->get();
        //dd(gettype($data));

        if(!($data->isEmpty()))
        {
            if(Hash::check($_Request->input('password'), $data[0]->password))
            {

                $Status = true;
                $Info = 'Login Success';
                $Data = [
                            'id' => $data[0]->id,
                            'ParentID' => $data[0]->ParentID,
                            'FirstName' => $data[0]->FirstName,
                            'LastName' => $data[0]->LastName,
                            'Gender' => $data[0]->Gender,
                            'email' => $data[0]->email,
                            'profilepicture' => $data[0]->profilepicture
                        ];
            }
            else
            {
                $Status = false;
                $Info = 'Login Fail';
                $Data = '404';
            }
        }
        else
        {
            $Status = false;
            $Info = 'Login Fail : email not exist';
            $Data = '404';
        }
        return ResponseBuilder::result($Status, $Info, $Data);
    }

}
