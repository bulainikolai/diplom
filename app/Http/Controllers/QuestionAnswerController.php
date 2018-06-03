<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Question;
use Validator;

class QuestionAnswerController extends Controller
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $temp = $request->except('_token', 'save');
        $messages = [
            'required'=>'Поле :attribute обязательно к заполнению'
        ];
        $validator = Validator::make($temp, [
            'answer' => 'required'
        ], $messages);
        if ($validator->fails()) {
            return redirect()->route('formChangeAnswer', ['id' => $temp['topic']])->withErrors($validator);
        }
        $new_answer = Question::find($temp['topic']);
        $new_answer->answer = $temp['answer'];
        $new_answer->save();

        return redirect()->route('category', ['id' => $new_answer->topic_id])->with('status', "Ответ с id = {$temp['topic']} изменен!");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $answer = [];
        $result = Question::find($id); 
        $answer['answer'] = $result->answer;
        $answer['answer_id'] = $id;
        $lastTopic = $result->topic_id;
        
        return view('site.change_answer',  compact('answer', 'lastTopic'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $question = [];
        $result = Question::find($id);
        $question['question'] = $result->question;
        $question['author'] = $result->author_question;
        $question['question_id'] = $id;
        $lastTopic = $result->topic_id;

        return view('site.change_question', compact('question', 'lastTopic'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $temp = $request->except('_token', 'save');
        $messages = [
            'required'=>'Поле :attribute обязательно к заполнению',
            'maX'=>'Значение поля :attribute должно быть меннее 255 символов'
        ];
        $validator = Validator::make($temp, [
            'question' => 'required',
            'author_name' => 'required|max:255'
        ], $messages);
        if ($validator->fails()) {
            return redirect()->route('formChangeQuestion', ['topic' => $id])->withErrors($validator);
        }
        $question = Question::find($id);
        $question->update(['question' => $temp['question'], 'author_question' => $temp['author_name']]);

        return redirect()->route('category', ['id' => $question->topic_id])->with('status', "Вопрос с id = $id изменен!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}