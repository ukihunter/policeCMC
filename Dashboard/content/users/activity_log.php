<?php
// Check if user is admin
if (!isset($_SESSION['user_id'])) {
    echo '<p>Please log in to access this page.</p>';
    exit;
}

require_once __DIR__ . '/../../../config/db.php';

$current_user_id = $_SESSION['user_id'];
$sql = "SELECT role FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $current_user_id);
$stmt->execute();
$result = $stmt->get_result();
$current_user = $result->fetch_assoc();

if ($current_user['role'] !== 'admin') {
    echo '<div class="access-denied">';
    echo '<i class="fas fa-lock fa-3x"></i>';
    echo '<h3>Access Denied</h3>';
    echo '<p>Only administrators can view activity logs.</p>';
    echo '</div>';
    exit;
}
?>

<div class="activity-container">
    <div class="section-card">
        <div class="section-header">
            <div>
                <h3><i class="fas fa-history"></i> System Activity Log</h3>
                <p class="section-description">Monitor all system activities and user actions</p>
            </div>
            <div class="activity-filters">
                <select id="activityTypeFilter" onchange="filterActivities()">
                    <option value="all">All Activities</option>
                    <option value="case_added">Case Added</option>
                    <option value="case_edited">Case Edited</option>
                    <option value="case_printed">Case Printed</option>
                    <option value="case_deleted">Case Deleted</option>
                    <option value="user_added">User Added</option>
                    <option value="user_edited">User Edited</option>
                    <option value="user_deleted">User Deleted</option>
                    <option value="password_changed">Password Changed</option>
                </select>

                <select id="activityUserFilter" onchange="filterActivities()">
                    <option value="all">All Users</option>
                </select>

                <input type="date" id="activityDateFilter" onchange="filterActivities()" placeholder="Filter by date">

                <button class="btn-secondary" onclick="resetFilters()">
                    <i class="fas fa-redo"></i> Reset
                </button>
            </div>
        </div>

        <!-- Activity Statistics -->
        <div class="activity-stats">
            <div class="stat-card">
                <div class="stat-icon" style="background: #e3f2fd;">
                    <i class="fas fa-chart-line" style="color: #2196f3;"></i>
                </div>
                <div class="stat-info">
                    <h4 id="totalActivities">0</h4>
                    <p>Total Activities</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: #e8f5e9;">
                    <i class="fas fa-plus-circle" style="color: #4caf50;"></i>
                </div>
                <div class="stat-info">
                    <h4 id="casesAdded">0</h4>
                    <p>Cases Added</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: #fff3e0;">
                    <i class="fas fa-edit" style="color: #ff9800;"></i>
                </div>
                <div class="stat-info">
                    <h4 id="casesEdited">0</h4>
                    <p>Cases Edited</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: #e1f5fe;">
                    <i class="fas fa-print" style="color: #00bcd4;"></i>
                </div>
                <div class="stat-info">
                    <h4 id="casesPrinted">0</h4>
                    <p>Cases Printed</p>
                </div>
            </div>
        </div>

        <!-- Activity Timeline -->
        <div class="activity-timeline" id="activityTimeline">
            <div class="loading-spinner">
                <i class="fas fa-spinner fa-spin fa-2x"></i>
                <p>Loading activities...</p>
            </div>
        </div>

        <!-- Pagination Controls -->
        <div class="pagination-container" id="paginationContainer" style="display: none;">
            <div class="pagination-info">
                <span id="paginationInfo">Showing 1-5 of 0 activities</span>
            </div>
            <div class="pagination-buttons">
                <button class="btn-pagination" id="prevPageBtn" onclick="goToPreviousPage()" disabled>
                    <i class="fas fa-chevron-left"></i> Previous
                </button>
                <span class="page-numbers" id="pageNumbers"></span>
                <button class="btn-pagination" id="nextPageBtn" onclick="goToNextPage()">
                    Next <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>

        <!-- Load More Button (Hidden - replaced by pagination) -->
        <div class="load-more-container" id="loadMoreContainer" style="display: none;">
            <button class="btn-secondary" onclick="loadMoreActivities()">
                <i class="fas fa-chevron-down"></i> Load More
            </button>
        </div>
    </div>
