<?php
session_start();
require_once('../../../config/db.php');

// Fetch all cases from database with user info
$sql = "SELECT c.*, 
        u1.full_name as created_by_name,
        u2.full_name as updated_by_name
        FROM cases c
        LEFT JOIN users u1 ON c.created_by = u1.id
        LEFT JOIN users u2 ON c.updated_by = u2.id
        ORDER BY c.created_at DESC";
$result = $conn->query($sql);
$cases = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $cases[] = $row;
    }
}
?>

<div class="cases-header">
    <h2><i class="fas fa-folder-open"></i> All Cases</h2>
    <div class="header-actions">
        <div class="filter-section">
            <input type="text" id="searchInput" placeholder="Search by case number, register, information book..." onkeyup="filterCases()">
            <div class="date-filters">
                <div class="date-filter-group">
                    <label>Previous Date From:</label>
                    <input type="date" id="prevDateFrom" onchange="filterCases()">
                </div>
                <div class="date-filter-group">
                    <label>To:</label>
                    <input type="date" id="prevDateTo" onchange="filterCases()">
                </div>
                <button class="btn-clear-filter" onclick="clearFilters()">
                    <i class="fas fa-times"></i> Clear Filters
                </button>
            </div>
        </div>
    </div>
</div>

<?php if (empty($cases)): ?>
    <div class="no-cases">
        <div><i class="fas fa-folder-open"></i></div>
        <h3>No Cases Found</h3>
        <p>There are no cases in the system yet. Click "Add New Case" to create one.</p>
    </div>
<?php else: ?>
    <div class="table-container">
        <table class="cases-table" id="casesTable">
            <thead>
                <tr>
                    <th>Case Number</th>
                    <th>Previous Date</th>
                    <th>Register Number</th>
                    <th>Information Book</th>
                    <th>Opens</th>
                    <th>Progress</th>
                    <th>Results</th>
                    <th>Next Date</th>
                    <th>Suspects</th>
                    <th>Witnesses</th>
                    <th>Status</th>
                    <th>Created By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cases as $case): ?>
                    <tr data-case-number="<?php echo htmlspecialchars($case['case_number']); ?>"
                        data-register="<?php echo htmlspecialchars($case['register_number']); ?>"
                        data-info-book="<?php echo htmlspecialchars($case['information_book']); ?>"
                        data-prev-date="<?php echo $case['previous_date'] ?? ''; ?>">
                        <td><strong><?php echo htmlspecialchars($case['case_number']); ?></strong></td>
                        <td><?php echo $case['previous_date'] ? date('d M Y', strtotime($case['previous_date'])) : '-'; ?></td>
                        <td><?php echo htmlspecialchars($case['register_number']); ?></td>
                        <td>
                            <div class="cell-content">
                                <?php echo htmlspecialchars(substr($case['information_book'], 0, 60)) . (strlen($case['information_book']) > 60 ? '...' : ''); ?>
                            </div>
                        </td>
                        <td>
                            <div class="cell-content">
                                <?php echo htmlspecialchars(substr($case['opens'] ?? '-', 0, 50)) . (strlen($case['opens'] ?? '') > 50 ? '...' : ''); ?>
                            </div>
                        </td>
                        <td>
                            <div class="cell-content">
                                <?php echo htmlspecialchars(substr($case['progress'] ?? '-', 0, 50)) . (strlen($case['progress'] ?? '') > 50 ? '...' : ''); ?>
                            </div>
                        </td>
                        <td>
                            <div class="cell-content">
                                <?php echo htmlspecialchars(substr($case['results'] ?? '-', 0, 50)) . (strlen($case['results'] ?? '') > 50 ? '...' : ''); ?>
                            </div>
                        </td>
                        <td>-</td>
                        <td>
                            <div class="cell-content">
                                <?php
                                $suspects = json_decode($case['suspect_data'] ?? '[]', true);
                                if (!empty($suspects)) {
                                    echo '<strong>' . count($suspects) . ' suspect(s)</strong><br>';
                                    echo htmlspecialchars($suspects[0]['name'] ?? '');
                                    if (count($suspects) > 1) echo ' +' . (count($suspects) - 1) . ' more';
                                } else {
                                    echo '-';
                                }
                                ?>
                            </div>
                        </td>
                        <td>
                            <div class="cell-content">
                                <?php
                                $witnesses = json_decode($case['witness_data'] ?? '[]', true);
                                if (!empty($witnesses)) {
                                    echo '<strong>' . count($witnesses) . ' witness(es)</strong><br>';
                                    echo htmlspecialchars($witnesses[0]['name'] ?? '');
                                    if (count($witnesses) > 1) echo ' +' . (count($witnesses) - 1) . ' more';
                                } else {
                                    echo '-';
                                }
                                ?>
                            </div>
                        </td>
                        <td>
                            <?php
                            $hasResults = !empty($case['results']);
                            $statusClass = $hasResults ? 'status-completed' : 'status-pending';
                            $statusText = $hasResults ? 'Completed' : 'In Progress';
                            echo "<span class='status-badge $statusClass'>$statusText</span>";
                            ?>
                        </td>
                        <td>
                            <div class="user-info">
                                <?php echo htmlspecialchars($case['created_by_name'] ?? 'Unknown'); ?><br>
                                <small><?php echo date('d M Y', strtotime($case['created_at'])); ?></small>
                            </div>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-view" onclick="viewCase(<?php echo $case['id']; ?>)" title="View Full Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn-edit" onclick="editCase(<?php echo $case['id']; ?>)" title="Edit Case">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<!-- Case Details Modal -->
