
    <div id="modal-product-delete-confirm" class="modal-overlay hidden">
        <form id="product-delete-form" method="post" action="<?=site_url('product/soft-delete'); ?>">
        <div class="modal-content">
            <div class="modal-header"><h2>Delete Product</h2><button type="button" class="modal-close-btn" onclick="closeModal('modal-product-delete-confirm')">&times;</button></div>
            <div class="modal-body"><p style="color:var(--clr-danger)"><strong>Warning:</strong> Are you sure you want to delete this product?</p></div>
            <div class="modal-footer">
                <button type="button" class="action-btn modal-cancel-btn" onclick="closeModal('modal-product-delete-confirm')">Cancel</button>
                <button class="action-btn delete-btn" onclick="handleFormSubmit('modal-product-delete-confirm', 'Product deleted.', 'error')">Delete</button>
            </div>
        </div>
        </form>
    </div>