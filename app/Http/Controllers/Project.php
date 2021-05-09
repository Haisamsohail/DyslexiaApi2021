<?php

//namespace App\Http\Controllers;
//use Illuminate\Http\Request;
//use DB;
//use App\Model\Project;
//use Illuminate\Database\Eloquent\Model;


namespace App\Http\Controllers;
use DB;
use App\Product;
use App\Http\Helper\ResponseBuilder;
// use Illuminate\Http\Request;
// use App\Model\Project;
// use App\Model\User;
// use App\Model\Order;
// use App\Http\Helper\ResponseBuilder;
// use Validator;


class Project extends Controller
{
	public function insertorm()
	{
		$product = new Product();
		$product->name = "Demo Product";
		$product->quantity = 10;
		$product->description = "This iS Test description Product Office";
		$product->save();
		if($product->save())
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

	public function CreatePro()
	{
		$project = new Project();
		$project->name = "Web Development";
		$Result = $project->save();
		if($Result == 1)
		{
			return "Data Saved...";
		}
			return "Data Fial to Saved...";
	}
}
