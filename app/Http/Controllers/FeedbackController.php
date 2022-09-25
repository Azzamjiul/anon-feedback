<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class FeedbackController extends Controller
{
    public function detail($id, Request $request)
    {
        $question = Question::where('id', $id)->first();

        if ($question->need_auth) {
            $hashedToken = str_replace('Bearer ', '', $request->header('authorization'));
            $token = PersonalAccessToken::findToken($hashedToken);

            if (is_null($token)) {
                return response()->json([
                    'message' => 'you need login to answer this question',
                ], 400);
            }
        }

        if ($question->passcode && ($question->passcode != $request->passcode)) {
            return response()->json([
                'message' => 'you need passcode to answer this question',
            ], 400);
        }

        return response()->json([
            'message' => 'success',
            'question' => $question->question
        ]);
    }

    public function feedback($id, Request $request)
    {
        $question = Question::where('id', $id)->first();
        $user = null;

        if ($question->need_auth) {
            $hashedToken = str_replace('Bearer ', '', $request->header('authorization'));
            $token = PersonalAccessToken::findToken($hashedToken);

            if (is_null($token)) {
                return response()->json([
                    'message' => 'you need login to answer this question',
                ], 400);
            }

            $user = $token->tokenable;
        }

        if ($question->passcode && ($question->passcode != $request->passcode)) {
            return response()->json([
                'message' => 'you need passcode to answer this question',
            ], 400);
        }

        $feedback = Answer::create([
            'id' => Str::uuid(),
            'question_id' => $id,
            'user_id' => is_null($user) ? null : $user->id,
            'sender' => $request->sender,
            'answer' => $request->feedback
        ]);

        return response()->json([
            'message' => 'success',
            'data' => $feedback
        ]);
    }
}
