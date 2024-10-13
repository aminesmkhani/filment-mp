<?php

namespace App\Models;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Speaker extends Model
{
    use HasFactory;

    const QUALIFICATIONS = [
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
    ];

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

    public function talks(): HasMany
    {
        return $this->hasMany(Talk::class);
    }

    public function conferences(): BelongsToMany
    {
        return $this->belongsToMany(Conference::class);
    }

    public static function getForm() :array
    {
        return [
            TextInput::make('name')
                ->required(),
            FileUpload::make('avatar')
                ->avatar()
                ->directory('avatars')
                ->preserveFilenames()
                ->imageEditor()
                ->maxSize(1024 * 1024 * 10),
            TextInput::make('email')
                ->email()
                ->required(),
            RichEditor::make('bio')
                ->columnSpanFull(),
            TextInput::make('twitter_handel'),
            CheckboxList::make('qualifications')
             ->columnSpanFull()
             ->searchable()
             ->bulkToggleable()
             ->options(self::QUALIFICATIONS)
            ->descriptions([
                'business-leader' => 'Large Business Leader for World Wide Company!',
                'charisma' => 'Charismatic Speaker',
            ])
            ->columns(3)
        ];
    }
}
