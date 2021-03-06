<?php
    class Research extends CI_Controller {
        public function index(){

            $user_info = $this->user->get_current_user_info();
            $lists = $this->researches->get_show_lists($user_info);
            $data['show_lists'] = $lists;
            $data['user'] = $user_info;

            $this->load->view('header', $data);
            $this->load->view('research', $data);
        }

        public function create() {

            if (! $this->user->is_enable_create_user()) {
                $this->user->unset_session();
                redirect('login');
            }

            if($this->form_validation->run('research') == TRUE) {
                #TODO research name UNIQUE check
                $this->researches->create_research(array(
                    'create_user_id'     => $this->session->userdata('user_id'),
                    'name'   => $this->input->post('name'),
                    'reword' => $this->input->post('reword'),
                ));
                redirect('research', 'location');
            } else {
                $this->load->view('/research/create');
            }
        }

        public function lists($id){

            if($this->form_validation->run('research') == TRUE) {
                 $updated_info = array(
                    'name'   => $this->input->post('name'),
                    'reword' => $this->input->post('reword'),
                );
                $research = $this->researches->update_research($id, $updated_info);
                redirect('research', 'location');
            } else {
                $research = $this->researches->get_research_by_id($id);
                $data['research'] = $research;
                $this->load->view('/research/update', $data);
            }
        }

        public function disable($id) {
            if ($this->input->server('REQUEST_METHOD') === 'GET') {
                $research = $this->researches->get_research_by_id($id);
                $data['research'] = $research;
                $this->load->view('/research/disable', $data);
            } elseif ($this->input->server('REQUEST_METHOD') === 'POST') {
                $this->researches->disable_research_by_id($id);
                redirect('research', 'location');
            }
        }

        public function execute($research_id) {

            $user_id = $this->session->userdata('user_id');
            $this->researches->pay_reword($user_id, $research_id);
            redirect('/research');

        }

    }
