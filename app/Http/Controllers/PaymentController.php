<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PaymentController extends Controller
{
    public function checkout(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret')); // Clave secreta desde el .env

        // Aquí deberías calcular dinámicamente el total desde el carrito
        $session = Session::create([
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => 'Pedido en MiTienda',
                    ],
                    'unit_amount' => 50, // 0.50 € en céntimos
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'customer_email' => auth()->user()->email,
            'success_url' => route('checkout.success', ['status' => 'success']),
            'cancel_url' => route('checkout.cancel', ['status' => 'cancel']),
        ]);

        return redirect($session->url);
    }

    public function success()
    {
        return redirect()->route('home')->with('success', '¡Pago realizado correctamente! Gracias por tu pedido.');
    }
    
    public function cancel()
    {
        return redirect()->route('cart')->with('error', 'El pago fue cancelado. Por favor, intenta nuevamente.');
    }    
}
