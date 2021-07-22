<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\User;
class UserController extends Controller
{
    //
    public function index()
    {
        return view('register');
    }
    public function login()
    {
        return view('login');
    }
  
    //Duyệt đăng nhập
    public function storeLogin(Request $request)
    {
        $data = $request->all();
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {         
            request()->session()->flash('success', 'Successfully login'); 
            echo "<div class='content' style='font-weight: bold;text-align: center;'>
            <div class='title m-b-md' style='font-size: 50px;'>
                 Laravel
            </div>

            <div class='links'>
               
                <a href='http://localhost:70/Account/public/logout' style='text-decoration: none;'>Logout</a>
            </div>
        </div>";
           
        } else {
            request()->session()->flash('error', 'Invalid email and password pleas try again!');
            return back();
        }
    }
    // Duyệt đăng ký
    public function storeRegister(Request $request)
    {
          
        $this->validate($request, [
            'name' => 'string|required',
            'email' => 'string|required',
            'password' => 'required|min:8|confirmed',
        ]);
        $data = $request->all();
        $data['password'] = bcrypt($request->password);
        $status = User::create($data);
        if ($status) {
            request()->session()->flash('success', 'Successfully registered!Please confrfirm your email!');
            return redirect('/login');
        } else {
            request()->session()->flash('error', 'Please try again!');
            return back();
        }
         
      }
      //Đăng xuất
      public function Logout()
      {
          Auth::logout();
          return back();
      }
     
}
