@extends('mobile.layouts.app')

@section('content')
<section id="category-page">
    <div class="container">
        <div class="category-page-wrapper mt-32">
            <h1 class="d-none">Category Page</h1>
            <h2 class="d-none">Category</h2>
            <h3 class="d-none">Hidden</h3>
            @foreach ($products as $product)
                <div class="category-page-deatils">
                    <a href="clothes-screen.html">
                        @if ($product->picture == null)
                            <div class="category-img-sec">
                                <img src="{{ asset('assets/images/category-page/category-1.png') }}" alt="category-img" class="img-fluid w-100"> 
                            </div>
                        @else
                            <div class="category-img-sec">
                                <img src="{{ asset('images/products/'.$product->picture) }}" alt="category-img" class="img-fluid w-100"> 
                            </div>
                        @endif
                        <div class="category-content-sec">
                            <h4>{{ $product->name }}</h4>
                            <h5>{{ $product->current_stock }}</h5>
                            <a href="javascript:void(0)" class="button-add" onclick="ModalAddCart('{{ $product->id }}', '{{ route('mobile.modal-add-product', $product->id) }}')">
                                <img src="{{ asset('assets/svg/plus-icon.svg') }}" alt="plus-icon">
                            </a>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
<div id="modalContainer"></div>

@endsection
@push('script')
    <script>
        // Event create by Modal
        function ModalAddCart(productId, url = '/modal-add-cart') {
            var getTarget = `#modal-add-to-cart-${productId}`;
            $.ajax({
                url: url,
                type: 'GET',
                success: function(data) {
                    $('#modalContainer').html(data);
                    $(`${getTarget}`).modal('show');
                    $(`${getTarget}`).on('shown.bs.modal', function () {
                        // Fungsi untuk menangani penambahan nilai ketika tombol + ditekan
                        $("#btn-add").on("click", function() {
                            var input = $(this).siblings("input[type='number']");
                            var value = parseInt(input.val());
                            var currentStock = parseInt($("#current-stock-" + productId).text());

                            if (value < currentStock) {
                                input.val(value + 1);
                            } else {
                                alert("Stock tidak cukup");
                            }
                        });

                        // Fungsi untuk menangani pengurangan nilai ketika tombol - ditekan
                        $("#btn-min").on("click", function() {
                            var input = $(this).siblings("input[type='number']");
                            var value = parseInt(input.val());
                            if (value > 0) {
                                input.val(value - 1);
                            }
                        });

                        // Memastikan nilai input tidak kurang dari 0
                        $(".qty-add").on("change", function() {
                            if ($(this).val() < 0) {
                                $(this).val(0);
                            } else {
                                var currentStock = parseInt($("#current-stock-" + productId).text());
                                if (parseInt($(this).val()) > currentStock) {
                                    alert("Stock tidak cukup");
                                    $(this).val(currentStock);
                                }
                            }
                        });

                        $('.child-checkbox').each(function() {
                            const isOptional = $(this).data('status-optional');
                            const maxChoose = $(this).data('choose');
                            const parentId = $(this).data('parent-id');
                            const name = $(this).data('name');

                            if (!isOptional) {
                                // Wajib dipilih
                                const checkboxes = $(`.child-checkbox[data-parent-id="${parentId}"]`);
                                const checkedCount = checkboxes.filter(':checked').length;

                                if (checkedCount < maxChoose) {
                                    checkboxes.slice(0, maxChoose).prop('checked', false);
                                } else {
                                    checkboxes.slice(maxChoose).prop('checked', false);
                                }
                            }
                        });

                        // Event handler untuk mengontrol checkbox berdasarkan pilihan maksimal
                        $('.child-checkbox').on('change', function() {
                            const parentId = $(this).data('parent-id');
                            const name = $(this).data('name');
                            const maxChoose = $(this).data('choose');
                            const checkboxes = $(`.child-checkbox[data-parent-id="${parentId}"]`);
                            const checkedCount = checkboxes.filter(':checked').length;

                            if (checkedCount > maxChoose) {
                                $(this).prop('checked', false);
                                alert(`Anda hanya dapat memilih maksimal ${maxChoose} addons.`);
                            }
                        });

                        $('#addToCartButton').on('click', function() {
                            let isValid = true;
                            let validationMessage = "";

                            // Loop through each parent addon to check if the selected addons are valid
                            $('.parent-addons').each(function() {
                                const parentId = $(this).data('parent-id');
                                const name = $(this).data('name');
                                const statusOptional = $(this).data('status-optional');
                                const maxChoose = $(this).data('choose');
                                const checkboxes = $(`.child-checkbox[data-parent-id="${parentId}"]`);
                                const checkedCount = checkboxes.filter(':checked').length;

                                if (!statusOptional && checkedCount !== maxChoose) {
                                    isValid = false;
                                    validationMessage += `Anda harus memilih ${maxChoose} addon ${name}.`;
                                }
                            });

                            if (!isValid) {
                                alert(validationMessage);
                                return;
                            }

                            // Ambil semua checkbox yang tercentang
                            const selectedAddons = $('.child-checkbox:checked').map(function() {
                                return {
                                    id: $(this).val(),
                                    name: $(this).data('name'),
                                    parentId: $(this).data('parent-id'),
                                    statusOptional: $(this).data('status-optional'),
                                    choose: $(this).data('choose'),
                                    price: $(this).data('price')
                                };
                            }).get();

                            const quantity = $('#qty-add').val();
                            const route = $(this).data('route');
                            const token = $(this).data('token');

                            addToCart(productId, selectedAddons, quantity, route, token);
                        });
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Failed to load Product: ', error);
                }
            });
        }

        // Add Product To Cart
        function addToCart(productId, addons, quantity, url, token) {
            $.ajax({
                url: url,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    "_token": token,
                    "product_id":productId,
                    "addons":addons,
                    "quantity":quantity,
                },
                success: function(response) {
                    window.location.reload();
                    console.log(response);
                },
                error: function(xhr, status, error) {
                    console.error('Failed to load Product: ', error);
                }
            });
        }

    </script>
@endpush
