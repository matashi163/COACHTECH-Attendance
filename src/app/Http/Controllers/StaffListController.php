<?php

namespace App\Http\Controllers;

use App\Models\User;

class StaffListController extends Controller
{
    public function viewStaffList()
    {
        $users = User::all();

        $userDatas = [];
        foreach ($users as $user) {
            $userDatas[] = [
                'name' => $user->name,
                'email' => $user->email,
                'detail_url' => '/admin/attendance/staff/' . $user->id,
            ];
        }

        return view('staff_list', compact('userDatas'));
    }
}
