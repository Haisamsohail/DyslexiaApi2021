<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;
use App\Hearabout;
use App\Http\Helper\ResponseBuilder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Exception;

class DropDownList extends Controller
{
    protected $jwt;
    protected $errorCode = 0;
	public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }


    public function CreateHearAboutUs(Request $_Request)
    {
        $this->validate($_Request, 
            [
                'HearAboutUsName'=>'required'
            ]);
        $Object_HearAboutUs = new Hearabout(
            [
                "HearAboutUsName" => $_Request->input('HearAboutUsName')
            ]);
    	try 
		{
            if($Object_HearAboutUs->save())
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

    public function ListHearAboutUs()
	{
		if(Hearabout::all()->isEmpty())
		{
			$data =  "No Record Found";
			$status = false;
		}
		else
		{
			$data =  Hearabout::all();
			$status = true;
			
		}
        return ResponseBuilder::resultList($data);

		//..  return ResponseBuilder::result($status, $data);
	}
    //..**************** HearAboutUs End

}