<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * Model: StaffModel
 * 
 * Automatically generated via CLI.
 */
class StaffModel extends Model {
    protected $table = 'users';
    protected $primary_key = 'id';
    protected $fillable = ['username', 'email', 'updated_at'];
    protected $has_soft_delete = true;
    protected $soft_delete_column = 'deleted_at';

    public function __construct()
    {
        parent::__construct();
    }

    public function users($q, $records_per_page = null, $page = null) {
        if (is_null($page)) {
            return $this->db->table('users')->get_all();
        } else {
            $query = $this->db->table('users');
                
            // Build LIKE conditions

	    $query->grouped(function($x) use ($q) {
		    $x->like('username', '%'.$q.'%')
                ->or_like('email', '%'.$q.'%');
	    })
        ->where('role', 'user')
	    ->where_null('deleted_at')
        ->where_not_null('updated_at')
        ->order_by('updated_at', 'DESC');


            // Clone before pagination
            $countQuery = clone $query;

            $data['total_rows'] = $countQuery->select_count('*', 'count')
                                            ->get()['count'];

            $data['records'] = $query->pagination($records_per_page, $page)
                                    ->get_all();

            return $data;
        }
    }

    public function applicants($q, $records_per_page = null, $page = null) {
        if (is_null($page)) {
            return $this->db->table('users')->get_all();
        } else {
            $query = $this->db->table('users');
                
            // Build LIKE conditions

	    $query->grouped(function($x) use ($q) {
		    $x->like('username', '%'.$q.'%')
                ->or_like('email', '%'.$q.'%');
	    })
        ->where('role', 'unverified')
	    ->where_null('deleted_at')
        ->where_not_null('updated_at')
        ->order_by('updated_at', 'DESC');


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