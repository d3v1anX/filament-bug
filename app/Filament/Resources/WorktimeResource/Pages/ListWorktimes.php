<?php

namespace App\Filament\Resources\WorktimeResource\Pages;

use App\Filament\Resources\WorktimeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWorktimes extends ListRecords
{
    protected static string $resource = WorktimeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
