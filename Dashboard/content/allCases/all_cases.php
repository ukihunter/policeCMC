<?php
session_start();
require_once('../../../config/db.php');

// Pagination settings
$cases_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, $page); // Ensure page is at least 1
$offset = ($page - 1) * $cases_per_page;

// Build WHERE clause based on filters
$where_conditions = [];
$params = [];
$types = "";

// Text search filters
if (!empty($_GET['searchCaseNumber'])) {
    $where_conditions[] = "c.case_number LIKE ?";
    $params[] = "%" . $_GET['searchCaseNumber'] . "%";
    $types .= "s";
}

if (!empty($_GET['searchRegister'])) {
    $where_conditions[] = "c.register_number LIKE ?";
    $params[] = "%" . $_GET['searchRegister'] . "%";
    $types .= "s";
}

if (!empty($_GET['searchInfoBook'])) {
    $where_conditions[] = "c.information_book LIKE ?";
    $params[] = "%" . $_GET['searchInfoBook'] . "%";
    $types .= "s";
}

// Dropdown filters
if (!empty($_GET['filterInfoBook'])) {
    $where_conditions[] = "c.information_book LIKE ?";
    $params[] = $_GET['filterInfoBook'] . "%";
    $types .= "s";
}

if (!empty($_GET['filterRegister'])) {
    $where_conditions[] = "c.register_number LIKE ?";
    $params[] = $_GET['filterRegister'] . "%";
    $types .= "s";
}

if (!empty($_GET['filterAttorneyAdvice'])) {
    $where_conditions[] = "c.attorney_general_advice = ?";
    $params[] = $_GET['filterAttorneyAdvice'];
    $types .= "s";
}

if (!empty($_GET['filterAnalystReport'])) {
    $where_conditions[] = "c.analyst_report = ?";
    $params[] = $_GET['filterAnalystReport'];
    $types .= "s";
}

// Date range filters
$date_filters = [
    'prevDate' => 'previous_date',
    'bReportDate' => 'date_produce_b_report',
    'plantDate' => 'date_produce_plant',
    'handoverDate' => 'date_handover_court',
    'nextDate' => 'next_date'
];

foreach ($date_filters as $param => $column) {
    $fromParam = $param . 'From';
    $toParam = $param . 'To';
    $exactParam = $param . 'Exact';

    if (!empty($_GET[$fromParam]) || !empty($_GET[$toParam])) {
        if (!empty($_GET[$exactParam]) && $_GET[$exactParam] === 'true') {
            // Exact date match
            if (!empty($_GET[$fromParam])) {
                $where_conditions[] = "c.$column = ?";
                $params[] = $_GET[$fromParam];
                $types .= "s";
            }
        } else {
            // Date range
            if (!empty($_GET[$fromParam])) {
                $where_conditions[] = "c.$column >= ?";
                $params[] = $_GET[$fromParam];
                $types .= "s";
            }
            if (!empty($_GET[$toParam])) {
                $where_conditions[] = "c.$column <= ?";
                $params[] = $_GET[$toParam];
                $types .= "s";
            }
        }
    }
}

// Build the WHERE clause
$where_sql = "";
if (!empty($where_conditions)) {
    $where_sql = " WHERE " . implode(" AND ", $where_conditions);
}

// Get total count of filtered cases
$count_sql = "SELECT COUNT(*) as total FROM cases c" . $where_sql;
if (!empty($params)) {
    $count_stmt = $conn->prepare($count_sql);
    $count_stmt->bind_param($types, ...$params);
    $count_stmt->execute();
    $count_result = $count_stmt->get_result();
    $count_row = $count_result->fetch_assoc();
    $total_cases = $count_row['total'];
    $count_stmt->close();
} else {
    $count_result = $conn->query($count_sql);
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
        LEFT JOIN users u2 ON c.updated_by = u2.id" .
    $where_sql .
    " ORDER BY c.created_at DESC
        LIMIT $cases_per_page OFFSET $offset";

$cases = [];
if (!empty($params)) {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $cases[] = $row;
    }
    $stmt->close();
} else {
    $result = $conn->query($sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $cases[] = $row;
        }
    }
}
?>

