<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $medicine->name }} Details - Our Pharmacy</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-blue-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <h1 class="text-2xl font-bold">Our Pharmacy</h1>
                <div class="flex space-x-4">
                    <a href="{{ route('public.home') }}" class="hover:text-blue-200">Home</a>
                    <a href="{{ route('public.medicines') }}" class="hover:text-blue-200">Browse Medicines</a>
                    <a href="{{ route('login') }}" class="hover:text-blue-200">Login</a>
                    <a href="{{ route('register') }}" class="hover:text-blue-200">Register as Pharmacist</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto px-4 py-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-8">{{ $medicine->name }}</h2>

        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @if ($medicine->image_path)
                    <div class="md:col-span-2 flex justify-center mb-4">
                        <img src="{{ asset('storage/' . $medicine->image_path) }}" alt="{{ $medicine->name }}" class="max-h-80 object-cover rounded-lg shadow-md">
                    </div>
                @else
                    <div class="md:col-span-2 flex justify-center mb-4">
                        <div class="w-full max-w-sm h-48 flex items-center justify-center bg-gray-200 rounded-lg shadow-md text-gray-500 text-lg">No Image Available</div>
                    </div>
                @endif
                <div>
                    <p class="text-gray-600 font-semibold">Generic Name:</p>
                    <p class="text-gray-900">{{ $medicine->generic_name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-gray-600 font-semibold">Manufacturer:</p>
                    <p class="text-gray-900">{{ $medicine->manufacturer }}</p>
                </div>
                <div>
                    <p class="text-gray-600 font-semibold">Category:</p>
                    <p class="text-gray-900">{{ $medicine->category ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-gray-600 font-semibold">Unit Price:</p>
                    <p class="text-green-600 text-xl font-bold">${{ number_format($medicine->unit_price, 2) }}</p>
                </div>
                <div>
                    <p class="text-gray-600 font-semibold">Expiry Date:</p>
                    <p class="text-gray-900">{{ $medicine->expiry_date->format('M d, Y') }}</p>
                </div>
                <div>
                    <p class="text-gray-600 font-semibold">Availability:</p>
                    <p class="text-gray-900">
                        @if($medicine->quantity > 0)
                            <span class="text-green-600 font-bold">In Stock</span>
                        @else
                            <span class="text-red-600 font-bold">Out of Stock</span>
                        @endif
                    </p>
                </div>
                @if($medicine->symptoms_treated)
                <div class="md:col-span-2">
                    <p class="text-gray-600 font-semibold">Symptoms Treated:</p>
                    <p class="text-gray-900">{{ $medicine->symptoms_treated }}</p>
                </div>
                @endif
                <div class="md:col-span-2">
                    <p class="text-gray-600 font-semibold">Description:</p>
                    <p class="text-gray-900">{{ $medicine->description }}</p>
                </div>
            </div>
        </div>

        <!-- Feedback Section (for public users to leave feedback on the pharmacist associated with this medicine) -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Leave Feedback</h3>
            <form action="{{ route('public.feedback.store') }}" method="POST">
                @csrf
                <input type="hidden" name="pharmacist_id" value="{{ $medicine->user_id }}">
                <div class="mb-4">
                    <label for="rating" class="block text-sm font-medium text-gray-700">Rating (Optional)</label>
                    <select name="rating" id="rating" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select rating</option>
                        <option value="5">5 Stars - Excellent</option>
                        <option value="4">4 Stars - Very Good</option>
                        <option value="3">3 Stars - Good</option>
                        <option value="2">2 Stars - Fair</option>
                        <option value="1">1 Star - Poor</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="comment" class="block text-sm font-medium text-gray-700">Your Feedback</label>
                    <textarea name="comment" id="comment" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required></textarea>
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Submit Feedback
                </button>
            </form>
        </div>

        <!-- Report Bug / Suggest Feature (for public users) -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Have a suggestion or found a bug?</h3>
            <div class="flex space-x-4">
                <button onclick="openReportBugModal()" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Report Bug</button>
                <button onclick="openSuggestFeatureModal()" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">Suggest Feature</button>
            </div>
        </div>
    </div>

    <!-- Bug Report Modal -->
    <div id="bugReportModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('public.bug_report.store') }}" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Report a Bug</h3>
                        <div class="mb-4">
                            <label for="bug_title" class="block text-sm font-medium text-gray-700">Title</label>
                            <input type="text" name="title" id="bug_title" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                        </div>
                        <div class="mb-4">
                            <label for="bug_description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="bug_description" rows="4" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500"></textarea>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Submit Bug Report
                        </button>
                        <button type="button" onclick="closeReportBugModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Feature Suggestion Modal -->
    <div id="suggestFeatureModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('public.feature_suggestion.store') }}" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Suggest a Feature</h3>
                        <div class="mb-4">
                            <label for="feature_title" class="block text-sm font-medium text-gray-700">Title</label>
                            <input type="text" name="title" id="feature_title" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
                        </div>
                        <div class="mb-4">
                            <label for="feature_description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="feature_description" rows="4" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500"></textarea>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-purple-600 text-base font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Submit Suggestion
                        </button>
                        <button type="button" onclick="closeSuggestFeatureModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openReportBugModal() {
            document.getElementById('bugReportModal').classList.remove('hidden');
        }

        function closeReportBugModal() {
            document.getElementById('bugReportModal').classList.add('hidden');
            document.getElementById('bug_title').value = '';
            document.getElementById('bug_description').value = '';
        }

        function openSuggestFeatureModal() {
            document.getElementById('suggestFeatureModal').classList.remove('hidden');
        }

        function closeSuggestFeatureModal() {
            document.getElementById('suggestFeatureModal').classList.add('hidden');
            document.getElementById('feature_title').value = '';
            document.getElementById('feature_description').value = '';
        }
    </script>
</body>
</html>
