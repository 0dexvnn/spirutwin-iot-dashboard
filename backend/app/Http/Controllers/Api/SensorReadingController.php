<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SensorReading;
use Illuminate\Http\Request;

class SensorReadingController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'kolam_id' => ['nullable', 'string', 'max:20'],
            'from' => ['nullable', 'date'],
            'until' => ['nullable', 'date'],
        ]);

        $readings = SensorReading::query()
            ->when($validated['kolam_id'] ?? null, fn ($query, $kolamId) => $query->where('kolam_id', $kolamId))
            ->when($validated['from'] ?? null, fn ($query, $from) => $query->where('recorded_at', '>=', $from))
            ->when($validated['until'] ?? null, fn ($query, $until) => $query->where('recorded_at', '<=', $until))
            ->latest('recorded_at')
            ->get()
            ->map(fn (SensorReading $reading) => $this->payload($reading));

        return response()->json($readings);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kolamId' => ['required', 'string', 'max:20'],
            'kolamNama' => ['required', 'string', 'max:100'],
            'timestamp' => ['nullable', 'date'],
            'ph' => ['required', 'numeric'],
            'suhu' => ['required', 'numeric'],
            'cahaya' => ['required', 'integer', 'min:0'],
            'turbidity' => ['required', 'numeric'],
            'tds' => ['required', 'integer', 'min:0'],
            'biomassa' => ['required', 'numeric'],
        ]);

        $reading = SensorReading::create([
            'kolam_id' => $validated['kolamId'],
            'kolam_nama' => $validated['kolamNama'],
            'recorded_at' => $validated['timestamp'] ?? now(),
            'ph' => $validated['ph'],
            'suhu' => $validated['suhu'],
            'cahaya' => $validated['cahaya'],
            'turbidity' => $validated['turbidity'],
            'tds' => $validated['tds'],
            'biomassa' => $validated['biomassa'],
        ]);

        return response()->json($this->payload($reading), 201);
    }

    private function payload(SensorReading $reading): array
    {
        return [
            'id' => 'db-' . $reading->id,
            'kolamId' => $reading->kolam_id,
            'kolamNama' => $reading->kolam_nama,
            'timestamp' => $reading->recorded_at->toIso8601String(),
            'ph' => $reading->ph,
            'suhu' => $reading->suhu,
            'cahaya' => $reading->cahaya,
            'turbidity' => $reading->turbidity,
            'tds' => $reading->tds,
            'biomassa' => $reading->biomassa,
        ];
    }
}
