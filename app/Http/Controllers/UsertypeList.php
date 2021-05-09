<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;
use App\Usertype;
use App\Http\Helper\ResponseBuilder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Exception;
class UsertypeList extends Controller
{
	protected $jwt;
    protected $errorCode = 0;
	public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    //..**************** Usertype Start
    public function CreateUsertype(Request $_Request)
    {
        //..	dd($_Request->input('UserTypeName'));

        $this->validate($_Request, 
            [
                'UserTypeName'=>'required'
            ]);
        $Object_Usertype = new Usertype(
            [
                "UserTypeName" => $_Request->input('UserTypeName')
            ]);
    	try 
		{
            if($Object_Usertype->save())
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
                //..	dd($e);
            }
        }
	    return ResponseBuilder::result($status, $Message, $errorCode);
    }

    public function ListUsertype()
	{
		if(Usertype::all()->isEmpty())
		{
			$data =  "No Record Found";
			$status = false;
		}
		else
		{
			$data =  Usertype::all();
			$status = true;		
		}

		return ResponseBuilder::result($status, $data);
	}

    public function ListUsertypeForRegister()
    {
        $ids = [6,8];
        if(Usertype::find($ids)->isEmpty())
        {
            $data =  "No Record Found";
            $status = false;
            $Info = "No Record Found";
        }
        else
        {
            $data =  Usertype::find($ids);
            $status = true;
            $Info = "Success";            
        }

        return ResponseBuilder::resultList($data);
    }
    //..**************** Usertype End

}
