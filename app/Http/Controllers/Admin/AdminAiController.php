<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\OpenAiService;
use Illuminate\Http\Request;

class AdminAiController extends Controller
{
    private $aiService;
    public function __construct(OpenAiService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function index(Request $request)
    {
        return view('admin.ai.index');
    }

    public function send(Request $request)
    {
        $params = $request->all();
        $prompt = __('prompt.' . $params['type'], $params);
        $result = $this->aiService->send($prompt);

        return response()->json(['success' => true, 'data' => $result]);
    }
}
