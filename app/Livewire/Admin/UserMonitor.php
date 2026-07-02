<?php

namespace App\Livewire\Admin;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Spatie\Activitylog\Models\Activity;

class UserMonitor extends Component
{
    // ─── Tab ──────────────────────────────────────────────────
    #[Url(as: 'tab')]
    public string $activeTab = 'sessions';

    // ─── Log Filter ───────────────────────────────────────────
    #[Url(as: 'user')]
    public ?int $selectedUserId = null;

    #[Url(as: 'log_name')]
    public string $selectedLogName = '';

    #[Url(as: 'dari')]
    public string $logDari = '';

    #[Url(as: 'sampai')]
    public string $logSampai = '';

    public int $logPage = 1;
    public int $perPage = 25;

    // ─── UI State ─────────────────────────────────────────────
    public ?string $flashSuccess = null;
    public ?string $flashError   = null;

    public function mount(): void
    {
        $this->logDari   = now()->subDays(30)->format('Y-m-d');
        $this->logSampai = now()->format('Y-m-d');
    }

    // ─────────────────────────────────────────────────────────
    // COMPUTED: SESSIONS
    // ─────────────────────────────────────────────────────────

    #[Computed(cache: false)]
    public function activeSessions(): Collection
    {
        $lifetime = (int) config('session.lifetime', 120);

        $sessions = DB::table('sessions')
            ->whereNotNull('user_id')
            ->where('last_activity', '>=', now()->subMinutes($lifetime)->timestamp)
            ->orderBy('last_activity', 'desc')
            ->get();

        $userIds = $sessions->pluck('user_id')->unique();
        $users   = User::whereIn('id', $userIds)
            ->with('cabang')
            ->get()
            ->keyBy('id');

        return $sessions->map(function ($session) use ($users) {
            $user    = $users->get($session->user_id);
            $payload = $this->decodeSessionPayload($session->payload);

            return (object) [
                'session_id'    => $session->id,
                'user_id'       => $session->user_id,
                'user'          => $user,
                'ip_address'    => $session->ip_address,
                'user_agent'    => $session->user_agent,
                'browser'       => $this->parseBrowser($session->user_agent),
                'os'            => $this->parseOS($session->user_agent),
                'last_activity' => \Carbon\Carbon::createFromTimestamp($session->last_activity),
                'is_current'    => $session->id === session()->getId(),
            ];
        });
    }

    #[Computed(cache: false)]
    public function sessionStats(): array
    {
        $sessions = $this->activeSessions;

        return [
            'total'   => $sessions->count(),
            'by_role' => $sessions->groupBy(fn($s) => $s->user?->role?->label() ?? 'Tidak Dikenal')
                ->map->count(),
        ];
    }

    // ─────────────────────────────────────────────────────────
    // COMPUTED: LOGS
    // ─────────────────────────────────────────────────────────

    #[Computed(cache: false)]
    public function allUsers(): Collection
    {
        return User::orderBy('name')
            ->with('cabang')
            ->get(['id', 'name', 'nik', 'role', 'id_cabang']);
    }

    #[Computed(cache: false)]
    public function selectedUser(): ?User
    {
        if (! $this->selectedUserId) return null;
        return User::with('cabang')->find($this->selectedUserId);
    }

    #[Computed(cache: false)]
    public function userActivityStats(): ?array
    {
        if (! $this->selectedUserId) return null;

        $total = Activity::where('causer_id', $this->selectedUserId)
            ->where('causer_type', 'App\\Models\\User')
            ->count();

        $last30 = Activity::where('causer_id', $this->selectedUserId)
            ->where('causer_type', 'App\\Models\\User')
            ->where('created_at', '>=', now()->subDays(30))
            ->count();

        $lastActivity = Activity::where('causer_id', $this->selectedUserId)
            ->where('causer_type', 'App\\Models\\User')
            ->latest()
            ->first();

        $byCategory = Activity::where('causer_id', $this->selectedUserId)
            ->where('causer_type', 'App\\Models\\User')
            ->where('created_at', '>=', now()->subDays(30))
            ->select('log_name', DB::raw('count(*) as total'))
            ->groupBy('log_name')
            ->pluck('total', 'log_name');

        return [
            'total'         => $total,
            'last_30'       => $last30,
            'last_activity' => $lastActivity,
            'by_category'   => $byCategory,
        ];
    }

    #[Computed(cache: false)]
    public function logNames(): Collection
    {
        $q = Activity::query();

        if ($this->selectedUserId) {
            $q->where('causer_id', $this->selectedUserId)
                ->where('causer_type', 'App\\Models\\User');
        }

        return $q->select('log_name')
            ->distinct()
            ->orderBy('log_name')
            ->pluck('log_name');
    }

    #[Computed(cache: false)]
    public function userLogs()
    {
        $q = Activity::with('causer')
            ->latest();

        if ($this->selectedUserId) {
            $q->where('causer_id', $this->selectedUserId)
                ->where('causer_type', 'App\\Models\\User');
        }

        if ($this->selectedLogName) {
            $q->where('log_name', $this->selectedLogName);
        }

        if ($this->logDari) {
            $q->whereDate('created_at', '>=', $this->logDari);
        }

        if ($this->logSampai) {
            $q->whereDate('created_at', '<=', $this->logSampai);
        }

        return $q->paginate($this->perPage, ['*'], 'page', $this->logPage);
    }

    // ─────────────────────────────────────────────────────────
    // ACTIONS
    // ─────────────────────────────────────────────────────────

