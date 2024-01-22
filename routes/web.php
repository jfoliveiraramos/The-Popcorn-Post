<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagController;
use App\Http\Controllers\HomeController;

use App\Http\Controllers\TopicController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\AppealController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\ContentItemController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\TopicSuggestionController;
use App\Http\Controllers\UpvoteNotificationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Home
Route::redirect('/', '/home');


// Homepage
Route::controller(HomeController::class)->group(function () {
    Route::get('/home', 'show')->name('home');
    Route::get('/api/feed', 'getFeed');
    Route::get('/api/feed/{username}', 'getMemberFeed');
}); 


// Member
Route::controller(MemberController::class)->group(function () {
    Route::get('/members/{username}', 'showProfile')->name('member')->middleware('auth');
    Route::get('/members/{username}/edit', 'showEditProfile')->middleware('auth');
    Route::patch('/members/{username}', 'updateProfile');
    Route::patch('/members/{username}/promote', 'promote');
    Route::patch('/members/{username}/block', 'block');
    Route::patch('/members/{username}/unblock', 'unblock');
    Route::get('/members/{username}/settings', 'showSettings')->middleware('auth');
    Route::patch('/members/{username}/settings', 'updateSettings');
    Route::delete('/members/{username}', 'delete');
    Route::delete('/members/{username}/settings', 'deleteOwnAccount');
    Route::post('/api/members/{username}/follow', 'follow');
    Route::delete('/api/members/{username}/follow', 'unfollow');
    Route::get('/api/members/{username}/articles', 'getArticles');
    Route::get('/api/members/{username}/comments', 'getComments');
    Route::get('/api/members', 'getMembers');
    Route::get('/administration', 'showAdministration')->name('administration')->middleware('auth');
    Route::get('/administration/create_member', 'showCreateMember')->name('create.member')->middleware('auth');
    Route::post('/administration/create_member', 'createMember');
});


// Appeal
Route::controller(AppealController::class)->group(function () {
    Route::post('/appeals/create', 'create')->name('create.appeal');
    Route::get('/api/appeals', 'showAll')->name('appeals');
    Route::delete('/appeals/{id}', 'handle')->name('handle.appeal');
});


// Article
Route::controller(ArticleController::class)->group(function () {
    Route::get('/articles/create', 'showCreateArticle')->name('create.article')->middleware('auth');
    Route::post('/articles', 'create');
    Route::get('/articles/{id}', 'show')->name('articles');
    Route::get('/articles/{id}/comments', 'getComments');
    Route::get('/articles/{id}/edit', 'edit')->middleware('auth');
    Route::patch('/articles/{id}', 'update');
    Route::delete('/articles/{id}', 'delete');
});

Route::controller(ContentItemController::class)->group(function () {
    Route::post('/api/vote', 'vote');
});

// Comment
Route::controller(CommentController::class)->group(function () {
    Route::post('/api/articles/{id}/comments', 'create')->where('id', '[0-9]+');
    Route::delete('/api/articles/{article_id}/comments/{comment_id}', 'delete')->where('article_id', '[0-9]+')->where('comment_id', '[0-9]+');
    Route::patch('/api/articles/{article_id}/comments/{comment_id}', 'update')->where('article_id', '[0-9]+')->where('comment_id', '[0-9]+');
});


//Tag
Route::controller(TagController::class)->group(function () {
    Route::get('/tags', 'showAll')->name('all.tags');
    Route::get('/tags/{name}', 'show')->name('tags');
    Route::patch('/tags/{name}', 'update');
    Route::get('/api/tags/{name}/articles', 'getArticles');
    Route::post('/api/tags/{name}/follow', 'follow');
    Route::delete('/api/tags/{name}/follow', 'unfollow');
});


// Topic
Route::controller(TopicController::class)->group(function () {
    Route::get('/topics', 'showAll')->name('topics');
    Route::patch('/topics/{id}', 'update');
    Route::delete('/topics/{id}', 'delete');
});


// Topic Suggestion
Route::controller(TopicSuggestionController::class)->group(function () {
    Route::get('/topic_suggestions', 'showAll')->name('topic.suggestions');
    Route::post('/topic_suggestions', 'create');
    Route::patch('/topic_suggestions/{id}', 'update')->name('suggestions.update');
});


// Authentication
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'authenticate');
    Route::post('/logout', 'logout')->name('logout');
    Route::post('/recover_password', 'recoverPassword')->name('recover.password');
    Route::get('/reset_password/{token}', 'showPasswordResetForm')->name('reset.password');
    Route::post('/reset_password/{token}', 'resetPassword');
});


Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
});


// Search
Route::controller(SearchController::class)->group(function () {
    Route::get('/search', 'search')->name('search');
    Route::post('/api/search', 'searchUpdate');
});


// Static Pages
Route::view('/contact', 'pages.contact', ['title' => 'Contacts - The Popcorn Post'])->name('contact');
Route::view('/pages', 'pages.about', ['title' => 'About Us - The Popcorn Post'])->name('about');
Route::view('/sitemap', 'pages.sitemap', ['title' => 'Sitemap - The Popcorn Post'])->name('sitemap');


// Notification
Route::controller(NotificationController::class)->group(function () {
    Route::get('/api/notifications/{username}', 'getNotifications');
    Route::patch('/api/notifications/{id}', 'toggleRead')->where('id', '[0-9]+');
    Route::patch('/api/notifications/{username}', 'markAllAsRead');
});


Route::controller(UpvoteNotificationController::class)->group(function () {
    Route::post('/api/upvote_notifications', 'create');
    Route::get('/api/upvote_notifications', 'get');
    Route::patch('/api/upvote_notifications/{id}', 'update');
});


Route::controller(GoogleAuthController::class)->group(function () {
    Route::get('/auth/google','redirect')->name('login.google');
    Route::get('/auth/google/callback', 'callback');
    Route::get('/auth/google/register', 'showRegistrationForm')->name('register.google');
    Route::post('/auth/google/register', 'register');
    Route::get('/auth/google/link/', 'showLinkForm')->name('link.google');
    Route::post('/auth/google/link/', 'link');
});

Route::controller(FileUploadController::class)->group(function () {
    Route::post('/api/filepond/uploads/process', 'process')->name('uploads.process');
    Route::get('/api/filepond/load/{id}', 'load')->name('load');
    Route::get('/api/filepond/restore/{id}', 'restore')->name('restore');
    Route::delete('/api/filepond/delete', 'delete')->name('delete');
    Route::delete('/api/filepond/remove', 'remove')->name('remove');
});