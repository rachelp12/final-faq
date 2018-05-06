<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Tag;
use App\Question;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($tag)
    {
        $tag = Tag::find($tag);
        $questions = $tag->questions()->get();
        for($i = 0; $i < count($questions); $i++)
        {
            $questions[$i]->customQuestionBody = $this->parseQuestionBody($questions[$i], $tag);
        }
        return view('tag', ['questions'=> $questions, 'tag' => $tag]);
    }

    /*
     * If there is any tag, replace it with href link
     */
    function parseQuestionBody($question, $tag)
    {
        $body = $question-> body;
        $hashtags = $question->tags()->get();
        for($i = 0; $i < count($hashtags); $i++)
        {
            $id = $hashtags[$i]->id;
            $url = url("../tag/{$id}/questions");
            if ($id == $tag->id)
            {
                $html = '<a href="'. $url.'"><b>'.$hashtags[$i]->tname.'</b></a>';
            } else
            {
                $html = '<a href="'. $url.'">'.$hashtags[$i]->tname.'</a>';
            }

            $body = str_replace($hashtags[$i]->tname, $html, $body);
        }
        return $body;
    }

}
