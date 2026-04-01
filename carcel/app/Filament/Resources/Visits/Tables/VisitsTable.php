<?php
namespace App\Filament\Resources\Visits\Tables;

use App\Models\Visit;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class VisitsTable
{
    public static function configure(Table $table): Table
    {
        // Auto-completar visitas cuya hora de fin ya pasó
        Visit::where('status', 'pendiente')
            ->where('end_time', '<', now())
            ->update(['status' => 'completada']);

        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('prisoner.full_name')
                    ->label('Prisionero')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('visitor.full_name')
                    ->label('Visitante')
                    ->searchable(),

                TextColumn::make('relationship')
                    ->label('Relación')
                    ->searchable(),

                TextColumn::make('officer.name')
                    ->label('Guardia')
                    ->searchable(),

                TextColumn::make('start_time')
                    ->label('Inicio')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('end_time')
                    ->label('Fin')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pendiente' => 'warning',
                        'completada' => 'success',
                        'cancelada' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pendiente' => 'Pendiente',
                        'completada' => 'Completada',
                        'cancelada' => 'Cancelada',
                        default => $state,
                    }),
            ])
            ->defaultSort('start_time', 'desc')
            ->filters([])
            ->recordActions([
                Action::make('cancelar')
                    ->label('Cancelar visita')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->requiresConfirmation()
                    ->modalHeading('¿Cancelar esta visita?')
                    ->modalDescription('Esta acción no se puede revertir.')
                    ->modalSubmitActionLabel('Sí, cancelar')
                    ->visible(fn(Visit $record): bool => $record->status === 'pendiente')
                    ->action(function (Visit $record) {
                        $record->update(['status' => 'cancelada']);

                        Notification::make()
                            ->title('Visita cancelada')
                            ->success()
                            ->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Eliminar seleccionados'),
                ]),
            ]);
    }
}