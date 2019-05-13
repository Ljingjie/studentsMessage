<?php

use Illuminate\Http\Request;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('user/login', 'UserController@login');

Route::group(['middleware'=>"checkToken"],function(){
    Route::get('user/logout','UserController@logout');
    Route::get('user/index','infoController@index');        // 显示当前专业学生信息
    Route::get('school/index','schoolController@index');    // 显示专业
    Route::get('school/showSubject','schoolController@showSubject'); // 显示科目
    Route::get('user/score/index','scoreController@index');
    Route::post('user/info/password','UserController@password');

    Route::group(['middleware'=>'checkTeacher'],function(){
        Route::post('user/student/information','UserController@detail'); // 添加学生信息
        Route::post('user/update','infoController@update');     // 更新学生信息
        Route::post('user/score/store','scoreController@store');
        Route::post('user/info/search','infoController@search');

        Route::group(['middleware'=>"checkAdmin"],function(){

            Route::post('user/register','UserController@register'); // 注册教师
            Route::get('user/teacher','UserController@teacher');    // get teacher information
            Route::delete('user/delete/teacher/{id}','UserController@delete'); // delete teacher
            Route::post('school/department','schoolController@department'); // 注册院系， 如果不存在则创建，存在无报错
            Route::post('school/major','schoolController@major');   // 注册专业 如果无对应院系，将会报错
            Route::post('school/subject','schoolController@subject'); // 注册科目
            Route::delete('school/destroy/{id}','schoolController@destroy'); // 删除专业
            Route::delete('user/destroy/{id}','infoController@destroy'); // 删除学生
        });
    });
});