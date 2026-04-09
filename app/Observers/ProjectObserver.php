<?php

namespace App\Observers;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class ProjectObserver
{
    public function updated(Project $project): void
    {
        if ($project->wasChanged('status')) {
            $project->activities()->create([
                'user_id'   => Auth::id() ?? $project->user_id,
                'type'      => 'status_change',
                'content'   => 'ステータスを変更しました',
                'old_value' => $project->getOriginal('status'),
                'new_value' => $project->status,
            ]);
        }
    }
}
