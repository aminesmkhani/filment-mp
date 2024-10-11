<?php

namespace App\Models;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Speaker extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'bio',
        'twitter_handel',
        'qualifications'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'qualifications' => 'array'
    ];

    public function conferences(): BelongsToMany
    {
        return $this->belongsToMany(Conference::class);
    }

    public static function getForm() :array
    {
        return [
            TextInput::make('name')
                ->required(),
            TextInput::make('email')
                ->email()
                ->required(),
            Textarea::make('bio')
                ->required()
                ->columnSpanFull(),
            TextInput::make('twitter_handel')
                ->required(),
            CheckboxList::make('qualifications')
             ->columnSpanFull()
             ->searchable()
             ->bulkToggleable()
             ->options([
                 'business-leader' => 'Business Leader',
                 'charisma' => 'Charismatic Speaker',
                 'first-time' => 'First Time Speaker',
                 'hometown-hero' => 'Hometown Hero',
                 'humanitarian' => 'Works in Humanitarian Field',
                 'laracast-contributor' => 'Laracast Contributor',
                 'twitter-influencer' => 'Large Twitter Following',
                 'youtube-influencer' => 'Large Youtube Following',
                 'open-source' => 'Open Source Creator / Maintainer',
                 'unique-perspective' => 'Unique perspective',
             ])
            ->descriptions([
                'business-leader' => 'Large Business Leader for World Wide Company!',
                'charisma' => 'Charismatic Speaker',
            ])
            ->columns(3)
        ];
    }
}
