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
     * The subject of the email.
     *
     * @var string
     */
    public $subject;

    /**
     * The email content.
     *
     * @var string
     */
    public $content;

    /**
     * Create a new message instance.
     *
     * @param string $subject
     * @param  string  $content
     * @return void
     */
    public function __construct(
        string $subject,
        string $content
    ) {
        $this->subject = $subject;
        $this->content = $content;
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
            ->with(['content' => $this->content]);
    }
}
