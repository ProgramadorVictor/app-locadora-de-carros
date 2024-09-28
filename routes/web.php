<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
$query_string = 'http://127.0.0.1:8000/nome?=victor&idade?=20'; //Isso aqui é uma 'Query String'