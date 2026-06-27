<?php

namespace App\Http\Controllers;

use App\Http\Requests\Loginrqt;
use App\Http\Requests\Searchrqt;
use App\Models\User;
use App\Services\Authservice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected $authservice;

    public function __construct(Authservice $authservice)
    {
        $this->authservice = $authservice;
    }
    public function login(Loginrqt $request)
    {
        return $this->authservice->loginauth($request);
    }

    public function me(Searchrqt $request)
    {
        return $this->authservice->searchuser($request);
    }

    public function logout(Searchrqt $request)
    {
        return $this->authservice->logout($request);
    }
}
