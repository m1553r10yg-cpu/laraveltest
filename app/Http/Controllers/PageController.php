<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        return view('welcome');
    }

    public function about(): string
    {
        return 'Страница о нашем проекте';
    }

    public function show(string $id): string
    {
        return 'Страница пользователя №'.$id;
    }

    public function hello(string $name = 'гость'): View
    {
        return view('hello', ['name' => $name]);
    }

    public function showComment(string $post, string $comment): string
    {
        return "Статья №{$post}, комментарий №{$comment}";
    }

    public function showPost(string $id): string
    {
        return 'Просмотр статьи №'.$id;
    }
}
