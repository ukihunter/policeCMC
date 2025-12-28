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
                            <input type="checkbox" class="print-field" data-field="opens">
                            <span>Opens</span>
                        </label>
                        <label class="print-checkbox">
                            <input type="checkbox" class="print-field" data-field="progress">
                            <span>Progress Notes</span>
                        </label>
                        <label class="print-checkbox">
                            <input type="checkbox" class="print-field" data-field="results">
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
                            <span>Suspects</span>
                        </label>
                        <label class="print-checkbox">
                            <input type="checkbox" class="print-field" data-field="witnesses">
                            <span>Witnesses</span>
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
    }

    .btn-quick-select:hover {
        background: #4a9eff;
        color: white;
        border-color: #4a9eff;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(74, 158, 255, 0.3);
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
    }

    .btn-print:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
    }

    /* Print Styles */
    @media print {
        body * {
            visibility: hidden;
        }

        #printContent,
        #printContent * {
            visibility: visible;
        }

        #printContent {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
    }
</style>

<script>
    let currentPrintCaseData = null;
    let currentPrintHistory = null;

    function closePrintModal() {
        document.getElementById('printCaseModal').style.display = 'none';
    }

    function selectAllPrintFields() {
        document.querySelectorAll('.print-field').forEach(checkbox => {
            checkbox.checked = true;
        });
    }

    function deselectAllPrintFields() {
        document.querySelectorAll('.print-field').forEach(checkbox => {
            checkbox.checked = false;
        });
    }

    function selectCourtEssentials() {
        deselectAllPrintFields();
        const essentials = ['case_number', 'previous_date', 'register_number', 'next_date', 'suspects', 'witnesses', 'opens', 'attorney_general_advice'];
        essentials.forEach(field => {
            const checkbox = document.querySelector(`.print-field[data-field="${field}"]`);
            if (checkbox) checkbox.checked = true;
        });
    }

    function generatePrint() {
        if (!currentPrintCaseData) {
            alert('No case data available');
            return;
        }

        const selectedFields = [];
        document.querySelectorAll('.print-field:checked').forEach(checkbox => {
            selectedFields.push(checkbox.dataset.field);
        });

        if (selectedFields.length === 0) {
            alert('Please select at least one field to print');
            return;
        }

        const printContent = generatePrintHTML(currentPrintCaseData, currentPrintHistory, selectedFields);

        // Create or update print container
        let printContainer = document.getElementById('printContent');
        if (!printContainer) {
            printContainer = document.createElement('div');
            printContainer.id = 'printContent';
            document.body.appendChild(printContainer);
        }

        printContainer.innerHTML = printContent;

        // Close modal and print
        closePrintModal();

        // Small delay to ensure rendering
        setTimeout(() => {
            window.print();
        }, 100);
    }

    function generatePrintHTML(caseData, history, selectedFields) {
        const formatDate = (dateString) => {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleDateString('en-GB', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });
        };

        let html = `
        <div style="font-family: 'Times New Roman', Times, serif; max-width: 900px; margin: 0 auto; padding: 30px;">
            <div style="text-align: center; margin-bottom: 40px; border-bottom: 3px solid #000; padding-bottom: 25px;">
                <h1 style="color: #000; margin-bottom: 5px; font-size: 24px; font-weight: bold; text-transform: uppercase;">POLICE CASE MANAGEMENT SYSTEM</h1>
                <h2 style="color: #000; font-weight: normal; font-size: 18px; margin-top: 10px;">Case Details Report</h2>
                <p style="color: #333; font-size: 11px; margin-top: 15px;">Generated on: ${new Date().toLocaleString('en-GB')}</p>
            </div>
    `;

        // Basic Information
        if (selectedFields.some(f => ['case_number', 'previous_date', 'information_book', 'register_number'].includes(f))) {
            html += `
            <div style="margin-bottom: 25px; page-break-inside: avoid;">
                <h3 style="color: #000; border-bottom: 2px solid #000; padding-bottom: 8px; margin-bottom: 15px; font-size: 16px; text-transform: uppercase;">
                    BASIC INFORMATION
                </h3>
                <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
        `;

            if (selectedFields.includes('case_number')) {
                html += `<tr><td style="padding: 10px 8px; font-weight: bold; width: 40%; border-bottom: 1px solid #ddd;">Case Number:</td><td style="padding: 10px 8px; border-bottom: 1px solid #ddd;">${caseData.case_number || '-'}</td></tr>`;
            }
            if (selectedFields.includes('previous_date')) {
                html += `<tr><td style="padding: 10px 8px; font-weight: bold; border-bottom: 1px solid #ddd;">Previous Date:</td><td style="padding: 10px 8px; border-bottom: 1px solid #ddd;">${formatDate(caseData.previous_date)}</td></tr>`;
            }
            if (selectedFields.includes('information_book')) {
                html += `<tr><td style="padding: 10px 8px; font-weight: bold; border-bottom: 1px solid #ddd;">Information Book:</td><td style="padding: 10px 8px; border-bottom: 1px solid #ddd;">${caseData.information_book || '-'}</td></tr>`;
            }
            if (selectedFields.includes('register_number')) {
                html += `<tr><td style="padding: 10px 8px; font-weight: bold; border-bottom: 1px solid #ddd;">Register Number:</td><td style="padding: 10px 8px; border-bottom: 1px solid #ddd;">${caseData.register_number || '-'}</td></tr>`;
            }

            html += `</table></div>`;
        }

        // Important Dates
        if (selectedFields.some(f => ['date_produce_b_report', 'date_produce_plant', 'date_handover_court', 'next_date'].includes(f))) {
            html += `
            <div style="margin-bottom: 25px; page-break-inside: avoid;">
                <h3 style="color: #000; border-bottom: 2px solid #000; padding-bottom: 8px; margin-bottom: 15px; font-size: 16px; text-transform: uppercase;">
                    IMPORTANT DATES
                </h3>
                <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
        `;

            if (selectedFields.includes('date_produce_b_report')) {
                html += `<tr><td style="padding: 10px 8px; font-weight: bold; width: 40%; border-bottom: 1px solid #ddd;">Date Produce B Report:</td><td style="padding: 10px 8px; border-bottom: 1px solid #ddd;">${formatDate(caseData.date_produce_b_report)}</td></tr>`;
            }
            if (selectedFields.includes('date_produce_plant')) {
                html += `<tr><td style="padding: 10px 8px; font-weight: bold; border-bottom: 1px solid #ddd;">Date Produce Plant:</td><td style="padding: 10px 8px; border-bottom: 1px solid #ddd;">${formatDate(caseData.date_produce_plant)}</td></tr>`;
            }
            if (selectedFields.includes('date_handover_court')) {
                html += `<tr><td style="padding: 10px 8px; font-weight: bold; border-bottom: 1px solid #ddd;">Date Handover Court:</td><td style="padding: 10px 8px; border-bottom: 1px solid #ddd;">${formatDate(caseData.date_handover_court)}</td></tr>`;
            }
            if (selectedFields.includes('next_date')) {
                html += `<tr><td style="padding: 10px 8px; font-weight: bold; border-bottom: 1px solid #ddd;">Next Date:</td><td style="padding: 10px 8px; font-size: 14px; font-weight: bold; border-bottom: 1px solid #ddd;">${formatDate(caseData.next_date)}</td></tr>`;
            }

            html += `</table></div>`;
        }

        // Case Details
        if (selectedFields.includes('opens')) {
            html += `
            <div style="margin-bottom: 25px; page-break-inside: avoid;">
                <h3 style="color: #000; border-bottom: 2px solid #000; padding-bottom: 8px; margin-bottom: 15px; font-size: 16px; text-transform: uppercase;">
                    CASE DETAILS - OPENS
                </h3>
                <p style="line-height: 1.8; color: #000; text-align: justify; font-size: 13px;">${(caseData.opens || 'No information provided').replace(/\n/g, '<br>')}</p>
            </div>
        `;
        }

        // Legal & Reports
        if (selectedFields.some(f => ['attorney_general_advice', 'receival_memorandum', 'analyst_report', 'production_register_number'].includes(f))) {
            html += `
            <div style="margin-bottom: 25px; page-break-inside: avoid;">
                <h3 style="color: #000; border-bottom: 2px solid #000; padding-bottom: 8px; margin-bottom: 15px; font-size: 16px; text-transform: uppercase;">
                    LEGAL AND REPORTS
                </h3>
                <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
        `;

            if (selectedFields.includes('attorney_general_advice')) {
                html += `<tr><td style="padding: 10px 8px; font-weight: bold; width: 40%; border-bottom: 1px solid #ddd;">Attorney General's Advice:</td><td style="padding: 10px 8px; border-bottom: 1px solid #ddd; font-weight: bold;">${caseData.attorney_general_advice || '-'}</td></tr>`;
            }
            if (selectedFields.includes('receival_memorandum')) {
                html += `<tr><td style="padding: 10px 8px; font-weight: bold; border-bottom: 1px solid #ddd;">Receival Memorandum:</td><td style="padding: 10px 8px; border-bottom: 1px solid #ddd; font-weight: bold;">${caseData.receival_memorandum || '-'}</td></tr>`;
            }
            if (selectedFields.includes('analyst_report')) {
                html += `<tr><td style="padding: 10px 8px; font-weight: bold; border-bottom: 1px solid #ddd;">Analyst Report:</td><td style="padding: 10px 8px; border-bottom: 1px solid #ddd; font-weight: bold;">${caseData.analyst_report || '-'}</td></tr>`;
            }
            if (selectedFields.includes('production_register_number') && caseData.production_register_number) {
                html += `<tr><td style="padding: 10px 8px; font-weight: bold; vertical-align: top; border-bottom: 1px solid #ddd;">Production Register Number:</td><td style="padding: 10px 8px; border-bottom: 1px solid #ddd;">${(caseData.production_register_number || '-').replace(/\n/g, '<br>')}</td></tr>`;
            }

            html += `</table></div>`;
        }

        // Suspects
        if (selectedFields.includes('suspects')) {
            const suspects = JSON.parse(caseData.suspect_data || '[]');
            html += `
            <div style="margin-bottom: 25px; page-break-inside: avoid;">
                <h3 style="color: #000; border-bottom: 2px solid #000; padding-bottom: 8px; margin-bottom: 15px; font-size: 16px; text-transform: uppercase;">
                    SUSPECTS (${suspects.length})
                </h3>
        `;

            if (suspects.length > 0) {
                suspects.forEach((suspect, index) => {
                    html += `
                    <div style="margin-bottom: 15px; padding: 15px; border: 1px solid #000; border-left: 4px solid #000;">
                        <h4 style="margin-bottom: 10px; color: #000; font-size: 14px;">Suspect ${index + 1}</h4>
                        <table style="width: 100%; font-size: 13px;">
                            <tr><td style="padding: 5px 0; font-weight: bold; width: 25%;">Name:</td><td style="padding: 5px 0;">${suspect.name || '-'}</td></tr>
                            <tr><td style="padding: 5px 0; font-weight: bold;">IC Number:</td><td style="padding: 5px 0;">${suspect.ic || '-'}</td></tr>
                            <tr><td style="padding: 5px 0; font-weight: bold; vertical-align: top;">Address:</td><td style="padding: 5px 0;">${suspect.address || '-'}</td></tr>
                        </table>
                    </div>
                `;
                });
            } else {
                html += `<p style="color: #666; font-style: italic; font-size: 13px;">No suspects recorded</p>`;
            }

            html += `</div>`;
        }

        // Witnesses
        if (selectedFields.includes('witnesses')) {
            const witnesses = JSON.parse(caseData.witness_data || '[]');
            html += `
            <div style="margin-bottom: 25px; page-break-inside: avoid;">
                <h3 style="color: #000; border-bottom: 2px solid #000; padding-bottom: 8px; margin-bottom: 15px; font-size: 16px; text-transform: uppercase;">
                    WITNESSES (${witnesses.length})
                </h3>
        `;

            if (witnesses.length > 0) {
                witnesses.forEach((witness, index) => {
                    html += `
                    <div style="margin-bottom: 15px; padding: 15px; border: 1px solid #000; border-left: 4px solid #000;">
                        <h4 style="margin-bottom: 10px; color: #000; font-size: 14px;">Witness ${index + 1}</h4>
                        <table style="width: 100%; font-size: 13px;">
                            <tr><td style="padding: 5px 0; font-weight: bold; width: 25%;">Name:</td><td style="padding: 5px 0;">${witness.name || '-'}</td></tr>
                            <tr><td style="padding: 5px 0; font-weight: bold;">IC Number:</td><td style="padding: 5px 0;">${witness.ic || '-'}</td></tr>
                            <tr><td style="padding: 5px 0; font-weight: bold; vertical-align: top;">Address:</td><td style="padding: 5px 0;">${witness.address || '-'}</td></tr>
                        </table>
                    </div>
                `;
                });
            } else {
                html += `<p style="color: #666; font-style: italic; font-size: 13px;">No witnesses recorded</p>`;
            }

            html += `</div>`;
        }

        // Progress
        if (selectedFields.includes('progress') && caseData.progress) {
            html += `
            <div style="margin-bottom: 25px; page-break-inside: avoid;">
                <h3 style="color: #000; border-bottom: 2px solid #000; padding-bottom: 8px; margin-bottom: 15px; font-size: 16px; text-transform: uppercase;">
                    PROGRESS NOTES
                </h3>
                <p style="line-height: 1.8; color: #000; text-align: justify; font-size: 13px;">${(caseData.progress || 'No progress notes').replace(/\n/g, '<br>')}</p>
            </div>
        `;
        }

        // Results
        if (selectedFields.includes('results') && caseData.results) {
            html += `
            <div style="margin-bottom: 25px; page-break-inside: avoid;">
                <h3 style="color: #000; border-bottom: 2px solid #000; padding-bottom: 8px; margin-bottom: 15px; font-size: 16px; text-transform: uppercase;">
                    RESULTS
                </h3>
                <p style="line-height: 1.8; color: #000; text-align: justify; font-size: 13px;">${(caseData.results || 'No results recorded').replace(/\n/g, '<br>')}</p>
            </div>
        `;
        }

        // Next Date History
        if (selectedFields.includes('next_date_history') && history && history.length > 0) {
            html += `
            <div style="margin-bottom: 25px; page-break-inside: avoid;">
                <h3 style="color: #000; border-bottom: 2px solid #000; padding-bottom: 8px; margin-bottom: 15px; font-size: 16px; text-transform: uppercase;">
                    NEXT DATE HISTORY (${history.length} ENTRIES)
                </h3>
        `;

            history.forEach((entry, index) => {
                html += `
                <div style="margin-bottom: 12px; padding: 12px; border: 1px solid #ddd; ${index === 0 ? 'border-left: 4px solid #000; background: #f5f5f5;' : ''}">
                    <p style="margin: 5px 0; font-size: 13px;"><strong>Date:</strong> ${formatDate(entry.next_date)} ${index === 0 ? '<strong>(CURRENT)</strong>' : ''}</p>
                    ${entry.notes ? `<p style="margin: 5px 0; font-size: 13px;"><strong>Notes:</strong> ${entry.notes}</p>` : ''}
                    <p style="margin: 5px 0; font-size: 12px; color: #666;"><strong>Set by:</strong> ${entry.created_by_name || 'Unknown'} on ${new Date(entry.created_at).toLocaleString('en-GB')}</p>
                </div>
            `;
            });

            html += `</div>`;
        }

        html += `
            <div style="margin-top: 50px; padding-top: 20px; border-top: 2px solid #000; text-align: center; font-size: 11px; color: #333;">
                <p style="margin: 5px 0;">This document is an official record from the Police Case Management System</p>
                <p style="margin: 5px 0;">Generated automatically - No signature required for system records</p>
            </div>
        </div>
    `;

        return html;
    }

    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        const printModal = document.getElementById('printCaseModal');
        if (event.target == printModal) {
            closePrintModal();
        }
    });
</script>