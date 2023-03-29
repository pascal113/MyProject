@extends('layouts.web')

@section('content')
    @component('components.blocks.page-hero', [
        'class' => 'block-page-hero--short',
        'image' => asset('images/page-hero-bgrs/find-a-wash.jpg'),
    ])@endcomponent
    <main class="doc-content">
        @include('components.blocks.order-summary', [ 'hideSummary' => true ])
        <div class="wrapper">
            <form id="membership-form" action="{{ route('checkout.memberships.details.store', [ 'index' => $index ]) }}" method="post">
                @csrf

                <section class="section">
                    <div class="
                        section-row
                        section-row--padded
                        section-row--center
                        section-row--sm
                    ">
                        @include('components.blocks.notifications')

                        <h2 class='section-title'>Club Details</h2>
                        <hr class="section-below">
                        <p class="page-intro">Please complete the following information for each club you are purchasing.</p>
                        <h3 class="section-subtitle section-subtitle--underline">{{ $cartItem->name }}</h3>

                        <div id="choose-wash-club-target">
                            <choose-wash-club-target
                                inline-template
                                ref="chooseWashClubTarget"
                                :is_giftable="@json($isGiftable)"
                                :is_gift="@json(((old('is_gift') ?? null) ? old('is_gift') === 'true' : null) ?? (($isGift ?? null) ? !!$isGift : null) ?? null)"
                                :is_modification_or_reactivation="@json((((old('is_modification_or_reactivation') ?? null) ? old('is_modification_or_reactivation') === 'true' : null) ?? old('membership_multi_id') ?? $membershipMultiId ?? null) ? true : null)"
                                membership_multi_id="{{ old('membership_multi_id') ?? $membershipMultiId ?? null }}"
                                vehicle_id="{!! old('vehicle_id') ?? $vehicleId ?? (count($availableVehicles) ? '' : 'new') !!}"
                            >
                                <div class="form-vehicle-selection">
                                    <div v-if="is_giftable" class="club-gift">
                                        <p><i class="checkout-icon checkout-icon--gift"></i></p>
                                        <p class="checkout-question">Is this club a gift?</p>
                                        <ul class="clean-list clear-margin-top">
                                            <li>
                                                <label for="is_gift-yes">
                                                    <input type="radio" v-model="state.is_gift" v-bind:value="true" id="is_gift-yes" name="is_gift" class="@error('is_gift') has-error @enderror">Yes
                                                </label>
                                            </li>
                                            <li>
                                                <label for="is_gift-no">
                                                    <input type="radio" v-model="state.is_gift" v-bind:value="false" id="is_gift-no" name="is_gift" class="@error('is_gift') has-error @enderror">No
                                                </label>
                                            </li>
                                        </ul>

                                        @error('is_gift')
                                            <span v-if="state.is_gift === @json(old('is_gift'))" class="error-text invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    @if (count($availableMemberships) || $isModificationBlocked)
                                        <div class="text-center centered-radio-group" v-bind:style="{ display: (!is_giftable || state.is_gift === false) ? 'block' : 'none' }">
                                            <p><i class="checkout-icon checkout-icon--membership"></i></p>
                                            <p class="checkout-question">Will this purchase establish a new club membership or modify an existing club membership? <i class="icon icon--helper icon--info" id="new-or-existing-tooltip-trigger"></i></p>
                                            <div id="new-or-existing-tooltip">
                                                To establish a new Brown Bear club membership choose "new." To upgrade, downgrade or reactivate an existing membership choose "modify existing."
                                            </div>
                                            <ul class="clean-list clear-margin">
                                                <li>
                                                    <label for="is_modification_or_reactivation-no" class="radio-group__radio weight-default">
                                                        <input type="radio" v-model="state.is_modification_or_reactivation" v-bind:value="false" id="is_modification_or_reactivation-no" name="is_modification_or_reactivation" class="@error('is_modification_or_reactivation') has-error @enderror">New Club Membership
                                                    </label>
                                                </li>
                                                <li class="weight-default">
                                                    <label for="is_modification_or_reactivation-yes" class="radio-group__radio weight-default">
                                                        <input type="radio" v-model="state.is_modification_or_reactivation" v-bind:value="true" id="is_modification_or_reactivation-yes" name="is_modification_or_reactivation" class="@error('is_modification_or_reactivation') has-error @enderror">Modify Existing Club Membership
                                                    </label>
                                                </li>
                                            </ul>

                                            @error('is_modification_or_reactivation')
                                                <span v-if="state.is_modification_or_reactivation === @json(old('is_modification_or_reactivation'))" class="error-text invalid-feedback" role="alert">
                                                <span class="error-text invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div v-if="!state.is_gift && state.is_modification_or_reactivation" class="text-center centered-radio-group">
                                            <hr />
                                            <p class="font-size--22">Select the club membership you wish to modify:</p>
                                            @if (!$isModificationBlocked)
                                                <ul class="clean-list clear-margin">
                                                    @foreach ($availableMemberships as $membership)
                                                        <li>
                                                            <label for="membership_multi_id-{{ $membership->wash_connect->membership->id ?? '' }}|{{ $membership->purchase->id ?? '' }}" class="radio-group__radio weight-default">
                                                                <input type="radio" v-model="state.membership_multi_id" v-bind:value="'{{ $membership->wash_connect->membership->id ?? '' }}|{{ $membership->purchase->id ?? '' }}'" id="membership_multi_id-{{ $membership->wash_connect->membership->id }}|{{ $membership->purchase->id ?? '' }}" name="membership_multi_id" class="@error('membership_multi_id') has-error @enderror">
                                                                    <strong>{{ $membership->vehicle->label ?? 'Unknown vehicle' }}</strong><br />
                                                                    <i>Status: {{ $membership->wash_connect->membership->status ?? 'n/a' }}, {{ $membership->is_expiration_in_the_past ? 'expired' : ($membership->club->is_recurring ?? null ? 'renews' : 'expires') }} {{ $membership->expires_at->format(config('format.date')) }}</i><br />
                                                                    {{ $membership->club->display_name_with_term ?? null }}<br />
                                                                    Membership Tag: {{ $membership->wash_connect->vehicle->rfid ?? '' }}
                                                            </label>
                                                        </li>
                                                    @endforeach
                                                </ul>

                                                @error('membership_multi_id')
                                                    <span class="error-text invalid-feedback" role="alert">
                                                    <span class="error-text invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            @else 
                                                @php      
                                                    $returnLinkUrl = route('my-account.index', ['expandedSection' => 'memberships']);                                       
                                                @endphp
                                                <div class="notifications notifications--warning">
                                                    <p>
                                                        Oops! You have too many memberships to load in our checkout
                                                        experience. To modify an existing club visit the Wash Club Memberships
                                                        section under My Account and select the club you wish to modify.                                                
                                                    </p>
                                                    <p>
                                                        <a href="{{ $returnLinkUrl }}" Wash Club Memberships >Visit My Account ></a>
                                                    </p>
                                                </div>
                                            @endif
                                        </div>
                                      
                                    @endif

                                    <div v-if="(!is_giftable || state.is_gift === false) && (state.is_modification_or_reactivation === false || @json($showAssociateVehicleControl))">
                                        <hr />
                                        <p class="font-size--22">
                                            @if (count($availableVehicles))
                                                Choose a vehicle to assign to this club.
                                            @else
                                                Add a vehicle to assign to this club.
                                            @endif
                                        </p>
                                        <div class="form-row">
                                            @if (count($availableVehicles))
                                                <div class="field-wrapper">
                                                    <label for="vehicle_id">Choose a Vehicle</label>
                                                    <select v-model="state.vehicle_id" id="vehicle_id" name="vehicle_id" class="has-placeholder @error('vehicle_id') has-error @enderror" data-test-id="vehicle:select" required>
                                                        <option value="">Choose One</option>
                                                        <option value="new">Add a New Vehicle</option>
                                                        @foreach ($availableVehicles as $vehicle)
                                                            <option value="{{ $vehicle->id }}"{{ (int)old('vehicle_id') === $vehicle->id ? ' selected="selected"' : '' }}>{{ $vehicle->label }}</option>
                                                        @endforeach
                                                    </select>

                                                    @error('vehicle_id')
                                                        <span v-if="state.vehicle_id === '{{ old('vehicle_id') }}'" class="error-text invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            @else
                                                <input v-model="state.vehicle_id" type="hidden" name="vehicle_id">
                                            @endif

                                            <div v-if="state.vehicle_id === 'new'">
                                                <div class="field-wrapper">
                                                    <label for="year">Vehicle Year</label>
                                                    <input id="year" name="year" type="text" value="{{ old('year', '') }}" class="@error('year') has-error @enderror" data-test-id="vehicle:year" required>

                                                    @error('year')
                                                        <span class="error-text invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="field-wrapper">
                                                    <label for="make">Vehicle Make</label>
                                                    <input id="make" name="make" type="text" value="{{ old('make', '') }}" class="@error('make') has-error @enderror" data-test-id="vehicle:make" required>

                                                    @error('make')
                                                        <span class="error-text invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="field-wrapper">
                                                    <label for="model">Vehicle Model</label>
                                                    <input id="model" name="model" type="text" value="{{ old('model', '') }}" class="@error('model') has-error @enderror" data-test-id="vehicle:model" required>

                                                    @error('model')
                                                        <span class="error-text invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="field-wrapper">
                                                    <label for="color_id">Vehicle Color</label>
                                                    <select id="color_id" name="color_id" class="has-placeholder @error('color_id') has-error @enderror" data-test-id="vehicle:color" required>
                                                        <option value="">Choose One</option>
                                                        @foreach ($colors as $color)
                                                            <option value="{{ $color->id }}"{{ (int)old('color_id') === $color->id ? ' selected="selected"' : '' }}>{{ $color->name }}</option>
                                                        @endforeach
                                                    </select>

                                                    @error('color_id')
                                                        <span class="error-text invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="field-wrapper">
                                                    <label for="license_plate_number">License Plate Number</label>
                                                    <input id="license_plate_number" name="license_plate_number" type="text" value="{{ old('license_plate_number', '') }}" class="@error('license_plate_number') has-error @enderror" data-test-id="vehicle:license" required>

                                                    @error('license_plate_number')
                                                        <span class="error-text invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="field-wrapper">
                                                    <label for="license_plate_state">Licensing State</label>
                                                    <select id="license_plate_state" name="license_plate_state" class="has-placeholder @error('license_plate_state') has-error @enderror" data-test-id="vehicle:state" required>
                                                        <option value="">Choose One</option>
                                                        @foreach ($states as $state)
                                                            @php
                                                                $selected = !empty(old('license_plate_state')) ? (int)old('license_plate_state') === $state->abbr : $state->abbr === 'WA';
                                                            @endphp
                                                            <option value="{{ $state->abbr }}" @if ($selected) selected="selected" @endif>{{ $state->name }}</option>
                                                        @endforeach
                                                    </select>

                                                    @error('license_plate_state')
                                                        <span class="error-text invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </choose-wash-club-target>
                        </div>
                    </div>
                </section>
                @include('components.blocks.footer-return', [
                    'returnLink' => [
                        'theme' => 'checkout',
                    ],
                    'button' => [
                        'text' => 'Continue',
                        'type' => 'submit',
                        'tags' => 'onclick="event.preventDefault(); $(\'#membership-form\').submit(); this.disabled = true; return false;"',
                        'testId' => 'vehicle:continue',
                    ],
                ])
            </form>
        </div>
    </main>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            if ($('#new-or-existing-tooltip-trigger')) {
                window.tippy('#new-or-existing-tooltip-trigger', {
                    content: document.querySelector('#new-or-existing-tooltip'),
                    placement: 'bottom',
                    trigger: 'mouseenter click',
                });
            }
        });

        Vue.component('choose-wash-club-target', {
            props: {
                vehicles: {
                    type: Array,
                    default: function() { return []; }
                },
                is_gift: {
                    type: Boolean,
                    default: undefined
                },
                is_giftable: {
                    type: Boolean,
                    default: true
                },
                is_modification_or_reactivation: {
                    type: Boolean,
                    default: false
                },
                membership_multi_id: {
                    type: String,
                    default: undefined
                },
                vehicle_id: {
                    type: String,
                    default: undefined
                }
            },

            data: function() {
                return {
                    state: {
                        is_gift: undefined,
                        is_modification_or_reactivation: undefined,
                        membership_multi_id: undefined,
                        vehicle_id: undefined
                    }
                }
            },

            mounted: function() {
                this.state.is_gift = this.is_gift;
                this.state.is_modification_or_reactivation = this.is_modification_or_reactivation || false;
                this.state.membership_multi_id = this.membership_multi_id || null;
                this.state.vehicle_id = this.vehicle_id || null;
            }
        });
    </script>
@endpush
