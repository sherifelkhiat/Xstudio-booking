<x-admin::layouts>
    <!-- Title of the page -->
    <x-slot:title>
        Edit Working Day
    </x-slot>

    <!-- Category Edit Form -->
    <x-admin::form
        :action="route('xbooking.working_days.update', $workingDay->id)"
        method="post"
        enctype="multipart/form-data"
    >
        @method('PUT')
        @csrf

        <!-- General Section -->
        <div class="p-4 bg-white dark:bg-gray-900 rounded box-shadow">
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
                    :value="$workingDay->from"
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
                    :value="$workingDay->to"
                    :placeholder="'Enter to time'"
                />
            </x-admin::form.control-group>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end mt-6">
            <button type="submit" class="primary-button">
                Update
            </button>
        </div>
    </x-admin::form>
</x-admin::layouts>