<div id="caseModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2><i class="fas fa-file-alt"></i> Case Details</h2>
            <span class="close-modal" onclick="closeModal()">&times;</span>
        </div>
        <div class="modal-body" id="modalBody">
            <div class="loading">Loading...</div>
        </div>
    </div>
</div>

<script>
    // Define functions immediately and attach to window
    window.filterCases = function() {
        const searchInput = document.getElementById('searchInput').value.toUpperCase();
        const prevDateFrom = document.getElementById('prevDateFrom').value;
        const prevDateTo = document.getElementById('prevDateTo').value;
        const table = document.getElementById('casesTable');
        const tr = table.getElementsByTagName('tr');

        for (let i = 1; i < tr.length; i++) {
            const row = tr[i];
            const caseNumber = row.dataset.caseNumber || '';
            const register = row.dataset.register || '';
            const infoBook = row.dataset.infoBook || '';
            const prevDate = row.dataset.prevDate || '';

            // Text search
            const searchText = (caseNumber + ' ' + register + ' ' + infoBook).toUpperCase();
            const matchesSearch = searchInput === '' || searchText.includes(searchInput);

            // Date range filter
            let matchesDateRange = true;
            if (prevDate && (prevDateFrom || prevDateTo)) {
                const rowDate = new Date(prevDate);

                if (prevDateFrom && prevDateTo) {
                    const dateFrom = new Date(prevDateFrom);
                    const dateTo = new Date(prevDateTo);
                    matchesDateRange = rowDate >= dateFrom && rowDate <= dateTo;
                } else if (prevDateFrom) {
                    const dateFrom = new Date(prevDateFrom);
                    matchesDateRange = rowDate >= dateFrom;
                } else if (prevDateTo) {
                    const dateTo = new Date(prevDateTo);
                    matchesDateRange = rowDate <= dateTo;
                }
            } else if (prevDateFrom || prevDateTo) {
                // If row has no date but filter is set, hide it
                if (prevDate === '') {
                    matchesDateRange = false;
                }
            }

            // Show/hide row
            if (matchesSearch && matchesDateRange) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    }

    window.clearFilters = function() {
        document.getElementById('searchInput').value = '';
        document.getElementById('prevDateFrom').value = '';
        document.getElementById('prevDateTo').value = '';
        window.filterCases();
    }

    window.viewCase = function(caseId) {
        const modal = document.getElementById('caseModal');
        const modalBody = document.getElementById('modalBody');

        if (!modal || !modalBody) {
            console.error('Modal elements not found');
            return;
        }

        modal.style.display = 'block';
        modalBody.innerHTML = '<div class="loading"><i class="fas fa-spinner fa-spin"></i> Loading...</div>';

        // Fetch case details - using correct relative path
        fetch('content/allCases/get_case_details.php?id=' + caseId)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    window.displayCaseDetails(data.case);
                } else {
                    modalBody.innerHTML = '<div class="error">Error: ' + (data.message || 'Case not found') + '</div>';
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                modalBody.innerHTML = '<div class="error">Error: ' + error.message + '</div>';
            });
    }

    window.displayCaseDetails = function(caseData) {
        const suspects = JSON.parse(caseData.suspect_data || '[]');
        const witnesses = JSON.parse(caseData.witness_data || '[]');

        let html = `
            <div class="case-details">
                <div class="detail-section">
                    <h3><i class="fas fa-info-circle"></i> Basic Information</h3>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label>Case ID:</label>
                            <span><strong>#${caseData.id}</strong></span>
                        </div>
                        <div class="detail-item">
                            <label>Case Number:</label>
                            <span><strong>${caseData.case_number}</strong></span>
                        </div>
                        <div class="detail-item">
                            <label>Previous Date:</label>
                            <span>${caseData.previous_date ? new Date(caseData.previous_date).toLocaleDateString('en-GB') : '-'}</span>
                        </div>
                        <div class="detail-item">
                            <label>Register Number:</label>
                            <span>${caseData.register_number}</span>
                        </div>
                        <div class="detail-item full-width">
                            <label>Information Book:</label>
                            <span>${(caseData.information_book || '-').replace(/\n/g, '<br>')}</span>
                        </div>
                    </div>
                </div>

                <div class="detail-section">
                    <h3><i class="fas fa-calendar-alt"></i> Important Dates</h3>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label>Date Produce B Report:</label>
                            <span>${caseData.date_produce_b_report ? new Date(caseData.date_produce_b_report).toLocaleDateString('en-GB') : '-'}</span>
                        </div>
                        <div class="detail-item">
                            <label>Date Produce Plant:</label>
                            <span>${caseData.date_produce_plant ? new Date(caseData.date_produce_plant).toLocaleDateString('en-GB') : '-'}</span>
                        </div>
                        <div class="detail-item">
                            <label>Date Handover Court:</label>
                            <span>${caseData.date_handover_court ? new Date(caseData.date_handover_court).toLocaleDateString('en-GB') : '-'}</span>
                        </div>
                        <div class="detail-item">
                            <label>Next Date:</label>
                            <span>${caseData.next_date ? new Date(caseData.next_date).toLocaleDateString('en-GB') : '-'}</span>
                        </div>
                    </div>
                </div>

                <div class="detail-section">
                    <h3><i class="fas fa-file-alt"></i> Case Details</h3>
                    <div class="detail-grid">
                        <div class="detail-item full-width">
                            <label>Opens:</label>
                            <span>${(caseData.opens || '-').replace(/\n/g, '<br>')}</span>
                        </div>
                    </div>
                </div>

                <div class="detail-section">
                    <h3><i class="fas fa-gavel"></i> Legal & Reports</h3>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label>Attorney General's Advice:</label>
                            <span class="badge-yn ${caseData.attorney_general_advice === 'YES' ? 'badge-yes' : 'badge-no'}">
                                ${caseData.attorney_general_advice || '-'}
                            </span>
                        </div>
                        <div class="detail-item">
                            <label>Receival Memorandum:</label>
                            <span class="badge-yn ${caseData.receival_memorandum === 'YES' ? 'badge-yes' : 'badge-no'}">
                                ${caseData.receival_memorandum || '-'}
                            </span>
                        </div>
                        <div class="detail-item">
                            <label>Analyst Report:</label>
                            <span class="badge-yn ${caseData.analyst_report === 'YES' ? 'badge-yes' : 'badge-no'}">
                                ${caseData.analyst_report || '-'}
                            </span>
                        </div>
                        <div class="detail-item full-width">
                            <label>Production Register Number:</label>
                            <span>${(caseData.production_register_number || '-').replace(/\n/g, '<br>')}</span>
                        </div>
                        <div class="detail-item full-width">
                            <label>Government Analyst Report:</label>
                            <span>${(caseData.government_analyst_report || '-').replace(/\n/g, '<br>')}</span>
                        </div>
                    </div>
                </div>

                ${suspects.length > 0 ? `
                <div class="detail-section">
                    <h3><i class="fas fa-user-secret"></i> Suspects (${suspects.length})</h3>
                    <div class="list-items">
                        ${suspects.map((s, i) => `
                            <div class="list-item">
                                <div class="list-item-header">
                                    <strong><i class="fas fa-user"></i> Suspect #${i + 1}: ${s.name || 'Unknown'}</strong>
                                </div>
                                <div class="list-item-details">
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="fas fa-id-card"></i> IC Number:</span>
                                        <span>${s.ic || '-'}</span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="fas fa-map-marker-alt"></i> Address:</span>
                                        <span>${s.address || '-'}</span>
                                    </div>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>
                ` : '<div class="detail-section"><h3><i class="fas fa-user-secret"></i> Suspects</h3><p class="no-data">No suspects recorded</p></div>'}

                ${witnesses.length > 0 ? `
                <div class="detail-section">
                    <h3><i class="fas fa-users"></i> Witnesses (${witnesses.length})</h3>
                    <div class="list-items">
                        ${witnesses.map((w, i) => `
                            <div class="list-item">
                                <div class="list-item-header">
                                    <strong><i class="fas fa-user"></i> Witness #${i + 1}: ${w.name || 'Unknown'}</strong>
                                </div>
                                <div class="list-item-details">
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="fas fa-id-card"></i> IC Number:</span>
                                        <span>${w.ic || '-'}</span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="fas fa-map-marker-alt"></i> Address:</span>
                                        <span>${w.address || '-'}</span>
                                    </div>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>
                ` : '<div class="detail-section"><h3><i class="fas fa-users"></i> Witnesses</h3><p class="no-data">No witnesses recorded</p></div>'}

                <div class="detail-section">
                    <h3><i class="fas fa-chart-line"></i> Progress & Results</h3>
                    <div class="detail-grid">
                        <div class="detail-item full-width">
                            <label>Progress Notes:</label>
                            <div class="text-content">${(caseData.progress || '<em>No progress notes recorded</em>').replace(/\n/g, '<br>')}</div>
                        </div>
                        <div class="detail-item full-width">
                            <label>Results:</label>
                            <div class="text-content">${(caseData.results || '<em>No results recorded</em>').replace(/\n/g, '<br>')}</div>
                        </div>
                    </div>
                </div>

                <div class="detail-section">
                    <h3><i class="fas fa-clock"></i> Record Information</h3>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label>Created By:</label>
                            <span><i class="fas fa-user-circle"></i> ${caseData.created_by_name || 'Unknown'}</span>
                        </div>
                        <div class="detail-item">
                            <label>Created At:</label>
                            <span><i class="fas fa-calendar-plus"></i> ${new Date(caseData.created_at).toLocaleString('en-GB')}</span>
                        </div>
                        <div class="detail-item">
                            <label>Updated By:</label>
                            <span><i class="fas fa-user-edit"></i> ${caseData.updated_by_name || '-'}</span>
                        </div>
                        <div class="detail-item">
                            <label>Last Updated:</label>
                            <span><i class="fas fa-calendar-check"></i> ${caseData.updated_at ? new Date(caseData.updated_at).toLocaleString('en-GB') : '-'}</span>
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.getElementById('modalBody').innerHTML = html;
    }

    window.closeModal = function() {
        document.getElementById('caseModal').style.display = 'none';
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('caseModal');
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }

    window.editCase = function(caseId) {
        // TODO: Implement edit case functionality
        alert('Edit case #' + caseId);
    }
</script>