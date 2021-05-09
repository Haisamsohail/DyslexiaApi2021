<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use App\Model\dys_user;
use App\Http\Helper\ResponseBuilder;
use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;

class UserDysController extends Controller
{
	protected $jwt;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

	public function LoginUser(Request $request)
	{
		//print_r($request);
		$this->validate($request, [
            'UserEmail'    => 'required|max:255',
            'UserPassword' => 'required',
        ]);

        try {

            if (! $token = $this->jwt->attempt($request->only('UserEmail', 'UserPassword'))) {
                return response()->json(['user_not_found.......'], 404);
            }

        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], 500);

        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], 500);

        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent' => $e->getMessage()], 500);

        }

        return response()->json(compact('token'));

		// $UserDys = new dys_user;
		// //..	$_Data = $UserDys->first();
		// $_Data = $UserDys->orderby('id', 'desc')->first();
		// // foreach ($_Data as $key => $value) {
		// // 	echo $value['UserName']. ' -- '.$value['UserEmail'];
		// // }
		// 	print_r($_Data['Status_']);
	}


	public function UpdateUser()
	{
		$UserDys = new dys_user();
		$_Data = $UserDys->find(7);

		$_Data->UserName = "Test User 31";
		$_Data->UserEmail = "testuser31@test.com";
		if($_Data->save())
		{
			$status = true;
			$info = "Data Updated Success";
		}
		else
		{
			$status = false;
			$info = "Fail To Update Data";
		}
		return ResponseBuilder::result($status, $info);
	}


	public function DeleteUser()
	{
		$UserDys = new dys_user();
		$_Data = $UserDys->find(7);
		if($_Data->delete())
		{
			$status = true;
			$info = "Data Deleted Success";
		}
		else
		{
			$status = false;
			$info = "Fail To Delete Data";
		}
		return ResponseBuilder::result($status, $info);
	}

	public function CreateUser(Request $_Request)
	{
		$this->validate($_Request, 
			[
				'UserEmail'=>'required',
				'UserPassword'=>'required'
			]);
		// ---- Method 1
		// $UserDys = new dys_user();
		// $UserDys->UserName = "Test User 2";
		// $UserDys->UserEmail = "testuser1@test.com";
		// //$UserDys->UserPhone = "03082998869";
		// $UserDys->UserAddress = "Test Address 2";
		// $UserDys->RoleID = 1;
		// $UserDys->UserPassword = "test2";
		
		// ---- Method 2

		$UserDys = new dys_user(
			[
				// "UserName" => "Test User 4",
				"UserEmail" => "testuser4@test.com",
				//"UserPhone" => "testuser1@test.com",
				// "UserAddress" => "Test Address 4",
				"UserPassword" => "test4"
			]);
		$UserDys->save();
		if($UserDys->save())
		{
			$status = true;
			$Message = "Data Save Success";
		}
		else
		{
			$status = false;
			$Message = "Data Not Saved Success";
		}
		return ResponseBuilder::result($status, $Message);

		# code...
	}
    //
}
