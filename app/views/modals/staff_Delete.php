
    <div id="modal-deactivate-staff-confirm" class="modal-overlay hidden">
        <form id="staff-deactivate-form" method="post" action="<?=site_url('staff/deactivate'); ?>">
        <div class="modal-content">
            <div class="modal-header"><h2>Deactivate Account</h2><button type="button" class="modal-close-btn" onclick="closeModal('modal-deactivate-staff-confirm')">&times;</button></div>
            <div class="modal-body"><p style="color:var(--clr-danger)"><strong>Warning:</strong> Are you sure you want to remove access for this user?</p></div>
            <div class="modal-footer">
                <button type="button" class="action-btn modal-cancel-btn" onclick="closeModal('modal-deactivate-staff-confirm')">Cancel</button>
                <button class="action-btn delete-btn" onclick="handleFormSubmit('modal-deactivate-staff-confirm', 'Account deactivated.', 'error')">Deactivate</button>
            </div>
        </div>
        </form>
    </div>