<div id="modal-print-receipt" class="modal-overlay hidden">
        <div class="modal-content">
            <div class="modal-header"><h2>Transaction Details</h2><button class="modal-close-btn" onclick="closeModal('modal-print-receipt')">&times;</button></div>
            <div class="modal-body">
                <p><strong>TRX-00101</strong></p>
                <hr style="margin:10px 0; border-top:1px dashed var(--clr-border)">
                <div style="display:flex; justify-content:space-between"><span>Item 1</span><span>₱100.00</span></div>
                <div style="display:flex; justify-content:space-between"><span>Item 2</span><span>₱200.00</span></div>
                <hr style="margin:10px 0; border-top:1px dashed var(--clr-border)">
                <div style="display:flex; justify-content:space-between; font-weight:bold"><span>Total</span><span>₱300.00</span></div>
            </div>
            <div class="modal-footer">
                <button class="action-btn modal-cancel-btn" onclick="closeModal('modal-print-receipt')">Close</button>
                <button class="action-btn primary-btn" onclick="handleFormSubmit('modal-print-receipt', 'Printing Receipt...')"><i class="fas fa-print"></i> Print</button>
            </div>
        </div>
    </div>