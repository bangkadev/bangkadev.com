<?php

namespace App\Filament\Resources\SubscriptionResource\Pages;

use App\Filament\Resources\SubscriptionResource;
use App\Models\Project;
use App\Models\Subscription;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListSubscriptions extends ListRecords
{
    protected static string $resource = SubscriptionResource::class;

    protected function getHeaderActions(): array
    {
        if (Auth::user()->role === 'admin') {
            return [
                Actions\CreateAction::make(),
            ];
        }

        $subscription = Subscription::where('user_id', Auth::user()->id)
            ->where('end_date', '>', now())
            ->where('is_active', true)
            ->latest()
            ->first();

        $countProject = Project::where('user_id', Auth::user()->id)->count();
        
        return [
            Actions\Action::make('alert')
            ->label('Project kamu melebihi batas maksimal, silahkan upgrade ke paket premium untuk menambah project.')
            ->color('danger')
            ->icon('heroicon-o-exclamation-triangle')
            ->visible(!$subscription && $countProject >= 2),
            Actions\CreateAction::make()
        ];
    }
}
