# Практическая работа 01 — Установка Laravel через Composer

**Цель работы** — создать приложение Laravel, запустить локальный сервер, изменить приветственную страницу и разобраться, где лежат маршруты, контроллеры и представления.

## Проверка окружения и установка

Перед установкой я выполнил `php -v`, `composer -V` и `php -m`. На компьютере установлены PHP 8.5.10 и Composer 2.10.3. В списке модулей есть `mbstring`, `openssl`, `PDO`, `tokenizer`, `xml`, `ctype`, `json` и `pdo_sqlite`.

Создал проект `my-app` через Composer и проверил версию фреймворка:

```bash
composer create-project laravel/laravel my-app
cd my-app
php artisan --version
```

Установился Laravel 13.44.1. При создании проекта Composer загрузил зависимости, а Laravel сгенерировал ключ приложения, создал базу SQLite и применил начальные миграции.

## Запуск и изменение приветственной страницы

Запустил локальный сервер командой `php artisan serve` и открыл [http://127.0.0.1:8000](http://127.0.0.1:8000). В браузере появилась стандартная страница Laravel с заголовком «Let's get started» и ссылкой на документацию.

Затем я отредактировал `resources/views/welcome.blade.php` и обновил страницу в браузере. Заголовок изменился на «Привет! Это мой проект на Laravel.». Под ним появился текст «Я создал проект через Composer и изменил приветственную страницу.». Сервер продолжал работать, поэтому перезапуск не понадобился.

## Маршруты, контроллеры и представления

| Что открыть | Где лежит | Что показать |
| --- | --- | --- |
| Маршруты | [`routes/web.php`](routes/web.php) | Маршрут `GET /` возвращает представление `welcome`. |
| Контроллеры | [`app/Http/Controllers/`](app/Http/Controllers/) | Файл [`Controller.php`](app/Http/Controllers/Controller.php) содержит базовый класс контроллера приложения. |
| Представления | [`resources/views/`](resources/views/) | Файл [`welcome.blade.php`](resources/views/welcome.blade.php) содержит разметку страницы и новый текст. |

В `routes/web.php` корневой маршрут задан так:

```php
Route::get('/', function () {
    return view('welcome');
});
```

При переходе на `/` Laravel вызывает функцию из маршрута. Вызов `view('welcome')` загружает `resources/views/welcome.blade.php`, и браузер получает HTML страницы. Для этого маршрута обработчик записан прямо в `routes/web.php`, поэтому базовый контроллер здесь не вызывается.

## Проверка результата

Команда `php artisan route:list --except-vendor` показала маршрут `GET|HEAD /`, а `php artisan test` завершилась успешно: прошли оба штатных теста. В браузере открылось приветствие на русском языке.

# Практическая работа 02 — Маршрутизация в Laravel

В проекте `my-app` я описал шесть GET-маршрутов в [`routes/web.php`](routes/web.php). Главный маршрут `/` получил имя `home` и по-прежнему открывает приветственную страницу из первой работы.

## Проверка в браузере

Я открыл адреса на локальном сервере. Числа и имя из адресной строки появились в тексте ответа.

| Адрес | Результат в браузере |
| --- | --- |
| [`/`](http://127.0.0.1:8000/) | Привет! Это мой проект на Laravel. |
| [`/about`](http://127.0.0.1:8000/about) | Страница о нашем проекте |
| [`/user/7`](http://127.0.0.1:8000/user/7) | Страница пользователя №7 |
| [`/hello/Анна`](http://127.0.0.1:8000/hello/%D0%90%D0%BD%D0%BD%D0%B0) | Привет, Анна! |
| [`/posts/3/comments/12`](http://127.0.0.1:8000/posts/3/comments/12) | Статья №3, комментарий №12 |
| [`/posts/5`](http://127.0.0.1:8000/posts/5) | Просмотр статьи №5 |

Маршрут `/hello/{name?}` принимает необязательное имя. По адресу [`/hello`](http://127.0.0.1:8000/hello) Laravel использует значение по умолчанию, и браузер показывает «Привет, гость!». Метод `whereNumber()` разрешает только цифры в идентификаторах. При проверке `/user/abc`, `/posts/abc` и `/posts/3/comments/abc` сервер вернул HTTP `404`.

Имена `home`, `hello` и `posts.show` назначены методом `name()`. Через имя можно получить адрес. Например, `route('posts.show', ['id' => 5], false)` возвращает `/posts/5`. Третий аргумент `false` запрашивает относительный адрес. [Документация Laravel о маршрутизации](https://laravel.com/docs/13.x/routing#named-routes)

## Вывод списка маршрутов

Запустил `php artisan route:list --except-vendor`, чтобы показать маршруты из задания без служебных маршрутов Laravel.

```text
 GET|HEAD / .. home › routes/web.php:5
 GET|HEAD about .. routes/web.php:9
 GET|HEAD hello/{name?} .. hello › routes/web.php:17
 GET|HEAD posts/{id} .. posts.show › routes/web.php:25
 GET|HEAD posts/{post}/comments/{comment} .. routes/web.php:21
 GET|HEAD user/{id} .. routes/web.php:13

 Showing [6] routes
```

Для повторной демонстрации запустите `php artisan serve` из папки проекта и откройте адреса из таблицы. Команда `php artisan test` также завершилась успешно. Прошли оба штатных теста проекта.

# Практическая работа 03 — Контроллеры в Laravel

Командой `php artisan make:controller PageController` я создал [`PageController.php`](app/Http/Controllers/PageController.php) и перенёс в его методы обработку всех шести маршрутов. В [`routes/web.php`](routes/web.php) указал пары вида `[PageController::class, 'show']`. Имена маршрутов и ограничения на числовые параметры сохранились.

Метод `home()` возвращает приветственную страницу из первой работы. Метод `hello()` передаёт имя из адреса в [`hello.blade.php`](resources/views/hello.blade.php), где Blade выводит его в заголовке через `{{ $name }}`. Если имя пропущено, используется «гость».

## Проверка в браузере

| Адрес | Результат |
| --- | --- |
| [`/`](http://127.0.0.1:8000/) | Привет! Это мой проект на Laravel. |
| [`/hello/Анна`](http://127.0.0.1:8000/hello/%D0%90%D0%BD%D0%BD%D0%B0) | Привет, Анна! |
| [`/hello`](http://127.0.0.1:8000/hello) | Привет, гость! |
| [`/user/7`](http://127.0.0.1:8000/user/7) | Страница пользователя №7 |

## Вывод списка маршрутов

В выводе `php artisan route:list --except-vendor` у каждого учебного маршрута указан метод `PageController`.

```text
 GET|HEAD / .. home › PageController@home
 GET|HEAD about .. PageController@about
 GET|HEAD hello/{name?} .. hello › PageController@hello
 GET|HEAD posts/{id} .. posts.show › PageController@showPost
 GET|HEAD posts/{post}/comments/{comment} .. PageController@showComment
 GET|HEAD user/{id} .. PageController@show

 Showing [6] routes
```

Оба штатных теста прошли. Скриншоты демонстрации находятся в [Word-отчёте](REPORT.docx).
