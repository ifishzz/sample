<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\User;
use Auth;  //没有引命名空间错误

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', [
           'except' => ['show', 'create', 'store']
       ]);

        $this->middleware('guest', [
            'only' => ['create']
        ]);

        //只有这个三个不过滤，其他的都过滤，应该是白名单
    }

    public function index()
    {
        // $users=User::all();
        $users=User::paginate(10); //分页
        return view('users.index', compact('users'));  //分配变量到视图
    }


    public function create()
    {
        return view('users/create');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50',
          'email' => 'required|email|unique:users|max:255',
          'password' => 'required|confirmed|min:6'
      ]);
        $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
      ]);
        Auth::login($user);

        session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');
        return redirect()->route('users.show', [$user]);
    }


    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }


    public function update(User $user, Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50',
            'password' => 'nullable|confirmed|min:6'
        ]);

        $this->authorize('update', $user);


        $data=[];
        $data['name']=$request->name;
        if ($request->password) {
            $data['password']=bcrypt($request->password);
        }
        // $user->update([
        //     'name' => $request->name,
        //     'password' => bcrypt($request->password),
        // ]);
        $user->update($data);
        session()->flash('success', '个人资料更新成功！');

        return redirect()->route('users.show', $user->id);
    }

    public function destroy(User $user)
    {
        $this->authorize('destroy', $user);
        $user->delete();
        session()->flash('success', '成功刪除用戶');
        return back();
    }
}
