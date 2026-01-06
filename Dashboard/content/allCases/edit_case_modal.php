<!-- Edit Case Modal -->
<div id="editCaseModal" class="modal">
    <div class="modal-content" style="max-width: 1400px;">
        <div class="modal-header">
            <h2><i class="fas fa-edit"></i> Edit Case</h2>
            <span class="close-modal" onclick="closeEditModal()">&times;</span>
        </div>
        <div class="modal-body" id="editModalBody">
            <form id="editCaseForm" method="post" onsubmit="return false;">
                <input type="hidden" id="edit_case_id" name="case_id">

                <div class="form-section">
                    <h3><i class="fas fa-info-circle"></i> Basic Information</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="edit_case_number">Case Number *</label>
                            <input type="text" id="edit_case_number" name="case_number" required>
                        </div>

                        <div class="form-group">
                            <label for="edit_previous_date">Previous Date *</label>
                            <input type="date" id="edit_previous_date" name="previous_date" required>
                        </div>

                        <div class="form-group full-width">
                            <label style="margin-bottom: 5px;">Register Information *</label>
                            <div style="display: grid; grid-template-columns: 2fr 2fr 1fr; gap: 15px;">
                                <div style="display: flex; flex-direction: column; gap: 5px;">
                                    <label for="edit_register_type" style="font-size: 13px; font-weight: 500;">Register Type *</label>
                                    <select id="edit_register_type" name="register_type">
                                        <option value="">-- Select Register Type --</option>
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
                                </div>
                                <div style="display: flex; flex-direction: column; gap: 5px;">
                                    <label for="edit_register_month" style="font-size: 13px; font-weight: 500;">Register Month *</label>
                                    <select id="edit_register_month" name="register_month">
                                        <option value="">-- Select Month --</option>
                                        <option value="01">January</option>
                                        <option value="02">February</option>
                                        <option value="03">March</option>
                                        <option value="04">April</option>
                                        <option value="05">May</option>
                                        <option value="06">June</option>
                                        <option value="07">July</option>
                                        <option value="08">August</option>
                                        <option value="09">September</option>
                                        <option value="10">October</option>
                                        <option value="11">November</option>
                                        <option value="12">December</option>
                                    </select>
                                </div>
                                <div style="display: flex; flex-direction: column; gap: 5px;">
                                    <label for="edit_register_year" style="font-size: 13px; font-weight: 500;">Register Year *</label>
                                    <input type="text" id="edit_register_year" name="register_year"
                                        placeholder="YYYY" maxlength="4" pattern="[0-9]{4}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="edit_register_number_display">Register Number</label>
                            <input type="text" id="edit_register_number_display"
                                style="background-color: #f3f4f6;"
                                placeholder="Will be generated from selections above or enter manually">
                            <input type="hidden" id="edit_register_number" name="register_number">
                            <small style="color: #6b7280; font-size: 12px; margin-top: 4px; display: block;">Auto-generated from selections, but you can edit it manually</small>
                        </div>
                        <div class="form-group full-width">
                            <label for="edit_information_book">Information Book *</label>
                            <select id="edit_information_book" name="information_book" required style="padding: 10px 15px; border: 1px solid #e0e0e0; border-radius: 6px; font-size: 14px; font-family: inherit;">
                                <option value="">Select Information Book</option>
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
                                <option value="CUSTOM">Other (Type Custom Value)</option>
                            </select>
                            <input type="text" id="edit_information_book_custom" name="information_book_custom" style="display:none; margin-top: 10px; padding: 10px 15px; border: 1px solid #e0e0e0; border-radius: 6px; font-size: 14px; width: 100%;" placeholder="Type custom Information Book">
                            <small style="color: #6b7280; font-size: 12px; margin-top: 4px; display: block;">Select from dropdown or choose "Other" to type custom value</small>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3><i class="fas fa-calendar-alt"></i> Important Dates</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="edit_date_produce_b_report">Date Produce B Report</label>
                            <input type="date" id="edit_date_produce_b_report" name="date_produce_b_report">
                        </div>
                        <div class="form-group">
                            <label for="edit_date_produce_plant">Date Produce Plant</label>
                            <input type="date" id="edit_date_produce_plant" name="date_produce_plant">
                        </div>
                        <div class="form-group">
                            <label for="edit_date_handover_court">Date Handover Court</label>
                            <input type="date" id="edit_date_handover_court" name="date_handover_court">
                        </div>
                        <div class="form-group">
                            <label for="edit_next_date">Next Date</label>
                            <input type="date" id="edit_next_date" name="next_date">
                        </div>
                        <div class="form-group full-width">
                            <label for="edit_next_date_notes">Next Date Notes (Optional)</label>
                            <textarea id="edit_next_date_notes" name="next_date_notes" rows="2" placeholder="Add notes about this next date..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3><i class="fas fa-file-alt"></i> Case Details</h3>
                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label for="edit_opens">Opens</label>
                            <textarea id="edit_opens" name="opens" rows="3"></textarea>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3><i class="fas fa-gavel"></i> Legal & Reports</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="edit_attorney_general_advice">Attorney General's Advice</label>
                            <select id="edit_attorney_general_advice" name="attorney_general_advice">
                                <option value="">Not Set</option>
                                <option value="YES">YES</option>
                                <option value="NO">NO</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_receival_memorandum">Receival Memorandum</label>
                            <select id="edit_receival_memorandum" name="receival_memorandum">
                                <option value="">Not Set</option>
                                <option value="YES">YES</option>
                                <option value="NO">NO</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_analyst_report">Analyst Report</label>
                            <select id="edit_analyst_report" name="analyst_report">
                                <option value="">Not Set</option>
                                <option value="YES">YES</option>
                                <option value="NO">NO</option>
                            </select>
                        </div>
                        <div class="form-group full-width">
                            <label for="edit_production_register_number">Production Register Number</label>
                            <textarea id="edit_production_register_number" name="production_register_number" rows="2"></textarea>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3><i class="fas fa-user-secret"></i> Suspects</h3>
                    <div id="edit_suspects_container">
                        <!-- Suspects will be dynamically added here -->
                    </div>
                    <button type="button" class="btn-add-item" onclick="addEditSuspect()">
                        <i class="fas fa-plus"></i> Add Suspect
                    </button>
                </div>

                <div class="form-section">
                    <h3><i class="fas fa-users"></i> Witnesses</h3>
                    <div id="edit_witnesses_container">
                        <!-- Witnesses will be dynamically added here -->
                    </div>
                    <button type="button" class="btn-add-item" onclick="addEditWitness()">
                        <i class="fas fa-plus"></i> Add Witness
                    </button>
                </div>

                <div class="form-section">
                    <h3><i class="fas fa-chart-line"></i> Progress & Results</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="edit_case_status">Case Status <span style="color: red;">*</span></label>
                            <select id="edit_case_status" name="case_status" required>
                                <option value="Ongoing">Ongoing</option>
                                <option value="Pending">Pending</option>
                                <option value="Closed">Closed</option>
                            </select>
                        </div>
                        <div class="form-group full-width">
                            <label for="edit_progress">Progress Notes</label>
                            <textarea id="edit_progress" name="progress" rows="4"></textarea>
                        </div>
                        <div class="form-group full-width">
                            <label for="edit_results">Results</label>
                            <textarea id="edit_results" name="results" rows="4"></textarea>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn-secondary" onclick="closeEditModal()">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" id="saveChangesBtn" class="btn-primary">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .form-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        border-left: 4px solid #4a9eff;
    }

    .form-section h3 {
        margin: 0 0 15px 0;
        color: #0a1628;
        font-size: 18px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-section h3 i {
        color: #4a9eff;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 15px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-group label {
        font-weight: 600;
        color: #555;
        font-size: 14px;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        padding: 10px 15px;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        font-size: 14px;
        font-family: inherit;
        transition: all 0.3s ease;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #4a9eff;
        box-shadow: 0 0 0 3px rgba(74, 158, 255, 0.1);
    }

    .form-group textarea {
        resize: vertical;
        min-height: 60px;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        padding-top: 20px;
        border-top: 2px solid #e0e0e0;
    }

    .btn-primary,
    .btn-secondary {
        padding: 12px 24px;
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

    .btn-primary {
        background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
    }

    .btn-secondary {
        background: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background: #5a6268;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(108, 117, 125, 0.4);
    }

    #editCaseForm {
        max-height: 70vh;
        overflow-y: auto;
        padding-right: 10px;
    }

    #editCaseForm::-webkit-scrollbar {
        width: 8px;
    }

    #editCaseForm::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    #editCaseForm::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }

    #editCaseForm::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    .person-entry {
        background: white;
        padding: 15px;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        margin-bottom: 15px;
        position: relative;
    }

    .person-entry-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #f0f0f0;
    }

    .person-entry-title {
        font-weight: 600;
        color: #0a1628;
        font-size: 15px;
    }

    .btn-remove-person {
        background: #dc3545;
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 12px;
        transition: all 0.3s ease;
    }

    .btn-remove-person:hover {
        background: #c82333;
        transform: translateY(-1px);
    }

    .btn-add-item {
        background: linear-gradient(135deg, #4a9eff 0%, #2d7dd2 100%);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-top: 10px;
    }

    .btn-add-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(74, 158, 255, 0.4);
    }
