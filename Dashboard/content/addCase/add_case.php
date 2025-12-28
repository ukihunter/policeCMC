<div class="add-case-container">
    <h2><i class="fas fa-plus-circle"></i> Add New Case</h2>

    <div id="message-container"></div>

    <form id="addCaseForm" class="case-form">
        <div class="form-section">
            <h3>Basic Information</h3>
            <div class="form-row">
                <div class="form-group">
                    <label for="case_number">Case Number <span class="required">*</span></label>
                    <input type="text" id="case_number" name="case_number" required>
                </div>
                <div class="form-group">
                    <label for="previous_date">Previous Date <span class="required">*</span></label>
                    <input type="date" id="previous_date" name="previous_date" required>
                </div>
            </div>

            <div class="form-group">
                <label for="information_book">Information Book <span class="required">*</span></label>
                <select name="information_book" id="information_book" required>
                    <option value="">-- Information Book --</option>

                    <option value="RIB">RIB</option>
                    <option value="GCIB_I">GCIB I</option>
                    <option value="GCIB_II">GCIB II</option>
                    <option value="GCIB_III">GCIB III</option>
                    <option value="MOIB">MOIB</option>
                    <option value="VIB">VIB</option>

                    <option value="EIB">EIB</option>
                    <option value="CPUIB">CPUIB</option>
                    <option value="WCIB">WCIB</option>

                    <option value="PIB">PIB</option>
                    <option value="TIB">TIB</option>
                    <option value="AIB">AIB</option>

                    <option value="CIB_I">CIB I</option>
                    <option value="CIB_II">CIB II</option>
                    <option value="CIB_III">CIB III</option>
                    <option value="119_IB">119 IB</option>

                    <option value="TR">TR</option>
                    <option value="119_TR">119 TR</option>
                    <option value="VPN_TR">VPN TR</option>
                    <option value="118_TR">118 TR</option>
                </select>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="register_type">Register Type <span class="required">*</span></label>
                    <select id="register_type" name="register_type" required>
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
                <div class="form-group">
                    <label for="register_month">Register Month <span class="required">*</span></label>
                    <select id="register_month" name="register_month" required>
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
                <div class="form-group">
                    <label for="register_year">Register Year <span class="required">*</span></label>
                    <div style="position: relative;">
                        <input type="text" id="register_year" name="register_year" required
                            placeholder="YYYY"
                            readonly
                            style="cursor: pointer;"
                            title="Click to select year">
                        <div id="year-picker-dropdown" style="display: none; position: absolute; z-index: 1000; background: white; border: 2px solid #3b82f6; border-radius: 8px; padding: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); max-height: 300px; overflow-y: auto; width: 100%; margin-top: 5px;">
                            <div style="text-align: center; margin-bottom: 10px; font-weight: 600; color: #1e3a8a;">Select Year</div>
                            <div id="year-list" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px;"></div>
                        </div>
                    </div>
                    <small style="color: #6b7280; font-size: 12px; margin-top: 4px; display: block;">Click to select year</small>
                </div>
            </div>
            <div class="form-group">
                <label>Full Register Number</label>
                <input type="text" id="register_number_display" readonly style="background-color: #f3f4f6; cursor: not-allowed;" placeholder="Will be generated from selections above">
                <input type="hidden" id="register_number" name="register_number">
            </div>

        </div>

        <div class="form-section">
            <h3>Production & Reports</h3>
            <div class="form-row">
                <div class="form-group">
                    <label for="date_produce_b_report">Date of Produce B Report</label>
                    <input type="date" id="date_produce_b_report" name="date_produce_b_report">
                </div>
                <div class="form-group">
                    <label for="date_produce_plant">Date of Produce Plant</label>
                    <input type="date" id="date_produce_plant" name="date_produce_plant">
                </div>
            </div>

            <div class="form-group">
                <label for="opens">Opens</label>
                <textarea id="opens" name="opens" rows="10"></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="attorney_general_advice">Attorney General's Advice</label>
                    <select id="attorney_general_advice" name="attorney_general_advice">
                        <option value="">Select...</option>
                        <option value="YES">YES</option>
                        <option value="NO">NO</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Production Register Numbers / Date of Hand Over to Court</label>
                <div id="production-list" class="dynamic-list"></div>
                <button type="button" class="btn-add" id="addProductionBtn">
                    <i class="fas fa-plus"></i> Add Production Register
                </button>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="date_handover_court">Date of Hand Over to Court</label>
                    <input type="date" id="date_handover_court" name="date_handover_court">
                </div>
            </div>

            <h3>Government Analyst's Report</h3>
            <div class="form-row">
                <div class="form-group">
                    <label for="receival_memorandum">Receival Memorandum</label>
                    <select id="receival_memorandum" name="receival_memorandum">
                        <option value="">Select...</option>
                        <option value="YES">YES</option>
                        <option value="NO">NO</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="analyst_report">Analyst's Report</label>
                    <select id="analyst_report" name="analyst_report">
                        <option value="">Select...</option>
                        <option value="YES">YES</option>
                        <option value="NO">NO</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3>Suspect Information</h3>
            <div id="suspects-list" class="dynamic-list"></div>
            <button type="button" class="btn-add" id="addSuspectBtn">
                <i class="fas fa-plus"></i> Add Suspect
            </button>
        </div>

        <div class="form-section">
            <h3>Witness Information</h3>
            <div id="witnesses-list" class="dynamic-list"></div>
            <button type="button" class="btn-add" id="addWitnessBtn">
                <i class="fas fa-plus"></i> Add Witness
            </button>
        </div>

        <div class="form-section">
            <div class="form-section">
                <h3>Case Progress & Results</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="case_status">Case Status <span class="required">*</span></label>
                        <select id="case_status" name="case_status" required>
                            <option value="Ongoing">Ongoing</option>
                            <option value="Pending">Pending</option>
                            <option value="Closed">Closed</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="progress">Progress</label>
                    <textarea id="progress" name="progress" rows="4"></textarea>
                </div>

                <div class="form-group">
                    <label for="results">Results</label>
                    <textarea id="results" name="results" rows="4"></textarea>
                </div>
            </div>

            <div class="form-actions">
                <button type="button" class="btn-secondary" id="resetFormBtn">
                    <i class="fas fa-undo"></i> Reset
                </button>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i> Save Case
                </button>
            </div>
    </form>
