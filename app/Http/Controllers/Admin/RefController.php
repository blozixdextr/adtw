<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Models\Mappers\LogMapper;
use App\Models\Mappers\RefMapper;
use App\Models\Ref;
use Illuminate\Http\Request;

class RefController extends Controller
{
    public function index()
    {
        $refTypes = RefMapper::allTypes();

        return view('admin.pages.ref.index', compact('refTypes'));
    }

    public static function type($type)
    {
        $refs = RefMapper::type($type);

        return view('admin.pages.ref.list', compact('refs', 'type'));
    }

    public function getRequestData(Request $request) {
        $rules = [
            'title' => 'required',
            'subtitle' => 'min:2'
        ];
        $pid = intval($request->get('pid'));
        if ($pid > 0) {
            $rules['pid'] = 'required|exists:refs,id';
        }
        $this->validate($request, $rules);
        $data = $request->all();

        $pid = intval($data['pid']);
        if ($pid == 0) {
            $data['type'] = 'state';
        } else {
            $parent = Ref::findOrFail($pid);
            if ($parent->pid == 0) {
                $data['type'] = 'region';
            } else {
                $data['type'] = 'city';
            }
        }

        return $data;
    }

    public function edit($id)
    {
        $location = Ref::findOrFail($id);
        //$parents = $location->parent->siblings();
        if ($location->pid == 0) {
            $parents = [];
        } else {
            $parent = $location->parent;
            if ($parent->pid == 0) {
                $parents = Ref::states()->get();
            } else {
                $parents = $location->siblings();
            }
        }

        return view('admin.pages.location.edit', compact('location', 'parents'));
    }

    public function update($id, Request $request)
    {
        $location = Ref::findOrFail($id);
        $data = $this->getRequestData($request);
        $location->fill($data)->save();

        return Redirect::back();
    }

    public function create()
    {
        $pid = intval(Input::get('id', 0));
        if ($pid != 0) {
            $location = Ref::findOrFail($pid);
            if ($location->pid == 0) {
                $parents = Ref::states()->get();
            } else {
                $parents = $location->siblings();
            }
        } else {
            $location = false;
            $parents = Ref::states()->get();
        }

        return view('admin.pages.location.add', compact('location', 'parents', 'pid'));
    }

    public function store(Request $request)
    {
        $data = $this->getRequestData($request);
        $location = Ref::create($data);
        $location->fill($data)->save();

        return Redirect::to('/admin/location/edit/'.$location->id);
    }

    public function destroy($id)
    {
        $location = Ref::findOrFail($id);
        $location->delete();

        return Redirect::back();
    }

    public function show($id)
    {
        $location = Ref::findOrFail($id);

        return view('admin.pages.location.show', compact('location'));
    }
}
