<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function detail($id)
    {
        $question = Question::where('id', $id)->select(['question'])->first();

        return response()->json([
            'message' => 'success',
            'question' => $question
        ]);
    }

    public function feedback($id, Request $request)
    {
        $feedback = Answer::create([
            'id' => Str::uuid(),
            'question_id' => $id,
            'sender' => $request->sender,
            'answer' => $request->feedback
        ]);

        return response()->json([
            'message' => 'success',
            'data' => $feedback
        ]);
    }
}
