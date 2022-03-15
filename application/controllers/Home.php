<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
	function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('pagination');
        $this->load->library('session');
        $this->load->model('m_qr');
        $this->load->model('m_user');

        if (null === $this->session->userdata('username')) {
                redirect('main');
        }
	}

    function privilege_allowance($min_level){
        if($this->session->userdata('privilege') > $min_level){
            echo "<script type=\"text/javascript\">
                    alert(\"You don't have the privilege to access this page\");
                    window.location.replace('".site_url()."home');
                </script>";
        }
    }

//===============================================================================
//=============================USER==============================================
//===============================================================================
    public function account_privileges(){
        $this->privilege_allowance(1);
        $data = array(
                        'nik'           => $this->session->userdata('username'),
                        'nama'          => $this->session->userdata('name'),
                        'breadcrumbs'   => "Account Setting > Account Privileges",
                );
        $data['total_rows'] = $this->count_item('nik', null);
        $data['per_page']   = 10;
        $this->load->view('header',$data);
        $this->load->view('account_privileges',$data);
        $this->load->view('footer');
    }

    public function delete_user_ajax(){
        $this->privilege_allowance(1);
        $where = array(
                    'nik'  => $this->input->post('nik')
        );
        $result = $this->m_user->delete_user($where, null);
        echo json_encode($result);
    }

    public function update_user_ajax(){
        $this->privilege_allowance(1);
        $where['nik']  = $this->input->post('nik');
        $data          = $this->input->post();
        unset($data['old_nik']);

        $result = $this->m_user->update_user($where,$data);
        echo json_encode($result);
    }

    function insert_user_function(){
        $this->privilege_allowance(2);
        $data   = $this->input->post(); 
        $result = $this->m_user->insert_user($data, "single");
        echo json_encode($result);
    }

//===============================================================================
//===================================USER========================================
//===============================================================================    

    public function export_data_site(){
        $spreadsheet = $this->m_map->get_sites(null);
        $data = array(
                'nik'           => $this->session->userdata('username'),
                'nama'          => $this->session->userdata('name'),
                'breadcrumbs'   => "Data ODP > Export Data Site",
                'rows' => $spreadsheet
        );
        $this->load->view('header',$data);
        $this->load->view('export_data_sites', $data);
        $this->load->view('footer');
    }

    public function export_data_link_sites(){
        $spreadsheet = $this->m_map->get_sites(null);
        $data = array(
                'nik'           => $this->session->userdata('username'),
                'nama'          => $this->session->userdata('name'),
                'breadcrumbs'   => "Data ODP > Export Data Link Sites",
                'rows' => $spreadsheet
        );
        $this->load->view('header',$data);
        $this->load->view('export_data_link_sites', $data);
        $this->load->view('footer');
    }

    function write_excel($spreadsheet,$table, $filename){
        //echo "zend_extension=/usr/lib64/php/modules/xdebug.so" > /etc/php.d/xdebug.ini
        require_once APPPATH . '/third_party/PHPExcel/PHPExcel.php';
        // require_once APPPATH . '/third_party/PHPExcel/PHPExcel.php';

        $excel = new PHPExcel();
        $excel->getProperties()->setCreator('Magenta MSO')
                 ->setLastModifiedBy('Magenta MSO')
                 ->setTitle("Magenta")
                 ->setSubject($filename)
                 ->setDescription("Data")
                 ->setKeywords("Data");
        // $query = $this->m_map->get_sites();
        $fields = $this->db->list_fields($table);
        // print_r($fields);return;
        $col = 0;
        foreach ($fields as $field)
        {
            $excel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
            $col++;
        }
 
        // Fetching the table data
        $row = 2;
        foreach($spreadsheet as $data)
        {
            $col = 0;
            foreach ($fields as $field)
            {
                $excel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $data[$field]);
                $col++;
            }
 
            $row++;
        }
 
        $excel->setActiveSheetIndex(0);
        
        $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
 
        // Sending headers to force the user to download the file
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename. '_' .date('dMy').'.xls"');
        header('Cache-Control: max-age=0');
 
        $objWriter->save('php://output');
        //echo "sss";
    }

    //public function read_excel($filepath, $delimiter = NULL){
    

    public function data_site(){
        $data = array(
                    'nik'   => $this->session->userdata('username'),
                    'nama'  => $this->session->userdata('name'),
                    'breadcrumbs'   => "Database Node > Data Node",
                    // 'list_witel'    => $list_witel
                );

        $data['total_rows'] = $this->count_item('site_id', null);
        $data['per_page']   = 10;

        $this->load->view('header',$data);
        $this->load->view('data_site',$data);
        $this->load->view('footer');
    }

    public function get_data_site_ajax_pagination(){
        $start  = $this->input->post('start');
        $limit  = $this->input->post('limit');
        $result = $this->m_map->fetch_site($limit,($start - 1) * 5); 
        for($i = 0;$i < count($result);$i++){
            $result[$i]['no'] = (($start - 1) * 5) + 1 + $i;
        }
        echo json_encode($result);
    }


   

   

    public function update_link_sites_ajax(){
        $this->privilege_allowance(2);
        $where['link_id_a_to_b']  = $this->input->post('old_link_id_a_to_b');
        $data               = $this->input->post();
        unset($data['old_link_id_a_to_b']);

        // print_r($where);return;
        $result = $this->m_map->update_link_sites($where,$data);
        echo json_encode($result);
    }

   

    

    

    public function update_site_ajax(){
        $this->privilege_allowance(2);
        $where['site_id']  = $this->input->post('old_site_id');
        $data               = $this->input->post();
        unset($data['old_site_id']);

        $result = $this->m_map->update_site($where,$data);
        echo json_encode($result);
    }

    public function delete_site_ajax(){
        $this->privilege_allowance(2);
        $where = array(
                    'site_id'  => $this->input->post('site_id')
        );
        $result = $this->m_map->delete_site($where);
        echo json_encode($result);
    }

    public function delete_link_sites_ajax(){
        $this->privilege_allowance(2);
        $where = array(
                    'link_id_a_to_b'  => $this->input->post('link_id_a_to_b')
        );
        $result = $this->m_map->delete_link_sites($where);
        echo json_encode($result);
    }


    public function input_site(){
        $this->privilege_allowance(2);
        $list_witel = $this->m_map->get_list_witel(); 

        $data = array(
                    'nik'           => $this->session->userdata('username'),
                    'nama'          => $this->session->userdata('name'),
                    'list_witel'    => $list_witel,
                    'breadcrumbs'   => "Database Node > Input Node"
                );
        $this->load->view('header',$data);
        $this->load->view('input_site',$data);
        $this->load->view('footer');
    }

    public function input_site_function(){
        $this->privilege_allowance(2);
        $data = $this->input->post();
        if(!$data){
            redirect(site_url()."home");
        }

        $result = $this->m_map->insert_site($data, 'single');
        echo json_encode($result);
        return;
    }

    public function input_link_sites(){
        $this->privilege_allowance(2);
        $list_witel = $this->m_map->get_list_witel(); 
        // print_r($list_witel);

        $data = array(
                    'nik'           => $this->session->userdata('username'),
                    'nama'          => $this->session->userdata('name'),
                    'list_witel'    => $list_witel,
                    'breadcrumbs'   => "Database Node > Input Link Sites"
                );
        
    }

    public function input_link_sites_function(){
        $this->privilege_allowance(2);
        $data = $this->input->post();
        if(!$data){
            redirect(site_url()."home");
        }

        $result = $this->m_map->insert_link_sites($data, 'single');
        echo json_encode($result);
        return;
    }

    public function record_count($type=null){
        return $this->m_map->get_count($type);
    }

    public function get_ajax_site_pagination(){
        $like = ($this->input->get('like'))?$this->input->get('like'):null;
        $field = ($this->input->get('field'))?$this->input->get('field'):null;

        $pageNumber = $this->input->get('pageNumber');
        $per_page = $this->input->get('pageSize');

        // harus
        if($pageNumber == 1){
            $start = 0;
        }
        else{
            $start = ($per_page * ($pageNumber - 1));    
        }

        $data_like = array(
            $field => $like
        );

        $result = $this->m_map->fetch_site($per_page,$start, null, $data_like);
        echo json_encode($result);
    }

    public function get_ajax_link_sites_pagination(){
        $like = ($this->input->get('like'))?$this->input->get('like'):null;
        $field = ($this->input->get('field'))?$this->input->get('field'):null;

        $pageNumber = $this->input->get('pageNumber');
        $per_page = $this->input->get('pageSize');

        // harus
        if($pageNumber == 1){
            $start = 0;
        }
        else{
            $start = ($per_page * ($pageNumber - 1));    
        }

        $data_like = array(
            $field => $like
        );

        $result = $this->m_map->fetch_link_sites($per_page,$start, null, $data_like);
        echo json_encode($result);
    }

    public function get_ajax_user_pagination(){
        $like = ($this->input->get('like'))?$this->input->get('like'):null;
        $pageNumber = $this->input->get('pageNumber');
        $per_page = $this->input->get('pageSize');

        // harus
        if($pageNumber == 1){
            $start = 0;
        }
        else{
            $start = ($per_page * ($pageNumber - 1));    
        }

        $data_like = array(
            'nik' => $like
        );
        //$start = ($config['per_page'] * $this->input->get('pageNumber')) - 1;
        // print_r($data_like);return;
        $result = $this->m_user->fetch_user($per_page,$start, null, $data_like);
        echo json_encode($result);
    }

    

    public function count_item($type=null, $like_value=null, $ajax=false){
        if($this->input->post('type')){
            $type   = $this->input->post('type');
        }
        if($this->input->post('like')){
            $like_value   = $this->input->post('like');
        }
        if($this->input->post('ajax')){
            $ajax   = $this->input->post('ajax');
        }


        $like = array(
                $type   => $like_value
        );
        //print_r($like);return;
        if($ajax){
            echo $this->m_map->get_count($type, $like);
            return;
        }
        else{
            return $this->m_map->get_count($type, $like);    
        }
        
    }

    

    public function data_link_sites(){
        $data = array(
                    'nik'   => $this->session->userdata('username'),
                    'nama'  => $this->session->userdata('name'),
                    'breadcrumbs'   => "Database Nodes > Data Link Sites",
                );

        $data['total_rows'] = $this->count_item('link_id_a_to_b', null);
        $data['per_page']   = 10;
        $this->load->view('header',$data);
        $this->load->view('data_link_sites',$data);
        $this->load->view('footer');
    }

    function input_bulk_site(){   
        $this->privilege_allowance(2); 
        $data = array(
                    'nik'           => $this->session->userdata('username'),
                    'nama'          => $this->session->userdata('name'),
                    'breadcrumbs'   => "Database Node > Input Bulk Site",
                    'link'          => site_url() . "home/upload_bulk_site",
                    'link_template' => site_url() . "files/template_site.xlsx",
                    'text_template' => "Download Template Bulk Site",
                );
        $this->load->view('header',$data);
        $this->load->view('input_bulk',$data);
        $this->load->view('footer');
    }

    function input_bulk_link_sites(){
        $this->privilege_allowance(2);
        $data = array(
                    'nik'           => $this->session->userdata('username'),
                    'nama'          => $this->session->userdata('name'),
                    'breadcrumbs'   => "Database Node > Input Bulk Link Sites",
                    'link'          => site_url() . "home/upload_bulk_link_sites",
                    'link_template' => site_url() . "files/template_link_sites.xlsx",
                    'text_template' => "Download Template Bulk Link Sites",
                );
        $this->load->view('header',$data);
        $this->load->view('input_bulk',$data);
        $this->load->view('footer');
    }

    

    function upload_bulk_site(){
        $this->privilege_allowance(2);
        $delimiter = NULL;

        if($this->input->post('delimiter_par')){
            $delimiter = $this->input->post('delimiter_par');
        }

        $extension  = $this->input->post('extension');
        $filepath   = $_FILES['fileupload']['tmp_name'];
        // $filepath = "";
        if($filepath and isset($filepath) and $filepath != ""){
            $excel_data = $this->read_excel($filepath, $extension  ,$delimiter);
        }
        else{
             echo "<script>alert('Session File has reached timeout');
                    window.location.replace('".site_url()."home/input_bulk_site');
                </script>";
        }
        $result     = [];
        $error_count = 0;
        $ok_count = 0;
        foreach ($excel_data as $key => $value) {
            $result  = $this->m_map->insert_site($excel_data[$key],"single");
            $excel_data[$key]['result_code'] = $result['code'];
            if($result['code'] != 0){
                $error_count++;
                $excel_data[$key]['result_message'] = $result['message'];
            }
            else{
                $ok_count++;
                $excel_data[$key]['result_message'] = "OK";
            }
        }

        $data = array(
                    'nik'           => $this->session->userdata('username'),
                    'nama'          => $this->session->userdata('name'),
                    'breadcrumbs'   => "Database Node > Result Bulk Site",
                    'data'          => $excel_data,
                    'error_count'   => $error_count,
                    'ok_count'      => $ok_count
                );
        $this->load->view('header',$data);
        $this->load->view('bulk_result',$data);
        $this->load->view('footer');
    }

    function upload_bulk_link_sites(){
        $this->privilege_allowance(2);
        $delimiter = NULL;

        if($this->input->post('delimiter_par')){
            $delimiter = $this->input->post('delimiter_par');
        }

        $extension  = $this->input->post('extension');
        $filepath   = $_FILES['fileupload']['tmp_name'];  
        if($filepath and isset($filepath) and $filepath != ""){
            $excel_data = $this->read_excel($filepath, $extension  ,$delimiter);
        }
        else{
             echo "<script>alert('Session File has reached timeout');
                    window.location.replace('".site_url()."home/input_bulk_link_sites');
                </script>";
        }

        $result     = [];
        $error_count = 0;
        $ok_count = 0;
 
        foreach ($excel_data as $key => $value) {
            $result  = $this->m_map->insert_link_sites($excel_data[$key],"single");
            $excel_data[$key]['result_code'] = $result['code'];
            if($result['code'] != 0){
                $error_count++;
                $excel_data[$key]['result_message'] = $result['message'];
            }
            else{
                $ok_count++;
                $excel_data[$key]['result_message'] = "OK";
            }
        }

        $data = array(
                    'nik'           => $this->session->userdata('username'),
                    'nama'          => $this->session->userdata('name'),
                    'breadcrumbs'   => "Database Node > Result Bulk Link Sites",
                    'link_template' => site_url() . "files/template_link_sites.xlsx",
                    'text_template' => "Download Template Bulk Link Sites",
                    'data'          => $excel_data,
                    'error_count'   => $error_count,
                    'ok_count'      => $ok_count
                );
        $this->load->view('header',$data);
        $this->load->view('bulk_result',$data);
        $this->load->view('footer');
    }

    

