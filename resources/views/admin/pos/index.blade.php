<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Vmond Truck POS</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo/logo-vmond-head.png') }}"/>
    <link href="{{ asset('layouts/vertical-dark-menu/css/light/loader.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('layouts/vertical-dark-menu/css/dark/loader.css') }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset('layouts/vertical-dark-menu/loader.js') }}"></script>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="{{ asset('src/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('layouts/vertical-dark-menu/css/light/plugins.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('layouts/vertical-dark-menu/css/dark/plugins.css') }}" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    <link rel="stylesheet" type="text/css" href="{{ asset('src/assets/css/light/elements/alert.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('src/assets/css/dark/elements/alert.css') }}">
    <link href="{{ asset('src/assets/css/light/components/tabs.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('src/assets/css/dark/components/tabs.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('src/assets/css/light/apps/contacts.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('src/assets/css/dark/apps/contacts.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('src/assets/css/dark/components/list-group.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('src/assets/css/custom-pos.css') }}" rel="stylesheet" type="text/css" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.css">
    <style>
        .custom-table th{
            padding: 10px;
            margin-bottom: 10px;
            color: white;
            background: #324c61;
            text-align: center;
        }
        .costum-form {
            border: 0;
            border-radius: 0;
            border-bottom: 1px solid #e2e2e2;
        }
        .table-cart > td{
            padding: 10px !important;
            vertical-align: middle;
        }
        .table-cart > td:last-child{
            text-align: right;
        }
        .table-cart > td:nth-child(2){
            text-align: center;
        }
        .custom-height{
            height: 46vh;
        }
        .list-group-item{
            border: 0;
            border-top: 1px solid #e2e2e2;
            border-bottom: 1px solid #e2e2e2;
            border-radius: 0 !important;
            padding: 10px 15px !important;
        }
        .my-product{
            height: 68vh;
            overflow-y: auto;
        }
        .my-action{
            height: 30vh;
        }
        .info-cart tr>td{
            width: 33.33333% !important;
        }

        .info-cart tr>td:last-child{
            text-align: right;
        }
        tr>td:first-child{
            border-right: 1px solid #dee2e600 !important;
        }
        tr>td:last-child{
            border-left: 1px solid #dee2e600 !important;
        }
        .card-img-top{
            border-radius: 5px !important;
            width: 80%;

        }
        .page-content{
            padding: 1rem 1.5rem 0.7rem 1.5rem !important;
        }

        .tab-style{
            max-height: 60vh;
            height: auto;
            overflow-y: auto;
        }
        .typeahead{
            border-radius: 0 !important;
            border-bottom-right-radius: 7px !important;
            border-top-right-radius: 7px !important;
        }
        .tt-hint {
            color: #999999;
        }
        .tt-menu {
            background-color: #FFFFFF;
            border: 1px solid rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
            margin-top: 5px;
            padding: 3px 0;
            width: 375px;
        }
        .tt-suggestion {
            font-size: 20px;  /* Set suggestion dropdown font size */
            padding: 3px 15px;
        }
        .tt-suggestion:hover {
            cursor: pointer;
            background-color: #0097CF;
            color: #FFFFFF;
        }
        .tt-suggestion p {
            margin: 0;
        }
        .twitter-typeahead{
            width: 80% !important;
        }
        .modal-header {
            border-bottom: 1px solid #1b2e4b;
        }
        .modal-footer {
            border-top: 1px solid #1b2e4b;
        }
        .jconfirm.jconfirm-black .jconfirm-box, .jconfirm.jconfirm-dark .jconfirm-box {
            background: #172631 !important;
        }
        .qty-add,input[type=number]::-webkit-inner-spin-button,
        .qty-add,input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .qty-add,input[type=number] {
            -moz-appearance: textfield;
        }
        .body.dark .list-group-item,.parent-addons {
            border:none !important;
            background-color: #1b2639 !important;
            color: #b8b8b8 !important;
        }
        .body.dark .list-group-item,.child-addons {
            border:none !important;
            background-color: transparent !important;
        }
        .addon-section {
            max-height: 17rem;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(115, 115, 115, 0.42) transparent;
        }
        .addon-section::-webkit-scrollbar {
            width: 8px;
        }

        .addon-section::-webkit-scrollbar-track {
            background: transparent;
        }

        .addon-section::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 10px;
            border: 2px solid transparent;
            background-clip: padding-box;
        }
        .bg-silver {
            background: #9b9b9bc4 !important
        }
        .table-cart .td-cart {
            border-bottom: 1px solid #060818 !important;
            padding-top: 10px !important;
            padding-bottom: 10px !important;
        }
    </style>
    <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->

</head>
<body class="layout-boxed">

    <!-- BEGIN LOADER -->
    <div id="load_screen"> <div class="loader"> <div class="loader-content">
        <div class="spinner-grow align-self-center"></div>
    </div></div></div>
    <!--  END LOADER -->


    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container p-0" id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>

        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content ms-0 mt-0">
            <div class="middle-content">
                <div class="simple-pill">
                    <!-- Navbar AREA -->
                    @include('admin.pos.navbar')
                    <!-- Navbar AREA -->

                    <!-- Content AREA -->
                    <div class="tab-content" id="pills-tabContent">

                        @include('admin.pos.content.transaction')
                        {{-- <div class="tab-pane fade" id="pills-price-icon" role="tabpanel" aria-labelledby="pills-price-icon-tab" tabindex="0">
                            test2
                        </div>
                        <div class="tab-pane fade" id="pills-stock-icon" role="tabpanel" aria-labelledby="pills-stock-icon-tab" tabindex="0">
                            test3
                        </div>
                        <div class="tab-pane fade" id="pills-others-icon" role="tabpanel" aria-labelledby="pills-others-icon-tab" tabindex="0">
                           test4
                        </div> --}}
                    </div>
                    <!-- Content AREA -->
                </div>
            </div>
        </div>
        <!--  END CONTENT AREA  -->
    </div>
    <!-- END MAIN CONTAINER -->

    <div id="modalContainer"></div>

    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="{{ asset('src/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('src/plugins/src/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('src/plugins/src/mousetrap/mousetrap.min.js') }}"></script>
    <script src="{{ asset('src/plugins/src/global/vendors.min.js') }}"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->
    <script src="{{ asset('src/plugins/src/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('src/assets/js/apps/contact.js') }}"></script>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <script src="{{ asset('src/assets/js/transaction.js') }}"></script>
    <script src="{{ asset('src/assets/js/typeahead.bundle.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.js"></script>
</body>
</html>
