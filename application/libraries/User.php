<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class User {

    protected $CI;

    # TEMP
    const USER_TYPE = array(
        '1' => 'ADMIN',
        '2' => 'CLIENT',
        '3' => 'MONITOR'
    );

    public function __construct(){
        $this->CI =& get_instance();
    }

    public function is_valid_and_set_session ($id, $pass){
        $user = $this->CI->Users_model->is_exist($id, $pass);
        if ($user) {
            $this->_set_user_session($user->user_type_id, $user->id);
            return 1;
        } else {
            return 0;
        }
    }

    public function get_current_user_info() {
        $id = $this->CI->session->userdata('user_id');
        # fetch db
        $info = $this->CI->Users_model->get_user_info_by_id($id);
        $info['type'] = self::USER_TYPE[$this->CI->session->userdata('user_type_id')];

        return $info;
    }

    public function is_enable_create_user(){
        $user_info = $this->get_current_user_info();
        return (!strcmp($user_info['type'], 'ADMIN') || !strcmp($user_info['type'], 'CLIENT') ) ? 1 : 0;
    }

    public function unset_session(){
        $this->CI->session->sess_destroy();
    }

    private function _set_user_session($type, $id) {
        #set session
        $this->CI->session->set_userdata('user_type_id', $type);
        $this->CI->session->set_userdata('user_id', $id);
    }

}
/* End of file User.php */
