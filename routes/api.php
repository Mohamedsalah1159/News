<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
define("PAGINATION_COUNT", 10);
Route::group(['namespace'=>'App\Http\Controllers'], function(){
    ##################### start rejester and login and out ##############
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
    Route::post('logout', 'AuthController@logout');
    Route::get('user-profile', 'AuthController@userProfile');
    ##################### end rejester and login and out ##############
        Route::group(['middleware' => 'auth:api'], function(){
        ############## start gallary ###############
        Route::post('savegallary', 'GallaryController@store');
        Route::post('removegallary/{id}', 'GallaryController@destroy');
        ##############end gallary##################
        #################start video ##############
        Route::post('savevideo', 'VideoContoller@store');
        Route::post('removevideo/{id}', 'VideoContoller@destroy');
        ################### end video #############
        #################start founder ##############
        Route::post('createfounder', 'FounderController@store');
        Route::post('removefounder/{id}', 'FounderController@destroy');
        ################### end founder #############
        #################start Governorate ##############
        Route::post('creategovernorate', 'GovernorateController@store');
        Route::post('removegovernorate/{id}', 'GovernorateController@destroy');
        ################### end Governorate #############
        #################start folder ##############
        Route::post('createFolder', 'FolderController@store');
        Route::post('updateFolder/{id}', 'FolderController@update');
        Route::post('deleteFolder/{id}', 'FolderController@destroy');
        ################### end folder #############
        #################start question ##############
        Route::post('createQuestion', 'QuestionController@store');
        Route::post('updateQuestion/{id}', 'QuestionController@update');
        Route::post('deleteQuestion/{id}', 'QuestionController@destroy');
        ################### end question #############
        #################start answer ##############
        Route::post('createAnswer', 'AnswerController@store');
        Route::post('updateAnswer/{id}', 'AnswerController@update');
        Route::post('changeStatus/{id}', 'AnswerController@changeStatus');
        Route::post('deleteAnswer/{id}', 'AnswerController@destroy');
        ################### end answer #############
        #################start exam ##############
        Route::post('createExam', 'ExamController@store');
        Route::post('updateExam/{id}', 'ExamController@update');
        Route::post('deleteExam/{id}', 'ExamController@destroy');
        ################### end exam #############

        });
    ############## start gallary ###############
    Route::get('getallgallary', 'GallaryController@getAllGallary');
    ##############end gallary##################
    #################start video ##############
    Route::get('getallvideos', 'VideoContoller@getAllVideos');
    ################### end video #############
    #################start founder ##############
    Route::get('getallfounder', 'FounderController@getAllFounder');
    ################### end founder #############
    #################start folder ##############
    Route::get('getallfolders', 'FolderController@getAllFolders');
    Route::get('getaOneFolder/{id}', 'FolderController@getOneFolder');
    ################### end folder #############
    #################start exam ##############
    Route::get('getallExams', 'ExamController@getAllExams');
    Route::get('getaOneExam/{id}', 'ExamController@getOneExam');
    Route::get('getaOneExamWithUser/{id}', 'ExamController@getOneExamWithUsers');
    Route::get('getUserWithHisExams/{id}', 'ExamController@getUserWithHisExams');
    ################### end exam #############
    #################start question ##############
    Route::get('getallquestions', 'QuestionController@getAllQuestions');
    Route::get('getaOnequestion/{id}', 'QuestionController@getOneQuestion');
    ################### end question #############
    #################start answer ##############
    Route::get('getAnswersAboutQuestion/{id}', 'AnswerController@getAllAnswerswithquestion');
    ################### end answer #############
    #################start Governorate ##############
    Route::get('getallgovernorate', 'GovernorateController@getAll');
    ################### end Governorate #############
    ##################### neeeeeews ###################
    Route::group(['namespace' => 'News'], function(){
        Route::group(['middleware' => 'auth:api'], function(){
            #################start news ##############
            Route::post('createnews', 'NewsController@store');
            Route::post('updatenews/{id}', 'NewsController@update');
            Route::post('changestatusnews/{id}', 'NewsController@changeStatus');
            Route::post('changeconstnews/{id}', 'NewsController@changeConst');
            Route::get('getallnews', 'NewsController@getAllNews');
            Route::get('getallnewsconst', 'NewsController@getAllNewsConst');
            Route::post('removenews/{id}', 'NewsController@destroy');
            ################### end news #############
            #################start words ##############
            Route::post('removewords/{id}', 'WordsController@destroy');
            ################### end words #############
            #################start source ##############
            Route::post('removesource/{id}', 'SourceController@destroy');
            ################### end source #############
            #################start newsimages ##############
            Route::post('createnewsimages', 'NewsimagesController@store');
            Route::post('removenewsimages/{id}', 'NewsimagesController@destroy');
            ################### end newsimages #############
            ################### start Idimages###############
            Route::post('createidimages', 'IdimageController@store');
            ################### start Idimages###############
        });
        #################start news ##############
        Route::get('getallnews', 'NewsController@getAllNews');
        Route::get('getallnewsconst', 'NewsController@getAllNewsConst');
        ################### end news #############
        #################start words ##############
        Route::get('getallwords/{id}', 'WordsController@getAll');
        ################### end words #############
        #################start source ##############
        Route::get('getallsources/{id}', 'SourceController@getAll');
        ################### end source #############
        #################start newsImages ##############
        Route::get('getAllNewsImages/{id}', 'NewsimagesController@getAll');
        ################### end newsImages #############

    });
    ################# start Initiatives #########################
    Route::group(['namespace' => 'Initiative'], function(){
       Route::group(['middleware' => 'auth:api'], function(){
        Route::post('create-initiative', 'InitiativeController@store');
        Route::post('update-initiative/{id}', 'InitiativeController@update');
        Route::post('delete-initiative/{id}', 'InitiativeController@destroy');
       });

       Route::get('getOne-initiative/{id}', 'InitiativeController@getOne');
       Route::get('getAll-initiative', 'InitiativeController@getAll');
    });
    #################### end Initiatives #######################
        ################# start government #########################
        Route::group(['namespace' => 'Governments'], function(){
            Route::group(['middleware' => 'auth:api'], function(){
            ############### start government into Governments ###########
             Route::post('create-government', 'GovernmentController@store');
             Route::post('changeReqStatus-government/{id}', 'GovernmentController@changeReqStatus');
             Route::post('update-government/{id}', 'GovernmentController@update');
             Route::post('delete-government/{id}', 'GovernmentController@destroy');
            ############### end government into Governments ###########
            ############### start hierarchical into Governments ###########
            Route::post('create-hierarchical', 'HierarchicalControlller@store');
            Route::post('update-hierarchical/{id}', 'HierarchicalControlller@update');
            Route::post('delete-hierarchical/{id}', 'HierarchicalControlller@destroy');
            ############### end hierarchical into Governments ###########
            ############### start employee into Governments ###########
            Route::post('create-employee', 'EmployeeController@store');
            Route::post('update-employee/{id}', 'EmployeeController@update');
            Route::post('delete-employee/{id}', 'EmployeeController@destroy');
            ############### end employee into Governments ###########

            });
            ############### start government into Governments ###########
            Route::get('getOne-government/{id}', 'GovernmentController@getOne');
            Route::get('getAll-governments', 'GovernmentController@getAll');
            ############### end government into Governments ###########

            ############### start hierarchical into Governments ###########
            Route::get('getAll-hierarchical', 'HierarchicalControlller@getAll');
            ############### end hierarchical into Governments ###########
            ############### start employee into Governments ###########
            Route::get('getAll-employee', 'EmployeeController@getAll');
            Route::get('getOne-employee/{id}', 'EmployeeController@getOne');
            ############### end employee into Governments ###########


         });
         #################### end government #######################

});


