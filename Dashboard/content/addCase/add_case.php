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
                <input type="text" id="information_book" name="information_book" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="register_number">Register Number <span class="required">*</span></label>
                    <input type="text" id="register_number" name="register_number" required>
                </div>
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
                    <div class="form-row">
                        <div class="form-group">
                            <label>Register Number</label>
                            <input type="text" name="production_registers[]" placeholder="e.g., PR - 275/2022">
                        </div>
                        <div class="form-group">
                            <label>Date</label>
                            <input type="date" name="production_dates[]">
                        </div>
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

    // Attach event listeners
    document.getElementById('addProductionBtn').addEventListener('click', addProduction);
    document.getElementById('addSuspectBtn').addEventListener('click', addSuspect);
    document.getElementById('addWitnessBtn').addEventListener('click', addWitness);
    document.getElementById('resetFormBtn').addEventListener('click', resetForm);

    document.getElementById('addCaseForm').addEventListener('submit', function(e) {
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
                    document.getElementById('addCaseForm').reset();

                    // Clear dynamic lists
                    document.getElementById('production-list').innerHTML = '';
                    document.getElementById('suspects-list').innerHTML = '';
                    document.getElementById('witnesses-list').innerHTML = '';
                    productionCount = 0;
                    suspectCount = 0;
                    witnessCount = 0;

                    // Scroll to top to show message
                    document.querySelector('.add-case-container').scrollTop = 0;

                    // Hide message after 5 seconds
                    setTimeout(() => {
                        messageContainer.innerHTML = '';
                    }, 5000);
                } else {
                    messageContainer.innerHTML = '<div class="message error"><i class="fas fa-exclamation-circle"></i> ' + data.message + '</div>';
                }
            })
            .catch(error => {
                messageContainer.innerHTML = '<div class="message error"><i class="fas fa-exclamation-circle"></i> An error occurred. Please try again.</div>';
                console.error('Error:', error);
            });
    });

    function resetForm() {
        if (confirm('Are you sure you want to reset the form? All entered data will be lost.')) {
            document.getElementById('addCaseForm').reset();
            document.getElementById('message-container').innerHTML = '';
            document.getElementById('production-list').innerHTML = '';
            document.getElementById('suspects-list').innerHTML = '';
            document.getElementById('witnesses-list').innerHTML = '';
            productionCount = 0;
            suspectCount = 0;
            witnessCount = 0;
        }
    }
</script>
</div>