//=============================================================================
//======================================ODP====================================
//=============================================================================
    public function data_odp($page=''){
        $data = array(
                    'nik'   => $this->session->userdata('username'),
                    'nama'  => $this->session->userdata('name'),
                    'breadcrumbs'   => "Database ODP > Data ODP",
                );

        $data['total_rows'] = $this->count_item('odp_name', null);
        $data['per_page']   = 10;
        $this->load->view('header',$data);
        $this->load->view('data_odp',$data);
        $this->load->view('footer');
    }

     public function update_odp_ajax(){
        $this->privilege_allowance(2);

        $where['odp_name']  = $this->input->post('old_odp_name');
        $data               = $this->input->post();
        unset($data['old_odp_name']);

        // print_r($where);return;
        $result = $this->m_map->update_odp($where,$data);
        echo json_encode($result);
    }

    public function get_ajax_odp_pagination(){
        $like = ($this->input->get('like'))?$this->input->get('like'):null;
        $pageNumber = $this->input->get('pageNumber');
        $per_page = $this->input->get('pageSize');

        // harus
        if($pageNumber == 1){
            $start = 0;
        }
        else{
            $start = ($per_page * ($pageNumber - 1));    
        }

        $data_like = array(
            'odp_name' => $like
        );
        //$start = ($config['per_page'] * $this->input->get('pageNumber')) - 1;
        // print_r($data_like);return;
        $result = $this->m_map->fetch_odp($per_page,$start, null, $data_like);
        echo json_encode($result);
    }

    function input_odp(){
        $this->privilege_allowance(2);
        $list_witel = $this->m_map->get_list_witel(); 
        // print_r($list_witel);

        $data = array(
                    'nik'           => $this->session->userdata('username'),
                    'nama'          => $this->session->userdata('name'),
                    'list_witel'    => $list_witel,
                    'breadcrumbs'   => "Database ODP > Input ODP"
                );
        $this->load->view('header',$data);
        $this->load->view('input_odp',$data);
        $this->load->view('footer');
    }

    function insert_odp_function(){
        $this->privilege_allowance(2);
        $data   = $this->input->post(); 
        $result = $this->m_map->insert_odp($data, "single");
        echo json_encode($result);
    }

    function input_bulk_odp(){
        $this->privilege_allowance(2);
        $data = array(
                    'nik'           => $this->session->userdata('username'),
                    'nama'          => $this->session->userdata('name'),
                    'breadcrumbs'   => "Database ODP > Input Bulk ODP",
                    'link'          => site_url() . "home/upload_bulk_odp",
                    'link_template' => site_url() . "files/template_odp.xlsx",
                    'text_template' => "Download Template Bulk ODP",
                );
        $this->load->view('header',$data);
        $this->load->view('input_bulk',$data);
        $this->load->view('footer');
    }

     public function delete_odp_ajax(){
        $this->privilege_allowance(2);
        $where = array(
                    'odp_name'  => $this->input->post('odp_name')
        );
        $result = $this->m_map->delete_odp($where);
        echo json_encode($result);
    }
    
    public function upload_bulk_odp(){
        $this->privilege_allowance(2);
        $delimiter = NULL;

        if($this->input->post('delimiter_par')){
            $delimiter = $this->input->post('delimiter_par');
        }

        $extension  = $this->input->post('extension');
        $filepath   = $_FILES['fileupload']['tmp_name'];
        if($filepath and isset($filepath) and $filepath != ""){
            $excel_data = $this->read_excel($filepath, $extension  ,$delimiter);
        }
        else{
             echo "<script>alert('Session File has reached timeout');
                    window.location.replace('".site_url()."home/input_bulk_odp');
                </script>";
        }       
        $result     = [];
        $error_count = 0;
        $ok_count = 0;
        foreach ($excel_data as $key => $value) {
            $result  = $this->m_map->insert_odp($excel_data[$key],"single");
            $excel_data[$key]['result_code'] = $result['code'];
            if($result['code'] != 0){
                $error_count++;
                $excel_data[$key]['result_message'] = $result['message'];
            }
            else{
                $ok_count++;
                $excel_data[$key]['result_message'] = "OK";
            }
        }

        $data = array(
                    'nik'           => $this->session->userdata('username'),
                    'nama'          => $this->session->userdata('name'),
                    'breadcrumbs'   => "Database ODP > Result Bulk ODP",
                    'data'          => $excel_data,
                    'error_count'   => $error_count,
                    'ok_count'      => $ok_count
                );
        $this->load->view('header',$data);
        $this->load->view('bulk_result',$data);
        $this->load->view('footer');
    }

    public function export_data_odp(){
        $spreadsheet = $this->m_map->get_odp(null);
        $data = array(
                'nik'           => $this->session->userdata('username'),
                'nama'          => $this->session->userdata('name'),
                'breadcrumbs'   => "Data ODP > Export Data ODP",
                'rows' => $spreadsheet
        );
        $this->load->view('header',$data);
        $this->load->view('export_data_odp', $data);
        $this->load->view('footer');
    }


