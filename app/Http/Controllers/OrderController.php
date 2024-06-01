<?php

namespace App\Http\Controllers;

// use App\Http\Requests\InsertOrderRequest;

use App\Http\Requests\InsertOrderRequest;
use App\Models\Officer;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Storage;

timezone_open('Asia/Jakarta');

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::all();

        if ($orders) {
            return response([
                'status' => true,
                'message' => 'Orders fetched successfully',
                'data' => $orders
            ], 200);
        } else {
            return response([
                'status' => false,
                'message' => 'Orders not found',
                'data' => null
            ], 404);
        }
    }

    public function insertOrder(InsertOrderRequest $request)
    {
        $order = Order::create([
            'user_id' => $request->user_id,
            'service_id' => $request->service_id,
            'service_fee' => $request->service_fee,
            'transport_fee' => $request->transport_fee,
            'total' => $request->total,
            'payment_method' => $request->payment_method,
            'status' => $request->status,
            'date' => date('Y-m-d H:i:s'),
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'is_emergency' => 0,
        ]);

        if ($order) {
            return response([
                'status' => true,
                'message' => 'Order created successfully',
                'data' => $order
            ], 200);
        } else {
            return response([
                'status' => false,
                'message' => 'Order not created successfully',
                'data' => null
            ], 200);
        }
    }
    public function insertEmergencyOrder(Request $request)
    {
        $name = date('Y-m-d H:i:s') . '-' . $request->file('bukti')->getClientOriginalName();
        $path = Storage::putFileAs('public/bukti', $request->file('bukti'), $name);

        $order = Order::create([
            'user_id' => $request->user_id,
            'service_id' => $request->service_id,
            'service_fee' => $request->service_fee,
            'transport_fee' => $request->transport_fee,
            'total' => $request->total,
            'payment_method' => $request->payment_method,
            'status' => $request->status,
            'date' => date('Y-m-d H:i:s'),
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'emergency_image' => $name,
            'is_emergency' => 1,
        ]);

        if ($order) {
            return response([
                'status' => true,
                'message' => 'Order created successfully',
                'data' => $order
            ], 200);
        } else {
            return response([
                'status' => false,
                'message' => 'Order not created successfully',
                'data' => null
            ], 200);
        }
    }
    public function getOrderByUserId(Request $request)
    {
        $phone = $request->user_id;
        // $orders = Order::where('user_id', $phone)->where('status', 'waiting')->orderBy('date', 'desc')->first();

        $orders = Order::Select("*")->join('users', 'order.user_id', '=', 'users.phone')->join('services', 'order.service_id', '=', 'services.id')->where('order.user_id', $phone)->where('order.status', 'waiting')->orWhere('order.status', 'on process')->orderBy('order.date', 'desc')->first();
        $data = [
            'order' => $orders
        ];

        if (isset($orders->officer_id) != null || isset($orders->officer_id) != '') {
            $officer = Officer::where('id', $orders->officer_id)->first();

            $data['officer'] = $officer;
        }

        if ($orders) {
            return response([
                'status' => true,
                'message' => 'Orders fetched successfully',
                'data' => $data
            ], 200);
        } else {
            return response([
                'status' => false,
                'message' => 'Orders not found',
                'data' => null
            ], 200);
        }
    }
    public function getLastPesananSelesai(Request $request)
    {
        $phone = $request->user_id;
        // $orders = Order::where('user_id', $phone)->where('status', 'waiting')->orderBy('date', 'desc')->first();

        $orders = Order::Select("order.id as id_order", "order.*", "users.*", "services.*")->join('users', 'order.user_id', '=', 'users.phone')->join('services', 'order.service_id', '=', 'services.id')->where('order.user_id', $phone)->where('order.status', 'complete')->orderBy('order.id', 'desc')->first();
        $data = [
            'order' => $orders
        ];

        if (isset($orders->officer_id) != null || isset($orders->officer_id) != '') {
            $officer = Officer::where('id', $orders->officer_id)->first();

            $data['officer'] = $officer;
        }

        if ($orders) {
            return response([
                'status' => true,
                'message' => 'Orders fetched successfully',
                'data' => $data
            ], 200);
        } else {
            return response([
                'status' => false,
                'message' => 'Orders not found',
                'data' => null
            ], 200);
        }
    }

    public function getWaitingOrders(Request $request)
    {
        $phone = $request->user_id;

        $orders = Order::select('order.id as id_order', 'order.total as total', 'services.name as service_name', 'order.officer_id')->join('services', 'order.service_id', '=', 'services.id')->where('user_id', $phone)->where('status', 'waiting')->orWhere('status', 'on process')->orderBy('date', 'desc')->get();

        $dataOrder = array();

        foreach ($orders as $o) {
            $curr_array = [
                'order_id' => $o->id_order,
                'service_name' => $o->service_name,
                'total' => number_format($o->total, 0, '.', '.'),
            ];

            if ($o->officer_id) {
                $curr_array['officer'] = Officer::where('id', $o->officer_id)->first()['name'];
            } else {
                $curr_array['officer'] = null;
            }
            $dataOrder[] = $curr_array;
        }

        $data = [
            'orders' => $dataOrder,
        ];

        if ($orders) {
            return response([
                'status' => true,
                'message' => 'Orders fetched successfully',
                'data' => $data
            ], 200);
        } else {
            return response([
                'status' => false,
                'message' => 'Orders not found',
                'data' => null
            ], 200);
        }
    }

    public function getCompletedOrder(Request $request)
    {
        $phone = $request->user_id;

        $orders = Order::select('order.id as id_order', 'order.total as total', 'services.name as service_name', 'order.officer_id')->join('services', 'order.service_id', '=', 'services.id')->where('user_id', $phone)->where('status', 'complete')->orderBy('date', 'desc')->get();

        $dataOrder = array();

        foreach ($orders as $o) {
            $curr_array = [
                'order_id' => $o->id_order,
                'service_name' => $o->service_name,
                'total' => number_format($o->total, 0, '.', '.'),
            ];

            if ($o->officer_id) {
                $curr_array['officer'] = Officer::where('id', $o->officer_id)->first()['name'];
            } else {
                $curr_array['officer'] = null;
            }
            $dataOrder[] = $curr_array;
        }

        $data = [
            'orders' => $dataOrder,
        ];

        if ($orders) {
            return response([
                'status' => true,
                'message' => 'Orders fetched successfully',
                'data' => $data
            ], 200);
        } else {
            return response([
                'status' => false,
                'message' => 'Orders not found',
                'data' => null
            ], 200);
        }
    }

    public function getCancelledOrder(Request $request)
    {
        $phone = $request->user_id;

        $orders = Order::select('order.id as id_order', 'order.total as total', 'services.name as service_name', 'order.officer_id')->join('services', 'order.service_id', '=', 'services.id')->where('user_id', $phone)->where('status', 'canceled')->orderBy('date', 'desc')->get();

        foreach ($orders as $o) {
            $curr_array = [
                'order_id' => $o->id_order,
                'service_name' => $o->service_name,
                'total' => number_format($o->total, 0, '.', '.'),
            ];

            if ($o->officer_id) {
                $curr_array['officer'] = Officer::where('id', $o->officer_id)->first()['name'];
            } else {
                $curr_array['officer'] = null;
            }
            $dataOrder[] = $curr_array;
        }

        $data = [
            'orders' => $dataOrder,
        ];

        if ($orders) {
            return response([
                'status' => true,
                'message' => 'Orders fetched successfully',
                'data' => $data
            ], 200);
        } else {
            return response([
                'status' => false,
                'message' => 'Orders not found',
                'data' => null
            ], 200);
        }
    }

    public function cancelOrder(Request $request)
    {
        $phone = $request->user_id;
        $order_id = $request->order_id;
        $reason = $request->reason;

        $order = Order::where('id', $order_id)->where('user_id', $phone)->update(['status' => 'canceled', 'cancelled_reason' => $reason]);

        if ($order) {
            return response([
                'status' => true,
                'message' => 'Order canceled successfully',
            ], 200);
        } else {
            return response([
                'status' => false,
                'message' => 'Order not canceled',
            ], 200);
        }
    }

    public function detailCompletedOrder(Request $request)
    {
        $order_id = $request->order_id;

        $orders = Order::Select("*")->join('users', 'order.user_id', '=', 'users.phone')->join('services', 'order.service_id', '=', 'services.id')->where('order.id', $order_id)->where('order.status', 'complete')->orderBy('order.date', 'desc')->first();
        $data = [
            'order' => $orders
        ];

        if (isset($orders->officer_id) != null || isset($orders->officer_id) != '') {
            $officer = Officer::where('id', $orders->officer_id)->first();

            $data['officer'] = $officer;
        }
        if ($orders) {
            return response([
                'status' => true,
                'message' => 'Order fetched successfully',
                'data' => $data
            ], 200);
        } else {
            return response([
                'status' => false,
                'message' => 'Order not found',
                'data' => null
            ], 200);
        }
    }
    public function detailCanceledOrder(Request $request)
    {
        $order_id = $request->order_id;

        $orders = Order::Select("*")->join('users', 'order.user_id', '=', 'users.phone')->join('services', 'order.service_id', '=', 'services.id')->where('order.id', $order_id)->where('order.status', 'canceled')->orderBy('order.date', 'desc')->first();
        $data = [
            'order' => $orders
        ];

        if (isset($orders->officer_id) != null || isset($orders->officer_id) != '') {
            $officer = Officer::where('id', $orders->officer_id)->first();

            $data['officer'] = $officer;
        }
        if ($orders) {
            return response([
                'status' => true,
                'message' => 'Order fetched successfully',
                'data' => $data
            ], 200);
        } else {
            return response([
                'status' => false,
                'message' => 'Order not found',
                'data' => null
            ], 200);
        }
    }

    public function submitReview(Request $request)
    {
        $order_id = $request->order_id;
        $rating = $request->rating;
        $review = $request->customer_notes;

        $order = Order::where('id', $order_id)->update(['rating' => $rating, 'customer_notes' => $review]);

        if ($order) {
            return response([
                'status' => true,
                'message' => 'Review submitted successfully',
            ], 200);
        } else {
            return response([
                'status' => false,
                'message' => 'Review not submitted',
            ], 200);
        }
    }
}
