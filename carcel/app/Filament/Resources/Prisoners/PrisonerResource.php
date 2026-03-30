<?php
namespace App\Filament\Resources\Prisoners;

use App\Filament\Resources\Prisoners\Pages\CreatePrisoner;
use App\Filament\Resources\Prisoners\Pages\EditPrisoner;
use App\Filament\Resources\Prisoners\Pages\ListPrisoners;
use App\Filament\Resources\Prisoners\Schemas\PrisonerForm;
use App\Filament\Resources\Prisoners\Tables\PrisonersTable;
use App\Models\Prisoner;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PrisonerResource extends Resource
{
    protected static ?string $model = Prisoner::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $modelLabel = 'Prisionero';
    protected static ?string $pluralModelLabel = 'Prisioneros';
    protected static ?string $navigationLabel = 'Prisioneros';
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return PrisonerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PrisonersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPrisoners::route('/'),
            'create' => CreatePrisoner::route('/create'),
            'edit' => EditPrisoner::route('/{record}/edit'),
        ];
    }
}