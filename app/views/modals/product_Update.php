<div id="modal-edit-product" class="modal-overlay hidden">
        <div class="modal-content">
            <div class="modal-header"><h2>Edit Product</h2><button class="modal-close-btn" onclick="closeModal('modal-edit-product')">&times;</button></div>
            <div class="modal-body">
                <p>Update product details.</p>
                <div class="form-group">
                    <label>Product Name</label>
                    <div class="input-group"><i class="fas fa-tag"></i><input type="text" value="Smartwatch X30"></div>
                </div>
                <div class="form-group">
                     <label>Price</label>
                     <div class="input-group"><i class="fas fa-peso-sign"></i><input type="number" value="8999.00"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="action-btn modal-cancel-btn" onclick="closeModal('modal-edit-product')">Cancel</button>
                <button class="action-btn primary-btn" onclick="handleFormSubmit('modal-edit-product', 'Product updated!')">Update</button>
            </div>
        </div>
    </div>