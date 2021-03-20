<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\PasswordValidationRules;
use App\Models\Organizations;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Jetstream\Jetstream;

class UsersController extends Controller
{
    use PasswordValidationRules;

    public function index(Request $request){

        $user_organization = Auth::user()->id_organization;

        $filter = $request->filter;

        if ($user_organization == 0){
            if ($filter != null){
                $list = User::where('id_role','!=',1)
                    ->where('name','like','%'.$filter.'%')->get();
            }else{
                $list = User::where('id_role','!=',1)->get();
            }
        }else{
            if ($filter != null){
                $list = User::where('id_role','!=',1)
                    ->where('name','like','%'.$filter.'%')
                    ->where('id_organization','=', $user_organization)
                    ->get();
            }else{
                $list = User::where('id_role','!=',1)
                    ->where('id_organization','=', $user_organization)
                    ->get();
            }
        }


        return view('pic.index',compact('list'));
    }

    public function registPage(){
        $user_organization = Auth::user()->id_organization;

        if ($user_organization == 0){
            $organizations = Organizations::all();
        }else{
            $organizations = Organizations::where('id','=',$user_organization)->get();
        }

        return view('pic.register',compact('organizations'));
    }

    public function saveData(Request $request){

        $save = new User();

        $save->name = $request->pic_name;
        $save->email = $request->email_address;
        $save->id_role = $request->id_role;
        $save->id_organization = $request->id_organization;
        $save->phone = $request->pic_phone;
        $save->password = Hash::make($request->password);

        if($request->hasFile('photo')){

            $name = $request->file('photo')->getClientOriginalName();
            $imageName = $name.'.'.$request->photo->extension();
            $path_logo = $request->photo->move(public_path('/avatar'), $imageName);

            $save->profile_photo_path = $imageName;
        }

        $save->save();

        return redirect()->route('pic.index')->with('success','success add data');
    }

    public function editPage($id){
        $picData = User::find($id);

        $user_organization = Auth::user()->id_organization;

        if ($user_organization == 0){
            $organizations = Organizations::all();
        }else{
            $organizations = Organizations::where('id','=',$user_organization)->get();
        }

        return view('pic.edit',compact('picData','organizations'));
    }

    public function editData(Request $request){
        $pic = User::find($request->id);

        $pic->name = $request->pic_name;
        $pic->email = $request->email_address;
        $pic->id_role = $request->id_role;
        $pic->id_organization = $request->id_organization;
        $pic->phone = $request->pic_phone;

        if ($request->password != null){
            $pic->password = Hash::make($request->password);
        }



        if($request->hasFile('photo')){

            $name = $request->file('photo')->getClientOriginalName();
            $imageName = $name.'.'.$request->photo->extension();
            $path_logo = $request->photo->move(public_path('/avatar'), $imageName);

            $pic->avatar = $imageName;
        }

        $pic->save();

        return redirect()->route('pic.index')->with('success','Update PIC');
    }

    public function deleteData($id){
        $pic = User::find($id);

        $pic->delete();

        return redirect()->route('pic.index')->with('success','Deleted Data');
    }

}
