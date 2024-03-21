{{--
    ? This is the page that shows the order confirmation
    ? It is used to display the products in the order and allows the customer to:
    ?   Continue shopping
--}}

<x-app-layout>

    <!-- Header Container -->
    <x-slot name="header">
        Order ID: {{ $order->id }}
        <div class="justify-center mt-2">
            @switch($order->status)
                @case('Placed')
                    <p class="text-neutral-500 text-lg font-lexend">Your order has been placed.</p>
                @break

                @case('Processing')
                    <p class="text-neutral-500 text-lg font-lexend">Your order is being processed.</p>
                @break

                @case('Dispatched')
                    <p class="text-neutral-500 text-lg font-lexend">Your order has been dispatched.</p>
                @break

                @case('Delivered')
                    <p class="text-neutral-500 text-lg font-lexend">Your order has been delivered.</p>
                @break

                @case('Returned')
                    <p class="text-neutral-500 text-lg font-lexend">Your order has been returned.</p>
                @break

                @case('Cancelled')
                    <p class="text-neutral-500 text-lg font-lexend">Your order has been cancelled.</p>
                @break
            @endswitch
        </div>
    </x-slot>

    <!-- Main Container -->
    <div class="grid grid-rows-2 items-center mx-10 md:flex-row md:justify-center gap-y-5">
        <!-- Order items container -->
        <div class="w-full max-w-[600px] gap-5 md:w-[1037px] p-5 border-2 border-neutral-50 rounded-lg">
            {{-- * Loops through all basket items in the user's basket --}}
            @foreach ($order->items as $item)
                <x-order-item>
                    <x-slot name="productImage">
                        {{ $item->product->image }}
                    </x-slot>
                    <x-slot name="productName">
                        {{ $item->product->name }}
                    </x-slot>
                    <x-slot name="sellingPrice">
                        {{ $item->product->selling_price }}
                    </x-slot>
                    <x-slot name="quantity">
                        {{ $item->quantity }}
                    </x-slot>
                    <x-slot name="size">
                        {{ $item->variation->size }}
                    </x-slot>
                </x-order-item>
            @endforeach
        </div>
        <div class="w-full max-w-[600px] p-5 border-2 border-neutral-50 rounded-lg">
            <!-- Total -->
            <div class="py-2 mt-6 border-t border-b border-primary-300 gap-y-5">
                <div class="flex justify-between items-center">
                    <p class="text-base sm:text-lg font-medium text-gray-900">Number of items</p>
                    <p class="font-semibold text-base sm:text-lg text-gray-900">{{ $order->getTotalItems() }}</p>
                </div>
                <div class="flex justify-between items-center">
                    <p class="text-base sm:text-lg font-medium text-gray-900">Order Status</p>
                    <p class="font-semibold text-base sm:text-lg text-gray-900">{{ $order->status }}</p>
                </div>
                <div class="flex justify-between items-center">
                    <p class="text-base sm:text-lg font-medium text-gray-900">Subtotal</p>
                    <p class="font-semibold text-base sm:text-lg text-gray-900">£{{ $order->total + $order->discount_amount }}</p>
                </div>
                @if ($order->discount_amount > 0)
                    <div class="flex justify-between items-center">
                        <p class="text-base sm:text-lg font-medium text-gray-900">Discount</p>
                        <p class="font-semibold text-base sm:text-lg text-gray-900">-£{{ $order->discount_amount }}</p>
                    </div>
                @endif
            </div>
            <div class="flex justify-between items-center mt-6">
                <p class="text-lg sm:text-xl font-semibold text-gray-900">Total</p>
                <p class="text-lg sm:text-xl font-semibold text-gray-900">£{{ $order->total }}</p>
            </div>
            <div class = "grid grid-flow-col items-center justify-center mt-6">
                @if ($order->status === 'Placed' || $order->status === 'Processing')
                    <form action="{{ route('cancel-order', ['id' => $order->id]) }}" method="POST">
                        @csrf

                        <x-primary-button>
                            Cancel Order
                        </x-primary-button>
                    </form>
                @else
                    @switch($order->status)
                        @case('Dispatched')
                            <p class="text-lg font-medium text-gray-900">You cannot cancel this order as it has already been
                                dispatched.</p>
                        @break

                        @case('Delivered')
                            <form action="{{ route('return-order', ['id' => $order->id]) }}" method="POST">
                                @csrf
                                <x-primary-button>
                                    Return Order
                                </x-primary-button>
                            </form>
                        @break
                    @endswitch
                @endif
                <a href="{{ route('home') }}">
                    <x-secondary-button class="ml-5">
                        Continue Shopping
                    </x-secondary-button></a>
            </div>
        </div>
    </div>
</x-app-layout>
