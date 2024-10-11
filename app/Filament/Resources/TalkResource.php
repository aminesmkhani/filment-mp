<?php

namespace App\Filament\Resources;

use App\Enums\TalkLength;
use App\Filament\Resources\TalkResource\Pages;
use App\Filament\Resources\TalkResource\RelationManagers;
use App\Models\Talk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class TalkResource extends Resource
{
    protected static ?string $model = Talk::class;

    protected static ?string $navigationIcon = 'heroicon-o-microphone';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required(),
                Forms\Components\Textarea::make('abstract')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Select::make('speaker_id')
                    ->relationship('speaker', 'name')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->persistFiltersInSession()
            ->filtersTriggerAction(function ($action){
                return $action->button()->label('Filters');
            })
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->sortable()
                    ->searchable()
                    ->description(function (Talk $record){
                        return Str::limit($record->abstract,30);
                    }),
                Tables\Columns\ImageColumn::make('speaker.avatar')
                    ->label('Speaker Avatar')
                ->circular()
                ->defaultImageUrl(function ($record){
                    return 'https://ui-avatars.com/api/?background=0D8ABC&color=fff&name='. urlencode($record->speaker->name);
                }),
                Tables\Columns\TextColumn::make('speaker.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('new_talk'),
                Tables\Columns\TextColumn::make('status')
                    ->sortable()
                    ->badge()
                    ->color(function ($state){
                        return $state->getColor();
                    }),
                Tables\Columns\IconColumn::make('length')
                ->icon(function ($state){
                   return match ($state){
                       TalkLength::NORMAL => 'heroicon-o-megaphone',
                       TalkLength::LIGHTNING => 'heroicon-o-bolt',
                       TalkLength::KEYNOTE => 'heroicon-o-key',
                   };
                })
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('new_talk'),
                Tables\Filters\SelectFilter::make('speaker')
                    ->multiple()
                    ->searchable()
                    ->preload()
                ->relationship('speaker', 'name'),
                Tables\Filters\Filter::make('has_avatar')
                    ->label('Show Only Speaker has Avatar')
                    ->toggle()
                ->query(function ($query){
                    return $query->whereHas('speaker', function(Builder $query){
                        return $query->whereNotNull('avatar');
                    });
                })
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
            'index' => Pages\ListTalks::route('/'),
            'create' => Pages\CreateTalk::route('/create'),
            'edit' => Pages\EditTalk::route('/{record}/edit'),
        ];
    }
}
