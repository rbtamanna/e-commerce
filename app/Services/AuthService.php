<?php

namespace App\Services;

use App\Jobs\ResetPasswordJob;
use App\Models\User;
use App\Repositories\AuthRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    private $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function register($data)
    {
        return $this->authRepository->setName($data['name'])
            ->setEmail($data['email'])
            ->setAddress($data['address'])
            ->setPhone($data['phone'])
            ->setPassword($data['password'])
            ->setCreatedAt(date('Y-m-d H:i:s'))
            ->register();
    }

    public function authenticate($data) : mixed
    {
        if (Auth::attempt(['email' => $data['email'], 'password' => $data['password'], 'deleted_at' => null])) {
            $user = User::findOrFail(Auth::id());
            $user_data = [
                'user_info' => $user,
            ];
            session(['user_data' => $user_data]);
            return true;
        } else {
            return 'Bad Credentials';
        }
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->flush();
        return true;
    }
    public function validatePasswords($data)
    {
        $current_password_msg = null;
        $confirm_password_msg = null;
        $password = $this->authRepository->getUserPassword();
        if (!Hash::check($data['current_password'], $password)) {
            $current_password_msg = 'Current password not matched';
        }
        if ($data['new_password'] != $data['confirm_password']) {
            $confirm_password_msg = 'Confirm password not matched with new password';
        }
        $regex = '/^[a-zA-Z0-9]{6,}$/';
        if (!preg_match($regex, $data['new_password']) || !preg_match($regex, $data['confirm_password'])) {
            $confirm_password_msg = 'New password and confirm password must be alphanumeric and at least 6 characters long';
        }
        if ($current_password_msg || $confirm_password_msg) {
            return [
                'success' => false,
                'current_password_msg' => $current_password_msg,
                'confirm_password_msg' => $confirm_password_msg
            ];
        } else {
            return [
                'success' => true,
                'current_password_msg' => $current_password_msg,
                'confirm_password_msg' => $confirm_password_msg
            ];
        }
    }
    public function forgetPassword($data)
    {
        $password = random_int(000001, 999999) ;
        $user = $this->authRepository->setEmail($data['email'])->setPassword($password)->forgetPassword();
        if($user)
        {
            $data =[
                'password' =>  $password,
                'to' => $data['email'],
                'email' => 'rbtamannarbt@gmail.com',
                'name' => 'Student Management System',
                'subject' => "!!!New Password!!!",
            ];
            ResetPasswordJob::dispatch($data);
            return true;
        }
        return false;
    }
    public function changePassword($data)
    {
        $password = $this->authRepository->getUserPassword();
        if(!Hash::check($data['current_password'],$password) || $data['new_password']!=$data['confirm_password'])
            return false;
        return $this->authRepository->setPassword($data['new_password'])->changePassword();
    }
}
