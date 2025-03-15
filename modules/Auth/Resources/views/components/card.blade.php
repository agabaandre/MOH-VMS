<div class="row h-100vh align-aitems-center px-0 position-relative">
    <div class="col-lg-6 d-flex align-aitems-center">
        <div class="form-wrapper m-auto">
            <!-- Logo for all form factors -->
            <div class="text-center mb-4">
                <img class="brand_logo" src="{{ setting('site.logo_light', admin_asset('img/logo-light.png'), true) }}"
                    alt="" style="max-width: 200px; background-color: white; border-radius: 10px; padding: 5px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);">
            </div>
            <div>
                {{ $slot }}
            </div>
        </div>
    </div>
    <div class="col-lg-6 login-bg d-none d-lg-block overflow-hidden text-end py-2"
        style="background-image: url({{ setting('site.auth_banner', admin_asset('img/login-bg.png'), true) }});">
    </div>
</div>

<style>
    @media (max-width: 991px) {
        .form-wrapper {
            width: 100%;
            max-width: 100%;
            padding: 0 15px;
        }
        
        .row {
            margin: 0;
        }
        
        html, body {
            overflow-x: hidden;
        }
        
        .h-100vh {
            min-height: 100vh;
            height: auto;
        }
    }
</style>
