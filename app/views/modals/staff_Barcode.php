<div id="modal-user-barcode" class="modal-overlay hidden">
        <div class="modal-content">
            <div class="modal-header"><h2>User Login Card</h2><button class="modal-close-btn" onclick="closeModal('modal-user-barcode')">&times;</button></div>
            <div class="modal-body" style="text-align:center">
                <p>Employee: <strong>cindy</strong></p>
                <div style="margin: 20px 0; background:#fff; padding:10px; display:inline-block; border:1px solid #ccc;">
                    <i class="fas fa-barcode" style="font-size:4rem; color:#000"></i>
                </div>
                <p>ID: MCC2023-00695</p>
            </div>
            <div class="modal-footer">
                <button class="action-btn modal-cancel-btn" onclick="closeModal('modal-user-barcode')">Close</button>
                <button class="action-btn primary-btn" onclick="handleFormSubmit('modal-user-barcode', 'Printing ID Card...')"><i class="fas fa-print"></i> Print</button>
            </div>
        </div>
    </div>