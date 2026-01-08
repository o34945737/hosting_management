<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;


class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('page-users.managements.roles.index');
    }

    public function data()
    {
        $q = Roles::query()
            ->select(['id', 'name', 'slug', 'description', 'created_at'])
            ->latest('id');

        return DataTables::of($q)
            ->addIndexColumn()
            ->filter(function ($query) {
                $search = trim((string) request('search.value'));
                if ($search === '') return;

                $escaped = addcslashes($search, '%_\\');

                $query->where(function ($qq) use ($escaped) {
                    $qq->where('name', 'like', "%{$escaped}%")
                        ->orWhere('slug', 'like', "%{$escaped}%")
                        ->orWhere('description', 'like', "%{$escaped}%");
                });
            }, true)
            ->editColumn('description', fn($r) => e(Str::limit($r->description ?? '-', 60)))
            ->editColumn('created_at', fn($r) => optional($r->created_at)->format('Y-m-d H:i'))
            ->addColumn('actions', function ($role) {
                $id = e($role->id);
                return '
                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-light btn-sm btn-edit" data-id="' . $id . '" title="Edit Role">
                        <i class="ki-outline ki-pencil fs-6"></i>
                    </button>
                    <button type="button" class="btn btn-danger btn-sm btn-delete" data-id="' . $id . '" title="Hapus Role">
                        <i class="ki-outline ki-trash fs-6"></i>
                    </button>
                </div>';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'slug' => [
                'required',
                'string',
                'max:150',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('roles', 'slug')
            ],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $role = Roles::create([
            'name'        => $data['name'],
            'slug'        => Str::of($data['slug'])->lower()->trim()->toString(),
            'description' => $data['description'] ?? null,
            'user_id' => auth()->id(),
        ]);

        return response()->json([
            'message' => 'Role berhasil dibuat.',
            'data'    => ['id' => $role->id],
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Roles $role)
    {
        return response()->json([
            'data' => [
                'id'          => $role->id,
                'name'        => $role->name,
                'slug'        => $role->slug,
                'description' => $role->description,
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Roles $roles)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Roles $role)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'slug' => [
                'required',
                'string',
                'max:150',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('roles', 'slug')->ignore($role->id)
            ],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $role->update([
            'name'        => $data['name'],
            'slug'        => Str::of($data['slug'])->lower()->trim()->toString(),
            'description' => $data['description'] ?? null,
            'user_id' => auth()->id(),
        ]);

        return response()->json(['message' => 'Role berhasil diupdate.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Roles $role)
    {
        $role->delete();

        return response()->json(['message' => 'Role berhasil dihapus.']);
    }
}
