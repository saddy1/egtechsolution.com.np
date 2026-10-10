<?php
// app/Mail/OrderPlacedAdmin.php
namespace App\Mail;

use App\Models\Order;
use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;

class OrderPlacedAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order) {}

    public function build()
    {
        return $this->subject('🧾 New Order #'.$this->order->id.' received')
                    ->markdown('backend.emails.orders.admin', ['order' => $this->order]);
    }
}
