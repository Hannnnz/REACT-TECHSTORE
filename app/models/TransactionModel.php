<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * Model: TransactionModel
 * 
 * Automatically generated via CLI.
 */
class TransactionModel extends Model {
    protected $table = 'transactions';
    protected $primary_key = 'id';
    protected $fillable = ['total', 'cashier'];
    protected $has_soft_delete = true;
    protected $soft_delete_column = 'deleted_at';

    public function __construct()
    {
        parent::__construct();
    }

    public function transactions($q, $records_per_page = null, $page = null) {
        if (is_null($page)) {
            return $this->db->table('transactions')->get_all();
        } else {
            $query = $this->db->table('transactions');
                
            // Build LIKE conditions

	    $query->like('cashier', '%'.$q.'%')
	    ->where_null('deleted_at')
        ->order_by('date', 'DESC');


            // Clone before pagination
            $countQuery = clone $query;

            $data['total_rows'] = $countQuery->select_count('*', 'count')
                                            ->get()['count'];

            $data['records'] = $query->pagination($records_per_page, $page)
                                    ->get_all();

            return $data;
        }
    }

    public function page_trash($q, $records_per_page = null, $page = null) {
        if (is_null($page)) {
            return $this->db->table('genshin')->get_all();
        } else {
            $query = $this->db->table('genshin');
                
            // Build LIKE conditions

	    $query->grouped(function($x) use ($q) {
		    $x->like('id', '%'.$q.'%')
                ->or_like('name', '%'.$q.'%')
                ->or_like('class', '%'.$q.'%');
	    })
	    ->where_not_null('deleted_at')
        ->order_by('deleted_at', 'DESC');


            // Clone before pagination
            $countQuery = clone $query;

            $data['total_rows'] = $countQuery->select_count('*', 'count')
                                            ->get()['count'];

            $data['records'] = $query->pagination($records_per_page, $page)
                                    ->get_all();

            return $data;
        }
    }
}