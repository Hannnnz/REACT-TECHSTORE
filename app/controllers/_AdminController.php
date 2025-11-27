<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * Controller: _AdminController
 * 
 * Automatically generated via CLI.
 */
class _AdminController extends Controller {
    public function __construct()
    {
        parent::__construct();
        $this->call->database();
        $this->call->model('ProductModel');
        $this->call->model('StaffModel');
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
            else if(logged_in() && $this->lauth->get_role($id) == "user") {
                redirect('pos');
            }
        }
    }

    public function index(){
        $page = 1;
        if(isset($_GET['page']) && ! empty($_GET['page'])) {
            $page = $this->io->get('page');
        }

        $q = '';
        if(isset($_GET['q']) && ! empty($_GET['q'])) {
            $q = trim($this->io->get('q'));
        }

        $records_per_page = 5;

        $all = $this->ProductModel->products($q, $records_per_page, $page);
        $data['all'] = $all['records'];
        $total_rows = $all['total_rows'];
        $this->pagination->set_options([
            'first_link'     => 'First',
            'last_link'      => 'Last',
            'next_link'      => '→',
            'prev_link'      => '←',
            'page_delimiter' => '&page='
        ]);
        $this->pagination->set_theme('tailwind'); // or 'tailwind', or 'custom'
        $this->pagination->initialize($total_rows, $records_per_page, $page,'?q='.$q);
        $data['page_products'] = $this->pagination->paginate();

        $all = $this->ProductModel->inventory($q, $records_per_page, $page);
        $data['inventory'] = $all['records'];
        $total_rows = $all['total_rows'];
        $this->pagination->set_options([
            'first_link'     => 'First',
            'last_link'      => 'Last',
            'next_link'      => '→',
            'prev_link'      => '←',
            'page_delimiter' => '&page='
        ]);
        $this->pagination->set_theme('tailwind'); // or 'tailwind', or 'custom'
        $this->pagination->initialize($total_rows, $records_per_page, $page,'?q='.$q);
        $data['page_inventory'] = $this->pagination->paginate();

        $all = $this->StaffModel->users($q, $records_per_page, $page);
        $data['users'] = $all['records'];
        $total_rows = $all['total_rows'];
        $this->pagination->set_options([
            'first_link'     => 'First',
            'last_link'      => 'Last',
            'next_link'      => '→',
            'prev_link'      => '←',
            'page_delimiter' => '&page='
        ]);
        $this->pagination->set_theme('tailwind'); // or 'tailwind', or 'custom'
        $this->pagination->initialize($total_rows, $records_per_page, $page,'?q='.$q);
        $data['page_users'] = $this->pagination->paginate();

        $all = $this->TransactionModel->transactions($q, $records_per_page, $page);
        $data['transactions'] = $all['records'];
        $total_rows = $all['total_rows'];
        $this->pagination->set_options([
            'first_link'     => 'First',
            'last_link'      => 'Last',
            'next_link'      => '→',
            'prev_link'      => '←',
            'page_delimiter' => '&page='
        ]);
        $this->pagination->set_theme('tailwind'); // or 'tailwind', or 'custom'
        $this->pagination->initialize($total_rows, $records_per_page, $page,'?q='.$q);
        $data['page_transactions'] = $this->pagination->paginate();

        $all = $this->StaffModel->applicants($q, $records_per_page, $page);
        $data['applicants'] = $all['records'];
        $total_rows = $all['total_rows'];
        $this->pagination->set_options([
            'first_link'     => 'First',
            'last_link'      => 'Last',
            'next_link'      => '→',
            'prev_link'      => '←',
            'page_delimiter' => '&page='
        ]);
        $this->pagination->set_theme('tailwind'); // or 'tailwind', or 'custom'
        $this->pagination->initialize($total_rows, $records_per_page, $page,'?q='.$q);
        $data['page_applicants'] = $this->pagination->paginate();

        $data['sales'] = $this->db->table('transactions')->select_sum('total', 'total')->get();
        $data['sold'] = $this->db->table('products')->select_sum('sold', 'sold')->where_null('deleted_at')->get();
        $res = $this->db->raw('SELECT COUNT(stock) AS total FROM products WHERE stock < 5 AND deleted_at IS NULL');
        $data['low_stock'] = $res->fetch();
        $res = $this->db->raw('SELECT COUNT(id) AS total FROM transactions WHERE deleted_at IS NULL');
        $data['transacts'] = $res->fetch();

        $sql = "
            SELECT cashier,
                COUNT(*) AS total_transactions,
                SUM(total) AS total_sales
            FROM transactions
            WHERE MONTH(date) = MONTH(CURRENT_DATE())
            AND YEAR(date) = YEAR(CURRENT_DATE())
            AND deleted_at IS NULL
            GROUP BY cashier
            ORDER BY total_sales DESC
            LIMIT 1
        ";

        $res = $this->db->raw($sql);
        $data['top_cashier'] = $res->fetch();

        $sql = "
            SELECT 
                name,
                sold AS units_sold,
                (sold * price) AS revenue
            FROM products
            WHERE deleted_at IS NULL
            ORDER BY sold DESC
            LIMIT 5
        ";

        $res = $this->db->raw($sql);
        $data['top_products'] = $res->fetchAll();

        $sql = "
            SELECT cashier, SUM(total) AS total_sales
            FROM transactions
            WHERE deleted_at IS NULL
            GROUP BY cashier
            ORDER BY total_sales DESC
        ";
        $res = $this->db->raw($sql);
        $data['cashier_sales'] = $res->fetchAll(PDO::FETCH_ASSOC);

        $this->call->view('home', $data);
    }

    public function settings(){
        if($this->io->method() == 'post'){
            $email = $this->io->post('email');
            $password = $this->io->post('current-password');
            $pass = $this->io->post('new-password');
            $pass2 = $this->io->post('confirm-password');

            $this->lauth->reset_admin($password, $email, $pass, $pass2);
            redirect('');
        }
    }
}