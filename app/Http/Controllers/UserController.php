<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        return view('page-users.managements.users.index');
    }

    public function data()
    {
        $q = User::query()
            ->with('role:id,name,slug') // ✅ select kolom minimal
            ->select('users.*')         // ✅ aman saat join/with
            ->latest();

        return DataTables::of($q)
            ->addIndexColumn()

            // ✅ search global (name/email/role)
            ->filter(function ($query) {
                $search = request('search.value');
                if (!$search) return;

                $query->where(function ($qq) use ($search) {
                    $qq->where('users.name', 'like', "%{$search}%")
                        ->orWhere('users.email', 'like', "%{$search}%")
                        ->orWhereHas('role', function ($qr) use ($search) {
                            $qr->where('name', 'like', "%{$search}%")
                                ->orWhere('slug', 'like', "%{$search}%");
                        });
                });
            })

            // ✅ role badge (kolom tambahan)
            ->addColumn('role', function ($user) {
                if (!$user->role) {
                    return '<span class="badge badge-light-danger">No Role</span>';
                }

                return '<span class="badge badge-light-primary fw-semibold">'
                    . e($user->role->name) .
                    '</span>';
            })

            ->editColumn('created_at', fn($u) => optional($u->created_at)->format('Y-m-d H:i'))

            // ✅ actions
            ->addColumn('actions', function ($user) {
                $id = e($user->id);

                return '
            <div class="d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-light btn-sm btn-edit"
                    data-id="' . $id . '" title="Edit User">
                    <i class="ki-outline ki-pencil fs-6"></i>
                </button>

                <button type="button" class="btn btn-danger btn-sm btn-delete"
                    data-id="' . $id . '" title="Hapus User">
                    <i class="ki-outline ki-trash fs-6"></i>
                </button>
            </div>';
            })

            ->rawColumns(['role', 'actions'])
            ->make(true);
    }

    public function rolesOptions()
    {
        $roles = Roles::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return response()->json(['data' => $roles]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:150'],
            'email'    => ['required', 'email:rfc,dns', 'max:150', Rule::unique('users', 'email')],
            'password' => ['required', 'string'],
            'role_id'  => ['required', 'integer', 'exists:roles,id'],
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => strtolower(trim($data['email'])),
            'password' => Hash::make($data['password']),
            'role_id'  => (int) $data['role_id'],
        ]);

        return response()->json([
            'message' => 'User berhasil dibuat.',
            'data'    => [
                'id' => $user->id,
            ],
        ], 201);
    }

    public function show(User $user)
    {
        return response()->json([
            'data' => [
                'id'      => $user->id,
                'name'    => $user->name,
                'email'   => $user->email,
                'role_id' => $user->role_id,
            ],
        ]);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:150'],
            'email'    => ['required', 'email:rfc,dns', 'max:150', Rule::unique('users', 'email')->ignore($user->id)],
            'role_id'  => ['required', 'integer', 'exists:roles,id'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $payload = [
            'name'    => $data['name'],
            'email'   => strtolower(trim($data['email'])),
            'role_id' => (int) $data['role_id'],
        ];

        if (!empty($data['password'])) {
            $payload['password'] = Hash::make($data['password']);
        }

        $user->update($payload);

        return response()->json([
            'message' => 'User berhasil diupdate.',
        ]);
    }

    public function destroy(User $user)
    {
        // ✅ optional: cegah hapus diri sendiri
        if (auth()->id() === $user->id) {
            return response()->json([
                'message' => 'Tidak bisa menghapus akun sendiri.',
            ], 422);
        }

        $user->delete();

        return response()->json([
            'message' => 'User berhasil dihapus.',
        ]);
    }
}
