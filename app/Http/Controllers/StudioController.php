<?php

namespace App\Http\Controllers;

use App\Models\Studios;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class StudioController extends Controller
{
    public function index()
    {
        return view('page-users.managements.studios.index');
    }

    public function data()
    {
        $q = Studios::query()
            ->select(['id', 'name', 'location', 'description', 'created_at'])
            ->latest('id');

        return DataTables::of($q)
            ->addIndexColumn()

            // global search (name/location/description)
            ->filter(function ($query) {
                $search = trim((string) request('search.value'));
                if ($search === '') return;

                $escaped = addcslashes($search, "%_\\"); // escape wildcard

                $query->where(function ($qq) use ($escaped) {
                    $qq->where('name', 'like', "%{$escaped}%")
                        ->orWhere('location', 'like', "%{$escaped}%")
                        ->orWhere('description', 'like', "%{$escaped}%");
                });
            }, true)

            ->editColumn('description', fn($r) => e(Str::limit($r->description ?? '-', 60)))
            ->editColumn('created_at', fn($r) => optional($r->created_at)->format('Y-m-d H:i'))

            ->addColumn('actions', function ($studio) {
                $id = e($studio->id);
                return '
                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-light btn-sm btn-edit" data-id="' . $id . '" title="Edit Studio">
                        <i class="ki-outline ki-pencil fs-6"></i>
                    </button>
                    <button type="button" class="btn btn-danger btn-sm btn-delete" data-id="' . $id . '" title="Hapus Studio">
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
            'location' => ['required', 'string'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $studio = Studios::create([
            'name'        => $data['name'],
            'location'        => $data['location'],
            'description' => $data['description'] ?? null,
            'user_id' => auth()->id(),
        ]);

        return response()->json([
            'message' => 'Studio berhasil dibuat.',
            'data'    => ['id' => $studio->id],
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Studios $studio)
    {
        return response()->json([
            'data' => [
                'id'          => $studio->id,
                'name'        => $studio->name,
                'location'        => $studio->location,
                'description' => $studio->description,
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Studios $studios)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Studios $studio)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'location' => ['required', 'string'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $studio->update([
            'name'        => $data['name'],
            'location'        => $data['location'],
            'description' => $data['description'] ?? null,
            'user_id' => auth()->id(),
        ]);

        return response()->json(['message' => 'Studio berhasil diupdate.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Studios $studio)
    {
        $studio->delete();

        return response()->json(['message' => 'Studio berhasil dihapus.']);
    }
}
