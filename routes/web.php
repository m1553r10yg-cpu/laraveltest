<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/about', function () {
    return 'Страница о нашем проекте';
});

Route::get('/user/{id}', function (string $id) {
    return 'Страница пользователя №'.$id;
})->whereNumber('id');

Route::get('/hello/{name?}', function (string $name = 'гость') {
    return 'Привет, '.e($name).'!';
})->name('hello');

Route::get('/posts/{post}/comments/{comment}', function (string $post, string $comment) {
    return "Статья №{$post}, комментарий №{$comment}";
})->whereNumber(['post', 'comment']);

Route::get('/posts/{id}', function (string $id) {
    return 'Просмотр статьи №'.$id;
})->whereNumber('id')->name('posts.show');
