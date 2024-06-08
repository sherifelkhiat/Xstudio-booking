<x-admin::layouts>
    <!-- Title of the page -->
    <x-slot:title>
        Working Days
    </x-slot>
    <body data-csrf-token="{{ csrf_token() }}">

    <!-- Content Section -->
    <div class="p-4 bg-white dark:bg-gray-900 rounded box-shadow">
        <div class="flex justify-end mb-4">
            <a href="{{ route('xbooking.working_days.create') }}"
                class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                Create Working Day
            </a>
        </div>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Days
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Working Hours
                    </th>
                    <th scope="col"
                        class="px-6 py-3">
                        <span class="sr-only">Edit</span>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-900 dark:divide-gray-700">
                @foreach ($workingDays as $workingDay)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white">{{ $workingDay->days }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white">{{ $workingDay->from }} - {{ $workingDay->to }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button onclick="openConfirmationModal({{ $workingDay->id }})"
                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-600">Remove</button>
                            <a href="{{ route('xbooking.working_days.edit', $workingDay->id) }}"
                                class="ml-2 text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-600">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmationModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <!-- Heroicon name: exclamation -->
                            <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.126 4h12.252c1.241 0 2.276-.896 2.476-2.084l1.31-8.364c.2-1.187-.635-2.252-1.876-2.252H6.376c-1.241 0-2.076 1.065-1.876 2.252l1.31 8.364c.2 1.188 1.236 2.084 2.476 2.084z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                Remove Working Day
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Are you sure you want to remove this working day? This action cannot be undone.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button onclick="deleteWorkingDay()"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                            Remove
                        </button>
                    </form>
                    <button onclick="closeConfirmationModal()"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="workingDayId" />
    <input type="hidden" id="token" value="{{ csrf_token() }}" />

    <script>
        function openConfirmationModal(workingDayId) {
            document.getElementById('confirmationModal').classList.remove('hidden');
            document.getElementById('workingDayId').value = workingDayId;
        }

        function closeConfirmationModal() {
            document.getElementById('confirmationModal').classList.add('hidden');
        }

        function deleteWorkingDay() {
            var workingDayId = document.getElementById('workingDayId').value;
            var csrfToken = document.getElementById('token').value;

            var form = document.getElementById('deleteForm');
            form.action = '/admin/xbooking/working-days/' + workingDayId + '/delete';
            form.submit();
        }
    </script>
</x-admin::layouts>
