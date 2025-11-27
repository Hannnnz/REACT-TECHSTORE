
    <div id="modal-applicant-reject-confirm" class="modal-overlay hidden">
        <form id="applicant-reject-form" method="post" action="<?=site_url('applicant/reject'); ?>">
        <div class="modal-content">
            <div class="modal-header"><h2>Reject Applicant</h2><button type="button" class="modal-close-btn" onclick="closeModal('modal-applicant-reject-confirm')">&times;</button></div>
            <div class="modal-body"><p style="color:var(--clr-danger)"><strong>Warning:</strong> Are you sure you want to decline access to this user?</p></div>
            <div class="modal-footer">
                <button type="button" class="action-btn modal-cancel-btn" onclick="closeModal('modal-applicant-reject-confirm')">Cancel</button>
                <button class="action-btn delete-btn" onclick="handleFormSubmit('modal-applicant-reject-confirm', 'Applicant deleted.', 'error')">Reject</button>
            </div>
        </div>
        </form>
    </div>