<?php

namespace App\Http\Controllers;

use App\Services\OAuthService;
use Illuminate\Http\Request;

class OAuthController extends Controller
{
    protected OAuthService $service;

    public function __construct(OAuthService $service)
    {
        $this->service = $service;
    }

    public function redirect(Request $request)
    {
        return $this->service->redirect($request);
    }

    public function callback(Request $request)
    {
        $token = $this->service->callback($request);

        return response()->json(['token' => $token]);
    }
}