</div>

<style>
    .access-denied {
        text-align: center;
        padding: 60px 20px;
        color: #6c757d;
    }

    .access-denied i {
        color: #dc3545;
        margin-bottom: 20px;
    }

    .access-denied h3 {
        color: #0a1628;
        margin-bottom: 10px;
    }

    .activity-container {
        padding: 0;
    }

    .activity-filters {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .activity-filters select,
    .activity-filters input {
        padding: 8px 12px;
        border: 1px solid #ced4da;
        border-radius: 6px;
        font-size: 14px;
    }

    .activity-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin: 25px 0;
    }

    .stat-card {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .stat-info h4 {
        margin: 0;
        font-size: 28px;
        color: #0a1628;
    }

    .stat-info p {
        margin: 5px 0 0 0;
        color: #6c757d;
        font-size: 13px;
    }

    .activity-timeline {
        margin-top: 25px;
        position: relative;
    }

    .timeline-item {
        position: relative;
        padding-left: 45px;
        padding-bottom: 30px;
        border-left: 2px solid #e9ecef;
    }

    .timeline-item:last-child {
        border-left-color: transparent;
        padding-bottom: 0;
    }

    .timeline-icon {
        position: absolute;
        left: -12px;
        top: 0;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .timeline-content {
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 15px;
        transition: all 0.3s ease;
    }

    .timeline-content:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transform: translateX(5px);
    }

    .timeline-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 10px;
    }

    .timeline-title {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .activity-badge {
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .timeline-user {
        font-weight: 600;
        color: #0a1628;
    }

    .timeline-time {
        color: #6c757d;
        font-size: 12px;
    }

    .timeline-description {
        color: #495057;
        margin: 10px 0;
        font-size: 14px;
    }

    .timeline-meta {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid #f1f3f5;
        font-size: 12px;
        color: #6c757d;
    }

    .timeline-meta-item {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .timeline-meta-item i {
        color: #4a9eff;
    }

    .loading-spinner {
        text-align: center;
        padding: 40px;
        color: #6c757d;
    }

    .loading-spinner i {
        color: #4a9eff;
        margin-bottom: 15px;
    }

    .no-activities {
        text-align: center;
        padding: 60px 20px;
        color: #6c757d;
    }

    .no-activities i {
        font-size: 48px;
        color: #dee2e6;
        margin-bottom: 15px;
    }

    .load-more-container {
        text-align: center;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #e9ecef;
    }

    .pagination-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 25px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 8px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .pagination-info {
        color: #6c757d;
        font-size: 14px;
        font-weight: 500;
    }

    .pagination-buttons {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-pagination {
        padding: 8px 16px;
        background: white;
        border: 1px solid #ced4da;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        color: #495057;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .btn-pagination:hover:not(:disabled) {
        background: #4a9eff;
        color: white;
        border-color: #4a9eff;
        transform: translateY(-1px);
    }

    .btn-pagination:disabled {
        background: #e9ecef;
        color: #adb5bd;
        cursor: not-allowed;
        opacity: 0.6;
    }

    .page-numbers {
        display: flex;
        gap: 5px;
    }

    .page-number {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #ced4da;
        border-radius: 6px;
        background: white;
        color: #495057;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.2s ease;
    }

    .page-number:hover {
        background: #f8f9fa;
        border-color: #4a9eff;
    }

    .page-number.active {
        background: #4a9eff;
        color: white;
        border-color: #4a9eff;
        font-weight: 600;
    }

    .page-ellipsis {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
    }

    @media (max-width: 768px) {
        .activity-filters {
            flex-direction: column;
        }

        .activity-filters select,
        .activity-filters input,
        .activity-filters button {
            width: 100%;
        }

        .timeline-header {
            flex-direction: column;
            gap: 10px;
        }
    }
</style>

<script>
    let currentPage = 1;
    let totalPages = 1;
    let currentFilters = {
        type: 'all',
        user: 'all',
        date: ''
    };
    let allActivities = [];

    // Load activities on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadActivities();
        loadUserFilter();
    });

    async function loadActivities(page = 1) {
        try {
            const params = new URLSearchParams({
                page: page,
                type: currentFilters.type,
                user: currentFilters.user,
                date: currentFilters.date
            });

            const response = await fetch(`content/users/get_activities.php?${params}`);
            const data = await response.json();

            if (data.success) {
                allActivities = data.activities; // Replace instead of append
                displayActivities(data.activities);

                updateStatistics(data.stats);
                currentPage = page;
                totalPages = data.total_pages;

                // Update pagination
                updatePagination(data.page, data.total_pages, data.total_count);
            } else {
                showNotification('Error loading activities', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('Error loading activities', 'error');
        }
    }

    function updatePagination(currentPage, totalPages, totalCount) {
        const paginationContainer = document.getElementById('paginationContainer');
        const prevBtn = document.getElementById('prevPageBtn');
        const nextBtn = document.getElementById('nextPageBtn');
        const paginationInfo = document.getElementById('paginationInfo');
        const pageNumbers = document.getElementById('pageNumbers');

        if (totalCount === 0) {
            paginationContainer.style.display = 'none';
            return;
        }

        paginationContainer.style.display = 'flex';

        // Update info text
        const startItem = (currentPage - 1) * 5 + 1;
        const endItem = Math.min(currentPage * 5, totalCount);
        paginationInfo.textContent = `Showing ${startItem}-${endItem} of ${totalCount} activities`;

        // Update prev/next buttons
        prevBtn.disabled = currentPage === 1;
        nextBtn.disabled = currentPage === totalPages;

        // Generate page numbers
        pageNumbers.innerHTML = generatePageNumbers(currentPage, totalPages);
    }

    function generatePageNumbers(current, total) {
        let pages = '';
        const maxVisible = 5;

        if (total <= maxVisible) {
            // Show all pages
            for (let i = 1; i <= total; i++) {
                pages += `<span class="page-number ${i === current ? 'active' : ''}" onclick="goToPage(${i})">${i}</span>`;
            }
        } else {
            // Show first page
            pages += `<span class="page-number ${current === 1 ? 'active' : ''}" onclick="goToPage(1)">1</span>`;

            if (current > 3) {
                pages += '<span class="page-ellipsis">...</span>';
            }

            // Show pages around current
            let start = Math.max(2, current - 1);
            let end = Math.min(total - 1, current + 1);

            for (let i = start; i <= end; i++) {
                pages += `<span class="page-number ${i === current ? 'active' : ''}" onclick="goToPage(${i})">${i}</span>`;
            }

            if (current < total - 2) {
                pages += '<span class="page-ellipsis">...</span>';
            }

            // Show last page
            pages += `<span class="page-number ${current === total ? 'active' : ''}" onclick="goToPage(${total})">${total}</span>`;
        }

        return pages;
    }

    function goToPage(page) {
        if (page < 1 || page > totalPages) return;
        loadActivities(page);
    }

    function goToPreviousPage() {
        if (currentPage > 1) {
            loadActivities(currentPage - 1);
        }
    }

    function goToNextPage() {
        if (currentPage < totalPages) {
            loadActivities(currentPage + 1);
        }
    }

    function displayActivities(activities) {
        const timeline = document.getElementById('activityTimeline');

        if (activities.length === 0) {
            timeline.innerHTML = `
            <div class="no-activities">
                <i class="fas fa-inbox"></i>
                <h4>No Activities Found</h4>
                <p>No system activities match your current filters.</p>
            </div>
        `;
            return;
        }

        timeline.innerHTML = activities.map(activity => {
            const icon = getActivityIcon(activity.activity_type);
            const color = getActivityColor(activity.activity_type);
            const label = getActivityLabel(activity.activity_type);
            const timeAgo = getTimeAgo(activity.created_at);

            return `
            <div class="timeline-item">
                <div class="timeline-icon" style="background: ${color};">
                    <i class="fas ${icon}"></i>
                </div>
                <div class="timeline-content">
                    <div class="timeline-header">
                        <div class="timeline-title">
                            <span class="timeline-user">${escapeHtml(activity.user_name)}</span>
                            <span class="activity-badge" style="background: ${color}20; color: ${color};">
                                ${label}
                            </span>
                        </div>
                        <span class="timeline-time">${timeAgo}</span>
                    </div>
                    <div class="timeline-description">
                        ${escapeHtml(activity.description)}
                    </div>
                    <div class="timeline-meta">
                        ${activity.case_number ? `
                            <div class="timeline-meta-item">
                                <i class="fas fa-file-alt"></i>
                                <span>Case: ${escapeHtml(activity.case_number)}</span>
                            </div>
                        ` : ''}
                        <div class="timeline-meta-item">
                            <i class="fas fa-network-wired"></i>
                            <span>IP: ${escapeHtml(activity.ip_address || 'Unknown')}</span>
                        </div>
                        <div class="timeline-meta-item">
                            <i class="fas fa-clock"></i>
                            <span>${new Date(activity.created_at).toLocaleString()}</span>
                        </div>
                    </div>
                </div>
            </div>
        `;
        }).join('');
    }

    function updateStatistics(stats) {
        document.getElementById('totalActivities').textContent = stats.total || 0;
        document.getElementById('casesAdded').textContent = stats.case_added || 0;
        document.getElementById('casesEdited').textContent = stats.case_edited || 0;
        document.getElementById('casesPrinted').textContent = stats.case_printed || 0;
    }

    async function loadUserFilter() {
        try {
            const response = await fetch('content/users/get_users.php');
            const data = await response.json();

            if (data.success) {
                const userFilter = document.getElementById('activityUserFilter');
                data.users.forEach(user => {
                    const option = document.createElement('option');
                    option.value = user.id;
                    option.textContent = user.full_name;
                    userFilter.appendChild(option);
                });
            }
        } catch (error) {
            console.error('Error loading users:', error);
        }
    }

    function filterActivities() {
        currentFilters.type = document.getElementById('activityTypeFilter').value;
        currentFilters.user = document.getElementById('activityUserFilter').value;
        currentFilters.date = document.getElementById('activityDateFilter').value;

        currentPage = 1;
        loadActivities(1);
    }

    function resetFilters() {
        document.getElementById('activityTypeFilter').value = 'all';
        document.getElementById('activityUserFilter').value = 'all';
        document.getElementById('activityDateFilter').value = '';
        filterActivities();
    }

    // Remove the old loadMoreActivities function since we're using pagination now

    function getActivityIcon(type) {
        const icons = {
            'case_added': 'fa-plus-circle',
            'case_edited': 'fa-edit',
            'case_printed': 'fa-print',
            'case_deleted': 'fa-trash',
            'user_added': 'fa-user-plus',
            'user_edited': 'fa-user-edit',
            'user_deleted': 'fa-user-times',
            'password_changed': 'fa-key'
        };
        return icons[type] || 'fa-circle';
    }

    function getActivityColor(type) {
        const colors = {
            'case_added': '#28a745',
            'case_edited': '#ffc107',
            'case_printed': '#17a2b8',
            'case_deleted': '#dc3545',
            'user_added': '#28a745',
            'user_edited': '#ffc107',
            'user_deleted': '#dc3545',
            'password_changed': '#6f42c1'
        };
        return colors[type] || '#6c757d';
    }

    function getActivityLabel(type) {
        const labels = {
            'case_added': 'Case Added',
            'case_edited': 'Case Edited',
            'case_printed': 'Case Printed',
            'case_deleted': 'Case Deleted',
            'user_added': 'User Added',
            'user_edited': 'User Edited',
            'user_deleted': 'User Deleted',
            'password_changed': 'Password Changed'
        };
        return labels[type] || type.replace('_', ' ');
    }

    function getTimeAgo(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const seconds = Math.floor((now - date) / 1000);

        const intervals = {
            year: 31536000,
            month: 2592000,
            week: 604800,
            day: 86400,
            hour: 3600,
            minute: 60
        };

        for (const [unit, secondsInUnit] of Object.entries(intervals)) {
            const interval = Math.floor(seconds / secondsInUnit);
            if (interval >= 1) {
                return interval === 1 ? `1 ${unit} ago` : `${interval} ${unit}s ago`;
            }
        }

        return 'Just now';
    }

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
</script>