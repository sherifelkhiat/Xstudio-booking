<v-book-slots :bookingProduct="ssss" />

@pushOnce('scripts')
    <script type="text/x-template" id="v-book-slots-template">
        <div>
            <x-shop::form.control-group.label class="required">
                {{ $title  ?? trans('booking::app.shop.products.view.types.booking.slots.book-an-appointment') }}
            </x-shop::form.control-group.label>

            <div class="grid grid-cols-2 gap-x-4">
                <!-- Select Working Day -->
                <x-shop::form.control-group class="!mb-0">
                    <x-shop::form.control-group.label>
                        @lang('booking::app.shop.products.view.types.booking.slots.date')
                    </x-shop::form.control-group.label>

                    <x-shop::form.control-group.control
                        type="select"
                        class="py-4"
                        name="booking[date]"
                        rules="required"
                        v-model="selectedDate"
                        @change="getAvailableSlots"
                    >
                        <option value="">
                            @lang('booking::app.shop.products.view.types.booking.slots.select-date')
                        </option>

                        <option
                            v-for="date in workingDays"
                            :value="date"
                            v-text="date"
                        >
                        </option>
                    </x-shop::form.control-group.control>

                    <x-shop::form.control-group.error control-name="booking[date]" />
                </x-shop::form.control-group>

                <!-- Select Slots -->
                <x-shop::form.control-group class="!mb-0">
                    <x-shop::form.control-group.label>
                        @lang('booking::app.shop.products.view.types.booking.slots.title')
                    </x-shop::form.control-group.label>

                    <x-shop::form.control-group.control
                        type="select"
                        class="py-4"
                        name="booking[from]"
                        rules="required"
                        v-model="selectedSlot"
                        is-disabled="!slots.length"
                    >
                        <option value="">
                            @lang('booking::app.shop.products.view.types.booking.slots.select-slot')
                        </option>

                        <option
                            v-for="slot in slots"
                            :value="slot"
                            v-text="slot"
                        >
                        </option>
                    </x-shop::form.control-group.control>

                    <x-shop::form.control-group.error control-name="booking[from]" />
                </x-shop::form.control-group>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-book-slots', {
            template: '#v-book-slots-template',

            props: ['bookingProduct', 'title'],

            data() {
                return {
                    workingDays: [],
                    slots: [],
                    selectedDate: '',
                    selectedSlot: '',
                }
            },

            mounted() {
                this.getWorkingDays();
            },

            methods: {
                getWorkingDays() {
                    this.$axios.get(`{{ route('shop.xbooking.dates', '') }}`)
                        .then((response) => {
                            this.workingDays = response.data;
                        })
                        .catch(error => {
                            console.error("Error fetching working days:", error);
                        });
                },

                getAvailableSlots() {
                    if (!this.selectedDate) {
                        this.slots = [];
                        return;
                    }

                    this.$axios.get(`{{ route('shop.xbooking.times', '') }}/${this.selectedDate}`)
                        .then((response) => {
                            this.slots = response.data;
                            this.selectedSlot = '';
                        })
                        .catch(error => {
                            console.error("Error fetching available slots:", error);
                        });
                }
            }
        });
    </script>
@endpushOnce
