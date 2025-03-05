<x-app-layout>
    <div class="tile">

        <div class="row justify-content-center">
            <div class="col-md-8 col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('Profile') }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <div class="media m-1 ">
                                <div class="align-left p-1">
                                    <a href="#" class="profile-image">
                                        <img src="{{ auth()->user()->profile_photo_url }}"
                                            class="avatar avatar-xl rounded-circle img-border height-100"
                                            alt="card image">
                                    </a>
                                </div>
                                <div class="media-body ms-3 mt-1">
                                    <h3 class="font-large-1 white">
                                        {{ auth()->user()->name }}
                                        <span
                                            class="font-medium-1 white">({{ implode(',', auth()->user()->getRoleNames()->toArray()) }})</span>
                                    </h3>
                                    <div class="row justify-content-center">
                                        <table class="table table-borderless table-responsive">
                                            <tbody>
                                                <tr>
                                                    <th class="white">
                                                        <i class="fas fa-map-marker-alt"></i>
                                                    </th>
                                                    <td class="white text-start">
                                                        {{ auth()->user()->address ?? '---' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="white">
                                                        <i class="fas fa-phone"></i>
                                                    </th>
                                                    <td class="white text-start">
                                                        {{ auth()->user()->phone ?? '---' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="white">
                                                        <i class="fas fa-envelope"></i>
                                                    </th>
                                                    <td class="white text-start">
                                                        {{ auth()->user()->email ?? '---' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="white">
                                                        <i class="fa-solid fa-venus-mars"></i>
                                                    </th>
                                                    <td class="white text-start">
                                                        {{ auth()->user()->gender ?? '---' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="white">
                                                        <i class="fa-solid fa-user-clock"></i>
                                                    </th>
                                                    <td class="white text-start">
                                                        {{ auth()->user()->age ?? '---' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="white">
                                                        <i class="fa-solid fa-signature"></i>
                                                    </th>
                                                    <td class="white text-start">
                                                        @if(auth()->user()->signature_url)
                                                            <img src="{{ auth()->user()->signature_url }}" 
                                                                alt="Signature" width="200">
                                                        @else
                                                            {{ localize('No signature uploaded') }}
                                                        @endif
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
