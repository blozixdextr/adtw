<?php

namespace App\Http\Controllers\Admin;

use Input;
use Redirect;
use App\Models\Mappers\RefMapper;
use App\Models\Ref;
use Illuminate\Http\Request;

class RefController extends Controller
{
    public $levelLimits = [
        'banner_type' => 1,
        'language' => 1,
        'game' => 2,
    ];

    public function getRequestData(Request $request)
    {
        $rules = [
            'title' => 'required'
        ];
        $pid = intval($request->get('pid'));
        if ($pid > 0) {
            $rules['pid'] = 'required|exists:refs,id';
        }
        $this->validate($request, $rules);
        $data = $request->all();

        return $data;
    }

    public function getRefLevel($ref)
    {
        $level = 1;
        while ($ref->parent) {
            $ref = $ref->parent;
            $level++;
        }

        return $level;
    }

    public function canHaveChildren($ref)
    {
        if (!isset($this->levelLimits[$ref->type])) {
            return true;
        }
        $level = $this->getRefLevel($ref);
        if ($level >= $this->levelLimits[$ref->type]) {
            return false;
        }

        return true;
    }

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

    public function show($refId)
    {
        $ref = Ref::findOrFail($refId);
        $canHaveChildren = $this->canHaveChildren($ref);
        //if ()

        return view('admin.pages.ref.show', compact('ref', 'canHaveChildren'));
    }

    public function edit($refId)
    {
        $ref = Ref::findOrFail($refId);
        if ($ref->pid == 0) {
            $parents = [];
        } else {
            $parent = $ref->parent;
            if ($parent->pid == 0) {
                $parents = RefMapper::type($ref->type);
            } else {
                $parents = $ref->siblings();
            }
        }

        return view('admin.pages.ref.edit', compact('ref', 'parents'));
    }

    public function update($refId, Request $request)
    {
        $ref = Ref::findOrFail($refId);
        $data = $this->getRequestData($request);
        $ref->fill($data)->save();

        return redirect('/admin/ref/'.$ref->id.'/edit')->with(['success' => 'Ref was saved']);
    }

    public function create($type)
    {
        $pid = intval(Input::get('pid', 0));
        if ($pid != 0) {
            $ref = Ref::findOrFail($pid);
            if ($ref->pid == 0) {
                $parents = RefMapper::type($type);
            } else {
                $parents = $ref->siblings();
            }
        } else {
            $ref = false;
            $parents = false;
        }

        return view('admin.pages.ref.create', compact('ref', 'parents', 'pid', 'type'));
    }


    public function store($type, Request $request)
    {
        $data = $this->getRequestData($request);
        $data['type'] = $type;
        $ref = Ref::create($data);

        return redirect('/admin/ref/'.$ref->id.'/edit')->with(['success' => 'Ref was created']);
    }


    public function destroy($id)
    {
        $ref = Ref::findOrFail($id);
        if ($ref->children()->count() > 0) {
            return Redirect::back()->withErrors(['This ref has children and can not be removed.']);
        }
        $ref->delete();

        return Redirect::back();
    }
}
