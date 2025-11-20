<div id="modal-record-stock" class="modal-overlay hidden">
        <div class="modal-content">
            <div class="modal-header"><h2>Record Stock Entry</h2><button class="modal-close-btn" onclick="closeModal('modal-record-stock')">&times;</button></div>
            <div class="modal-body">
                <form id="form-record-stock" method="post" action="<?=site_url('update-stock'); ?>" autocomplete="off">
                    <div class="form-group"><label for="product_id">Product</label><div class="input-group"><i class="fas fa-box"></i><input type="text" id="product_id" name="product_id" placeholder="Scan unique product code" required></div></div>
                    <div class="form-group"><label for="stock">Quantity Added</label><div class="input-group"><input type="number" id="stock" name="stock" placeholder="1" step="1" min="1" value="1" required></div></div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="action-btn modal-cancel-btn" onclick="closeModal('modal-record-stock')">Cancel</button>
                <button class="action-btn primary-btn" onclick="handleFormSubmit('modal-record-stock', 'Stock updated successfully!')">Add Stock</button>
            </div>
        </div>
    </div>  