<x-admin::layouts>
    <!-- Title of the page -->
    <x-slot:title>
        @lang('admin::app.catalog.categories.create.title')
    </x-slot>

    <!-- Category Create Form -->
    <x-admin::form
        :action="route('xbooking.working_days.store')"
        enctype="multipart/form-data"
    >
        <!-- General Section -->
        <div class="p-4 bg-white dark:bg-gray-900 rounded box-shadow">
            <p class="mb-4 text-base text-gray-800 dark:text-white font-semibold">
                @lang('admin::app.catalog.categories.create.general')
            </p>

            <!-- Days -->
            <x-admin::form.control-group>
                <x-admin::form.control-group.label class="required">
                    Days
                </x-admin::form.control-group.label>
            
                <x-admin::form.control-group.control
                    type="select"
                    name="days"
                    :value="old('days')"
                    :placeholder="'Select Day'"
                >
                    <option value="">Select Day</option>
                    <option value="Sunday" {{ old('days') == 'Sunday' ? 'selected' : '' }}>Sunday</option>
                    <option value="Monday" {{ old('days') == 'Monday' ? 'selected' : '' }}>Monday</option>
                    <option value="Tuesday" {{ old('days') == 'Tuesday' ? 'selected' : '' }}>Tuesday</option>
                    <option value="Wednesday" {{ old('days') == 'Wednesday' ? 'selected' : '' }}>Wednesday</option>
                    <option value="Thursday" {{ old('days') == 'Thursday' ? 'selected' : '' }}>Thursday</option>
                    <option value="Friday" {{ old('days') == 'Friday' ? 'selected' : '' }}>Friday</option>
                    <option value="Saturday" {{ old('days') == 'Saturday' ? 'selected' : '' }}>Saturday</option>
                </x-admin::form.control-group.control>
            </x-admin::form.control-group>
            

            <!-- From -->
            <x-admin::form.control-group>
                <x-admin::form.control-group.label class="required">
                    From
                </x-admin::form.control-group.label>

                <x-admin::form.control-group.control
                    type="text"
                    name="from"
                    :value="old('from')"
                    :placeholder="'Enter from time'"
                />
            </x-admin::form.control-group>

            <!-- To -->
            <x-admin::form.control-group>
                <x-admin::form.control-group.label class="required">
                    To
                </x-admin::form.control-group.label>

                <x-admin::form.control-group.control
                    type="text"
                    name="to"
                    :value="old('to')"
                    :placeholder="'Enter to time'"
                />
            </x-admin::form.control-group>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end mt-6">
            <button type="submit" class="primary-button">
                Submit
            </button>
        </div>
    </x-admin::form>
</x-admin::layouts>