</div>

<script>
    // Year picker functionality
    (function() {
        const yearInput = document.getElementById('register_year');
        const yearDropdown = document.getElementById('year-picker-dropdown');
        const yearList = document.getElementById('year-list');
        const currentYear = new Date().getFullYear();

        // Generate years from 2000 to 10000
        function populateYears() {
            yearList.innerHTML = '';
            for (let year = 10000; year >= 2000; year--) {
                const yearBtn = document.createElement('button');
                yearBtn.type = 'button';
                yearBtn.textContent = year;
                yearBtn.style.cssText = 'padding: 8px; border: 1px solid #e5e7eb; border-radius: 6px; background: white; cursor: pointer; transition: all 0.2s; font-size: 14px;';

                yearBtn.addEventListener('mouseenter', function() {
                    this.style.background = '#3b82f6';
                    this.style.color = 'white';
                    this.style.borderColor = '#3b82f6';
                });

                yearBtn.addEventListener('mouseleave', function() {
                    this.style.background = 'white';
                    this.style.color = 'black';
                    this.style.borderColor = '#e5e7eb';
                });

                yearBtn.addEventListener('click', function() {
                    yearInput.value = year;
                    yearDropdown.style.display = 'none';
                    updateRegisterNumber();
                });

                yearList.appendChild(yearBtn);
            }
        }

        // Toggle dropdown
        yearInput.addEventListener('click', function(e) {
            e.stopPropagation();
            if (yearDropdown.style.display === 'none') {
                populateYears();
                yearDropdown.style.display = 'block';
                // Scroll to current year
                const currentYearBtn = Array.from(yearList.children).find(btn => btn.textContent == currentYear);
                if (currentYearBtn) {
                    currentYearBtn.scrollIntoView({
                        block: 'center'
                    });
                }
            } else {
                yearDropdown.style.display = 'none';
            }
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!yearInput.contains(e.target) && !yearDropdown.contains(e.target)) {
                yearDropdown.style.display = 'none';
            }
        });
    })();

    // Update register number display
    function updateRegisterNumber() {
        const type = document.getElementById('register_type').value;
        const month = document.getElementById('register_month').value;
        const year = document.getElementById('register_year').value;
        const display = document.getElementById('register_number_display');
        const hidden = document.getElementById('register_number');

        if (type && month && year && year.length === 4) {
            const fullNumber = `${type} ${month}/${year}`;
            display.value = fullNumber;
            hidden.value = fullNumber;
        } else {
            display.value = '';
            hidden.value = '';
        }
    }

    document.getElementById('register_type').addEventListener('change', updateRegisterNumber);
    document.getElementById('register_month').addEventListener('change', updateRegisterNumber);
    document.getElementById('register_year').addEventListener('input', updateRegisterNumber);

    // Production Register Management
    let productionCount = 0;

    function addProduction() {
        const container = document.getElementById('production-list');

        // Collapse all existing items
        container.querySelectorAll('.dynamic-item').forEach(item => {
            if (!item.classList.contains('collapsed')) {
                const header = item.querySelector('.dynamic-item-header');
                toggleCollapse(header);
            }
        });

        const item = document.createElement('div');
        item.className = 'dynamic-item';
        item.innerHTML = `
                <div class="dynamic-item-header">
                    <div>
                        <span>Production Register #${productionCount + 1}</span>
                        <i class="fas fa-chevron-down collapse-icon"></i>
                    </div>
                    <button type="button" class="btn-remove" data-remove="true">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="dynamic-item-content">
                    <div class="form-group">
                        <label>Register Number</label>
                        <input type="text" name="production_registers[]" placeholder="e.g., PR - 275/2022">
                        <small style="color: #666; font-size: 12px; margin-top: 4px;">Each register on a separate entry</small>
                    </div>
                </div>
            `;
        container.appendChild(item);
        productionCount++;
    }

    // Suspect Management
    let suspectCount = 0;

    function addSuspect() {
        const container = document.getElementById('suspects-list');

        // Collapse all existing items
        container.querySelectorAll('.dynamic-item').forEach(item => {
            if (!item.classList.contains('collapsed')) {
                const header = item.querySelector('.dynamic-item-header');
                toggleCollapse(header);
            }
        });

        const item = document.createElement('div');
        item.className = 'dynamic-item';
        item.innerHTML = `
                <div class="dynamic-item-header">
                    <div>
                        <span>Suspect #${suspectCount + 1}</span>
                        <i class="fas fa-chevron-down collapse-icon"></i>
                    </div>
                    <button type="button" class="btn-remove" data-remove="true">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="dynamic-item-content">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="suspect_names[]" placeholder="Full Name">
                        </div>
                        <div class="form-group">
                            <label>NIC Number</label>
                            <input type="text" name="suspect_ic_numbers[]" placeholder="NIC Number">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <textarea name="suspect_addresses[]" rows="2" placeholder="Full Address"></textarea>
                    </div>
                </div>
            `;
        container.appendChild(item);
        suspectCount++;
    }

    // Witness Management
    let witnessCount = 0;

    function addWitness() {
        const container = document.getElementById('witnesses-list');

        // Collapse all existing items
        container.querySelectorAll('.dynamic-item').forEach(item => {
            if (!item.classList.contains('collapsed')) {
                const header = item.querySelector('.dynamic-item-header');
                toggleCollapse(header);
            }
        });

        const item = document.createElement('div');
        item.className = 'dynamic-item';
        item.innerHTML = `
                <div class="dynamic-item-header">
                    <div>
                        <span>Witness #${witnessCount + 1}</span>
                        <i class="fas fa-chevron-down collapse-icon"></i>
                    </div>
                    <button type="button" class="btn-remove" data-remove="true">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="dynamic-item-content">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="witness_names[]" placeholder="Full Name">
                        </div>
                        <div class="form-group">
                            <label>NIC Number</label>
                            <input type="text" name="witness_ic_numbers[]" placeholder="NIC Number">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <textarea name="witness_addresses[]" rows="2" placeholder="Full Address"></textarea>
                    </div>
                </div>
            `;
        container.appendChild(item);
        witnessCount++;
    }

    // Toggle collapse function
    function toggleCollapse(header) {
        const item = header.parentElement;
        const content = item.querySelector('.dynamic-item-content');
        const icon = header.querySelector('.collapse-icon');

        item.classList.toggle('collapsed');

        if (item.classList.contains('collapsed')) {
            content.style.display = 'none';
            icon.classList.remove('fa-chevron-down');
            icon.classList.add('fa-chevron-right');
        } else {
            content.style.display = 'block';
            icon.classList.remove('fa-chevron-right');
            icon.classList.add('fa-chevron-down');
        }
    }

    // Event delegation for dynamic items
    document.addEventListener('click', function(e) {
        // Handle remove buttons
        if (e.target.closest('[data-remove="true"]')) {
            e.stopPropagation();
            e.target.closest('.dynamic-item').remove();
            return;
        }

        // Handle collapse/expand
        const header = e.target.closest('.dynamic-item-header');
        if (header && !e.target.closest('.btn-remove')) {
            toggleCollapse(header);
        }
    });

    // Wait for DOM to be fully loaded before attaching event listeners
    function initializeForm() {
        const addProductionBtn = document.getElementById('addProductionBtn');
        const addSuspectBtn = document.getElementById('addSuspectBtn');
        const addWitnessBtn = document.getElementById('addWitnessBtn');
        const resetFormBtn = document.getElementById('resetFormBtn');
        const addCaseForm = document.getElementById('addCaseForm');

        if (addProductionBtn) {
            // Remove any existing listeners by cloning and replacing
            const newProductionBtn = addProductionBtn.cloneNode(true);
            addProductionBtn.parentNode.replaceChild(newProductionBtn, addProductionBtn);
            newProductionBtn.addEventListener('click', addProduction);
        }
        if (addSuspectBtn) {
            const newSuspectBtn = addSuspectBtn.cloneNode(true);
            addSuspectBtn.parentNode.replaceChild(newSuspectBtn, addSuspectBtn);
            newSuspectBtn.addEventListener('click', addSuspect);
        }
        if (addWitnessBtn) {
            const newWitnessBtn = addWitnessBtn.cloneNode(true);
            addWitnessBtn.parentNode.replaceChild(newWitnessBtn, addWitnessBtn);
            newWitnessBtn.addEventListener('click', addWitness);
        }
        if (resetFormBtn) {
            const newResetBtn = resetFormBtn.cloneNode(true);
            resetFormBtn.parentNode.replaceChild(newResetBtn, resetFormBtn);
            newResetBtn.addEventListener('click', resetForm);
        }
        if (addCaseForm) {
            addCaseForm.removeEventListener('submit', handleFormSubmit);
            addCaseForm.addEventListener('submit', handleFormSubmit);
        }
    }

    // Initialize immediately since content is loaded dynamically
    initializeForm();

    function handleFormSubmit(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const messageContainer = document.getElementById('message-container');

        // Show loading state
        messageContainer.innerHTML = '<div class="message info"><i class="fas fa-spinner fa-spin"></i> Saving case...</div>';

        fetch('content/addCase/save_case.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    messageContainer.innerHTML = '<div class="message success"><i class="fas fa-check-circle"></i> ' + data.message + '</div>';
                    showSuccess(data.message, 'Case Added Successfully');
                    document.getElementById('addCaseForm').reset();

                    // Clear dynamic lists
                    document.getElementById('production-list').innerHTML = '';
                    document.getElementById('suspects-list').innerHTML = '';
                    document.getElementById('witnesses-list').innerHTML = '';
                    productionCount = 0;
                    suspectCount = 0;
                    witnessCount = 0;

                    // Clear register number display
                    document.getElementById('register_number_display').value = '';
                    document.getElementById('register_number').value = '';

                    // Scroll to top to show message
                    document.querySelector('.add-case-container').scrollTop = 0;

                    // Hide message after 5 seconds
                    setTimeout(() => {
                        messageContainer.innerHTML = '';
                    }, 5000);

                    // Reload dashboard stats if on dashboard
                    if (typeof loadDashboardStats === 'function') {
                        loadDashboardStats();
                    }
                } else {
                    messageContainer.innerHTML = '<div class="message error"><i class="fas fa-exclamation-circle"></i> ' + data.message + '</div>';
                    showError(data.message, 'Failed to Add Case');
                }
            })
            .catch(error => {
                messageContainer.innerHTML = '<div class="message error"><i class="fas fa-exclamation-circle"></i> An error occurred. Please try again.</div>';
                showError('An error occurred while saving the case. Please try again.', 'Error');
                console.error('Error:', error);
            });
    }

    function resetForm() {
        if (confirm('Are you sure you want to reset the form? All entered data will be lost.')) {
            document.getElementById('addCaseForm').reset();
            document.getElementById('message-container').innerHTML = '';
            document.getElementById('production-list').innerHTML = '';
            document.getElementById('suspects-list').innerHTML = '';
            document.getElementById('witnesses-list').innerHTML = '';
            document.getElementById('register_number_display').value = '';
            document.getElementById('register_number').value = '';
            productionCount = 0;
            suspectCount = 0;
            witnessCount = 0;
        }
    }
</script>
</div>