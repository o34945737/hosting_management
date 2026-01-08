<?php

namespace App\Http\Controllers;

use App\Imports\SchedulePreviewImport;
use App\Models\Brand;
use App\Models\Schedules;
use App\Models\Studios;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ScheduleController extends Controller
{

    private function sessionKey(): string
    {
        return 'schedule_import_preview'; // per-user otomatis karena session
    }

    public function preview(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:5120'],
        ]);

        $import = new SchedulePreviewImport();
        Excel::import($import, $request->file('file'));

        $rows = ($import->rows ?? collect())->take(500);

        $preview = [];
        $errors = [];
        $validCount = 0;

        foreach ($rows as $i => $r) {
            $rowNum = $i + 2; // heading row = 1

            $hostEmail = trim((string)($r['host_email'] ?? ''));
            $studioName = trim((string)($r['studio'] ?? ''));
            $brandName  = trim((string)($r['brand'] ?? ''));
            $startRaw   = (string)($r['start_at'] ?? '');
            $endRaw     = (string)($r['end_at'] ?? '');
            $status     = trim((string)($r['status'] ?? ''));
            $notes      = (string)($r['notes'] ?? null);

            $rowErrors = [];

            if ($hostEmail === '') $rowErrors['host_email'][] = 'host_email wajib diisi.';
            if ($studioName === '') $rowErrors['studio'][] = 'studio wajib diisi.';
            if ($brandName === '')  $rowErrors['brand'][]  = 'brand wajib diisi.';
            if ($startRaw === '')   $rowErrors['start_at'][] = 'start_at wajib diisi.';
            if ($endRaw === '')     $rowErrors['end_at'][] = 'end_at wajib diisi.';

            if ($status === '') $rowErrors['status'][] = 'status wajib diisi.';
            elseif (!in_array($status, ['planned', 'ongoing', 'done', 'canceled'], true)) {
                $rowErrors['status'][] = 'status harus planned|ongoing|done|canceled.';
            }

            try {
                $start = $startRaw ? Carbon::parse($startRaw) : null;
            } catch (\Throwable $e) {
                $start = null;
                $rowErrors['start_at'][] = 'Format start_at tidak valid.';
            }

            try {
                $end = $endRaw ? Carbon::parse($endRaw) : null;
            } catch (\Throwable $e) {
                $end = null;
                $rowErrors['end_at'][] = 'Format end_at tidak valid.';
            }

            if ($start && $end && $end->lessThanOrEqualTo($start)) {
                $rowErrors['end_at'][] = 'end_at harus setelah start_at.';
            }

            $host   = $hostEmail ? User::query()->where('email', strtolower($hostEmail))->first() : null;
            $studio = $studioName ? Studios::query()->where('name', $studioName)->first() : null;
            $brand  = $brandName ? Brand::query()->where('name', $brandName)->first() : null;

            if ($hostEmail && !$host) $rowErrors['host_email'][] = 'Host email tidak ditemukan.';
            if ($studioName && !$studio) $rowErrors['studio'][] = 'Studio tidak ditemukan.';
            if ($brandName && !$brand) $rowErrors['brand'][] = 'Brand tidak ditemukan.';

            $item = [
                '_row' => $rowNum,
                'host_email' => $hostEmail,
                'host_id' => $host?->id,
                'host_name' => $host?->name,
                'studio' => $studioName,
                'studio_id' => $studio?->id,
                'brand' => $brandName,
                'brand_id' => $brand?->id,
                'start_at' => $start?->format('Y-m-d H:i'),
                'end_at'   => $end?->format('Y-m-d H:i'),
                'status' => $status,
                'notes' => $notes,
            ];

            $preview[] = $item;

            if (!empty($rowErrors)) {
                $errors[] = ['_row' => $rowNum, 'errors' => $rowErrors];
            } else {
                $validCount++;
            }
        }

        $token = (string) Str::uuid();

        // ✅ simpan ke session (no tags issue)
        session()->put($this->sessionKey(), [
            'token' => $token,
            'created_at' => now()->toDateTimeString(),
            'items' => $preview,
            'errors' => $errors,
        ]);

        return response()->json([
            'message' => 'Preview berhasil dibuat.',
            'data' => [
                'token' => $token,
                'total' => count($preview),
                'valid' => $validCount,
                'invalid' => count($errors),
                'items' => $preview,
                'errors' => $errors,
            ]
        ]);
    }

    public function commit(Request $request)
    {
        $data = $request->validate([
            'token' => ['required', 'string'],
        ]);

        $cached = session()->get($this->sessionKey());
        if (!$cached || ($cached['token'] ?? null) !== $data['token']) {
            return response()->json(['message' => 'Preview expired. Silakan upload ulang.'], 422);
        }

        $items = collect($cached['items'] ?? []);
        $errors = $cached['errors'] ?? [];

        if (!empty($errors)) {
            return response()->json([
                'message' => 'Masih ada data invalid. Perbaiki file lalu preview ulang.',
                'errors' => ['file' => ['Masih ada baris invalid.']]
            ], 422);
        }

        DB::transaction(function () use ($items) {
            foreach ($items as $it) {
                if (empty($it['host_id']) || empty($it['studio_id']) || empty($it['brand_id'])) continue;

                $start = Carbon::parse($it['start_at']);
                $end   = Carbon::parse($it['end_at']);

                $overlap = Schedules::query()
                    ->where('host_id', $it['host_id'])
                    ->where('start_at', '<', $end)
                    ->where('end_at', '>', $start)
                    ->exists();

                if ($overlap) {
                    throw new \RuntimeException("Overlap schedule (row {$it['_row']}).");
                }

                Schedules::create([
                    'host_id' => (int) $it['host_id'],
                    'studio_id' => (int) $it['studio_id'],
                    'brand_id' => (int) $it['brand_id'],
                    'user_id' => auth()->id(),
                    'start_at' => $start,
                    'end_at' => $end,
                    'status' => $it['status'],
                    'notes' => $it['notes'] ?: null,
                ]);
            }
        });

        // ✅ clear session preview
        session()->forget($this->sessionKey());

        return response()->json(['message' => 'Import schedule berhasil.']);
    }

    public function index()
    {
        return view('page-users.managements.schedules.index');
    }

    public function data()
    {
        $q = Schedules::query()
            ->with([
                'host:id,name,email',
                'studio:id,name',
                'brand:id,name',
            ])
            ->select('schedules.*')
            ->latest('start_at');

        return DataTables::of($q)
            ->addIndexColumn()

            ->filter(function ($query) {
                $search = request('search.value');
                if (!$search) return;

                $query->where(function ($qq) use ($search) {
                    $qq->where('notes', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%")
                        ->orWhereHas('host', fn($u) => $u->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"))
                        ->orWhereHas('studio', fn($s) => $s->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('brand', fn($b) => $b->where('name', 'like', "%{$search}%"));
                });
            })

            ->addColumn('host', fn($row) => e(optional($row->host)->name ?? '-'))
            ->addColumn('studio', fn($row) => e(optional($row->studio)->name ?? '-'))
            ->addColumn('brand', fn($row) => e(optional($row->brand)->name ?? '-'))

            ->editColumn('start_at', function ($row) {
                if (blank($row->start_at)) return '-';
                return Carbon::parse($row->start_at)->format('Y-m-d H:i');
            })
            ->editColumn('end_at', function ($row) {
                if (blank($row->end_at)) return '-';
                return Carbon::parse($row->end_at)->format('Y-m-d H:i');
            })

            ->addColumn('actions', function ($row) {
                $id = e($row->id);
                return '
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light btn-sm btn-edit" data-id="' . $id . '" title="Edit">
                            <i class="ki-outline ki-pencil fs-6"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-sm btn-delete" data-id="' . $id . '" title="Hapus">
                            <i class="ki-outline ki-trash fs-6"></i>
                        </button>
                    </div>';
            })

            ->rawColumns(['actions'])
            ->make(true);
    }

    public function hostsOptions()
    {
        $items = User::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return response()->json(['data' => $items]);
    }

    public function BrandOptions()
    {
        $items = Brand::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return response()->json(['data' => $items]);
    }

    public function studiosOptions()
    {
        $items = Studios::query()
            ->select('id', 'name', 'location')
            ->orderBy('name')
            ->get();

        return response()->json(['data' => $items]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'host_id'   => ['required', 'integer', 'exists:users,id'],
            'studio_id' => ['required', 'integer', 'exists:studios,id'],
            'brand_id'  => ['required', 'integer', 'exists:Brand,id'],
            'start_at'  => ['required', 'date'],
            'end_at'    => ['required', 'date', 'after:start_at'],
            'status'    => ['required', Rule::in(['planned', 'ongoing', 'done', 'canceled'])],
            'notes'     => ['nullable', 'string'],
        ]);

        // normalisasi datetime (opsional tapi recommended)
        $start = Carbon::parse($data['start_at']);
        $end   = Carbon::parse($data['end_at']);

        // ✅ Best practice: cegah bentrok jadwal host (overlap)
        $overlap = Schedules::query()
            ->where('host_id', $data['host_id'])
            ->where('start_at', '<', $end)
            ->where('end_at', '>', $start)
            ->exists();

        if ($overlap) {
            return response()->json([
                'message' => 'Validasi gagal.',
                'errors' => [
                    'start_at' => ['Host sudah punya schedule di rentang waktu ini.'],
                ],
            ], 422);
        }

        $schedule = Schedules::create([
            'host_id'   => $data['host_id'],
            'studio_id' => $data['studio_id'],
            'brand_id'  => $data['brand_id'],
            'user_id'   => auth()->id(),
            'start_at'  => $start,
            'end_at'    => $end,
            'status'    => $data['status'],
            'notes'     => $data['notes'] ?? null,
        ]);

        return response()->json([
            'message' => 'Schedule berhasil dibuat.',
            'data' => ['id' => $schedule->id],
        ], 201);
    }

    public function show(Schedules $schedule)
    {
        $schedule->load(['host:id,name', 'studio:id,name', 'brand:id,name']);

        return response()->json([
            'data' => [
                'id' => $schedule->id,
                'host_id' => $schedule->host_id,
                'studio_id' => $schedule->studio_id,
                'brand_id' => $schedule->brand_id,
                'status' => $schedule->status,
                'notes' => $schedule->notes,
                'start_at_form' => optional($schedule->start_at)->format('Y-m-d\TH:i'),
                'end_at_form' => optional($schedule->end_at)->format('Y-m-d\TH:i'),
            ]
        ]);
    }


    public function update(Request $request, Schedules $schedule)
    {
        $data = $request->validate([
            'host_id'   => ['required', 'integer', 'exists:users,id'],
            'studio_id' => ['required', 'integer', 'exists:studios,id'],
            'brand_id'  => ['required', 'integer', 'exists:Brand,id'],
            'start_at'  => ['required', 'date'],
            'end_at'    => ['required', 'date', 'after:start_at'],
            'status'    => ['required', Rule::in(['planned', 'ongoing', 'done', 'canceled'])],
            'notes'     => ['nullable', 'string'],
        ]);

        $start = Carbon::parse($data['start_at']);
        $end   = Carbon::parse($data['end_at']);

        $overlap = Schedules::query()
            ->where('host_id', $data['host_id'])
            ->where('id', '!=', $schedule->id)
            ->where('start_at', '<', $end)
            ->where('end_at', '>', $start)
            ->exists();

        if ($overlap) {
            return response()->json([
                'message' => 'Validasi gagal.',
                'errors' => [
                    'start_at' => ['Host sudah punya schedule di rentang waktu ini.'],
                ],
            ], 422);
        }

        $schedule->update([
            'host_id'   => $data['host_id'],
            'studio_id' => $data['studio_id'],
            'brand_id'  => $data['brand_id'],
            'start_at'  => $start,
            'end_at'    => $end,
            'status'    => $data['status'],
            'notes'     => $data['notes'] ?? null,
        ]);

        return response()->json([
            'message' => 'Schedule berhasil diupdate.',
            'data' => ['id' => $schedule->id],
        ]);
    }

    public function destroy(Schedules $schedule)
    {
        $schedule->delete();

        return response()->json(['message' => 'Schedule berhasil dihapus.']);
    }
}
