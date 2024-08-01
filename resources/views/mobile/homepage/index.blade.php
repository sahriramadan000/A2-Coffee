@extends('mobile.layouts.app')

@section('content')
<section id="homescreen1-deatils-page" class="homescreen1-main">
    <div class="homescreen1-deatils-page-full">
        <div class="homescreen-third-sec">
            <div class="container">
                <div class="homescreen-third-wrapper">
                    <h3>&nbsp;</h3>
                    <p>&nbsp;</p>
                    <div class="home1-shop-now-btn mt-32">
                        &nbsp;
                    </div>
                </div>
            </div>
        </div>
        <div class="homescreen-second-sec mt-32">
            <div class="homescreen-second-wrapper">
                <div class="container">
                    <div class="homescreen-second-wrapper-top">
                        <div class="categories-first">
                            <h2 class="home1-txt3">Categories</h2>
                            <h3 class="d-none">Hidden</h3>
                        </div>
                        <div class="view-all-second">
                            <a href="#"><p class="view-all-txt">View all<span><img src="{{ asset('assets/svg/right-icon.svg') }}" alt="right-arrow"></span></p></a>
                        </div>
                    </div>
                </div>
                <div class="homescreen-second-wrapper-bottom mt-16">
                    <div class="homescreen-second-wrapper-slider">
                        @foreach ($tags as $tag)
                        <a href="{{ route('mobile.detail-category', ['category' => $tag->name]) }}">
                                <div class="category-slide">
                                    <img src="{{ asset('assets/images/category/category-1.jpg') }}" alt="category-img">
                                    <div class="category-slide-content">
                                        <h4>{{ $tag->name }}</h4>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="homescreen-eight-sec mt-32">
            <div class="homescreen-eight-wrapper">
                <div class="container">
                    <div class="homescreen-second-wrapper-top">
                        <div class="categories-first">
                            <h2 class="home1-txt3">Menu</h2>
                        </div>
                        <div class="view-all-second">
                            <a href="#"><p class="view-all-txt">View all<span><img src="{{ asset('assets/svg/right-icon.svg') }}" alt="right-arrow"></span></p></a>
                        </div>
                    </div>
                </div>
                <div class="homescreen-eight-wrapper-bottom mt-16">
                    <div class="homescreen-eight-bottom-full">
                        <ul class="nav nav-pills mb-3" id="homepage1-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active custom-home1-tab-btn" id="pills-all-tab" data-bs-toggle="pill" data-bs-target="#pills-all" type="button" role="tab" aria-selected="true">All</button>
                            </li>
                            @foreach ($tags as $tag)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link custom-home1-tab-btn" id="pills-{{ $tag->slug }}-tab" data-bs-toggle="pill" data-bs-target="#pills-{{ $tag->slug }}" type="button" role="tab" aria-selected="false">{{ $tag->name }}</button>
                                </li>
                            @endforeach
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-all" role="tabpanel" tabindex="0">
                                @foreach ($products as $product)
                                    <div class="container">
                                        <div class="homepage1-tab-details">
                                            <div class="homepage1-tab-details-wrapper">
                                                <div class="home1-tab-img">
                                                    <img src="{{ asset('assets/images/produk/product-1.png') }}" alt="watch-img">
                                                </div>
                                                <div class="home1-tab-details w-100">
                                                    <div class="home1-tab-details-full">
                                                        <p class="tab-home1-txt1">{{ $product->name }}</p>
                                                        <h3 class="tab-home1-txt2">Rp. {{ number_format($product->selling_price,0) }}</h3>
                                                        <div class="orange-star-tab">
                                                            <span>
                                                                <img src="{{ asset('assets/svg/orange-star18.svg') }}" alt="star-img">
                                                            </span>
                                                            <span class="tab-home1-txt3">4.8</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="home1-tab-favourite">
                                                    <div class="home-page-arrival-favourite">
                                                        <a href="javascript:void(0);" class="item-bookmark" tabindex="-1">
                                                            <img src="{{ asset('assets/svg/unfill-heart.svg') }}" alt="unfill-heart">
                                                        </a>
                                                    </div>
                                                    <div class="plus-bnt-home1">
                                                        <a href="javascript:void(0)" class="button-add" onclick="ModalAddToCart('{{ $product->id }}', '{{ route('mobile.modal-add-product', $product->id) }}')">
                                                            <img src="{{ asset('assets/svg/plus-icon.svg') }}" alt="plus-icon">
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @foreach ($tags as $tag)
                                <div class="tab-pane fade" id="pills-{{ $tag->slug }}" role="tabpanel" tabindex="0">
                                    @foreach ($productsByTag[$tag->slug] as $product)
                                        <div class="container">
                                            <div class="homepage1-tab-details">
                                                <div class="homepage1-tab-details-wrapper">
                                                    <div class="home1-tab-img">
                                                        <img src="{{ asset('assets/images/produk/product-1.png') }}" alt="watch-img">
                                                    </div>
                                                    <div class="home1-tab-details w-100">
                                                        <div class="home1-tab-details-full">
                                                            <p class="tab-home1-txt1">{{ $product->name }}</p>
                                                            <h3 class="tab-home1-txt2">Rp. {{ number_format($product->selling_price,0) }}</h3>
                                                            <div class="orange-star-tab">
                                                                <span>
                                                                    <img src="{{ asset('assets/svg/orange-star18.svg') }}" alt="star-img">
                                                                </span>
                                                                <span class="tab-home1-txt3">4.8</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="home1-tab-favourite">
                                                        <div class="home-page-arrival-favourite">
                                                            <a href="javascript:void(0)" class="item-bookmark" tabindex="-1">
                                                                <img src="{{ asset('assets/svg/unfill-heart.svg') }}" alt="unfill-heart">
                                                            </a>
                                                        </div>
                                                        <div class="plus-bnt-home1">
                                                            <a href="#!">
                                                                <img src="{{ asset('assets/svg/plus-icon.svg') }}" alt="plus-icon">
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div id="modalContainer"></div>

@endsection

@push('script')
    <script>
        // Event create by Modal
        function ModalAddToCart(productId, url = '/modal-add-cart') {
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
                    "product_id": productId,
                    "addons": addons,
                    "quantity": quantity,
                },
                success: function(response) {
                    // Get current query string
                    let currentQueryString = window.location.search;
                    // Redirect to homepage with the current query string
                    window.location.href = "{{ route('mobile.homepage') }}" + currentQueryString;
                    console.log(response);
                },
                error: function(xhr, status, error) {
                    console.error('Failed to load Product: ', error);
                }
            });
        }


    </script>
@endpush
