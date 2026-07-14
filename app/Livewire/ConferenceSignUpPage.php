<?php

namespace App\Livewire;

use App\Models\Attendee;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Illuminate\Support\HtmlString;
use Livewire\Component;

class ConferenceSignUpPage extends Component implements HasForms,HasActions
{
    use InteractsWithActions, InteractsWithForms;

    public int $conferenceId;
    public int $price = 50000;
    public function mount()
    {
        $this-> conferenceId=1;
    }
    public function signUpAction(): Action
    {
        return Action::make('signUp')
            ->slideOver()
            ->form([
                Placeholder::make('total_price')
                ->hiddenLabel()
                ->contect(function(Get $get){
                // return new HtmlString('')
                   return count($get('attendess')) * 500;
                }),
                Repeater::make('atendees')
                ->schema(Attendee::getForm()),
            ])
            ->action(function($data) {
                Attendee::create([
                    'conference_id' =>$this->conferenceId,
                    'ticket_cost' => $this->price,
                    'name' =>$data['name'],
                    'email' =>$data['email'],
                    'is_paid' => true,
                ]);
            })
            ->after(function(){
                Notification::make()->success()->itle('success')->
                    body(new HtmlString('You have succesfully signed up for ther conference'))
                    ->send();
            });
    }

    public function render()
    {
        return view('livewire.conference-sign-up-page');
    }
}
