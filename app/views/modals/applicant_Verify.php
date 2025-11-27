<div id="modal-verify-applicant" class="modal-overlay hidden">
    <form id="applicant-verify-form" method="post" action="<?=site_url('applicant/verify'); ?>">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Verify Applicant</h2>
                <button type="button" class="modal-close-btn" onclick="closeModal('modal-verify-applicant')">&times;</button>
            </div>
            <div class="modal-body">
                <p id="verify-message">Approve <strong>John Doe</strong> as a new employee? This will move them to the Users list.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="action-btn modal-cancel-btn" onclick="closeModal('modal-verify-applicant')">Cancel</button>
                <button type="submit" class="action-btn primary-btn" style="background-color: var(--clr-success); border-color: var(--clr-success);">
                    Approve
                </button>
            </div>
        </div>
    </form>
</div>
