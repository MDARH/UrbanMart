@extends('backend.layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Wholesale Order Details') }} - {{ $order->code }}</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6">
                    <h6>{{ translate('Order Information') }}</h6>
                    <p><strong>{{ translate('Order ID') }}:</strong> {{ $order->id }}</p>
                    <p><strong>{{ translate('Order Code') }}:</strong> {{ $order->code }}</p>
                    <p><strong>{{ translate('Order Date') }}:</strong> {{ $order->created_at->format('d-m-Y h:i A') }}</p>
                    <p><strong>{{ translate('Wholesaler Name') }}:</strong>
                        @if ($order->user)
                            {{ $order->user->name }} ({{ $order->user->email }})
                        @else
                            {{ translate('N/A') }}
                        @endif
                    </p>
                    <p><strong>{{ translate('Total Amount') }}:</strong> {{ format_price($order->grand_total) }}</p>
                    <p><strong>{{ translate('Payment Status') }}:</strong>
                        @if ($order->payment_status == 'paid')
                            <span class="badge badge-inline badge-success">{{ translate('Paid') }}</span>
                        @else
                            <span class="badge badge-inline badge-danger">{{ translate('Unpaid') }}</span>
                        @endif
                    </p>
                    <p><strong>{{ translate('Delivery Status') }}:</strong> {{ translate(ucfirst(str_replace('_', ' ', $order->delivery_status))) }}</p>
                </div>
                <div class="col-lg-6">
                    <h6>{{ translate('Shipping Address') }}</h6>
                    @if ($order->shipping_address)
                        <p>{{ $order->shipping_address->address ?? translate('N/A') }}</p>
                        <p>{{ $order->shipping_address->city ?? translate('N/A') }}, {{ $order->shipping_address->state ?? translate('N/A') }}</p>
                        <p>{{ $order->shipping_address->country ?? translate('N/A') }} - {{ $order->shipping_address->postal_code ?? translate('N/A') }}</p>
                        <p><strong>{{ translate('Phone') }}:</strong> {{ $order->shipping_address->phone ?? translate('N/A') }}</p>
                    @else
                        <p>{{ translate('No shipping address provided.') }}</p>
                    @endif
                </div>
            </div>

            <hr>

            <h6>{{ translate('Products') }}</h6>
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ translate('Product') }}</th>
                        <th>{{ translate('Quantity') }}</th>
                        <th>{{ translate('Unit Price') }}</th>
                        <th>{{ translate('Total') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->orderDetails as $key => $orderDetail)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>
                                @if ($orderDetail->product)
                                    {{ $orderDetail->product->getTranslation('name') }}
                                @else
                                    {{ translate('Product Not Found') }}
                                @endif
                            </td>
                            <td>{{ $orderDetail->quantity }}</td>
                            <td>{{ format_price($orderDetail->price) }}</td>
                            <td>{{ format_price($orderDetail->total) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="text-right mt-4">
                <a href="{{ route('wholesale_orders.index') }}" class="btn btn-info">{{ translate('Back to Wholesale Orders') }}</a>
                {{-- এখানে প্রিন্ট বা অন্যান্য অ্যাকশনের জন্য বাটন যোগ করা যেতে পারে --}}
            </div>
        </div>
    </div>
@endsection