<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;
use App\ChildStudent;
use App\word;
use App\SkillLevelHeading;
use App\video;
use App\chapter;
use App\passingcriteria;
use App\Studentpoint;
use App\studentactivelevel;
use App\Http\Helper\ResponseBuilder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Exception;
use Validator,Redirect,Response,File;
use Illuminate\Support\Facades\Storage;
// app('filesystem');



class ChapterController extends Controller
{
    protected $jwt;
    protected $errorCode = 0;
	public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    public function CreateChapter(Request $_Request)
    {
    	// dd($_Request->input());
        $this->validate($_Request, 
            [
                'levelId'=>'required',
                'chapter'=>'required',
                'ChapterType'=>'required',
                'description'=>'required',
                'Status'=>'required',
            ]);

        if($_Request->input('id') > 0)
        {
            $Finddata =  chapter::where(['levelId' => 9000000])->get(); 
        }
        else
        {
           $Finddata =  chapter::where(['levelId' => $_Request->input('levelId'), 'chapter' => $_Request->input('chapter')])->get(); 
        }

        if($Finddata->isEmpty())
        {
            if($_Request->input('id') > 0)
            {
                $Object_chapter = chapter::find($_Request->input('id'));
                $Object_chapter->levelId = $_Request->input('levelId');
                $Object_chapter->chapter = $_Request->input('chapter');
                $Object_chapter->description = $_Request->input('description');
                $Object_chapter->ChapterType = $_Request->input('ChapterType');
                $Object_chapter->Status = $_Request->input('Status');
            } 
            else
            {
                $Object_chapter = new chapter(
                [
                    "levelId" => $_Request->input('levelId'),
                    "chapter" => $_Request->input('chapter'),
                    "description" => $_Request->input('description'),
                    "ChapterType" => $_Request->input('ChapterType')
                ]);
            }
            // dd($_Request->input('Words'));
            try 
            {
                if($Object_chapter->save())
                {
                    $Object_chapter->id;
                    $Container = (object) ['file' => ""];
                    $FileName = $_Request->file('file')->getClientOriginalName();
                    $Filename_arr = explode('.', $FileName);
                    $FileExt = end($Filename_arr);
                    $MainFile = $Object_chapter->id.'.' . $FileExt;
                    $DestinationPath = './ChapterIcons/';

                    if ($_Request->file('file')->move($DestinationPath, $MainFile)) 
                    {
                        $Container->MainFile = '/ChapterIcons/' . $MainFile;                  
                    }
                    
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
        }
        else
        {
            $status = false;
            $errorCode = 1062;
            $Message = 'Chapter Already In the List';

        }

        
	    return ResponseBuilder::result($status, $Message, $errorCode);
    }

    public function CreateChapterWord(Request $_Request)
    {
        // dd($_Request->input());
        $this->validate($_Request, 
            [
                'levelId'=>'required',
                'chapter'=>'required'
            ]);
        // dd($_Request->input('Words'));
        try 
        {
                $chapter = $_Request->input('chapter');
                foreach ($_Request->input('Words') as $key => $value) 
                {
                     // dd($Object_chapter->id);

                    if($value['id'] > 0)
                    {
                        $Object_word = word::find($value['id']);
                        // dd()
                        $Object_word->chapter_id = $chapter;
                        $Object_word->word = $value['word'];
                    } 
                    else
                    {
                        $Object_word = new word(
                        [
                            "word" => $value['word'],
                            "chapter_id" => $chapter
                        ]);
                    }

                    $Object_word->save();
                }
                $status = true;
                $Message = "Data Save Success";
                $errorCode = 0;
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


    public function GetChaptersList($levelId)
    {
        $data =  chapter::where('levelId','=',$levelId)->get();
        return ResponseBuilder::resultList($data);
    }

    public function GetChapterDetail($levelId,$chapter)
    {
        // dd($levelId,$chapter);

        $data =  chapter::where(['levelId' => $levelId, 'id' => $chapter])->get();
        return ResponseBuilder::resultList($data);
    }
    
    public function ListChapter()
    {
        if(chapter::all()->isEmpty())
        {
            $data =  "No Record Found...";
            $status = true;
        } 
        else
        {
            $data = chapter::Join('skill_level_headings', 'chapters.levelId', '=', 'skill_level_headings.id') ->select("skill_level_headings.LevelNumber", "skill_level_headings.LevelHeading", "chapters.chapter", "chapters.Status", "chapters.id","chapters.levelId","chapters.description","chapters.ChapterType")->get();

             // $data =  chapter::orderBy('levelId')->get(); ;
            $status = true;
            
        }
        return ResponseBuilder::resultList($data);

        //..  return ResponseBuilder::result($status, $data);
    }

    public function ListChapterWord()
	{
		if(chapter::all()->isEmpty())
		{
			$data =  "No Record Found...";
			$status = true;
		} 
		else
		{
            // $data = chapter::Join('skill_level_headings', 'chapters.levelId', '=', 'skill_level_headings.id') ->select("skill_level_headings.LevelNumber", "skill_level_headings.LevelHeading", "chapters.chapter", "chapters.Status", "chapters.id","chapters.levelId","chapters.description","chapters.ChapterType")->get();

            $data = chapter::Join('skill_level_headings', 'chapters.levelId', '=', 'skill_level_headings.id')
                    ->JOIN('words', 'words.chapter_id','=','chapters.id') 
                    ->select("skill_level_headings.LevelNumber", "skill_level_headings.LevelHeading", "chapters.chapter", "chapters.Status", "chapters.id","chapters.levelId","chapters.description","chapters.ChapterType")
                    ->groupBy('chapters.id')
                    ->get();

			 // $data =  chapter::orderBy('levelId')->get(); ;
			$status = true;
			
		}
        return ResponseBuilder::resultList($data);

		//..  return ResponseBuilder::result($status, $data);
	}

	public function ListWords(Request $_Request)
	{
		// dd($_Request->input('levelId'));
		if(chapter::all()->isEmpty())
		{
			$data =  "No Record Found";
			$status = false;
		}
		else
		{  

			// $data =  chapter::find($_Request->input('id'))->words;
			// $data =  chapter::find(16)->words;
        $Student_ID =  $_Request->input('StudentID');
        // dd($Student_ID);

        $data = word::
            leftJoin('studentpoints', function($join) use ($Student_ID)
            {
                    $join->on('studentpoints.WordID','=','words.id')
                     ->where('studentpoints.StudentID', '=', $Student_ID);
            })

            // leftjoin('studentpoints', 'studentpoints.WordID','=','words.id') 
            ->where(['words.chapter_id' => $_Request->input('id')])
            ->select("words.id", "words.chapter_id", "words.word", "studentpoints.Answer", "studentpoints.StudentID")->get();
			$status = true;
			
		}
        return ResponseBuilder::resultList($data);

		//..  return ResponseBuilder::result($status, $data);
	}

	public function AddUpdatePassingCriteria(Request $_Request)
    {
        $this->validate($_Request, 
            [
                'skilllevelheading_id'=>'required',
                'passingpoints'=>'required'
            ]);
            // dd($_Request->input());
        if($_Request->input('id') > 0)
        {
            $Object_passingcriteria = passingcriteria::find($_Request->input('id'));
            $Object_passingcriteria->skilllevelheading_id = $_Request->input('skilllevelheading_id');
            $Object_passingcriteria->passingpoints = $_Request->input('passingpoints');
            $Object_passingcriteria->Status = $_Request->input('Status');
        }
        else
        {
            $Object_passingcriteria = new passingcriteria(
            [
                "skilllevelheading_id" => $_Request->input('skilllevelheading_id'),
                "passingpoints" => $_Request->input('passingpoints')
            ]);
        }
        
        try 
        {
            if($Object_passingcriteria->save())
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
                //..    dd($e);
            }
        }
        return ResponseBuilder::result($status, $Message, $errorCode);
    }


    public function ListPassingCriteria()
	{
		if(passingcriteria::all()->isEmpty())
		{
			$data =  "No Record Found";
			$status = false;
		}
		else
		{
			// $data =  passingcriteria::orderBy('skilllevelheading_id')->get();
            $data = passingcriteria::Join('skill_level_headings', 'passingcriterias.skilllevelheading_id', '=', 'skill_level_headings.id') 
            ->select("skill_level_headings.LevelNumber", "skill_level_headings.LevelHeading", "passingcriterias.passingpoints", "passingcriterias.Status", "passingcriterias.id","passingcriterias.skilllevelheading_id")->get();
			$status = true;
			
		}
        return ResponseBuilder::resultList($data);

		//..  return ResponseBuilder::result($status, $data);
	}


    public function ChapterWordsDescriptionList(Request $_Request)
    {
        $Student_ID =  $_Request->input('StudentID');
        // dd($_Request->input());
        if(chapter::all()->isEmpty())
        {
            $data =  "No Record Found";
            $status = false;
        }
        else
        {
            //$_Request->input('levelId')
            $data = chapter::where('levelId','=',$_Request->input('levelId'))
            

            ->Join('skill_level_headings', 'chapters.levelId', '=', 'skill_level_headings.id')
            ->Join('words','chapters.id','=','words.chapter_id')
            ->leftJoin('studentpoints', function($join) use ($Student_ID)
            {
                    $join->on('studentpoints.WordID','=','words.id')
                     ->where(
                            ['studentpoints.StudentID' => $Student_ID, 'studentpoints.Answer' => 1]);
            })
             ->select("skill_level_headings.LevelNumber", "skill_level_headings.LevelHeading", "chapters.chapter", "chapters.Status", "chapters.id as chapterId","chapters.levelId","chapters.description","chapters.ChapterType", 'words.id', 'studentpoints.StudentID')
             ->selectRaw("count(words.id) as total")
             ->selectRaw("count(studentpoints.StudentID) as StudentsPercentagetotal")
             ->groupBy('chapters.id')
            // ->count()
             ->orderBy('chapters.chapter')
            ->get()
            ;

                // dd($data);
             // $data =  chapter::orderBy('levelId')->get(); ;
            $status = true;            
        }
        return ResponseBuilder::resultList($data);
    }

    public function AddUpatePoints(Request $_Request)
    {

        // $FindNextLevel = SkillLevelHeading::where('LevelNumber', '=', 1+1)->get();

        // $FindNextChapterDetail =  chapter::where(['levelId' => $FindNextLevel[0]->id, 'chapter'=> 1])->get();
        // if($FindNextChapterDetail->isEmpty())
        // {
        //     dd('Null', $FindNextChapterDetail->isEmpty());
        // }
        // else
        // {
        //     dd($FindNextChapterDetail);
        // }
        $this->validate($_Request, 
            [
                'StudentID'=>'required',
                'WordID'=>'required',
                'Answer'=>'required'
            ]);
            // dd($_Request->input());

        $FindObject =  Studentpoint::where(['StudentID' => $_Request->input('StudentID'), 'WordID' => $_Request->input('WordID')])->get();
        // dd($FindObject[0]);
        if(!($FindObject->isEmpty()))
        {
            $Object_Studentpoint = Studentpoint::find($FindObject[0]->id);
            $Object_Studentpoint->Answer = $_Request->input('Answer');
        }
        else
        {
            $Object_Studentpoint = new Studentpoint(
            [
                "StudentID" => $_Request->input('StudentID'),
                "WordID" => $_Request->input('WordID'),
                "Answer" => $_Request->input('Answer')
            ]);
        }
        
        try 
        {
            if($Object_Studentpoint->save())
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
                //..    dd($e);
            }
        }

        /******* Chapter Level Update Start *******************/
        $ChapterId_ =  word::where(['id' => $_Request->input('WordID')])->get();
        $StudentActiveLevel = studentactivelevel::where(['StudentID' => $_Request->input('StudentID'),'Status' => 1])->get();
         // dd('$StudentActiveLevel',$StudentActiveLevel[0]);

        $ActiveLevelNumber = SkillLevelHeading::where('id','=',$StudentActiveLevel[0]->LevelID)->get()[0]->LevelNumber;
         // dd('$ActiveLevelNumber',$ActiveLevelNumber);
         //..       
        $StudentActiveChapter =  chapter::where(['id' => $StudentActiveLevel[0]->ChapterID])->get();
        // dd('$ChapterId_',$StudentActiveChapter[0]);


        $FindCurrentChapterDetail =  chapter::where(['id' => $ChapterId_[0]->chapter_id])->get();
        
        $Data = [];
        $Count_ = count(Studentpoint::
                    Join('words', 'words.id', '=','studentpoints.WordID')
                    ->where(['StudentID' => $_Request->input('StudentID'), 'Answer' => '1', 'chapter_id' => $ChapterId_[0]->chapter_id])->get());
        
        if(count(word::where(['chapter_id' => $ChapterId_[0]->chapter_id])->get()) == count(Studentpoint::
            Join('words', 'words.id', '=', 'studentpoints.WordID')
             ->where(['StudentID' => $_Request->input('StudentID'), 'Answer' => '1', 'chapter_id' => $ChapterId_[0]->chapter_id])->get()))
        {
            // $FindStudentActiveLevelDetail = studentactivelevel::where('StudentID', '=', $_Request->input('StudentID'))->get();

            // dd($FindStudentActiveLevelDetail[0]);
            if($StudentActiveChapter[0]->ChapterType == 'Challenge')
            {

                $FindStudentActiveLevelNumber = SkillLevelHeading::where('id', '=', $StudentActiveLevel[0]->LevelID)->get();

                $FindNextLevel = SkillLevelHeading::where('LevelNumber', '=', $FindStudentActiveLevelNumber[0]->LevelNumber+1)->get();

                // dd($FindNextLevel);

                $FindNextChapterDetail =  chapter::where(['levelId' => $FindNextLevel[0]->id, 'chapter'=> 1])->get();
                 // dd($FindNextChapterDetail);



                
                if($FindNextChapterDetail->isEmpty())
                {
                    $UpdateStudentActiveLevel = studentactivelevel::where(['StudentID' => $_Request->input('StudentID'),'Status' => 1])->update([
                    'WordsCount' => count(Studentpoint::where(['StudentID' => $_Request->input('StudentID'), 'Answer' => '1'])->get()),
                    'Points' => $Count_,
                    'Status' => 1 ]);

                    $StudentActiveLevel = studentactivelevel::where(['StudentID' => $_Request->input('StudentID'),'Status' => 1])->get();
        
                    $ActiveLevelNumber = SkillLevelHeading::where('id','=',$StudentActiveLevel[0]->LevelID)->get()[0]->LevelNumber;
                    // dd('*****', $ActiveLevelNumber);
                    $Data = [
                        'WordsCount' => count(Studentpoint::where(['StudentID' => $_Request->input('StudentID'), 'Answer' => '1'])->get()),
                        'LevelID' => $StudentActiveChapter[0]->levelId,
                        'LevelNumber' => $ActiveLevelNumber,
                        'ChapterID' => $StudentActiveChapter[0]->id,
                        'ChapterNumber' => $StudentActiveChapter[0]->chapter,
                        'Points' => $Count_
                    ];

                }
                else
                {
                    $UpdateStudentActiveLevel = studentactivelevel::where(['StudentID' => $_Request->input('StudentID'),'Status' => 1])->update([
                    'WordsCount' => count(Studentpoint::where(['StudentID' => $_Request->input('StudentID'), 'Answer' => '1'])->get()),
                    'Points' => $Count_,
                    'Status' => 2 ]);

                     $Object_studentactivelevel = new studentactivelevel(
                    [
                        "StudentID" => $_Request->input('StudentID'),
                        "LevelID" => $FindNextChapterDetail[0]->levelId,
                        "ChapterID" => $FindNextChapterDetail[0]->id,
                        "WordsCount" => count(Studentpoint::where(['StudentID' => $_Request->input('StudentID'), 'Answer' => '1'])->get()),
                        "Points" => $Count_
                    ]);
                    $Object_studentactivelevel->save();

                    $StudentActiveLevel = studentactivelevel::where(['StudentID' => $_Request->input('StudentID'),'Status' => 1])->get();
        

                    $ActiveLevelNumber = SkillLevelHeading::where('id','=',$StudentActiveLevel[0]->LevelID)->get()[0]->LevelNumber;
                    // dd('*****', $ActiveLevelNumber);
                    $Data = [
                        'WordsCount' => count(Studentpoint::where(['StudentID' => $_Request->input('StudentID'), 'Answer' => '1'])->get()),
                        'LevelID' => $FindNextChapterDetail[0]->levelId,
                        'LevelNumber' => $ActiveLevelNumber,
                        'ChapterID' => $FindNextChapterDetail[0]->id,
                        'ChapterNumber' => $FindNextChapterDetail[0]->chapter,
                        'Points' => $Count_
                    ];

                }


                

               

                    
                 

            }
            else
            {
                $FindNextChapterDetail =  chapter::where(['levelId' => $StudentActiveLevel[0]->LevelID, 'chapter'=>$StudentActiveChapter[0]->chapter+1])->get();
                $Data = [
                    'WordsCount' => count(Studentpoint::where(['StudentID' => $_Request->input('StudentID'), 'Answer' => '1'])->get()),
                    'LevelID' =>  $StudentActiveLevel[0]->LevelID,
                    'LevelNumber' => $ActiveLevelNumber,
                    'ChapterID' => $FindNextChapterDetail[0]->id,
                    'ChapterNumber' => $FindNextChapterDetail[0]->chapter,
                    'Points' => $StudentActiveLevel[0]->Points
                ]; 

                // $Data = [
                //     'WordsCount' => count(Studentpoint::where(['StudentID' => $_Request->input('StudentID'), 'Answer' => '1'])->get()),
                //     'LevelID' => $FindNextChapterDetail[0]->id,
                //     'LevelNumber' => $ActiveLevelNumber,
                //     'ChapterID' => $FindNextChapterDetail[0]->id,
                //     'ChapterNumber' => $FindNextChapterDetail[0]->chapter,
                //     'Points' => $StudentActiveLevel[0]->Points
                // ]; 

                 $StudentActiveLevel = studentactivelevel::where(['StudentID' => $_Request->input('StudentID'),'Status' => 1])->update(['ChapterID' => $FindNextChapterDetail[0]->id]);
            }
            

        }
        else
        {

            // if($StudentActiveChapter[0]->ChapterType == 'Challenge')
            // {   
                $Data = [
                    'WordsCount' => count(Studentpoint::where(['StudentID' => $_Request->input('StudentID'), 'Answer' => '1'])->get()),
                    'LevelID' => $StudentActiveLevel[0]->LevelID,
                    'LevelNumber' => $ActiveLevelNumber,
                    'ChapterID' => $FindCurrentChapterDetail[0]->id,
                    'ChapterNumber' => $FindCurrentChapterDetail[0]->chapter,
                    'Points' => $StudentActiveLevel[0]->Points
                ];  
            // }
            // else
            // {
            //     $Data = [
            //         'WordsCount' => count(Studentpoint::where(['StudentID' => $_Request->input('StudentID'), 'Answer' => '1'])->get()),
            //         'LevelID' => $StudentActiveLevel[0]->LevelID,
            //         'LevelNumber' => $ActiveLevelNumber,
            //         'ChapterID' => $FindCurrentChapterDetail[0]->id,
            //         'ChapterNumber' => $FindCurrentChapterDetail[0]->chapter,
            //         'Points' => $StudentActiveLevel[0]->Points
            //     ];
            // }
            
        }
        
        // dd('$FindNextChapterDetail',$FindNextChapterDetail);

        /******* Chapter Level Update End   *******************/
        return ResponseBuilder::result($status,$Data, $errorCode);
    }

    public function WordCount(Request $_Request)
    {
        $this->validate($_Request, 
            [
                'StudentID'=>'required'
            ]);
            // dd($_Request->input());

        $FindObject =  Studentpoint::where(['StudentID' => $_Request->input('StudentID'), 'Answer' => '1'])->get();
        // dd(count($FindObject));
        $status = true;
        $Message = count($FindObject);
        $errorCode = 0;
        return ResponseBuilder::result($status, $Message, $errorCode);
    }

    protected function responseRequestSuccess($ret)
    {
        return response()->json(['status' => 'success', 'data' => $ret], 200)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    }

    protected function responseRequestError($message = 'Bad request', $statusCode = 200)
    {
        return response()->json(['status' => 'error', 'error' => $message], $statusCode)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    }


    public function store_(Request $request)
    {
 
       $validator = Validator::make($request->all(), 
              [ 
              'user_id' => 'required',
              'file' => 'required',
             ]);    
 
  
        if ($files = $request->file('file')) {
             
            //store file into document folder
            $file = $request->file->store('public/documents');              
            return response()->json([
                "success" => true,
                "message" => "File successfully uploaded",
                "file" => $file
            ]);
  
        }
    }

     public function CreateChapterVideo(Request $_Request)
    {
         // dd($_Request->input('chapter_id'));
        $this->validate($_Request, 
            [
                'chapter_id'=>'required',
                'VideoNumber'=>'required',
                'VideoTitle'=>'required'
            ]);
        
            $Object_Video = new video(
            [
                "chapter_id" => $_Request->input('chapter_id'),
                "VideoNumber" => $_Request->input('VideoNumber'),
                "VideoTitle" => $_Request->input('VideoTitle')
            ]);
         
        try 
        {
            if($Object_Video->save())
            {
                $Object_Video->id;

                /*********** Video File Save Start *************/
                $Object_Video->id;
                $Container = (object) ['file' => ""];
                $FileName = $_Request->file('file')->getClientOriginalName();
                $Filename_arr = explode('.', $FileName);
                $FileExt = end($Filename_arr);
                $MainFile = $Object_Video->id.'.' . $FileExt;

                $DestinationPath = './ChapterVidoes/';

                if ($_Request->file('file')->move($DestinationPath, $MainFile)) 
                {
                    $Container->MainFile = '/ChapterVidoes/' . $MainFile;
                }
                
                $status = true;
                $Message = "Data Save Success";
                $errorCode = 0;
                /*********** Video File Save End   *************/
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


    public function ListChapterVideo()
    {
        if(chapter::all()->isEmpty())
        {
            $data =  "No Record Found...";
            $status = true;
        } 
        else
        {
            // $data = chapter::Join('skill_level_headings', 'chapters.levelId', '=', 'skill_level_headings.id') ->select("skill_level_headings.LevelNumber", "skill_level_headings.LevelHeading", "chapters.chapter", "chapters.Status", "chapters.id","chapters.levelId","chapters.description","chapters.ChapterType")->get();

            $data = chapter::Join('skill_level_headings', 'chapters.levelId', '=', 'skill_level_headings.id')
                    ->JOIN('videos', 'videos.chapter_id','=','chapters.id') 
                    ->select("skill_level_headings.LevelNumber", "skill_level_headings.LevelHeading", "chapters.chapter", "chapters.Status", "chapters.id","chapters.levelId","chapters.description","chapters.ChapterType")
                    ->groupBy('chapters.id')
                    ->get();

             // $data =  chapter::orderBy('levelId')->get(); ;
            $status = true;
            
        }
        return ResponseBuilder::resultList($data);

        //..  return ResponseBuilder::result($status, $data);
    }


    public function ListVideos(Request $_Request)
    {
        // dd($_Request->input('chapter_id'));
        if(video::all()->isEmpty())
        {
            $data =  "No Record Found";
            $status = false;
        }
        else
        {
        $chapter_id =  $_Request->input('chapter_id');
        $Student_ID =  $_Request->input('StudentID');
        // dd($Student_ID);

        $data =  video::where(['chapter_id' => $_Request->input('chapter_id'), 'Status'=>1])->get();
        $status = true;
        // dd($data);
        }
        return ResponseBuilder::resultList($data);

        //..  return ResponseBuilder::result($status, $data);
    }

     public function storeA(Request $request)
    {
        $response = null;
        $user = (object) ['image' => ""];
        if ($request->hasFile('image')) {
            $original_filename = $request->file('image')->getClientOriginalName();
            $original_filename_arr = explode('.', $original_filename);
            $file_ext = end($original_filename_arr);
            $destination_path = './ChapterVideo/';
            $image = 'U-' . time() . '.' . $file_ext;

            if ($request->file('image')->move($destination_path, $image)) {
                $user->image = '/ChapterVideo/' . $image;
                return $this->responseRequestSuccess($user);
            } else 
            {
                return $this->responseRequestError('Cannot upload image');
            }
        }
        else 
        {
            return $this->responseRequestError('File not found');
        }
    }




    /**************** Videos Section Start ***************************/
    
    /**************** Videos Section End   ***************************/


}
