<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kolam;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PetaniController extends Controller
{
    public function index(): View
    {
        return view('admin.petani.index', ['petani' => User::where('role', 'petani')->with('kolams')->orderBy('name')->get()]);
    }

    public function create(): View
    {
        return view('admin.petani.create', ['kolams' => Kolam::orderBy('code')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        DB::transaction(function () use ($data): void {
            $identifier = str()->lower(str()->random(24));
            $petani = User::create(array_merge(collect($data)->except('kolam_ids')->all(), ['role' => 'petani', 'username' => 'petani_'.$identifier, 'email' => 'petani-'.$identifier.'@petani.spirutwin.local', 'password' => str()->random(40)]));
            $petani->kolams()->sync($data['kolam_ids']);
        });

        return redirect()->route('admin.petani.index')->with('success', 'Data petani berhasil ditambahkan.');
    }

    public function edit(User $petani): View
    {
        abort_unless($petani->role === 'petani', 404);

        return view('admin.petani.edit', ['petani' => $petani->load('kolams'), 'kolams' => Kolam::orderBy('code')->get()]);
    }

    public function update(Request $request, User $petani): RedirectResponse
    {
        abort_unless($petani->role === 'petani', 404);
        $data = $this->validatedData($request, $petani);
        $petani->update(collect($data)->except('kolam_ids')->all());
        $petani->kolams()->sync($data['kolam_ids']);

        return redirect()->route('admin.petani.index')->with('success', 'Data petani diperbarui.');
    }

    public function destroy(User $petani): RedirectResponse
    {
        abort_unless($petani->role === 'petani', 404);
        $petani->delete();

        return redirect()->route('admin.petani.index')->with('success', 'Data petani berhasil dihapus.');
    }

    private function validatedData(Request $request, ?User $petani = null): array
    {
        return $request->validate(['name' => ['required', 'string', 'max:255'], 'nik' => ['required', 'string', 'max:30', Rule::unique('users', 'nik')->ignore($petani?->id)], 'no_kk' => ['required', 'string', 'max:30'], 'phone' => ['required', 'string', 'max:30'], 'address' => ['required', 'string', 'max:1000'], 'kolam_ids' => ['required', 'array', 'min:1'], 'kolam_ids.*' => ['integer', Rule::exists('kolams', 'id')]]);
    }
}
