<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }} - Notification Manager</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        
        <!-- Styles -->
        <style>
            :root {
                --primary-color: #4361ee;
                --secondary-color: #3f37c9;
                --success-color: #4cc9f0;
                --danger-color: #f72585;
                --light-bg: #f8f9fa;
                --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                --hover-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
            }
            
            body {
                font-family: 'Instrument Sans', sans-serif;
                background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
                min-height: 100vh;
                padding-bottom: 50px;
            }
            
            .navbar-brand {
                font-weight: 600;
                color: var(--primary-color) !important;
            }
            
            .main-card {
                border: none;
                border-radius: 15px;
                box-shadow: var(--card-shadow);
                transition: transform 0.3s, box-shadow 0.3s;
                overflow: hidden;
            }
            
            .main-card:hover {
                transform: translateY(-5px);
                box-shadow: var(--hover-shadow);
            }
            
            .card-header {
                background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
                color: white;
                border-bottom: none;
                padding: 1.5rem;
            }
            
            .btn-primary {
                background-color: var(--primary-color);
                border-color: var(--primary-color);
                padding: 0.5rem 1.5rem;
                border-radius: 8px;
                font-weight: 500;
            }
            
            .btn-primary:hover {
                background-color: var(--secondary-color);
                border-color: var(--secondary-color);
                transform: translateY(-2px);
                transition: all 0.3s;
            }
            
            .btn-outline-primary {
                color: var(--primary-color);
                border-color: var(--primary-color);
            }
            
            .btn-outline-primary:hover {
                background-color: var(--primary-color);
                border-color: var(--primary-color);
            }
            
            .notification-item {
                border-left: 4px solid var(--primary-color);
                margin-bottom: 15px;
                transition: all 0.3s;
                border-radius: 8px;
            }
            
            .notification-item:hover {
                background-color: rgba(67, 97, 238, 0.05);
                transform: translateX(5px);
            }
            
            .notification-item.unread {
                border-left-color: var(--danger-color);
                background-color: rgba(247, 37, 133, 0.05);
            }
            
            .form-control, .form-select {
                border-radius: 8px;
                border: 1px solid #dee2e6;
                padding: 0.75rem;
            }
            
            .form-control:focus, .form-select:focus {
                border-color: var(--primary-color);
                box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
            }
            
            .form-label {
                font-weight: 500;
                color: #495057;
                margin-bottom: 0.5rem;
            }
            
            .section-title {
                color: var(--secondary-color);
                font-weight: 600;
                padding-bottom: 10px;
                border-bottom: 2px solid #eee;
                margin-bottom: 20px;
            }
            
            .badge-notification {
                background-color: var(--primary-color);
                padding: 0.35em 0.65em;
                font-size: 0.75em;
            }
            
            .action-buttons .btn {
                margin-right: 5px;
                margin-bottom: 5px;
            }
            
            .status-badge {
                padding: 0.25rem 0.5rem;
                border-radius: 20px;
                font-size: 0.75rem;
                font-weight: 600;
            }
            
            .status-read {
                background-color: rgba(76, 201, 240, 0.2);
                color: #0a8ea0;
            }
            
            .status-unread {
                background-color: rgba(247, 37, 133, 0.2);
                color: #c2185b;
            }
            
            .toast-container {
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
            }
            
            .toast {
                border-radius: 10px;
                box-shadow: var(--card-shadow);
                border: none;
            }
            
            @media (max-width: 768px) {
                .action-buttons .btn {
                    width: 100%;
                    margin-right: 0;
                }
            }
        </style>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    </head>
    <body>
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="#">
                    <i class="fas fa-bell me-2"></i>Notification API
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                {{-- <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link active" href="#allNotifications">All Notifications</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#createNotification">Create New</a>
                        </li>
                    </ul>
                </div> --}}
            </div>
        </nav>

        <!-- Main Container -->
        <div class="container mt-4">
            <!-- Toast Container for Messages -->
            <div class="toast-container"></div>
            
            <!-- Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h1 class="h3 fw-bold text-dark">Notification Management</h1>
                        <span class="badge badge-notification rounded-pill bg-primary p-2" id="notificationCount">0 Notifications</span>
                    </div>
                    <p class="text-muted">Manage all your notifications with full CRUD operations</p>
                </div>
            </div>
            
            <div class="row">
                <!-- Left Column - Create & Update Forms -->
                <div class="col-lg-5 mb-4">
                    <!-- Create Notification Form -->
                    <div class="card main-card mb-4" id="createNotification">
                        <div class="card-header bg-primary text-light">
                            <h5 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Create New Notification</h5>
                        </div>
                        <div class="card-body">
                            <form id="createNotificationForm">
                                @csrf
                                <div class="mb-3">
                                    <label for="title" class="form-label">Title *</label>
                                    <input type="text" class="form-control" id="title" name="title" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="message" class="form-label">Message *</label>
                                    <textarea class="form-control" id="message" name="message" rows="3" required></textarea>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="type" class="form-label">Type</label>
                                        <select class="form-select" id="type" name="type">
                                            <option value="info">Information</option>
                                            <option value="warning">Warning</option>
                                            <option value="alert">Alert</option>
                                            <option value="success">Success</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="status" class="form-label">Status</label>
                                        <select class="form-select" id="status" name="status">
                                            <option value="unread">Unread</option>
                                            <option value="read">Read</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="is_urgent" name="is_urgent">
                                    <label class="form-check-label" for="is_urgent">Mark as urgent</label>
                                </div>
                                
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="bi bi-paper-plane me-2"></i>Create Notification
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Update Notification Form (Initially Hidden) -->
                    <div class="card main-card" id="updateNotificationFormContainer" style="display: none;">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0"><i class="bi bi-edit me-2"></i>Update Notification</h5>
                        </div>
                        <div class="card-body">
                            <form id="updateNotificationForm">
                                @csrf
                                @method('PUT')
                                <input type="hidden" id="update_id" name="id">
                                
                                <div class="mb-3">
                                    <label for="update_title" class="form-label">Title *</label>
                                    <input type="text" class="form-control" id="update_title" name="title" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="update_message" class="form-label">Message *</label>
                                    <textarea class="form-control" id="update_message" name="message" rows="3" required></textarea>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="update_type" class="form-label">Type</label>
                                        <select class="form-select" id="update_type" name="type">
                                            <option value="info">Information</option>
                                            <option value="warning">Warning</option>
                                            <option value="alert">Alert</option>
                                            <option value="success">Success</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="update_status" class="form-label">Status</label>
                                        <select class="form-select" id="update_status" name="status">
                                            <option value="unread">Unread</option>
                                            <option value="read">Read</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="update_is_urgent" name="is_urgent">
                                    <label class="form-check-label" for="update_is_urgent">Mark as urgent</label>
                                </div>
                                
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="button" class="btn btn-outline-secondary" id="cancelUpdate">
                                        <i class="fas fa-times me-2"></i>Cancel
                                    </button>
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-save me-2"></i>Update Notification
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Right Column - Notifications List & Single View -->
                <div class="col-lg-7">
                    <!-- All Notifications -->
                    <div class="card main-card mb-4" id="allNotifications">
                        <div class="card-header text-light">
                            <h5 class="mb-0"><i class="bi bi-list me-2"></i>All Notifications</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-3">
                                <div class="input-group w-auto">
                                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                                    <input type="text" class="form-control" id="searchNotifications" placeholder="Search notifications...">
                                </div>
                                <button class="btn btn-outline-primary" id="refreshNotifications">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                            
                            <div id="notificationsList" class="mt-3">
                                <!-- Notifications will be loaded here -->
                                <div class="text-center py-5">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <p class="mt-3 text-muted">Loading notifications...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Single Notification View -->
                    <div class="card main-card" id="singleNotificationView" style="display: none;">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-eye me-2"></i>Notification Details</h5>
                        </div>
                        <div class="card-body">
                            <div id="notificationDetails">
                                <!-- Single notification details will be shown here -->
                            </div>
                            <div class="action-buttons mt-4">
                                <button class="btn btn-primary" id="editNotificationBtn">
                                    <i class="fas fa-edit me-2"></i>Edit
                                </button>
                                <button class="btn btn-outline-danger" id="deleteNotificationBtn">
                                    <i class="fas fa-trash me-2"></i>Delete
                                </button>
                                <button class="btn btn-outline-secondary" id="backToListBtn">
                                    <i class="fas fa-arrow-left me-2"></i>Back to List
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap JS Bundle -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
        
        <!-- Custom JavaScript -->
        <script>
            // Base API URL
            const API_BASE_URL = 'http://127.0.0.1:8000/api/notifications';
            
            // DOM Elements
            let currentNotificationId = null;
            
            // Initialize when page loads
            document.addEventListener('DOMContentLoaded', function() {
                loadNotifications();
                setupEventListeners();
            });
            
            // Setup all event listeners
            function setupEventListeners() {
                // Create form submission
                document.getElementById('createNotificationForm').addEventListener('submit', handleCreate);
                
                // Update form submission
                document.getElementById('updateNotificationForm').addEventListener('submit', handleUpdate);
                
                // Cancel update button
                document.getElementById('cancelUpdate').addEventListener('click', cancelUpdate);
                
                // Refresh notifications button
                document.getElementById('refreshNotifications').addEventListener('click', loadNotifications);
                
                // Search input
                document.getElementById('searchNotifications').addEventListener('input', handleSearch);
                
                // Back to list button
                document.getElementById('backToListBtn').addEventListener('click', showNotificationsList);
                
                // Edit and delete buttons (will be dynamically bound)
            }
            
            // Show toast message
            function showToast(message, type = 'success') {
                const toastContainer = document.querySelector('.toast-container');
                const toastId = 'toast-' + Date.now();
                
                const toastHtml = `
                    <div id="${toastId}" class="toast align-items-center text-bg-${type} border-0" role="alert">
                        <div class="d-flex">
                            <div class="toast-body">
                                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} me-2"></i>
                                ${message}
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                        </div>
                    </div>
                `;
                
                toastContainer.insertAdjacentHTML('beforeend', toastHtml);
                const toastElement = document.getElementById(toastId);
                const toast = new bootstrap.Toast(toastElement, { delay: 3000 });
                toast.show();
                
                // Remove toast after it hides
                toastElement.addEventListener('hidden.bs.toast', function() {
                    toastElement.remove();
                });
            }
            
            // Load all notifications
            async function loadNotifications() {
                try {
                    const response = await fetch(API_BASE_URL);
                    if (!response.ok) throw new Error('Failed to fetch notifications');
                    
                    const notifications = await response.json();
                    renderNotificationsList(notifications);
                    updateNotificationCount(notifications.length);
                } catch (error) {
                    console.error('Error loading notifications:', error);
                    showToast('Error loading notifications: ' + error.message, 'danger');
                    
                    // Show error in notifications list
                    document.getElementById('notificationsList').innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Failed to load notifications. Please try again.
                        </div>
                    `;
                }
            }
            
            // Render notifications list
            function renderNotificationsList(notifications) {
                const container = document.getElementById('notificationsList');
                
                if (!notifications || notifications.length === 0) {
                    container.innerHTML = `
                        <div class="text-center py-5">
                            <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No notifications yet</h5>
                            <p class="text-muted">Create your first notification using the form on the left!</p>
                        </div>
                    `;
                    return;
                }
                
                let html = '';
                
                notifications.forEach(notification => {
                    const isUnread = notification.status === 'unread';
                    const isUrgent = notification.is_urgent;
                    const typeIcon = getTypeIcon(notification.type);
                    
                    html += `
                        <div class="card notification-item ${isUnread ? 'unread' : ''} mb-3" data-id="${notification.id}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="card-title mb-1">
                                            <i class="${typeIcon} me-2"></i>
                                            ${notification.title}
                                            ${isUrgent ? '<span class="badge bg-danger ms-2">URGENT</span>' : ''}
                                        </h6>
                                        <p class="card-text text-muted mb-2">${notification.message.substring(0, 100)}${notification.message.length > 100 ? '...' : ''}</p>
                                        <div class="d-flex align-items-center">
                                            <span class="status-badge ${isUnread ? 'status-unread' : 'status-read'} me-2">
                                                ${isUnread ? 'Unread' : 'Read'}
                                            </span>
                                            <small class="text-muted">
                                                <i class="far fa-clock me-1"></i>
                                                ${formatDate(notification.created_at)}
                                            </small>
                                        </div>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item view-notification" href="#" data-id="${notification.id}">
                                                    <i class="fas fa-eye me-2"></i>View
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item edit-notification" href="#" data-id="${notification.id}">
                                                    <i class="fas fa-edit me-2"></i>Edit
                                                </a>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <a class="dropdown-item text-danger delete-notification" href="#" data-id="${notification.id}">
                                                    <i class="fas fa-trash me-2"></i>Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                container.innerHTML = html;
                
                // Add event listeners to dynamically created elements
                document.querySelectorAll('.view-notification').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        const id = this.getAttribute('data-id');
                        viewNotification(id);
                    });
                });
                
                document.querySelectorAll('.edit-notification').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        const id = this.getAttribute('data-id');
                        editNotification(id);
                    });
                });
                
                document.querySelectorAll('.delete-notification').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        const id = this.getAttribute('data-id');
                        deleteNotification(id);
                    });
                });
            }
            
            // Handle create form submission
            async function handleCreate(e) {
                e.preventDefault();
                
                const form = e.target;
                const formData = new FormData(form);
                
                // Convert FormData to JSON
                const data = {};
                formData.forEach((value, key) => {
                    data[key] = value;
                });
                
                // Convert checkbox to boolean
                data.is_urgent = form.is_urgent.checked;
                
                try {
                    const response = await fetch(API_BASE_URL, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        },
                        body: JSON.stringify(data)
                    });
                    
                    const result = await response.json();
                    
                    if (response.ok) {
                        showToast('Notification created successfully!', 'success');
                        form.reset();
                        loadNotifications();
                    } else {
                        throw new Error(result.message || 'Failed to create notification');
                    }
                } catch (error) {
                    console.error('Error creating notification:', error);
                    showToast('Error creating notification: ' + error.message, 'danger');
                }
            }
            
            // Handle update form submission
            async function handleUpdate(e) {
                e.preventDefault();
                
                if (!currentNotificationId) return;
                
                const form = e.target;
                const formData = new FormData(form);
                
                const data = {};
                formData.forEach((value, key) => {
                    data[key] = value;
                });
                
                // Convert checkbox to boolean
                data.is_urgent = form.is_urgent.checked;
                
                try {
                    const response = await fetch(`${API_BASE_URL}/${currentNotificationId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'X-HTTP-Method-Override': 'PUT'
                        },
                        body: JSON.stringify(data)
                    });
                    
                    const result = await response.json();
                    
                    if (response.ok) {
                        showToast('Notification updated successfully!', 'success');
                        cancelUpdate();
                        loadNotifications();
                        showNotificationsList();
                    } else {
                        throw new Error(result.message || 'Failed to update notification');
                    }
                } catch (error) {
                    console.error('Error updating notification:', error);
                    showToast('Error updating notification: ' + error.message, 'danger');
                }
            }
            
            // View single notification
            async function viewNotification(id) {
                try {
                    const response = await fetch(`${API_BASE_URL}/${id}`);
                    if (!response.ok) throw new Error('Failed to fetch notification');
                    
                    const notification = await response.json();
                    renderSingleNotification(notification);
                    
                    // Update current notification ID
                    currentNotificationId = id;
                    
                    // Bind delete button
                    document.getElementById('deleteNotificationBtn').addEventListener('click', function() {
                        deleteNotification(id);
                    });
                    
                    // Bind edit button
                    document.getElementById('editNotificationBtn').addEventListener('click', function() {
                        editNotification(id);
                    });
                    
                    // Show single view, hide list
                    document.getElementById('allNotifications').style.display = 'none';
                    document.getElementById('singleNotificationView').style.display = 'block';
                    
                    // Scroll to single view
                    document.getElementById('singleNotificationView').scrollIntoView({ behavior: 'smooth' });
                } catch (error) {
                    console.error('Error viewing notification:', error);
                    showToast('Error loading notification: ' + error.message, 'danger');
                }
            }
            
            // Render single notification view
            function renderSingleNotification(notification) {
                const container = document.getElementById('notificationDetails');
                const isUnread = notification.status === 'unread';
                const isUrgent = notification.is_urgent;
                const typeIcon = getTypeIcon(notification.type);
                const typeLabel = notification.type.charAt(0).toUpperCase() + notification.type.slice(1);
                
                container.innerHTML = `
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h4 class="mb-0">
                                <i class="${typeIcon} me-2"></i>${notification.title}
                                ${isUrgent ? '<span class="badge bg-danger ms-2">URGENT</span>' : ''}
                            </h4>
                            <span class="status-badge ${isUnread ? 'status-unread' : 'status-read'}">
                                ${isUnread ? 'Unread' : 'Read'}
                            </span>
                        </div>
                        
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">Message</h6>
                            <div class="p-3 bg-light rounded">
                                ${notification.message}
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted mb-2">Type</h6>
                                <p><i class="${typeIcon} me-2"></i>${typeLabel}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted mb-2">Status</h6>
                                <p>${isUnread ? '<i class="fas fa-envelope me-2"></i>Unread' : '<i class="fas fa-envelope-open me-2"></i>Read'}</p>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted mb-2">Created</h6>
                                <p><i class="far fa-calendar-plus me-2"></i>${formatDate(notification.created_at)}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted mb-2">Last Updated</h6>
                                <p><i class="far fa-calendar-check me-2"></i>${formatDate(notification.updated_at)}</p>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <h6 class="text-muted mb-2">Notification ID</h6>
                            <code class="p-2 bg-light rounded d-inline-block">${notification.id}</code>
                        </div>
                    </div>
                `;
            }
            
            // Edit notification
            async function editNotification(id) {
                try {
                    const response = await fetch(`${API_BASE_URL}/${id}`);
                    if (!response.ok) throw new Error('Failed to fetch notification');
                    
                    const notification = await response.json();
                    
                    // Populate update form
                    document.getElementById('update_id').value = notification.id;
                    document.getElementById('update_title').value = notification.title;
                    document.getElementById('update_message').value = notification.message;
                    document.getElementById('update_type').value = notification.type;
                    document.getElementById('update_status').value = notification.status;
                    document.getElementById('update_is_urgent').checked = notification.is_urgent;
                    
                    // Update current notification ID
                    currentNotificationId = id;
                    
                    // Show update form, hide create form
                    document.getElementById('createNotification').style.display = 'none';
                    document.getElementById('updateNotificationFormContainer').style.display = 'block';
                    
                    // Scroll to update form
                    document.getElementById('updateNotificationFormContainer').scrollIntoView({ behavior: 'smooth' });
                } catch (error) {
                    console.error('Error loading notification for edit:', error);
                    showToast('Error loading notification: ' + error.message, 'danger');
                }
            }
            
            // Cancel update
            function cancelUpdate() {
                document.getElementById('updateNotificationFormContainer').style.display = 'none';
                document.getElementById('createNotification').style.display = 'block';
                document.getElementById('updateNotificationForm').reset();
                currentNotificationId = null;
            }
            
            // Delete notification
            async function deleteNotification(id) {
                if (!confirm('Are you sure you want to delete this notification?')) {
                    return;
                }
                
                try {
                    const response = await fetch(`${API_BASE_URL}/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        }
                    });
                    
                    if (response.ok) {
                        showToast('Notification deleted successfully!', 'success');
                        loadNotifications();
                        showNotificationsList();
                    } else {
                        const result = await response.json();
                        throw new Error(result.message || 'Failed to delete notification');
                    }
                } catch (error) {
                    console.error('Error deleting notification:', error);
                    showToast('Error deleting notification: ' + error.message, 'danger');
                }
            }
            
            // Show notifications list (hide single view)
            function showNotificationsList() {
                document.getElementById('allNotifications').style.display = 'block';
                document.getElementById('singleNotificationView').style.display = 'none';
                currentNotificationId = null;
            }
            
            // Handle search
            function handleSearch(e) {
                const searchTerm = e.target.value.toLowerCase();
                const notificationCards = document.querySelectorAll('.notification-item');
                
                notificationCards.forEach(card => {
                    const title = card.querySelector('.card-title').textContent.toLowerCase();
                    const message = card.querySelector('.card-text').textContent.toLowerCase();
                    
                    if (title.includes(searchTerm) || message.includes(searchTerm)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            }
            
            // Update notification count
            function updateNotificationCount(count) {
                const countElement = document.getElementById('notificationCount');
                countElement.textContent = `${count} Notification${count !== 1 ? 's' : ''}`;
            }
            
            // Helper: Get icon for notification type
            function getTypeIcon(type) {
                switch(type) {
                    case 'info': return 'fas fa-info-circle text-primary';
                    case 'warning': return 'fas fa-exclamation-triangle text-warning';
                    case 'alert': return 'fas fa-bell text-danger';
                    case 'success': return 'fas fa-check-circle text-success';
                    default: return 'fas fa-bell text-primary';
                }
            }
            
            // Helper: Format date
            function formatDate(dateString) {
                const date = new Date(dateString);
                return date.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    </body>
</html>