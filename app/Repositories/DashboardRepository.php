<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DashboardRepository
{
    private $id, $first_name, $last_name, $dob, $father_name, $mother_name, $image, $birth_certificate, $created_at, $updated_at, $deleted_at;

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    public function setFirstName($first_name)
    {
        $this->first_name = $first_name;
        return $this;
    }

    public function setLastName($last_name)
    {
        $this->last_name = $last_name;
        return $this;
    }

    public function setDob($dob)
    {
        $this->dob = $dob;
        return $this;
    }

    public function setFatherName($father_name)
    {
        $this->father_name = $father_name;
        return $this;
    }

    public function setMotherName($mother_name)
    {
        $this->mother_name = $mother_name;
        return $this;
    }

    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    public function setBirthCertificate($birth_certificate)
    {
        $this->birth_certificate = $birth_certificate;
        return $this;
    }

    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    public function setDeletedAt($deleted_at)
    {
        $this->deleted_at = $deleted_at;
        return $this;
    }
    public function getAllProducts()
    {
        return DB::table('products as p')
            ->whereNull('p.deleted_at')
            ->leftJoin('product_categories as pc', 'pc.product_id', '=', 'p.id')
            ->leftJoin('categories as c', 'c.id', '=', 'pc.category_id')
            ->select('p.*', 'c.name as category_name', 'c.id as category_id')
            ->get();
    }

   public function getProfileInfo()
   {
       return DB::table('students_info as si')
           ->whereNull('si.deleted_at')
           ->leftJoin('users as u', 'u.id', '=', 'si.user_id')
           ->select('u.*', 'si.*', DB::raw('date_format(si.dob, "%d-%m-%Y") as dob'))
           ->get();
   }
   public function getProfile()
   {
       $user_id = auth()->user()->id ;
       return  DB::table('users as u')
           ->where('u.id', '=', $user_id)
           ->leftJoin('students_info as si', 'u.id', '=', 'si.user_id')
           ->select('u.*', 'si.*', 'u.id as user_id', DB::raw('date_format(si.dob, "%d-%m-%Y") as dob'))
           ->first();
   }

    public function getStudentProfile()
    {
        $user_id = auth()->user()->id ;
        return  DB::table('users as u')
            ->where('u.id', '=', $user_id)
            ->leftJoin('students_info as si', 'u.id', '=', 'si.user_id')
            ->select('u.*', 'si.*', 'u.id as user_id', DB::raw('date_format(si.dob, "%d-%m-%Y") as dob'))
            ->first();
    }

    public function updateProfile()
    {
        DB::beginTransaction();
        try {
            $user_id = auth()->user()->id;
            $user = DB::table('users')
                ->where('id', '=', $user_id)
                ->update([
                    'first_name' => $this->first_name,
                    'last_name' => $this->last_name,
                    'updated_at' => $this->updated_at,
                ]);
            if($user)
            {
                $updateData = [
                    'father_name' => $this->father_name,
                    'mother_name' => $this->mother_name,
                    'dob' => $this->dob,
                    'updated_at' => $this->updated_at,
                ];
                if ($this->image) {
                    $updateData['image'] = $this->image;
                }
                if ($this->birth_certificate) {
                    $updateData['birth_certificate'] = $this->birth_certificate;
                }
                DB::table('students_info')
                    ->where('user_id', '=', $user_id)
                    ->update($updateData);
            }
            DB::commit();
            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }
    }
}
