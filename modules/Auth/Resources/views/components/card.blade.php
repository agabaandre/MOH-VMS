<div class="row h-100vh align-aitems-center px-0 position-relative">
    <div class="col-lg-6 d-flex align-aitems-center">
        <div class="form-wrapper m-auto">
            <div>
                {{ $slot }}
            </div>
        </div>
    </div>
    <div class="col-lg-6 login-bg d-none d-lg-block overflow-hidden text-end py-2"
        style="background-image: url({{ setting('site.auth_banner', admin_asset('img/login-bg.png'), true) }});">
    </div>
    <!-- Positioned logo at the split -->
    <div class="position-absolute" style="left: calc(49% - 100px); top: 20%; transform: translateY(-50%); z-index: 10;">
        <img class="brand_logo" src="{{ setting('site.logo_light', admin_asset('img/logo-light.png'), true) }}"
            alt="" style="max-width: 200px; background-color: white; border-radius: 10px; padding: 5px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);">
    </div>
</div>
