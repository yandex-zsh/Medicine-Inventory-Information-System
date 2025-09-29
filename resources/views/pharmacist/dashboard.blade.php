<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacist Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-blue-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <h1 class="text-2xl font-bold">Pharmacist Dashboard</h1>
                <div class="flex space-x-4">
                    <a href="{{ route('pharmacist.dashboard') }}" class="hover:text-blue-200">Dashboard</a>
                    <a href="{{ route('pharmacist.reports') }}" class="hover:text-blue-200">Reports</a>
                    <a href="{{ route('pharmacist.profile') }}" class="hover:text-blue-200">Profile</a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        @method('POST')
                        <button type="submit" class="hover:text-blue-200">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Alerts Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Low Stock Alert -->
            <div class="bg-yellow-100 border-l-4 border-yellow-500 p-4 rounded">
                <div class="flex">
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            <strong>Low Stock Alert:</strong> {{ $lowStockMedicines->count() }} medicines need restocking
                        </p>
                    </div>
                </div>
            </div>

            <!-- Expiring Soon Alert -->
            <div class="bg-orange-100 border-l-4 border-orange-500 p-4 rounded">
                <div class="flex">
                    <div class="ml-3">
                        <p class="text-sm text-orange-700">
                            <strong>Expiring Soon:</strong> {{ $expiringSoonMedicines->count() }} medicines expiring within 30 days
                        </p>
                    </div>
                </div>
            </div>

            <!-- Expired Alert -->
            <div class="bg-red-100 border-l-4 border-red-500 p-4 rounded">
                <div class="flex">
                    <div class="ml-3">
                        <p class="text-sm text-red-700">
                            <strong>Expired:</strong> {{ $expiredMedicines->count() }} medicines have expired
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Medicine Stock Section -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Medicine Stock</h2>
                <div class="flex space-x-4">
                    <button onclick="openAddMedicineModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Add Medicine
                    </button>
                    <button onclick="openRecordSaleModal()" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                        Record Sale
                    </button>
                </div>
            </div>

            <!-- Medicine Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Manufacturer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expiry Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Public</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($medicines as $medicine)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $medicine->name }}</div>
                                @if($medicine->generic_name)
                                    <div class="text-sm text-gray-500">{{ $medicine->generic_name }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $medicine->manufacturer }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $medicine->quantity }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${{ number_format($medicine->unit_price, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $medicine->expiry_date->format('M d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <form action="{{ route('pharmacist.medicines.toggle_public', $medicine) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="is_public" class="sr-only peer" {{ $medicine->is_public ? 'checked' : '' }} onchange="this.form.submit()">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                </form>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if ($medicine->image_path)
                                    <img src="{{ asset('storage/' . $medicine->image_path) }}" alt="Medicine Image" class="h-10 w-10 object-cover rounded-full">
                                @else
                                    No Image
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($medicine->stock_status === 'expired')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Expired</span>
                                @elseif($medicine->stock_status === 'expiring_soon')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">Expiring Soon</span>
                                @elseif($medicine->stock_status === 'low_stock')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Low Stock</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Normal</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="openEditMedicineModal({{ $medicine->id }}, '{{ $medicine->name }}', '{{ $medicine->generic_name }}', '{{ $medicine->manufacturer }}', '{{ $medicine->quantity }}', '{{ $medicine->minimum_stock_level }}', '{{ $medicine->unit_price }}', '{{ $medicine->expiry_date->format('Y-m-d') }}', '{{ $medicine->batch_number }}', '{{ $medicine->description }}', '{{ $medicine->category }}', '{{ $medicine->is_public }}', '{{ $medicine->symptoms_treated }}')" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                <form action="{{ route('pharmacist.medicines.destroy', $medicine) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this medicine?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Low Stock Medicines</h3>
                @if($lowStockMedicines->count() > 0)
                    <ul class="space-y-2">
                        @foreach($lowStockMedicines->take(5) as $medicine)
                            <li class="text-sm text-gray-600">{{ $medicine->name }} ({{ $medicine->quantity }} left)</li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-sm text-gray-500">No low stock medicines</p>
                @endif
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Expiring Soon</h3>
                @if($expiringSoonMedicines->count() > 0)
                    <ul class="space-y-2">
                        @foreach($expiringSoonMedicines->take(5) as $medicine)
                            <li class="text-sm text-gray-600">{{ $medicine->name }} ({{ $medicine->expiry_date->format('M d, Y') }})</li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-sm text-gray-500">No medicines expiring soon</p>
                @endif
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Reports</h3>
                <div class="space-y-2">
                    <a href="{{ route('pharmacist.reports', ['period' => 'daily']) }}" class="block text-sm text-blue-600 hover:text-blue-800">Daily Report</a>
                    <a href="{{ route('pharmacist.reports', ['period' => 'weekly']) }}" class="block text-sm text-blue-600 hover:text-blue-800">Weekly Report</a>
                    <a href="{{ route('pharmacist.reports', ['period' => 'monthly']) }}" class="block text-sm text-blue-600 hover:text-blue-800">Monthly Report</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Medicine Modal -->
    <div id="addMedicineModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('pharmacist.medicines.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Add New Medicine</h3>
                        @if ($errors->any())
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                                <strong class="font-bold">Whoops!</strong>
                                <span class="block sm:inline">There were some problems with your input.</span>
                                <ul class="mt-3 list-disc list-inside text-sm">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Medicine Name</label>
                                <input type="text" name="name" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('name') }}">
                                @error('name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Generic Name</label>
                                <input type="text" name="generic_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('generic_name') }}">
                                @error('generic_name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Manufacturer</label>
                                <input type="text" name="manufacturer" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('manufacturer') }}">
                                @error('manufacturer')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Quantity</label>
                                    <input type="number" name="quantity" required min="0" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('quantity') }}">
                                    @error('quantity')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Minimum Stock Level</label>
                                    <input type="number" name="minimum_stock_level" required min="0" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('minimum_stock_level') }}">
                                    @error('minimum_stock_level')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Unit Price</label>
                                <input type="number" name="unit_price" step="0.01" required min="0" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('unit_price') }}">
                                @error('unit_price')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Expiry Date</label>
                                <input type="date" name="expiry_date" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('expiry_date') }}">
                                @error('expiry_date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Batch Number</label>
                                <input type="text" name="batch_number" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('batch_number') }}">
                                @error('batch_number')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Category</label>
                                <input type="text" name="category" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('category') }}">
                                @error('category')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Symptoms Treated</label>
                                <textarea name="symptoms_treated" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('symptoms_treated') }}</textarea>
                                @error('symptoms_treated')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="is_public" value="1" class="form-checkbox" {{ old('is_public', 1) ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-gray-600">Publicly Visible</span>
                                </label>
                                @error('is_public')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Medicine Image</label>
                                <input type="file" name="image" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                @error('image')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Add Medicine
                        </button>
                        <button type="button" onclick="closeAddMedicineModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Medicine Modal -->
    <div id="editMedicineModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form id="editMedicineForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Medicine: <span id="editMedicineName"></span></h3>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Medicine Name</label>
                                <input type="text" name="name" id="edit_name" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Generic Name</label>
                                <input type="text" name="generic_name" id="edit_generic_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Manufacturer</label>
                                <input type="text" name="manufacturer" id="edit_manufacturer" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Quantity</label>
                                    <input type="number" name="quantity" id="edit_quantity" required min="0" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Minimum Stock Level</label>
                                    <input type="number" name="minimum_stock_level" id="edit_minimum_stock_level" required min="0" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Unit Price</label>
                                <input type="number" name="unit_price" id="edit_unit_price" step="0.01" required min="0" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Expiry Date</label>
                                <input type="date" name="expiry_date" id="edit_expiry_date" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Batch Number</label>
                                <input type="text" name="batch_number" id="edit_batch_number" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Category</label>
                                <input type="text" name="category" id="edit_category" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Symptoms Treated</label>
                                <textarea name="symptoms_treated" id="edit_symptoms_treated" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>
                            <div>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="is_public" id="edit_is_public" value="1" class="form-checkbox">
                                    <span class="ml-2 text-sm text-gray-600">Publicly Visible</span>
                                </label>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" id="edit_description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Medicine Image</label>
                                @if (isset($medicine) && $medicine->image_path)
                                    <img src="{{ asset('storage/' . $medicine->image_path) }}" alt="Medicine Image" class="mt-2 h-20 w-20 object-cover rounded-md">
                                @endif
                                <input type="file" name="image" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                @error('image')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Update Medicine
                        </button>
                        <button type="button" onclick="closeEditMedicineModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Record Sale Modal -->
    <div id="recordSaleModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('pharmacist.record_sale') }}" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Record Medicine Sale</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Medicine</label>
                                <select name="medicine_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
                                    <option value="">Select Medicine</option>
                                    @foreach($medicines as $medicine)
                                        <option value="{{ $medicine->id }}">{{ $medicine->name }} (Current Stock: {{ $medicine->quantity }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Quantity Sold</label>
                                <input type="number" name="quantity" required min="1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Sale Date</label>
                                <input type="date" name="sale_date" required value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-purple-600 text-base font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Record Sale
                        </button>
                        <button type="button" onclick="closeRecordSaleModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openAddMedicineModal() {
            document.getElementById('addMedicineModal').classList.remove('hidden');
        }

        function closeAddMedicineModal() {
            document.getElementById('addMedicineModal').classList.add('hidden');
        }
        
        @if ($errors->any() && Session::has('add_medicine_modal_open'))
            openAddMedicineModal();
        @endif

        function openEditMedicineModal(id, name, generic_name, manufacturer, quantity, minimum_stock_level, unit_price, expiry_date, batch_number, description, category, is_public, symptoms_treated) {
            document.getElementById('editMedicineForm').action = `/pharmacist/medicines/${id}`;
            document.getElementById('editMedicineName').innerText = name;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_generic_name').value = generic_name;
            document.getElementById('edit_manufacturer').value = manufacturer;
            document.getElementById('edit_quantity').value = quantity;
            document.getElementById('edit_minimum_stock_level').value = minimum_stock_level;
            document.getElementById('edit_unit_price').value = unit_price;
            document.getElementById('edit_expiry_date').value = expiry_date;
            document.getElementById('edit_batch_number').value = batch_number;
            document.getElementById('edit_description').value = description;
            document.getElementById('edit_category').value = category;
            document.getElementById('edit_symptoms_treated').value = symptoms_treated;
            document.getElementById('edit_is_public').checked = is_public == '1' ? true : false;
            document.getElementById('editMedicineModal').classList.remove('hidden');
        }

        function closeEditMedicineModal() {
            document.getElementById('editMedicineModal').classList.add('hidden');
        }

        function openRecordSaleModal() {
            document.getElementById('recordSaleModal').classList.remove('hidden');
        }

        function closeRecordSaleModal() {
            document.getElementById('recordSaleModal').classList.add('hidden');
        }
    </script>
</body>
</html>
