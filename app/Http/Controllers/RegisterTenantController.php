<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;
use Database\Seeders\TenantRolesSeeder;

class RegisterTenantController extends Controller
{
    public function index()
    {
        return view('components.central-superadmin.multi-tenants.index');
    }

    public function data()
    {
        $q = Tenant::query()->with('domains')->latest();

        return DataTables::of($q)
            ->addIndexColumn() // 👈 ini kuncinya
            ->filter(function ($query) {
                $search = request('search.value');

                if (!$search) return;

                $query->where(function ($qq) use ($search) {
                    $qq->where('name', 'like', "%{$search}%")
                        ->orWhereHas('domains', function ($qd) use ($search) {
                            $qd->where('domain', 'like', "%{$search}%");
                        });
                });
            })
            ->addColumn('name', fn($tenant) => $tenant->name)
            ->addColumn('domain', fn($tenant) => optional($tenant->domains->first())->domain ?? '-')
            ->addColumn('created_at', fn($t) => optional($t->created_at)->format('Y-m-d H:i'))
            ->addColumn('actions', function ($tenant) {
                $id = e($tenant->id);

                return '
                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-primary btn-sm btn-add-admin"
                        data-id="' . $id . '"
                        title="Tambah Admin Tenant">
                        <i class="ki-outline ki-user fs-6"></i>
                    </button>
                    <button type="button"
                        class="btn btn-danger btn-sm btn-delete"
                        data-id="' . $id . '">
                        <i class="ki-outline ki-trash fs-6"></i>
                    </button>
                </div>
            ';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => ['required', 'string', 'max:150'],
            'subdomain' => ['required', 'string', 'max:40', 'regex:/^[a-z0-9]([a-z0-9-]{0,38}[a-z0-9])?$/'],
        ]);

        $baseDomain = 'localhost';
        $domain = $data['subdomain'] . '.' . $baseDomain;

        if (DB::table('domains')->where('domain', $domain)->exists()) {
            return response()->json([
                'message' => 'Validasi gagal.',
                'errors'  => ['subdomain' => ['Subdomain sudah digunakan.']],
            ], 422);
        }

        $tenant = null;

        try {
            // 1) Create tenant + domain (CENTRAL)
            $tenant = Tenant::create([
                'id'   => (string) Str::uuid(),
                'name' => $data['name'],
                'data' => ['tenancy_db_name' => null],
            ]);

            $tenant->domains()->create(['domain' => $domain]);

            // 2) Init tenancy (switch connection to TENANT DB)
            tenancy()->initialize($tenant);

            try {
                // 2a) migrate tenant DB (hanya untuk tenant ini)
                // SESUAIKAN PATH MIGRATION TENANT KAMU:
                Artisan::call('migrate', [
                    '--force' => true,
                    '--path'  => 'database/migrations/tenant',
                ]);

                // 2b) seed roles tenant (langsung, cepat & stabil)
                (new TenantRolesSeeder())->run();
            } finally {
                tenancy()->end();
            }

            return response()->json([
                'message' => 'Tenant berhasil dibuat + roles berhasil diseed.',
                'tenant'  => [
                    'id'     => $tenant->id,
                    'name'   => $tenant->name,
                    'domain' => $domain,
                ],
            ], 201);
        } catch (\Throwable $e) {
            if ($tenant) {
                try {
                    $tenant->delete();
                } catch (\Throwable $ignored) {
                }
            }

            return response()->json([
                'message' => 'Gagal membuat tenant. ' . $e->getMessage(),
            ], 500);
        }
    }

    public function addAdmin(Request $request, Tenant $multi_tenant)
    {
        $data = $request->validate([
            'admin_email'    => ['required', 'email', 'max:150'],
            'admin_password' => ['required', 'string'],
        ]);

        tenancy()->initialize($multi_tenant);

        try {
            $roleAdminId = Roles::where('slug', 'admin')->value('id');

            if (! $roleAdminId) {
                return response()->json([
                    'message' => 'Role admin belum tersedia di tenant DB. Jalankan tenant migrations/seeder roles dulu.',
                ], 500);
            }

            if (User::where('email', $data['admin_email'])->exists()) {
                return response()->json([
                    'message' => 'Validasi gagal.',
                    'errors'  => ['admin_email' => ['Email sudah terdaftar di tenant ini.']],
                ], 422);
            }

            User::create([
                'name'     => 'Tenant Admin',
                'email'    => $data['admin_email'],
                'password' => Hash::make($data['admin_password']),
                'role_id'  => $roleAdminId,
            ]);

            return response()->json(['message' => 'Admin tenant berhasil ditambahkan.'], 201);
        } finally {
            tenancy()->end();
        }
    }

    public function show(Tenant $multi_tenant)
    {
        $multi_tenant->load('domains');

        $domain = optional($multi_tenant->domains->first())->domain ?? '';

        return response()->json([
            'id'        => $multi_tenant->id,
            'name'      => $multi_tenant->name,   // dari kolom tenants.name
            'domain'    => $domain,                // dari relasi domains
            'subdomain' => $domain,                // SAMA persis (sesuai request)
        ]);
    }

    public function update(Request $request, Tenant $multi_tenant)
    {
        //
    }

    public function destroy(Tenant $multi_tenant)
    {
        $multi_tenant->delete();

        return response()->json(['message' => 'Tenant berhasil dihapus.']);
    }
}
