<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthRepository
{
    private $userId, $password, $email, $name, $address, $phone, $created_at;

    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
        return $this;
    }

   public function register()
   {
       $user = new User();
       $user->name = $this->name;
       $user->email = $this->email;
       $user->address = $this->address;
       $user->phone_number = $this->phone;
       $user->password = Hash::make($this->password);
       $user->role = Config::get('variable_constants.role.customer');
       $user->created_at = $this->created_at;
       return $user->save();
   }

    public function changePassword()
    {
        return DB::table('users')
            ->where('id','=',auth()->user()->id)
            ->update([
                'password'=> Hash::make($this->password)
            ]);
    }

    public function forgetPassword()
    {
        return DB::table('users')
            ->where('email','=',$this->email)
            ->update([
                'password'=> Hash::make($this->password)
            ]);
    }
    public function getUserPassword()
    {
        $user = DB::table('users')->where('id', '=',auth()->user()->id)->select('password')->first();
        return $user->password;
    }
}