<div class="cases-header">
    <h2><i class="fas fa-folder-open"></i> All Cases</h2>
    <div class="bulk-actions" id="bulkActions" style="display: none;">
        <span class="selected-count" id="selectedCount">0 selected</span>
        <button class="btn-bulk-print" onclick="bulkPrintCases()">
            <i class="fas fa-print"></i> Print Selected Cases
        </button>
        <button class="btn-bulk-clear" onclick="clearSelection()">
            <i class="fas fa-times"></i> Clear Selection
        </button>
    </div>
    <div class="header-actions">
        <div class="filter-section">
            <!-- Row 1: Text Search -->
            <div class="filter-row">
                <input type="text" id="searchCaseNumber" placeholder="Search Case Number..." value="<?php echo isset($_GET['searchCaseNumber']) ? htmlspecialchars($_GET['searchCaseNumber']) : ''; ?>" oninput="debouncedFilter()">
                <input type="text" id="searchRegister" placeholder="Search Register..." value="<?php echo isset($_GET['searchRegister']) ? htmlspecialchars($_GET['searchRegister']) : ''; ?>" oninput="debouncedFilter()">
                <input type="text" id="searchInfoBook" placeholder="Search Information Book..." value="<?php echo isset($_GET['searchInfoBook']) ? htmlspecialchars($_GET['searchInfoBook']) : ''; ?>" oninput="debouncedFilter()">
            </div>

            <!-- Row 2: Date Filters (Previous, B Report, Plant) -->
            <div class="filter-row">
                <div class="date-filter-group">
                    <label>Previous Date:</label>
                    <input type="date" id="prevDateFrom" value="<?php echo isset($_GET['prevDateFrom']) ? htmlspecialchars($_GET['prevDateFrom']) : ''; ?>" onchange="handleDateChange('prevDate')">
                    <label style="font-size: 11px; margin-top: 2px;">
                        <input type="checkbox" id="prevDateExact" onchange="handleDateChange('prevDate')" style="width: auto; margin-right: 3px;" <?php echo (!isset($_GET['prevDateExact']) || $_GET['prevDateExact'] === 'true') ? 'checked' : ''; ?>> Exact Match
                    </label>
                </div>
                <div class="date-filter-group">
                    <label>To:</label>
                    <input type="date" id="prevDateTo" value="<?php echo isset($_GET['prevDateTo']) ? htmlspecialchars($_GET['prevDateTo']) : ''; ?>" onchange="filterCases()" <?php echo (!isset($_GET['prevDateExact']) || $_GET['prevDateExact'] === 'true') ? 'disabled style="opacity: 0.5; cursor: not-allowed;"' : ''; ?>>
                </div>
                <div class="date-filter-group">
                    <label>B Report Date:</label>
                    <input type="date" id="bReportDateFrom" value="<?php echo isset($_GET['bReportDateFrom']) ? htmlspecialchars($_GET['bReportDateFrom']) : ''; ?>" onchange="handleDateChange('bReportDate')">
                    <label style="font-size: 11px; margin-top: 2px;">
                        <input type="checkbox" id="bReportDateExact" onchange="handleDateChange('bReportDate')" style="width: auto; margin-right: 3px;" <?php echo (!isset($_GET['bReportDateExact']) || $_GET['bReportDateExact'] === 'true') ? 'checked' : ''; ?>> Exact Match
                    </label>
                </div>
                <div class="date-filter-group">
                    <label>To:</label>
                    <input type="date" id="bReportDateTo" value="<?php echo isset($_GET['bReportDateTo']) ? htmlspecialchars($_GET['bReportDateTo']) : ''; ?>" onchange="filterCases()" <?php echo (!isset($_GET['bReportDateExact']) || $_GET['bReportDateExact'] === 'true') ? 'disabled style="opacity: 0.5; cursor: not-allowed;"' : ''; ?>>
                </div>
                <div class="date-filter-group">
                    <label>Plant Date:</label>
                    <input type="date" id="plantDateFrom" value="<?php echo isset($_GET['plantDateFrom']) ? htmlspecialchars($_GET['plantDateFrom']) : ''; ?>" onchange="handleDateChange('plantDate')">
                    <label style="font-size: 11px; margin-top: 2px;">
                        <input type="checkbox" id="plantDateExact" onchange="handleDateChange('plantDate')" style="width: auto; margin-right: 3px;" <?php echo (!isset($_GET['plantDateExact']) || $_GET['plantDateExact'] === 'true') ? 'checked' : ''; ?>> Exact Match
                    </label>
                </div>
                <div class="date-filter-group">
                    <label>To:</label>
                    <input type="date" id="plantDateTo" value="<?php echo isset($_GET['plantDateTo']) ? htmlspecialchars($_GET['plantDateTo']) : ''; ?>" onchange="filterCases()" <?php echo (!isset($_GET['plantDateExact']) || $_GET['plantDateExact'] === 'true') ? 'disabled style="opacity: 0.5; cursor: not-allowed;"' : ''; ?>>
                </div>
            </div>

            <!-- Row 3: Date Filters (Handover, Next) -->
            <div class="filter-row">
                <div class="date-filter-group">
                    <label>Handover Date:</label>
                    <input type="date" id="handoverDateFrom" value="<?php echo isset($_GET['handoverDateFrom']) ? htmlspecialchars($_GET['handoverDateFrom']) : ''; ?>" onchange="handleDateChange('handoverDate')">
                    <label style="font-size: 11px; margin-top: 2px;">
                        <input type="checkbox" id="handoverDateExact" onchange="handleDateChange('handoverDate')" style="width: auto; margin-right: 3px;" <?php echo (!isset($_GET['handoverDateExact']) || $_GET['handoverDateExact'] === 'true') ? 'checked' : ''; ?>> Exact Match
                    </label>
                </div>
                <div class="date-filter-group">
                    <label>To:</label>
                    <input type="date" id="handoverDateTo" value="<?php echo isset($_GET['handoverDateTo']) ? htmlspecialchars($_GET['handoverDateTo']) : ''; ?>" onchange="filterCases()" <?php echo (!isset($_GET['handoverDateExact']) || $_GET['handoverDateExact'] === 'true') ? 'disabled style="opacity: 0.5; cursor: not-allowed;"' : ''; ?>>
                </div>
                <div class="date-filter-group">
                    <label>Next Date:</label>
                    <input type="date" id="nextDateFrom" value="<?php echo isset($_GET['nextDateFrom']) ? htmlspecialchars($_GET['nextDateFrom']) : ''; ?>" onchange="handleDateChange('nextDate')">
                    <label style="font-size: 11px; margin-top: 2px;">
                        <input type="checkbox" id="nextDateExact" onchange="handleDateChange('nextDate')" style="width: auto; margin-right: 3px;" <?php echo (!isset($_GET['nextDateExact']) || $_GET['nextDateExact'] === 'true') ? 'checked' : ''; ?>> Exact Match
                    </label>
                </div>
                <div class="date-filter-group">
                    <label>To:</label>
                    <input type="date" id="nextDateTo" value="<?php echo isset($_GET['nextDateTo']) ? htmlspecialchars($_GET['nextDateTo']) : ''; ?>" onchange="filterCases()" <?php echo (!isset($_GET['nextDateExact']) || $_GET['nextDateExact'] === 'true') ? 'disabled style="opacity: 0.5; cursor: not-allowed;"' : ''; ?>>
                </div>
            </div>

            <!-- Row 4: Dropdown Filters -->
            <div class="filter-row">
                <select id="filterInfoBook" onchange="filterCases()">
                    <option value="" <?php echo (!isset($_GET['filterInfoBook']) || $_GET['filterInfoBook'] === '') ? 'selected' : ''; ?>>All Information Books</option>
                    <option value="RIB" <?php echo (isset($_GET['filterInfoBook']) && $_GET['filterInfoBook'] === 'RIB') ? 'selected' : ''; ?>>RIB</option>
                    <option value="GCIB I" <?php echo (isset($_GET['filterInfoBook']) && $_GET['filterInfoBook'] === 'GCIB I') ? 'selected' : ''; ?>>GCIB I</option>
                    <option value="GCIB II" <?php echo (isset($_GET['filterInfoBook']) && $_GET['filterInfoBook'] === 'GCIB II') ? 'selected' : ''; ?>>GCIB II</option>
                    <option value="GCIB III" <?php echo (isset($_GET['filterInfoBook']) && $_GET['filterInfoBook'] === 'GCIB III') ? 'selected' : ''; ?>>GCIB III</option>
                    <option value="MOIB" <?php echo (isset($_GET['filterInfoBook']) && $_GET['filterInfoBook'] === 'MOIB') ? 'selected' : ''; ?>>MOIB</option>
                    <option value="VIB" <?php echo (isset($_GET['filterInfoBook']) && $_GET['filterInfoBook'] === 'VIB') ? 'selected' : ''; ?>>VIB</option>
                    <option value="EIB" <?php echo (isset($_GET['filterInfoBook']) && $_GET['filterInfoBook'] === 'EIB') ? 'selected' : ''; ?>>EIB</option>
                    <option value="CPUIB" <?php echo (isset($_GET['filterInfoBook']) && $_GET['filterInfoBook'] === 'CPUIB') ? 'selected' : ''; ?>>CPUIB</option>
                    <option value="WCIB" <?php echo (isset($_GET['filterInfoBook']) && $_GET['filterInfoBook'] === 'WCIB') ? 'selected' : ''; ?>>WCIB</option>
                    <option value="PIB" <?php echo (isset($_GET['filterInfoBook']) && $_GET['filterInfoBook'] === 'PIB') ? 'selected' : ''; ?>>PIB</option>
                    <option value="TIB" <?php echo (isset($_GET['filterInfoBook']) && $_GET['filterInfoBook'] === 'TIB') ? 'selected' : ''; ?>>TIB</option>
                    <option value="AIB" <?php echo (isset($_GET['filterInfoBook']) && $_GET['filterInfoBook'] === 'AIB') ? 'selected' : ''; ?>>AIB</option>
                    <option value="CIB I" <?php echo (isset($_GET['filterInfoBook']) && $_GET['filterInfoBook'] === 'CIB I') ? 'selected' : ''; ?>>CIB I</option>
                    <option value="CIB II" <?php echo (isset($_GET['filterInfoBook']) && $_GET['filterInfoBook'] === 'CIB II') ? 'selected' : ''; ?>>CIB II</option>
                    <option value="CIB III" <?php echo (isset($_GET['filterInfoBook']) && $_GET['filterInfoBook'] === 'CIB III') ? 'selected' : ''; ?>>CIB III</option>
                    <option value="119 IB" <?php echo (isset($_GET['filterInfoBook']) && $_GET['filterInfoBook'] === '119 IB') ? 'selected' : ''; ?>>119 IB</option>
                    <option value="TR" <?php echo (isset($_GET['filterInfoBook']) && $_GET['filterInfoBook'] === 'TR') ? 'selected' : ''; ?>>TR</option>
                    <option value="119 TR" <?php echo (isset($_GET['filterInfoBook']) && $_GET['filterInfoBook'] === '119 TR') ? 'selected' : ''; ?>>119 TR</option>
                    <option value="VPN TR" <?php echo (isset($_GET['filterInfoBook']) && $_GET['filterInfoBook'] === 'VPN TR') ? 'selected' : ''; ?>>VPN TR</option>
                    <option value="118 TR" <?php echo (isset($_GET['filterInfoBook']) && $_GET['filterInfoBook'] === '118 TR') ? 'selected' : ''; ?>>118 TR</option>
                </select>

                <select id="filterRegister" onchange="filterCases()">
                    <option value="" <?php echo (!isset($_GET['filterRegister']) || $_GET['filterRegister'] === '') ? 'selected' : ''; ?>>All Registers</option>
                    <option value="GCR" <?php echo (isset($_GET['filterRegister']) && $_GET['filterRegister'] === 'GCR') ? 'selected' : ''; ?>>GCR</option>
                    <option value="MOR" <?php echo (isset($_GET['filterRegister']) && $_GET['filterRegister'] === 'MOR') ? 'selected' : ''; ?>>MOR</option>
                    <option value="VMOR" <?php echo (isset($_GET['filterRegister']) && $_GET['filterRegister'] === 'VMOR') ? 'selected' : ''; ?>>VMOR</option>
                    <option value="MCR" <?php echo (isset($_GET['filterRegister']) && $_GET['filterRegister'] === 'MCR') ? 'selected' : ''; ?>>MCR</option>
                    <option value="TAR" <?php echo (isset($_GET['filterRegister']) && $_GET['filterRegister'] === 'TAR') ? 'selected' : ''; ?>>TAR</option>
                    <option value="TMOR" <?php echo (isset($_GET['filterRegister']) && $_GET['filterRegister'] === 'TMOR') ? 'selected' : ''; ?>>TMOR</option>
                    <option value="AR" <?php echo (isset($_GET['filterRegister']) && $_GET['filterRegister'] === 'AR') ? 'selected' : ''; ?>>AR</option>
                    <option value="SDR" <?php echo (isset($_GET['filterRegister']) && $_GET['filterRegister'] === 'SDR') ? 'selected' : ''; ?>>SDR</option>
                    <option value="MPR" <?php echo (isset($_GET['filterRegister']) && $_GET['filterRegister'] === 'MPR') ? 'selected' : ''; ?>>MPR</option>
                    <option value="LPR" <?php echo (isset($_GET['filterRegister']) && $_GET['filterRegister'] === 'LPR') ? 'selected' : ''; ?>>LPR</option>
                </select>

                <select id="filterAttorneyAdvice" onchange="filterCases()">
                    <option value="" <?php echo (!isset($_GET['filterAttorneyAdvice']) || $_GET['filterAttorneyAdvice'] === '') ? 'selected' : ''; ?>>All Attorney Advice</option>
                    <option value="YES" <?php echo (isset($_GET['filterAttorneyAdvice']) && $_GET['filterAttorneyAdvice'] === 'YES') ? 'selected' : ''; ?>>YES</option>
                    <option value="NO" <?php echo (isset($_GET['filterAttorneyAdvice']) && $_GET['filterAttorneyAdvice'] === 'NO') ? 'selected' : ''; ?>>NO</option>
                </select>

                <select id="filterAnalystReport" onchange="filterCases()">
                    <option value="" <?php echo (!isset($_GET['filterAnalystReport']) || $_GET['filterAnalystReport'] === '') ? 'selected' : ''; ?>>All Analyst Reports</option>
                    <option value="YES" <?php echo (isset($_GET['filterAnalystReport']) && $_GET['filterAnalystReport'] === 'YES') ? 'selected' : ''; ?>>YES</option>
                    <option value="NO" <?php echo (isset($_GET['filterAnalystReport']) && $_GET['filterAnalystReport'] === 'NO') ? 'selected' : ''; ?>>NO</option>
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
                    <th style="width: 40px;">
                        <input type="checkbox" id="selectAll" onchange="toggleSelectAll()" title="Select All">
                    </th>
                    <th>Case No / Previous Date</th>
                    <th>Information Book / Register</th>
                    <th>offense</th>
                    <th>Suspects</th>
                    <th>Witnesses</th>
                    <th>Progress</th>
                    <th>Results</th>
                    <th>Next Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cases as $case): ?>
                    <tr data-case-id="<?php echo $case['id']; ?>"
                        data-case-number="<?php echo htmlspecialchars($case['case_number']); ?>"
                        data-register="<?php echo htmlspecialchars($case['register_number']); ?>"
                        data-info-book="<?php echo htmlspecialchars($case['information_book']); ?>"
                        data-prev-date="<?php echo $case['previous_date'] ?? ''; ?>"
                        data-b-report-date="<?php echo $case['date_produce_b_report'] ?? ''; ?>"
                        data-plant-date="<?php echo $case['date_produce_plant'] ?? ''; ?>"
                        data-handover-date="<?php echo $case['date_handover_court'] ?? ''; ?>"
                        data-next-date="<?php echo $case['next_date'] ?? ''; ?>"
                        data-attorney-advice="<?php echo $case['attorney_general_advice'] ?? ''; ?>"
                        data-analyst-report="<?php echo $case['analyst_report'] ?? ''; ?>">
                        <!-- Checkbox -->
                        <td>
                            <input type="checkbox" class="case-checkbox" value="<?php echo $case['id']; ?>" onchange="updateBulkActions()">
                        </td>
                        <!-- Case Number and Previous Date -->
                        <td>
                            <div class="case-main">
                                <strong><?php echo htmlspecialchars($case['case_number']); ?></strong>
                                <div class="case-sub">
                                    <?php echo $case['previous_date'] ? date('d M Y', strtotime($case['previous_date'])) : '-'; ?>
                                </div>
                            </div>
                        </td>
                        <!-- Information Book and Register Number -->
                        <td>
                            <div class="case-main">
                                <strong><?php echo htmlspecialchars($case['information_book']); ?></strong>
                                <div class="case-sub">
                                    <?php echo htmlspecialchars($case['register_number']); ?>
                                </div>
                            </div>
                        </td>
                        <!-- offense -->

                        <td>
                            <div class="cell-content">
                                <?php echo htmlspecialchars(substr($case['opens'] ?? '-', 0, 50)) . (strlen($case['opens'] ?? '') > 50 ? '...' : ''); ?>
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

                        <td><?php echo $case['next_date'] ? date('d M Y', strtotime($case['next_date'])) : '-'; ?></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-view" onclick="viewCase(<?php echo $case['id']; ?>)" title="View Full Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn-edit" onclick="editCase(<?php echo $case['id']; ?>)" title="Edit Case">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn-print" onclick="printCase(<?php echo $case['id']; ?>)" title="Print Case">
                                    <i class="fas fa-print"></i>
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
    <div class="modal-content" style="max-width: 1400px;">
        <div class="modal-header">
            <h2><i class="fas fa-file-alt"></i> Case Details</h2>
            <span class="close-modal" onclick="closeModal()">&times;</span>
        </div>
        <div class="modal-body" id="modalBody">
            <div class="loading">Loading...</div>
        </div>
    </div>
