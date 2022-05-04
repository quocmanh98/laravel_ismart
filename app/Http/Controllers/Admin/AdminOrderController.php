<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminOrderController extends Controller {

    function __construct() {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'order']);
            return $next($request);
        });
    }

    function index(Request $request) {
        if ($this->authorize('xem-don-hang')) {
            $status = $request->status;
            $list_act = array(
                'cancel' => 'Hủy đơn hàng',
            );
            if ($status == "cancel") {
                $list_act = [
                    'active' => 'Khôi phục',
                    'forceDelete' => 'Xóa vĩnh viễn'
                ];
                $orders = Order::onlyTrashed()->latest()->paginate(5);
            } else if ($status == "processing") {
                $list_act = [
                    'delivery' => 'Đang giao hàng',
                    'complete' => 'Hoàn thành',
                    'cancel' => 'Hủy đơn hàng',

                ];
                $orders = Order::where('status', 0)->latest()->paginate(5);
            } else if ($status == 'delivery') {
                $list_act = [
                    'processing' => 'Đang xử lý',
                    'complete' => 'Hoàn thành',
                    'cancel' => 'Hủy đơn hàng',

                ];
                $orders = Order::where('status', 1)->latest()->paginate(5);
            } else if ($status == 'complete') {
                $list_act = [
                    'processing' => 'Đang xử lý',
                    'delivery' => 'Đang giao hàng',
                    'cancel' => 'Hủy đơn hàng',
                ];
                $orders = Order::where('status', 2)->latest()->paginate(5);
            } else {
                $kw = "";
                $customerId = array();
                if (!empty($request->kw)) {
                    $kw = $request->kw;
                    $customer = Customer::where('name', 'like', "%{$kw}%")->get();
                    foreach ($customer as $key => $item) {
                        $customerId[] = $item->id;
                    }
                    $orders = Order::whereIn('customer_id', $customerId)->latest()->paginate(5);
                } else {
                    $orders = Order::latest()->paginate(5);
                }
            }
            $count['all'] = Order::count();
            $count['processing'] = Order::where('status', 0)->count();
            $count['delivery'] = Order::where('status', 1)->count();
            $count['complete'] = Order::where('status', 2)->count();
            $count['cancel'] = Order::onlyTrashed()->count();
            return view('admin.order.list', compact('orders', 'count', 'list_act'));
        }
    }

    function seen($id) {
        $order = Order::find($id);
        $selectStatus = ['Đang xử lý', 'Đang giao hàng', 'Hoàn thành'];

        $orderDetails = DB::table('products')->join('order_details', 'order_details.product_id', '=', 'products.id')
            ->join('product_colors', 'product_colors.product_id', '=', 'products.id')
            ->select('product_colors.image_color_path', 'products.name', 'order_details.color', 'products.price', 'order_details.quantity')
            ->where('order_details.order_id', '=', "$id")
            ->where('product_colors.color_id', function ($query) {
                $query->select('colors.id')
                    ->from('colors')
                    ->whereRaw('order_details.color = colors.name');
            })->get();
        return view('admin.order.detail', compact('orderDetails', 'order', 'selectStatus'));
    }

    function changeStatus(Request $request, $id) {
        Order::find($id)->update([
            'status' => (int)$request->input('status')
        ]);

        return redirect()->route('order.index')->with('status', 'Bạn đã chuyển tình trạng đơn hàng thành công');
    }

    function action(Request $request) {
        $list_check = $request->list_check;
        $action = $request->act;
        if (!empty($list_check)) {
            if (!empty($action)) {
                if ($action == 'cancel') {
                    Order::destroy($list_check);
                    return back()->with('status', 'Bạn đã hủy đơn hàng thành công');
                } else if ($action == 'active') {
                    Order::onlyTrashed()->whereIn('id', $list_check)->restore();
                    return back()->with('status', 'Bạn đã khôi phục đơn hàng thành công');
                } else if ($action == 'forceDelete') {
                    Order::onlyTrashed()->whereIn('id', $list_check)->forceDelete();
                    return back()->with('status', 'Bạn đã xóa vĩnh viễn đơn hàng thành công');
                } else if ($action == 'processing') {
                    Order::whereIn('id', $list_check)->update([
                        'status' => 0
                    ]);
                    return back()->with('status', 'Bạn đã chuyển đơn hàng sang đang xử lý thành công');
                } else if ($action == 'delivery') {
                    // dd($list_check);
                    Order::whereIn('id', $list_check)->update([
                        'status' => 1
                    ]);
                    return back()->with('status', 'Bạn đã chuyển đơn hàng sang đang giao hàng thành công');
                } else {
                    Order::whereIn('id', $list_check)->update([
                        'status' => 2
                    ]);
                    return back()->with('status', 'Bạn đã chuyển đơn hàng sang đã hoàn thành thành công ');
                }
            } else {
                return back()->with('errors', 'Bạn vui lòng chọn thao tác thực hiện bản ghi');
            }
        } else {
            return back()->with('errors', 'Bạn vui lòng chọn bản ghi để thực hiện');
        }
    }

    function cancel($id) {
        if ($this->authorize('huy-don-hang')) {
            Order::find($id)->delete();
            return redirect()->route('order.index')->with('status', 'Bạn đã hủy đơn hàng thành công');
        }
    }
}
