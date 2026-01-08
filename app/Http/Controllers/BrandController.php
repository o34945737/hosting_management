<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('page-users.managements.brands.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function data()
    {
        $q = Brand::query()
            ->select(['id', 'name', 'description', 'created_at'])
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
                        ->orWhere('description', 'like', "%{$escaped}%");
                });
            }, true)

            ->editColumn('description', fn($r) => e(Str::limit($r->description ?? '-', 60)))
            ->editColumn('created_at', fn($r) => optional($r->created_at)->format('Y-m-d H:i'))

            ->addColumn('actions', function ($brand) {
                $id = e($brand->id);
                return '
                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-light btn-sm btn-edit" data-id="' . $id . '" title="Edit brand">
                        <i class="ki-outline ki-pencil fs-6"></i>
                    </button>
                    <button type="button" class="btn btn-danger btn-sm btn-delete" data-id="' . $id . '" title="Hapus brand">
                        <i class="ki-outline ki-trash fs-6"></i>
                    </button>
                </div>';
            })

            ->rawColumns(['actions'])
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $brand = Brand::create([
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
            'user_id' => auth()->id(),
        ]);

        return response()->json([
            'message' => 'Brand berhasil dibuat.',
            'data'    => ['id' => $brand->id],
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        return response()->json([
            'data' => [
                'id'          => $brand->id,
                'name'        => $brand->name,
                'description' => $brand->description,
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brand $brand)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Brand $brand)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $brand->update([
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
            'user_id' => auth()->id(),
        ]);

        return response()->json(['message' => 'Brand berhasil diupdate.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        $brand->delete();

        return response()->json(['message' => 'Brand berhasil dihapus.']);
    }
}
