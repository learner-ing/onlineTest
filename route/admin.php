<?php
Route::group('api/admin', function () {
    Route::get('users','admin/user/getUsers');
    Route::delete('users','admin/user/deleteUser');
    Route::put('users','admin/user/changeUser');
    Route::post('users','admin/user/searchUser');
    Route::get('exams','admin/exam/getExams');
    Route::post('exams','admin/exam/addExam');
    Route::post('exam','admin/exam/searchExam');
    Route::delete('exams','admin/exam/deleteExam');
    Route::put('exams','admin/exam/changeExam');
    Route::get('questions','admin/exam/getQuestions');
    Route::post('questions','admin/exam/addQuestions');
    Route::delete('questions','admin/exam/deleteQuestions');
    Route::post('question','admin/exam/searchQuestion');
    Route::get('rank','admin/exam/rank');
});