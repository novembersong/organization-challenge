<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrganizationRequest;
use App\Models\Organizations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizationsController extends Controller
{
    public function index(Request $request){

        $user_organization = Auth::user()->id_organization;

        $filter = $request->filter;

        if ($user_organization == 0){
            if ($filter != null){
                $list = Organizations::where('name','like','%'.$filter.'%')
                    ->get();
            }else{
                $list = Organizations::all();
            }
        }else{
            if ($filter != null){
                $list = Organizations::where('name','like','%'.$filter.'%')
                    ->where('id', '=', $user_organization)
                    ->get();
            }else{
                $list = Organizations::where('id', '=', $user_organization)
                    ->get();
            }
        }

        return view('organizations.index',compact('list'));
    }

    public function registPage(){
        return view('organizations.register');
    }

    public function saveData(StoreOrganizationRequest $request){

        $validated = $request->validated();

        $save = new Organizations();

        $save->name = $request->org_name;
        $save->phone = $request->org_phone;
        $save->email = $request->email_address;
        $save->website = $request->website;


        if($request->hasFile('logo')){

//            $name = $request->file('logo-file-upload');
            $imageName = time().'.'.$request->logo->extension();
//            $path_logo = $request->logo->storeAs('organizationslogo', $imageName);
            $path_logo = $request->logo->move(public_path('/organizationslogo'), $imageName);

            $save->logo = $imageName;
        }

        $save->save();

        return redirect()->route('organizations.index')->with('success','save new organization');
    }

    public function editPage($id){
        $orgData = Organizations::find($id);

        return view('organizations.edit',compact('orgData'));
    }

    public function editData(StoreOrganizationRequest $request){

        $validate = $request->validated();

        $organization = Organizations::find($request->id);

        $organization->name = $request->org_name;
        $organization->phone = $request->org_phone;
        $organization->email = $request->email_address;
        $organization->website = $request->website;


        if($request->hasFile('logo')){

            $imageName = time().'.'.$request->logo->extension();
            $path_logo = $request->logo->move(public_path('/organizationslogo'), $imageName);

            $organization->logo = $imageName;
        }

        $organization->save();

        return redirect()->route('organizations.index')->with('success','Update organization');
    }

    public function deleteData($id){
        $organization = Organizations::find($id);

        $organization->delete();

        return redirect()->route('organizations.index')->with('success','Deleted Data');
    }


}
