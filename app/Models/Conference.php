<?php

namespace App\Models;

use App\Enums\Region;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;

class Conference extends Model
{
    use HasFactory;

    protected $casts =[
            'id' => 'integer',
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'region' => Region::class,
            'venue_id' => 'integer',
        ];

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function speakers(): BelongsToMany
    {
        return $this->belongsToMany(Speaker::class);
    }

    public function talks(): BelongsToMany
    {
        return $this->belongsToMany(Talk::class);
    }
    public static function getForm():array
    {
        return [
            Tabs::make()
            ->columnSpanFull()
            ->tabs([
                Tabs\Tab::make('Conference Details')
                ->schema([
                     TextInput::make('name')
                     ->columnSpanFull()
                    ->required() ->helperText('The name of the conference')->default('My Conference'),
                    RichEditor::make('description')
                    ->columnSpan(2)
                    ->required()->hint('here is the hint')->hintIcon('heroicon-o-rectangle-stack')->toolbarButtons(['h2','bold']),
                    DatePicker::make('start_date')
                    ->required(),
                    DateTimePicker::make('end_date')
                    ->required()->native(false),
                    Fieldset::make('Status')
                    ->columns(1)
                    ->schema([
                        Select::make('status')
                        ->required()->options([
                            'draft' => 'Draft',
                            'published' => 'Published',
                            'archiverd' => 'Archived',
                        ]),
                         Toggle::make('is_published')->default(true),
                    ])
                ]),
                Tabs\Tab::make('Locations')
                ->schema([
                     Select::make('region')
                    ->options([
                        'US' => 'US',
                        'EU' => 'EU',
                        'AU' => 'AU',
                        'India' => 'India',
                        'Online' => 'Online',
                    ]),

                    Select::make('venue_id')
                        ->searchable()
                        ->preload() // for speed
                        ->createOptionForm(Venue::getForm())
                        ->editOptionForm(Venue::getForm())
                        ->relationship('venue', 'name',modifyQueryUsing:function(Builder $query,Forms\Get $get) {
                            // ray();
                            return $query->where('region',$get(path:'region'));
                        }),

                    ]),
            ]),
            // Section::make('Conference Details')
            //     // ->aside() მარცხნივ გაიწევა
            //     ->collapsible()
            //     ->columns(['md' => 2,'lg'=>3])

                // Section::make('Location')
                // ->columns(2),
                // CheckboxList::make('speakers')
                //     ->relationship('speakers','name')
                //     ->required(),
            ];
    }
}
