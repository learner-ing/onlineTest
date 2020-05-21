<?php
Route::get('', 'index/view/view');

Route::group('api', function () {
    Route::post('login', 'index/index/login');
    Route::post('register', 'index/index/register');
    Route::get('logout', 'index/user/logout');
    Route::get('users', 'index/user/getUser');
    Route::put('users', 'index/user/changeInfo');
    Route::put('password', 'index/user/changePassword');
    Route::get('exams', 'index/exam/getExams');
    Route::post('exams', 'index/exam/examSubmit');
    Route::get('rank', 'index/exam/rank');
});
