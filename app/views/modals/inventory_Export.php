<div id="modal-export-confirm" class="modal-overlay hidden">
        <div class="modal-content">
            <div class="modal-header"><h2>Export Data</h2><button class="modal-close-btn" onclick="closeModal('modal-export-confirm')">&times;</button></div>
            <div class="modal-body"><p>Do you want to download the current inventory list as a CSV file?</p></div>
            <div class="modal-footer">
                <button class="action-btn modal-cancel-btn" onclick="closeModal('modal-export-confirm')">No</button>
                <button class="action-btn primary-btn" onclick="handleFormSubmit('modal-export-confirm', 'Download started...')">Yes, Export</button>
            </div>
        </div>
    </div>