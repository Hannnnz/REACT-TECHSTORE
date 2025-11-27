<div id="modal-export-confirm" class="modal-overlay hidden">
        <div class="modal-content">
            <div class="modal-header"><h2>Download Data</h2><button class="modal-close-btn" onclick="closeModal('modal-export-confirm')">&times;</button></div>
            <div class="modal-body"><p>Do you want to download the current inventory list as a CSV file in your device?</p></div>
            <div class="modal-footer">
                <button class="action-btn modal-cancel-btn" onclick="closeModal('modal-export-confirm')">Cancel</button>
                <button class="action-btn primary-btn" onclick="handleFormSubmit('modal-export-confirm', 'Download started...')">Export</button>
            </div>
        </div>
    </div>