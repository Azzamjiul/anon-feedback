<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class QuestionController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question' => ['required', 'min:10'],
            'need_auth' => ['required', 'boolean'],
            'passcode' => ['alpha_num', 'nullable']
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'data' => null,
                'errors' => $errors
            ], 400);
        }

        $validated = $validator->validated();

        $question = Question::create([
            'id' => Str::uuid(),
            'user_id' => auth()->user()->id,
            'question' => $validated['question'],
            'need_auth' => $validated['need_auth'],
            'passcode' => $validated['passcode'],
        ]);

        return response()->json([
            'message' => 'success',
            'data' => $question
        ]);
    }
}
