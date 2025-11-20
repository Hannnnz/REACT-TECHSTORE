<div id="modal-delete-confirm" class="modal-overlay hidden">
        <div class="modal-content">
            <div class="modal-header"><h2>Confirm Deletion</h2><button class="modal-close-btn" onclick="closeModal('modal-delete-confirm')">&times;</button></div>
            <div class="modal-body"><p style="color:var(--clr-danger)"><strong>Warning:</strong> This action is permanent and cannot be undone. Are you sure?</p></div>
            <div class="modal-footer">
                <button class="action-btn modal-cancel-btn" onclick="closeModal('modal-delete-confirm')">Cancel</button>
                <button class="action-btn delete-btn" onclick="handleFormSubmit('modal-delete-confirm', 'Record deleted permanently.', 'error')">Delete</button>
            </div>
        </div>
    </div>