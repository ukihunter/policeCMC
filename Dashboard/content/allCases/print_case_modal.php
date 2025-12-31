<!-- Print Case Modal -->
<div id="printCaseModal" class="modal">
    <div class="modal-content" style="max-width: 900px;">
        <div class="modal-header">
            <h2><i class="fas fa-print"></i> Print Case Details</h2>
            <span class="close-modal" onclick="closePrintModal()">&times;</span>
        </div>
        <div class="modal-body">
            <div class="print-options-section">
                <h3><i class="fas fa-cog"></i> Select Information to Print</h3>
                <p style="color: #666; font-size: 14px; margin-bottom: 20px;">
                    Choose which details you want to include in the printed document
                </p>

                <div class="print-options-grid">
                    <!-- Basic Information -->
                    <div class="print-category">
                        <h4><i class="fas fa-info-circle"></i> Basic Information</h4>
                        <label class="print-checkbox">
                            <input type="checkbox" class="print-field" data-field="case_number" checked>
                            <span>Case Number</span>
                        </label>
                        <label class="print-checkbox">
                            <input type="checkbox" class="print-field" data-field="previous_date" checked>
                            <span>Previous Date</span>
                        </label>
                        <label class="print-checkbox">
                            <input type="checkbox" class="print-field" data-field="information_book" checked>
                            <span>Information Book</span>
                        </label>
                        <label class="print-checkbox">
                            <input type="checkbox" class="print-field" data-field="register_number" checked>
                            <span>Register Number</span>
                        </label>
                    </div>

                    <!-- Important Dates -->
                    <div class="print-category">
                        <h4><i class="fas fa-calendar-alt"></i> Important Dates</h4>
                        <label class="print-checkbox">
                            <input type="checkbox" class="print-field" data-field="date_produce_b_report">
                            <span>Date Produce B Report</span>
                        </label>
                        <label class="print-checkbox">
                            <input type="checkbox" class="print-field" data-field="date_produce_plant">
                            <span>Date Produce Plant</span>
                        </label>
                        <label class="print-checkbox">
                            <input type="checkbox" class="print-field" data-field="date_handover_court">
                            <span>Date Handover Court</span>
                        </label>
                        <label class="print-checkbox">
                            <input type="checkbox" class="print-field" data-field="next_date" checked>
                            <span>Next Date</span>
                        </label>
                    </div>

                    <!-- Case Details -->
                    <div class="print-category">
                        <h4><i class="fas fa-file-alt"></i> Case Details</h4>
                        <label class="print-checkbox">
                            <input type="checkbox" class="print-field" data-field="opens" checked>
                            <span>Offense</span>
                        </label>
                        <label class="print-checkbox">
                            <input type="checkbox" class="print-field" data-field="progress" checked>
                            <span>Progress Notes</span>
                        </label>
                        <label class="print-checkbox">
                            <input type="checkbox" class="print-field" data-field="results" checked>
                            <span>Results</span>
                        </label>
                    </div>

                    <!-- Legal & Reports -->
                    <div class="print-category">
                        <h4><i class="fas fa-gavel"></i> Legal & Reports</h4>
                        <label class="print-checkbox">
                            <input type="checkbox" class="print-field" data-field="attorney_general_advice">
                            <span>Attorney General's Advice</span>
                        </label>
                        <label class="print-checkbox">
                            <input type="checkbox" class="print-field" data-field="receival_memorandum">
                            <span>Receival Memorandum</span>
                        </label>
                        <label class="print-checkbox">
                            <input type="checkbox" class="print-field" data-field="analyst_report">
                            <span>Analyst Report</span>
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" class="print-field" data-field="production_register_number">
                            <span>Production Register Number</span>
                        </label>
                    </div>

                    <!-- People -->
                    <div class="print-category">
                        <h4><i class="fas fa-users"></i> People Involved</h4>
                        <label class="print-checkbox">
                            <input type="checkbox" class="print-field" data-field="suspects" checked>
                            <span>Suspects (Police Area)</span>
                        </label>
                        <label class="print-checkbox">
                            <input type="checkbox" class="print-field" data-field="witnesses" checked>
                            <span>Witnesses (Police Area)</span>
                        </label>
                    </div>

                    <!-- Next Date History -->
                    <div class="print-category">
                        <h4><i class="fas fa-history"></i> History</h4>
                        <label class="print-checkbox">
                            <input type="checkbox" class="print-field" data-field="next_date_history">
                            <span>Next Date History</span>
                        </label>
                    </div>
                </div>

                <div class="print-layout-section">
                    <h4><i class="fas fa-th-large"></i> Page Layout</h4>
                    <p style="color: #666; font-size: 13px; margin-bottom: 15px;">
                        Legal paper in landscape mode - columns split across 2 pages
                    </p>
                    <div class="layout-options">
                        <label class="layout-radio">
                            <input type="radio" name="printLayout" value="dual" checked>
                            <div class="layout-card">
                                <i class="fas fa-columns"></i>
                                <span class="layout-title">Dual Page Legal Landscape</span>
                                <span class="layout-desc">Split across 2 legal pages (full width columns)</span>
                            </div>
                        </label>
                        <label class="layout-radio">
                            <input type="radio" name="printLayout" value="single">
                            <div class="layout-card">
                                <i class="fas fa-file"></i>
                                <span class="layout-title">Single Page</span>
                                <span class="layout-desc">All columns compressed on one page</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="print-quick-select">
                    <button type="button" class="btn-quick-select" onclick="selectAllPrintFields()">
                        <i class="fas fa-check-double"></i> Select All
                    </button>
                    <button type="button" class="btn-quick-select" onclick="deselectAllPrintFields()">
                        <i class="fas fa-times"></i> Deselect All
                    </button>
                    <button type="button" class="btn-quick-select" onclick="selectCourtEssentials()">
                        <i class="fas fa-balance-scale"></i> Court Essentials Only
                    </button>
                </div>

                <div class="print-actions">
                    <button type="button" class="btn-secondary" onclick="closePrintModal()">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="button" class="btn-print" onclick="generatePrint()">
                        <i class="fas fa-print"></i> Print Selected
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .print-options-section h3 {
        color: #0a1628;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .print-options-section h3 i {
        color: #4a9eff;
    }

    .print-options-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 25px;
    }

    .print-category {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        border-left: 4px solid #4a9eff;
    }

    .print-category h4 {
        color: #0a1628;
        font-size: 15px;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .print-category h4 i {
        color: #4a9eff;
        font-size: 14px;
    }

    .print-checkbox {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 0;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .print-checkbox:hover {
        padding-left: 5px;
    }

    .print-checkbox input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .print-checkbox span {
        font-size: 14px;
        color: #333;
        user-select: none;
    }

    .print-quick-select {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        padding: 15px;
        background: #e9ecef;
        border-radius: 8px;
    }

    .btn-quick-select {
        flex: 1;
        padding: 10px 15px;
        background: white;
        border: 1px solid #ced4da;
        border-radius: 6px;
        cursor: pointer;
        font-size: 13px;
        font-weight: 500;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        pointer-events: auto;
        position: relative;
        z-index: 1;
    }

    .btn-quick-select:hover {
        background: #4a9eff;
        color: white;
        border-color: #4a9eff;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(74, 158, 255, 0.3);
    }

    .btn-quick-select:active {
        transform: translateY(0);
    }

    .print-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        padding-top: 20px;
        border-top: 2px solid #e0e0e0;
    }

    .btn-print {
        padding: 12px 24px;
        background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        pointer-events: auto;
        position: relative;
        z-index: 1;
    }

    .btn-print:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
    }

    .btn-print:active {
        transform: translateY(0);
    }

    .btn-secondary {
        padding: 12px 24px;
        background: #6c757d;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        pointer-events: auto;
        position: relative;
        z-index: 1;
    }

    .btn-secondary:hover {
        background: #5a6268;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(108, 117, 125, 0.4);
    }

    .btn-secondary:active {
        transform: translateY(0);
    }

    /* Print Layout Options */
    .print-layout-section {
        margin-bottom: 25px;
        padding: 20px;
        background: #f0f8ff;
        border-radius: 8px;
        border: 2px solid #4a9eff;
    }

    .print-layout-section h4 {
        color: #0a1628;
        font-size: 16px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .print-layout-section h4 i {
        color: #4a9eff;
    }

    .layout-options {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        margin-top: 15px;
    }

    .layout-radio {
        cursor: pointer;
    }

    .layout-radio input[type="radio"] {
        display: none;
    }

    .layout-card {
        padding: 20px;
        background: white;
        border: 2px solid #ced4da;
        border-radius: 8px;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        text-align: center;
    }

    .layout-card i {
        font-size: 32px;
        color: #6c757d;
        transition: all 0.3s ease;
    }

    .layout-title {
        font-size: 15px;
        font-weight: 600;
        color: #0a1628;
    }

    .layout-desc {
        font-size: 12px;
        color: #666;
    }

    .layout-radio input[type="radio"]:checked+.layout-card {
        border-color: #4a9eff;
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        box-shadow: 0 4px 12px rgba(74, 158, 255, 0.3);
    }

    .layout-radio input[type="radio"]:checked+.layout-card i {
        color: #4a9eff;
        transform: scale(1.1);
    }

    .layout-card:hover {
        border-color: #4a9eff;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* Hide print content on screen */
    #printContent {
        display: none;
    }

    /* Print Styles */
    @media print {
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            margin: 0 !important;
            padding: 0 !important;
            width: 100%;
        }

        body * {
            visibility: hidden;
        }

        #printContent {
            display: block !important;
            visibility: visible;
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0 !important;
            padding: 0 !important;
        }

        #printContent * {
            visibility: visible;
        }

        /* Single Page Layout - Legal Landscape */
        @page {
            size: legal landscape;
            margin: 5mm;
        }

        /* Dual Page Layout - Legal Landscape Full Width */
        body.dual-page-print {
            margin: 0 !important;
            padding: 0 !important;
        }

        body.dual-page-print #printContent {
            width: 100%;
            max-width: none;
        }

        body.dual-page-print #printContent .page-1,
        body.dual-page-print #printContent .page-2 {
            page-break-after: always;
            width: 100%;
            max-width: 100%;
            margin: 0;
            padding: 5mm;
            box-sizing: border-box;
        }

        body.dual-page-print #printContent .page-2 {
            page-break-after: auto;
        }

        body.dual-page-print #printContent table {
            width: 100% !important;
            max-width: 100% !important;
            table-layout: fixed !important;
            border-collapse: collapse !important;
        }

        body.dual-page-print #printContent th,
        body.dual-page-print #printContent td {
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        /* Single page print styles */
        body:not(.dual-page-print) #printContent {
            width: 100%;
            max-width: none;
            padding: 0;
            margin: 0;
        }

        body:not(.dual-page-print) #printContent table {
            width: 100% !important;
            max-width: 100% !important;
            table-layout: fixed !important;
        }
    }
</style>