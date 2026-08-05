<?php

namespace App\Livewire\Public;

use App\Models\Subscription;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Validate;
use Livewire\Component;

class NewsletterForm extends Component
{
    #[Validate('required|email|max:255|unique:subscriptions,email')]
    public string $email = '';

    public bool $submitted = false;

    public function subscribe(): void
    {
        $this->validate();

        Subscription::query()->create(['email' => $this->email]);

        $this->submitted = true;
        $this->reset('email');
    }

    public function render(): View
    {
        return view('livewire.public.newsletter-form');
    }
}
