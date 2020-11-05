<?php

namespace App\Http\Livewire;

use App\Mail\ContactFormMailable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

/**
 * Class ContactForm
 *
 * @package App\Http\Livewire
 */
class ContactForm extends Component
{
    public $name;
    public $email;
    public $phone;
    public $message;
    public $successMessage;
    protected $rules = [
        'name' => ['required'],
        'email' => ['required', 'email'],
        'phone' => ['required'],
        'message' => ['required', 'min:4']
    ];


    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function submitForm() : void
    {
        $contact = $this->validate();

        sleep(1);

        // Send a notification email
        Mail::to('q.curder@gmail.com')->send(new ContactFormMailable($contact));

        $this->successMessage = 'We receiver your message successfully and will go back to you shortly!';

        $this->resetForm();
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
    private function resetForm()
    {
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->message = '';
    }
}
