
<div class="tab-pane fade show active" id="pills-transaction" role="tabpanel" aria-labelledby="pills-transaction-tab" tabindex="0">
    <div class="wrapper">
        <!--start page wrapper -->
		<div class="page-wrapper m-0">
            <div class="page-content">
                @include('admin.components.alert')
				<div class="row">
					<div class="col-12 col-md-6 mb-3">
                        <div class="card">
							<div class="card-body m-0 p-0">
                                <div class="row">
                                    <div class="fm-search col-lg-12 px-4 mt-3">
                                        <div class="mb-0">
                                            @can('coupon')
                                            <div class="input-group">
                                                {{-- <button class="btn btn-outline-secondary text-dark" type="button" style="font-size:14px;"><i class='bx bx-comment-detail me-0' style="font-size:16px;"></i> <small>Comments</small></button> --}}
                                                <button class="btn btn-success d-flex align-items-center" type="button" style="font-size:14px;" onclick="ModalAddCoupon('{{ route('modal-add-coupon') }}', '{{ route('update-cart-by-coupon') }}', '{{ csrf_token() }}')" data-bs-target="#modal-add-coupon"><i class='bx bx-tag me-1' style="font-size:1.2rem;"></i> <span id="coupon-info">Coupon</span></button>
                                                <input type="text" class="form-control" placeholder="" disabled>
                                            </div>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
								<!--end row-->
								<form action="{{ route('checkout-order', md5(strtotime("now"))) }}" method="POST" class="">
									@csrf
                                    <input type="hidden" name="coupon_id" value="">

									<div class="table-responsive mt-3 custom-height">
										<table class="table table-sm mb-0 custom-table">
											<thead>
												<tr>
													<th width="60%">Product</th>
													<th width="15%">Quantity</th>
													{{-- <th width="25%">Price</th> --}}
												</tr>
											</thead>
											<tbody id="cart-product">
												@forelse ($data_items as $key => $item)
                                                @php
                                                    $inputer    = $item->attributes['inputer'] ?? '';
                                                    $table      = $item->attributes['table'] ?? '';

                                                    $note = $item->attributes['note'] ?? '';

                                                    // If 'note' is an array, convert it to a string
                                                    if (is_array($note)) {
                                                        $note = implode(', ', $note); // You can choose the separator, here it’s a comma followed by a space
                                                    }
                                                @endphp

                                                    <tr class="table-cart text-white">
                                                        <td class="td-cart">
                                                            <div class="d-flex justify-content-between">
                                                                <div class="">
                                                                    <p class="p-0 m-0 text-white">
                                                                        {{ $item->name }}
                                                                    </p>
                                                                    <small>Note: {{ $note ?? '' }}</small>
                                                                </div>

                                                                @can('delete-product-in-cart')
                                                                <div class="">
                                                                    <a href="{{ route('delete-item', $key)}}" class="" style="border-bottom: 1px dashed red;">
                                                                        <i class='bx bx-trash font-14 text-danger'></i>
                                                                    </a>
                                                                </div>
                                                                @endcan
                                                            </div>
                                                        </td>
                                                        <td class="td-cart">
                                                            <a href="#!" type="button" onclick="ModalEditQtyCart('{{ route('modal-edit-qty-cart', $key) }}', '{{ $key }}', '{{ csrf_token() }}')" class="cursor-pointer" data-bs-target="#modal-add-customer" style="border-bottom: 1px dashed #bfbfbf; font-size:12px;">
                                                                <small id="data-qty" style="font-size: 12px;" class="text-white opacity-75">{{ $item->quantity }}</small>
                                                            </a>
                                                        </td>
                                                        <input type="hidden" name="qty[]" id="quantityInput" readonly class="min-width-40 flex-grow-0 border border-success text-success fs-4 fw-semibold form-control text-center qty" min="0" style="width: 15%"  value="{{ $item->quantity }}">
                                                        {{-- <td class="td-cart">Rp.{{ number_format($item->price, 0, ',', '.') }}</td> --}}
                                                    </tr>
                                                @empty
                                                    <tr class="table-cart">
                                                        <td colspan="3" class="text-center" style="border-bottom: 1px solid #060818d0">No products added</td>
                                                    </tr>
                                                    @php
                                                        $inputer = '';
                                                        $table = '';
                                                    @endphp
                                                @endforelse
											</tbody>
										</table>
									</div>
                                    <div class="row my-action align-items-end align-content-end">
                                        <div class="col-12">
                                            <table width="100%" class="table">
                                                <tbody class="info-cart">
                                                    <tr>
                                                        <td style="border-top: 1px solid #060818 !important;">
                                                            <span class="d-flex justify-content-between text-white opacity-75">
                                                                Customer:
                                                                <a href="#!" type="button" onclick="ModalAddCustomer('{{ route('modal-add-customer') }}', '{{ route('get-data-customers') }}', '{{ csrf_token() }}')" class="cursor-pointer" data-bs-target="#modal-add-customer" style="border-bottom: 1px dashed #bfbfbf; font-size:12px;">
                                                                    <small id="data-customer" style="font-size: 12px;" class="text-white opacity-75">No data</small>
                                                                </a>
                                                            </span>
                                                            <input type="hidden" name="customer_id" value="">
                                                        </td>
                                                        <td style="border-top: 1px solid #060818 !important; border-left: 1px solid #060818 !important;" colspan="2">
                                                            <div class="d-flex justify-content-between">
                                                                <small class="text-white opacity-75">Sub Total</small>
                                                                <small class="text-white opacity-75" id="subtotal-cart">Rp.{{ number_format($subtotal, 0, ',', '.') }}</small>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="border-top: 1px solid #060818 !important;">
                                                            <span style="font-size: 12px;" class="text-white opacity-75 d-flex justify-content-between">Tax({{ $other_setting->pb01 }}%): <small id="tax-cart" style="font-size: 12px;">Rp.{{ number_format($tax, 0, ',', '.') }}</small></span>
                                                        </td>
                                                        <td style="border-top: 1px solid #060818 !important; border-left: 1px solid #060818 !important;" colspan="2">
                                                            <div class="d-flex justify-content-between">
                                                                @can('discount')
                                                                <span style="font-size: 12px;" class="text-white opacity-75">Discount<small id="type-discount"></small></span>
                                                                <a href="#!" type="button" onclick="ModalAddDiscount('{{ route('modal-add-discount') }}', '{{ route('update-cart-by-discount') }}', '{{ csrf_token() }}')" class="cursor-pointer" data-bs-target="#modal-add-discount" style="border-bottom: 1px dashed #bfbfbf;font-size:14px;">
                                                                    <small id="discount-price" class="text-white opacity-75">Rp.0</small>
                                                                </a>
                                                                @endcan
                                                            </div>
                                                            <input type="hidden" name="type_discount" value="">
                                                            <input type="hidden" name="discount_price" value="">
                                                            <input type="hidden" name="discount_percent" value="">
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td style="border-top: 1px solid #060818 !important; border-bottom: 1px solid #060818 !important;">
                                                            <span style="font-size: 12px;" class="text-white opacity-75 d-flex justify-content-between">Service: <small id="service-cart" style="font-size: 12px;">Rp.{{ number_format($service, 0, ',', '.') }}</small></span>
                                                        </td>
                                                        <td class="bg-light-info fw-medium" style="border-top: 1px solid #060818 !important; border-bottom: 1px solid #060818 !important; border-left: 1px solid #060818 !important;" colspan="2">
                                                            <div class="d-flex justify-content-between">
                                                                <small class="text-white opacity-75">Total</small>
                                                                <small id="total-cart" class="text-white opacity-75">Rp.{{ number_format($total, 0, ',', '.') }}</small>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-12">
                                            <div class="btn-group w-100 p-3 pt-0" role="group" aria-label="Grouping Button">
                                                <input type="hidden" name="button" id="buttonValue" value="">

                                                @can('simpan-order')
                                                <button type="button" class="btn btn-lg btn-success fw-bold w-25 p-3" data-bs-toggle="modal" data-bs-target="#modalPayment" onclick="setButtonValue('simpan-order')">
                                                    <h6 class="mb-0 text-white">
                                                        SIMPAN ORDER
                                                    </h6>
                                                </button>
                                                @endcan

                                                <button type="button" class="btn btn-lg btn-warning fw-bold w-25 p-3" data-bs-toggle="modal" data-bs-target="#modalOpenBill" onclick="setButtonValue('simpan-bill')">
                                                    <h6 class="mb-0 text-white">
                                                        SIMPAN BILL
                                                    </h6>
                                                </button>
                                                {{-- <button type="button" class="btn btn-lg btn-primary fw-bold w-25 p-3" onclick="onHoldOrder('{{ route('on-hold-order') }}', '{{ csrf_token() }}')">
                                                    <h6 class="mb-0 text-white">
                                                        ON HOLD
                                                    </h6>
                                                </button> --}}
                                                @can('discounts')
                                                <button type="button" class="btn btn-lg btn-white fw-bold w-25 p-3" onclick="ModalAddDiscount('{{ route('modal-add-discount') }}', '{{ route('update-cart-by-discount') }}', '{{ csrf_token() }}')" data-bs-target="#modal-add-discount">
                                                    <h6 class="mb-0 text-dark">
                                                        DISCOUNT
                                                    </h6>
                                                </button>
                                                @endcan
                                                @can('voids')
                                                <button type="button" class="btn btn-lg btn-danger fw-bold w-25 p-3" onclick="voidCart('{{ route('void-cart') }}', '{{ csrf_token() }}')">
                                                    <h6 class="mb-0 text-white">
                                                        VOID
                                                    </h6>
                                                </button>
                                                @endcan
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="modalPayment" tabindex="-1" aria-labelledby="modalPaymentLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalPaymentLabel">PAYMENT</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                        <div class="form-group">
                                                            <h6 class="mb-3">Metode Payment</h6>
                                                            <select name="payment_method" id="payment_method" class="form-control form-control-sm">
                                                                <option selected disabled value="">Pilih Metode Pembayaran</option>
                                                                <option value="EDC BCA">EDC BCA</option>
                                                                <option value="EDC BRI">EDC BRI</option>
                                                                <option value="EDC BNI">EDC BNI</option>
                                                                <option value="EDC PANIN">EDC PANIN</option>
                                                                <option value="Qris BCA">Qris BCA</option>
                                                                <option value="Qris BRI">Qris BRI</option>
                                                                <option value="Qris BNI">Qris BNI</option>
                                                                <option value="Qris MANDIRI">Qris MANDIRI</option>
                                                                <option value="Cash">Cash</option>
                                                            </select>
                                                        </div>
                                                    <div class="form-group mt-2" id="cashInput" style="display: none;">
                                                        <label for="cash" class="form-label">Cash</label>
                                                        <input type="text" name="cash" value="{{ old('cash') }}" class="form-control form-control-sm" placeholder="Ex:50.000" id="cash" aria-describedby="cash">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">CLOSE</button>
                                                    <button type="submit" class="btn btn-primary" onclick="setButtonValue('simpan-order')">PAY</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                
                                    <!-- Modal Open Bill -->
                                    <div class="modal fade" id="modalOpenBill" tabindex="-1" aria-labelledby="modalOpenBillLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalOpenBillLabel">PAYMENT</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group mt-2">
                                                        <label for="inputer" class="form-label">Inputer</label>
                                                        <input type="text" name="inputer" value="{{ old('inputer', $inputer) }}" class="form-control form-control-sm" placeholder="Enter Inputer Name....." id="inputer" aria-describedby="inputer">
                                                    </div>
                                                    <div class="form-group mt-2">
                                                        <label class="form-label">Table</label>
                                                        <select class="form-select mr-sm-2 @error('table') is-invalid @enderror" id="table" name="table" style="width:100%">
                                                            @foreach ($tables as $tableOption)
                                                                <option value="{{ $tableOption->name }}" {{ $tableOption->name == old('table', $table) ? 'selected' : '' }}>
                                                                    {{ $tableOption->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                        <div class="form-group">
                                                            <h6 class="mt-3">Status Order</h6>
                                                            <select name="status_order" id="status_order" class="form-control form-control-sm">
                                                                <option selected value="Order Baru">Order Baru</option>
                                                                <option value="Order Tambahan">Order Tambahan</option>
                                                            </select>
                                                        </div>
                                                    <div class="form-group mb-3">
                                                        <label for="note">Note</label>
                                                        <textarea name="note" id="note" cols="30" rows="5" class="form-control" placeholder="Ex:note">{{ old('note') }}</textarea>
                
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">CLOSE</button>
                                                    <button type="submit" class="btn btn-primary" onclick="setButtonValue('simpan-bill')">SUBMIT</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
								</form>
							</div>
						</div>
                    </div>
					<div class="col-12 col-md-6">
                        <div class="card">
							<div class="card-body m-0 p-0">
                                <div class="row">
                                    <div class="fm-search col-lg-12 px-4 mt-3">
                                        <div class="mb-0">
                                            <di v class="input-group">
                                                {{-- <button class="btn btn-outline-info text-white d-flex align-items-center" type="button">Favorites</button>
                                                <button class="btn btn-outline-info text-white d-flex align-items-center" type="button">All Menu</button> --}}
                                                <input type="text" id="text-search" class="form-control barcode" placeholder="" disabled>
                                                <button class="btn btn-primary text-white d-flex align-items-center" type="button" onclick="ModalSearch('{{ route('modal-search-product') }}', '{{ route('search-product') }}', '{{ csrf_token() }}')"><i class='bx bx-search-alt me-0' style="font-size: 1.2rem !important;"></i></button>
                                            </di>
                                        </div>
                                    </div>
                                </div>
								<!--end row-->
								<div class="row mt-3">
                                    <div class="col-12">
                                        <ul class="list-group">
                                            <li class="list-group-item d-flex justify-content-start align-items-center gap-1 text-white" style="background-color: #060818 !important; border:none; border-radius:10px !important; opacity: 0.9;">
                                                <a href="#!" onclick="loadTags('{{ route('get-tag') }}');">Home</a> /
                                                <span id="tagNavigation"></span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

								<div class="row mt-3 px-3 pb-3 my-product align-content-start" id="productContainer"></div>
							</div>
						</div>
                    </div>
				</div>
				<!--end row-->
			</div>
		</div>
		<!--end page wrapper -->
	</div>
</div>

<script>
    const selectInput = document.getElementById('payment_method');
    const cashInput = document.getElementById('cashInput');

    selectInput.addEventListener('change', function() {
        if (selectInput.value === 'Cash') {
            cashInput.style.display = 'block';
        } else {
            cashInput.style.display = 'none';
        }
    });

</script>

<script>
    function setButtonValue(value) {
        document.getElementById('buttonValue').value = value;
    }
</script>

