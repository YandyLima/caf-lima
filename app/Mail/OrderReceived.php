<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderReceived extends Mailable
{
    use Queueable, SerializesModels;

    public $sale;
    public $userName;

    public function __construct($sale)
    {
        $this->sale = $sale;
        $this->userName = $sale->user->name;
    }

    public function build()
    {
        return $this->view('mail.order-received')
            ->subject('Orden recibida');
    }
}
