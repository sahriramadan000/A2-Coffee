<script src="{{ asset('src/plugins/src/global/vendors.min.js') }}"></script>
<script src="{{ asset('src/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('src/plugins/src/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('src/plugins/src/mousetrap/mousetrap.min.js') }}"></script>
<script src="{{ asset('src/plugins/src/waves/waves.min.js') }}"></script>
<script src="{{ asset('layouts/vertical-dark-menu/app.js') }}"></script>
<script src="{{ asset('src/assets/js/custom.js') }}"></script>

<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="{{ asset('src/plugins/src/table/datatable/datatables.js') }}"></script>
<!-- END PAGE LEVEL SCRIPTS -->

<script src="{{ asset('src/plugins/src/highlight/highlight.pack.js') }}"></script>
<!-- END GLOBAL MANDATORY STYLES -->

<!--  BEGIN CUSTOM SCRIPT FILE  -->
<script src="{{ asset('src/assets/js/scrollspyNav.js') }}"></script>
<script src="{{ asset('src/plugins/src/filepond/filepond.min.js') }}"></script>
<script src="{{ asset('src/plugins/src/filepond/FilePondPluginFileValidateType.min.js') }}"></script>
<script src="{{ asset('src/plugins/src/filepond/FilePondPluginImageExifOrientation.min.js') }}"></script>
<script src="{{ asset('src/plugins/src/filepond/FilePondPluginImagePreview.min.js') }}"></script>
<script src="{{ asset('src/plugins/src/filepond/FilePondPluginImageCrop.min.js') }}"></script>
<script src="{{ asset('src/plugins/src/filepond/FilePondPluginImageResize.min.js') }}"></script>
<script src="{{ asset('src/plugins/src/filepond/FilePondPluginImageTransform.min.js') }}"></script>
<script src="{{ asset('src/plugins/src/filepond/filepondPluginFileValidateSize.min.js') }}"></script>
<script src="{{ asset('src/plugins/src/sweetalerts2/sweetalerts2.min.js') }}"></script>
<script src="{{ asset('src/plugins/src/sweetalerts2/custom-sweetalert.js') }}"></script>
@stack('js-src')

@stack('js')
<script>
    // Event Update by Modal
    $(document).on('click', '#other-setting', function() {
        var getTarget = $(this).data('bs-target');

        $.get("{{ route('other-settings.modal') }}", function(data) {
            $('#modalContainerOther').html(data);
            $(`${getTarget}`).modal('show');
            $(`${getTarget}`).on('shown.bs.modal', function () {
                handleInput('layanan');

                $('#layanan').on('keyup', function() {
                    handleInput('layanan');
                });
            });

        });
    });

    function formatRupiah(angka) {
        var numberString = angka.toString().replace(/\D/g, '');
        var ribuan = numberString.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        return ribuan;
    }

    function handleInput(inputId) {
        var inputField = $('#' + inputId);
        var input = inputField.val().replace(/\D/g, '');
        var formattedInput = formatRupiah(input);
        inputField.val(formattedInput);
    }

    function generateKey(url) {
        Swal.fire({
            title: 'Generating Key',
            text: 'Please wait...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: url,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
            },
            success: function(data) {
                Swal.close();
                Swal.fire({
                    icon: 'success',
                    title: 'Key Generated Successfully',
                    html: `
                        <p>Your generated key is: <strong id="generated-key">${data.key}</strong></p>
                        <button id="copy-key" class="btn btn-primary">Copy Key</button>
                    `,
                    didOpen: () => {
                        const copyButton = document.getElementById('copy-key');
                        copyButton.addEventListener('click', () => {
                            const keyElement = document.getElementById('generated-key');
                            const key = keyElement.textContent;
                            navigator.clipboard.writeText(key).then(() => {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Key Copied',
                                    text: 'The key has been copied to your clipboard.',
                                });
                            }).catch((err) => {
                                console.error('Failed to copy key: ', err);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Copy Failed',
                                    text: 'There was an error copying the key to your clipboard.',
                                });
                            });
                        });
                    }
                });
            },
            error: function(xhr, status, error) {
                Swal.close();
                console.error('Failed to generate key: ', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Failed to Generate Key',
                    text: error,
                });
            }
        });
    }

</script>
