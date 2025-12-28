<?php
session_start();
require_once('../../../config/db.php');

// Pagination settings
$cases_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, $page); // Ensure page is at least 1
$offset = ($page - 1) * $cases_per_page;

// Get total count of cases
$count_sql = "SELECT COUNT(*) as total FROM cases";
$count_result = $conn->query($count_sql);
$total_cases = 0;
if ($count_result) {
    $count_row = $count_result->fetch_assoc();
    $total_cases = $count_row['total'];
}
$total_pages = ceil($total_cases / $cases_per_page);

// Fetch cases for current page with user info
$sql = "SELECT c.*, 
        u1.full_name as created_by_name,
        u2.full_name as updated_by_name
        FROM cases c
        LEFT JOIN users u1 ON c.created_by = u1.id
        LEFT JOIN users u2 ON c.updated_by = u2.id
        ORDER BY c.created_at DESC
        LIMIT $cases_per_page OFFSET $offset";
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
            <!-- Row 1: Text Search -->
            <div class="filter-row">
                <input type="text" id="searchCaseNumber" placeholder="Search Case Number..." onkeyup="filterCases()">
                <input type="text" id="searchRegister" placeholder="Search Register..." onkeyup="filterCases()">
            </div>

            <!-- Row 2: Date Filters -->
            <div class="filter-row">
                <div class="date-filter-group">
                    <label>Previous Date From:</label>
                    <input type="date" id="prevDateFrom" onchange="filterCases()">
                </div>
                <div class="date-filter-group">
                    <label>To:</label>
                    <input type="date" id="prevDateTo" onchange="filterCases()">
                </div>
                <div class="date-filter-group">
                    <label>B Report Date From:</label>
                    <input type="date" id="bReportDateFrom" onchange="filterCases()">
                </div>
                <div class="date-filter-group">
                    <label>To:</label>
                    <input type="date" id="bReportDateTo" onchange="filterCases()">
                </div>
            </div>

            <!-- Row 3: More Date Filters -->
            <div class="filter-row">
                <div class="date-filter-group">
                    <label>Plant Date From:</label>
                    <input type="date" id="plantDateFrom" onchange="filterCases()">
                </div>
                <div class="date-filter-group">
                    <label>To:</label>
                    <input type="date" id="plantDateTo" onchange="filterCases()">
                </div>
                <div class="date-filter-group">
                    <label>Handover Date From:</label>
                    <input type="date" id="handoverDateFrom" onchange="filterCases()">
                </div>
                <div class="date-filter-group">
                    <label>To:</label>
                    <input type="date" id="handoverDateTo" onchange="filterCases()">
                </div>
            </div>

            <!-- Row 4: Next Date Filter -->
            <div class="filter-row">
                <div class="date-filter-group">
                    <label>Next Date From:</label>
                    <input type="date" id="nextDateFrom" onchange="filterCases()">
                </div>
                <div class="date-filter-group">
                    <label>To:</label>
                    <input type="date" id="nextDateTo" onchange="filterCases()">
                </div>
            </div>

            <!-- Row 5: Dropdown Filters -->
            <div class="filter-row">
                <select id="filterInfoBook" onchange="filterCases()">
                    <option value="">All Information Books</option>
                    <option value="RIB">RIB</option>
                    <option value="GCIB I">GCIB I</option>
                    <option value="GCIB II">GCIB II</option>
                    <option value="GCIB III">GCIB III</option>
                    <option value="MOIB">MOIB</option>
                    <option value="VIB">VIB</option>
                    <option value="EIB">EIB</option>
                    <option value="CPUIB">CPUIB</option>
                    <option value="WCIB">WCIB</option>
                    <option value="PIB">PIB</option>
                    <option value="TIB">TIB</option>
                    <option value="AIB">AIB</option>
                    <option value="CIB I">CIB I</option>
                    <option value="CIB II">CIB II</option>
                    <option value="CIB III">CIB III</option>
                    <option value="119 IB">119 IB</option>
                    <option value="TR">TR</option>
                    <option value="119 TR">119 TR</option>
                    <option value="VPN TR">VPN TR</option>
                    <option value="118 TR">118 TR</option>
                </select>

                <select id="filterRegister" onchange="filterCases()">
                    <option value="">All Registers</option>
                    <option value="GCR">GCR</option>
                    <option value="MOR">MOR</option>
                    <option value="VMOR">VMOR</option>
                    <option value="MCR">MCR</option>
                    <option value="TAR">TAR</option>
                    <option value="TMOR">TMOR</option>
                    <option value="AR">AR</option>
                    <option value="SDR">SDR</option>
                    <option value="MPR">MPR</option>
                    <option value="LPR">LPR</option>
                </select>

                <select id="filterAttorneyAdvice" onchange="filterCases()">
                    <option value="">All Attorney Advice</option>
                    <option value="YES">YES</option>
                    <option value="NO">NO</option>
                </select>

                <select id="filterAnalystReport" onchange="filterCases()">
                    <option value="">All Analyst Reports</option>
                    <option value="YES">YES</option>
                    <option value="NO">NO</option>
                </select>

                <button class="btn-clear-filter" onclick="clearFilters()">
                    <i class="fas fa-times"></i> Clear All Filters
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
                    <th>Information Book</th>
                    <th>Register</th>
                    <th>B Report Date</th>
                    <th>Plant Date</th>
                    <th>Opens</th>
                    <th>Attorney General Advice</th>
                    <th>PR & Handover Date</th>
                    <th>Comment Analyst Report</th>
                    <th>Progress</th>
                    <th>Results</th>
                    <th>Suspects</th>
                    <th>Witnesses</th>
                    <th>Next Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cases as $case): ?>
                    <tr data-case-number="<?php echo htmlspecialchars($case['case_number']); ?>"
                        data-register="<?php echo htmlspecialchars($case['register_number']); ?>"
                        data-info-book="<?php echo htmlspecialchars($case['information_book']); ?>"
                        data-prev-date="<?php echo $case['previous_date'] ?? ''; ?>"
                        data-b-report-date="<?php echo $case['date_produce_b_report'] ?? ''; ?>"
                        data-plant-date="<?php echo $case['date_produce_plant'] ?? ''; ?>"
                        data-handover-date="<?php echo $case['date_handover_court'] ?? ''; ?>"
                        data-next-date="<?php echo $case['next_date'] ?? ''; ?>"
                        data-attorney-advice="<?php echo $case['attorney_general_advice'] ?? ''; ?>"
                        data-analyst-report="<?php echo $case['analyst_report'] ?? ''; ?>">

                        <td><strong><?php echo htmlspecialchars($case['case_number']); ?></strong></td>
                        <td><?php echo $case['previous_date'] ? date('d M Y', strtotime($case['previous_date'])) : '-'; ?></td>
                        <td><?php echo htmlspecialchars($case['information_book']); ?></td>
                        <td><?php echo htmlspecialchars($case['register_number']); ?></td>
                        <td><?php echo $case['date_produce_b_report'] ? date('d M Y', strtotime($case['date_produce_b_report'])) : '-'; ?></td>
                        <td><?php echo $case['date_produce_plant'] ? date('d M Y', strtotime($case['date_produce_plant'])) : '-'; ?></td>
                        <td>
                            <div class="cell-content">
                                <?php echo htmlspecialchars(substr($case['opens'] ?? '-', 0, 50)) . (strlen($case['opens'] ?? '') > 50 ? '...' : ''); ?>
                            </div>
                        </td>
                        <td>
                            <span class="badge-yn <?php echo ($case['attorney_general_advice'] === 'YES') ? 'badge-yes' : 'badge-no'; ?>">
                                <?php echo $case['attorney_general_advice'] ?? '-'; ?>
                            </span>
                        </td>
                        <td><?php echo $case['date_handover_court'] ? date('d M Y', strtotime($case['date_handover_court'])) : '-'; ?></td>
                        <td>
                            <span class="badge-yn <?php echo ($case['analyst_report'] === 'YES') ? 'badge-yes' : 'badge-no'; ?>">
                                <?php echo $case['analyst_report'] ?? '-'; ?>
                            </span>
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
                        <td>
                            <div class="cell-content">
                                <?php
                                $suspects = json_decode($case['suspect_data'] ?? '[]', true);
                                if (!empty($suspects)) {
                                    echo '<strong>' . count($suspects) . '</strong>';
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
                                    echo '<strong>' . count($witnesses) . '</strong>';
                                } else {
                                    echo '-';
                                }
                                ?>
                            </div>
                        </td>
                        <td><?php echo $case['next_date'] ? date('d M Y', strtotime($case['next_date'])) : '-'; ?></td>
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

    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
        <div class="pagination">
            <div class="pagination-info">
                Showing <?php echo $offset + 1; ?> to <?php echo min($offset + $cases_per_page, $total_cases); ?> of <?php echo $total_cases; ?> cases
            </div>
            <div class="pagination-controls">
                <?php if ($page > 1): ?>
                    <button class="pagination-btn" onclick="loadPage(1)">
                        <i class="fas fa-angle-double-left"></i> First
                    </button>
                    <button class="pagination-btn" onclick="loadPage(<?php echo $page - 1; ?>)">
                        <i class="fas fa-angle-left"></i> Previous
                    </button>
                <?php endif; ?>

                <?php
                // Show page numbers
                $start_page = max(1, $page - 2);
                $end_page = min($total_pages, $page + 2);

                for ($i = $start_page; $i <= $end_page; $i++):
                ?>
                    <button class="pagination-btn <?php echo $i == $page ? 'active' : ''; ?>" onclick="loadPage(<?php echo $i; ?>)">
                        <?php echo $i; ?>
                    </button>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <button class="pagination-btn" onclick="loadPage(<?php echo $page + 1; ?>)">
                        Next <i class="fas fa-angle-right"></i>
                    </button>
                    <button class="pagination-btn" onclick="loadPage(<?php echo $total_pages; ?>)">
                        Last <i class="fas fa-angle-double-right"></i>
                    </button>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
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
    // Load specific page
    window.loadPage = function(pageNumber) {
        const casesContent = document.getElementById('cases-content');
        casesContent.innerHTML = '<h2><i class="fas fa-folder-open"></i> All Cases</h2><p>Loading cases...</p>';

        fetch('content/allCases/all_cases.php?page=' + pageNumber)
            .then(response => response.text())
            .then(data => {
                casesContent.innerHTML = data;
                // Scroll to top of cases section
                casesContent.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            })
            .catch(error => {
                console.error('Error loading page:', error);
                casesContent.innerHTML = '<h2><i class="fas fa-folder-open"></i> All Cases</h2><p style="color: red;">Error loading cases. Please try again.</p>';
            });
    };

    // Define functions immediately and attach to window
    window.filterCases = function() {
        const searchCaseNumber = document.getElementById('searchCaseNumber').value.toUpperCase();
        const searchRegister = document.getElementById('searchRegister').value.toUpperCase();
        const filterInfoBook = document.getElementById('filterInfoBook').value;
        const filterRegister = document.getElementById('filterRegister').value;
        const filterAttorneyAdvice = document.getElementById('filterAttorneyAdvice').value;
        const filterAnalystReport = document.getElementById('filterAnalystReport').value;

        // Date filters
        const prevDateFrom = document.getElementById('prevDateFrom').value;
        const prevDateTo = document.getElementById('prevDateTo').value;
        const bReportDateFrom = document.getElementById('bReportDateFrom').value;
        const bReportDateTo = document.getElementById('bReportDateTo').value;
        const plantDateFrom = document.getElementById('plantDateFrom').value;
        const plantDateTo = document.getElementById('plantDateTo').value;
        const handoverDateFrom = document.getElementById('handoverDateFrom').value;
        const handoverDateTo = document.getElementById('handoverDateTo').value;
        const nextDateFrom = document.getElementById('nextDateFrom').value;
        const nextDateTo = document.getElementById('nextDateTo').value;

        const table = document.getElementById('casesTable');
        const tr = table.getElementsByTagName('tr');

        for (let i = 1; i < tr.length; i++) {
            const row = tr[i];
            const caseNumber = row.dataset.caseNumber || '';
            const register = row.dataset.register || '';
            const infoBook = row.dataset.infoBook || '';
            const prevDate = row.dataset.prevDate || '';
            const bReportDate = row.dataset.bReportDate || '';
            const plantDate = row.dataset.plantDate || '';
            const handoverDate = row.dataset.handoverDate || '';
            const nextDate = row.dataset.nextDate || '';
            const attorneyAdvice = row.dataset.attorneyAdvice || '';
            const analystReport = row.dataset.analystReport || '';

            // Text search filters
            const matchesCaseNumber = searchCaseNumber === '' || caseNumber.toUpperCase().includes(searchCaseNumber);
            const matchesRegisterSearch = searchRegister === '' || register.toUpperCase().includes(searchRegister);

            // Dropdown filters
            const matchesInfoBook = filterInfoBook === '' || infoBook === filterInfoBook;
            const matchesRegister = filterRegister === '' || register === filterRegister;
            const matchesAttorneyAdvice = filterAttorneyAdvice === '' || attorneyAdvice === filterAttorneyAdvice;
            const matchesAnalystReport = filterAnalystReport === '' || analystReport === filterAnalystReport;

            // Date range filters
            const matchesPrevDate = checkDateRange(prevDate, prevDateFrom, prevDateTo);
            const matchesBReportDate = checkDateRange(bReportDate, bReportDateFrom, bReportDateTo);
            const matchesPlantDate = checkDateRange(plantDate, plantDateFrom, plantDateTo);
            const matchesHandoverDate = checkDateRange(handoverDate, handoverDateFrom, handoverDateTo);
            const matchesNextDate = checkDateRange(nextDate, nextDateFrom, nextDateTo);

            // Show/hide row based on all filters
            if (matchesCaseNumber && matchesRegisterSearch && matchesInfoBook && matchesRegister &&
                matchesAttorneyAdvice && matchesAnalystReport && matchesPrevDate && matchesBReportDate &&
                matchesPlantDate && matchesHandoverDate && matchesNextDate) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    }

    function checkDateRange(rowDate, dateFrom, dateTo) {
        if (!dateFrom && !dateTo) {
            return true; // No filter applied
        }

        if (!rowDate) {
            // If row has no date but filter is set, hide it
            return false;
        }

        const rowDateObj = new Date(rowDate);

        if (dateFrom && dateTo) {
            const dateFromObj = new Date(dateFrom);
            const dateToObj = new Date(dateTo);
            return rowDateObj >= dateFromObj && rowDateObj <= dateToObj;
        } else if (dateFrom) {
            const dateFromObj = new Date(dateFrom);
            return rowDateObj >= dateFromObj;
        } else if (dateTo) {
            const dateToObj = new Date(dateTo);
            return rowDateObj <= dateToObj;
        }

        return true;
    }

    window.clearFilters = function() {
        document.getElementById('searchCaseNumber').value = '';
        document.getElementById('searchRegister').value = '';
        document.getElementById('filterInfoBook').value = '';
        document.getElementById('filterRegister').value = '';
        document.getElementById('filterAttorneyAdvice').value = '';
        document.getElementById('filterAnalystReport').value = '';
        document.getElementById('prevDateFrom').value = '';
        document.getElementById('prevDateTo').value = '';
        document.getElementById('bReportDateFrom').value = '';
        document.getElementById('bReportDateTo').value = '';
        document.getElementById('plantDateFrom').value = '';
        document.getElementById('plantDateTo').value = '';
        document.getElementById('handoverDateFrom').value = '';
        document.getElementById('handoverDateTo').value = '';
        document.getElementById('nextDateFrom').value = '';
        document.getElementById('nextDateTo').value = '';
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