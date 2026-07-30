<?php

namespace App\Livewire\Admin;

use App\Models\Cabang;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class UserMonitor extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'role')]
    public string $role = '';

    #[Url(as: 'cabang')]
    public string $cabang = '';

    public int $perPage = 25;

    public ?string $flashSuccess = null;
    public ?string $flashError   = null;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedRole(): void
    {
        $this->resetPage();
    }

    public function updatedCabang(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'role', 'cabang']);
        $this->resetPage();
    }

    #[Computed(cache: false)]
    public function cabangs()
    {
        return Cabang::orderBy('nama_cabang')->get();
    }

    #[Computed(cache: false)]
    public function users()
    {
        $lifetime = (int) config('session.lifetime', 120);
        $activeTimestamp = now()->subMinutes($lifetime)->timestamp;

        $query = User::query()
            ->select('users.*')
            ->with('cabang');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('nik', 'like', '%' . $this->search . '%');
            });
        }
        if ($this->role) {
            $query->where('role', $this->role);
        }
        if ($this->cabang) {
            $query->where('id_cabang', $this->cabang);
        }

        // Subqueries for latest activity info
        $query->selectSub(
            DB::table('sessions')
                ->whereColumn('user_id', 'users.id')
                ->select('id')
                ->orderBy('last_activity', 'desc')
                ->limit(1),
            'latest_session_id'
        );

        $query->selectSub(
            DB::table('sessions')
                ->whereColumn('user_id', 'users.id')
                ->select('last_activity')
                ->orderBy('last_activity', 'desc')
                ->limit(1),
            'latest_session_activity'
        );

        $query->selectSub(
            DB::table('activity_log')
                ->whereColumn('causer_id', 'users.id')
                ->where('causer_type', User::class)
                ->select('created_at')
                ->orderBy('created_at', 'desc')
                ->limit(1),
            'latest_log_activity'
        );

        // Sort by Online > Session Activity > Log Activity
        $query->orderByRaw('(latest_session_activity >= ?) DESC', [$activeTimestamp])
              ->orderBy('latest_session_activity', 'desc')
              ->orderBy('latest_log_activity', 'desc');

        $paginated = $query->paginate($this->perPage);

        // Enrich the collection with session info
        $paginated->getCollection()->transform(function ($user) use ($activeTimestamp) {
            $isOnline = $user->latest_session_activity >= $activeTimestamp;
            $user->is_online = $isOnline;
            $user->last_seen = $isOnline 
                ? \Carbon\Carbon::createFromTimestamp($user->latest_session_activity)
                : ($user->latest_log_activity ? \Carbon\Carbon::parse($user->latest_log_activity) : null);
            
            if ($isOnline && $user->latest_session_id) {
                $session = DB::table('sessions')->where('id', $user->latest_session_id)->first();
                if ($session) {
                    $user->session_id = $session->id;
                    $user->ip_address = $session->ip_address;
                    $user->user_agent = $session->user_agent;
                    $user->browser    = $this->parseBrowser($session->user_agent);
                    $user->os         = $this->parseOS($session->user_agent);
                    $user->is_current = $session->id === session()->getId();
                }
            }

            return $user;
        });

        return $paginated;
    }

    public function forceLogout(string $sessionId): void
    {
        $this->resetFlash();

        if ($sessionId === session()->getId()) {
            $this->flashError = 'Anda tidak dapat me-logout sesi Anda sendiri.';
            return;
        }

        $session = DB::table('sessions')->where('id', $sessionId)->first();
        if (! $session) {
            $this->flashError = 'Sesi tidak ditemukan atau sudah berakhir.';
            return;
        }

        $targetUser = User::find($session->user_id);
        DB::table('sessions')->where('id', $sessionId)->delete();

        activity('monitoring')
            ->causedBy(auth()->user())
            ->withProperties([
                'target_user'   => $targetUser?->name,
                'target_nik'    => $targetUser?->nik,
                'target_ip'     => $session->ip_address,
                'session_id'    => substr($sessionId, 0, 8) . '...',
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

    private function resetFlash(): void
    {
        $this->flashSuccess = null;
        $this->flashError   = null;
    }

    private function parseBrowser(string $ua): string
    {
        $ua = strtolower($ua);
        return match (true) {
            str_contains($ua, 'edg/')           => 'Edge',
            str_contains($ua, 'opr/') || str_contains($ua, 'opera') => 'Opera',
            str_contains($ua, 'chrome/') && str_contains($ua, 'safari/') => 'Chrome',
            str_contains($ua, 'firefox/')       => 'Firefox',
            str_contains($ua, 'safari/') && !str_contains($ua, 'chrome') => 'Safari',
            str_contains($ua, 'msie') || str_contains($ua, 'trident') => 'Internet Explorer',
            str_contains($ua, 'curl/')          => 'cURL',
            str_contains($ua, 'postman')        => 'Postman',
            default                             => 'Browser Lain',
        };
    }

    private function parseOS(string $ua): string
    {
        $ua = strtolower($ua);
        return match (true) {
            str_contains($ua, 'android')       => 'Android',
            str_contains($ua, 'iphone')        => 'iOS (iPhone)',
            str_contains($ua, 'ipad')          => 'iOS (iPad)',
            (str_contains($ua, 'macintosh') || str_contains($ua, 'mac os x')) && str_contains($ua, 'macintosh') && preg_match('/applewebkit.*version\/.*safari/i', $ua) && !str_contains($ua, 'realtouch') => (str_contains($ua, 'macintosh') && isset($_SERVER['HTTP_SEC_CH_UA_PLATFORM']) && json_decode($_SERVER['HTTP_SEC_CH_UA_PLATFORM']) === 'iOS') ? 'iOS (iPad)' : 'macOS',
            str_contains($ua, 'macintosh') || str_contains($ua, 'mac os x') => 'macOS',
            str_contains($ua, 'windows nt 10') => 'Windows 10/11',
            str_contains($ua, 'windows nt 6.3') => 'Windows 8.1',
            str_contains($ua, 'windows nt 6.1') => 'Windows 7',
            str_contains($ua, 'windows')       => 'Windows',
            str_contains($ua, 'linux')         => 'Linux',
            default                            => 'OS Lain',
        };
    }

    public function render()
    {
        return view('livewire.admin.user-monitor');
    }
}
