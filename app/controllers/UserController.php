<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * Controller: UserController
 * 
 * Automatically generated via CLI.
 */
class UserController extends Controller {
    public function __construct()
    {
        parent::__construct();
        $this->call->database();
        $this->call->model('ProductModel');
        $this->call->model('TransactionModel');
        if(segment(2) != 'logout') {
            $id = $this->lauth->get_user_id();
            if(!logged_in()){
                redirect('auth/login');
            }
            else if($this->lauth->is_user_verified($id) == 0){
                if($this->lauth->set_logged_out()) {
                    redirect('auth/login');
                }
            }
            else if(logged_in() && $this->lauth->get_role($id) == "admin") {
                redirect('');
            }
        }
    }

    public function index(){
        $data['products'] = $this->ProductModel->stock();
        $this->call->view('home_user', $data);
    }

    public function transaction()
{
    if ($this->io->method() == 'post') {

        // Get POST data
        $total = $this->io->post('total');
        $cashier = $this->io->post('cashier');
        $time = $this->io->post('transaction_time');
        $itemsJson = $this->io->post('items'); // cart items
        $base64Image = $this->io->post('receipt_image'); // receipt screenshot (base64)

        // --- SAVE RECEIPT IMAGE ---
        $filename = null;

        if (!empty($base64Image)) {
            // remove base64 prefix
            $cleaned = preg_replace('#^data:image/\w+;base64,#i', '', $base64Image);

            // decode image
            $imageData = base64_decode($cleaned);

            // ensure upload directory exists
            $uploadDir = ROOT_DIR.PUBLIC_DIR.'/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // generate file name
            $filename = 'receipt_' . time() . '.png';
            $filepath = $uploadDir . $filename;

            // save file
            file_put_contents($filepath, $imageData);
        }

        // --- INSERT TRANSACTION ---
        $data = [
            'total'   => $total,
            'cashier' => $cashier,
            'date'    => $time,
            'receipt'     => $filename  // saved screenshot file
        ];

        $this->TransactionModel->insert($data);

        // --- UPDATE PRODUCT STOCKS ---
        $items = json_decode($itemsJson, true);

        foreach ($items as $item) {

            $id = $item['product_id'];
            $qty = $item['qty'];

            $product = $this->ProductModel->find($id);

            $newStock = $product['stock'] - $qty;
            $newSold = $product['sold'] + $qty;

            $updateProduct = [
                'stock' => $newStock,
                'sold'  => $newSold
            ];

            $this->ProductModel->update($id, $updateProduct);
        }

        // return to POS
        redirect('pos');
    }
}
}