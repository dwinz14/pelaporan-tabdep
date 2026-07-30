<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;

class UserLogDetail extends Component
{
    use WithPagination;

    public User $user;

    #[Url(as: 'log_name')]
    public string $selectedLogName = '';

    #[Url(as: 'dari')]
    public string $logDari = '';

    #[Url(as: 'sampai')]
    public string $logSampai = '';

    public int $perPage = 25;

    public function mount(User $user)
    {
        $this->user = $user;
        
        if (empty($this->logDari) && empty($this->logSampai)) {
            $this->logDari   = now()->subDays(30)->format('Y-m-d');
            $this->logSampai = now()->format('Y-m-d');
        }
    }

    public function updatedSelectedLogName(): void
    {
        $this->resetPage();
    }

    public function updatedLogDari(): void
    {
        $this->resetPage();
    }

    public function updatedLogSampai(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->selectedLogName = '';
        $this->logDari         = now()->subDays(30)->format('Y-m-d');
        $this->logSampai       = now()->format('Y-m-d');
        $this->resetPage();
    }

    #[Computed(cache: false)]
    public function logNames()
    {
        return Activity::query()
            ->where('causer_id', $this->user->id)
            ->where('causer_type', User::class)
            ->select('log_name')
            ->distinct()
            ->orderBy('log_name')
            ->pluck('log_name');
    }

    #[Computed(cache: false)]
    public function logs()
    {
        $q = Activity::where('causer_id', $this->user->id)
            ->where('causer_type', User::class)
            ->latest();

        if ($this->selectedLogName) {
            $q->where('log_name', $this->selectedLogName);
        }

        if ($this->logDari) {
            $q->whereDate('created_at', '>=', $this->logDari);
        }

        if ($this->logSampai) {
            $q->whereDate('created_at', '<=', $this->logSampai);
        }

        return $q->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.admin.user-log-detail');
    }
}
