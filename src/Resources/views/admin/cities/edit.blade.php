<x-admin::layouts>
    <!-- Title of the page -->
    <x-slot:title>
        Edit City
    </x-slot>

    <!-- City Edit Form -->
    <x-admin::form
        :action="route('xbooking.cities.update', $city->id)"
        method="post"
        enctype="multipart/form-data"
    >
        @method('PUT')
        @csrf

        <!-- Source City -->
        <x-admin::form.control-group>
            <x-admin::form.control-group.label class="required">
                Source City
            </x-admin::form.control-group.label>

            <x-admin::form.control-group.control
                type="text"
                name="source_city"
                :value="$city->source_city"
                :placeholder="'Enter source city'"
            />
        </x-admin::form.control-group>

        <!-- Destination City -->
        <x-admin::form.control-group>
            <x-admin::form.control-group.label class="required">
                Destination City
            </x-admin::form.control-group.label>

            <x-admin::form.control-group.control
                type="text"
                name="destination_city"
                :value="$city->destination_city"
                :placeholder="'Enter destination city'"
            />
        </x-admin::form.control-group>

        <!-- Duration -->
        <x-admin::form.control-group>
            <x-admin::form.control-group.label class="required">
                Duration (minuts)
            </x-admin::form.control-group.label>

            <x-admin::form.control-group.control
                type="number"
                name="duration"
                :value="$city->duration"
                :placeholder="'Enter duration in days'"
            />
        </x-admin::form.control-group>

        <!-- Extra Cost -->
        <x-admin::form.control-group>
            <x-admin::form.control-group.label class="required">
                Extra Cost
            </x-admin::form.control-group.label>

            <x-admin::form.control-group.control
                type="number"
                name="extra_cost"
                :value="$city->extra_cost"
                :placeholder="'Enter extra cost'"
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