</div>

<?php include 'edit_case_modal.php'; ?>
<?php include 'print_case_modal.php'; ?>

<script>
    // Global variables for print modal
    var currentPrintCaseData = null;
    var currentPrintHistory = null;
    var bulkPrintMode = false;
    var bulkPrintCaseIds = [];

    // Load specific page
    // Store current filter state globally
    window.currentCaseFilters = new URLSearchParams();
    <?php
    foreach ($_GET as $key => $value) {
        if ($key !== 'page') {
            echo "window.currentCaseFilters.set('" . htmlspecialchars($key, ENT_QUOTES) . "', '" . htmlspecialchars($value, ENT_QUOTES) . "');\n    ";
        }
    }
    ?>

    window.loadPage = function(pageNumber) {
        const casesContent = document.getElementById('cases-content');
        casesContent.innerHTML = '<h2><i class="fas fa-folder-open"></i> All Cases</h2><p>Loading cases...</p>';

        // Use stored filters instead of window.location.search
        const params = new URLSearchParams(window.currentCaseFilters);
        params.set('page', pageNumber);

        fetch('content/allCases/all_cases.php?' + params.toString())
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

    // Register Number Management for Edit Modal
    window.updateEditRegisterNumber = function() {
        const type = document.getElementById('edit_register_type');
        const month = document.getElementById('edit_register_month');
        const year = document.getElementById('edit_register_year');
        const display = document.getElementById('edit_register_number_display');
        const hidden = document.getElementById('edit_register_number');

        if (!type || !month || !year || !display || !hidden) {
            return; // Elements not ready yet
        }

        const typeValue = type.value;
        const monthValue = month.value;
        const yearValue = year.value;

        if (typeValue && monthValue && yearValue && yearValue.length === 4) {
            const fullNumber = `${typeValue} ${monthValue}/${yearValue}`;
            display.value = fullNumber;
            hidden.value = fullNumber;
        } else {
            display.value = '';
            hidden.value = '';
        }
    }

    function parseAndPopulateRegisterNumber(registerNumber) {
        // Parse register number like "GCR 01/2025"
        const displayField = document.getElementById('edit_register_number_display');
        const hiddenField = document.getElementById('edit_register_number');

        if (!registerNumber) {
            document.getElementById('edit_register_type').value = '';
            document.getElementById('edit_register_month').value = '';
            document.getElementById('edit_register_year').value = '';
            displayField.value = '';
            hiddenField.value = '';
            return;
        }

        // Always set the display and hidden field to the actual value from database
        displayField.value = registerNumber;
        hiddenField.value = registerNumber;

        // Pattern: TYPE MONTH/YEAR (e.g., "GCR 01/2025")
        const match = registerNumber.match(/^([A-Z]+)\s+(\d{2})\/(\d{4})$/);

        if (match) {
            // If it matches the standard format, populate the dropdowns
            const [, type, month, year] = match;
            document.getElementById('edit_register_type').value = type;
            document.getElementById('edit_register_month').value = month;
            document.getElementById('edit_register_year').value = year;
        } else {
            // If it doesn't match (manually edited), clear dropdowns but keep the display value
            document.getElementById('edit_register_type').value = '';
            document.getElementById('edit_register_month').value = '';
            document.getElementById('edit_register_year').value = '';
        }
    }

    function attachEditRegisterListeners() {
        const editRegisterType = document.getElementById('edit_register_type');
        const editRegisterMonth = document.getElementById('edit_register_month');
        const editRegisterYear = document.getElementById('edit_register_year');
        const editRegisterDisplay = document.getElementById('edit_register_number_display');

        if (editRegisterType) {
            editRegisterType.removeEventListener('change', window.updateEditRegisterNumber);
            editRegisterType.addEventListener('change', window.updateEditRegisterNumber);
        }
        if (editRegisterMonth) {
            editRegisterMonth.removeEventListener('change', window.updateEditRegisterNumber);
            editRegisterMonth.addEventListener('change', window.updateEditRegisterNumber);
        }
        if (editRegisterYear) {
            editRegisterYear.removeEventListener('input', window.updateEditRegisterNumber);
            editRegisterYear.addEventListener('input', window.updateEditRegisterNumber);
        }

        // Allow manual editing of register number display and sync with hidden field
        if (editRegisterDisplay) {
            editRegisterDisplay.removeEventListener('input', syncEditRegisterDisplay);
            editRegisterDisplay.addEventListener('input', syncEditRegisterDisplay);
        }
    }

    function syncEditRegisterDisplay() {
        const display = document.getElementById('edit_register_number_display');
        const hidden = document.getElementById('edit_register_number');
        if (display && hidden) {
            hidden.value = display.value;
        }
    }

    // Handle date change and exact checkbox interaction
    window.handleDateChange = function(dateType) {
        const exactCheckbox = document.getElementById(dateType + 'Exact');
        const toField = document.getElementById(dateType + 'To');

        if (exactCheckbox && toField) {
            if (exactCheckbox.checked) {
                toField.value = '';
                toField.disabled = true;
                toField.style.opacity = '0.5';
                toField.style.cursor = 'not-allowed';
            } else {
                toField.disabled = false;
                toField.style.opacity = '1';
                toField.style.cursor = 'text';
            }
        }

        filterCases();
    }

    // Debounce function to delay filter execution
    let filterTimeout = null;
    window.debouncedFilter = function() {
        clearTimeout(filterTimeout);
        filterTimeout = setTimeout(function() {
            filterCases();
        }, 300); // Wait 300ms after user stops typing
    }

    // Define functions immediately and attach to window
    window.filterCases = function() {
        // Collect all filter values
        const searchCaseNumber = document.getElementById('searchCaseNumber').value;
        const searchRegister = document.getElementById('searchRegister').value;
        const searchInfoBook = document.getElementById('searchInfoBook').value;
        const filterInfoBook = document.getElementById('filterInfoBook').value;
        const filterRegister = document.getElementById('filterRegister').value;
        const filterAttorneyAdvice = document.getElementById('filterAttorneyAdvice').value;
        const filterAnalystReport = document.getElementById('filterAnalystReport').value;

        // Date filters
        const prevDateFrom = document.getElementById('prevDateFrom').value;
        const prevDateTo = document.getElementById('prevDateTo').value;
        const prevDateExact = document.getElementById('prevDateExact').checked;

        const bReportDateFrom = document.getElementById('bReportDateFrom').value;
        const bReportDateTo = document.getElementById('bReportDateTo').value;
        const bReportDateExact = document.getElementById('bReportDateExact').checked;

        const plantDateFrom = document.getElementById('plantDateFrom').value;
        const plantDateTo = document.getElementById('plantDateTo').value;
        const plantDateExact = document.getElementById('plantDateExact').checked;

        const handoverDateFrom = document.getElementById('handoverDateFrom').value;
        const handoverDateTo = document.getElementById('handoverDateTo').value;
        const handoverDateExact = document.getElementById('handoverDateExact').checked;

        const nextDateFrom = document.getElementById('nextDateFrom').value;
        const nextDateTo = document.getElementById('nextDateTo').value;
        const nextDateExact = document.getElementById('nextDateExact').checked;

        // Build URL with filter parameters
        const params = new URLSearchParams();
        params.set('page', '1'); // Reset to page 1 when filtering

        if (searchCaseNumber) params.set('searchCaseNumber', searchCaseNumber);
        if (searchRegister) params.set('searchRegister', searchRegister);
        if (searchInfoBook) params.set('searchInfoBook', searchInfoBook);
        if (filterInfoBook) params.set('filterInfoBook', filterInfoBook);
        if (filterRegister) params.set('filterRegister', filterRegister);
        if (filterAttorneyAdvice) params.set('filterAttorneyAdvice', filterAttorneyAdvice);
        if (filterAnalystReport) params.set('filterAnalystReport', filterAnalystReport);

        // Date filters
        if (prevDateFrom) params.set('prevDateFrom', prevDateFrom);
        if (prevDateTo && !prevDateExact) params.set('prevDateTo', prevDateTo);
        if (prevDateFrom && prevDateExact) params.set('prevDateExact', 'true');

        if (bReportDateFrom) params.set('bReportDateFrom', bReportDateFrom);
        if (bReportDateTo && !bReportDateExact) params.set('bReportDateTo', bReportDateTo);
        if (bReportDateFrom && bReportDateExact) params.set('bReportDateExact', 'true');

        if (plantDateFrom) params.set('plantDateFrom', plantDateFrom);
        if (plantDateTo && !plantDateExact) params.set('plantDateTo', plantDateTo);
        if (plantDateFrom && plantDateExact) params.set('plantDateExact', 'true');

        if (handoverDateFrom) params.set('handoverDateFrom', handoverDateFrom);
        if (handoverDateTo && !handoverDateExact) params.set('handoverDateTo', handoverDateTo);
        if (handoverDateFrom && handoverDateExact) params.set('handoverDateExact', 'true');

        if (nextDateFrom) params.set('nextDateFrom', nextDateFrom);
        if (nextDateTo && !nextDateExact) params.set('nextDateTo', nextDateTo);
        if (nextDateFrom && nextDateExact) params.set('nextDateExact', 'true');

        // Store current filters globally for pagination
        window.currentCaseFilters = new URLSearchParams(params);
        window.currentCaseFilters.delete('page'); // Don't store page number

        // Reload content via AJAX (same as loadPage function)
        const casesContent = document.getElementById('cases-content');
        if (casesContent) {
            // We're in the dashboard - load via AJAX
            // Show a subtle loading indicator without clearing all content
            const existingTable = casesContent.querySelector('.table-container');
            if (existingTable) {
                existingTable.style.opacity = '0.5';
                existingTable.style.pointerEvents = 'none';
            }

            fetch('content/allCases/all_cases.php?' + params.toString())
                .then(response => response.text())
                .then(data => {
                    casesContent.innerHTML = data;
                    // No scroll on filter - only on pagination
                })
                .catch(error => {
                    console.error('Error filtering cases:', error);
                    casesContent.innerHTML = '<h2><i class="fas fa-folder-open"></i> All Cases</h2><p style="color: red;">Error loading filtered cases. Please try again.</p>';
                });
        } else {
            // Standalone page - use normal navigation
            window.location.href = 'all_cases.php?' + params.toString();
        }
    }

    function checkDateRange(rowDate, dateFrom, dateTo, isExact) {
        if (!dateFrom && !dateTo) {
            return true; // No filter applied
        }

        if (!rowDate || rowDate.trim() === '') {
            // If exact match is selected or any filter is set, hide empty dates
            return false;
        }

        const rowDateObj = new Date(rowDate);

        // Check if the date is valid
        if (isNaN(rowDateObj.getTime())) {
            return false; // Invalid date, filter it out when filter is active
        }

        // Normalize dates to compare only year-month-day (ignore time)
        const rowDateOnly = new Date(rowDateObj.getFullYear(), rowDateObj.getMonth(), rowDateObj.getDate());

        if (isExact && dateFrom) {
            // Exact date match - only show exact matches
            const dateFromObj = new Date(dateFrom);
            const dateFromOnly = new Date(dateFromObj.getFullYear(), dateFromObj.getMonth(), dateFromObj.getDate());
            return rowDateOnly.getTime() === dateFromOnly.getTime();
        } else if (dateFrom && dateTo) {
            const dateFromObj = new Date(dateFrom);
            const dateToObj = new Date(dateTo);
            const dateFromOnly = new Date(dateFromObj.getFullYear(), dateFromObj.getMonth(), dateFromObj.getDate());
            const dateToOnly = new Date(dateToObj.getFullYear(), dateToObj.getMonth(), dateToObj.getDate());
            return rowDateOnly >= dateFromOnly && rowDateOnly <= dateToOnly;
        } else if (dateFrom) {
            const dateFromObj = new Date(dateFrom);
            const dateFromOnly = new Date(dateFromObj.getFullYear(), dateFromObj.getMonth(), dateFromObj.getDate());
            return rowDateOnly >= dateFromOnly;
        } else if (dateTo) {
            const dateToObj = new Date(dateTo);
            const dateToOnly = new Date(dateToObj.getFullYear(), dateToObj.getMonth(), dateToObj.getDate());
            return rowDateOnly <= dateToOnly;
        }

        return true;
    }

    window.clearFilters = function() {
        document.getElementById('searchCaseNumber').value = '';
        document.getElementById('searchRegister').value = '';
        document.getElementById('searchInfoBook').value = '';
        document.getElementById('filterInfoBook').value = '';
        document.getElementById('filterRegister').value = '';
        document.getElementById('filterAttorneyAdvice').value = '';
        document.getElementById('filterAnalystReport').value = '';

        // Clear Previous Date filters
        document.getElementById('prevDateFrom').value = '';
        document.getElementById('prevDateTo').value = '';
        document.getElementById('prevDateTo').disabled = true;
        document.getElementById('prevDateTo').style.opacity = '0.5';
        document.getElementById('prevDateTo').style.cursor = 'not-allowed';
        document.getElementById('prevDateExact').checked = true;

        // Clear B Report Date filters
        document.getElementById('bReportDateFrom').value = '';
        document.getElementById('bReportDateTo').value = '';
        document.getElementById('bReportDateTo').disabled = true;
        document.getElementById('bReportDateTo').style.opacity = '0.5';
        document.getElementById('bReportDateTo').style.cursor = 'not-allowed';
        document.getElementById('bReportDateExact').checked = true;

        // Clear Plant Date filters
        document.getElementById('plantDateFrom').value = '';
        document.getElementById('plantDateTo').value = '';
        document.getElementById('plantDateTo').disabled = true;
        document.getElementById('plantDateTo').style.opacity = '0.5';
        document.getElementById('plantDateTo').style.cursor = 'not-allowed';
        document.getElementById('plantDateExact').checked = true;

        // Clear Handover Date filters
        document.getElementById('handoverDateFrom').value = '';
        document.getElementById('handoverDateTo').value = '';
        document.getElementById('handoverDateTo').disabled = true;
        document.getElementById('handoverDateTo').style.opacity = '0.5';
        document.getElementById('handoverDateTo').style.cursor = 'not-allowed';
        document.getElementById('handoverDateExact').checked = true;

        // Clear Next Date filters
        document.getElementById('nextDateFrom').value = '';
        document.getElementById('nextDateTo').value = '';
        document.getElementById('nextDateTo').disabled = true;
        document.getElementById('nextDateTo').style.opacity = '0.5';
        document.getElementById('nextDateTo').style.cursor = 'not-allowed';
        document.getElementById('nextDateExact').checked = true;

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
                            <label>Case Status:</label>
                            <span class="badge-status badge-${(caseData.case_status || 'Ongoing').toLowerCase()}">${caseData.case_status || 'Ongoing'}</span>
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
                            <label>Current Next Date:</label>
                            <span><strong>${caseData.next_date ? new Date(caseData.next_date).toLocaleDateString('en-GB') : '-'}</strong></span>
                        </div>
                    </div>
                </div>

                <div class="detail-section" id="nextDateHistory">
                    <h3><i class="fas fa-history"></i> Next Date History</h3>
                    <div class="loading-history">
                        <i class="fas fa-spinner fa-spin"></i> Loading history...
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
                            <label>Date Handover to Court:</label>
                            <span>${caseData.date_handover_court ? new Date(caseData.date_handover_court).toLocaleDateString('en-GB') : '-'}</span>
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
                                        <span class="detail-label"><i class="fas fa-id-card"></i> NIC Number:</span>
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
                                        <span class="detail-label"><i class="fas fa-id-card"></i> NIC Number:</span>
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

        // Load next date history
        loadNextDateHistory(caseData.id);
    }

    function loadMostRecentNextDateNotes(caseId) {
        fetch('content/allCases/get_next_date_history.php?case_id=' + caseId)
            .then(response => response.json())
            .then(data => {
                const notesField = document.getElementById('edit_next_date_notes');
                const notesLabel = notesField ? notesField.previousElementSibling : null;

                if (data.success && data.history.length > 0) {
                    // Get the most recent entry
                    const latestEntry = data.history[0];
                    if (latestEntry.notes) {
                        // Show current notes as placeholder
                        notesField.placeholder = 'Current notes: ' + latestEntry.notes.substring(0, 100) + (latestEntry.notes.length > 100 ? '...' : '');
                        // Update label to clarify
                        if (notesLabel && notesLabel.tagName === 'LABEL') {
                            notesLabel.innerHTML = 'Next Date Notes (Optional) <small style="color: #666; font-weight: normal;">- Add new notes if changing the date</small>';
                        }
                    }
                } else {
                    notesField.placeholder = 'Add notes about this next date...';
                }
            })
            .catch(error => {
                console.error('Error loading notes:', error);
            });
    }

    function loadNextDateHistory(caseId) {
        const historySection = document.getElementById('nextDateHistory');

        fetch('content/allCases/get_next_date_history.php?case_id=' + caseId)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.history.length > 0) {
                    let historyHtml = '<h3><i class="fas fa-history"></i> Next Date History (' + data.count + ' entries)</h3>';
                    historyHtml += '<div class="history-timeline">';

                    data.history.forEach((entry, index) => {
                        const entryDate = new Date(entry.next_date);
                        const createdDate = new Date(entry.created_at);
                        const isLatest = index === 0;

                        historyHtml += `
                            <div class="history-entry ${isLatest ? 'history-latest' : ''}">
                                <div class="history-marker">
                                    <i class="fas fa-circle"></i>
                                </div>
                                <div class="history-content">
                                    <div class="history-header">
                                        <strong class="history-date">${entryDate.toLocaleDateString('en-GB')}</strong>
                                        ${isLatest ? '<span class="badge-current">Current</span>' : ''}
                                    </div>
                                    ${entry.notes ? `<div class="history-notes">${entry.notes}</div>` : ''}
                                    <div class="history-meta">
                                        <span><i class="fas fa-user"></i> ${entry.created_by_name || 'Unknown'}</span>
                                        <span><i class="fas fa-clock"></i> ${createdDate.toLocaleString('en-GB')}</span>
                                    </div>
                                </div>
                            </div>
                        `;
                    });

                    historyHtml += '</div>';
                    historySection.innerHTML = historyHtml;
                } else {
                    historySection.innerHTML = `
                        <h3><i class="fas fa-history"></i> Next Date History</h3>
                        <p class="no-data">No next date history available</p>
                    `;
                }
            })
            .catch(error => {
                console.error('Error loading history:', error);
                historySection.innerHTML = `
                    <h3><i class="fas fa-history"></i> Next Date History</h3>
                    <p class="no-data" style="color: #dc3545;">Error loading history</p>
                `;
            });
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
        const editModal = document.getElementById('editCaseModal');

        if (!editModal) {
            console.error('Edit modal not found');
            return;
        }

        // Show modal with loading state
        editModal.style.display = 'block';

        // Fetch case details to populate the form
        fetch('content/allCases/get_case_details.php?id=' + caseId)
            .then(response => {
                if (!response.ok) {
                    throw new Error('HTTP error! status: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                console.log('Received data:', data);
                if (data.success) {
                    populateEditForm(data.case);
                } else {
                    alert('Error loading case details: ' + (data.message || 'Unknown error'));
                    editModal.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading case details: ' + error.message);
                editModal.style.display = 'none';
            });
    }

    function populateEditForm(caseData) {
        // Debug: Log the case data
        console.log('Populating edit form with case data:', caseData);

        // Reset counters
        editSuspectCounter = 0;
        editWitnessCounter = 0;

        // Clear existing suspects and witnesses
        document.getElementById('edit_suspects_container').innerHTML = '';
        document.getElementById('edit_witnesses_container').innerHTML = '';

        // Populate all form fields
        document.getElementById('edit_case_id').value = caseData.id;
        document.getElementById('edit_case_number').value = caseData.case_number || '';
        document.getElementById('edit_previous_date').value = caseData.previous_date || '';

        // Parse and populate register number fields
        parseAndPopulateRegisterNumber(caseData.register_number || '');

        // Populate information book
        const infoBook = caseData.information_book || '';
        const infoBookSelect = document.getElementById('edit_information_book');
        const infoBookCustom = document.getElementById('edit_information_book_custom');

        if (!infoBookSelect || !infoBookCustom) {
            console.error('Information book elements not found');
            return;
        }

        const standardValues = ['RIB', 'GCIB I', 'GCIB II', 'GCIB III', 'MOIB', 'VIB', 'EIB', 'CPUIB', 'WCIB', 'PIB', 'TIB', 'AIB', 'CIB I', 'CIB II', 'CIB III', '119 IB', 'TR', '119 TR', 'VPN TR', '118 TR'];

        console.log('Information Book Debug:', {
            raw: caseData.information_book,
            infoBook: infoBook,
            isStandard: standardValues.includes(infoBook),
            selectExists: !!infoBookSelect,
            customExists: !!infoBookCustom
        });

        if (standardValues.includes(infoBook)) {
            infoBookSelect.value = infoBook;
            infoBookCustom.style.display = 'none';
            console.log('✓ Set standard value:', infoBook);
        } else if (infoBook) {
            infoBookSelect.value = 'CUSTOM';
            infoBookCustom.value = infoBook;
            infoBookCustom.style.display = 'block';
            console.log('✓ Set custom value:', infoBook);
        } else {
            infoBookSelect.value = '';
            infoBookCustom.style.display = 'none';
            console.log('⚠ Information Book is empty');
        }

        document.getElementById('edit_date_produce_b_report').value = caseData.date_produce_b_report || '';
        document.getElementById('edit_date_produce_plant').value = caseData.date_produce_plant || '';
        document.getElementById('edit_date_handover_court').value = caseData.date_handover_court || '';
        document.getElementById('edit_next_date').value = caseData.next_date || '';
        // Load the most recent next_date_notes from history
        loadMostRecentNextDateNotes(caseData.id);
        document.getElementById('edit_opens').value = caseData.opens || '';
        document.getElementById('edit_attorney_general_advice').value = caseData.attorney_general_advice || '';
        document.getElementById('edit_receival_memorandum').value = caseData.receival_memorandum || '';
        document.getElementById('edit_analyst_report').value = caseData.analyst_report || '';
        document.getElementById('edit_production_register_number').value = caseData.production_register_number || '';
        document.getElementById('edit_case_status').value = caseData.case_status || 'Ongoing';
        document.getElementById('edit_progress').value = caseData.progress || '';
        document.getElementById('edit_results').value = caseData.results || '';

        // Load suspects
        const suspects = JSON.parse(caseData.suspect_data || '[]');
        if (suspects.length > 0) {
            suspects.forEach(suspect => {
                addEditSuspect(suspect);
            });
        } else {
            // Add one empty suspect field
            addEditSuspect();
        }

        // Load witnesses
        const witnesses = JSON.parse(caseData.witness_data || '[]');
        if (witnesses.length > 0) {
            witnesses.forEach(witness => {
                addEditWitness(witness);
            });
        } else {
            // Add one empty witness field
            addEditWitness();
        }

        // Attach event listeners for register number fields
        attachEditRegisterListeners();

        // IMPORTANT: Re-attach form submission handlers after populating the form
        // This fixes the issue where Save Changes button doesn't work after editing
        if (typeof attachEditFormHandlers === 'function') {
            attachEditFormHandlers();
        } else {
            console.warn('attachEditFormHandlers function not found - form submission may not work');
        }
    }

    window.printCase = function(caseId) {
        const printModal = document.getElementById('printCaseModal');

        if (!printModal) {
            console.error('Print modal not found');
            return;
        }

        // Show modal
        printModal.style.display = 'block';

        // Fetch case details and history
        Promise.all([
                fetch('content/allCases/get_case_details.php?id=' + caseId).then(r => r.json()),
                fetch('content/allCases/get_next_date_history.php?case_id=' + caseId).then(r => r.json())
            ])
            .then(([caseResponse, historyResponse]) => {
                if (caseResponse.success) {
                    currentPrintCaseData = caseResponse.case;
                    currentPrintHistory = historyResponse.success ? historyResponse.history : [];
                } else {
                    alert('Error loading case details');
                    printModal.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading case details');
                printModal.style.display = 'none';
            });
    }

    // Print Modal Functions
    window.closePrintModal = function() {
        const modal = document.getElementById('printCaseModal');
        if (modal) {
            modal.style.display = 'none';
        }
    }

    window.selectAllPrintFields = function() {
        const checkboxes = document.querySelectorAll('.print-field');
        checkboxes.forEach(checkbox => {
            checkbox.checked = true;
        });
        console.log('Selected all fields:', checkboxes.length);
    }

    window.deselectAllPrintFields = function() {
        const checkboxes = document.querySelectorAll('.print-field');
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        console.log('Deselected all fields:', checkboxes.length);
    }

    window.selectCourtEssentials = function() {
        window.deselectAllPrintFields();
        const essentials = [
            'case_number',
            'previous_date',
            'information_book',
            'register_number',
            'opens',
            'witnesses',
            'suspects',
            'progress',
            'results',
            'next_date'
        ];
        essentials.forEach(field => {
            const checkbox = document.querySelector(`.print-field[data-field="${field}"]`);
            if (checkbox) {
                checkbox.checked = true;
            }
        });
        console.log('Selected court essentials');
    }

    window.generatePrint = function() {
        console.log('Generate print called');

        if (!currentPrintCaseData) {
            alert('No case data available');
            return;
        }

        const selectedFields = [];
        document.querySelectorAll('.print-field:checked').forEach(checkbox => {
            selectedFields.push(checkbox.dataset.field);
        });

        console.log('Selected fields:', selectedFields);

        if (selectedFields.length === 0) {
            alert('Please select at least one field to print');
            return;
        }

        // Get selected print layout
        const printLayout = document.querySelector('input[name="printLayout"]:checked').value;
        console.log('Print layout:', printLayout);

        // Close modal
        window.closePrintModal();

        // Fetch police station setting before printing
        fetch('content/users/get_system_settings.php')
            .then(response => response.json())
            .then(data => {
                const policeStation = data.success ? data.police_station : 'POLICE CASE MANAGEMENT';

                if (bulkPrintMode) {
                    // Handle bulk print
                    generateBulkPrint(selectedFields, printLayout, policeStation);
                } else {
                    // Handle single case print
                    const printContent = window.generatePrintHTML(currentPrintCaseData, currentPrintHistory, selectedFields, printLayout, policeStation);

                    // Create or update print container
                    let printContainer = document.getElementById('printContent');
                    if (!printContainer) {
                        printContainer = document.createElement('div');
                        printContainer.id = 'printContent';
                        document.body.appendChild(printContainer);
                    }

                    printContainer.innerHTML = printContent;

                    // Add dual-page class to body if needed
                    if (printLayout === 'dual') {
                        document.body.classList.add('dual-page-print');
                    } else {
                        document.body.classList.remove('dual-page-print');
                    }

                    // Small delay to ensure rendering
                    setTimeout(() => {
                        window.print();

                        // Log print activity
                        fetch('content/allCases/log_print.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                case_id: currentPrintCaseData.id,
                                case_number: currentPrintCaseData.case_number,
                                print_type: 'single'
                            })
                        }).catch(err => console.error('Failed to log print activity:', err));

                        // Clean up after printing
                        setTimeout(() => {
                            document.body.classList.remove('dual-page-print');
                            // Remove print content from DOM
                            if (printContainer) {
                                printContainer.innerHTML = '';
                            }
                        }, 1000);
                    }, 100);
                }
            })
            .catch(error => {
                console.error('Error fetching police station:', error);
                alert('Error loading print settings');
            });
    }

    function generateBulkPrint(selectedFields, printLayout, policeStation) {
        // Show loading message
        const bulkActions = document.getElementById('bulkActions');
        const originalHTML = bulkActions.innerHTML;
        bulkActions.innerHTML = '<span style="color: #4a9eff;"><i class="fas fa-spinner fa-spin"></i> Loading cases...</span>';

        // Fetch all selected cases data
        Promise.all(bulkPrintCaseIds.map(id =>
                fetch('content/allCases/get_case_details.php?id=' + id)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        return fetch('content/allCases/get_next_date_history.php?case_id=' + id)
                            .then(historyResponse => historyResponse.json())
                            .then(historyData => ({
                                caseData: data.case,
                                history: historyData.success ? historyData.history : []
                            }));
                    }
                    return null;
                })
            ))
            .then(casesData => {
                bulkActions.innerHTML = originalHTML;

                // Filter out any failed fetches
                const validCases = casesData.filter(c => c !== null);

                if (validCases.length === 0) {
                    alert('Failed to load case data');
                    bulkPrintMode = false;
                    bulkPrintCaseIds = [];
                    return;
                }

                // Generate bulk print HTML
                const printContent = generateBulkPrintHTML(validCases, selectedFields, printLayout, policeStation);

                // Create or update print container
                let printContainer = document.getElementById('printContent');
                if (!printContainer) {
                    printContainer = document.createElement('div');
                    printContainer.id = 'printContent';
                    document.body.appendChild(printContainer);
                }

                printContainer.innerHTML = printContent;

                // Add dual-page class to body if needed
                if (printLayout === 'dual') {
                    document.body.classList.add('dual-page-print');
                } else {
                    document.body.classList.remove('dual-page-print');
                }

                // Small delay to ensure rendering
                setTimeout(() => {
                    window.print();

                    // Log bulk print activity
                    const caseNumbers = validCases.map(c => c.case_number).join(', ');
                    fetch('content/allCases/log_print.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            case_id: validCases[0]?.id,
                            case_number: caseNumbers,
                            print_type: 'bulk'
                        })
                    }).catch(err => console.error('Failed to log print activity:', err));

                    // Clean up after printing
                    setTimeout(() => {
                        document.body.classList.remove('dual-page-print');
                        if (printContainer) {
                            printContainer.innerHTML = '';
                        }
                        // Reset bulk print mode
                        bulkPrintMode = false;
                        bulkPrintCaseIds = [];
                    }, 1000);
                }, 100);
            })
            .catch(error => {
                bulkActions.innerHTML = originalHTML;
                console.error('Error loading cases:', error);
                alert('Error loading cases for printing: ' + error.message);
                bulkPrintMode = false;
                bulkPrintCaseIds = [];
            });
    }

    function generateBulkPrintHTML(casesData, selectedFields, printLayout, policeStation = 'POLICE CASE MANAGEMENT') {
        const formatDate = (dateString) => {
            if (!dateString) return '';
            const date = new Date(dateString);
            return date.toLocaleDateString('en-GB', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });
        };

        // Define all columns (same structure as single print)
        const allColumns = [{
                key: 'case_previous',
                header: 'Case Number / Previous Date'
            },
            {
                key: 'info_register',
                header: 'Information Book / Register Number'
            },
            {
                key: 'date_produce_b_report',
                header: 'Date of Produce B Report'
            },
            {
                key: 'date_produce_plant',
                header: 'Date of Produce Plant'
            },
            {
                key: 'offence',
                header: 'Offence'
            },
            {
                key: 'attorney_general_advice',
                header: "Attorney General's Advice"
            },
            {
                key: 'production_handover',
                header: 'Production Register Number / Date of Hand Over to Court'
            },
            {
                key: 'government_analyst',
                header: "Government Analyst's Report",
                hasSubColumns: true,
                subColumns: [{
                        key: 'receival_memorandum',
                        header: 'Receival Memorandum'
                    },
                    {
                        key: 'analyst_report',
                        header: "Analyst's Report"
                    }
                ]
            },
            {
                key: 'suspects',
                header: 'Suspect Name, Address, NIC Number'
            },
            {
                key: 'witnesses',
                header: 'Witness Name, Address, NIC Number'
            },
            {
                key: 'progress',
                header: 'Progress'
            },
            {
                key: 'results',
                header: 'Results'
            },
            {
                key: 'next_date',
                header: 'Next Date'
            }
        ];

        if (printLayout === 'dual') {
            return generateBulkDualPageHTML(casesData, allColumns, formatDate, policeStation);
        } else {
            return generateBulkSinglePageHTML(casesData, allColumns, formatDate, policeStation);
        }
    }

    function generateBulkDualPageHTML(casesData, columns, formatDate, policeStation) {
        const page1Columns = columns.slice(0, 7);
        const page2Columns = columns.slice(7);

        let html = '';

        // Page 1 - Header only
        html += `
        <div class="page-1" style="font-family: 'Arial', sans-serif; padding: 5mm; width: 100%; box-sizing: border-box;">
            <div style="text-align: center; margin-bottom: 8px;">
                <h1 style="color: #000; margin: 0; font-size: 18px; font-weight: bold;">POLICE CASE MANAGEMENT</h1>
                <h2 style="color: #000; margin: 5px 0 0 0; font-size: 14px; font-weight: bold;">Police Station: ${policeStation}</h2>
                <p style="color: #000; font-size: 11px; margin: 3px 0;">${casesData.length} Cases | ${new Date().toLocaleString('en-GB')}</p>
            </div>
            
            <table style="width: 100%; table-layout: fixed; border-collapse: collapse; font-size: 16px; border: 2px solid #000;">
                <thead>`;

        // Page 1 Headers
        let hasSubColumns1 = page1Columns.some(col => col.hasSubColumns);
        if (hasSubColumns1) {
            html += '<tr>';
            page1Columns.forEach(col => {
                if (col.hasSubColumns) {
                    html += `<th colspan="${col.subColumns.length}" style="border: 2px solid #000; padding: 6px 4px; background: #fff; color: #000; font-weight: bold; text-align: center; vertical-align: middle; font-size: 16px; word-wrap: break-word;">${col.header}</th>`;
                } else {
                    html += `<th rowspan="2" style="border: 2px solid #000; padding: 6px 4px; background: #fff; color: #000; font-weight: bold; text-align: center; vertical-align: middle; font-size: 16px; word-wrap: break-word; width: ${100/9}%;">${col.header}</th>`;
                }
            });
            html += '</tr><tr>';
            page1Columns.forEach(col => {
                if (col.hasSubColumns) {
                    col.subColumns.forEach(subCol => {
                        html += `<th style="border: 2px solid #000; padding: 5px 3px; background: #fff; color: #000; font-weight: bold; text-align: center; vertical-align: middle; font-size: 16px; word-wrap: break-word; width: ${100/9}%;">${subCol.header}</th>`;
                    });
                }
            });
            html += '</tr>';
        } else {
            html += '<tr>';
            page1Columns.forEach(col => {
                html += `<th style="border: 2px solid #000; padding: 6px 4px; background: #fff; color: #000; font-weight: bold; text-align: center; vertical-align: middle; font-size: 16px; word-wrap: break-word; width: ${100/page1Columns.length}%;">${col.header}</th>`;
            });
            html += '</tr>';
        }

        html += `</thead><tbody>`;

        // Add all cases to Page 1
        casesData.forEach(caseInfo => {
            const caseData = caseInfo.caseData;
            html += '<tr>';

            page1Columns.forEach(col => {
                if (col.hasSubColumns) {
                    col.subColumns.forEach(subCol => {
                        const value = getColumnValue(caseData, subCol.key, formatDate);
                        html += `<td style="border: 2px solid #000; padding: 5px 4px; vertical-align: top; text-align: left; line-height: 1.4; font-size: 16px; color: #000; word-wrap: break-word; overflow-wrap: break-word;">${value}</td>`;
                    });
                } else {
                    const value = getColumnValue(caseData, col.key, formatDate);
                    html += `<td style="border: 2px solid #000; padding: 5px 4px; vertical-align: top; text-align: left; line-height: 1.4; font-size: 16px; color: #000; word-wrap: break-word; overflow-wrap: break-word;">${value}</td>`;
                }
            });

            html += '</tr>';
        });

        html += `</tbody></table>
        </div>`;

        // Page 2 - Header and all cases
        html += `
        <div class="page-2" style="font-family: 'Arial', sans-serif; padding: 5mm; width: 100%; box-sizing: border-box;">
            <div style="text-align: center; margin-bottom: 8px;">
                <h1 style="color: #000; margin: 0; font-size: 18px; font-weight: bold;">POLICE CASE MANAGEMENT</h1>
                <h2 style="color: #000; margin: 5px 0 0 0; font-size: 14px; font-weight: bold;">Police Station: ${policeStation}</h2>
                <p style="color: #000; font-size: 11px; margin: 3px 0;">${casesData.length} Cases | ${new Date().toLocaleString('en-GB')}</p>
            </div>
            
            <table style="width: 100%; table-layout: fixed; border-collapse: collapse; font-size: 16px; border: 2px solid #000;">
                <thead>`;

        // Page 2 Headers
        html += '<tr>';
        page2Columns.forEach(col => {
            if (col.hasSubColumns) {
                html += `<th colspan="${col.subColumns.length}" style="border: 2px solid #000; padding: 6px 4px; background: #fff; color: #000; font-weight: bold; text-align: center; vertical-align: middle; font-size: 16px; word-wrap: break-word; width: ${100/page2Columns.length}%;">${col.header}</th>`;
            } else {
                html += `<th style="border: 2px solid #000; padding: 6px 4px; background: #fff; color: #000; font-weight: bold; text-align: center; vertical-align: middle; font-size: 16px; word-wrap: break-word; width: ${100/page2Columns.length}%;">${col.header}</th>`;
            }
        });
        html += '</tr>';

        html += `</thead><tbody>`;

        // Add all cases to Page 2
        casesData.forEach(caseInfo => {
            const caseData = caseInfo.caseData;
            html += '<tr>';

            page2Columns.forEach(col => {
                if (col.hasSubColumns) {
                    col.subColumns.forEach(subCol => {
                        const value = getColumnValue(caseData, subCol.key, formatDate);
                        html += `<td style="border: 2px solid #000; padding: 5px 4px; vertical-align: top; text-align: left; line-height: 1.4; font-size: 16px; color: #000; word-wrap: break-word; overflow-wrap: break-word;">${value}</td>`;
                    });
                } else {
                    const value = getColumnValue(caseData, col.key, formatDate);
                    html += `<td style="border: 2px solid #000; padding: 5px 4px; vertical-align: top; text-align: left; line-height: 1.4; font-size: 16px; color: #000; word-wrap: break-word; overflow-wrap: break-word;">${value}</td>`;
                }
            });

            html += '</tr>';
        });

        html += `</tbody></table>
        </div>`;

        return html;
    }

    function getColumnValue(caseData, key, formatDate) {
        switch (key) {
            case 'case_previous':
                let val = '';
                if (caseData.case_number) val += caseData.case_number;
                if (caseData.previous_date) {
                    if (val) val += '<br>';
                    val += formatDate(caseData.previous_date);
                }
                return val;

            case 'info_register':
                let val2 = '';
                if (caseData.information_book) val2 += caseData.information_book;
                if (caseData.register_number) {
                    if (val2) val2 += '<br>';
                    val2 += caseData.register_number;
                }
                return val2;

            case 'date_produce_b_report':
                return formatDate(caseData.date_produce_b_report);

            case 'date_produce_plant':
                return formatDate(caseData.date_produce_plant);

            case 'offence':
                return caseData.opens ? caseData.opens.replace(/\n/g, '<br>') : '';

            case 'attorney_general_advice':
                return caseData.attorney_general_advice || '';

            case 'production_handover':
                let val3 = '';
                if (caseData.production_register_number) {
                    val3 += caseData.production_register_number.replace(/\n/g, '<br>');
                }
                if (caseData.date_handover_court) {
                    if (val3) val3 += '<br>';
                    val3 += formatDate(caseData.date_handover_court);
                }
                return val3;

            case 'receival_memorandum':
                return caseData.receival_memorandum || '';

            case 'analyst_report':
                return caseData.analyst_report || '';

            case 'suspects':
                const suspects = JSON.parse(caseData.suspect_data || '[]');
                if (suspects.length === 0) return '';
                let text = '';
                suspects.forEach((suspect, index) => {
                    if (index > 0) text += '<br><br>';
                    text += `${index + 1}. ${suspect.name || ''}<br>${suspect.address || ''}<br>NIC ${suspect.ic || ''}`;
                });
                return text;

            case 'witnesses':
                const witnesses = JSON.parse(caseData.witness_data || '[]');
                if (witnesses.length === 0) return '';
                let text2 = '';
                witnesses.forEach((witness, index) => {
                    if (index > 0) text2 += '<br><br>';
                    text2 += `${index + 1}. ${witness.name || ''}<br>${witness.address || ''}<br>NIC ${witness.ic || ''}`;
                });
                return text2;

            case 'progress':
                return caseData.progress ? caseData.progress.replace(/\n/g, '<br>') : '';

            case 'results':
                return caseData.results ? caseData.results.replace(/\n/g, '<br>') : '';

            case 'next_date':
                return formatDate(caseData.next_date);

            default:
                return '';
        }
    }

    function generateBulkSinglePageHTML(casesData, columns, formatDate, policeStation) {
        // Similar to single page but with multiple rows
        let htmlContent = `
        <div style="font-family: 'Arial', sans-serif; padding: 10px;">
            <div style="text-align: center; margin-bottom: 15px;">
                <h1 style="color: #000; margin: 0; font-size: 16px; font-weight: bold;">POLICE CASE MANAGEMENT SYSTEM</h1>
                <h2 style="color: #000; margin: 5px 0 0 0; font-size: 13px; font-weight: bold;">Police Station: ${policeStation}</h2>
                <p style="color: #333; font-size: 9px; margin: 5px 0;">${casesData.length} Cases Report | Generated on: ${new Date().toLocaleString('en-GB')}</p>
            </div>
            
            <table style="width: 100%; border-collapse: collapse; font-size: 9px; border: 1px solid #000;">
                <thead>
                    <tr>`;

        // Add headers
        columns.forEach(col => {
            if (col.hasSubColumns) {
                htmlContent += `<th colspan="${col.subColumns.length}" style="border: 1px solid #000; padding: 8px 4px; background: #000; color: #fff; font-weight: bold; text-align: center;">${col.header}</th>`;
            } else {
                htmlContent += `<th style="border: 1px solid #000; padding: 8px 4px; background: #000; color: #fff; font-weight: bold; text-align: center;">${col.header}</th>`;
            }
        });

        htmlContent += `</tr>
                </thead>
                <tbody>`;

        // Add all cases
        casesData.forEach(caseInfo => {
            const caseData = caseInfo.caseData;
            htmlContent += '<tr>';

            columns.forEach(col => {
                if (col.hasSubColumns) {
                    col.subColumns.forEach(subCol => {
                        const value = getColumnValue(caseData, subCol.key, formatDate);
                        htmlContent += `<td style="border: 1px solid #000; padding: 6px 4px; vertical-align: top; text-align: left; line-height: 1.4;">${value}</td>`;
                    });
                } else {
                    const value = getColumnValue(caseData, col.key, formatDate);
                    htmlContent += `<td style="border: 1px solid #000; padding: 6px 4px; vertical-align: top; text-align: left; line-height: 1.4;">${value}</td>`;
                }
            });

            htmlContent += '</tr>';
        });

        htmlContent += `
                </tbody>
            </table>
            
            <div style="margin-top: 15px; text-align: center; font-size: 8px; color: #333;">
                <p style="margin: 2px 0;">This document is an official record from the Police Case Management System - Generated automatically</p>
            </div>
        </div>
        `;

        return htmlContent;
    }

    window.generatePrintHTML = function(caseData, history, selectedFields, printLayout = 'single', policeStation = 'POLICE CASE MANAGEMENT') {
        const formatDate = (dateString) => {
            if (!dateString) return '';
            const date = new Date(dateString);
            return date.toLocaleDateString('en-GB', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });
        };

        // Define all available columns with their field mappings
        const allColumns = [{
                key: 'case_previous',
                fields: ['case_number', 'previous_date'],
                header: 'Case Number / Previous Date',
                getValue: () => {
                    let val = '';
                    if (caseData.case_number) val += caseData.case_number;
                    if (caseData.previous_date) {
                        if (val) val += '<br>';
                        val += formatDate(caseData.previous_date);
                    }
                    return val;
                }
            },
            {
                key: 'info_register',
                fields: ['information_book', 'register_number'],
                header: 'Information Book / Register Number',
                getValue: () => {
                    let val = '';
                    if (caseData.information_book) val += caseData.information_book;
                    if (caseData.register_number) {
                        if (val) val += '<br>';
                        val += caseData.register_number;
                    }
                    return val;
                }
            },
            {
                key: 'date_produce_b_report',
                fields: ['date_produce_b_report'],
                header: 'Date of Produce B Report',
                getValue: () => formatDate(caseData.date_produce_b_report)
            },
            {
                key: 'date_produce_plant',
                fields: ['date_produce_plant'],
                header: 'Date of Produce Plant',
                getValue: () => formatDate(caseData.date_produce_plant)
            },
            {
                key: 'offence',
                fields: ['opens'],
                header: 'Offence',
                getValue: () => caseData.opens ? caseData.opens.replace(/\n/g, '<br>') : ''
            },
            {
                key: 'attorney_general_advice',
                fields: ['attorney_general_advice'],
                header: "Attorney General's Advice",
                getValue: () => caseData.attorney_general_advice || ''
            },
            {
                key: 'production_handover',
                fields: ['production_register_number', 'date_handover_court'],
                header: 'Production Register Number / Date of Hand Over to Court',
                getValue: () => {
                    let val = '';
                    if (caseData.production_register_number) {
                        val += caseData.production_register_number.replace(/\n/g, '<br>');
                    }
                    if (caseData.date_handover_court) {
                        if (val) val += '<br>';
                        val += formatDate(caseData.date_handover_court);
                    }
                    return val;
                }
            },
            {
                key: 'government_analyst',
                fields: ['receival_memorandum', 'analyst_report'],
                header: "Government Analyst's Report",
                hasSubColumns: true,
                subColumns: [{
                        key: 'receival_memorandum',
                        header: 'Receival Memorandum',
                        getValue: () => caseData.receival_memorandum || ''
                    },
                    {
                        key: 'analyst_report',
                        header: "Analyst's Report",
                        getValue: () => caseData.analyst_report || ''
                    }
                ]
            },
            {
                key: 'suspects',
                fields: ['suspects'],
                header: 'Suspect Name, Address, NIC Number',
                getValue: () => {
                    const suspects = JSON.parse(caseData.suspect_data || '[]');
                    if (suspects.length === 0) return '';
                    let text = '';
                    suspects.forEach((suspect, index) => {
                        if (index > 0) text += '<br><br>';
                        text += `${index + 1}. ${suspect.name || ''}<br>${suspect.address || ''}<br>NIC ${suspect.ic || ''}`;
                    });
                    return text;
                }
            },
            {
                key: 'witnesses',
                fields: ['witnesses'],
                header: 'Witness Name, Address, NIC Number',
                getValue: () => {
                    const witnesses = JSON.parse(caseData.witness_data || '[]');
                    if (witnesses.length === 0) return '';
                    let text = '';
                    witnesses.forEach((witness, index) => {
                        if (index > 0) text += '<br><br>';
                        text += `${index + 1}. ${witness.name || ''}<br>${witness.address || ''}<br>NIC ${witness.ic || ''}`;
                    });
                    return text;
                }
            },
            {
                key: 'progress',
                fields: ['progress'],
                header: 'Progress',
                getValue: () => caseData.progress ? caseData.progress.replace(/\n/g, '<br>') : ''
            },
            {
                key: 'results',
                fields: ['results'],
                header: 'Results',
                getValue: () => caseData.results ? caseData.results.replace(/\n/g, '<br>') : ''
            },
            {
                key: 'next_date',
                fields: ['next_date'],
                header: 'Next Date',
                getValue: () => formatDate(caseData.next_date)
            }
        ];

        // Filter columns based on selected fields
        const columns = allColumns.filter(col => {
            // If column has sub-columns, check if any sub-column field is selected
            if (col.hasSubColumns) {
                return col.fields.some(field => selectedFields.includes(field));
            }
            // Otherwise check if any of the column's fields are selected
            return col.fields.some(field => selectedFields.includes(field));
        });

        // If using sub-columns, filter them too
        columns.forEach(col => {
            if (col.hasSubColumns) {
                col.subColumns = col.subColumns.filter(subCol =>
                    selectedFields.includes(subCol.key)
                );
            }
        });

        if (printLayout === 'dual') {
            return generateDualPageHTML(caseData, columns, formatDate, policeStation);
        } else {
            return generateSinglePageHTML(caseData, columns, formatDate, policeStation);
        }
    }

    function generateSinglePageHTML(caseData, columns, formatDate, policeStation) {
        let htmlContent = `
        <div style="font-family: 'Arial', sans-serif; padding: 10px;">
            <div style="text-align: center; margin-bottom: 15px;">
                <h1 style="color: #000; margin: 0; font-size: 16px; font-weight: bold;">POLICE CASE MANAGEMENT SYSTEM</h1>
                <h2 style="color: #000; margin: 5px 0 0 0; font-size: 13px; font-weight: bold;">Police Station: ${policeStation}</h2>
                <p style="color: #333; font-size: 9px; margin: 5px 0;">Case Details Report | Generated on: ${new Date().toLocaleString('en-GB')}</p>
            </div>
            
            <table style="width: 100%; border-collapse: collapse; font-size: 9px; border: 1px solid #000;">
                <thead>
        `;

        // Build header rows
        let hasSubColumns = columns.some(col => col.hasSubColumns);

        if (hasSubColumns) {
            // First header row - main columns
            htmlContent += '<tr>';
            columns.forEach(col => {
                if (col.hasSubColumns) {
                    htmlContent += `<th colspan="${col.subColumns.length}" style="border: 1px solid #000; padding: 8px 4px; background: #000; color: #fff; font-weight: bold; text-align: center; vertical-align: middle;">${col.header}</th>`;
                } else {
                    htmlContent += `<th rowspan="2" style="border: 1px solid #000; padding: 8px 4px; background: #000; color: #fff; font-weight: bold; text-align: center; vertical-align: middle; min-width: 60px;">${col.header}</th>`;
                }
            });
            htmlContent += '</tr>';

            // Second header row - sub columns
            htmlContent += '<tr>';
            columns.forEach(col => {
                if (col.hasSubColumns) {
                    col.subColumns.forEach(subCol => {
                        htmlContent += `<th style="border: 1px solid #000; padding: 6px 3px; background: #333; color: #fff; font-weight: bold; text-align: center; vertical-align: middle; font-size: 8px;">${subCol.header}</th>`;
                    });
                }
            });
            htmlContent += '</tr>';
        } else {
            // Single header row
            htmlContent += '<tr>';
            columns.forEach(col => {
                htmlContent += `<th style="border: 1px solid #000; padding: 8px 4px; background: #000; color: #fff; font-weight: bold; text-align: center; vertical-align: middle; min-width: 60px;">${col.header}</th>`;
            });
            htmlContent += '</tr>';
        }

        htmlContent += `
                </thead>
                <tbody>
                    <tr>
        `;

        // Add data cells
        columns.forEach(col => {
            if (col.hasSubColumns) {
                col.subColumns.forEach(subCol => {
                    const value = subCol.getValue();
                    htmlContent += `<td style="border: 1px solid #000; padding: 6px 4px; vertical-align: top; text-align: left; line-height: 1.4;">${value}</td>`;
                });
            } else {
                const value = col.getValue();
                htmlContent += `<td style="border: 1px solid #000; padding: 6px 4px; vertical-align: top; text-align: left; line-height: 1.4;">${value}</td>`;
            }
        });

        htmlContent += `
                    </tr>
                </tbody>
            </table>
            
            <div style="margin-top: 15px; text-align: center; font-size: 8px; color: #333;">
                <p style="margin: 2px 0;">This document is an official record from the Police Case Management System - Generated automatically</p>
            </div>
        </div>
        `;

        return htmlContent;
    }

    function generateDualPageHTML(caseData, columns, formatDate, policeStation) {
        // Split columns into two groups for dual page printing
        // Page 1: First 7 columns (including sub-columns count)
        // Page 2: Remaining columns

        const page1Columns = columns.slice(0, 7); // First 7 columns
        const page2Columns = columns.slice(7); // Remaining columns

        let html = '';

        // Generate Page 1
        html += `
        <div class="page-1" style="font-family: 'Arial', sans-serif; width: 100%; box-sizing: border-box; margin: 0; padding: 0;">
            <div style="text-align: center; margin-bottom: 8px;">
                <h1 style="color: #000; margin: 0; font-size: 18px; font-weight: bold;">POLICE CASE MANAGEMENT</h1>
                <h2 style="color: #000; margin: 5px 0 0 0; font-size: 14px; font-weight: bold;">Police Station: ${policeStation}</h2>
                <p style="color: #000; font-size: 11px; margin: 3px 0;">Case: ${caseData.case_number || 'N/A'} | ${new Date().toLocaleString('en-GB')}</p>
            </div>
            
            <table style="width: 100%; table-layout: fixed; border-collapse: collapse; font-size: 16px; border: 2px solid #000;">
                <thead>`;

        // Page 1 Headers
        let hasSubColumns1 = page1Columns.some(col => col.hasSubColumns);
        if (hasSubColumns1) {
            html += '<tr>';
            page1Columns.forEach(col => {
                if (col.hasSubColumns) {
                    html += `<th colspan="${col.subColumns.length}" style="border: 2px solid #000; padding: 6px 4px; background: #000; color: #000; font-weight: bold; text-align: center; vertical-align: middle; font-size: 16px; word-wrap: break-word;">${col.header}</th>`;
                } else {
                    html += `<th rowspan="2" style="border: 2px solid #000; padding: 6px 4px; background: #fff; color: #000; font-weight: bold; text-align: center; vertical-align: middle; font-size: 16px; word-wrap: break-word; width: ${100/9}%;">${col.header}</th>`;
                }
            });
            html += '</tr><tr>';
            page1Columns.forEach(col => {
                if (col.hasSubColumns) {
                    col.subColumns.forEach(subCol => {
                        html += `<th style="border: 2px solid #000; padding: 5px 3px; background: #fff; color: #000; font-weight: bold; text-align: center; vertical-align: middle; font-size: 16px; word-wrap: break-word; width: ${100/9}%;">${subCol.header}</th>`;
                    });
                }
            });
            html += '</tr>';
        } else {
            html += '<tr>';
            page1Columns.forEach(col => {
                html += `<th style="border: 2px solid #000; padding: 6px 4px; background: #fff; color: #000; font-weight: bold; text-align: center; vertical-align: middle; font-size: 16px; word-wrap: break-word; width: ${100/page1Columns.length}%;">${col.header}</th>`;
            });
            html += '</tr>';
        }

        html += `</thead><tbody><tr>`;

        // Page 1 Data
        page1Columns.forEach(col => {
            if (col.hasSubColumns) {
                col.subColumns.forEach(subCol => {
                    const value = subCol.getValue();
                    html += `<td style="border: 2px solid #000; padding: 5px 4px; vertical-align: top; text-align: left; line-height: 1.4; font-size: 16px; color: #000; word-wrap: break-word; overflow-wrap: break-word;">${value}</td>`;
                });
            } else {
                const value = col.getValue();
                html += `<td style="border: 2px solid #000; padding: 5px 4px; vertical-align: top; text-align: left; line-height: 1.4; font-size: 16px; color: #000; word-wrap: break-word; overflow-wrap: break-word;">${value}</td>`;
            }
        });

        html += `</tr></tbody></table>
        </div>`;

        // Generate Page 2
        html += `
        <div class="page-2" style="font-family: 'Arial', sans-serif; width: 100%; box-sizing: border-box; margin: 0; padding: 0;">
            <div style="text-align: center; margin-bottom: 8px;">
                <h1 style="color: #000; margin: 0; font-size: 18px; font-weight: bold;">POLICE CASE MANAGEMENT</h1>
                <h2 style="color: #000; margin: 5px 0 0 0; font-size: 14px; font-weight: bold;">Police Station: ${policeStation}</h2>
                <p style="color: #000; font-size: 11px; margin: 3px 0;">Case: ${caseData.case_number || 'N/A'} | ${new Date().toLocaleString('en-GB')}</p>
            </div>
            
            <table style="width: 100%; table-layout: fixed; border-collapse: collapse; font-size: 16px; border: 2px solid #000;">
                <thead>`;

        // Page 2 Headers
        let hasSubColumns2 = page2Columns.some(col => col.hasSubColumns);
        html += '<tr>';
        page2Columns.forEach(col => {
            if (col.hasSubColumns) {
                html += `<th colspan="${col.subColumns.length}" style="border: 2px solid #000; padding: 6px 4px; background: #fff; color: #000; font-weight: bold; text-align: center; vertical-align: middle; font-size: 16px; word-wrap: break-word; width: ${100/page2Columns.length}%;">${col.header}</th>`;
            } else {
                html += `<th style="border: 2px solid #000; padding: 6px 4px; background: #fff; color: #000; font-weight: bold; text-align: center; vertical-align: middle; font-size: 16px; word-wrap: break-word; width: ${100/page2Columns.length}%;">${col.header}</th>`;
            }
        });
        html += '</tr>';

        html += `</thead><tbody><tr>`;

        // Page 2 Data
        page2Columns.forEach(col => {
            if (col.hasSubColumns) {
                col.subColumns.forEach(subCol => {
                    const value = subCol.getValue();
                    html += `<td style="border: 2px solid #000; padding: 5px 4px; vertical-align: top; text-align: left; line-height: 1.4; font-size: 16px; color: #000; word-wrap: break-word; overflow-wrap: break-word;">${value}</td>`;
                });
            } else {
                const value = col.getValue();
                html += `<td style="border: 2px solid #000; padding: 5px 4px; vertical-align: top; text-align: left; line-height: 1.4; font-size: 16px; color: #000; word-wrap: break-word; overflow-wrap: break-word;">${value}</td>`;
            }
        });

        html += `</tr></tbody></table>
        </div>`;

        return html;
    }

    // Bulk Print Functions
    window.toggleSelectAll = function() {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.case-checkbox');
        checkboxes.forEach(checkbox => {
            if (checkbox.closest('tr').style.display !== 'none') {
                checkbox.checked = selectAll.checked;
            }
        });
        updateBulkActions();
    }

    window.updateBulkActions = function() {
        const checkboxes = document.querySelectorAll('.case-checkbox:checked');
        const bulkActions = document.getElementById('bulkActions');
        const selectedCount = document.getElementById('selectedCount');

        if (checkboxes.length > 0) {
            bulkActions.style.display = 'flex';
            selectedCount.textContent = checkboxes.length + ' selected';
        } else {
            bulkActions.style.display = 'none';
        }

        // Update select all checkbox state
        const allCheckboxes = document.querySelectorAll('.case-checkbox');
        const visibleCheckboxes = Array.from(allCheckboxes).filter(cb => cb.closest('tr').style.display !== 'none');
        const selectAll = document.getElementById('selectAll');
        selectAll.checked = visibleCheckboxes.length > 0 && visibleCheckboxes.every(cb => cb.checked);
    }

    window.clearSelection = function() {
        const checkboxes = document.querySelectorAll('.case-checkbox');
        checkboxes.forEach(checkbox => checkbox.checked = false);
        document.getElementById('selectAll').checked = false;
        updateBulkActions();
    }

    window.bulkPrintCases = function() {
        const selectedCheckboxes = document.querySelectorAll('.case-checkbox:checked');

        if (selectedCheckboxes.length === 0) {
            alert('Please select at least one case to print');
            return;
        }

        // Store selected IDs for bulk print
        bulkPrintCaseIds = Array.from(selectedCheckboxes).map(cb => cb.value);
        bulkPrintMode = true;

        // Set bulk print info in modal
        currentPrintCaseData = {
            case_number: `${bulkPrintCaseIds.length} Cases Selected`
        };
        currentPrintHistory = [];

        // Open print modal for column selection
        const printModal = document.getElementById('printCaseModal');
        printModal.style.display = 'block';

        // Auto-select all fields for bulk print
        window.selectAllPrintFields();
    }

    // Close modal when clicking outside
    if (!window.printModalClickHandlerAdded) {
        window.addEventListener('click', function(event) {
            const printModal = document.getElementById('printCaseModal');
            if (event.target == printModal) {
                window.closePrintModal();
            }
        });
        window.printModalClickHandlerAdded = true;
    }

    // Restore filter values from URL parameters
    (function restoreFilters() {
        // Build params from PHP GET variables (server-side source of truth)
        const params = new URLSearchParams();
        <?php
        foreach ($_GET as $key => $value) {
            if ($key !== 'page') {
                echo "params.set('" . htmlspecialchars($key, ENT_QUOTES) . "', '" . htmlspecialchars($value, ENT_QUOTES) . "');\n        ";
            }
        }
        ?>

        console.log('Restoring filters:', params.toString());

        // Text search filters
        const searchCaseNumber = document.getElementById('searchCaseNumber');
        const searchRegister = document.getElementById('searchRegister');
        const searchInfoBook = document.getElementById('searchInfoBook');

        if (searchCaseNumber && params.has('searchCaseNumber'))
            searchCaseNumber.value = params.get('searchCaseNumber');
        if (searchRegister && params.has('searchRegister'))
            searchRegister.value = params.get('searchRegister');
        if (searchInfoBook && params.has('searchInfoBook'))
            searchInfoBook.value = params.get('searchInfoBook');

        // Dropdown filters
        const filterInfoBook = document.getElementById('filterInfoBook');
        const filterRegister = document.getElementById('filterRegister');
        const filterAttorneyAdvice = document.getElementById('filterAttorneyAdvice');
        const filterAnalystReport = document.getElementById('filterAnalystReport');

        if (filterInfoBook && params.has('filterInfoBook')) {
            const value = params.get('filterInfoBook');
            filterInfoBook.value = value;
            console.log('Set filterInfoBook to:', value, 'Actual value now:', filterInfoBook.value);
        }
        if (filterRegister && params.has('filterRegister')) {
            const value = params.get('filterRegister');
            filterRegister.value = value;
            console.log('Set filterRegister to:', value, 'Actual value now:', filterRegister.value);
        }
        if (filterAttorneyAdvice && params.has('filterAttorneyAdvice'))
            filterAttorneyAdvice.value = params.get('filterAttorneyAdvice');
        if (filterAnalystReport && params.has('filterAnalystReport'))
            filterAnalystReport.value = params.get('filterAnalystReport');

        // Date filters
        if (params.has('prevDateFrom'))
            document.getElementById('prevDateFrom').value = params.get('prevDateFrom');
        if (params.has('prevDateTo'))
            document.getElementById('prevDateTo').value = params.get('prevDateTo');
        if (params.has('prevDateExact'))
            document.getElementById('prevDateExact').checked = true;

        if (params.has('bReportDateFrom'))
            document.getElementById('bReportDateFrom').value = params.get('bReportDateFrom');
        if (params.has('bReportDateTo'))
            document.getElementById('bReportDateTo').value = params.get('bReportDateTo');
        if (params.has('bReportDateExact'))
            document.getElementById('bReportDateExact').checked = true;

        if (params.has('plantDateFrom'))
            document.getElementById('plantDateFrom').value = params.get('plantDateFrom');
        if (params.has('plantDateTo'))
            document.getElementById('plantDateTo').value = params.get('plantDateTo');
        if (params.has('plantDateExact'))
            document.getElementById('plantDateExact').checked = true;

        if (params.has('handoverDateFrom'))
            document.getElementById('handoverDateFrom').value = params.get('handoverDateFrom');
        if (params.has('handoverDateTo'))
            document.getElementById('handoverDateTo').value = params.get('handoverDateTo');
        if (params.has('handoverDateExact'))
            document.getElementById('handoverDateExact').checked = true;

        if (params.has('nextDateFrom'))
            document.getElementById('nextDateFrom').value = params.get('nextDateFrom');
        if (params.has('nextDateTo'))
            document.getElementById('nextDateTo').value = params.get('nextDateTo');
        if (params.has('nextDateExact'))
            document.getElementById('nextDateExact').checked = true;
    })();
</script>