<script src="{{ asset('assets/js/jquery-min-3.6.0.js') }}"></script>
<script src="{{ asset('assets/js/slick.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/modal.js') }}"></script>
<script src="{{ asset('assets/js/custom.js') }}"></script>
{{-- Toastify --}}
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

@if(session()->has('success'))
    <script>
            Toastify({
                text: "{{ session()->get('success') }}",
                close: true,
                gravity: "top", // `top` or `bottom`
                position: "center", // `left`, `center` or `center`
                stopOnFocus: true, // Prevents dismissing of toast on hover
                style: {
                    background: "#D5F3E9",
                    color: "#1f7556"
                },
                duration: 3000
            }).showToast();
    </script>
@endif

@if(session()->has('warning'))
<script>
        Toastify({
            text: "{{ session()->get('warning') }}",
            close: true,
            gravity: "top", // `top` or `bottom`
            position: "center", // `left`, `center` or `center`
            stopOnFocus: true, // Prevents dismissing of toast on hover
            style: {
                background: "#FBEFDB",
                color: "#916c2e"
            },
            duration: 3000
        }).showToast();
</script>
@endif

@if(session()->has('failed'))
<script>
    Toastify({
        text: "🚨 {{ session()->get('failed') }}",
        close: true,
        gravity: "top", // `top` or `bottom`
        position: "center", // `left`, `center` or `center`
        stopOnFocus: true, // Prevents dismissing of toast on hover
        theme: "dark",
        style: {
            background: "#fde1e1",
            color: "#924040"
        },
        duration: 4000
    }).showToast();
</script>

<script>
    function phoneMask() {
        var num = $(this).val().replace(/\D/g,'');
        $(this).val(num.substring(0,13));
    }
    $('[type="tel"]').keyup(phoneMask);
</script>

@endif
@stack('script')
