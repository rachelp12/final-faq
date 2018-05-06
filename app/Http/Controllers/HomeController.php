<?php

namespace App\Http\Controllers;

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

    /*
      * If there is any tag, replace it with href link
      */
    function parseQuestionBody($question)
    {
        $body = $question-> body;
        $hashtags = $question->tags()->get();
        for($i = 0; $i < count($hashtags); $i++)
        {
            $id = $hashtags[$i]->id;
            $url = url("/tag/{$id}/questions");
            $html = '<a href="'. $url.'">'.$hashtags[$i]->tname.'</a>';
            $body = str_replace($hashtags[$i]->tname, $html, $body);
        }
        return $body;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $questions = $user->questions()->paginate(6);
        for($i = 0; $i < count($questions); $i++)
        {
            $questions[$i]->customQuestionBody = $this->parseQuestionBody($questions[$i]);
        }
        return view('home')->with('questions', $questions);
    }
}
