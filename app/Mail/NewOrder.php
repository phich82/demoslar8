<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewOrder extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var \App\Models\Order
     */
    public $order;

    /**
     * Raw Data Attachments
     *
     * @var string
     */
    public $text;

    /**
     * Create a new message instance.
     *
     * @param \App\Models\Order $order
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
            // ->attachFromStorage('test.txt', 'order.txt', ['mime' => 'text/plain'])
            // ->attachFromStorageDisk('s3', 'test.txt', 'order.txt', ['mime' => 'text/plain'])
            // ->attachData($this->text, 'order.txt', ['mime' => 'text/plain'])
            ->attach(storage_path('app/test.txt'), [
                'as' => 'order.txt',
                'mime' => 'text/plain',
            ])
            ->with([
                'image' => asset('images/test.jpg')
            ])
            ->view('templates.emails.orders.new');
    }
}
