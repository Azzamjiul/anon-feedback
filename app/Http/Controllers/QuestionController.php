<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        $limit = 10;

        if (!empty($request->limit)) {
            $limit = $request->limit;
        }

        $questions = Question::paginate($limit);
        return $questions;
    }

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

    public function detail($id)
    {
        $question = Question::where('id', $id)->first();

        return response()->json([
            'message' => 'success',
            'question' => $question
        ]);
    }
}
