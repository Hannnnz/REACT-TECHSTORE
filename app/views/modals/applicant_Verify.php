<div id="modal-verify-applicant" class="modal-overlay hidden">
        <div class="modal-content">
            <div class="modal-header"><h2>Verify Applicant</h2><button class="modal-close-btn" onclick="closeModal('modal-verify-applicant')">&times;</button></div>
            <div class="modal-body"><p>Approve <strong>John Doe</strong> as a new employee? This will move them to the Users list.</p></div>
            <div class="modal-footer">
                <button class="action-btn modal-cancel-btn" onclick="closeModal('modal-verify-applicant')">Cancel</button>
                <button class="action-btn primary-btn" style="background-color: var(--clr-success); border-color: var(--clr-success);" onclick="handleFormSubmit('modal-verify-applicant', 'Applicant Verified and Added to Users.', 'success')">Approve</button>
            </div>
        </div>
    </div>