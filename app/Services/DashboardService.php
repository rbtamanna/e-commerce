<?php

namespace App\Services;

use App\Repositories\DashboardRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DashboardService
{
    private $dashboardRepository;

    public function __construct(DashboardRepository $dashboardRepository)
    {
        $this->dashboardRepository = $dashboardRepository;
    }

    public function getAllProducts()
    {
        return $this->dashboardRepository->getAllProducts();
    }
    public function getProfile()
    {
        return $this->dashboardRepository->getProfile();
    }

    public function getStudentProfile()
    {
        return $this->dashboardRepository->getStudentProfile();
    }

    public function updateProfile($data)
    {
        $img_file_name = '';
        $bc_file_name = '';
        if($data['image'])
        {
            $img_extension = $data['image']->getClientOriginalExtension();
            $img_file_name = random_int(00001, 99999).'.'.$img_extension;
            $img_file_path = 'student/images/'.$img_file_name;
            Storage::disk('public')->put($img_file_path, file_get_contents($data['image']));
        }
        if($data['birth_certificate'])
        {
            $bc_extension = $data['birth_certificate']->getClientOriginalExtension();
            $bc_file_name = random_int(00001, 99999).'.'.$bc_extension;
            $bc_file_path = 'student/birth_certificate/'.$bc_file_name;
            Storage::disk('public')->put($bc_file_path, file_get_contents($data['birth_certificate']));
        }
        return $this->dashboardRepository->setFirstName($data['first_name'])
            ->setLastName($data['last_name'])
            ->setDob(Carbon::createFromFormat('d-m-Y', $data['dob'])->format('Y-m-d'))
            ->setFatherName($data['father_name'])
            ->setMotherName($data['mother_name'])
            ->setImage($img_file_name? $img_file_name:'')
            ->setBirthCertificate($bc_file_name? $bc_file_name:'')
            ->setUpdatedAt(date('Y-m-d H:i:s'))
            ->updateProfile();
    }

    public function getProfileInfo()
    {
        $result = $this->dashboardRepository->getProfileInfo();
        if ($result->count() > 0) {
            $data = array();
            foreach ($result as $key=>$row) {
                $id = $row->id;
                $name = $row->first_name.' '.$row->last_name;
                $email = $row->email;
                $father_name = $row->father_name;
                $mother_name = $row->mother_name;
                $url = asset('storage/student/images/'. $row->image);
                $img = "<td> <img src=\"$url\" class=\"w-100 rounded\" alt=\"user_img\"></td>";

                $temp = array();
                array_push($temp, $key+1);
                array_push($temp, $img);
                array_push($temp, $name);
                array_push($temp, $email);
                array_push($temp, $father_name);
                array_push($temp, $mother_name);
                array_push($data, $temp);
            }
            return json_encode(array('data'=>$data));
        } else {
            return '{
                "sEcho": 1,
                "iTotalRecords": "0",
                "iTotalDisplayRecords": "0",
                "aaData": []
            }';
        }
    }
}
