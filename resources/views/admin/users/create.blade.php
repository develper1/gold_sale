
@extends('admin.layouts.app')

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">

    <!-- Back Button -->
    <div class="mb-3">
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to Users
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fa-solid fa-user-plus me-2"></i>Add New User</h5>
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

                    <form action="{{ route('admin.users.store') }}" method="POST">
                        @csrf

                        <!-- Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}"
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
                                value="{{ old('email') }}"
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
                                    {{ old('allow_different_shipping') ? 'checked' : '' }}
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
                                    value="{{ old('shipping_address_1') }}"
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
                                    value="{{ old('shipping_address_2') }}"
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
                                    value="{{ old('shipping_city') }}"
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
                                    value="{{ old('shipping_postcode') }}"
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
                                    data-selected-country="{{ old('shipping_country') }}"
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
                                    data-selected-state="{{ old('shipping_state') }}"
                                >
                                    <option value="">Select a State / County</option>
                                </select>
                                @error('shipping_state')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr>

                        <!-- Password -->
                        <h6 class="mb-3 text-muted">
                            <i class="fa-solid fa-key me-1"></i> Password
                        </h6>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Enter password"
                                    autocomplete="new-password"
                                    required
                                >
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password', this)" title="Show/hide password">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                                <button class="btn btn-outline-secondary" type="button" id="generate-password-btn" title="Generate a strong password">
                                    <i class="fa-solid fa-arrows-rotate me-1"></i> Generate
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted">
                                Minimum 8 characters, with at least one uppercase letter, one lowercase letter, one number,
                                and one special character (@$!%*?&amp;) — or click Generate for a strong password.
                            </small>
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input
                                    type="password"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    class="form-control"
                                    placeholder="Confirm password"
                                    autocomplete="new-password"
                                    required
                                >
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation', this)" title="Show/hide password">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </div>
                            <small class="text-muted d-none" id="generated-password-note">
                                <i class="fa-solid fa-circle-info me-1"></i>
                                A password was generated and is currently shown in plain text above — make sure to copy it and share it with the user securely before leaving this page.
                            </small>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Create User
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Cancel</a>
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

    // Generates a password that always satisfies the server-side complexity rule
    // (upper + lower + digit + special char, min 8 chars).
    function generateStrongPassword(length) {
        const lower   = 'abcdefghijkmnpqrstuvwxyz';
        const upper   = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
        const digits  = '23456789';
        const symbols = '@$!%*?&';
        const all     = lower + upper + digits + symbols;

        function randomChar(chars) {
            const bytes = new Uint32Array(1);
            crypto.getRandomValues(bytes);
            return chars[bytes[0] % chars.length];
        }

        let chars = [randomChar(lower), randomChar(upper), randomChar(digits), randomChar(symbols)];
        for (let i = chars.length; i < length; i++) {
            chars.push(randomChar(all));
        }

        // Shuffle so the guaranteed character classes aren't always in the same positions.
        for (let i = chars.length - 1; i > 0; i--) {
            const bytes = new Uint32Array(1);
            crypto.getRandomValues(bytes);
            const j = bytes[0] % (i + 1);
            [chars[i], chars[j]] = [chars[j], chars[i]];
        }

        return chars.join('');
    }

    document.getElementById('generate-password-btn')?.addEventListener('click', function () {
        const password = generateStrongPassword(14);
        const passwordField = document.getElementById('password');
        const confirmField  = document.getElementById('password_confirmation');

        passwordField.value = password;
        confirmField.value  = password;

        // Reveal both fields as plain text so the admin can read/copy the generated value.
        passwordField.type = 'text';
        confirmField.type  = 'text';
        document.querySelectorAll('#password + button i, #password_confirmation + button i').forEach(function (icon) {
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        });

        document.getElementById('generated-password-note')?.classList.remove('d-none');
    });

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