</style>

<script>
    let editSuspectCounter = 0;
    let editWitnessCounter = 0;

    // Handle Information Book change - set up when modal is ready
    setTimeout(function() {
        const infoBookSelect = document.getElementById('edit_information_book');
        if (infoBookSelect) {
            infoBookSelect.addEventListener('change', function() {
                const customInput = document.getElementById('edit_information_book_custom');
                if (customInput) {
                    if (this.value === 'CUSTOM') {
                        customInput.style.display = 'block';
                        customInput.required = true;
                        this.required = false;
                    } else {
                        customInput.style.display = 'none';
                        customInput.required = false;
                        this.required = true;
                    }
                }
            });
        }
    }, 100);

    function closeEditModal() {
        document.getElementById('editCaseModal').style.display = 'none';
    }

    window.addEditSuspect = function(suspectData = null) {
        const container = document.getElementById('edit_suspects_container');
        const index = editSuspectCounter++;

        const suspectHtml = `
        <div class="person-entry" id="edit_suspect_${index}">
            <div class="person-entry-header">
                <span class="person-entry-title">
                    <i class="fas fa-user-secret"></i> Suspect #${index + 1}
                </span>
                <button type="button" class="btn-remove-person" onclick="removeEditSuspect(${index})">
                    <i class="fas fa-trash"></i> Remove
                </button>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="suspects[${index}][name]" 
                           value="${suspectData?.name || ''}" 
                           placeholder="Suspect name">
                </div>
                <div class="form-group">
                    <label>NIC Number</label>
                    <input type="text" name="suspects[${index}][ic]" 
                           value="${suspectData?.ic || ''}" 
                           placeholder="NIC number">
                </div>
                <div class="form-group full-width">
                    <label>Address</label>
                    <textarea name="suspects[${index}][address]" 
                              rows="2" 
                              placeholder="Suspect address">${suspectData?.address || ''}</textarea>
                </div>
            </div>
        </div>
    `;

        container.insertAdjacentHTML('beforeend', suspectHtml);
    }

    window.removeEditSuspect = function(index) {
        const element = document.getElementById('edit_suspect_' + index);
        if (element) {
            element.remove();
        }
    }

    window.addEditWitness = function(witnessData = null) {
        const container = document.getElementById('edit_witnesses_container');
        const index = editWitnessCounter++;

        const witnessHtml = `
        <div class="person-entry" id="edit_witness_${index}">
            <div class="person-entry-header">
                <span class="person-entry-title">
                    <i class="fas fa-user"></i> Witness #${index + 1}
                </span>
                <button type="button" class="btn-remove-person" onclick="removeEditWitness(${index})">
                    <i class="fas fa-trash"></i> Remove
                </button>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="witnesses[${index}][name]" 
                           value="${witnessData?.name || ''}" 
                           placeholder="Witness name">
                </div>
                <div class="form-group">
                    <label>NIC Number</label>
                    <input type="text" name="witnesses[${index}][ic]" 
                           value="${witnessData?.ic || ''}" 
                           placeholder="NIC number">
                </div>
                <div class="form-group full-width">
                    <label>Address</label>
                    <textarea name="witnesses[${index}][address]" 
                              rows="2" 
                              placeholder="Witness address">${witnessData?.address || ''}</textarea>
                </div>
            </div>
        </div>
    `;

        container.insertAdjacentHTML('beforeend', witnessHtml);
    }

    window.removeEditWitness = function(index) {
        const element = document.getElementById('edit_witness_' + index);
        if (element) {
            element.remove();
        }
    }

    // Function to handle form submission (will be called from attachEditFormHandlers)
    function handleEditFormSubmit(e) {
        e.preventDefault();
        console.log('✓ Form submit handler triggered');

        const form = document.getElementById('editCaseForm');
        if (!form) {
            console.error('Form not found in submit handler');
            return;
        }

        const formData = new FormData(form);

        // Handle custom information book
        const infoBookSelect = document.getElementById('edit_information_book');
        const infoBookCustom = document.getElementById('edit_information_book_custom');
        if (infoBookSelect && infoBookSelect.value === 'CUSTOM' && infoBookCustom) {
            formData.set('information_book', infoBookCustom.value);
        }

        const submitBtn = document.getElementById('saveChangesBtn');
        if (!submitBtn) {
            console.error('Save button not found');
            return;
        }

        const originalText = submitBtn.innerHTML;

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

        console.log('Sending update request...');

        fetch('content/allCases/update_case.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log('Update response:', data);
                if (data.success) {
                    showSuccess('Case updated successfully!', 'Success');
                    closeEditModal();

                    // Reload the page to show updated data
                    setTimeout(() => {
                        location.reload();
                    }, 500);
                } else {
                    showError(data.message || 'Failed to update case', 'Update Failed');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('Error updating case. Please try again.', 'Error');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
    }

    // Attach event handlers to edit form (call this after populating the form)
    function attachEditFormHandlers() {
        const editForm = document.getElementById('editCaseForm');
        if (!editForm) {
            console.error('Edit form not found');
            return;
        }

        // Remove existing submit handler first (if any) to prevent duplicates
        // We'll use removeEventListener with a named function
        editForm.removeEventListener('submit', handleEditFormSubmit);

        // Attach new event listener
        editForm.addEventListener('submit', handleEditFormSubmit);
        console.log('✓ Edit form submit handler attached');

        // Attach Information Book change handler
        const infoBookSelect = document.getElementById('edit_information_book');
        const infoBookCustom = document.getElementById('edit_information_book_custom');

        if (infoBookSelect && infoBookCustom) {
            // Remove old listener first
            const oldHandler = infoBookSelect._changeHandler;
            if (oldHandler) {
                infoBookSelect.removeEventListener('change', oldHandler);
            }

            // Create new handler and store reference
            const newHandler = function() {
                if (this.value === 'CUSTOM') {
                    infoBookCustom.style.display = 'block';
                    infoBookCustom.required = true;
                } else {
                    infoBookCustom.style.display = 'none';
                    infoBookCustom.required = false;
                    infoBookCustom.value = '';
                }
            };

            infoBookSelect._changeHandler = newHandler;
            infoBookSelect.addEventListener('change', newHandler);
            console.log('✓ Information Book change handler attached');
        } else {
            console.error('✗ Information Book elements not found for event handler');
        }

        console.log('✓ Edit form handlers attached successfully');
    }

    // Function to update a single case row without reloading the entire page
    function updateCaseRow(caseData) {
        // Find the row by case ID
        const table = document.getElementById('casesTable');
        if (!table) return;

        const rows = table.getElementsByTagName('tr');
        for (let i = 1; i < rows.length; i++) {
            const row = rows[i];
            const actionButtons = row.querySelector('.action-buttons');
            if (!actionButtons) continue;

            const viewButton = actionButtons.querySelector('.btn-view');
            if (viewButton && viewButton.getAttribute('onclick').includes(caseData.id)) {
                // Update row data attributes
                row.dataset.caseNumber = caseData.case_number;
                row.dataset.register = caseData.register_number;
                row.dataset.infoBook = caseData.information_book;
                row.dataset.prevDate = caseData.previous_date || '';
                row.dataset.bReportDate = caseData.date_produce_b_report || '';
                row.dataset.plantDate = caseData.date_produce_plant || '';
                row.dataset.handoverDate = caseData.date_handover_court || '';
                row.dataset.nextDate = caseData.next_date || '';
                row.dataset.attorneyAdvice = caseData.attorney_general_advice || '';
                row.dataset.analystReport = caseData.analyst_report || '';

                // Update visible cell contents
                const cells = row.getElementsByTagName('td');
                if (cells.length >= 12) {
                    cells[0].innerHTML = '<strong>' + escapeHtml(caseData.case_number) + '</strong>';
                    cells[1].textContent = caseData.previous_date ? formatDate(caseData.previous_date) : '-';
                    cells[2].textContent = caseData.information_book;
                    cells[3].textContent = caseData.register_number;
                    cells[4].textContent = caseData.date_produce_b_report ? formatDate(caseData.date_produce_b_report) : '-';
                    cells[5].textContent = caseData.date_produce_plant ? formatDate(caseData.date_produce_plant) : '-';
                    cells[6].innerHTML = '<div class="cell-content">' + escapeHtml(caseData.opens ? caseData.opens.substring(0, 50) : '-') + (caseData.opens && caseData.opens.length > 50 ? '...' : '') + '</div>';
                    cells[7].innerHTML = '<span class="badge-yn ' + (caseData.attorney_general_advice === 'YES' ? 'badge-yes' : 'badge-no') + '">' + (caseData.attorney_general_advice || '-') + '</span>';
                    cells[8].textContent = caseData.date_handover_court ? formatDate(caseData.date_handover_court) : '-';
                    cells[9].innerHTML = '<span class="badge-yn ' + (caseData.analyst_report === 'YES' ? 'badge-yes' : 'badge-no') + '">' + (caseData.analyst_report || '-') + '</span>';
                    cells[10].innerHTML = '<div class="cell-content">' + escapeHtml(caseData.progress ? caseData.progress.substring(0, 50) : '-') + (caseData.progress && caseData.progress.length > 50 ? '...' : '') + '</div>';
                    cells[11].innerHTML = '<div class="cell-content">' + escapeHtml(caseData.results ? caseData.results.substring(0, 50) : '-') + (caseData.results && caseData.results.length > 50 ? '...' : '') + '</div>';

                    // Update suspects count
                    const suspects = JSON.parse(caseData.suspect_data || '[]');
                    cells[12].innerHTML = '<div class="cell-content">' + (suspects.length > 0 ? '<strong>' + suspects.length + '</strong>' : '-') + '</div>';

                    // Update witnesses count
                    const witnesses = JSON.parse(caseData.witness_data || '[]');
                    cells[13].innerHTML = '<div class="cell-content">' + (witnesses.length > 0 ? '<strong>' + witnesses.length + '</strong>' : '-') + '</div>';

                    // Update next date
                    cells[14].textContent = caseData.next_date ? formatDate(caseData.next_date) : '-';
                }
                break;
            }
        }
    }

    // Helper function to format dates
    function formatDate(dateString) {
        if (!dateString) return '-';
        const date = new Date(dateString);
        const day = String(date.getDate()).padStart(2, '0');
        const month = date.toLocaleString('en-GB', {
            month: 'short'
        });
        const year = date.getFullYear();
        return `${day} ${month} ${year}`;
    }

    // Helper function to escape HTML
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Outside click is DISABLED to prevent accidental data loss
    // Modal can only be closed using Save, Cancel, or X close button
</script>