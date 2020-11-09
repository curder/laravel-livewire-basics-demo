<?php
namespace Tests\Feature;

use App\Http\Livewire\ContactForm;
use App\Mail\ContactFormMailable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Class ContactFormTest
 *
 * @package Tests\Feature
 */
class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function examples_page_contains_contract_form_livewire_component()
    {
        $this->get('/examples')->assertSeeLivewire('contact-form');
    }

    /** @test */
    public function contact_form_send_outs_an_email()
    {
        Mail::fake();

        Livewire::test('contact-form')
                ->set('name', 'curder')
                ->set('email', 'example@example.com')
                ->set('phone', 1234567)
                ->set('message', 'This is my message')
                ->call('submitForm')
                ->assertSee('We receiver your message successfully and will go back to you shortly!');

        Mail::assertSent(function (ContactFormMailable $mail) {
            $mail->build();

            return $mail->hasTo('q.curder@gmail.com')
                && $mail->hasFrom('example@example.com')
                && $mail->subject === 'Contact Form Submission';
        });
    }

    /** @test */
    public function contact_form_name_field_is_required()
    {
        Livewire::test(ContactForm::class)
                ->set('email', 'example@example.com')
                ->set('phone', 1234567)
                ->set('message', 'This is my message')
                ->call('submitForm')
                ->assertHasErrors(['name' => 'required']);
    }

    /** @test */
    public function contact_form_email_field_is_required()
    {
        Livewire::test(ContactForm::class)
                ->set('name', 'curder')
                ->set('phone', 1234567)
                ->set('message', 'This is my message')
                ->call('submitForm')
                ->assertHasErrors(['email' => 'required']);
    }

    /** @test */
    public function contact_form_email_field_is_an_email()
    {
        Livewire::test(ContactForm::class)
                ->set('name', 'curder')
                ->set('email', 'email')
                ->set('phone', 1234567)
                ->set('message', 'This is my message')
                ->call('submitForm')
                ->assertHasErrors(['email' => 'email']);
    }

    /** @test */
    public function contact_form_message_has_minimum_characters()
    {
        Livewire::test(ContactForm::class)
                ->set('message', 'abc')
                ->call('submitForm')
                ->assertHasErrors(['message' => 'min']);
    }
}
