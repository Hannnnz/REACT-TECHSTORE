<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * Controller: _StaffController
 * 
 * Automatically generated via CLI.
 */
class _StaffController extends Controller {
    public function __construct()
    {
        parent::__construct();
        $this->call->database();
        $this->call->model('StaffModel');
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

    public function create()
    {
        if($this->io->method() == 'post'){
            $name = $this->io->post('name');
            $class = $this->io->post('class');

            $this->call->library('upload', $_FILES["fileToUpload"]);
		    $this->upload
			->max_size(5)
			//->min_size(1)
			->set_dir('uploads')
			->allowed_extensions(array('png', 'jpg', 'gif'))
			->allowed_mimes(array('image/png', 'image/jpeg', 'image/gif'))
			->is_image()
			->encrypt_name();

            if($this->upload->do_upload()){
                $data = [
                'name' => $name,
                'class' => $class,
                'pic' => $this->upload->get_filename()
            ];
                $this->ProductModel->insert($data);
                redirect();
            }
        }
        else{
            $this->call->view('create');
        }
    }

    public function update()
    {
        if($this->io->method() == 'post'){
            $id = $this->io->post('product_id');
            $stock = $this->io->post('stock');

                $data = [
                    'id' => $id,
                    'stock' => $stock
                ];

                $this->ProductModel->update($data);
                redirect();
        }
    }

    function delete($id){
        if($this->lauth->get_role(get_user_id()) == "admin") {
            $this->ProductModel->delete($id);
            redirect('trash');
        }
    }

    function soft_delete($id){
        if($this->lauth->get_role(get_user_id()) == "admin") {
            $this->StaffModel->soft_delete($id);
            redirect();
        }
    }

    function trash(){
        $page = 1;
        if(isset($_GET['page']) && ! empty($_GET['page'])) {
            $page = $this->io->get('page');
        }

        $q = '';
        if(isset($_GET['q']) && ! empty($_GET['q'])) {
            $q = trim($this->io->get('q'));
        }

        $records_per_page = 5;

        $all = $this->ProductModel->page_trash($q, $records_per_page, $page);
        $data['all'] = $all['records'];
        $total_rows = $all['total_rows'];
        $this->pagination->set_options([
            'first_link'     => 'First',
            'last_link'      => 'Last',
            'next_link'      => '→',
            'prev_link'      => '←',
            'page_delimiter' => '&page='
        ]);
        $this->pagination->set_theme('bootstrap'); // or 'tailwind', or 'custom'
        $this->pagination->initialize($total_rows, $records_per_page, $page,'trash/?q='.$q);
        $data['page'] = $this->pagination->paginate();
        $this->call->view('trash', $data);
    }

    function restore($id){
        if($this->lauth->get_role(get_user_id()) == "admin") {
            $this->ProductModel->restore($id);
            redirect('trash');
        }
    }

    public function product()
    {
        if($this->io->method() == 'post'){
            $id = $this->io->post('product_id');
            $name = $this->io->post('product_name');
            $category = $this->io->post('category');
            $price = $this->io->post('unit_price');

            
                $data = [
                'id' => $id,
                'name' => $name,
                'category' => $category,
                'price' => $price
                ];

                $this->ProductModel->insert($data);

                /*if ($this->ProductModel->insert($data)) {
                    $this->session->set_flashdata('message', 'Product inserted successfully!');
                } else {
                    $this->session->set_flashdata('error', 'Something went wrong.');
                }*/

                redirect();
        }
    }

    function user_deactivate($id){
        if($this->lauth->get_role(get_user_id()) == "admin") {
            if($this->io->method() == 'post'){
                    $char = $this->StaffModel->find($id);
                    $data = [
                    'role' => "declined"
                    ];
                    $template = file_get_contents(ROOT_DIR.PUBLIC_DIR.'/templates/account_deactivated.html');
                    $this->StaffModel->update($id, $data);
                    SendMail("TechStore", get_email(get_user_id()), "Account Termination", $template, $char['email']);
                    $this->session->set_flashdata('alert', 'info');
                    $this->session->set_flashdata('message', 'Account deactivated! Email sent.');
                    redirect();
            }
        }
    }
}