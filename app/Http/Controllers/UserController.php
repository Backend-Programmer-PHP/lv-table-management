<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\User;
class UserController extends Controller
{
    // Xử lý dữ liệu bảng : 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::orderBy('id', 'DESC')->paginate(5);
        return view('users.index')->with('users', $users);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->validate(
            $request,
            [
                'name' => 'string|required|max:30',
                'email' => 'string|required|unique:users',
                'password' => 'string|required',
                'role' => 'required|in:admin,user',
                'status' => 'required|in:active,inactive',
                'photo' => 'string|nullable',
            ]
        );
        $data = $request->all();
        $data['password'] = Hash::make($request->password);
        $status = User::create($data);
        if ($status) {
            request()->session()->flash('success', 'Successfully added user');
        } else {
            request()->session()->flash('error', 'Error occurred while adding user');
        }
        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $user = User::findOrFail($id);
        return view('users.edit')->with('user', $user);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $user = User::findOrFail($id);
        $this->validate(
            $request,
            [
                'name' => 'string|required|max:30',
                'email' => 'string|required',
                'role' => 'required|in:admin,user',
                'status' => 'required|in:active,inactive',
                'photo' => 'nullable|string',
            ]
        );
        // dd($request->all());
        $data = $request->all();
        $status = $user->fill($data)->save();
        if ($status) {
            request()->session()->flash('success', 'Successfully updated user');
        } else {
            request()->session()->flash('error', 'Error occurred while updating user');
        }
        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $user = User::findorFail($id);
        $status = $user->delete();
        if ($status) {
            request()->session()->flash('success', 'User Successfully deleted');
        } else {
            request()->session()->flash('error', 'There is an error while deleting users');
        }
        return redirect()->route('users.index');
    }
    //Trang đăng ký
    public function register()
    {
        return view('register');
    }
    //Trang đăng nhập
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
            return redirect('users');
           
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
