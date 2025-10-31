<?php

namespace App\Jobs;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendReceiptMail implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public $orderId)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $order = Order::findOrFail($this->orderId);

        $pdf = Pdf::loadView('emails.receipt', compact('order'));

        $pdf->setPaper('A4', 'portrait');

        return $pdf->download("invoice-{$order->id}.pdf");
    }
}
