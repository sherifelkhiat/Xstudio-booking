<x-admin::layouts>
    <!-- Title of the page -->
    <x-slot:title>
        Edit Exception Day
    </x-slot>

    <!-- Exception Day Edit Form -->
    <x-admin::form
        :action="route('xbooking.exception_days.update', $day->id)"
        method="post"
        enctype="multipart/form-data"
    >
        @method('PUT')
        @csrf

        <!-- Date -->
        <x-admin::form.control-group>
            <x-admin::form.control-group.label class="required">
                Date
            </x-admin::form.control-group.label>

            <x-admin::form.control-group.control
                type="date"
                name="date"
                :value="$day->date"
            />
        </x-admin::form.control-group>

        <!-- Submit Button -->
        <div class="flex justify-end mt-6">
            <button type="submit" class="primary-button">
                Update
            </button>
        </div>
    </x-admin::form>
</x-admin::layouts>
