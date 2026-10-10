<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EG Tech - Admin Dashboard</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://unpkg.com/alpinejs"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Fixed Header -->
    <header class="fixed top-0 left-0 right-0 bg-white shadow-lg z-50 h-16">
        <div class="flex items-center justify-between h-full px-6">
            <div class="flex items-center space-x-4">
                <button id="sidebarToggle" class="lg:hidden text-gray-600 hover:text-gray-900">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-store text-blue-600 text-2xl"></i>
                    <a href="/admin">
                        <h1 class="text-xl font-bold text-gray-800">EG Tech</h1>
                    </a>
                </div>
            </div>

            <div class="flex items-center space-x-4">
                <div class="relative">
                    <button class="relative p-2 text-gray-600 hover:text-gray-900">
                        <i class="fas fa-bell text-xl"></i>
                        <span
                            class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span>
                    </button>
                </div>
                <div class="relative">
                    <button id="profileDropdown" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900">
                        <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=32&h=32&fit=crop&crop=face"
                            alt="Profile" class="w-8 h-8 rounded-full">
                        <span class="hidden md:block">
                            {{ Auth::guard('admin')->user()->name ?? 'Admin' }}
                        </span>
                        <i class="fas fa-chevron-down text-sm"></i>
                    </button>
                    <div id="profileMenu"
                        class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit"
                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </header>

    <!-- Fixed Sidebar -->
    <aside id="sidebar"
        class="fixed left-0 top-16 h-full w-64 bg-gray-900 text-white transform -translate-x-full lg:translate-x-0 transition-transform duration-300 z-40">
        <nav class="h-full overflow-y-auto scrollbar-hide py-4">
            <ul class="space-y-2 px-3">
                <li><a href="{{ route('admin.dashboard') }}"
                        class="nav-item active flex items-center space-x-3 px-3 py-2 rounded-lg">
                        <i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
                <li><a href="{{ route('product.index') }}"
                        class="nav-item flex items-center space-x-3 px-3 py-2 rounded-lg">
                        <i class="fas fa-box"></i><span>Products</span></a></li>
                <li><a href="{{ route('categories.index') }}"
                        class="nav-item flex items-center space-x-3 px-3 py-2 rounded-lg">
                        <i class="fas fa-tags"></i><span>Categories</span></a></li>
                <li><a href=" {{ route('brand.index') }}"
                        class="nav-item flex items-center space-x-3 px-3 py-2 rounded-lg">
                        <i class="fas fa-star"></i><span>Brands</span></a></li>
                <li><a href=" {{ route('banner.index') }}"
                        class="nav-item flex items-center space-x-3 px-3 py-2 rounded-lg">
                        <i class="fas fa-star"></i><span>Banners</span></a></li>
                <li><a href="{{ route('admin.orders.index') }}"
                        class="nav-item flex items-center space-x-3 px-3 py-2 rounded-lg">
                        <i class="fas fa-shopping-cart"></i><span>Orders</span></a></li>

                <li><a href="{{ route('suppliers.index') }}"
                        class="nav-item flex items-center space-x-3 px-3 py-2 rounded-lg">
                        <i class="fas fa-shopping-cart"></i><span>Suppliers</span></a></li>
                <li><a href="{{ route('stock.stock.index') }}"
                        class="nav-item flex items-center space-x-3 px-3 py-2 rounded-lg">
                        <i class="fas fa-warehouse"></i><span>Stock Management</span></a></li>
                <li><a href="{{ route('billing.index') }}"
                        class="nav-item flex items-center space-x-3 px-3 py-2 rounded-lg">
                        <i class="fas fa-file-invoice"></i><span>Bills & Invoices</span></a></li>
                <li><a href="{{ route('stock.summary') }}"
                        class="nav-item flex items-center space-x-3 px-3 py-2 rounded-lg">
                        <i class="fas fa-chart-line"></i><span>Summary</span></a></li>
                <li><a href="{{ route('billing.profit') }}"
                        class="nav-item flex items-center space-x-3 px-3 py-2 rounded-lg">
                        <i class="fas fa-calculator"></i><span>Profit & Loss</span></a></li>
                <li><a href="{{ route('users.index') }}"
                        class="nav-item flex items-center space-x-3 px-3 py-2 rounded-lg">
                        <i class="fas fa-users"></i><span>Customers</span></a></li>
                <li><a href="{{ route('credits.index') }}"
                        class="nav-item flex items-center space-x-3 px-3 py-2 rounded-lg">
                        <i class="fas fa-cog"></i><span>Credit</span></a></li>
                <li><a href="#settings" class="nav-item flex items-center space-x-3 px-3 py-2 rounded-lg">
                        <i class="fas fa-cog"></i><span>Settings</span></a></li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->

    <main class="lg:ml-64 pt-16 min-h-screen">
        @yield('content')
    </main>



    <!-- Mobile Overlay -->
    <div id="sidebarOverlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden"></div>

    <script>
        // Mobile menu toggle
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
            sidebarOverlay.classList.toggle('hidden');
        });

        sidebarOverlay.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
        });

        // Profile dropdown
        const profileDropdown = document.getElementById('profileDropdown');
        const profileMenu = document.getElementById('profileMenu');

        profileDropdown.addEventListener('click', () => {
            profileMenu.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!profileDropdown.contains(e.target)) {
                profileMenu.classList.add('hidden');
            }
        });

        // Navigation
        const navItems = document.querySelectorAll('.nav-item');
        const contentSections = document.querySelectorAll('.content-section');

        navItems.forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                const target = e.target.getAttribute('href').substring(1);

                // Update active nav item
                navItems.forEach(nav => nav.classList.remove('active', 'bg-blue-600'));
                item.classList.add('active', 'bg-blue-600');

                // Show target section
                contentSections.forEach(section => section.classList.add('hidden'));
                document.getElementById(target).classList.remove('hidden');

                // Close mobile menu
                sidebar.classList.add('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
            });
        });

        // Initialize Charts
        function initializeCharts() {
            // Sales Chart
            const salesCtx = document.getElementById('salesChart').getContext('2d');
            new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Sales',
                        data: [12000, 15000, 13000, 17000, 16000, 24580],
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            // Category Chart
            const categoryCtx = document.getElementById('categoryChart').getContext('2d');
            new Chart(categoryCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Electronics', 'Clothing', 'Books', 'Sports'],
                    datasets: [{
                        data: [55, 26, 19, 12],
                        backgroundColor: ['#3B82F6', '#10B981', '#F59E0B', '#8B5CF6']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Profit Loss Chart
            const profitLossCtx = document.getElementById('profitLossChart').getContext('2d');
            new Chart(profitLossCtx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Revenue',
                        data: [85000, 92000, 78000, 105000, 98000, 124580],
                        backgroundColor: 'rgba(34, 197, 94, 0.8)'
                    }, {
                        label: 'Expenses',
                        data: [65000, 72000, 58000, 78000, 75000, 89240],
                        backgroundColor: 'rgba(239, 68, 68, 0.8)'
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Expense Chart
            const expenseCtx = document.getElementById('expenseChart').getContext('2d');
            new Chart(expenseCtx, {
                type: 'pie',
                data: {
                    labels: ['Inventory', 'Marketing', 'Operations', 'Salaries', 'Other'],
                    datasets: [{
                        data: [45, 20, 15, 15, 5],
                        backgroundColor: ['#3B82F6', '#10B981', '#F59E0B', '#8B5CF6', '#6B7280']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Traffic Chart
            const trafficCtx = document.getElementById('trafficChart').getContext('2d');
            new Chart(trafficCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Organic Search', 'Direct', 'Social Media', 'Referral', 'Email'],
                    datasets: [{
                        data: [42, 28, 15, 10, 5],
                        backgroundColor: ['#3B82F6', '#10B981', '#F59E0B', '#8B5CF6', '#EC4899']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }

        // Initialize charts when page loads
        document.addEventListener('DOMContentLoaded', function() {
            initializeCharts();
        });

        // Add some interactive functionality
        document.addEventListener('DOMContentLoaded', function() {
                    // Add hover effects to nav items
                    navItems.forEach(item => {
                        if (!item.classList.contains('active')) {
                            item.addEventListener('mouseenter', () => {
                                item.classList.add('bg-gray-700');
                            });
                            item.addEventListener('mouseleave', () => {
                                item.classList.remove('bg-gray-700');
                            });
                        }
                    });

                    // Auto-refresh data simulation
                    setInterval(() => {
                            // Simulate real-time updates
                            const statsElements = document.querySelectorAll('.text-3xl.font-bold');
                            statsElements.forEach(element => {
                                    if (element.textContent.includes(')) {
                                            const currentValue = parseFloat(element.textContent.replace(/[$,]/g,
                                                ''));
                                            const change = (Math.random() - 0.5) * 100;
                                            const newValue = Math.max(0, currentValue + change); element
                                            .textContent = ' + newValue.toLocaleString('
                                            en - US ', {
                                            minimumFractionDigits: 2,
                                            maximumFractionDigits: 2
                                        });
                                }
                            });
                    }, 30000); // Update every 30 seconds

                // Add notification system
                function showNotification(message, type = 'info') {
                    const notification = document.createElement('div');
                    notification.className = `fixed top-20 right-4 z-50 p-4 rounded-lg shadow-lg ${
                    type === 'success' ? 'bg-green-500' : 
                    type === 'error' ? 'bg-red-500' : 
                    'bg-blue-500'
                } text-white transform translate-x-full transition-transform duration-300`;
                    notification.textContent = message;

                    document.body.appendChild(notification);

                    setTimeout(() => {
                        notification.classList.remove('translate-x-full');
                    }, 100);

                    setTimeout(() => {
                        notification.classList.add('translate-x-full');
                        setTimeout(() => {
                            document.body.removeChild(notification);
                        }, 300);
                    }, 3000);
                }

                // Add notification triggers for demo purposes
                document.querySelectorAll('button').forEach(button => {
                    if (button.textContent.includes('Add') || button.textContent.includes('Generate') || button
                        .textContent.includes('Update')) {
                        button.addEventListener('click', (e) => {
                            e.preventDefault();
                            const action = button.textContent.trim();
                            showNotification(`${action} action completed successfully!`, 'success');
                        });
                    }
                });

                // Bill Generation functionality
                const generateBillModal = `
                <div id="billModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
                    <div class="bg-white rounded-xl p-6 w-full max-w-2xl mx-4">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-semibold">Generate Invoice</h3>
                            <button id="closeBillModal" class="text-gray-500 hover:text-gray-700">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <form id="billForm" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1">Customer Name</label>
                                    <input type="text" class="w-full px-3 py-2 border rounded-lg" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Invoice Date</label>
                                    <input type="date" class="w-full px-3 py-2 border rounded-lg" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Customer Email</label>
                                    <input type="email" class="w-full px-3 py-2 border rounded-lg" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Payment Terms</label>
                                    <select class="w-full px-3 py-2 border rounded-lg">
                                        <option>Net 30</option>
                                        <option>Net 15</option>
                                        <option>Due on Receipt</option>
                                    </select>
                                </div>
                            </div>
                            <div id="invoiceItems" class="border rounded-lg p-4">
                                <div class="flex justify-between items-center mb-3">
                                    <h4 class="font-medium">Invoice Items</h4>
                                    <button type="button" id="addItem" class="text-blue-600 hover:text-blue-700">
                                        <i class="fas fa-plus mr-1"></i>Add Item
                                    </button>
                                </div>
                                <div class="invoice-item grid grid-cols-12 gap-2 mb-2">
                                    <input type="text" placeholder="Item" class="col-span-4 px-2 py-1 border rounded text-sm">
                                    <input type="number" placeholder="Qty" class="col-span-2 px-2 py-1 border rounded text-sm">
                                    <input type="number" placeholder="Price" class="col-span-3 px-2 py-1 border rounded text-sm">
                                    <input type="text" placeholder="Total" class="col-span-2 px-2 py-1 border rounded text-sm bg-gray-100" readonly>
                                    <button type="button" class="col-span-1 text-red-600 hover:text-red-700">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="flex justify-between items-center pt-4 border-t">
                                <div class="text-lg font-semibold">
                                    Total: $<span id="invoiceTotal">0.00</span>
                                </div>
                                <div class="space-x-2">
                                    <button type="button" id="closeBillModal2" class="px-4 py-2 border rounded-lg hover:bg-gray-50">
                                        Cancel
                                    </button>
                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                        Generate Invoice
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            `;

                document.body.insertAdjacentHTML('beforeend', generateBillModal);

                // Modal functionality
                const billModal = document.getElementById('billModal');
                const closeBillModal = document.getElementById('closeBillModal');
                const closeBillModal2 = document.getElementById('closeBillModal2');

                document.querySelectorAll('button').forEach(button => {
                    if (button.textContent.includes('Generate Invoice')) {
                        button.addEventListener('click', (e) => {
                            e.preventDefault();
                            billModal.classList.remove('hidden');
                            billModal.classList.add('flex');
                        });
                    }
                });

                [closeBillModal, closeBillModal2].forEach(btn => {
                    btn.addEventListener('click', () => {
                        billModal.classList.add('hidden');
                        billModal.classList.remove('flex');
                    });
                });

                // Add item functionality
                document.getElementById('addItem').addEventListener('click', () => {
                    const itemsContainer = document.getElementById('invoiceItems');
                    const newItem = document.createElement('div');
                    newItem.className = 'invoice-item grid grid-cols-12 gap-2 mb-2';
                    newItem.innerHTML = `
                    <input type="text" placeholder="Item" class="col-span-4 px-2 py-1 border rounded text-sm">
                    <input type="number" placeholder="Qty" class="col-span-2 px-2 py-1 border rounded text-sm">
                    <input type="number" placeholder="Price" class="col-span-3 px-2 py-1 border rounded text-sm">
                    <input type="text" placeholder="Total" class="col-span-2 px-2 py-1 border rounded text-sm bg-gray-100" readonly>
                    <button type="button" class="col-span-1 text-red-600 hover:text-red-700">
                        <i class="fas fa-trash"></i>
                    </button>
                `;
                    itemsContainer.appendChild(newItem);

                    // Add remove functionality
                    newItem.querySelector('button').addEventListener('click', () => {
                        newItem.remove();
                        updateInvoiceTotal();
                    });

                    // Add calculation functionality
                    const qtyInput = newItem.querySelector('input[placeholder="Qty"]');
                    const priceInput = newItem.querySelector('input[placeholder="Price"]');
                    const totalInput = newItem.querySelector('input[placeholder="Total"]');

                    [qtyInput, priceInput].forEach(input => {
                        input.addEventListener('input', () => {
                            const qty = parseFloat(qtyInput.value) || 0;
                            const price = parseFloat(priceInput.value) || 0;
                            totalInput.value = (qty * price).toFixed(2);
                            updateInvoiceTotal();
                        });
                    });
                });

                function updateInvoiceTotal() {
                    let total = 0;
                    document.querySelectorAll('input[placeholder="Total"]').forEach(input => {
                        total += parseFloat(input.value) || 0;
                    });
                    document.getElementById('invoiceTotal').textContent = total.toFixed(2);
                }

                // Form submission
                document.getElementById('billForm').addEventListener('submit', (e) => {
                    e.preventDefault();
                    showNotification('Invoice generated successfully!', 'success');
                    billModal.classList.add('hidden');
                    billModal.classList.remove('flex');
                });

                // Stock management functionality
                document.querySelectorAll('button').forEach(button => {
                    if (button.textContent.includes('Update Stock')) {
                        button.addEventListener('click', (e) => {
                            e.preventDefault();
                            const newStock = prompt('Enter new stock quantity:');
                            if (newStock !== null && !isNaN(newStock)) {
                                const row = button.closest('tr');
                                const stockCell = row.children[1];
                                stockCell.textContent = newStock;
                                showNotification('Stock updated successfully!', 'success');
                            }
                        });
                    }
                });

                // Reorder functionality
                document.querySelectorAll('button').forEach(button => {
                    if (button.textContent.includes('Reorder')) {
                        button.addEventListener('click', (e) => {
                            e.preventDefault();
                            const quantity = prompt('Enter reorder quantity:');
                            if (quantity !== null && !isNaN(quantity)) {
                                showNotification(`Reorder for ${quantity} units placed successfully!`,
                                    'success');
                            }
                        });
                    }
                });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const profileDropdown = document.getElementById("profileDropdown");
            const profileMenu = document.getElementById("profileMenu");

            profileDropdown.addEventListener("click", function(e) {
                e.stopPropagation();
                profileMenu.classList.toggle("hidden");
            });

            // Close when clicking outside
            document.addEventListener("click", function(e) {
                if (!profileDropdown.contains(e.target) && !profileMenu.contains(e.target)) {
                    profileMenu.classList.add("hidden");
                }
            });
        });
    </script>

</body>

</html>
