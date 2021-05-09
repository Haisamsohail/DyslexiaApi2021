<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;
use App\ChildStudent;
use App\word;
use App\video;
use App\chapter;
use App\passingcriteria;
use App\Studentpoint;
use App\Http\Helper\ResponseBuilder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Exception;
use Validator,Redirect,Response,File;
use Illuminate\Support\Facades\Storage;

class ProgressRepoerController extends Controller
{
	protected $jwt;
    protected $errorCode = 0;
	public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }


    public function ProgressReportMaster(Request $_Request)
    {
    	// dd($_Request->input());
		// $data =  ChildStudent::where('ParentID','=',$_Request->input('ParentID'))->get();


		if($_Request->input('roleId') == '5')
		{
			$data = ChildStudent::Join('studentactivelevels', 'child_students.id', '=', 'studentactivelevels.StudentID') 
				->Join('skill_level_headings', 'studentactivelevels.LevelID', '=', 'skill_level_headings.id')
				->select("child_students.id", "child_students.FirstName", "child_students.LastName", "child_students.Age", "child_students.Gender","child_students.email","child_students.profilepicture","studentactivelevels.LevelID","studentactivelevels.ChapterID","studentactivelevels.WordsCount","studentactivelevels.Points","studentactivelevels.Status","skill_level_headings.LevelNumber","skill_level_headings.LevelHeading")->get();
		}
		else
		{
				$data = ChildStudent::Join('studentactivelevels', 'child_students.id', '=', 'studentactivelevels.StudentID') 
				->Join('skill_level_headings', 'studentactivelevels.LevelID', '=', 'skill_level_headings.id')
				->where(['child_students.ParentID' => $_Request->input('ParentID')])
				->select("child_students.id", "child_students.FirstName", "child_students.LastName", "child_students.Age", "child_students.Gender","child_students.email","child_students.profilepicture","studentactivelevels.LevelID","studentactivelevels.ChapterID","studentactivelevels.WordsCount","studentactivelevels.Points","studentactivelevels.Status","skill_level_headings.LevelNumber","skill_level_headings.LevelHeading")->get();
		}
	
// dd($data);
		return ResponseBuilder::resultList($data);
    }
}
