<?php

namespace App\Http\Helper;

class ResponseBuilder
{
	public static function result(	$Status = "", $Info = "", $Data = "")
	{
		return 
		[
			"Success" => $Status,
			"information" => $Info,
			"Data" => $Data
		];
	}

	public static function resultChildLogin(	$Status = "", $Info = "", $Data = "")
	{
		return 
		[
			"Success" => $Status,
			"information_" => $Info,
			"Data" => $Data
		];
	}


	public static function resultList($Data = "")
	{
		return  $Data;
	}
}

?>