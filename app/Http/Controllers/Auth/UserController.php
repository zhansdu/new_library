<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $user = Session::get('user');
        return response()->json([
            'res' => [
                'user' => !empty($user) ? $user['info'] : null,
                'access_token' => !empty($user) ? $user['access_token'] : null,
            ]
        ]);
    }
}
