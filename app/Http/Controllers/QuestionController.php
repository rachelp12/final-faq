<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Question;
use App\Tag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $question = new Question;
        $edit = FALSE;
        return view('questionForm', ['question' => $question,'edit' => $edit  ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->validate([
            'body' => 'required|min:5',
        ], [

            'body.required' => 'Body is required',
            'body.min' => 'Body must be at least 5 characters',

        ]);
        $input = request()->all();

        $question = new Question($input);

        $question->user()->associate(Auth::user());
        $question->save();

        $this->updateHashTags($question);

        return redirect()->route('home')->with('message', 'IT WORKS!');



        // return redirect()->route('questions.show', ['id' => $question->id]);

    }


    /*
     * First, we grab the unique hash tag from question body
     * Second, for each tag, if it already exists in tag table, we grab tag id;
     *  if not, insert new data and grab tag id
     * THen we attach each tag id to question (insert data to question_tag)
     */
    function updateHashTags($question)
    {
        $body = $question->body;
        preg_match_all("/(#\w+)/", $body, $matches);
        $hashtags = false;
        if($matches)
        {
            $array = array_count_values($matches[0]);
            $hashtags = array_keys($array);
        }

        if($hashtags != false)
        {
            for($i = 0; $i < count($hashtags); $i++)
            {
                $tag = DB::table('tags')->where('tname', $hashtags[$i])->first();
                // tag not exist, create one first
                if($tag == null)
                {
                    $tagId = DB::table('tags')->insertGetId(
                        ['tname' => $hashtags[$i], 'created_at' => now(),  'updated_at' => now()]
                    );
                } else {
                    $tagId = $tag -> id;
                }
                $question->tags()->attach($tagId);
            }
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
        return view('question')->with('question', $question);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question)
    {
        $edit = TRUE;
        return view('questionForm', ['question' => $question, 'edit' => $edit ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Question $question)
    {

        $input = $request->validate([
            'body' => 'required|min:5',
        ], [

            'body.required' => 'Body is required',
            'body.min' => 'Body must be at least 5 characters',

        ]);

        $question->body = $request->body;
        $question->save();

        // reset all existing tags and then re-add all tags, similar to store()
        $question->tags()->detach();
        $this->updateHashTags($question);

        return redirect()->route('questions.show',['question_id' => $question->id])->with('message', 'Saved');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        $question->tags()->detach();
        $question->delete();
        return redirect()->route('home')->with('message', 'Deleted');

    }
}
