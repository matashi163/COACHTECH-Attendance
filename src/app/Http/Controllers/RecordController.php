<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Carbon;

class RecordController extends Controller
{
    public function viewRecord()
    {
        $user = User::find(auth()->id());

        $status = $user->status;

        $nowTime = now();

        return view('record', compact('status', 'nowTime'));
    }
}
