<x-admin::layouts>
    <!-- Title of the page -->
    <x-slot:title>
        Create Exception Day
    </x-slot>

    <!-- Exception Day Create Form -->
    <x-admin::form
        :action="route('xbooking.exception_days.store')"
        method="post"
        enctype="multipart/form-data"
    >
        @csrf

        <!-- Date -->
        <x-admin::form.control-group>
            <x-admin::form.control-group.label class="required">
                Date
            </x-admin::form.control-group.label>

            <x-admin::form.control-group.control
                type="date"
                name="date"
            />
        </x-admin::form.control-group>

        <!-- Submit Button -->
        <div class="flex justify-end mt-6">
            <button type="submit" class="primary-button">
                Create
            </button>
        </div>
    </x-admin::form>
</x-admin::layouts>
