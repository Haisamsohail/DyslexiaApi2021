<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Helper\ResponseBuilder;
use Exception;
use Validator,Redirect,Response,File;
use Illuminate\Support\Facades\Storage;
use App\Help;


class HelpController extends Controller
{
    
    public function index()
        {
            print_r('expression');
        }

    public function create()
        {
        }

    public function store(Request $request)
        {
        }

    public function show(Help $help)
        {
        }

    public function edit(Help $help)
        {
        }

    public function update(Request $request, Help $help)
        {
        }

    public function destroy(Help $help)
        {
        }


    public function AddUpdateHelp(Request $_Request)
    {
        $this->validate($_Request, 
            [
                'Heading'=>'required',
                'DetailContent'=>'required'
            ]);

        // dd($_Request->input());

        if($_Request->input('id') > 0)
        {
            $Help = Help::find($_Request->input('id'));
            // dd($Help);
            $Help->Heading = $_Request->input('Heading');
            $Help->DetailContent = $_Request->input('DetailContent');
            $Help->FileType = $_Request->input('FileType');
            $Help->Status = $_Request->input('Status');
        } 
        else
        {
            $Help = new Help(
            [
                "Heading" => $_Request->input('Heading'),
                "DetailContent" => $_Request->input('DetailContent'),
                "FileType" => $_Request->input('FileType')
            ]);
        }
         
        try 
        {
            if($Help->save())
            {
                $Help->id;
                $Container = (object) ['file' => ""];
                $FileName = $_Request->file('file')->getClientOriginalName();
                $Filename_arr = explode('.', $FileName);
                $FileExt = end($Filename_arr);
                $MainFile = $Help->id.'.' . $FileExt;

                $Help_ = Help::find($Help->id);
                $Help_->FileName = $MainFile;
                $Help_->save();
                
                if($_Request->input('FileType') == 'Word')
                {
                    $DestinationPath = './HelpWord/';
                }
                else if($_Request->input('FileType') == 'PDF')
                {
                    $DestinationPath = './HelpPDF/';
                }
                else if($_Request->input('FileType') == 'Video')
                {
                    $DestinationPath = './HelpVideo/';
                }
                else 
                {
                    $DestinationPath = './HelpImage/';
                }

                
                

                if ($_Request->file('file')->move($DestinationPath, $MainFile)) 
                {
                    if($_Request->input('FileType') == 'Word')
                    {
                        $Container->MainFile = '/HelpWord/' . $MainFile;
                    }
                    else if($_Request->input('FileType') == 'PDF')
                    {
                        $Container->MainFile = '/HelpPDF/' . $MainFile;
                    }
                    else if($_Request->input('FileType') == 'Video')
                    {
                        $Container->MainFile = '/HelpVideo/' . $MainFile;
                    }
                    else 
                    {
                        $Container->MainFile = '/HelpImage/' . $MainFile;
                    }                    
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
        return ResponseBuilder::result($status, $Message, $errorCode);
    }

    public function HelpList()
    {
        if(Help::all()->isEmpty())
        {
            $data =  "No Record Found";
            $status = false;
        }
        else
        {
            $data =  Help::orderBy('id')->get(); 
            //all()->sortBy("LevelNumber");
            $status = true;
            
        }
        return ResponseBuilder::resultList($data);

        //..  return ResponseBuilder::result($status, $data);
    }
}
