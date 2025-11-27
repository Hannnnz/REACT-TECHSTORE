<div id="modal-print-receipt" class="modal-overlay hidden">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Transaction Details</h2>
            <button class="modal-close-btn" onclick="closeModal('modal-print-receipt')">&times;</button>
        </div>
        <div class="modal-body">
            <img id="receipt-img" src="" alt="Receipt Image" height="800" width="1600">
        </div>
        <div class="modal-footer">
            <button class="action-btn modal-cancel-btn" onclick="closeModal('modal-print-receipt')">Close</button>
            <button class="action-btn primary-btn" onclick="downloadReceiptAsPDF(this)">
                <i class="fas fa-print"></i> Print
            </button>
        </div>
    </div>
</div>