//=============================================================================
//======================================ODP====================================
//=============================================================================
     public function index(){  
        $this->privilege_allowance(2); 
        $data = array(
                    'nik'           => $this->session->userdata('username'),
                    'nama'          => $this->session->userdata('name'),
                    'breadcrumbs'   => "Database QR Code > Input Bulk QR Code",
                    'link'          => site_url() . "home/upload_bulk_qr",
                    'link_template' => site_url() . "files/template_qr.xlsx",
                    'text_template' => "Download Template Upload Bulk QR Code",
                );
        $this->load->view('header',$data);
        $this->load->view('input_bulk',$data);
        $this->load->view('footer');
    }

    function upload_bulk_qr(){
        $this->privilege_allowance(2);
        $delimiter = NULL;

        if($this->input->post('delimiter_par')){
            $delimiter = $this->input->post('delimiter_par');
        }

        $extension  = $this->input->post('extension');
        $filepath   = $_FILES['fileupload']['tmp_name'];
        // $filepath = "";
        if($filepath and isset($filepath) and $filepath != ""){
            $excel_data = $this->read_excel($filepath, $extension  ,$delimiter);
        }
        else{
             echo "<script>alert('Session File has reached timeout');
                    window.location.replace('".site_url()."home/');
                </script>";
        }
        $result     = [];
        $error_count = 0;
        $ok_count = 0;
        foreach ($excel_data as $key => $value) {
            $result  = $this->m_qr->insert_qr($excel_data[$key],"single");
            $excel_data[$key]['result_code'] = $result['code'];
            if($result['code'] != 0){
                $error_count++;
                $excel_data[$key]['result_message'] = $result['message'];
            }
            else{
                $ok_count++;
                $excel_data[$key]['result_message'] = "OK";
            }
        }

        $data = array(
                    'nik'           => $this->session->userdata('username'),
                    'nama'          => $this->session->userdata('name'),
                    'breadcrumbs'   => "Database Node > Result Bulk QR",
                    'data'          => $excel_data,
                    'error_count'   => $error_count,
                    'ok_count'      => $ok_count
                );
        $this->load->view('header',$data);
        $this->load->view('bulk_result',$data);
        $this->load->view('footer');
    }

    function read_excel($filepath, $extension,$delimiter=null){
        require APPPATH . 'third_party/excel_reader/vendor/autoload.php';
        $pathinfo = pathinfo($filepath);

        if ($extension == 'xlsx'){
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        }
        else if ($extension == 'xls'){
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        }
        else if ($extension == 'csv'){
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            $reader->setInputEncoding('ASCII');
            // $reader->setDelimiter(';');
            $reader->setEnclosure('');
            $reader->setSheetIndex(0);
            if($delimiter){
                $reader->setDelimiter($delimiter);
            }
            else{
                $reader->setDelimiter(',');
            }
        }
        else {
            echo "File not recognized as .csv, .xlsx, .xls. Try Again!";
        }
        #PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
	$spreadsheet = $reader->load($filepath);
        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        $headers = array_shift($sheetData);

        $data = array();
        foreach ($sheetData as $key_row => $row) {
            // print_r($row);
            $row_array = array();
            for($i=0;$i<count($headers);$i++){  
                $row_array[$headers[$i]] = $row[$i];
            }
            array_push($data, $row_array);
        }

        return $data;
    }

    public function logout() {
        $this->session->sess_destroy();
        $this->session->unset_userdata('nik');
        $this->session->unset_userdata('nama');
        $this->session->unset_userdata('email');

        echo "<script type='text/javascript'>
                location = '".site_url()."main';
              </script>";
    }

    function get_a_site(){
        $site_id   = $this->input->post('site_id');
        $data = array(
                'site_id'   => $site_id
            );

        $a = $this->m_map->get_sites($data);

        if($a != '-'){
            $msg = array(
                    'site_id'           => $a[0]['site_id'],
                    'site_name'         => $a[0]['site_name'],
                    'lat'               => $a[0]['latitude'],
                    'long'              => $a[0]['longitude'],
                    'transport_3g'      => $a[0]['transport_3g'],
                    'metro'             => $a[0]['metro'],
                    'metro_port'        => $a[0]['metro_port'],
                    'vlan'              => $a[0]['vlan'],
                    'ran'               => $a[0]['ran'],
                    'olt'               => $a[0]['olt'],
                    'olt_port'          => $a[0]['olt_port'],
                    'ip_ont'            => $a[0]['ip_ont'],
                    'tipe_transmisi'    => $a[0]['tipe_transmisi'],
                    'warna'             => $a[0]['warna']
                    );
            echo json_encode($msg);
        }
        else{
            echo "0";
        }
    }

    function get_a_odp(){
        $odp_name   = $this->input->post('odp_name');
        $data = array(
                'odp_name'   => $odp_name
            );

        $a = $this->m_map->get_odp($data);

        if($a != '-'){
            $msg = array(
                        'odp_name'      => $a[0]['odp_name'],
                        'lat'           => $a[0]['latitude'],
                        'long'          => $a[0]['longitude'],
                        'witel'         => $a[0]['witel']
                    );
            echo json_encode($msg);
        }
        else{
            echo "0";
        }
    }


    function get_data_site(){
        $lat    = $this->input->post('lat');
        $long   = $this->input->post('long');
        $radius = $this->input->post('radius');

        $a = $this->m_map->get_sites();

        if($a != '-'){

            $msg = [];
            foreach($a as $row){
                $distance_ = $this->distanceCalculation($lat, $long, $row['latitude'], $row['longitude']);
                // print_r($distance_);
                if($distance_ <= $radius){
                    $row_   = array(
                    'site_id'           => $row['site_id'],
                    'site_name'         => $row['site_name'],
                    'lat'               => $row['latitude'],
                    'long'              => $row['longitude'],
                    'transport_3g'      => $row['transport_3g'],
                    'metro'             => $row['metro'],
                    'metro_port'        => $row['metro_port'],
                    'vlan'              => $row['vlan'],
                    'ran'               => $row['ran'],
                    'olt'               => $row['olt'],
                    'olt_port'          => $row['olt_port'],
                    'ip_ont'            => $row['ip_ont'],
                    'tipe_transmisi'    => $row['tipe_transmisi'],
                    'warna'             => $row['warna'],
                    'distance'          =>  $distance_
                    );  
                    array_push($msg, $row_);
                    $row_ = [];
                }
            }
        }
         
        else {
            echo "0";
        }
        echo json_encode($msg);
    }

    public function get_link(){
        $lat    = $this->input->post('lat');
        $long   = $this->input->post('long');
        $radius = $this->input->post('radius');

        $a = $this->m_map->get_links();
        // print_r($a);
        // return;
        // print_r($a);        return;
        if($a != '-'){

            $msg = [];
            foreach($a as $row){
                $distance_a = $this->distanceCalculation(floatval($lat), floatval($long), floatval($row['site_a_lat']), floatval($row['site_a_long']));
                $distance_b = $this->distanceCalculation(floatval($lat), floatval($long), floatval($row['site_b_lat']), floatval($row['site_b_long']));
                // print_r($distance_);
                if($distance_a <= $radius or $distance_b <= $radius){
                // if($distance_a <= $radius){
                    $row_   = array(
                        'type'              => 'link',
                        'link_id_a_to_b'    => $row['link_id_a_to_b'], 
                        'site_a_lat'        => $row['site_a_lat'],
                        'site_a_long'       => $row['site_a_long'],
                        'site_b_lat'        => $row['site_b_lat'],
                        'site_b_long'       => $row['site_b_long'],
                        'distance_a'        => $distance_a,
                        'distance_b'        => $distance_b
                    );  
                    array_push($msg, $row_);
                    $row_ = [];
                }
            }
        }
         
        else {
            echo "0";
        }
        echo json_encode($msg);
    }

    public function get_all_link_site(){
        $lat    = $this->input->post('lat');
        $long   = $this->input->post('long');
        $radius = $this->input->post('radius');

        $a = $this->m_map->get_links();
        $family_site = [];

        if($a != '-'){
            $msg = [];
            foreach($a as $row){
                $distance_a = $this->distanceCalculation(floatval($lat), floatval($long), floatval($row['site_a_lat']), floatval($row['site_a_long']));
                $distance_b = $this->distanceCalculation(floatval($lat), floatval($long), floatval($row['site_b_lat']), floatval($row['site_b_long']));
                // print_r($distance_);
                if($distance_a <= $radius or $distance_b <= $radius){
                // if($distance_a <= $radius){
                    if(!in_array($row['site_id_fe'],$family_site)){
                        array_push($family_site, $row['site_id_fe']);
                    }
                    if(!in_array($row['site_id_ne'],$family_site)){
                        array_push($family_site, $row['site_id_ne']);
                    }

                    $row_   = array(
                        'type'              => 'link',
                        'link_id_a_to_b'    => $row['link_id_a_to_b'], 
                        'site_a_lat'        => $row['site_a_lat'],
                        'site_a_long'       => $row['site_a_long'],
                        'site_b_lat'        => $row['site_b_lat'],
                        'site_b_long'       => $row['site_b_long'],
                        'distance_a'        => $distance_a,
                        'distance_b'        => $distance_b,
                    );  
                    array_push($msg, $row_);
                    $row_ = [];
                }
            }
        }

        if(count($family_site) > 0 ){




        $b = $this->m_map->get_few_sites($family_site);

        if($b != '-'){
            foreach($b as $row){
                $distance_ = $this->distanceCalculation($lat, $long, $row['latitude'], $row['longitude']);
                // print_r($distance_);
                    $row_   = array(
                        'type'      => 'site',
                        'site_id'           => $row['site_id'],
                        'site_name'         => $row['site_name'],
                        'lat'               => $row['latitude'],
                        'long'              => $row['longitude'],
                        'transport_3g'      => $row['transport_3g'],
                        'metro'             => $row['metro'],
                        'metro_port'        => $row['metro_port'],
                        'vlan'              => $row['vlan'],
                        'ran'               => $row['ran'],
                        'olt'               => $row['olt'],
                        'olt_port'          => $row['olt_port'],
                        'ip_ont'            => $row['ip_ont'],
                        'tipe_transmisi'    => $row['tipe_transmisi'],
                        'warna'             => $row['warna'],
                        'distance'          => $distance_
                    );  
                    array_push($msg, $row_);
                    $row_ = [];

            }
        }

        $c = $this->m_map->get_few_links($family_site);
        foreach($c as $row){
                $distance_a = $this->distanceCalculation(floatval($lat), floatval($long), floatval($row['site_a_lat']), floatval($row['site_a_long']));
                $distance_b = $this->distanceCalculation(floatval($lat), floatval($long), floatval($row['site_b_lat']), floatval($row['site_b_long']));
                $row_   = array(
                    'type'              => 'link',
                    'link_id_a_to_b'    => $row['link_id_a_to_b'], 
                    'site_a_lat'        => $row['site_a_lat'],
                    'site_a_long'       => $row['site_a_long'],
                    'site_b_lat'        => $row['site_b_lat'],
                    'site_b_long'       => $row['site_b_long'],
                    'distance_a'        => $distance_a,
                    'distance_b'        => $distance_b
                );  
                array_push($msg, $row_);
                $row_ = [];
            }   
            echo json_encode($msg);
        }
        else{
            echo "0";
        }
        
    }

    private function distanceCalculation($point1_lat, $point1_long, $point2_lat, $point2_long, $unit = 'meter', $decimals = 2) {
        // Calculate the distance in degrees
        $degrees = rad2deg(acos((sin(deg2rad((float)$point1_lat)) * sin(deg2rad((float)$point2_lat))) + (cos(deg2rad((float)$point1_lat)) * cos(deg2rad((float)$point2_lat)) * cos(deg2rad((float)$point1_long - (float)$point2_long)))));

        //print_r($degrees);return;
        // Convert the distance in degrees to the chosen unit (kilometres, miles or nautical miles)
        switch ($unit) {
        case 'km':
            $distance = $degrees * 111.13384; // 1 degree = 111.13384 km, based on the average diameter of the Earth (12,735 km)
            break;
        case 'meter':
            $distance = $degrees * 111133.84; // 1 degree = 111.13384 km, based on the average diameter of the Earth (12,735 km)
            break;
        case 'mi':
            $distance = $degrees * 69.05482; // 1 degree = 69.05482 miles, based on the average diameter of the Earth (7,913.1 miles)
            break;
        case 'nmi':
            $distance = $degrees * 59.97662; // 1 degree = 59.97662 nautic miles, based on the average diameter of the Earth (6,876.3 nautical miles)
        }
        return round($distance, $decimals);
    }

    public function get_employee_by_unit(){
        $unit = $this->input->post('unit');
        $data = array(
            'unit'  => $unit
        );

        $result = $this->m_vote->get_pegawai($data);
        if($result){
                $message = $result;
        }
        else{
                $message = "no";
        }
        echo json_encode($message);

    }
}
