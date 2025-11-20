<div id="modal-revert-confirm" class="modal-overlay hidden">
        <div class="modal-content">
            <div class="modal-header"><h2>Void Transaction</h2><button class="modal-close-btn" onclick="closeModal('modal-revert-confirm')">&times;</button></div>
            <div class="modal-body"><p>Are you sure you want to <strong>VOID/REFUND</strong> this transaction? Stock will be returned to inventory.</p></div>
            <div class="modal-footer">
                <button class="action-btn modal-cancel-btn" onclick="closeModal('modal-revert-confirm')">Cancel</button>
                <button class="action-btn delete-btn" onclick="handleFormSubmit('modal-revert-confirm', 'Transaction Voided. Stock Returned.', 'info')">Confirm Void</button>
            </div>
        </div>
    </div>