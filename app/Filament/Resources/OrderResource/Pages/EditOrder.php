<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function resolveRecord(int|string $key): \Illuminate\Database\Eloquent\Model
    {
        // ⬅️ Ini yang penting!
        return $this->getModel()::with(['user', 'product'])->findOrFail($key);
    }
}
