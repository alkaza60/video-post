<?php

namespace App\Livewire\Membro;

use Livewire\Component;
use App\Models\Collection;
use App\Models\Activity;
use Illuminate\Support\Facades\DB;

class Home extends Component
{
    public $collections;
    public $recentActivities;
    public $usage;

    public function mount()
    {
        $this->collections = Collection::withCount('videos')->get();
        $this->recentActivities = Activity::latest()->take(5)->get();
        $this->usage = DB::table('videos')
            ->select(DB::raw('SUM(size) as total_size'), DB::raw('COUNT(*) as total_videos'))
            ->first();
    }
    public function render()
    {
        return view('livewire.membro.home');
    }
}
