<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Mail extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * The subject of the message.
     *
     * @var string
     */
    public $subject;

    /**
     * The message content.
     *
     * @var string
     */
    public $messageContent;

    /**
     * Create a new message instance.
     *
     * @param  string  $subject
     * @param  string  $messageContent
     * @return void
     */
    public function __construct($subject, $messageContent)
    {
        $this->subject = $subject;
        $this->messageContent = $messageContent;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)
            ->view('emails.notify-transaction')
            ->with(['messageContent' => $this->messageContent]);
    }
}
