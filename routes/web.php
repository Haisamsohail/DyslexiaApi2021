<?php
use Illuminate\Support\Facades\Mail;


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/


$router->post('/login', 'AuthController@postLogin');
$router->post('/mail', 'AuthController@mail');
$router->post('/registerUser', 'AuthController@registerUser');
$router->post('/ListUsers', 'AuthController@ListUsers');
$router->post('/ParentsList', 'AuthController@ParentsList');
$router->post('/TeachersList', 'AuthController@TeachersList');
$router->post('/logout', 'AuthController@logout');
$router->post('/ApproveUnApproveUser', 'AuthController@ApproveUnApproveUser');

$router->post('/CreateHearAboutUs', 'DropDownList@CreateHearAboutUs');
$router->post('/ListHearAboutUs', 'DropDownList@ListHearAboutUs');

$router->post('/CreateUsertype', 'UsertypeList@CreateUsertype');
$router->post('/ListUsertype', 'UsertypeList@ListUsertype');
$router->post('/ListUsertypeForRegister', 'UsertypeList@ListUsertypeForRegister');


$router->post('/ChildStudentLogin', 'ChildStudentController@ChildStudentLogin');
$router->post('/RegisterChildStudent', 'ChildStudentController@RegisterChildStudent');
$router->post('/RegisterChildStudentUpdate', 'ChildStudentController@RegisterChildStudentUpdate');
$router->get('/ChildStudentList/{ParentID}', 'ChildStudentController@ChildStudentList');
$router->get('/ChildStudentBYID/{id}', 'ChildStudentController@ChildStudentBYID');


$router->post('/AddUpdateSkillLevelHeading', 'SkillLevelHeadingController@AddUpdateSkillLevelHeading');
$router->post('/ListSkillLevelHeading', 'SkillLevelHeadingController@ListSkillLevelHeading');
$router->post('/ListSkillLevelHeadingHaveChapter', 'SkillLevelHeadingController@ListSkillLevelHeadingHaveChapter');
$router->post('/ListSkillLevelHeadingDetail', 'SkillLevelHeadingController@ListSkillLevelHeadingDetail');


$router->post('/AddUpdateChapterWord', 'SkillLevelHeadingController@AddUpdateChapterWord');
$router->post('/CreateChapterWord', 'ChapterController@CreateChapterWord');
$router->post('/CreateChapter', 'ChapterController@CreateChapter');
$router->post('/ListChapter', 'ChapterController@ListChapter');
$router->post('/ListChapterWord', 'ChapterController@ListChapterWord');
$router->post('/ListWords', 'ChapterController@ListWords');
$router->post('/AddUpdatePassingCriteria', 'ChapterController@AddUpdatePassingCriteria');
$router->post('/ListPassingCriteria', 'ChapterController@ListPassingCriteria');
$router->post('/ChapterWordsDescriptionList', 'ChapterController@ChapterWordsDescriptionList');
$router->post('/AddUpatePoints', 'ChapterController@AddUpatePoints');
$router->post('/WordCount', 'ChapterController@WordCount');
$router->post('/CreateChapterVideo', 'ChapterController@CreateChapterVideo');
$router->post('/storeA', 'ChapterController@storeA');
$router->post('/store_', 'ChapterController@store_');

$router->post('/ProgressReportMaster', 'ProgressRepoerController@ProgressReportMaster');


/**************  Help Start *************************** */
$router->post('/AddUpdateHelp', 'HelpController@AddUpdateHelp');
$router->post('/HelpList', 'HelpController@HelpList');
/**************  Help End   *************************** */


$router->get('/GetChaptersList/{levelId}', 'ChapterController@GetChaptersList');
$router->get('/GetChapterDetail/{levelId}/{chapter}', 'ChapterController@GetChapterDetail');
$router->post('/ListChapterVideo', 'ChapterController@ListChapterVideo');
$router->post('/ListVideos', 'ChapterController@ListVideos');



/**************** Videos Section Start ***************************/
    
/**************** Videos Section End   ***************************/




$router->get('/EmailTest', function(){
	$Data =[
		'title' => 'Test Title Leveler 1',
		'Content' => 'Test Content Leveler'
	];
	Mail::send('test', $Data, function($message){
		$message->to('haisamsohail@gmail.com','Haisam')->subject('Subject: Haisam Testing Leveler');
	});
});



/****** Release 2 Middleware Auth Start *****************/
// $router->group( [
// 				'middleware' => "auth"
// 				], 
// 				function ($router)
// 				{
//     				//..**************** HearAboutUs Start
// 					$router->post('/CreateHearAboutUs', 'DropDownList@CreateHearAboutUs');
// 					$router->post('/ListHearAboutUs', 'DropDownList@ListHearAboutUs');

// 					$router->get('/test', 'ExampleController@test');

// 				});
// /****** Release 2 Middleware Auth End   *****************/




$router->get('/', function () use ($router) {
    return $router->app->version();
});


//.	$router->post('/LoginUser', 'UserDysController@LoginUser');
$router->post('/CreateUser', 'UserDysController@CreateUser');
$router->post('/UpdateUser', 'UserDysController@UpdateUser');
$router->post('/DeleteUser', 'UserDysController@DeleteUser');


$router->post('/insertorm', 'Project@insertorm');
$router->post('/CreatePro', 'Project@CreatePro');
$router->get('/list', 'Youtube@list');
$router->post('/order', 'Youtube@order');


// $router->group(['prefix' => 'api'], function () use ($router) {
//    // Matches "/api/register
//    $router->post('register', 'AuthController@register');

// });


//..	$router->post('/login', 'ExampleController@_Login');
// $router->group(['middleware' => "auth"], function ($router)
// {
// 	$router->get('/test', 'ExampleController@test');
// });

