<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\User;
use App\Model\Order;
use App\Http\Helper\ResponseBuilder;
use Validator;

class Youtube extends Controller
{
	public function list()
	{
		$data =  User::all();
		$status = true;
		$info = "Data is Listed Success";
		return ResponseBuilder::result($status, $info, $data);

	}

	public function order(Request $request)
	{
		//.	print_r($request->input());
		$valid = Validator::make
					(
						$request->all(),
						[
							'OrderNumber' => 'required'
						]
					);
		if ($valid->fails()) 
		{
			return response()->json
			(
				['error'=>$valid->errors()],
				401
			);
		}


		$order = new Order();
		$order->OrderNumber = $request->input('OrderNumber');
		$order->user_id = $request->input('user_id');
		$Result =  $order->save();
		if($Result == 1)
		{
			$status = true;
			$info = "Data Save Success";
		}
		else
		{
			$status = false;
			$info = "Data Not Saved Success";
		}
		
		return ResponseBuilder::result($status, $info);

	}
}
