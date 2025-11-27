<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

require ROOT_DIR . 'vendor/autoload.php';
use Dompdf\Dompdf;

class _TransactionController extends Controller {
    public function __construct()
    {
        parent::__construct();
        $this->call->database();
        $this->call->model('StaffModel');

        // Auth check
        if(segment(2) != 'logout') {
            $id = $this->lauth->get_user_id();
            if(!logged_in()){
                redirect('auth/login');
            } else if($this->lauth->is_user_verified($id) == 0){
                if($this->lauth->set_logged_out()) redirect('auth/login');
            } else if(logged_in() && $this->lauth->get_role($id) == "user") {
                redirect('pos');
            }
        }
    }

    public function report()
{
    // --- Sales total ---
    $row = $this->db->table('transactions')
                    ->select_sum('total','total')
                    ->get(); // returns array
    $data['sales'] = (float)($row['total'] ?? 0);

    // --- Products sold ---
    $row = $this->db->table('products')
                    ->select_sum('sold','sold')
                    ->where_null('deleted_at')
                    ->get(); // returns array
    $data['sold'] = (float)($row['sold'] ?? 0);

    // --- Total transactions ---
    $res = $this->db->raw('SELECT COUNT(id) AS total FROM transactions WHERE deleted_at IS NULL');
    $row = $res->fetch(PDO::FETCH_ASSOC);
    $data['transacts'] = (int)($row['total'] ?? 0);

    // --- Top cashier ---
    $res = $this->db->raw("
        SELECT cashier, COUNT(*) AS total_transactions, SUM(total) AS total_sales
        FROM transactions
        WHERE MONTH(date) = MONTH(CURRENT_DATE())
          AND YEAR(date) = YEAR(CURRENT_DATE())
          AND deleted_at IS NULL
        GROUP BY cashier
        ORDER BY total_sales DESC
        LIMIT 1
    ");
    $row = $res->fetch(PDO::FETCH_ASSOC);
    $data['top_cashier'] = [
        'cashier' => $row['cashier'] ?? 'N/A',
        'total_transactions' => (int)($row['total_transactions'] ?? 0),
        'total_sales' => (float)($row['total_sales'] ?? 0)
    ];

    // --- Top 5 products ---
    $res = $this->db->raw("
        SELECT name, sold AS units_sold, (sold*price) AS revenue
        FROM products
        WHERE deleted_at IS NULL
        ORDER BY sold DESC
        LIMIT 5
    ");
    $data['top_products'] = $res->fetchAll(PDO::FETCH_ASSOC);

    // --- All transactions for the month (unsorted by cashier) ---
    $data['transactions'] = $this->db->table('transactions')
                                 ->where_null('deleted_at')
                                 ->order_by('date','DESC')
                                 ->get_all(); // returns array

    // --- Generate PDF ---
    $dompdf = new Dompdf();
    ob_start();
    include APP_DIR.'views/report.php';
    $html = ob_get_clean();

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4','portrait');
    $dompdf->render();
    $dompdf->stream("TechStore_Report_".date('F_Y').".pdf", ["Attachment" => true]);
}

}
