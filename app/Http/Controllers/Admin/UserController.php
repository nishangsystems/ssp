<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassMaster;
use App\Models\TeachersSubject;
use App\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Session;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(\request('role')){
            $data['title'] = "Role ( ".\App\Models\Role::whereSlug(\request('role'))->first()->name." ) Users";
            $data['users'] =\App\Models\Role::whereSlug(\request('role'))->first()->users()->paginate(15);
            return view('admin.user.index')->with($data);
        }else if(\request('permission')){
            $data['title'] = "Permission ( ".\App\Models\Permission::whereSlug(\request('permission'))->first()->name." ) Users";
            $data['users'] =\App\Models\Permission::whereSlug(\request('permission'))->first()->users()->paginate(15);
            return view('admin.user.index')->with($data);
        }else{
            $data['users'] = \App\Models\User::where('type', request('type', 'teacher'))->paginate(15);
            $data['title'] = "Manage " . request('teacher', 'user') . 's';
            return view('admin.user.index')->with($data);
        }
    }

    public function create(Request $request)
    {
        $data['title'] = "Add User";
        return view('admin.user.create')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|unique:users',
            'phone' => 'required',
            'address' => 'nullable',
            'gender' => 'required',
            'type' => 'required',
        ]);

        $input = $request->all();
        $input['password'] = Hash::make('password');
        $input['username'] = $request->email;
        $user = \App\Models\User::create($input);

        return redirect()->to(route('admin.users.index', ['type' => $user->type]))->with('success', "User Created Successfully !");
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $data['title'] = "User details";
        $data['user'] = \App\Models\User::find($id);
        return view('admin.user.show')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $data['title'] = "Edit user details";
        $data['user'] = \App\Models\User::find($id);
        return view('admin.user.edit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required',
            'address' => 'nullable',
            'gender' => 'required',
            'type' => 'required',
        ]);
        $user = \App\Models\User::find($id);
        if (\Auth::user()->id == $id || \Auth::user()->id == 1) {
            return redirect()->to(route('admin.users.index', ['type' => $user->type]))->with('error', "User can't be updated");
        }

        $input = $request->all();
        $user->update($input);
        return redirect()->to(route('admin.users.show', [$user->id]))->with('success', "User updated Successfully !");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = \App\Models\User::find($id);
        if (\Auth::user()->id == $id || \Auth::user()->id == 1) {
            return redirect()->to(route('admin.users.index', ['type' => $user->type]))->with('error', "User can't be deleted");
        }
        $user->delete();
        return redirect()->to(route('admin.users.index', ['type' => $user->type]))->with('success', "User deleted successfully!");
    }


    public function createSubject($id)
    {
        $data['user'] = \App\Models\User::find($id);
        $data['title'] = "Assign Subject to " . $data['user']->name;
        return view('admin.user.assignSubject')->with($data);
    }

    public function classmaster()
    {
        $data['users'] = \App\Models\ClassMaster::where('batch_id', \App\Helpers\Helpers::instance()->getCurrentAccademicYear())->paginate(20);
        $data['title'] = "Class Master";
        return view('admin.user.classmaster')->with($data);
    }

    public function classmasterCreate()
    {
        $data['title'] = "Assign Class Master";
        $data['units'] = \App\Models\SchoolUnits::where('parent_id', 0)->get();
        return view('admin.user.create_classmaster')->with($data);
    }

    public function saveClassmaster(Request  $request)
    {
        $this->validate($request, [
            'user_id' => 'required',
            'section' => 'required',
        ]);
        if (ClassMaster::where('user_id', $request->user_id)->where('class_id',  $request->section)->where('batch_id',  \App\Helpers\Helpers::instance()->getCurrentAccademicYear())->count() > 0) {
            return redirect()->back()->with('error', "User already assigned to this class !");
        }

        $master = new ClassMaster();
        $master->user_id = $request->user_id;
        $master->class_id = $request->section;
        $master->batch_id = \App\Helpers\Helpers::instance()->getCurrentAccademicYear();
        $master->save();

        return redirect()->to(route('admin.users.classmaster'))->with('success', "User updated Successfully !");
    }

    public function  deleteMaster(Request  $request)
    {
        $master = ClassMaster::findOrFail($request->master);
        $master->delete();
        return redirect()->to(route('admin.users.classmaster'))->with('success', "User unassigned Successfully !");
    }


    public function dropSubject($id)
    {
        $s = TeachersSubject::find($id);
        if ($s) {
            $s->delete();
        }
        return redirect()->to(route('admin.users.show', $id))->with('success', "Subject deleted successfully!");
    }

    public function saveSubject(Request $request, $id)
    {
        $subject = TeachersSubject::where([
            'teacher_id' => $id,
            'subject_id' => $request->subject,
            'class_id' => $request->section,
            'batch_id' => \App\Helpers\Helpers::instance()->getCurrentAccademicYear()
        ]);

        if ($subject->count() == 0) {
            TeachersSubject::create([
                'teacher_id' => $id,
                'subject_id' => $request->subject,
                'class_id' => $request->section,
                'batch_id' => \App\Helpers\Helpers::instance()->getCurrentAccademicYear()
            ]);
            Session::flash('success', "Subject assigned successfully!");
        } else {
            Session::flash('error', "Subject assigned already");
        }

        return redirect()->to(route('admin.users.show', $id));
    }
}
