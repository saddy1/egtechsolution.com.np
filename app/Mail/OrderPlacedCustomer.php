<?php
// app/Mail/OrderPlacedCustomer.php
namespace App\Mail;

use App\Models\Order;
use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;

class OrderPlacedCustomer extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order) {}

    public function build()
    {
        return $this->subject('Your Order #'.$this->order->id.' at EG Tech Solution')
                    ->markdown('backend.emails.orders.customer', ['order' => $this->order]);
    }
}
