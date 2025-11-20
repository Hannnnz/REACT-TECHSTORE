<div id="modal-import-csv" class="modal-overlay hidden">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Import Inventory CSV</h2>
                <button class="modal-close-btn" onclick="closeModal('modal-import-csv')">&times;</button>
            </div>
            <div class="modal-body">
                <div id="drop-zone" style="padding: 40px; border: 2px dashed var(--clr-border); text-align:center; color:var(--clr-text-secondary); cursor: pointer; transition: all 0.2s ease;">
                    <i class="fas fa-file-csv" style="font-size: 3rem; margin-bottom:10px"></i>
                    <p style="font-family: var(--font-body);">Drag & Drop CSV file here or Click to Upload</p>
                    
                    <p id="file-name-display" style="margin-top:10px; color:var(--clr-primary); font-weight:600; font-size:0.9rem;"></p>
                    
                    <input type="file" id="csv-file-input" accept=".csv" hidden>
                </div>
            </div>
            <div class="modal-footer">
                <button class="action-btn modal-cancel-btn" onclick="closeModal('modal-import-csv')">Cancel</button>
                <button class="action-btn primary-btn" id="btn-upload-csv">Upload</button>
            </div>
        </div>
    </div>