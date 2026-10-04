<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MaintenanceController extends Controller
{
    protected string $downFile;

    public function __construct()
    {
        $this->downFile = storage_path('framework/down');
    }

    public function index()
    {
        return view('admin.settings.maintenance');
    }

    public function enable(Request $request)
    {
        $data = $request->validate([
            'message' => ['nullable', 'string', 'max:500'],
            'retry' => ['nullable', 'integer', 'min:1', 'max:43200'],
            'allowed_ips' => ['nullable', 'string', 'max:500'],
        ]);

        $allowedIps = [];

        if (!empty($data['allowed_ips'])) {
            $allowedIps = collect(explode(',', $data['allowed_ips']))
                ->map(fn ($ip) => trim($ip))
                ->filter(fn ($ip) => filter_var($ip, FILTER_VALIDATE_IP) !== false)
                ->unique()
                ->values()
                ->toArray();
        }

        if (!in_array($request->ip(), $allowedIps, true)) {
            $allowedIps[] = $request->ip();
        }

        $payload = [
            'time' => time(),
            'message' => $data['message'] ?? '',
            'retry' => isset($data['retry']) && $data['retry'] > 0
                ? time() + ((int) $data['retry'] * 60)
                : null,
            'secret' => Str::random(40),
            'allowed' => $allowedIps,
        ];

        if (!is_dir(dirname($this->downFile))) {
            mkdir(dirname($this->downFile), 0755, true);
        }

        file_put_contents(
            $this->downFile,
            json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
        );

        return redirect()
            ->route('admin.settings.maintenance')
            ->with('success', 'Texniki xidmət rejimi aktivləşdirildi.');
    }

    public function disable()
    {
        if (file_exists($this->downFile)) {
            @unlink($this->downFile);
        }

        return redirect()
            ->route('admin.settings.maintenance')
            ->with('success', 'Texniki xidmət rejimi deaktiv edildi. Sayt aktivdir.');
    }
}
