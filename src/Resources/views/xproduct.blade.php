<v-book-slots :bookingProduct="ssss" />

@pushOnce('scripts')

    <script type="text/x-template" id="v-book-slots-template">
    <input type="hidden" id="productDuration" value="{{ $product->duration }}">
        <div>
            <x-shop::form.control-group.label class="required">
                {{ $title  ?? trans('xbooking::app.shop.bookSession') }}
            </x-shop::form.control-group.label>

            <div class="grid grid-cols-2 gap-x-4">
                <!-- Select Working Day -->
                <x-shop::form.control-group class="!mb-0">
                    <x-shop::form.control-group.label>
                        @lang('xbooking::app.shop.selectDate')
                    </x-shop::form.control-group.label>

                    <x-shop::form.control-group.control
                        type="select"
                        class="py-4"
                        name="booking[date]"
                        rules="required"
                        v-model="selectedDate"
                    >
                        <option value="">
                            @lang('xbooking::app.shop.selectDate')
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


                <!-- Select Cities -->
                <x-shop::form.control-group class="!mb-0">
                    <x-shop::form.control-group.label>
                        @lang('xbooking::app.shop.selectCity')
                    </x-shop::form.control-group.label>

                    <x-shop::form.control-group.control
                        type="select"
                        class="py-4"
                        name="booking[city]"
                        v-model="selectedCity"
                        rules="required"
                        @change="getAvailableSlots"
                    >
                        <option value="">
                            @lang('xbooking::app.shop.selectCity')
                        </option>

                        <option
                            v-for="(value, key) in cities"
                            :value="value"
                            v-text="key"
                        >
                        </option>
                    </x-shop::form.control-group.control>

                    <x-shop::form.control-group.error control-name="booking[city]" />
                </x-shop::form.control-group>

                <!-- Select Slots -->
                <x-shop::form.control-group class="!mb-0" v-if="selectedCity!=''">
                    <x-shop::form.control-group.label>
                        @lang('xbooking::app.shop.selectSlot') 
                    </x-shop::form.control-group.label>

                    <x-shop::form.control-group.control
                        type="select"
                        class="py-4"
                        name="booking[from]"
                        rules="required"
                        v-model="selectedSlot"
                        
                    >
                        <option value="">
                            @lang('xbooking::app.shop.selectSlot')
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
            <x-shop::form.control-group.label class="required" v-if="selectedCity!=''">
            {{ trans('xbooking::app.shop.cityExtraCost') }} @{{ extraCost }}
            </x-shop::form.control-group.label>
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
                    cities: [],
                    selectedDate: '',
                    selectedSlot: '',
                    selectedCity: '',
                    productDuration: '',
                    extraCost: ''
                }
            },

            mounted() {
                this.getWorkingDays();
                this.getCities();
                this.productDuration = document.getElementById('productDuration').value;
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

                getCities() {
                    this.$axios.get(`{{ route('shop.xbooking.cities', '') }}`)
                        .then((response) => {
                            this.cities = response.data;
                        })
                        .catch(error => {
                            console.error("Error fetching Cities:", error);
                        });
                },

                getAvailableSlots() {
                    if (!this.selectedDate) {
                        this.slots = [];
                        return;
                    }

                    const parts = this.selectedCity.split(':');
                    this.extraCost = parts[1];

                    console.log("Hii City:" + this.productDuration);
                    this.$axios.get(`{{ route('shop.xbooking.times', '') }}/${this.selectedDate}/?city=${this.selectedCity}&productDuration=${this.productDuration}`)
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
