@extends('backend.layout.app')

@section('content')

        <div class="p-6">
            <!-- Dashboard Section -->
            <section id="dashboard" class="content-section">
                <h2 class="text-3xl font-bold text-gray-800 mb-6">Dashboard Overview</h2>
                
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">Total Sales</p>
                                <p class="text-3xl font-bold text-gray-800">$24,580</p>
                                <p class="text-green-500 text-sm">+12.5% from last month</p>
                            </div>
                            <div class="bg-blue-100 p-3 rounded-full">
                                <i class="fas fa-dollar-sign text-blue-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">Total Orders</p>
                                <p class="text-3xl font-bold text-gray-800">1,247</p>
                                <p class="text-green-500 text-sm">+8.2% from last month</p>
                            </div>
                            <div class="bg-green-100 p-3 rounded-full">
                                <i class="fas fa-shopping-cart text-green-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-yellow-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">Products</p>
                                <p class="text-3xl font-bold text-gray-800">847</p>
                                <p class="text-red-500 text-sm">-2.1% from last month</p>
                            </div>
                            <div class="bg-yellow-100 p-3 rounded-full">
                                <i class="fas fa-box text-yellow-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">Customers</p>
                                <p class="text-3xl font-bold text-gray-800">3,692</p>
                                <p class="text-green-500 text-sm">+15.3% from last month</p>
                            </div>
                            <div class="bg-purple-100 p-3 rounded-full">
                                <i class="fas fa-users text-purple-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Sales Overview</h3>
                        <canvas id="salesChart" width="400" height="200"></canvas>
                    </div>
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Top Categories</h3>
                        <canvas id="categoryChart" width="400" height="200"></canvas>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Recent Orders</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left">Order ID</th>
                                    <th class="px-4 py-3 text-left">Customer</th>
                                    <th class="px-4 py-3 text-left">Amount</th>
                                    <th class="px-4 py-3 text-left">Status</th>
                                    <th class="px-4 py-3 text-left">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr><td class="px-4 py-3">#12345</td><td class="px-4 py-3">John Doe</td><td class="px-4 py-3">$129.99</td><td class="px-4 py-3"><span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Completed</span></td><td class="px-4 py-3">2024-12-01</td></tr>
                                <tr><td class="px-4 py-3">#12346</td><td class="px-4 py-3">Jane Smith</td><td class="px-4 py-3">$89.50</td><td class="px-4 py-3"><span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Pending</span></td><td class="px-4 py-3">2024-12-01</td></tr>
                                <tr><td class="px-4 py-3">#12347</td><td class="px-4 py-3">Mike Johnson</td><td class="px-4 py-3">$234.75</td><td class="px-4 py-3"><span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Processing</span></td><td class="px-4 py-3">2024-11-30</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Products Section -->
            <section id="products" class="content-section hidden">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                    <h2 class="text-3xl font-bold text-gray-800">Products Management</h2>
                    <button class="mt-4 sm:mt-0 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                        <i class="fas fa-plus mr-2"></i>Add Product
                    </button>
                </div>
                
                <div class="bg-white rounded-xl shadow-md">
                    <div class="p-6 border-b">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <input type="text" placeholder="Search products..." class="flex-1 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <select class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option>All Categories</option>
                                <option>Electronics</option>
                                <option>Clothing</option>
                                <option>Books</option>
                            </select>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left">Product</th>
                                    <th class="px-6 py-4 text-left">Category</th>
                                    <th class="px-6 py-4 text-left">Price</th>
                                    <th class="px-6 py-4 text-left">Stock</th>
                                    <th class="px-6 py-4 text-left">Status</th>
                                    <th class="px-6 py-4 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-3">
                                            <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=50&h=50&fit=crop" alt="Product" class="w-12 h-12 rounded-lg object-cover">
                                            <div>
                                                <div class="font-medium">Wireless Headphones</div>
                                                <div class="text-sm text-gray-500">SKU: WH001</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">Electronics</td>
                                    <td class="px-6 py-4">$99.99</td>
                                    <td class="px-6 py-4">45</td>
                                    <td class="px-6 py-4"><span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Active</span></td>
                                    <td class="px-6 py-4">
                                        <button class="text-blue-600 hover:text-blue-900 mr-3"><i class="fas fa-edit"></i></button>
                                        <button class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Stock Management Section -->
            <section id="stock" class="content-section hidden">
                <h2 class="text-3xl font-bold text-gray-800 mb-6">Stock Management</h2>
                
                <!-- Stock Alerts -->
                <div class="bg-red-50 border border-red-200 rounded-xl p-6 mb-6">
                    <h3 class="text-lg font-semibold text-red-800 mb-3">
                        <i class="fas fa-exclamation-triangle mr-2"></i>Low Stock Alerts
                    </h3>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center bg-white p-3 rounded-lg">
                            <span>Wireless Mouse - Only 5 left</span>
                            <button class="bg-red-600 text-white px-3 py-1 rounded text-sm">Reorder</button>
                        </div>
                        <div class="flex justify-between items-center bg-white p-3 rounded-lg">
                            <span>Gaming Keyboard - Only 2 left</span>
                            <button class="bg-red-600 text-white px-3 py-1 rounded text-sm">Reorder</button>
                        </div>
                    </div>
                </div>

                <!-- Stock Overview -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Total Products</h3>
                        <p class="text-3xl font-bold text-blue-600">847</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">In Stock</h3>
                        <p class="text-3xl font-bold text-green-600">623</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Out of Stock</h3>
                        <p class="text-3xl font-bold text-red-600">24</p>
                    </div>
                </div>

                <!-- Stock Table -->
                <div class="bg-white rounded-xl shadow-md">
                    <div class="p-6 border-b">
                        <h3 class="text-xl font-semibold">Stock Levels</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left">Product</th>
                                    <th class="px-6 py-4 text-left">Current Stock</th>
                                    <th class="px-6 py-4 text-left">Min Stock</th>
                                    <th class="px-6 py-4 text-left">Status</th>
                                    <th class="px-6 py-4 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr>
                                    <td class="px-6 py-4">Wireless Headphones</td>
                                    <td class="px-6 py-4">45</td>
                                    <td class="px-6 py-4">10</td>
                                    <td class="px-6 py-4"><span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Good</span></td>
                                    <td class="px-6 py-4">
                                        <button class="bg-blue-600 text-white px-3 py-1 rounded text-sm mr-2">Update Stock</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Bills & Invoices Section -->
            <section id="bills" class="content-section hidden">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                    <h2 class="text-3xl font-bold text-gray-800">Bills & Invoices</h2>
                    <button class="mt-4 sm:mt-0 bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition-colors">
                        <i class="fas fa-plus mr-2"></i>Generate Invoice
                    </button>
                </div>

                <!-- Invoice Stats -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h3 class="text-sm text-gray-500">Total Invoices</h3>
                        <p class="text-2xl font-bold text-gray-800">1,247</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h3 class="text-sm text-gray-500">Paid</h3>
                        <p class="text-2xl font-bold text-green-600">1,089</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h3 class="text-sm text-gray-500">Pending</h3>
                        <p class="text-2xl font-bold text-yellow-600">132</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h3 class="text-sm text-gray-500">Overdue</h3>
                        <p class="text-2xl font-bold text-red-600">26</p>
                    </div>
                </div>

                <!-- Invoices Table -->
                <div class="bg-white rounded-xl shadow-md">
                    <div class="p-6 border-b">
                        <h3 class="text-xl font-semibold">Recent Invoices</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left">Invoice #</th>
                                    <th class="px-6 py-4 text-left">Customer</th>
                                    <th class="px-6 py-4 text-left">Amount</th>
                                    <th class="px-6 py-4 text-left">Status</th>
                                    <th class="px-6 py-4 text-left">Date</th>
                                    <th class="px-6 py-4 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr>
                                    <td class="px-6 py-4">INV-001</td>
                                    <td class="px-6 py-4">John Doe</td>
                                    <td class="px-6 py-4">$299.99</td>
                                    <td class="px-6 py-4"><span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Paid</span></td>
                                    <td class="px-6 py-4">2024-12-01</td>
                                    <td class="px-6 py-4">
                                        <button class="text-blue-600 hover:text-blue-900 mr-3"><i class="fas fa-eye"></i></button>
                                        <button class="text-green-600 hover:text-green-900 mr-3"><i class="fas fa-download"></i></button>
                                        <button class="text-gray-600 hover:text-gray-900"><i class="fas fa-print"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Profit & Loss Section -->
            <section id="profit-loss" class="content-section hidden">
                <h2 class="text-3xl font-bold text-gray-800 mb-6">Profit & Loss Analysis</h2>
                
                <!-- P&L Summary -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Total Revenue</h3>
                        <p class="text-3xl font-bold text-green-600">$124,580</p>
                        <p class="text-sm text-gray-500">This month</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-red-500">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Total Expenses</h3>
                        <p class="text-3xl font-bold text-red-600">$89,240</p>
                        <p class="text-sm text-gray-500">This month</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Net Profit</h3>
                        <p class="text-3xl font-bold text-blue-600">$35,340</p>
                        <p class="text-sm text-green-500">+28.4% margin</p>
                    </div>
                </div>

                <!-- P&L Chart -->
                <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Monthly Profit & Loss Trend</h3>
                    <canvas id="profitLossChart" width="400" height="200"></canvas>
                </div>

                <!-- Expense Breakdown -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Expense Breakdown</h3>
                        <canvas id="expenseChart" width="400" height="300"></canvas>
                    </div>
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Revenue by Category</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span>Electronics</span>
                                <span class="font-semibold">$68,420</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: 55%"></div>
                            </div>
                            <div class="flex justify-between items-center">
                                <span>Clothing</span>
                                <span class="font-semibold">$32,180</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-600 h-2 rounded-full" style="width: 26%"></div>
                            </div>
                            <div class="flex justify-between items-center">
                                <span>Books</span>
                                <span class="font-semibold">$23,980</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-yellow-600 h-2 rounded-full" style="width: 19%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Other sections (categories, brands, orders, analytics, customers, settings) -->
            <section id="categories" class="content-section hidden">
                <h2 class="text-3xl font-bold text-gray-800 mb-6">Categories</h2>
                <div class="bg-white rounded-xl shadow-md p-6">
                    <p class="text-gray-600">Categories management interface will be here...</p>
                </div>
            </section>

            <section id="brands" class="content-section hidden">
                <h2 class="text-3xl font-bold text-gray-800 mb-6">Brands Management</h2>
                <div class="bg-white rounded-xl shadow-md p-6">
                    <p class="text-gray-600">Brands management interface will be here...</p>
                </div>
            </section>

            <section id="orders" class="content-section hidden">
                <h2 class="text-3xl font-bold text-gray-800 mb-6">Orders Management</h2>
                
                <!-- Order Stats -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h3 class="text-sm text-gray-500">Total Orders</h3>
                        <p class="text-2xl font-bold text-gray-800">1,247</p>
                        <p class="text-green-500 text-sm">+8.2% from last month</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h3 class="text-sm text-gray-500">Pending</h3>
                        <p class="text-2xl font-bold text-yellow-600">89</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h3 class="text-sm text-gray-500">Processing</h3>
                        <p class="text-2xl font-bold text-blue-600">156</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h3 class="text-sm text-gray-500">Completed</h3>
                        <p class="text-2xl font-bold text-green-600">1,002</p>
                    </div>
                </div>

                <!-- Orders Table -->
                <div class="bg-white rounded-xl shadow-md">
                    <div class="p-6 border-b">
                        <div class="flex flex-col sm:flex-row gap-4 items-center justify-between">
                            <h3 class="text-xl font-semibold">All Orders</h3>
                            <div class="flex gap-2">
                                <select class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option>All Status</option>
                                    <option>Pending</option>
                                    <option>Processing</option>
                                    <option>Completed</option>
                                    <option>Cancelled</option>
                                </select>
                                <input type="date" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left">Order ID</th>
                                    <th class="px-6 py-4 text-left">Customer</th>
                                    <th class="px-6 py-4 text-left">Products</th>
                                    <th class="px-6 py-4 text-left">Amount</th>
                                    <th class="px-6 py-4 text-left">Status</th>
                                    <th class="px-6 py-4 text-left">Date</th>
                                    <th class="px-6 py-4 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr>
                                    <td class="px-6 py-4">#ORD-12345</td>
                                    <td class="px-6 py-4">
                                        <div>
                                            <div class="font-medium">John Doe</div>
                                            <div class="text-sm text-gray-500">john@example.com</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">3 items</td>
                                    <td class="px-6 py-4">$299.99</td>
                                    <td class="px-6 py-4"><span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Completed</span></td>
                                    <td class="px-6 py-4">2024-12-01</td>
                                    <td class="px-6 py-4">
                                        <button class="text-blue-600 hover:text-blue-900 mr-3"><i class="fas fa-eye"></i></button>
                                        <button class="text-green-600 hover:text-green-900 mr-3"><i class="fas fa-edit"></i></button>
                                        <button class="text-gray-600 hover:text-gray-900"><i class="fas fa-print"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4">#ORD-12346</td>
                                    <td class="px-6 py-4">
                                        <div>
                                            <div class="font-medium">Jane Smith</div>
                                            <div class="text-sm text-gray-500">jane@example.com</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">1 item</td>
                                    <td class="px-6 py-4">$89.50</td>
                                    <td class="px-6 py-4"><span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Pending</span></td>
                                    <td class="px-6 py-4">2024-12-01</td>
                                    <td class="px-6 py-4">
                                        <button class="text-blue-600 hover:text-blue-900 mr-3"><i class="fas fa-eye"></i></button>
                                        <button class="text-green-600 hover:text-green-900 mr-3"><i class="fas fa-edit"></i></button>
                                        <button class="text-gray-600 hover:text-gray-900"><i class="fas fa-print"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <section id="analytics" class="content-section hidden">
                <h2 class="text-3xl font-bold text-gray-800 mb-6">Analytics & Reports</h2>
                
                <!-- Analytics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">Page Views</p>
                                <p class="text-2xl font-bold text-gray-800">45,678</p>
                                <p class="text-green-500 text-sm">+5.4% vs last month</p>
                            </div>
                            <div class="bg-purple-100 p-3 rounded-full">
                                <i class="fas fa-eye text-purple-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">Conversion Rate</p>
                                <p class="text-2xl font-bold text-gray-800">3.2%</p>
                                <p class="text-green-500 text-sm">+0.8% vs last month</p>
                            </div>
                            <div class="bg-green-100 p-3 rounded-full">
                                <i class="fas fa-chart-line text-green-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">Avg Order Value</p>
                                <p class="text-2xl font-bold text-gray-800">$127.50</p>
                                <p class="text-red-500 text-sm">-2.1% vs last month</p>
                            </div>
                            <div class="bg-blue-100 p-3 rounded-full">
                                <i class="fas fa-dollar-sign text-blue-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">Return Rate</p>
                                <p class="text-2xl font-bold text-gray-800">2.1%</p>
                                <p class="text-green-500 text-sm">-0.3% vs last month</p>
                            </div>
                            <div class="bg-red-100 p-3 rounded-full">
                                <i class="fas fa-undo text-red-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Analytics Charts -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Traffic Sources</h3>
                        <canvas id="trafficChart" width="400" height="300"></canvas>
                    </div>
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Device Usage</h3>
                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium">Desktop</span>
                                    <span class="text-sm">54.2%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: 54.2%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium">Mobile</span>
                                    <span class="text-sm">38.7%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-600 h-2 rounded-full" style="width: 38.7%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium">Tablet</span>
                                    <span class="text-sm">7.1%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-yellow-600 h-2 rounded-full" style="width: 7.1%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top Products -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Top Performing Products</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left">Product</th>
                                    <th class="px-6 py-3 text-left">Sales</th>
                                    <th class="px-6 py-3 text-left">Revenue</th>
                                    <th class="px-6 py-3 text-left">Growth</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr>
                                    <td class="px-6 py-4">Wireless Headphones</td>
                                    <td class="px-6 py-4">234 units</td>
                                    <td class="px-6 py-4">$23,400</td>
                                    <td class="px-6 py-4"><span class="text-green-600">+15.2%</span></td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4">Gaming Mouse</td>
                                    <td class="px-6 py-4">189 units</td>
                                    <td class="px-6 py-4">$13,230</td>
                                    <td class="px-6 py-4"><span class="text-green-600">+8.7%</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <section id="customers" class="content-section hidden">
                <h2 class="text-3xl font-bold text-gray-800 mb-6">Customers Management</h2>
                
                <!-- Customer Stats -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h3 class="text-sm text-gray-500">Total Customers</h3>
                        <p class="text-2xl font-bold text-gray-800">3,692</p>
                        <p class="text-green-500 text-sm">+15.3% from last month</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h3 class="text-sm text-gray-500">New This Month</h3>
                        <p class="text-2xl font-bold text-blue-600">234</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h3 class="text-sm text-gray-500">Active Customers</h3>
                        <p class="text-2xl font-bold text-green-600">2,847</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h3 class="text-sm text-gray-500">Lifetime Value</h3>
                        <p class="text-2xl font-bold text-purple-600">$285</p>
                    </div>
                </div>

                <!-- Customers Table -->
                <div class="bg-white rounded-xl shadow-md">
                    <div class="p-6 border-b">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <input type="text" placeholder="Search customers..." class="flex-1 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <select class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option>All Status</option>
                                <option>Active</option>
                                <option>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left">Customer</th>
                                    <th class="px-6 py-4 text-left">Email</th>
                                    <th class="px-6 py-4 text-left">Orders</th>
                                    <th class="px-6 py-4 text-left">Total Spent</th>
                                    <th class="px-6 py-4 text-left">Joined</th>
                                    <th class="px-6 py-4 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-3">
                                            <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=40&h=40&fit=crop&crop=face" alt="Customer" class="w-10 h-10 rounded-full">
                                            <div>
                                                <div class="font-medium">John Doe</div>
                                                <div class="text-sm text-gray-500">Premium Customer</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">john@example.com</td>
                                    <td class="px-6 py-4">15</td>
                                    <td class="px-6 py-4">$2,340.99</td>
                                    <td class="px-6 py-4">Jan 2024</td>
                                    <td class="px-6 py-4">
                                        <button class="text-blue-600 hover:text-blue-900 mr-3"><i class="fas fa-eye"></i></button>
                                        <button class="text-green-600 hover:text-green-900 mr-3"><i class="fas fa-edit"></i></button>
                                        <button class="text-red-600 hover:text-red-900"><i class="fas fa-ban"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <section id="settings" class="content-section hidden">
                <h2 class="text-3xl font-bold text-gray-800 mb-6">Settings</h2>
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- General Settings -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                            <h3 class="text-xl font-semibold text-gray-800 mb-4">General Settings</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Store Name</label>
                                    <input type="text" value="EG Tech" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Store Email</label>
                                    <input type="email" value="admin@egtech.com" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Store Address</label>
                                    <textarea class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" rows="3">123 Tech Street, Digital City, TC 12345</textarea>
                                </div>
                                <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                                    Save Changes
                                </button>
                            </div>
                        </div>

                        <!-- Payment Settings -->
                        <div class="bg-white rounded-xl shadow-md p-6">
                            <h3 class="text-xl font-semibold text-gray-800 mb-4">Payment Settings</h3>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-4 border rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <i class="fab fa-stripe text-2xl text-blue-600"></i>
                                        <div>
                                            <div class="font-medium">Stripe</div>
                                            <div class="text-sm text-gray-500">Credit/Debit Cards</div>
                                        </div>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="sr-only peer" checked>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                </div>
                                <div class="flex items-center justify-between p-4 border rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <i class="fab fa-paypal text-2xl text-blue-500"></i>
                                        <div>
                                            <div class="font-medium">PayPal</div>
                                            <div class="text-sm text-gray-500">PayPal Payments</div>
                                        </div>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div>
                        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                            <h3 class="text-xl font-semibold text-gray-800 mb-4">Quick Actions</h3>
                            <div class="space-y-3">
                                <button class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-3 rounded-lg text-left transition-colors">
                                    <i class="fas fa-download mr-2"></i>Export Data
                                </button>
                                <button class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-3 rounded-lg text-left transition-colors">
                                    <i class="fas fa-upload mr-2"></i>Import Products
                                </button>
                                <button class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-3 rounded-lg text-left transition-colors">
                                    <i class="fas fa-database mr-2"></i>Backup Database
                                </button>
                                <button class="w-full bg-red-100 hover:bg-red-200 text-red-800 px-4 py-3 rounded-lg text-left transition-colors">
                                    <i class="fas fa-trash mr-2"></i>Clear Cache
                                </button>
                            </div>
                        </div>

                        <!-- System Info -->
                        <div class="bg-white rounded-xl shadow-md p-6">
                            <h3 class="text-xl font-semibold text-gray-800 mb-4">System Information</h3>
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Version:</span>
                                    <span>v2.1.0</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Database:</span>
                                    <span class="text-green-600">Connected</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Storage:</span>
                                    <span>67% Used</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Last Backup:</span>
                                    <span>2 hours ago</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>


@endsection
