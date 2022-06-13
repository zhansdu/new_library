<?php

declare(strict_types=1);

namespace App\Mail;

use App\Services\Entities\OnQueueMailMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class OnQueueMail.
 */
final class OnQueueMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public OnQueueMailMessage $data
    ) {}

    /**
     * Build the message.
     *
     * @return static
     */
    public function build(): self
    {
        return $this->subject('It works!')
            ->view('emails.on_queue');
    }
}
