<script src="{{ asset('vendor/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/froiden-helper/helper.js') }}"></script>
<script src="{{ asset('vendor/jquery/dropzone.min.js') }}"></script>
<script>
    $('body').on('click', '.languagePackPublish', function() {

        console.log('languagePackPublish');
        var languageCode = $(this).data('language-code');

        var isRepublish = $(this).data('republish');

        var alertMessage = isRepublish ? `@lang('languagepack::app.republishConfirm')` : `@lang('languagepack::app.publishConfirm')`;

        Swal.fire({
            title: "@lang('messages.sweetAlertTitle')",
            text: alertMessage,
            icon: 'warning',
            showCancelButton: true,
            focusConfirm: false,
            confirmButtonText: "@lang('app.yes')",
            cancelButtonText: "@lang('app.cancel')",
            customClass: {
                confirmButton: 'bg-red-500 text-white hover:bg-red-600 hover:text-white rounded-md p-2 mr-3',
                cancelButton: 'bg-gray-500 text-white hover:bg-gray-600 hover:text-white rounded-md p-2'
            },
            showClass: {
                popup: 'swal2-noanimation',
                backdrop: 'swal2-noanimation'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {

                var url = "{{ route('language-pack.publish') }}";

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                    url: url,
                    data: {
                        '_token': token,
                        'languageCode': languageCode,
                        'isRepublish': isRepublish,
                    },
                    blockUI: true,
                    success: function(response) {
                        window.location.reload();
                    }
                });
            }
        });
    });

    $('body').on('click', '#languagePackPublishAll', function() {

        var alertMessage = `@lang('languagepack::app.publishAllConfirm')`;

        Swal.fire({
            title: "@lang('messages.sweetAlertTitle')",
            text: alertMessage,
            icon: 'warning',
            showCancelButton: true,
            focusConfirm: false,
            confirmButtonText: "@lang('app.yes')",
            cancelButtonText: "@lang('app.cancel')",
            customClass: {
                    confirmButton: 'bg-red-500 text-white hover:bg-red-600 hover:text-white rounded-md p-2 mr-3',
                    cancelButton: 'bg-gray-500 text-white hover:bg-gray-600 hover:text-white rounded-md p-2'
                },
                showClass: {
                    popup: 'swal2-noanimation',
                    backdrop: 'swal2-noanimation'
                },
                buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {

                var url = "{{ route('language-pack.publish-all') }}";

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                    url: url,
                    data: {
                        '_token': token,
                    },
                    blockUI: true,
                    success: function(response) {
                        window.location.reload();
                    }
                });
            }
        });
    });
</script>
