<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubscriptionResource\Pages;
use App\Filament\Resources\SubscriptionResource\RelationManagers;
use App\Models\Project;
use App\Models\Subscription;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class SubscriptionResource extends Resource
{
    protected static ?string $model = Subscription::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return parent::getEloquentQuery();
        }

        return parent::getEloquentQuery()
            ->where('user_id', $user->id);
    }

    public static function canCreate(): bool
    {
        if (Auth::user()->role === 'admin') {
            return true;
        }

        $subcription = Subscription::where('user_id', Auth::user()->id)
            ->where('end_date', '>', now())
            ->where('is_active', true)
            ->latest()
            ->first();

        $countProject = Project::where('user_id', Auth::user()->id)->count();

        return !($countProject >= 2 && !$subcription);
    }

    public static function canEdit(Model $record): bool
    {
        if (Auth::user()->role === 'admin') {
            return true;
        }

        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Pengguna')
                    ->options(User::all()->pluck('name', 'id')->toArray())
                    ->required()
                    ->hidden(fn() => Auth::user()->role === 'pengguna'),
                Forms\Components\Toggle::make('is_active')
                    ->required()
                    ->hidden(fn() => Auth::user()->role === 'pengguna'),
                Forms\Components\Repeater::make('subscriptionPayment')
                    ->relationship()
                    ->schema([
                        Forms\Components\FileUpload::make('proof')
                            ->label('Bukti Transfer Ke Rekening 7146979143 (BSI) A/N Dicky Sebesar Rp. 50.000')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'success' => 'Success',
                                'failed' => 'Failed',
                            ])
                            ->required()
                            ->label('Status Pembayaran')
                            ->columnSpanFull()
                            ->hidden(fn() => Auth::user()->role === 'pengguna'),
                    ])
                    ->columnSpanFull()
                    ->addable(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama Pengguna')
                    ->hidden(fn() => Auth::user()->role === 'pengguna'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Tanggal Mulai'),
                Tables\Columns\TextColumn::make('end_date')
                    ->dateTime()
                    ->label('Tanggal Berakhir'),
                Tables\Columns\ImageColumn::make('subscriptionPayment.proof')
                    ->label('Bukti Pembayaran'),
                Tables\Columns\TextColumn::make('subscriptionPayment.status')
                    ->label('Status Pembayaran'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListSubscriptions::route('/'),
            'create' => Pages\CreateSubscription::route('/create'),
            'edit' => Pages\EditSubscription::route('/{record}/edit'),
        ];
    }
}
