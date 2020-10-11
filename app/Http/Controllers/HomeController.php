<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $articles = Article::where('user_id',Auth::user()->id)->where('belongsto','articles')->get();
        $working = Article::where('user_id',Auth::user()->id)->where('belongsto','working')->get();
        $done = Article::where('user_id',Auth::user()->id)->where('belongsto','done')->get();
        return view('home', compact('articles','working','done'));
    }

    public function gettodo()
    {
        $articles = Article::where('user_id',Auth::user()->id)->where('belongsto','articles')->get();
        echo "<hr>
        <h4>Remaining</h4>
        <hr>";
        foreach ($articles as $article) {
            echo   "<div class='card' id='$article->id'>";
            echo    "<div class='card-body'>";
            echo      "<h5 class='card-title'>$article->title<span class='float-right'><button class='btn' id='article' onclick='deletetodo($article->id)'><i class='fas fa-trash-alt fa-lg' style='color:red;'></i></button></span></h5><hr />";
            echo     "<p class='card-text'>$article->description</p>";
            echo    "</div>";
            echo  "</div>";

        }
    }

    public function gettodoworking()
    {
        $articles = Article::where('user_id',Auth::user()->id)->where('belongsto','working')->get();
        echo "<hr>
        <h4>Working</h4>
        <hr>";
        foreach ($articles as $article) {
            echo   "<div class='card' id='$article->id'>";
            echo    "<div class='card-body'>";
            echo      "<h5 class='card-title'>$article->title<span class='float-right'><button class='btn' id='article' onclick='deletetodo($article->id)'><i class='fas fa-trash-alt fa-lg' style='color:red;'></i></button></span></h5><hr />";
            echo     "<p class='card-text'>$article->description</p>";
            echo    "</div>";
            echo  "</div>";

        }
    }

    public function gettododone()
    {
        $articles = Article::where('user_id',Auth::user()->id)->where('belongsto','done')->get();
        echo "<hr>
        <h4>Done</h4>
        <hr>";
        foreach ($articles as $article) {
            echo   "<div class='card' id='$article->id'>";
            echo    "<div class='card-body'>";
            echo      "<h5 class='card-title'>$article->title<span class='float-right'><button class='btn' id='article' onclick='deletetodo($article->id)'><i class='fas fa-trash-alt fa-lg' style='color:red;'></i></button></span></h5><hr />";
            echo     "<p class='card-text'>$article->description</p>";
            echo    "</div>";
            echo  "</div>";

        }
    }

    public function deletetodo(Request $request)
    {
        Article::find($request->id)->delete();
        return json_encode(['success' => "deleted successfully"]);
    }

    public function updatetodo(Request $request)
    {
        Article::find($request->id)->update([
            'belongsto' => $request->belongsto,
        ]);
        return json_encode(['success' => "updated successfully"]);
    }

    public function addtodo(ArticleRequest $request)
    {
        Article::create([
            'title' => $request->title,
            'description' => $request->description,
            'belongsto' => 'articles',
            'user_id'   => Auth::user()->id,
        ]);

        return json_encode(['success' => "inserted successfully"]);
    }
}
