<div class="modal fade" id="modal-add-to-cart-{{ $product->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 m-0">
                <div class="row my-3 px-3">
                    <div class="col-12 d-flex bd-highlight">
                        <div class="p-2 flex-shrink-1 bd-highlight w-50 text-center">
                            <img src="{{ $product->picture ? asset('images/products/'.$product->picture) : 'https://ui-avatars.com/api/?name='. str_replace(' ', '+', $product->name ?? '') }}" class="card-img-top" alt="">
                        </div>
                        <div class="p-2 w-100 bd-highlight d-flex flex-column justify-content-between">
                            <div class="">
                                <h5 class="mb-0 pb-0 d-flex align-items-center">
                                    {{ $product->name }}
                                    @if (($product->current_stock ?? 0) <= 0)
                                        <small class="ms-2 p-1 badge badge-light-danger" style="font-size: 10px;">Out of Stock</small>
                                    @endif
                                </h4>
                                <p class="m-0 p-0">
                                    <div class="d-flex align-content-center">
                                        Stock: <span id="current-stock-{{ $product->id }}">{{ $product->current_stock }}</span>
                                    </div>
                                </p>
                            </div>

                            {{-- <p style="text-align: justify !important;">{{ mb_strimwidth($product->description, 0, 150, "...") ?? '' }}</p> --}}

                            <div class="row justify-content-between align-items-center">
                                <div class="col-12 col-md-6">
                                    @php
                                        $priceForPercent = $product->selling_price ?? 0;
                                        $priceAfterDiscount = $priceForPercent;
                                        $isDiscounted = false;

                                        if ($product->is_discount) {
                                            if ($product->price_discount && $product->price_discount > 0) {
                                                $priceAfterDiscount = $product->price_discount;
                                                $isDiscounted = true;
                                            } elseif ($product->percent_discount && $product->percent_discount > 0 && $product->percent_discount <= 100) {
                                                $discount_price = $priceForPercent * ($product->percent_discount / 100);
                                                $priceAfterDiscount = $priceForPercent - $discount_price;
                                                $isDiscounted = true;
                                            }
                                        }
                                    @endphp

                                    <p class="m-0 p-0">
                                        @if($isDiscounted)
                                            <small class="text-danger text-left" style="font-size: 11px!">
                                                <del>Rp. {{ number_format($priceForPercent, 0, ',', '.') }}</del>
                                            </small>
                                            <br>
                                        @endif
                                        <span class="text-success fs-6">Rp. {{ number_format($priceAfterDiscount, 0, ',', '.') }}</span>
                                    </p>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="w-100 d-flex justify-content-center">
                                        <button class="btn btn-default text-white" id="btn-min" style="background: #0c0f1d !important; padding:5px 10px !important;border-color:#000;opacity:0.5;" {{ (($product->current_stock ?? 0) <= 0) ? 'disabled' : '' }}>-</button>
                                        <input type="number" name="qty" id="qty-add" class="qty-add form-control rounded-0 text-center p-1 bg-transparent border-0 w-50" value="1" {{ (($product->current_stock ?? 0) <= 0) ? 'disabled' : '' }}>
                                        <button class="btn btn-default text-white" id="btn-add" style="background: #0c0f1d !important; padding:5px 10px !important;border-color:#000;opacity:0.5;" {{ (($product->current_stock ?? 0) <= 0) ? 'disabled' : '' }}>+</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 px-0 mt-3 addon-section">
                        @forelse ($addons as $addon)
                            <ul class="list-group mb-2">
                                <li class="list-group-item parent-addons" data-parent-id="{{ $addon['addon']->id }}" data-status-optional="{{ $addon['addon']->status_optional }}" data-choose="{{ $addon['addon']->choose }}" data-name="{{ $addon['addon']->name }}">
                                    {{ $addon['addon']->name }}
                                    <span>
                                        ({{ ($addon['addon']->status_optional) ?  "Optional, Maksimal " . $addon['addon']->choose : "Pilih " . $addon['addon']->choose }})
                                    </span>
                                </li>

                                @if (!empty($addon['children']))
                                    <ul class="list-group ml-4">
                                        @foreach ($addon['children'] as $child)
                                            <li class="list-group-item child-addons d-flex justify-content-between align-items-center">
                                                <div class="d-flex flex-column justify-content-center">
                                                    {{ $child->name }}
                                                    <span>Rp.{{ number_format($child->price, 0, ',', '.') }}</span>
                                                </div>
                                                <input class="form-check-input child-checkbox" type="checkbox" value="{{ $child->id }}" id="form-check-{{ $child->id }}" data-parent-id="{{ $addon['addon']->id }}" data-status-optional="{{ $addon['addon']->status_optional }}" data-choose="{{ $addon['addon']->choose }}" data-price="{{ $child->price }}">
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </ul>
                        @empty
                            <p>&nbsp;</p>
                        @endforelse
                    </div>
                    <div class="col-12 d-flex bd-highlight mt-3">
                        <button type="button" class="btn btn-default {{ (($product->current_stock ?? 0) <= 0) ? 'bg-silver' : 'bg-primary' }} text-white w-100" {{ (($product->current_stock ?? 0) <= 0) ? 'disabled' : '' }} id="addToCartButton" data-route="{{ route('add-item') }}" data-token="{{ csrf_token() }}">Add to Cart</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
