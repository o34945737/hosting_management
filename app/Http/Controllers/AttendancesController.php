<?php

namespace App\Http\Controllers;

use App\Models\Attendances;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AttendancesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('page-users.monitoring.attendance-hosts.index');
    }

    public function data()
    {
        $q = Attendances::query()
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Attendances $attendances)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attendances $attendances)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attendances $attendances)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendances $attendances)
    {
        //
    }
}
