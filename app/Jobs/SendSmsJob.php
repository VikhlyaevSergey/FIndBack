<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Nutnet\LaravelSms\SmsSender;

class SendSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var string
     */
    public $phone;

    /**
     * @var string
     */
    public $text;

    /**
     * Create a new job instance.
     *
     * @param $phone
     * @param $text
     */
    public function __construct(string $phone, string $text)
    {
        $this->phone = $phone;
        $this->text  = $text;
    }

    /**
     * Execute the job.
     *
     * @param SmsSender $sms
     *
     * @return void
     */
    public function handle(SmsSender $sms)
    {
        $sms->send($this->phone, $this->text);
    }
}
