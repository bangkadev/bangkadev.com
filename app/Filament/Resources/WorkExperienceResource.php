<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorkExperienceResource\Pages;
use App\Filament\Resources\WorkExperienceResource\RelationManagers;
use App\Models\WorkExperience;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class WorkExperienceResource extends Resource
{
    protected static ?string $model = WorkExperience::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationLabel = 'Manajemen Pengalaman Kerja';
    protected static ?string $navigationGroup = 'Data Master';

    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return parent::getEloquentQuery();
        }

        return parent::getEloquentQuery()
            ->where('user_id', $user->id);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Pengguna')
                    ->relationship('user', 'name')
                    ->required()
                    ->hidden(fn() => Auth::user()->role === 'pengguna'),
                Forms\Components\TextInput::make('company')
                    ->label('Perusahaan')
                    ->required(),
                Forms\Components\FileUpload::make('logo')
                    ->label('Logo Perusahaan')
                    ->image()
                    ->required(),
                Forms\Components\TextInput::make('position')
                    ->label('Posisi')
                    ->required(),
                Forms\Components\TextInput::make('start_year')
                    ->label('Tahun Mulai')
                    ->numeric()
                    ->minValue(1900)
                    ->maxValue(2100)
                    ->required(),
                Forms\Components\TextInput::make('end_year')
                    ->label('Tahun Berakhir')
                    ->numeric()
                    ->minValue(1900)
                    ->maxValue(2100)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pengguna')
                    ->hidden(fn() => Auth::user()->role === 'pengguna'),
                Tables\Columns\TextColumn::make('company')
                    ->label('Perusahaan'),
                Tables\Columns\ImageColumn::make('logo')
                    ->label('Logo Perusahaan'),
                Tables\Columns\TextColumn::make('position')
                    ->label('Posisi'),
                Tables\Columns\TextColumn::make('start_year')
                    ->label('Tahun Mulai'),
                Tables\Columns\TextColumn::make('end_year')
                    ->label('Tahun Berakhir'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWorkExperiences::route('/'),
            'create' => Pages\CreateWorkExperience::route('/create'),
            'edit' => Pages\EditWorkExperience::route('/{record}/edit'),
        ];
    }
}
