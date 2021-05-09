<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;
use App\SkillLevelHeading;
use App\chapter;

use App\Http\Helper\ResponseBuilder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Exception;


class SkillLevelHeadingController extends Controller
{

	protected $jwt;
    protected $errorCode = 0;
	public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

	public function AddUpdateSkillLevelHeading(Request $_Request)
    {
        $this->validate($_Request, 
            [
                'LevelNumber'=>'required',
                'LevelHeading'=>'required'
            ]);

        if($_Request->input('id') > 0)
        {
            $Object_SkillLevelHeading = SkillLevelHeading::find($_Request->input('id'));
            $Object_SkillLevelHeading->LevelNumber = $_Request->input('LevelNumber');
            $Object_SkillLevelHeading->LevelHeading = $_Request->input('LevelHeading');
            $Object_SkillLevelHeading->Status = $_Request->input('Status');
            // dd('AAAA');
        }
        else
        {
        	$Object_SkillLevelHeading = new SkillLevelHeading(
	        [
	            "LevelNumber" => $_Request->input('LevelNumber'),
	            "LevelHeading" => $_Request->input('LevelHeading')
	        ]);
        }

        // dd('$Object_SkillLevelHeading', $Object_SkillLevelHeading);
        
    	try 
		{
            if($Object_SkillLevelHeading->save())
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
            // dd($e->errorInfo);
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

    public function ListSkillLevelHeading()
	{
		if(SkillLevelHeading::all()->isEmpty())
		{
			$data =  "No Record Found";
			$status = false;
		}
		else
		{
			$data =  SkillLevelHeading::orderBy('LevelNumber')->get(); 
            //all()->sortBy("LevelNumber");
			$status = true;
			
		}
        return ResponseBuilder::resultList($data);

		//..  return ResponseBuilder::result($status, $data);
	}

    public function ListSkillLevelHeadingHaveChapter()
    {
        if(SkillLevelHeading::all()->isEmpty())
        {
            $data =  "No Record Found";
            $status = false;
        }
        else
        {
            $data = chapter::Join('skill_level_headings', 'chapters.levelId', '=', 'skill_level_headings.id')
                    ->select("skill_level_headings.LevelNumber", "skill_level_headings.LevelHeading", "chapters.chapter", "chapters.Status", "chapters.id","chapters.levelId","chapters.description","chapters.ChapterType")
                    ->groupBy('skill_level_headings.id')
                    ->get();

            // $data =  SkillLevelHeading::orderBy('LevelNumber')->get(); 
            //all()->sortBy("LevelNumber");
            $status = true;
            
        }
        return ResponseBuilder::resultList($data);

        //..  return ResponseBuilder::result($status, $data);
    }

    public function ListSkillLevelHeadingDetail()
    {
        if(SkillLevelHeading::all()->isEmpty())
        {
            $data =  "No Record Found";
            $status = false;
        }
        else
        {
            $data =  SkillLevelHeading::orderBy('LevelNumber')->get(); 
            //all()->sortBy("LevelNumber");
            $status = true;
            
        }
        return ResponseBuilder::resultList($data);

        //..  return ResponseBuilder::result($status, $data);
    }


	//*************************
	public function AddUpdateChapterWord(Request $_Request)
    {
        $this->validate($_Request, 
            [
                'ChapterName'=>'required',
                'Words'=>'required'
            ]);

        $LevelId = SkillLevelHeading::find($_Request->input('skilllevelheading_id'));

         // dd($LevelId->LevelHeading);
        $Objectchapter = new chapter(
            [
                "ChapterName" => $_Request->input('ChapterName')
            ]);
    	
        try 
		{
         // dd($Objectchapter);

            if($Objectchapter->save($LevelId))
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
            dd($e->errorInfo);
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


    
}
