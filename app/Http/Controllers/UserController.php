<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Models\User;
use File;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
   
    public function index()
    {
        $users = User::orderBy('id','desc')->get();
        return UserResource::collection($users);
    }

    public function store(Request $request)
    {
        $user = new User();
        $user->fill($request->all());
        if($request->hasFile('photo')){
           $user->photo =  Storage::put('user', $request->file('photo'));
        }
        $user->save();
        return response(['Data Stored Successfully']);
    }

  
    public function update(Request $request, $id)
    {   
        $user = User::find($id);
        if($user){
            $user->fill($request->all());
            $old_file = $user->photo;
            if($request->hasFile('photo')){
                if ($user->photo != null){
                    $this->deleteFile($old_file);
                }
                $user->photo =  Storage::put('user', $request->file('photo'));
             }
            $user->save();
            return response(['Data Updated Successfully']); 
        }
        return response(['Data not found,please try again']);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if($user){
            if ($user->photo != null){
                $this->deleteFile($user->photo);
            }
            $user->delete(); 
            return response(['Data Deleted Successfully']);
        }else{
            return response(['Data not found,please try again']);
        }
       
    }

    private function deleteFile($path)
    {         
        if(Storage::exists($path)) {
            Storage::delete($path);
        }
    }
}
