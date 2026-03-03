
@extends('admin.layouts.app')

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">

    <!-- Back Button -->
    <div class="mb-3">
        <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-secondary btn-sm">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to User Details
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fa-solid fa-user-pen me-2"></i>Edit User — {{ $user->name }}</h5>
                </div>
                <div class="card-body">

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $user->name) }}"
                                required
                            >
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $user->email) }}"
                                required
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Ship to Different Address Permission -->
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    id="allow_different_shipping"
                                    name="allow_different_shipping"
                                    value="1"
                                    {{ old('allow_different_shipping', $user->allow_different_shipping) ? 'checked' : '' }}
                                >
                                <label class="form-check-label" for="allow_different_shipping">
                                    Allow shipping to a different address
                                </label>
                            </div>
                            <small class="text-muted d-block mt-1">
                                When enabled, this user can ship orders to an address different from their billing address.
                                Leave off (default) to enforce billing = shipping for fraud prevention.
                            </small>
                        </div>

                        <!-- Shipping Address -->
                        <hr>
                        <h6 class="mb-3 text-muted">
                            <i class="fa-solid fa-location-dot me-1"></i> Shipping Address
                        </h6>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="shipping_address_1" class="form-label">Street address</label>
                                <input
                                    type="text"
                                    id="shipping_address_1"
                                    name="shipping_address_1"
                                    class="form-control @error('shipping_address_1') is-invalid @enderror"
                                    placeholder="House number and street name"
                                    value="{{ old('shipping_address_1', $user->shipping_address_1) }}"
                                >
                                @error('shipping_address_1')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="shipping_address_2" class="form-label">Apartment, suite, unit, etc. <span class="text-muted">(optional)</span></label>
                                <input
                                    type="text"
                                    id="shipping_address_2"
                                    name="shipping_address_2"
                                    class="form-control @error('shipping_address_2') is-invalid @enderror"
                                    placeholder="Apartment, suite, unit, etc. (optional)"
                                    value="{{ old('shipping_address_2', $user->shipping_address_2) }}"
                                >
                                @error('shipping_address_2')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="shipping_city" class="form-label">Town / City</label>
                                <input
                                    type="text"
                                    id="shipping_city"
                                    name="shipping_city"
                                    class="form-control @error('shipping_city') is-invalid @enderror"
                                    value="{{ old('shipping_city', $user->shipping_city) }}"
                                >
                                @error('shipping_city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="shipping_postcode" class="form-label">Postcode / ZIP</label>
                                <input
                                    type="text"
                                    id="shipping_postcode"
                                    name="shipping_postcode"
                                    class="form-control @error('shipping_postcode') is-invalid @enderror"
                                    value="{{ old('shipping_postcode', $user->shipping_postcode) }}"
                                >
                                @error('shipping_postcode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="shipping_country" class="form-label">Country / Region</label>
                                <select
                                    id="shipping_country"
                                    name="shipping_country"
                                    class="form-select @error('shipping_country') is-invalid @enderror"
                                    data-selected-country="{{ old('shipping_country', $user->shipping_country) }}"
                                >
                                    <option value="">Select a Country / Region</option>
                                </select>
                                @error('shipping_country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="shipping_state" class="form-label">State / County</label>
                                <select
                                    id="shipping_state"
                                    name="shipping_state"
                                    class="form-select @error('shipping_state') is-invalid @enderror"
                                    data-selected-state="{{ old('shipping_state', $user->shipping_state) }}"
                                >
                                    <option value="">Select a State / County</option>
                                </select>
                                @error('shipping_state')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr>

                        <!-- Reset Password Section -->
                        <h6 class="mb-3 text-muted">
                            <i class="fa-solid fa-key me-1"></i> Reset Password
                            <small class="d-block fw-normal mt-1" style="font-size:0.82rem;">Leave blank to keep the current password unchanged.</small>
                        </h6>

                        <!-- New Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <div class="input-group">
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Enter new password"
                                    autocomplete="new-password"
                                >
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password', this)">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted">Minimum 8 characters.</small>
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                            <div class="input-group">
                                <input
                                    type="password"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    class="form-control"
                                    placeholder="Confirm new password"
                                    autocomplete="new-password"
                                >
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation', this)">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Save Changes
                            </button>
                            <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
    function togglePassword(fieldId, btn) {
        const field = document.getElementById(fieldId);
        const icon  = btn.querySelector('i');
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }

    // Populate shipping country/state dropdowns from countries.json (same source as checkout)
    document.addEventListener('DOMContentLoaded', function () {
        const countrySelect = document.getElementById('shipping_country');
        const stateSelect   = document.getElementById('shipping_state');

        if (!countrySelect || !stateSelect) {
            return;
        }

        let countriesData = [];
        const selectedCountry = countrySelect.getAttribute('data-selected-country') || '';
        const selectedState   = stateSelect.getAttribute('data-selected-state') || '';

        function populateStates(countryCode, selectedStateCode) {
            let states = [];
            countriesData.forEach(function (country) {
                if (country.iso2 === countryCode) {
                    states = country.states || [];
                }
            });

            let stateOptions = '<option value=\"\">Select a state / county…</option>';
            states.forEach(function (state) {
                const selectedAttr = state.state_code === selectedStateCode ? ' selected' : '';
                stateOptions += '<option value=\"' + state.state_code + '\"' + selectedAttr + '>' + state.name + '</option>';
            });
            stateSelect.innerHTML = stateOptions;
        }

        fetch('/public/countries.json')
            .then(function (response) { return response.json(); })
            .then(function (data) {
                countriesData = data;
                let countryOptions = '<option value=\"\">Select a country / region…</option>';
                data.forEach(function (country) {
                    const selectedAttr = country.iso2 === selectedCountry ? ' selected' : '';
                    countryOptions += '<option value=\"' + country.iso2 + '\"' + selectedAttr + '>' + country.name + '</option>';
                });
                countrySelect.innerHTML = countryOptions;

                if (selectedCountry) {
                    populateStates(selectedCountry, selectedState);
                }
            })
            .catch(function () {
                // silently fail if file is missing
            });

        countrySelect.addEventListener('change', function () {
            populateStates(this.value, '');
        });
    });
</script>
@endpush

