<?php

namespace App\Filament\Resources\IntroResource\Pages;

use App\Filament\Resources\IntroResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIntros extends ListRecords
{
    protected static string $resource = IntroResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
