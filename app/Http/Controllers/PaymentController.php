<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderMail;

class PaymentController extends Controller
{
    public function checkout(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $user = auth()->user();
        $cart = session('cart', []);
        $addressData = session('address', []);

        if (!$cart || ($request->has('send_home') && !$addressData)) {
            return back()->with('error', 'No se ha podido iniciar el pago.');
        }

        $amount = intval(round($request['precio_total'] * 100));

        // Guardar temporalmente los datos en la sesión
        session([
            'checkout_pending' => [
                'cart' => $cart,
                'address' => $addressData,
                'send_home' => $request->has('send_home'),
                'precio_total' => $request->precio_total,
            ]
        ]);

        $session = \Stripe\Checkout\Session::create([
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => ['name' => 'Pedido en DLG'],
                    'unit_amount' => intval($amount),
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'customer_email' => $user->email,
            'success_url' => route('checkout.success'),
            'cancel_url' => route('checkout.cancel'),
        ]);

        return redirect($session->url);
    }

    public function success()
    {
        DB::beginTransaction();
    
        try {
            $user = auth()->user();
            $data = session('checkout_pending');
    
            if (!$data) {
                return redirect()->route('cart')->with('error', 'No se encontró información del pago.');
            }
    
            $cart = $data['cart'];
            $addressData = $data['address'];
    
            $order = new Order();
            $order->id_user = $user->id;
            $order->fecha = now();
            $order->precio_total = $data['precio_total'];
    
            if ($data['send_home']) {
                if (isset($addressData['id']) && $user->addresses()->where('id', $addressData['id'])->exists()) {
                    $address = Address::find($addressData['id']);
                } else {
                    $address = $user->addresses()->create($addressData);
                }
    
                $order->id_address = $address->id;
            }
    
            $order->save();
    
            $orderItems = [];
            foreach ($cart as $item) {
                if ($item['item'] instanceof \App\Models\Item) {
                    $orderItems[$item['item']->id] = ['cantidad' => $item['cantidad']];
                }
            }
    
            $order->items()->sync($orderItems);
    
            Mail::to($user->email)->send(new OrderMail($order, $user));
    
            // Limpiar sesión
            session()->forget(['cart', 'address', 'checkout_pending']);
    
            DB::commit();
    
            return redirect()->route('home')->with('success', '¡Pago realizado correctamente! Gracias por tu pedido.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('cart')->with('error', 'Error al finalizar el pedido: ' . $e->getMessage());
        }
    }    
    
    public function cancel()
    {
        return redirect()->route('cart')->with('error', 'El pago fue cancelado. Por favor, intenta nuevamente.');
    }    
}