    public function forceLogout(string $sessionId): void
    {
        $this->resetFlash();

        // Pastikan session bukan milik admin yang sedang login
        if ($sessionId === session()->getId()) {
            $this->flashError = 'Anda tidak dapat me-logout sesi Anda sendiri.';
            return;
        }

        // Cari session di DB
        $session = DB::table('sessions')->where('id', $sessionId)->first();
        if (! $session) {
            $this->flashError = 'Sesi tidak ditemukan atau sudah berakhir.';
            return;
        }

        // Ambil info user untuk log
        $targetUser = User::find($session->user_id);

        // Hapus session dari database
        DB::table('sessions')->where('id', $sessionId)->delete();

        // Log aktivitas
        activity('monitoring')
            ->causedBy(auth()->user())
            ->withProperties([
                'target_user'   => $targetUser?->name,
                'target_nik'    => $targetUser?->nik,
                'target_ip'     => $session->ip_address,
                'session_id'    => substr($sessionId, 0, 8) . '...', // partial untuk keamanan
            ])
            ->log("Force logout dilakukan terhadap {$targetUser?->name} ({$targetUser?->nik}) dari IP {$session->ip_address}");

        $this->flashSuccess = "Sesi {$targetUser?->name} berhasil diterminasi.";
    }

    public function forceLogoutAll(): void
    {
        $this->resetFlash();
        $currentId = session()->getId();

        $deleted = DB::table('sessions')
            ->whereNotNull('user_id')
            ->where('id', '!=', $currentId)
            ->delete();

        activity('monitoring')
            ->causedBy(auth()->user())
            ->withProperties(['jumlah_sesi' => $deleted])
            ->log("Force logout SEMUA user: {$deleted} sesi diterminasi");

        $this->flashSuccess = "{$deleted} sesi user berhasil diterminasi. Hanya sesi Anda yang tersisa.";
    }

    // ─── Log Filter Helpers ───────────────────────────────────

    public function updatedSelectedUserId(): void
    {
        $this->logPage = 1;
    }

    public function updatedSelectedLogName(): void
    {
        $this->logPage = 1;
    }

    public function updatedLogDari(): void
    {
        $this->logPage = 1;
    }

    public function updatedLogSampai(): void
    {
        $this->logPage = 1;
    }

    public function previousPage(): void
    {
        if ($this->logPage > 1) $this->logPage--;
    }

    public function nextPage(int $lastPage): void
    {
        if ($this->logPage < $lastPage) $this->logPage++;
    }

    public function resetLogFilter(): void
    {
        $this->selectedUserId   = null;
        $this->selectedLogName  = '';
        $this->logDari          = now()->subDays(30)->format('Y-m-d');
        $this->logSampai        = now()->format('Y-m-d');
        $this->logPage          = 1;
    }
    // ─────────────────────────────────────────────────────────
    // PRIVATE HELPERS
    // ─────────────────────────────────────────────────────────

    private function resetFlash(): void
    {
        $this->flashSuccess = null;
        $this->flashError   = null;
    }

    private function decodeSessionPayload(string $payload): array
    {
        try {
            return unserialize(base64_decode($payload)) ?: [];
        } catch (\Exception) {
            return [];
        }
    }

    private function parseBrowser(string $ua): string
    {
        $ua = strtolower($ua);

        return match (true) {
            str_contains($ua, 'edg/')           => 'Edge',
            str_contains($ua, 'opr/')
                || str_contains($ua, 'opera')   => 'Opera',
            str_contains($ua, 'chrome/')
                && str_contains($ua, 'safari/') => 'Chrome',
            str_contains($ua, 'firefox/')       => 'Firefox',
            str_contains($ua, 'safari/')
                && ! str_contains($ua, 'chrome') => 'Safari',
            str_contains($ua, 'msie')
                || str_contains($ua, 'trident') => 'Internet Explorer',
            str_contains($ua, 'curl/')          => 'cURL',
            str_contains($ua, 'postman')        => 'Postman',
            default                             => 'Browser Lain',
        };
    }
    private function parseOS(string $ua): string
    {
        $ua = strtolower($ua);

        return match (true) {
            // 1. Cek Android dulu (karena Android juga mengandung kata 'linux')
            str_contains($ua, 'android')       => 'Android',

            // 2. Cek iOS & Perangkat Apple Mobile
            str_contains($ua, 'iphone')        => 'iOS (iPhone)',
            str_contains($ua, 'ipad')          => 'iOS (iPad)',

            // 3. Trick untuk mendeteksi iPadOS 13+ (Deteksi Layar Sentuh pada UA Mac)
            (str_contains($ua, 'macintosh') || str_contains($ua, 'mac os x'))
                && str_contains($ua, 'macintosh') && preg_match('/applewebkit.*version\/.*safari/i', $ua) && !str_contains($ua, 'realtouch')
            => (str_contains($ua, 'macintosh') && isset($_SERVER['HTTP_SEC_CH_UA_PLATFORM']) && json_decode($_SERVER['HTTP_SEC_CH_UA_PLATFORM']) === 'iOS') ? 'iOS (iPad)' : 'macOS',
            // Catatan: Jika tidak mau seribet ini untuk iPad, pakai urutan di bawah ini saja.

            // 4. Cek macOS versi standar
            str_contains($ua, 'macintosh')
                || str_contains($ua, 'mac os x') => 'macOS',

            // 5. Cek Windows (Urutan dari yang paling spesifik ke umum)
            str_contains($ua, 'windows nt 10') => 'Windows 10/11',
            str_contains($ua, 'windows nt 6.3') => 'Windows 8.1',
            str_contains($ua, 'windows nt 6.1') => 'Windows 7',
            str_contains($ua, 'windows')       => 'Windows',

            // 6. Cek Linux Desktop (Harus di bawah Android, karena Android itu berbasis Linux)
            str_contains($ua, 'linux')         => 'Linux',

            default                            => 'OS Lain',
        };
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.admin.user-monitor');
    }
}
