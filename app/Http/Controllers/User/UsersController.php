<?php

namespace App\Http\Controllers\User;
use App\Models\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return response()->json(['data'=>$users, 'count'=>$users->count()], 200);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed'
        ];

        $this->validate($request, $rules);

        $data = $request->all();

        $data['password'] = bcrypt($request->password);
        $data['verified'] = User::UNVERIFIED_USER;
        $data['verification_token'] = User::generateVerificationCode();
        $data['admin'] = User::REGULAR_USER;

        $user = User::create($data);

        return response()->json(['data' => $user], 201);



    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json(['data'=>$user], 200);

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
        $user = User::findOrFail($id);

        $rules = [
            'email' => 'email|unique:users,email,'.$user->id,
            'password' => 'min:8|confirmed',
            'admin' => 'in:'. User::REGULAR_USER . ',' . User::ADMIN_USER,
        ];

        $this->validate($request, $rules);

        if($request->has('name')) {
            $user->name = $request->name;
        }

        if($request->has('password')) {
            $user->password = bcrypt($request->password);
        }

        if($request->has('email')) {
            $user->email = $request->email;
            $user->verified = User::UNVERIFIED_USER;
            $user->verification_token = User::generateVerficationCode();
            $user->admin = User::REGULAR_USER;
        }

        if($request->has('admin')) {
            if(!$user->isVerified()) {
                return response()
                    ->json([
                        'error' => 'Only verified user can be admins!',
                        'code' => 409
                    ], 409);
            }
        }

        if(!$user->isDirty()) {
            return response()->json([
                'error'=>'You need to update some data!',
                'code'=>422
            ], 422);
        }

        $user->save();

        return response()->json(['data'=>$user], 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['data'=>$user], 204);

    }
}
