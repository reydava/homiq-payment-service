<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use App\Models\Payment;

class ListenOrderCreated extends Command
{
    protected $signature = 'listen:order';

    protected $description = 'Listen order.created event';

    public function handle()
    {
        $this->info('Listening order.created...');

        Redis::subscribe(['order.created'], function ($message) {

            $data = json_decode($message, true);

            $this->info('Order masuk: ' . $data['order_id']);

            $payment = Payment::create([
                'order_id' => $data['order_id'],
                'amount' => $data['amount'],
                'status' => 'SUCCESS'
            ]);

            $this->info('Payment berhasil disimpan');

            Redis::connection('publisher')->publish(
                'payment.success',
                json_encode([
                    'payment_id' => $payment->id,
                    'order_id' => $payment->order_id,
                    'status' => $payment->status
                ])
            );

            $this->info('Event payment.success dikirim');
        });
    }
}