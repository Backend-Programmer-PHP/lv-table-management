<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function index()
    {
        return view('register');
    }
     //Xu ly du lieu register
     public function userRegisterSubmit(Request $request)
     {
         $this->validate($request, [
             'name' => 'string|required|min:2',
             'email' => 'string|required|unique:users,email',
             'password' => 'required|min:6|confirmed',
         ]);
         $data = $request->all();
         // dd($data);
         $check = $this->create($data);
         $check['status'] = 'inactive';
         $check->save();
         // dd($check);
         Session::put('user', $data['email']);
         if ($check) {
             request()->session()->flash('success', 'Successfully registered!Please confrfirm your email!');
             $userActivation = new UserActivation;
             $activation = new ActivationService($userActivation);
             $activation->sendActivationMail($check);
             $details = [
                 'title' => 'New user registed',
                 'actionURL' => route('users.index'),
                 'fas' => 'fa-file-alt'
             ];
             $users = User::where('role', 'admin')->first();
             Notification::send($users, new StatusNotification($details));
             return redirect()->route('index');
         } else {
             request()->session()->flash('error', 'Please try again!');
             return back();
         }
     }
      //Xu ly user logout
    public function userLogout()
    {
        Session::forget('user');
        Auth::logout();
        request()->session()->flash('success', 'Logout successfully');
        return back();
    }
    //Xu ly dang ky user
    public function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'status' => 'active'
        ]);
    }
}
