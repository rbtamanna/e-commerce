<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function index()
    {
        return view('backend.auth.login');
    }
    public function viewRegister()
    {
        return view('backend.auth.register');
    }

    public function register(RegisterRequest $request)
    {

        try {
            $response = $this->authService->register($request->validated());
            if ($response ) {
                return redirect('login');
            } else {
                return redirect()->back()->with('error', $response);
            }
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function authenticate(LoginRequest $request)
    {
        try {
            $response = $this->authService->authenticate($request->validated());
            if ($response === true) {
                return redirect('/');
            } else {
                return redirect()->back()->with('error', $response);
            }
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function logout()
    {
        $this->authService->logout();
        return redirect(route('viewLogin'));
    }

    public function viewChangePassword()
    {
        return view('backend.auth.changePassword');
    }

    public function viewForgetPassword()
    {
        return view('backend.auth.forgetPassword');
    }
    public function validateEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|exists:users,email',
        ]);
        if($validator->fails())
        {
            return [
                'success' => false,
                'email_error' => "This email not registered",
            ];
        }
        else
        {
            return [
                'success' => true,
                'email_error' => null,
            ];
        }
    }
    public function validatePasswords(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required|same:new_password'
        ]);
        if($validator)
        {
            return $this->authService->validatePasswords($request->all());
        }
        return redirect()->back()->with('error', 'All fields are required.');
    }

    public function forgetPassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|exists:users,email',
            ]);
            if(!$validator->fails())
            {
                $user = $this->authService->forgetPassword($request->all());
                if($user)
                    return redirect('login')->with('success', 'New password saved successfully.');
                return redirect()->back()->with('error', 'Sorry! password not updated!');
            }
            return redirect()->back()->with('error', 'Invalid.');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
    public function changePassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'current_password' => 'required',
                'new_password' => 'required',
                'confirm_password' => 'required|same:new_password'
            ]);
            if($validator)
            {

                $user = $this->authService->changePassword($request->all());
                if($user)
                    return redirect('/')->with('success', 'New password saved successfully.');
                return redirect()->back()->with('error', 'Sorry! password not updated!');
            }
            return redirect()->back()->with('error', 'All fields are required.');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
}
