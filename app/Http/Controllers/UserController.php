<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function addUser(Request $request)
    {
        $roles = Role::get();

        return view('welcome', compact('roles'));
    }

    public function submitUser(Request $request)
    {
        if ($request->ajax() && $request->isMethod('post')) {
            $processData = $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'phone' => 'required|numeric|digits:10',
                'description' => 'required',
                'role_id' => 'required',
                'profile_image' => 'required|mimes:jpg,png,jpeg,gif',
            ]);
            $user = new User();
            $user->name = $processData['name'];
            $user->email = $processData['email'];
            $user->phone = $processData['phone'];
            $user->description = $processData['description'];
            $user->role_id = $processData['role_id'];
            $imageName = time().'_'.$processData['phone'].'.'.$request->profile_image->extension();
            $request->profile_image->move(public_path('profile_images'), $imageName);
            $user->profile_image = $imageName;
            $user->save();

            return response()->json(['status' => 'success', 'message' => 'user stored']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Invalid Request Sent']);
        }
    }

    public function userList(Request $request)
    {
        $datas = User::with('role')->latest('id')->get();

        return Datatables::of($datas)
                    ->addColumn('role', function (User $data) {
                        return $data->role->role_name;
                    })
                    ->addColumn('profile_pic', function (User $data) {
                        $imageUrl = asset('profile_images/'.$data->profile_image);
                        $profileImage = '<img src="'.$imageUrl.'" style="width:150px;height:150x;" />';

                        return $profileImage;
                    })
                    ->rawColumns(['role', 'profile_pic'])
                    ->toJson();
    }
}
