<link href="{{ admin_asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ admin_asset('vendor/metisMenu/metisMenu.min.css') }}" rel="stylesheet">
<link href="{{ nanopkg_asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ admin_asset('vendor/typicons/src/typicons.min.css') }}" rel="stylesheet">
<link href="{{ admin_asset('vendor/themify-icons/themify-icons.min.css') }}" rel="stylesheet">
<link href="{{ admin_asset('vendor/material_icons/materia_icons.css') }}" rel="stylesheet">
<link href="{{ admin_asset('vendor/emojionearea/dist/emojionearea.min.css') }}" rel="stylesheet">
@stack('lib-styles')
<link rel="stylesheet" href="{{ nanopkg_asset('vendor/highlight/highlight.min.css') }}">
<link href="{{ nanopkg_asset('vendor/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">
<link href="{{ nanopkg_asset('vendor/fontawesome-free-6.3.0-web/css/all.min.css') }}" rel="stylesheet">
<link href="{{ nanopkg_asset('vendor/bootstrap-icons/css/bootstrap-icons.min.css') }}" rel="stylesheet">
<link href="{{ nanopkg_asset('vendor/toastr/build/toastr.min.css') }}" rel="stylesheet">
<link href="{{ nanopkg_asset('css/arrow-hidden.min.css') }}" rel="stylesheet">
<link href="{{ nanopkg_asset('css/custom.min.css') }}" rel="stylesheet">

<!--Start Your Custom Style Now-->
<link href="{{ admin_asset('css/style-new.min.css') }}" rel="stylesheet">
<link href="{{ admin_asset('css/custom.min.css') }}" rel="stylesheet">
<link href="{{ admin_asset('css/extra.min.css') }}" rel="stylesheet">
<link href="{{ admin_asset('scss/customStyle.min.css') }}" rel="stylesheet">
<link href="{{ admin_asset('css/grapData.min.css') }}" rel="stylesheet">

<!-- Custom sidebar color override -->
<style>
  .sidebar {
    background-color: #0c0c0c !important;
  }

  /* Override sidebar header background color */
  .sidebar-header {
    background: #0c0c0c !important;
  }

  /* Improve visibility of menu items against dark background */
  .sidebar .metismenu a,
  .sidebar .nav-label_text,
  .sidebar .sidebar-header .sidebar-brand h2,
  .sidebar .sidebar_user_profile p {
    color: #ffffff9c !important;
  }

  /* Make SVG icons visible on dark background */
  .sidebar .metismenu svg path,
  .sidebar .metismenu svg g {
    fill: #ffffff9c !important;
  }

  /* Improve hover state visibility */
  .sidebar .metismenu a:hover {
    background-color: rgba(255, 255, 255, 0.1) !important;
  }

  /* Active menu item with yellow background and black text/icons */
  .sidebar .metismenu>li.mm-active>a {
    background-color: #ffcc00ea !important;
    color: #000000 !important;
  }

  /* Make SVG icons black when menu item is active */
  .sidebar .metismenu>li.mm-active>a svg path,
  .sidebar .metismenu>li.mm-active>a svg g {
    fill: #000000 !important;
  }

  /* Style for active submenu items - yellow bold text without background change */
  .sidebar .metismenu .nav-second-level .mm-active>a {
    background-color: transparent !important;
    color: #ffcc00 !important;
    font-weight: bold !important;
  }

  /* Make submenu items visible */
  .sidebar .metismenu ul a {
    color: #e0e0e0 !important;
  }

  /* Adjust sidebar user profile styling */
  .sidebar .sidebar_user_profile {
    background-color: rgba(255, 255, 255, 0.08) !important;
  }

  /* Change submenu dots color from green to yellow */
  .sidebar-nav ul li .nav-second-level li:before {
    background: #ffcc00 !important;
  }

  /* Style the logout button with Uganda flag red and white text */
  .sidebar-logout .btn-dark {
    background-color: #ce1126 !important;
    /* Uganda flag red */
    color: white !important;
    border-color: #ce1126 !important;
  }

  .sidebar-logout .btn-dark:hover {
    background-color: #b3101f !important;
    /* Slightly darker red for hover */
  }

  /* Custom themed table with yellow accents using Bootstrap approach */
  .table-themed {
    --bs-table-bg: #ffcc00;
    --bs-table-striped-bg: #edc000;
    --bs-table-striped-color: #000;
    --bs-table-active-bg: #e0b800;
    --bs-table-active-color: #000;
    --bs-table-hover-bg: #e6bd00;
    --bs-table-hover-color: #000;
    color: #000;
    border-color: #000000;
  }

  /* Override pagination active state with yellow */
  .page-item.active .page-link {
    background-color: #ffcc00 !important;
    border-color: #ffcc00 !important;
    color: #000 !important;
    /* Black text for better contrast on yellow */
  }

  .btn-success:hover {
    color: #ffcc00 !important;
    background-color: #000000 !important;
  }

  .btn-success {
    color: #000000 !important;
    background-color: #ffcc00 !important;
  }

  .select2-selection__rendered,
  input[type=date].form-control,
  input[type=email].form-control,
  input[type=file].form-control,
  input[type=number].form-control,
  input[type=password].form-control,
  input[type=text].form-control,
  select.form-control {
    border-left: 4px solid #ffcc00 !important;
  }
</style>

@stack('css')