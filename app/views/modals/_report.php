<div id="modal-generate-report" class="modal-overlay hidden">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Generate PDF Report</h2>
            <button class="modal-close-btn" onclick="closeModal('modal-generate-report')">&times;</button>
        </div>

        <div class="modal-body">
            <p>Do you want to generate the PDF report for this month?</p>
        </div>

        <div class="modal-footer">
            <button class="action-btn modal-cancel-btn" onclick="closeModal('modal-generate-report')">Cancel</button>
            <button class="action-btn primary-btn" onclick="generatePDFReport()">Generate</button>
        </div>
    </div>
</div>
