<?php if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}
include "phpmailer/src/PHPMailer.php";
include "phpmailer/src/Exception.php";
include "phpmailer/src/OAuth.php";
include "phpmailer/src/POP3.php";
include "phpmailer/src/SMTP.php";

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class User extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    //load model user
    $this->load->model('user/user_model');
    $this->load->helper(['locdau', 'form_helper', 'text', 'url', 'cookie']);
    $this->load->library(['form_validation', 'session', 'pagination']);
    $this->load->driver('cache');
    $this->load->database();
    // load thu vien validation
    global $salary;
    global $sex;
    global $learn_rank;
    global $marriage;
    global $my_exp;
    global $level;
    global $language;
    global $job_form;
    global $type_work;
    global $degree;
    global $user_news;
    if (!$user_news = $this->cache->file->get('user_news')) {
      $date      = time();
      $sql       = "SELECT un.*,u.avatar FROM `user_news` as un INNER JOIN `users` as u ON un.user_id = u.id WHERE un.active <> 0 AND un.expdate >= '$date' ORDER BY un.id DESC LIMIT 20";
      $user_news = $this->db->query($sql)->result_array();
      $this->cache->file->save('user_news', $user_news, 180);
    }
    //
    if (!$type_work = $this->cache->file->get('type_work')) {
      $type_work = $this->user_model->getSelectOrder('type_work', 'name', 'asc');
      $this->cache->file->save('type_work', $type_work, 36000);
    }
    if (!$salary = $this->cache->file->get('salary')) {
      $salary = $this->user_model->getSelectOrder('salary', 'name', 'asc');
      $this->cache->file->save('salary', $salary, 36000);
    }
    if (!$sex = $this->cache->file->get('sex')) {
      $sex = $this->user_model->getSelectOrder('sex', 'name', 'asc');
      $this->cache->file->save('sex', $sex, 36000);
    }
    if (!$learn_rank = $this->cache->file->get('learn_rank')) {
      $learn_rank = $this->user_model->getSelectOrder('learn_rank', 'name', 'asc');
      $this->cache->file->save('learn_rank', $learn_rank, 36000);
    }
    if (!$marriage = $this->cache->file->get('marriage')) {
      $marriage = $this->user_model->getSelectOrder('marriage', 'name', 'asc');
      $this->cache->file->save('marriage', $marriage, 36000);
    }
    if (!$my_exp = $this->cache->file->get('my_exp')) {
      $my_exp = $this->user_model->getSelectOrder('my_exp', 'name', 'asc');
      $this->cache->file->save('my_exp', $my_exp, 36000);
    }
    if (!$level = $this->cache->file->get('level')) {
      $level = $this->user_model->getSelectOrder('level', 'name', 'asc');
      $this->cache->file->save('level', $level, 36000);
    }
    if (!$language = $this->cache->file->get('language')) {
      $language = $this->user_model->getSelectOrder('language', 'name', 'asc');
      $this->cache->file->save('language', $language, 36000);
    }
    if (!$job_form = $this->cache->file->get('job_form')) {
      $job_form = $this->user_model->getSelectOrder('job_form', 'name', 'asc');
      $this->cache->file->save('job_form', $job_form, 36000);
    }
    if (!$degree = $this->cache->file->get('degree')) {
      $degree = $this->user_model->getSelectOrder('degree', 'name', 'asc');
      $this->cache->file->save('degree', $degree, 36000);
    }
    if (isset($_COOKIE['siteAuth'])) {
      $username   = $this->input->cookie('siteAuth', true);
      $query_prod = $this->db->where('user_name', $username)->get('users');
      $prod       = $query_prod->row_array();
      $this->session->set_userdata('loginted', $prod);
    }
  }
  public function index()
  {
    global $user_news;
    global $type_work;
    $data['slogan']       = 'TÌM CÔNG VIỆC BẠN YÊU THÍCH';
    $data['description']  = 'Tìm việc làm, việc làm và cơ hội nghề nghiệp.';
    $data['type_work']    = $type_work;
    $data['user_new']     = $user_news;
    $sql                  = $this->db->get_where('tbl_meta', ['id' => 1])->row_array();
    $data['meta_title']   = $sql['title'];
    $data['meta_key']     = $sql['metakeywork'];
    $data['meta_des']     = $sql['metadesc'];
    $data['user_post']    = $this->db->select('id,name,description,image,slug')->from('post')->where(['active' => 0, 'cate_id' => 16])->limit(3, 0)->get()->result_array();
    $data['title']        = 'Web tìm vệc';
    $data['header']       = 'header_3';
    $data['content']      = 'user/index_3';
    $data['footer']       = 'footer_3';
    $data['class_header'] = 'home';
    $this->load->view('master_3', $data);
  }
  public function intro()
  {
    global $user_news;
    global $type_work;
    $data['slogan']       = 'GIỚI THIỆU VỀ MYJOB';
    $data['description']  = 'Tìm việc làm, việc làm và cơ hội nghề nghiệp.';
    $data['type_work']    = $type_work;
    $data['user_new']     = $user_news;
    $sql                  = $this->db->get_where('tbl_meta', ['id' => 1])->row_array();
    $data['meta_title']   = $sql['title'];
    $data['meta_key']     = $sql['metakeywork'];
    $data['meta_des']     = $sql['metadesc'];
    $data['user_post']    = $this->db->select('id,name,description,image,slug')->from('post')->where(['active' => 0, 'cate_id' => 16])->limit(3, 0)->get()->result_array();
    $data['title']        = 'Web tìm vệc';
    $data['header']       = 'header_3';
    $data['content']      = 'user/intro';
    $data['footer']       = 'footer_3';
    $data['class_header'] = 'intro';
    $this->load->view('master_3', $data);
  }
  public function termsofuse()
  {
    global $user_news;
    global $type_work;
    $data['slogan']       = 'ĐIỀU KHOẢN SỬ DỤNG';
    $data['description']  = 'Tìm việc làm, việc làm và cơ hội nghề nghiệp.';
    $data['type_work']    = $type_work;
    $data['user_new']     = $user_news;
    $sql                  = $this->db->get_where('tbl_meta', ['id' => 1])->row_array();
    $data['meta_title']   = $sql['title'];
    $data['meta_key']     = $sql['metakeywork'];
    $data['meta_des']     = $sql['metadesc'];
    $data['user_post']    = $this->db->select('id,name,description,image,slug')->from('post')->where(['active' => 0, 'cate_id' => 16])->limit(3, 0)->get()->result_array();
    $data['title']        = 'Web tìm vệc';
    $data['header']       = 'header_3';
    $data['content']      = 'user/termsofuse';
    $data['footer']       = 'footer_3';
    $data['class_header'] = 'termsofuse';
    $this->load->view('master_3', $data);
  }
  public function contactus()
  {
    global $user_news;
    global $type_work;
    $data['slogan']       = 'LIÊN HỆ CHÚNG TÔI';
    $data['description']  = 'Tìm việc làm, việc làm và cơ hội nghề nghiệp.';
    $data['type_work']    = $type_work;
    $data['user_new']     = $user_news;
    $sql                  = $this->db->get_where('tbl_meta', ['id' => 1])->row_array();
    $data['meta_title']   = $sql['title'];
    $data['meta_key']     = $sql['metakeywork'];
    $data['meta_des']     = $sql['metadesc'];
    $data['user_post']    = $this->db->select('id,name,description,image,slug')->from('post')->where(['active' => 0, 'cate_id' => 16])->limit(3, 0)->get()->result_array();
    $data['title']        = 'Web tìm vệc';
    $data['header']       = 'header_3';
    $data['content']      = 'user/contactus';
    $data['footer']       = 'footer_3';
    $data['class_header'] = 'contactus';
    $this->load->view('master_3', $data);
  }
  public function disputeresolutionprocess()
  {
    global $user_news;
    global $type_work;
    $data['slogan']       = 'QUY TRÌNH GIẢI QUYẾT TRANH CHẤP';
    $data['description']  = 'Tìm việc làm, việc làm và cơ hội nghề nghiệp.';
    $data['type_work']    = $type_work;
    $data['user_new']     = $user_news;
    $sql                  = $this->db->get_where('tbl_meta', ['id' => 1])->row_array();
    $data['meta_title']   = $sql['title'];
    $data['meta_key']     = $sql['metakeywork'];
    $data['meta_des']     = $sql['metadesc'];
    $data['user_post']    = $this->db->select('id,name,description,image,slug')->from('post')->where(['active' => 0, 'cate_id' => 16])->limit(3, 0)->get()->result_array();
    $data['title']        = 'Web tìm vệc';
    $data['header']       = 'header_3';
    $data['content']      = 'user/disputeresolutionprocess';
    $data['footer']       = 'footer_3';
    $data['class_header'] = 'disputeresolutionprocess';
    $this->load->view('master_3', $data);
  }
  public function privacypolocy()
  {
    global $user_news;
    global $type_work;
    $data['slogan']       = 'CHÍNH SÁCH BẢO MẬT';
    $data['description']  = 'Tìm việc làm, việc làm và cơ hội nghề nghiệp.';
    $data['type_work']    = $type_work;
    $data['user_new']     = $user_news;
    $sql                  = $this->db->get_where('tbl_meta', ['id' => 1])->row_array();
    $data['meta_title']   = $sql['title'];
    $data['meta_key']     = $sql['metakeywork'];
    $data['meta_des']     = $sql['metadesc'];
    $data['user_post']    = $this->db->select('id,name,description,image,slug')->from('post')->where(['active' => 0, 'cate_id' => 16])->limit(3, 0)->get()->result_array();
    $data['title']        = 'Web tìm vệc';
    $data['header']       = 'header_3';
    $data['content']      = 'user/privacypolocy';
    $data['footer']       = 'footer_3';
    $data['class_header'] = 'privacypolocy';
    $this->load->view('master_3', $data);
  }
  //this story by soan//
  public function employerinformation()
  {
    global $user_news;
    global $type_work;
    $data['slogan']       = 'Công Ty TNHH Sán Xuất Và Dịch Vụ ABC';
    $data['description']  = 'Tìm việc làm, việc làm và cơ hội nghề nghiệp.';
    $data['type_work']    = $type_work;
    $data['user_new']     = $user_news;
    $sql                  = $this->db->get_where('tbl_meta', ['id' => 1])->row_array();
    $data['meta_title']   = $sql['title'];
    $data['meta_key']     = $sql['metakeywork'];
    $data['meta_des']     = $sql['metadesc'];
    $data['user_post']    = $this->db->select('id,name,description,image,slug')->from('post')->where(['active' => 0, 'cate_id' => 16])->limit(3, 0)->get()->result_array();
    $data['title']        = 'Thông tin công ty';
    $data['header']       = 'header_3';
    $data['content']      = 'user/employerinformation';
    $data['footer']       = 'footer_3';
    $data['class_header'] = 'employerinformation';
    $this->load->view('master_3', $data);
  }
  public function candidateinformation()
  {
    global $user_news;
    global $type_work;
    $data['slogan']       = 'Công Ty TNHH Sán Xuất Và Dịch Vụ ABC';
    $data['description']  = 'Tìm việc làm, việc làm và cơ hội nghề nghiệp.';
    $data['type_work']    = $type_work;
    $data['user_new']     = $user_news;
    $sql                  = $this->db->get_where('tbl_meta', ['id' => 1])->row_array();
    $data['meta_title']   = $sql['title'];
    $data['meta_key']     = $sql['metakeywork'];
    $data['meta_des']     = $sql['metadesc'];
    $data['user_post']    = $this->db->select('id,name,description,image,slug')->from('post')->where(['active' => 0, 'cate_id' => 16])->limit(3, 0)->get()->result_array();
    $data['title']        = 'Thông tin ứng viên';
    $data['header']       = 'header_3';
    $data['content']      = 'user/candidateinformation';
    $data['footer']       = 'footer_3';
    $data['class_header'] = 'candidateinformation';
    $this->load->view('master_3', $data);
  }
  public function candidateinformation2()
  {
    global $user_news;
    global $type_work;
    $data['slogan']       = 'Công Ty TNHH Sán Xuất Và Dịch Vụ ABC';
    $data['description']  = 'Tìm việc làm, việc làm và cơ hội nghề nghiệp.';
    $data['type_work']    = $type_work;
    $data['user_new']     = $user_news;
    $sql                  = $this->db->get_where('tbl_meta', ['id' => 1])->row_array();
    $data['meta_title']   = $sql['title'];
    $data['meta_key']     = $sql['metakeywork'];
    $data['meta_des']     = $sql['metadesc'];
    $data['user_post']    = $this->db->select('id,name,description,image,slug')->from('post')->where(['active' => 0, 'cate_id' => 16])->limit(3, 0)->get()->result_array();
    $data['title']        = 'Thông tin ứng viên 2';
    $data['header']       = 'header_3';
    $data['content']      = 'user/candidateinformation2';
    $data['footer']       = 'footer_3';
    $data['class_header'] = 'candidateinformation2';
    $this->load->view('master_3', $data);
  }
  public function savedjobs()
  {
    global $user_news;
    global $type_work;
    $data['slogan']       = 'Công Ty TNHH Sán Xuất Và Dịch Vụ ABC';
    $data['description']  = 'Tìm việc làm, việc làm và cơ hội nghề nghiệp.';
    $data['type_work']    = $type_work;
    $data['user_new']     = $user_news;
    $sql                  = $this->db->get_where('tbl_meta', ['id' => 1])->row_array();
    $data['meta_title']   = $sql['title'];
    $data['meta_key']     = $sql['metakeywork'];
    $data['meta_des']     = $sql['metadesc'];
    $data['user_post']    = $this->db->select('id,name,description,image,slug')->from('post')->where(['active' => 0, 'cate_id' => 16])->limit(3, 0)->get()->result_array();
    $data['title']        = 'Việc làm đã lưu';
    $data['header']       = 'header_3';
    $data['content']      = 'user/savedjobs';
    $data['footer']       = 'footer_3';
    $data['class_header'] = 'savedjobs';
    $this->load->view('master_3', $data);
  }
  public function companyiswatching()
  {
    global $user_news;
    global $type_work;
    $data['slogan']       = 'Công Ty TNHH Sán Xuất Và Dịch Vụ ABC';
    $data['description']  = 'Tìm việc làm, việc làm và cơ hội nghề nghiệp.';
    $data['type_work']    = $type_work;
    $data['user_new']     = $user_news;
    $sql                  = $this->db->get_where('tbl_meta', ['id' => 1])->row_array();
    $data['meta_title']   = $sql['title'];
    $data['meta_key']     = $sql['metakeywork'];
    $data['meta_des']     = $sql['metadesc'];
    $data['user_post']    = $this->db->select('id,name,description,image,slug')->from('post')->where(['active' => 0, 'cate_id' => 16])->limit(3, 0)->get()->result_array();
    $data['title']        = 'Công ty đang theo dỗi';
    $data['header']       = 'header_3';
    $data['content']      = 'user/companyiswatching';
    $data['footer']       = 'footer_3';
    $data['class_header'] = 'companyiswatching';
    $this->load->view('master_3', $data);
  }
  public function cvrecruitment()
  {
    global $user_news;
    global $type_work;
    $data['slogan']       = 'Công Ty TNHH Sán Xuất Và Dịch Vụ ABC';
    $data['description']  = 'Tìm việc làm, việc làm và cơ hội nghề nghiệp.';
    $data['type_work']    = $type_work;
    $data['user_new']     = $user_news;
    $sql                  = $this->db->get_where('tbl_meta', ['id' => 1])->row_array();
    $data['meta_title']   = $sql['title'];
    $data['meta_key']     = $sql['metakeywork'];
    $data['meta_des']     = $sql['metadesc'];
    $data['user_post']    = $this->db->select('id,name,description,image,slug')->from('post')->where(['active' => 0, 'cate_id' => 16])->limit(3, 0)->get()->result_array();
    $data['title']        = 'CV ứng viên';
    $data['header']       = 'header_3';
    $data['content']      = 'user/cvrecruitment';
    $data['footer']       = 'footer_3';
    $data['class_header'] = 'cvrecruitment';
    $this->load->view('master_3', $data);
  }
  // Tài khoản ================================================================================================
  public function login()
  {
    if ($this->session->userdata('loginted')) {
      $this->session->unset_userdata('loginted');
      delete_cookie('siteAuth');
    }
    $this->form_validation->set_rules('username', 'Tên đăng nhập', 'required|callback_check_login_username',
      [
        'required' => 'Vui lòng nhập tên đăng nhập.',
      ]);
    $this->form_validation->set_rules('password', 'Mật khẩu', 'required|callback_check_pass_user',
      [
        'required'        => 'Vui lòng nhập mật khẩu.',
        'check_pass_user' => 'Vui lòng nhập lại mật khẩu',
      ]);
    $username  = $this->input->post('username');
    $autologin = ($this->input->post('remember_me') == '1') ? 1 : 0;
    if ($this->form_validation->run()) {
      // $this->db->where('user_name', $username);
      $this->db->where('email', $username);
      $query     = $this->db->get('users');
      $row_array = $query->row_array();
      if ($row_array > 0) {
        if ($autologin == 1) {
          $cookie = [
            'name'   => 'siteAuth',
            'value'  => $username,
            'expire' => 3600 * 24 * 30,
          ];
          $this->input->set_cookie($cookie);
        }
        $this->session->set_userdata('loginted', $row_array);
        header('Location: ' . base_url());
      } else {
        echo '<script language="javascript">';
        echo 'alert("Đăng nhập không thành công")';
        echo '</script>';
      }
    }
    $data['header']       = 'header_3';
    $data['class_header'] = 'login';
    $data['title']        = 'Đăng nhập';
    $data['content']      = 'user/login_3';
    $data['footer']       = 'footer_3';
    $this->load->view('master_3', $data);
  }
  public function register()
  {
    if ($this->input->post('email')) {
      $is_unique_email = '|is_unique[users.email]';
    } else {
      $is_unique_email = '';
    }
    if ($this->input->post('username')) {
      $is_unique_username = '|is_unique[users.user_name]';
    } else {
      $is_unique_username = '';
    }
    $this->form_validation->set_rules('username', 'Tên đăng nhập', 'required|callback_checkKytu' . $is_unique_username,
      [
        'required'  => 'Vui lòng nhập tên đăng nhập.',
        'is_unique' => 'Tên đăng nhập đã sử dụng.',
      ]);
    $this->form_validation->set_rules('email', 'Email', 'required|valid_email' . $is_unique_email,
      [
        'required'    => 'Vui lòng nhập email.',
        'is_unique'   => 'Email đã sử dụng.',
        'valid_email' => 'Vui lòng nhập đúng định dạng email.',
      ]);
    $this->form_validation->set_rules('name', 'Họ và tên', 'required|min_length[6]',
      [
        'required'   => 'Vui lòng nhập họ và tên.',
        'min_length' => 'Tên bạn quá ngắn vui lòng điền đủ họ và tên.',
      ]);
    $this->form_validation->set_rules('password', 'Mật khẩu', 'required|min_length[6]',
      [
        'required'   => 'Vui lòng nhập mật khẩu.',
        'min_length' => 'Mật khẩu của bạn phải gồm ít nhất 6 ký tự.',
      ]);
    $this->form_validation->set_rules('re_password', 'Nhập lại mật khẩu', 'required|matches[password]',
      [
        'required' => 'Vui lòng nhập lại mật khẩu',
        'matches'  => 'Nhập lại mật khẩu chưa đúng',
      ]);
    if ($this->form_validation->run()) {
      $username  = $this->input->post('username');
      $name      = $this->input->post('name');
      $email     = $this->input->post('email');
      $pass      = $this->input->post('password');
      $pass_hash = md5($pass);
      $type      = $this->input->post('type') ? $this->input->post('type') : 1;
      $token     = time() . '_' . md5(time());
      $register  = [
        'name'         => $name,
        'user_name'    => $username,
        'password'     => $pass_hash,
        'email'        => $email,
        'type'         => $type,
        'token'        => $token,
        'created_date' => time(),
        'updated_date' => time(),
      ];
      $create = $this->db->insert("users", $register);
      if ($create) {
        $sender       = 'mail@job247.vn';
        $senderName   = 'Web tìm việc job247.vn';
        $usernameSmtp = 'AKIAX5B4PFVWPIOLOAPL';
        $passwordSmtp = 'BNVhvpwZzGi/Tmq27gXv64+Bdtrws/GsO9i367Dv4TwX';
        $host         = 'email-smtp.eu-west-1.amazonaws.com';
        $port         = 587;
        $mail         = new PHPMailer(true);

        try {
          $mail->isSMTP();
          $mail->setFrom($sender, $senderName);
          $mail->Username   = $usernameSmtp;
          $mail->Password   = $passwordSmtp;
          $mail->Host       = $host;
          $mail->Port       = $port;
          $mail->SMTPAuth   = true;
          $mail->SMTPSecure = 'tls';
          $mail->isHTML(true);

          $this->db->where('user_name', $username);
          $query_l   = $this->db->get('users');
          $row_array = $query_l->row_array();
          $this->session->set_userdata('loginted', $row_array);
          $email_mes['token']    = $token;
          $email_mes['name']     = $name;
          $email_mes['email']    = $email;
          $email_mes['username'] = $username;

          $mail->addAddress($email, 'Web tìm việc job247.vn');
          $mail->Subject = 'Kích hoạt tài khoản đăng ký';
          $mail->Body    = $this->load->view('user/mail/mail_check', $email_mes, true);
          $mail->CharSet = 'UTF-8';

          if (!$mail->Send()) {
            $this->session->set_userdata('notification', 'Đăng ký tài khoản thất bại!');
            header('Location: ' . base_url() . 'User/error');
          } else {
            $this->session->set_userdata('notification', 'Đăng ký tài khoản thành công, vui lòng check mail để kích hoạt tài khoản!');
            header('Location: ' . base_url() . 'User/success');
          }
        } catch (Exception $e) {
          $this->session->set_userdata('notification', 'Lỗi gửi mail hệ thống, vui lòng thử lại sau!');
          header('Location: ' . base_url() . 'User/error');
        }
      }
    }
    $data['header']       = 'header_3';
    $data['title']        = 'Đăng ký';
    $data['class_header'] = 'register';
    $data['content']      = 'user/register_3';
    $data['footer']       = 'footer_3';
    $this->load->view('master_3', $data);
  }
  public function logout()
  {
    if ($this->session->userdata('loginted')) {
      delete_cookie('siteAuth');
      $this->session->unset_userdata('loginted');
      header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
  }
  public function forgot_password()
  {
    if ($this->session->userdata('loginted')) {
      $this->session->unset_userdata('loginted');
    }
    $data['title'] = 'Lấy lại mật khẩu';

    $this->form_validation->set_rules('email', 'email', 'required|callback_check_forgot_pass');

    $this->form_validation->set_message('required', 'Vui lòng nhập đỉa chỉ {field}!');

    $email = $this->input->post('email');

    if ($this->form_validation->run()) {
      $this->db->where('email', $email);
      $query = $this->db->get('users');
      $row   = $query->row();

      $token  = time() . '_' . md5(time());
      $update = $this->db->update("users", ['token' => $token], ['email' => $email]);
      if ($update) {
        $email_mes['token'] = $token;
        $email_mes['name']  = $row->name;

        $sender       = 'mail@job247.vn';
        $senderName   = 'Web tìm việc job247.vn';
        $usernameSmtp = 'AKIAX5B4PFVWPIOLOAPL';
        $passwordSmtp = 'BNVhvpwZzGi/Tmq27gXv64+Bdtrws/GsO9i367Dv4TwX';
        $host         = 'email-smtp.eu-west-1.amazonaws.com';
        $port         = 587;
        $mail         = new PHPMailer(true);

        try {
          $mail->isSMTP();
          $mail->setFrom($sender, $senderName);
          $mail->Username   = $usernameSmtp;
          $mail->Password   = $passwordSmtp;
          $mail->Host       = $host;
          $mail->Port       = $port;
          $mail->SMTPAuth   = true;
          $mail->SMTPSecure = 'tls';
          $mail->isHTML(true);

          $this->db->where('user_name', $username);
          $query_l   = $this->db->get('users');
          $row_array = $query_l->row_array();
          $this->session->set_userdata('loginted', $row_array);
          $email_mes['token']    = $token;
          $email_mes['name']     = $name;
          $email_mes['email']    = $email;
          $email_mes['username'] = $username;

          $mail->addAddress($email, 'Web tìm việc job247.vn');
          $mail->Subject = 'Lấy lại mật khẩu !';
          $mail->Body    = $this->load->view('user/mail/mail_forgot', $email_mes, true);
          $mail->CharSet = 'UTF-8';

          if (!$mail->Send()) {
            $this->session->set_userdata('notification', 'Gửi link thay đổi mật khẩu tới tài khoản thất bại!');
            header('Location: ' . base_url() . 'User/error');
          } else {
            $this->session->set_userdata('notification', 'Gửi link thay đổi mật khẩu tới tài khoản thành công, bạn vui lòng check mail !');
            header('Location: ' . base_url() . 'User/success');
          }
        } catch (Exception $e) {
          $this->session->set_userdata('notification', 'Lỗi gửi mail hệ thống, vui lòng thử lại sau!');
          header('Location: ' . base_url() . 'User/error');
        }
      }
    }
    $this->load->view('user/forgot_password', $data);
  }
  public function getForgotPassword($token)
  {
    if ($this->session->userdata('loginted')) {
      $this->session->unset_userdata('loginted');
    }
    $this->db->where('token', $token);
    $query = $this->db->get('users');
    if ($query->num_rows() > 0) {
    } else {
      $this->session->set_userdata('notification', 'Đường link của thay đổi mật khẩu của bạn không tồn tại !');
      header('Location: ' . base_url() . 'User/error');
    }

    $data['header']       = 'header';
    $data['class_header'] = 'white';

    $data['token']   = $token;
    $data['title']   = 'Thay đổi mật khẩu';
    $data['content'] = 'user/postForgotPassword';
    $this->load->view('master', $data);
  }
  public function postForgotPassword()
  {
    if ($this->session->userdata('loginted')) {
      $this->session->unset_userdata('loginted');
    }
    $json    = [];
    $token   = $this->input->post('token');
    $explode = explode('_', $token);
    $check   = time() - $explode[0];
    if ($check < 50000) {
      $this->form_validation->set_rules('password', 'Mật khẩu', 'required|min_length[6]',
        [
          'required'   => 'Vui lòng nhập mật khẩu.',
          'min_length' => 'Mật khẩu của bạn phải gồm ít nhất 6 ký tự.',
        ]);
      $this->form_validation->set_rules('re_password', 'Nhập lại mật khẩu', 'required|matches[password]',
        [
          'required' => 'Vui lòng nhập lại mật khẩu',
          'matches'  => 'Nhập lại mật khẩu chưa đúng',
        ]);
      if (!$this->form_validation->run()) {
        $json = [
          'password'    => form_error('password', '<p class="mt-3 text-danger">', '</p>'),
          're_password' => form_error('re_password', '<p class="mt-3 text-danger">', '</p>'),
        ];
      }

      if ($json == null) {
        $password     = $this->input->post('password');
        $password_md5 = md5($password);

        $this->db->where('token', $token);
        $update = $this->db->update("users", ['password' => $password_md5, 'token' => time()]);

        if ($update) {
          echo json_encode(['boot' => 'true', 'mes' => 'Thay đổi mật khẩu thành công !', true], JSON_UNESCAPED_UNICODE);
        } else {
          echo json_encode(['boot' => 'false', 'mes' => 'Thay đổi mật khẩu thất bại !', false], JSON_UNESCAPED_UNICODE);
        }
      } else {
        $this->output
          ->set_content_type('application/json')
          ->set_output(json_encode(['boot' => 'json_true', 'mes' => 'Thay đổi mật khẩu thành công !', 'json' => $json, false]));
      }
    } else {
      echo json_encode(['boot' => 'notification', 'mes' => 'Đường link của thay đổi mật khẩu của bạn đã hết hạn, vui lòng thử lại sau !', false], JSON_UNESCAPED_UNICODE);
    }
  }
  public function acc_verification($username, $token)
  {

    $this->db->where('token', $token);
    $query = $this->db->get('users');
    if ($query->num_rows() > 0) {
      $this->db->where('token', $token);
      $update = $this->db->update('users', ['active' => 1]);
      if ($update) {
        $this->db->where('user_name', $username);
        $query     = $this->db->get('users');
        $row_array = $query->row_array();
        $this->session->set_userdata('loginted', $row_array);
        $this->session->set_userdata('notification', 'Xác thực tài khoản thành công!');
        header('Location: ' . base_url() . 'User/success');
      } else {
        $this->session->set_userdata('notification', 'Xác thực tài khoản thất bại, mã gửi đến tài khaonr của bạn đã hết hạn!');
        header('Location: ' . base_url() . 'User/error');
      }
    }
  }
  public function info_account() // Thông tin tài khoản

  {
    $this->checkstatus();
    $this->checktype();
    $this->checkIsProfileUser();
    $id = $this->session->userdata('loginted')['id'];

    switch ($this->checktype()) {
      case '1':
        global $salary;
        global $sex;
        global $learn_rank;
        global $marriage;
        global $my_exp;
        global $level;
        global $language;
        global $job_form;
        global $type_work;
        global $degree;
        global $user_news;
        $data['province']     = $this->db->get('province');
        $data['district']     = $this->db->get('district');
        $data['sex']          = $sex;
        $data['learn_rank']   = $learn_rank;
        $data['marriage']     = $marriage;
        $data['my_exp']       = $my_exp;
        $data['salary']       = $salary;
        $data['level']        = $level;
        $data['language']     = $language;
        $data['job_form']     = $job_form;
        $data['type_work']    = $type_work;
        $data['degree']       = $degree;
        $joinUserProfile      = $this->user_model->getJoinUserProfile('users', 'user_profiles', 'id', 'user_id', $id);
        $data['header']       = 'header';
        $data['class_header'] = 'stick-top';
        $data['content']      = 'user/info_account';
        $data['data']         = $joinUserProfile;
        $data['title']        = 'Thông tin tin tài khoản ứng viên';
        $this->load->view('master', $data);
        break;
      case '2':
        $data['company_size'] = $this->db->get('company_size');
        $joinUserProfile      = $this->user_model->getJoinUserProfile('users', 'company_profiles', 'id', 'company_id', $id);
        $data['data']         = $joinUserProfile;
        $data['header']       = 'header';
        $data['class_header'] = 'stick-top';
        $data['title']        = 'Thông tin tin tài khoản người tuyển dụng';
        $data['content']      = 'user/info_account_company';
        $this->load->view('master', $data);
        break;
      default:
        header('Location: ' . base_url());
        break;
    }
  }
  public function update_profile_user()
  {

    $this->checkstatus();
    $loginted = $this->session->userdata('loginted');
    $id_user  = $loginted['id'];
    $form     = $this->input->post('form');
    $json     = [];
    $this->form_validation->set_message('required', 'Bạn vui lòng nhập vào trường {field}!');
    switch ($form) {
      case 'form_info':
        $this->form_validation->set_rules('address', 'đến từ', 'required');
        $this->form_validation->set_rules('birthday', 'ngày sinh', 'required');
        $this->form_validation->set_rules('sex', 'sex', 'required');
        $this->form_validation->set_rules('marriage', 'marriage', 'required');
        $this->form_validation->set_rules('skills', 'kỹ năng bản thân', 'required');
        $this->form_validation->set_rules('title', 'vị trí mong muốn', 'required');
        if (!$this->form_validation->run()) {
          $json = [
            'address'  => form_error('address', '<p class="text-danger">', '</p>'),
            'birthday' => form_error('birthday', '<p class="text-danger">', '</p>'),
            'sex'      => form_error('sex', '<p class="text-danger">', '</p>'),
            'marriage' => form_error('marriage', '<p class="text-danger">', '</p>'),
            'skills'   => form_error('skills', '<p class="text-danger">', '</p>'),
            'title'    => form_error('title', '<p class="text-danger">', '</p>'),
          ];
        }
        $update_user = [
          'address'  => $this->input->post('address'),
          'birthday' => $this->input->post('birthday'),
          'sex'      => $this->input->post('sex'),
          'marriage' => $this->input->post('marriage'),
          'skills'   => $this->input->post('skills'),

        ];
        if ($json == null) {
          $update_user_profiles = $this->db->update('user_profiles', $update_user, ['user_id' => $id_user]);
          if ($update_user_profiles) {
            echo json_encode(['mes' => 'Cập nhật thông tin thành công !', 'boot' => 'true', true], JSON_UNESCAPED_UNICODE);
          } else {
            echo json_encode(['mes' => 'Cập nhật thông tin thất bại, vui lòng thử lại sau !', 'boot' => 'false', false], JSON_UNESCAPED_UNICODE);
          }
        } else {
          $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['json' => $json, 'boot' => 'undfile', 'mes' => 'Vui lòng điền đầy đủ thông tin!']));
        }
        break;
      case 'form_job_wish':
        $this->form_validation->set_rules('province_id', 'địa điểm', 'required');
        $this->form_validation->set_rules('district_id', 'quận huyện', 'required');
        $this->form_validation->set_rules('level', 'cấp bậc', 'required');
        $this->form_validation->set_rules('degree', 'bằng cấp', 'required');
        $this->form_validation->set_rules('job_form', 'hình thức làm việc', 'required');
        $this->form_validation->set_rules('job', 'vị trí mong muốn', 'required');
        $this->form_validation->set_rules('job_other', 'vị trí khác', 'required');
        $this->form_validation->set_rules('my_exp', 'kinh nghiệm bản thân', 'required');
        $this->form_validation->set_rules('salary', 'mức lương', 'required');
        $this->form_validation->set_rules('career', 'mục tiêu nghề nghiêp', 'required');
        $this->form_validation->set_rules('title', 'vị trí mong muốn', 'required');
        if (!$this->form_validation->run()) {
          $json = [
            'province_id' => form_error('province_id', '<p class="text-danger">', '</p>'),
            'district_id' => form_error('district_id', '<p class="text-danger">', '</p>'),
            'level'       => form_error('level', '<p class="text-danger">', '</p>'),
            'degree'      => form_error('degree', '<p class="text-danger">', '</p>'),
            'job_form'    => form_error('job_form', '<p class="text-danger">', '</p>'),
            'job'         => form_error('job', '<p class="text-danger">', '</p>'),
            'job_other'   => form_error('job_other', '<p class="text-danger">', '</p>'),
            'my_exp'      => form_error('my_exp', '<p class="text-danger">', '</p>'),
            'salary'      => form_error('salary', '<p class="text-danger">', '</p>'),
            'career'      => form_error('career', '<p class="text-danger">', '</p>'),
            'title'       => form_error('title', '<p class="text-danger">', '</p>'),
          ];
        }
        $update_user = [
          'province_id'  => $this->input->post('province_id'),
          'district_id'  => $this->input->post('district_id'),
          'level'        => $this->input->post('level'),
          'degree'       => $this->input->post('degree'),
          'job_form'     => $this->input->post('job_form'),
          'job'          => $this->input->post('job'),
          'job_other'    => $this->input->post('job_other'),
          'my_exp'       => $this->input->post('my_exp'),
          'salary'       => $this->input->post('salary'),
          'career'       => $this->input->post('career'),
          'job_title'    => $this->input->post('title'),
          'updated_date' => time(),
        ];
        $users = [
          'address_acc' => $this->input->post('province_id'),
          'job_acc'     => $this->db->get_where('type_work', ['id' => $this->input->post('job')])->row_array()['name'],
          'job_acc_id'  => $this->input->post('job'),
        ];
        if ($json == null) {
          $update_users         = $this->db->update('users', $users, ['id' => $id_user]);
          $update_user_profiles = $this->db->update('user_profiles', $update_user, ['user_id' => $id_user]);
          if ($update_users && $update_user_profiles) {
            echo json_encode(['mes' => 'Cập nhật thông tin thành công !', 'boot' => 'true', true], JSON_UNESCAPED_UNICODE);
          } else {
            echo json_encode(['mes' => 'Cập nhật thông tin thất bại, vui lòng thử lại sau !', 'boot' => 'false', false], JSON_UNESCAPED_UNICODE);
          }
        } else {
          $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['json' => $json, 'boot' => 'undfile', 'mes' => 'Vui lòng điền đầy đủ thông tin!']));
        }
        break;
      case 'form_info_more':
        $this->form_validation->set_rules('learn_rank', 'xếp loại học tập', 'required');
        $this->form_validation->set_rules('name_school', 'tên trường học', 'required');
        $this->form_validation->set_rules('language', 'ngôn ngữ', 'required');
        $this->form_validation->set_rules('other_workplace', 'nơi làm việc khác', 'required');
        $this->form_validation->set_rules('majors', 'chuyên ngành', 'required');
        $this->form_validation->set_rules('general', 'giới thiệu chung', 'required');
        $this->form_validation->set_rules('description_exp', 'mô tả kinh nghiệm', 'required');
        if (!$this->form_validation->run()) {
          $json = [
            'learn_rank'      => form_error('learn_rank', '<p class="text-danger">', '</p>'),
            'name_school'     => form_error('name_school', '<p class="text-danger">', '</p>'),
            'language'        => form_error('language', '<p class="text-danger">', '</p>'),
            'other_workplace' => form_error('other_workplace', '<p class="text-danger">', '</p>'),
            'majors'          => form_error('majors', '<p class="text-danger">', '</p>'),
            'general'         => form_error('general', '<p class="text-danger">', '</p>'),
            'description_exp' => form_error('description_exp', '<p class="text-danger">', '</p>'),
          ];
        }
        $update_user = [
          'learn_rank'      => $this->input->post('learn_rank'),
          'name_school'     => $this->input->post('name_school'),
          'language'        => $this->input->post('language'),
          'other_workplace' => $this->input->post('other_workplace'),
          'majors'          => $this->input->post('majors'),
          'general'         => $this->input->post('general'),
          'description_exp' => $this->input->post('description_exp'),
          'updated_date'    => time(),
        ];
        if ($json == null) {
          $update_user_profiles = $this->db->update('user_profiles', $update_user, ['user_id' => $id_user]);
          if ($update_user_profiles) {
            echo json_encode(['mes' => 'Cập nhật thông tin thành công !', 'boot' => 'true', true], JSON_UNESCAPED_UNICODE);
          } else {
            echo json_encode(['mes' => 'Cập nhật thông tin thất bại, vui lòng thử lại sau !', 'boot' => 'false', false], JSON_UNESCAPED_UNICODE);
          }
        } else {
          $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['json' => $json, 'boot' => 'undfile', 'mes' => 'Vui lòng điền đầy đủ thông tin!']));
        }
        break;
      case 'update_phone':
        $phone = $this->input->post('phone');

        if ($this->db->get_where('users', ['id' => $id_user, 'phone' => $phone])->num_rows() == 0) {
          $is_unique_phone = '|is_unique[users.phone]';
        } else {
          $is_unique_phone = '';
        }

        $this->form_validation->set_rules('phone', 'số điện thoại', 'required' . $is_unique_phone);
        if (!$this->form_validation->run()) {
          $json = [
            'phone' => form_error('phone', '<p class="text-danger">', '</p>'),
          ];
        }
        $update_user = [
          'phone'        => $this->input->post('phone'),
          'updated_date' => time(),
        ];
        if ($json == null) {
          $update_user_profiles = $this->db->update('users', $update_user, ['id' => $id_user]);
          if ($update_user_profiles) {
            echo json_encode(['mes' => 'Cập nhật số điện thoại thành công !', 'boot' => 'true', true], JSON_UNESCAPED_UNICODE);
          } else {
            echo json_encode(['mes' => 'Cập nhật số điện thoại thất bại, vui lòng thử lại sau !', 'boot' => 'false', false], JSON_UNESCAPED_UNICODE);
          }
        } else {
          $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['json' => $json, 'boot' => 'undfile', 'mes' => 'Vui lòng điền số điện thoại hợp lệ!']));
        }
        break;
    }
  }
  public function upload_background_info()
  {
    if (!empty($_FILES['background']['name'])) {
      $config['upload_path']   = 'upload';
      $config['allowed_types'] = 'jpg|jpeg|png|gif';
      $config['file_name']     = time() . '-' . $_FILES['background']['name'];

      $this->load->library('upload', $config);
      $this->upload->initialize($config);

      if ($this->upload->do_upload('background')) {
        $uploadData = $this->upload->data();
        $background = $uploadData['file_name'];
      } else {
        $background = '';
      }
    } else {
      $background = '';
    }
    $link       = '/upload/' . $background;
    $id_user    = $this->session->userdata('loginted')['id'];
    $update_img = $this->db->where('id', $id_user)->update("users", ['background' => $background]);
    if ($update_img) {
      echo json_encode(['link' => $link, 'boot' => 'true', true], JSON_UNESCAPED_UNICODE);
    } else {
      echo json_encode(['boot' => 'false', false], JSON_UNESCAPED_UNICODE);
    }
  }
  public function upload_avatar_info()
  {
    if (!empty($_FILES['avatar']['name'])) {
      $config['upload_path']   = 'upload';
      $config['allowed_types'] = 'jpg|jpeg|png|gif';
      $config['file_name']     = time() . '-' . $_FILES['avatar']['name'];

      $this->load->library('upload', $config);
      $this->upload->initialize($config);

      if ($this->upload->do_upload('avatar')) {
        $uploadData = $this->upload->data();
        $avatar     = $uploadData['file_name'];
      } else {
        $avatar = '';
      }
    } else {
      $avatar = '';
    }
    $link       = '/upload/' . $avatar;
    $id_user    = $this->session->userdata('loginted')['id'];
    $update_img = $this->db->where('id', $id_user)->update("users", ['avatar' => $avatar]);
    if ($update_img) {
      echo json_encode(['link' => $link, 'boot' => 'true', true], JSON_UNESCAPED_UNICODE);
    } else {
      echo json_encode(['boot' => 'false', false], JSON_UNESCAPED_UNICODE);
    }
  }
  public function update_profile_company()
  {
    $this->checkstatus();
    $loginted    = $this->session->userdata('loginted');
    $id_user     = $loginted['id'];
    $json        = [];
    $update_user = [
      'name_company'  => $this->input->post('name_company'),
      'address'       => $this->input->post('address'),
      'website'       => $this->input->post('website'),
      'phone'         => $this->input->post('phone'),
      'email_company' => $this->input->post('email_company'),
      'company_size'  => $this->input->post('company_size'),
      'description'   => $this->input->post('description'),
      'updated_date'  => time(),
    ];
    $update_user_profiles = $this->db->update('company_profiles', $update_user, ['company_id ' => $id_user]);
    if ($update_user_profiles) {
      $query_new_session = $this->db->get_where('users', ['id' => $id_user]);
      $new_session       = $query_new_session->row_array();
      echo json_encode(['mes' => 'Cập nhật thông tin thành công !', 'boot' => 'true', true], JSON_UNESCAPED_UNICODE);
    } else {
      echo json_encode(['mes' => 'Cập nhật thông tin thất bại, vui lòng thử lại sau !', 'boot' => 'false', false], JSON_UNESCAPED_UNICODE);
    }
  }
  public function change_password()
  {
    $data['header']       = 'header_3';
    $data['class_header'] = 'changepassword';
    $data['title']        = 'Thay đổi mật khẩu';
    $data['content']      = 'user/change_password_3';
    $data['footer']       = 'footer_3';
    $this->load->view('master_3', $data);
  }
  public function manager_recruitment()
  {
    $data['class_header'] = 'stick-top';
    $data['slogan']       = 'Quản lý của nhà tuyển dụng';
    $data['description']  = 'Phạm Thanh Hà';
    $data['header']       = 'header_3';
    $data['class_header'] = 'managerrecruitmen';
    $data['footer']       = 'footer_3';
    $data['title']        = 'Quản lý tin đăng';
    $data['content']      = 'user/manager_recruitment_3';
    $this->load->view('master_3', $data);
  }
  public function add_employer()
  {

    $data['header']       = 'header_3';
    $data['class_header'] = 'addemployer';
    $data['title']        = 'Đăng tin tuyển dụng';
    $data['footer']       = 'footer_3';
    $data['content']      = 'user/add_employer_3';
    $this->load->view('master_3', $data);
  }
  public function post_add_new()
  {
    $this->checkstatus();
    $login_user = $this->session->userdata('loginted');

    $json = [];

    $data['cate_post'] = $this->db->get('cate_post');
    $this->form_validation->set_rules('title', 'title', 'required');
    $this->form_validation->set_rules('career', 'career', 'required');
    $this->form_validation->set_rules('province_id', 'province_id', 'required');
    $this->form_validation->set_rules('degree', 'degree', 'required');
    $this->form_validation->set_rules('salary', 'salary', 'required');
    $this->form_validation->set_rules('exp', 'exp', 'required');
    $this->form_validation->set_rules('sex', 'sex', 'required');
    $this->form_validation->set_rules('level', 'level', 'required');
    $this->form_validation->set_rules('workingform', 'workingform', 'required');
    // $this->form_validation->set_rules('expdate', 'expdate', 'required');
    // $this->form_validation->set_rules('description', 'description', 'required');
    // $this->form_validation->set_rules('req', 'req', 'required');
    // $this->form_validation->set_rules('des_interest', 'des_interest', 'required');
    // $this->form_validation->set_rules('date_start', 'date_start', 'required');
    // $this->form_validation->set_rules('date_end', 'date_end', 'required');
    $this->form_validation->set_message('required', 'Bạn vui lòng nhập vào trường {field}!');
    if (!$this->form_validation->run()) {
      $json = [
        'title'       => form_error('title', '<p class=" text-danger">', '</p>'),
        'career'      => form_error('career', '<p class=" text-danger">', '</p>'),
        'province_id' => form_error('province_id', '<p class=" text-danger">', '</p>'),
        'degree'      => form_error('degree', '<p class=" text-danger">', '</p>'),
        'salary'      => form_error('salary', '<p class=" text-danger">', '</p>'),
        'exp'         => form_error('exp', '<p class=" text-danger">', '</p>'),
        'sex'         => form_error('sex', '<p class=" text-danger">', '</p>'),
        'level'       => form_error('level', '<p class=" text-danger">', '</p>'),
        'workingform' => form_error('workingform', '<p class=" text-danger">', '</p>'),
        // 'expdate'      => form_error('expdate', '<p class=" text-danger">', '</p>'),
        // 'description'  => form_error('description', '<p class=" text-danger">', '</p>'),
        // 'req'          => form_error('req', '<p class=" text-danger">', '</p>'),
        // 'des_interest' => form_error('des_interest', '<p class=" text-danger">', '</p>'),
        // 'date_start'   => form_error('date_start', '<p class=" text-danger">', '</p>'),
        // 'date_end'     => form_error('date_end', '<p class=" text-danger">', '</p>'),
      ];
      $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['boot' => 'notification', 'json' => $json, 'mes' => 'Vui lòng nhập đầy đủ các trường !']));
    }

    if ($json == null) {
      $user_new = [
        'user_id'      => $login_user['id'],
        'title'        => $this->input->post('title'),
        'career'       => $this->input->post('career'),
        'province_id'  => $this->input->post('province_id'),
        'degree'       => $this->input->post('degree'),
        'salary'       => $this->input->post('salary'),
        'exp'          => $this->input->post('exp'),
        'sex'          => $this->input->post('sex'),
        'level'        => $this->input->post('level'),
        'workingform'  => $this->input->post('workingform'),
        'expdate'      => strtotime($this->input->post('expdate')),
        'description'  => $this->input->post('description'),
        'reqprofile'   => $this->input->post('reqprofile'),
        'req'          => $this->input->post('req'),
        'des_interest' => $this->input->post('des_interest'),
        'date_start'   => $this->input->post('date_start'),
        'date_end'     => $this->input->post('date_end'),
        'created_date' => time(),
      ];
      $insert_user_new = $this->db->insert('user_news', $user_new);
      if ($insert_user_new) {
        echo json_encode(['mes' => 'Đăng tin tuyển dụng thành công !', 'boot' => 'true', true], JSON_UNESCAPED_UNICODE);
      } else {
        echo json_encode(['mes' => 'Đăng tin tuyển dụng thất bại !', 'boot' => 'false', false], JSON_UNESCAPED_UNICODE);
      }
    }
  }
  public function edit_employer($id)
  {
    $data['data']         = $this->db->get_where('user_news', ['id' => $id])->row_array();
    $data['header']       = 'header';
    $data['class_header'] = 'stick-top';
    $data['title']        = 'Sửa tin tuyển dụng';
    $data['content']      = 'user/edit_employer';

    $data['province']  = $this->db->get('province')->result_array();
    $data['type_work'] = $this->user_model->getSelectOrder('type_work', 'name', 'asc');
    $data['sex']       = $this->user_model->getSelectOrder('sex', 'name', 'asc');
    $data['my_exp']    = $this->user_model->getSelectOrder('my_exp', 'name', 'asc');
    $data['salary']    = $this->user_model->getSelectOrder('salary', 'name', 'asc');
    $data['level']     = $this->user_model->getSelectOrder('level', 'name', 'asc');
    $data['job_form']  = $this->user_model->getSelectOrder('job_form', 'name', 'asc');
    $data['degree']    = $this->user_model->getSelectOrder('degree', 'name', 'asc');

    $this->load->view('master', $data);
  }
  public function post_edit_new()
  {
    $this->checklogin();
    $this->checkstatus();

    $json = [];

    $data['cate_post'] = $this->db->get('cate_post');
    $this->form_validation->set_rules('title', 'title', 'required');
    $this->form_validation->set_rules('career', 'career', 'required');
    $this->form_validation->set_rules('province_id', 'province_id', 'required');
    $this->form_validation->set_rules('degree', 'degree', 'required');
    $this->form_validation->set_rules('salary', 'salary', 'required');
    $this->form_validation->set_rules('exp', 'exp', 'required');
    $this->form_validation->set_rules('level', 'level', 'required');
    $this->form_validation->set_rules('workingform', 'workingform', 'required');
    $this->form_validation->set_rules('expdate', 'expdate', 'required');
    // $this->form_validation->set_rules('description', 'description', 'required');
    // $this->form_validation->set_rules('reqprofile', 'reqprofile', 'required');
    // $this->form_validation->set_rules('req', 'req', 'required');
    // $this->form_validation->set_rules('des_interest', 'des_interest', 'required');
    // $this->form_validation->set_rules('date_start', 'date_start', 'required');
    // $this->form_validation->set_rules('date_end', 'date_end', 'required');
    $this->form_validation->set_message('required', 'Bạn vui lòng nhập vào trường {field}!');
    if (!$this->form_validation->run()) {
      $json = [
        'title'       => form_error('title', '<p class="mt-3 text-danger">', '</p>'),
        'career'      => form_error('career', '<p class="mt-3 text-danger">', '</p>'),
        'province_id' => form_error('province_id', '<p class="mt-3 text-danger">', '</p>'),
        'degree'      => form_error('degree', '<p class="mt-3 text-danger">', '</p>'),
        'salary'      => form_error('salary', '<p class="mt-3 text-danger">', '</p>'),
        'exp'         => form_error('exp', '<p class="mt-3 text-danger">', '</p>'),
        'level'       => form_error('level', '<p class=" text-danger">', '</p>'),
        'workingform' => form_error('workingform', '<p class="mt-3 text-danger">', '</p>'),
        'expdate'     => form_error('expdate', '<p class="mt-3 text-danger">', '</p>'),
        // 'description'  => form_error('description', '<p class="mt-3 text-danger">', '</p>'),
        // 'reqprofile'   => form_error('reqprofile', '<p class="mt-3 text-danger">', '</p>'),
        // 'req'          => form_error('req', '<p class="mt-3 text-danger">', '</p>'),
        // 'des_interest' => form_error('des_interest', '<p class="mt-3 text-danger">', '</p>'),
        // 'date_start'   => form_error('date_start', '<p class="mt-3 text-danger">', '</p>'),
        // 'date_end'     => form_error('date_end', '<p class="mt-3 text-danger">', '</p>'),
      ];
      $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['boot' => 'notification', 'json' => $json]));
    }
    if ($json == null) {
      $id_user_new = $this->input->post('id_user_new');
      $user_new    = [
        'title'        => $this->input->post('title'),
        'career'       => $this->input->post('career'),
        'province_id'  => $this->input->post('province_id'),
        'degree'       => $this->input->post('degree'),
        'salary'       => $this->input->post('salary'),
        'exp'          => $this->input->post('exp'),
        'sex'          => $this->input->post('sex'),
        'level'        => $this->input->post('level'),
        'workingform'  => $this->input->post('workingform'),
        'expdate'      => strtotime($this->input->post('expdate')),
        'description'  => $this->input->post('description'),
        'reqprofile'   => $this->input->post('reqprofile'),
        'req'          => $this->input->post('req'),
        'des_interest' => $this->input->post('des_interest'),
        'date_start'   => $this->input->post('date_start'),
        'date_end'     => $this->input->post('date_end'),
        'updated_date' => time(),

      ];
      $update_user_new = $this->db->update('user_news', $user_new, ['id' => $id_user_new]);
      if ($update_user_new) {
        echo json_encode(['mes' => 'Sửa tin tuyển dụng thành công !', 'boot' => 'true', true], JSON_UNESCAPED_UNICODE);
      } else {
        echo json_encode(['mes' => 'Sửa tin tuyển dụng thất bại !', 'boot' => 'false', false], JSON_UNESCAPED_UNICODE);
      }
    }
  }
  public function update_user_new()
  {
    $id     = $this->input->post('id');
    $active = $this->input->post('status');
    $this->db->where('id', $id);
    $update = $this->db->update("user_news", ['active' => $active]);
    if ($update) {
      echo json_encode(true, JSON_UNESCAPED_UNICODE);
    } else {
      echo json_encode(false, JSON_UNESCAPED_UNICODE);
    }
  }
  public function dell_user_new()
  {
    $id     = $this->input->post('id');
    $table  = $this->input->post('table');
    $delete = $this->db->delete($table, ['id' => $id]);
    if ($delete) {
      echo json_encode(true, JSON_UNESCAPED_UNICODE);
    } else {
      echo json_encode(false, JSON_UNESCAPED_UNICODE);
    }
  }
  public function job_list()
  {
    global $type_work;
    global $degree;
    global $level;
    global $my_exp;
    global $job_form;
    global $salary;
    $data['slogan']       = 'TÌM KIẾM VIỆC LÀM';
    $data['description']  = 'Tìm kiếm việc làm phù hợp nhất với bạn';
    $data['type_work']    = $type_work;
    $data['degree']       = $degree;
    $data['level']        = $level;
    $data['my_exp']       = $my_exp;
    $data['job_form']     = $job_form;
    $data['salary']       = $salary;
    $sql                  = $this->db->get_where('tbl_meta', ['id' => 2])->row_array();
    $data['meta_title']   = $sql['title'];
    $data['meta_key']     = $sql['metakeywork'];
    $data['meta_des']     = $sql['metadesc'];
    $data['data']         = [];
    $data['class_header'] = 'joblist';
    $data['title']        = 'Danh sách công việc';
    $data['header']       = 'header_3';
    $data['content']      = 'user/job_list_3';
    $data['footer']       = 'footer_3';
    $this->load->view('master_3', $data);
  }
  public function postJobList($page123 = "")
  {
    $address  = $this->input->post('address') ? $this->input->post('address') : '';
    $name     = $this->input->post('name') ? $this->input->post('name') : '';
    $career   = $this->input->post('career') ? $this->input->post('career') : '';
    $degree   = $this->input->post('degree') ? $this->input->post('degree') : '';
    $my_exp   = $this->input->post('my_exp') ? $this->input->post('my_exp') : '';
    $salary   = $this->input->post('salary') ? $this->input->post('salary') : '';
    $job_form = $this->input->post('job_form') ? $this->input->post('job_form') : '';
    $level    = $this->input->post('level') ? $this->input->post('level') : '';

    $page    = $page123;
    $perpage = 10;
    $page    = ($page123) ? ($page123 - 1) : 0;
    $start   = $page * $perpage;

    $query                      = $this->user_model->postSearchProJob($perpage, $start, $name, $address, $career, $degree, $my_exp, $salary, $job_form, $level);
    $config                     = [];
    $config['base_url']         = '#';
    $config['total_rows']       = $query['total'];
    $config['per_page']         = $perpage;
    $config['uri_segment']      = 3;
    $config['use_page_numbers'] = true;
    $config['full_tag_open']    = '<ul class="pagination">';
    $config['full_tag_close']   = '</ul>';
    $config['first_tag_open']   = '<li>';
    $config['first_tag_close']  = '</li>';
    $config['last_tag_open']    = '<li>';
    $config['last_tag_close']   = '</li>';
    $config['next_link']        = '<i class="fal fa-chevron-double-right"></i>';
    $config['next_tag_open']    = '<li>';
    $config['next_tag_close']   = '</li>';
    $config['prev_link']        = '<i class="fal fa-chevron-double-left"></i>';
    $config['prev_tag_open']    = '<li>';
    $config['prev_tag_close']   = '</li>';
    $config['cur_tag_open']     = "<li class='active'><a href='javascript:void(0)'>";
    $config['cur_tag_close']    = '</a></li>';
    $config['num_tag_open']     = '<li>';
    $config['num_tag_close']    = '</li>';
    $config['num_links']        = 3;
    $this->pagination->initialize($config);
    $pagination_link = $this->pagination->create_links();
    $data['data']    = $query['sql'];
    $view            = $this->load->view('search_pagination', $data, true);
    $count           = $query['total'];
    $this->output->set_content_type('application/json');
    $this->output->set_output(json_encode(['name' => $name, 'view' => $view, 'count' => $count, 'pagination_link' => $pagination_link]));
  }
  public function detail_recruitment($any, $id)
  {
    $detail = $this->db->select('u.avatar,un.*, cp.name_company,cp.company_id, cp.description as description_cp,cp.address as address_cp, cp.phone as phone_cp, cp.website as website_cp, cp.email_company')
      ->from('user_news un')
      ->join('users u', 'u.id = un.user_id')
      ->join('company_profiles cp', 'cp.company_id = un.user_id')
      ->where('un.id', $id)
      ->get()->row_array();
    $joblist_2 = $this->db->select('u.avatar,un.id,un.title, cp.name_company')
      ->from('user_news un')
      ->join('users u', 'u.id = un.user_id')
      ->join('company_profiles cp', 'cp.company_id = un.user_id')
      ->where('salary', $detail['salary'])->where('career', $detail['career'])->where('un.active !=', 0)
      ->limit(3, 1)
      ->order_by('un.id desc')
      ->get()->result_array();
    $active   = 0;
    $type     = 0;
    $loginted = $this->session->userdata('loginted');
    if ($this->session->has_userdata('loginted')) {
      $acc_user_news = $this->db->get_where('acc_user_news', ['user_id' => $loginted['id'], 'job_id' => $id]);
      if ($acc_user_news->num_rows() > 0) {
        $active = $acc_user_news->row_array()['active'];
        $type   = $acc_user_news->row_array()['type'];
      }
    }
    $candidate_cv         = $this->db->get_where('candidate_cv', ['uid' => $loginted['id']])->result_array();
    $data['slogan']       = 'TÌM CÔNG VIỆC BẠN YÊU THÍCH';
    $data['description']  = 'Tìm việc làm, việc làm và cơ hội nghề nghiệp.';
    $data['joblist_2']    = $joblist_2;
    $data['detail']       = $detail;
    $data['loginted']     = $loginted;
    $data['candidate_cv'] = $candidate_cv;
    $data['active']       = $active;
    $data['type']         = $type;
    $data['class_header'] = 'detailrecruitment';
    $data['title']        = 'Chi tiết tin tuyển dụng';
    $data['header']       = 'header_3';
    $data['content']      = 'user/detail_recruitment_3';
    $data['footer']       = 'footer_3';
    $this->load->view('master_3', $data);
  }
  public function save_recruitment()
  {
    $user_id    = $this->session->userdata('loginted')['id'];
    $type       = $this->input->post('type');
    $company_id = $this->input->post('company_id');
    $job_id     = $this->input->post('job_id');
    $cv_id      = $this->input->post('cv_id');
    $note       = $this->input->post('note');

    if ($this->session->has_userdata('loginted')) {
      $acc_user_news = $this->db->get_where('acc_user_news', ['user_id' => $user_id, 'job_id' => $job_id]);
      if ($acc_user_news->num_rows() > 0) {
        $update = [
          'user_id'    => $user_id,
          'job_id'     => $job_id,
          'note'       => $note,
          'cv_id'      => $cv_id,
          'company_id' => $company_id,
          'type'       => $type,
        ];
        $id                = $acc_user_news->row_array()['id'];
        $acc_user_news_stt = $this->db->update('acc_user_news', $update, ['id' => $id]);
      } else {
        $insert = [
          'user_id'    => $user_id,
          'job_id'     => $job_id,
          'note'       => $note,
          'cv_id'      => $cv_id,
          'company_id' => $company_id,
          'type'       => 1,
        ];
        $acc_user_news_stt = $this->db->insert('acc_user_news', $insert);
      }
      if ($acc_user_news_stt) {
        echo json_encode(['mes' => 'Ứng tuyển công việc thành công !', 'boot' => 'true', true], JSON_UNESCAPED_UNICODE);
      } else {
        echo json_encode(['mes' => 'Ứng tuyển công việc thất bại !', 'boot' => 'false', false], JSON_UNESCAPED_UNICODE);
      }
    }
  }
  public function save_the_job()
  {
    $user_id    = $this->session->userdata('loginted')['id'];
    $company_id = $this->input->post('company_id');
    $job_id     = $this->input->post('job_id');

    if ($this->session->has_userdata('loginted')) {
      $acc_user_news = $this->db->get_where('acc_user_news', ['user_id' => $user_id, 'job_id' => $job_id]);
      if ($acc_user_news->num_rows() > 0) {
        $update = [
          'user_id'    => $user_id,
          'job_id'     => $job_id,
          'company_id' => $company_id,
          'active'     => $acc_user_news->row_array()['active'] == 0 ? 1 : 0,
        ];

        $acc_user_news_stt = $this->db->update('acc_user_news', $update, ['id' => $acc_user_news->row_array()['id']]);
      } else {
        $update = [
          'user_id'    => $user_id,
          'job_id'     => $job_id,
          'company_id' => $company_id,
          'active'     => 1,
        ];
        $acc_user_news_stt = $this->db->insert('acc_user_news', $update);
      }
      if ($acc_user_news_stt) {
        echo json_encode(['mes' => 'Lưu công việc thành công !', 'boot' => 'true', true], JSON_UNESCAPED_UNICODE);
      } else {
        echo json_encode(['mes' => 'Lưu công việc thất bại !', 'boot' => 'false', false], JSON_UNESCAPED_UNICODE);
      }
    }
  }
// Quản lý tin đăng =========================================================================================

// Nhà tuyển dụng ===========================================================================================
  public function company_list()
  {
    $start   = $this->uri->segment(2);
    $perpage = 4;
    if (is_numeric($start)) {
      $start = $start;
    } else {
      $start = 0;
    }
    $data['slogan']             = 'TÌM KIẾM CÔNG TY';
    $data['description']        = 'Tìm kiếm công ty phù hợp nhất với bạn';
    $sql                        = $this->db->get_where('tbl_meta', ['id' => 4])->row_array();
    $data['meta_title']         = $sql['title'];
    $data['meta_key']           = $sql['metakeywork'];
    $data['meta_des']           = $sql['metadesc'];
    $query                      = $this->user_model->selctListCompany($start, $perpage, $company_new = "");
    $highlight                  = $this->user_model->selctListCompany($start, $perpage, $company_new = "true");
    $config                     = [];
    $config['base_url']         = base_url() . '/danh-sach-nha-tuyen-dung.html';
    $config['total_rows']       = $query['total'];
    $config['per_page']         = $perpage;
    $config['uri_segment']      = 2;
    $config['use_page_numbers'] = true;
    $config['full_tag_open']    = '<ul class="pagination">';
    $config['full_tag_close']   = '</ul>';
    $config['first_tag_open']   = '<li>';
    $config['first_tag_close']  = '</li>';
    $config['last_tag_open']    = '<li>';
    $config['last_tag_close']   = '</li>';
    $config['next_link']        = 'Next <i class="la la-long-arrow-right"></i>';
    $config['next_tag_open']    = '<li>';
    $config['next_tag_close']   = '</li>';
    $config['prev_link']        = '<i class="la la-long-arrow-left"></i> Prev';
    $config['prev_tag_open']    = '<li>';
    $config['prev_tag_close']   = '</li>';
    $config['cur_tag_open']     = "<li class='active'><a href='javascript:void(0)'>";
    $config['cur_tag_close']    = '</a></li>';
    $config['num_tag_open']     = '<li>';
    $config['num_tag_close']    = '</li>';
    $config['num_links']        = 2;
    $this->pagination->initialize($config);
    global $my_exp;
    global $salary;
    global $type_work;
    $data['my_exp']       = $my_exp;
    $data['salary']       = $salary;
    $data['type_work']    = $type_work;
    $data['pagination']   = $this->pagination->create_links();
    $data['province']     = $this->db->get('province', 10, 0)->result_array();
    $data['count1']       = $this->db->get('province')->num_rows();
    $data['header']       = 'header_3';
    $data['data']         = $query['result'];
    $data['highlight']    = $highlight['result'];
    $data['class_header'] = 'companylist';
    $data['title']        = 'Danh sách nhà tuyển dụng';
    $data['content']      = 'user/company_list_3';
    $data['footer']       = 'footer_3';
    $this->load->view('master_3', $data);
  }
  public function detail_employer($any, $user_id)
  {
    $start   = $this->uri->segment(3);
    $perpage = 6;
    if (is_numeric($start)) {
      $start = $start;
    } else {
      $start = 0;
    }
    $data['slogan']      = 'TÌM KIẾM CÔNG TY';
    $data['description'] = 'Tìm kiếm công ty phù hợp nhất với bạn';
    $time1               = time() - 6048000;
    $joblist_2           = $this->db->select('u.avatar,un.id,un.title, cp.name_company')
      ->from('user_news un')
      ->join('users u', 'u.id = un.user_id')
      ->join('company_profiles cp', 'cp.company_id = un.user_id')
      ->where('un.created_date >', $time1)->where('un.active !=', 0)
      ->limit(5, 1)
      ->order_by('un.id desc')
      ->get()->result_array();
    $highlight         = $this->user_model->selctListCompany($start, $perpage, $company_new = "true");
    $data['highlight'] = $highlight['result'];

    $query = $this->user_model->selectDetaiJob($perpage, $start, $user_id);

    $config                     = [];
    $config['base_url']         = base_url() . '/chi-tiet-nha-tuyen-dung/' . $any . '-company' . $user_id . '.html';
    $config['total_rows']       = $query['total'];
    $config['per_page']         = $perpage;
    $config['uri_segment']      = 3;
    $config['use_page_numbers'] = true;
    $config['full_tag_open']    = '<ul class="pagination">';
    $config['full_tag_close']   = '</ul>';
    $config['first_tag_open']   = '<li>';
    $config['first_tag_close']  = '</li>';
    $config['last_tag_open']    = '<li>';
    $config['last_tag_close']   = '</li>';
    $config['next_link']        = 'Next <i class="la la-long-arrow-right"></i>';
    $config['next_tag_open']    = '<li>';
    $config['next_tag_close']   = '</li>';
    $config['prev_link']        = '<i class="la la-long-arrow-left"></i> Prev';
    $config['prev_tag_open']    = '<li>';
    $config['prev_tag_close']   = '</li>';
    $config['cur_tag_open']     = "<li class='active'><a href='javascript:void(0)'>";
    $config['cur_tag_close']    = '</a></li>';
    $config['num_tag_open']     = '<li>';
    $config['num_tag_close']    = '</li>';
    $config['num_links']        = 3;

    $this->pagination->initialize($config);
    $data['pagination'] = $this->pagination->create_links();
    global $my_exp;
    global $salary;
    global $type_work;

    $active           = 0;
    $acc_save_company = $this->db->get_where('acc_save_company', ['user_id' => $user_id, 'user_save_id' => $user_id]);
    if ($acc_save_company->num_rows() > 0) {
      $active = $acc_save_company->row_array()['active'];
    }

    $data['active'] = $active;

    $data['my_exp']       = $my_exp;
    $data['joblist_2']    = $joblist_2;
    $data['salary']       = $salary;
    $data['type_work']    = $type_work;
    $data['data']         = $this->db->get_where('company_profiles', ['company_id' => $user_id])->row_array();
    $data['header']       = 'header_3';
    $data['detail']       = $query['sql'];
    $data['class_header'] = 'detailemployer';
    $data['title']        = 'Chi tiết nhà tuyển dụng';
    $data['footer']       = 'footer_3';
    $data['content']      = 'user/detail_employer_3';
    $this->load->view('master_3', $data);
  }
  public function save_company()
  {
    $user_id    = $this->session->userdata('loginted')['id'];
    $active     = $this->input->post('active');
    $company_id = $this->input->post('company_id');

    if ($this->session->has_userdata('loginted')) {
      $acc_save_company = $this->db->get_where('acc_save_company', ['user_id' => $user_id, 'user_save_id' => $company_id]);

      if ($acc_save_company->num_rows() > 0) {
        $update = [
          'user_id'      => $user_id,
          'user_save_id' => $company_id,
          'active'       => 1,
        ];

        $id = $acc_save_company->row_array()['id'];

        $acc_save_company_stt = $this->db->update('acc_save_company', $update, ['id' => $id]);
      } else {
        $insert = [
          'user_id'      => $user_id,
          'user_save_id' => $company_id,
          'active'       => 1,
        ];
        $acc_save_company_stt = $this->db->insert('acc_save_company', $insert);
      }
      if ($acc_save_company_stt) {
        echo json_encode(['mes' => 'Lưu công ty thành công !', 'boot' => 'true', true], JSON_UNESCAPED_UNICODE);
      } else {
        echo json_encode(['mes' => 'Lưu công ty thất bại !', 'boot' => 'false', false], JSON_UNESCAPED_UNICODE);
      }
    }
  }
// Nhà tuyển dụng ===========================================================================================

// Newsfeed =================================================================================================

// Newsfeed =================================================================================================

// CV ứng tuyên==============================================================================================
  public function cv_recruitment()
  {
    $this->checklogin();
    $this->checkstatus();
    $login_user = $this->session->userdata('loginted');
    $star_page  = $this->uri->segment(3);
    $perpage    = 2;
    if (is_numeric($star_page)) {
      $star_page = $star_page;
    } else {
      $star_page = 0;
    }

    $user_id = $login_user['id'];

    $query = $this->user_model->cv_recruitment($star_page, $perpage, $user_id);

    $total_rows                 = $query['total'];
    $config['base_url']         = base_url() . '/ung-vien/cv-ung-tuyen';
    $config['total_rows']       = $total_rows;
    $config['per_page']         = $perpage;
    $config['uri_segment']      = 3;
    $config['use_page_numbers'] = true;
    $config['full_tag_open']    = '<ul class="pagination">';
    $config['full_tag_close']   = '</ul>';
    $config['first_tag_open']   = '<li>';
    $config['first_tag_close']  = '</li>';
    $config['last_tag_open']    = '<li>';
    $config['last_tag_close']   = '</li>';
    $config['next_link']        = 'Next <i class="la la-long-arrow-right"></i>';
    $config['next_tag_open']    = '<li>';
    $config['next_tag_close']   = '</li>';
    $config['prev_link']        = '<i class="la la-long-arrow-left"></i> Prev';
    $config['prev_tag_open']    = '<li>';
    $config['prev_tag_close']   = '</li>';
    $config['cur_tag_open']     = "<li class='active'><a href='javascript:void(0)'>";
    $config['cur_tag_close']    = '</a></li>';
    $config['num_tag_open']     = '<li>';
    $config['num_tag_close']    = '</li>';
    $config['num_links']        = 4;
    $this->pagination->initialize($config);
    $data['pagination']  = $this->pagination->create_links();
    $data['candiate_cv'] = $query['result'];
    $data['total']       = $query['total'];

    $data['header']       = 'header';
    $data['class_header'] = 'stick-top';
    $data['title']        = 'CV ứng tuyển';
    $data['content']      = 'user/cv_recruitment';
    $this->load->view('master', $data);
  }
  public function dell_cv($id)
  {
    $dell = $this->db->delete('candidate_cv', ['id' => $id]);
    if ($dell) {
      $this->session->set_userdata('success', 'Xóa cv thành công!');
      header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
    $this->session->set_userdata('error', 'Xóa cv thất bại!');
    header('Location: ' . $_SERVER['HTTP_REFERER']);
  }
// CV ứng tuyên==============================================================================================

// Ứng viên =================================================================================================

  public function user_list()
  {
    $sql                = $this->db->get_where('tbl_meta', ['id' => 3])->row_array();
    $data['meta_title'] = $sql['title'];
    $data['meta_key']   = $sql['metakeywork'];
    $data['meta_des']   = $sql['metadesc'];

    $start   = $this->uri->segment(2);
    $perpage = 15;
    if (is_numeric($start)) {
      $start = $start;
    } else {
      $start = 0;
    }

    $query = $this->user_model->selectListUser($start, $perpage);

    $data3 = $this->db->select('*')->from('users u')->where(['u.type ' => 1])->order_by('u.id', 'desc')->limit(5, 1)->get()->result_array();

    $config                     = [];
    $config['base_url']         = base_url() . '/danh-sach-ung-vien.html';
    $config['total_rows']       = $query['total'];
    $config['per_page']         = $perpage;
    $config['uri_segment']      = 2;
    $config['use_page_numbers'] = true;
    $config['full_tag_open']    = '<ul class="pagination">';
    $config['full_tag_close']   = '</ul>';
    $config['first_tag_open']   = '<li>';
    $config['first_tag_close']  = '</li>';
    $config['last_tag_open']    = '<li>';
    $config['next_link']        = '<i class="la la-long-arrow-right"></i>';
    $config['last_tag_close']   = '</li>';
    $config['next_tag_open']    = '<li>';
    $config['prev_link']        = '<i class="la la-long-arrow-left"></i>';
    $config['next_tag_close']   = '</li>';
    $config['prev_tag_open']    = '<li>';
    $config['prev_tag_close']   = '</li>';
    $config['cur_tag_open']     = "<li class='active'><a href='javascript:void(0)'>";
    $config['cur_tag_close']    = '</a></li>';
    $config['num_tag_open']     = '<li>';
    $config['num_tag_close']    = '</li>';
    $config['num_links']        = 2;
    $this->pagination->initialize($config);

    global $my_exp;
    global $salary;
    global $type_work;
    $data['slogan']       = 'TÌM ỨNG VIÊN PHÙ HỢP';
    $data['description']  = 'Có rất nhiều ứng viên đang chờ bạn';
    $data['pagination']   = $this->pagination->create_links();
    $data['my_exp']       = $my_exp;
    $data['salary']       = $salary;
    $data['type_work']    = $type_work;
    $data['data3']        = $data3;
    $data['news_blog']    = $this->db->get_where('post', ['active' => 0, 'cate_id' => 16], 5, 1)->result_array();
    $data['header']       = 'header_3';
    $data['data']         = $query['result'];
    $data['total']        = $query['total'];
    $data['class_header'] = 'userlist';
    $data['title']        = 'Danh sách ứng viên';
    $data['content']      = 'user/list_user_3';
    $data['footer']       = 'footer_3';
    $this->load->view('master_3', $data);
  }
  public function detail_candidate($any, $num)
  {
    $data_null = [
      'user_idIndex'    => '',
      'address'         => '',
      'province_id'     => '',
      'district_id'     => '',
      'name_school'     => '',
      'birthday'        => '',
      'sex'             => '',
      'marriage'        => '',
      'learn_rank'      => '',
      'level'           => '',
      'language'        => '',
      'job_wish'        => '',
      'degree'          => '',
      'other_workplace' => '',
      'majors'          => '',
      'job_form'        => '',
      'job'             => '',
      'job_other'       => '',
      'my_exp'          => '',
      'salary'          => '',
      'career'          => '',
      'skills'          => '',
      'general'         => '',
      'description_exp' => '',
      'updated_date'    => '',
    ];
    $data['slogan']       = 'Thông tin chi tiết ứng viên';
    $data['description']  = 'Phạm Thanh Hà';
    $data['header']       = 'header_3';
    $data['class_header'] = 'detailcandidate';
    $data['my_exp']       = $this->db->get('my_exp')->result_array();
    $data['salary']       = $this->db->get('salary')->result_array();
    $data['footer']       = 'footer_3';
    $data['title']        = 'Chi tiết tin ứng viên';
    $data['content']      = 'user/detail_candidate_3';
    $this->load->view('master_3', $data);
  }
  public function save_candidate()
  {
    $user_id      = $this->session->userdata('loginted')['id'];
    $active       = $this->input->post('active');
    $candidate_id = $this->input->post('candidate_id');

    if ($this->session->has_userdata('loginted')) {
      $acc_save_candidate = $this->db->get_where('acc_save_candidate', ['user_id' => $user_id, 'user_save_id' => $candidate_id]);

      if ($acc_save_candidate->num_rows() > 0) {
        $update = [
          'user_id'      => $user_id,
          'user_save_id' => $candidate_id,
          'active'       => 1,
        ];

        $id = $acc_save_candidate->row_array()['id'];

        $acc_save_candidate_stt = $this->db->update('acc_save_candidate', $update, ['id' => $id]);
      } else {
        $insert = [
          'user_id'      => $user_id,
          'user_save_id' => $candidate_id,
          'active'       => 1,
        ];
        $acc_save_candidate_stt = $this->db->insert('acc_save_candidate', $insert);
      }
      if ($acc_save_candidate_stt) {
        echo json_encode(['mes' => 'Lưu hồ sơ ứng viên thành công !', 'boot' => 'true', true], JSON_UNESCAPED_UNICODE);
      } else {
        echo json_encode(['mes' => 'Lưu hồ sơ ứng viên thất bại !', 'boot' => 'false', false], JSON_UNESCAPED_UNICODE);
      }
    }
  }

// Ứng viên =================================================================================================

// Việc làm =================================================================================================

  public function myjob_save()
  {

    $this->checklogin();
    $this->checkstatus();
    $star_page = $this->uri->segment(2);
    $perpage   = 2;
    if (is_numeric($star_page)) {
      $star_page = $star_page;
    } else {
      $star_page = 0;
    }
    $login_user                 = $this->session->userdata('loginted');
    $user_id                    = $login_user['id'];
    $query                      = $this->user_model->myjob_save($star_page, $perpage, $user_id, 'active', 1);
    $total_rows                 = $query['total'];
    $config['base_url']         = base_url() . '/viec-lam-cua-toi.html';
    $config['total_rows']       = $total_rows;
    $config['per_page']         = $perpage;
    $config['uri_segment']      = 2;
    $config['use_page_numbers'] = true;
    $config['full_tag_open']    = '<ul class="pagination">';
    $config['full_tag_close']   = '</ul>';
    $config['first_tag_open']   = '<li>';
    $config['first_tag_close']  = '</li>';
    $config['last_tag_open']    = '<li>';
    $config['last_tag_close']   = '</li>';
    $config['next_link']        = 'Next <i class="la la-long-arrow-right"></i>';
    $config['next_tag_open']    = '<li>';
    $config['next_tag_close']   = '</li>';
    $config['prev_link']        = '<i class="la la-long-arrow-left"></i> Prev';
    $config['prev_tag_open']    = '<li>';
    $config['prev_tag_close']   = '</li>';
    $config['cur_tag_open']     = "<li class='active'><a href='javascript:void(0)'>";
    $config['cur_tag_close']    = '</a></li>';
    $config['num_tag_open']     = '<li>';
    $config['num_tag_close']    = '</li>';
    $config['num_links']        = 4;
    $this->pagination->initialize($config);
    $data['pagination']   = $this->pagination->create_links();
    $data['data']         = $query['result'];
    $data['total']        = $query['total'];
    $data['header']       = 'header';
    $data['class_header'] = 'stick-top';
    $data['title']        = 'Việc làm của tôi';
    $data['content']      = 'user/my_job_save';
    $this->load->view('master', $data);
  }

  public function myjob_saverecruitment()
  {
    $this->checklogin();
    $this->checkstatus();
    $login_user = $this->session->userdata('loginted');
    if ($login_user['type'] == 2) {
      ?>
<script type="text/javascript">
alert("Tài khoản của bạn không có quyền sử dụng chức năng này!");
window.location.href = '<?php echo base_url() ?>';
</script>
<?php
}
    $star_page = $this->uri->segment(2);
    $perpage   = 2;
    if (is_numeric($star_page)) {
      $star_page = $star_page;
    } else {
      $star_page = 0;
    }
    $user_id                    = $login_user['id'];
    $query                      = $this->user_model->myjob_save($star_page, $perpage, $user_id, 'type', 1);
    $total_rows                 = $query['total'];
    $config['base_url']         = base_url() . '/viec-lam-da-ung-tuyen.html';
    $config['total_rows']       = $total_rows;
    $config['per_page']         = $perpage;
    $config['uri_segment']      = 2;
    $config['use_page_numbers'] = true;
    $config['full_tag_open']    = '<ul class="pagination">';
    $config['full_tag_close']   = '</ul>';
    $config['first_tag_open']   = '<li>';
    $config['first_tag_close']  = '</li>';
    $config['last_tag_open']    = '<li>';
    $config['last_tag_close']   = '</li>';
    $config['next_link']        = 'Next <i class="la la-long-arrow-right"></i>';
    $config['next_tag_open']    = '<li>';
    $config['next_tag_close']   = '</li>';
    $config['prev_link']        = '<i class="la la-long-arrow-left"></i> Prev';
    $config['prev_tag_open']    = '<li>';
    $config['prev_tag_close']   = '</li>';
    $config['cur_tag_open']     = "<li class='active'><a href='javascript:void(0)'>";
    $config['cur_tag_close']    = '</a></li>';
    $config['num_tag_open']     = '<li>';
    $config['num_tag_close']    = '</li>';
    $config['num_links']        = 4;
    $this->pagination->initialize($config);
    $data['pagination'] = $this->pagination->create_links();
    $data['data']       = $query['result'];
    $data['total']      = $query['total'];

    $data['header']       = 'header';
    $data['class_header'] = 'stick-top';
    $data['title']        = 'Việc làm đã ứng tuyển';
    $data['content']      = 'user/myjob_saverecruitment';
    $this->load->view('master', $data);
  }
  public function user_recruitment_save()
  {
    $data['slogan']       = 'Thông tin chi tiết ứng viên';
    $data['description']  = 'Phạm Thanh Hà';
    $data['header']       = 'header_3';
    $data['class_header'] = 'userrecruitmentsave';
    $data['title']        = 'Ứng viên ứng tuyển';
    $data['content']      = 'user/user_recruitment_save_3';
    $data['footer']       = 'footer_3';
    $this->load->view('master_3', $data);
  }

  public function follow_company()
  {
    $this->checklogin();
    $this->checkstatus();
    $star_page = $this->uri->segment(2);
    $perpage   = 2;
    if (is_numeric($star_page)) {
      $star_page = $star_page;
    } else {
      $star_page = 0;
    }
    $login_user                 = $this->session->userdata('loginted');
    $user_id                    = $login_user['id'];
    $query                      = $this->user_model->follow_company($star_page, $perpage, $user_id, 'active', 1);
    $total_rows                 = $query['total'];
    $config['base_url']         = base_url() . '/cong-ty-dang-theo-doi.html';
    $config['total_rows']       = $total_rows;
    $config['per_page']         = $perpage;
    $config['uri_segment']      = 2;
    $config['use_page_numbers'] = true;
    $config['full_tag_open']    = '<ul class="pagination">';
    $config['full_tag_close']   = '</ul>';
    $config['first_tag_open']   = '<li>';
    $config['first_tag_close']  = '</li>';
    $config['last_tag_open']    = '<li>';
    $config['last_tag_close']   = '</li>';
    $config['next_link']        = 'Next <i class="la la-long-arrow-right"></i>';
    $config['next_tag_open']    = '<li>';
    $config['next_tag_close']   = '</li>';
    $config['prev_link']        = '<i class="la la-long-arrow-left"></i> Prev';
    $config['prev_tag_open']    = '<li>';
    $config['prev_tag_close']   = '</li>';
    $config['cur_tag_open']     = "<li class='active'><a href='javascript:void(0)'>";
    $config['cur_tag_close']    = '</a></li>';
    $config['num_tag_open']     = '<li>';
    $config['num_tag_close']    = '</li>';
    $config['num_links']        = 4;
    $this->pagination->initialize($config);
    $data['pagination']   = $this->pagination->create_links();
    $data['data']         = $query['result'];
    $data['total']        = $query['total'];
    $data['header']       = 'header';
    $data['class_header'] = 'stick-top';
    $data['title']        = 'Công ty đang theo dõi';
    $data['content']      = 'user/follow_company';
    $this->load->view('master', $data);
  }
  public function follow_candidate()
  {
    $data['slogan']       = 'Thông tin chi tiết ứng viên';
    $data['description']  = 'Phạm Thanh Hà';
    $data['header']       = 'header_3';
    $data['class_header'] = 'userrecruitmentsave';
    $data['title']        = 'Ứng viên đang theo dõi';
    $data['content']      = 'user/follow_candidate';
    $data['footer']       = 'footer_3';
    $this->load->view('master_3', $data);
  }
  public function dell_save_job()
  {
    $id     = $this->input->post('id');
    $type   = $this->input->post('type');
    $active = $this->input->post('active');
    $tbl    = $this->input->post('tbl');
    if ($tbl == 'save_job') {
      if ($type == 0) {
        $action = $this->db->delete('acc_user_news', ['id' => $id]);
      } else if ($type != 0) {
        $action = $this->db->update('acc_user_news', ['active' => 0], ['id' => $id]);
      }
    } else if ($tbl == 'save_cruitment') {
      if ($active == 0) {
        $action = $this->db->delete('acc_user_news', ['id' => $id]);
      } else if ($active != 0) {
        $action = $this->db->update('acc_user_news', ['type' => 0], ['id' => $id]);
      }
    }
    if ($action) {
      echo json_encode(['mes' => 'Xóa thành công !', 'boot' => 'true', true], JSON_UNESCAPED_UNICODE);
    } else {
      echo json_encode(['mes' => 'Xóa thất bại !', 'boot' => 'false', false], JSON_UNESCAPED_UNICODE);
    }
  }
  public function dell_follow_company()
  {
    $id     = $this->input->post('id');
    $action = $this->db->delete('acc_save_company', ['id' => $id]);
    if ($action) {
      echo json_encode(['mes' => 'Xóa thành công !', 'boot' => 'true', true], JSON_UNESCAPED_UNICODE);
    } else {
      echo json_encode(['mes' => 'Xóa thất bại !', 'boot' => 'false', false], JSON_UNESCAPED_UNICODE);
    }
  }
  public function dell_follow_candidate()
  {
    $id     = $this->input->post('id');
    $action = $this->db->delete('acc_save_candidate', ['id' => $id]);
    if ($action) {
      echo json_encode(['mes' => 'Xóa thành công !', 'boot' => 'true', true], JSON_UNESCAPED_UNICODE);
    } else {
      echo json_encode(['mes' => 'Xóa thất bại !', 'boot' => 'false', false], JSON_UNESCAPED_UNICODE);
    }
  }
  public function save_note_myjob()
  {
    $id    = $this->input->post('id');
    $note  = $this->input->post('note');
    $table = $this->input->post('table');

    $action = $this->db->update('' . $table . '', ['note' => $note], ['id' => $id]);
    if ($action) {
      echo json_encode(['mes' => 'Thêm ghi chú thành công !', 'boot' => 'true', true], JSON_UNESCAPED_UNICODE);
    } else {
      echo json_encode(['mes' => 'Thêm ghi chú thất bại !', 'boot' => 'false', false], JSON_UNESCAPED_UNICODE);
    }
  }
// Việc làm =================================================================================================

// Ước tính lương ===========================================================================================
  public function salary_estimates()
  {

    $data['header']       = 'header_3';
    $data['slogan']       = 'QUY TRÌNH GIẢI QUYẾT TRANH CHẤP';
    $data['description']  = 'Tìm việc làm, việc làm và cơ hội nghề nghiệp.';
    $data['footer']       = 'footer_3';
    $data['class_header'] = 'salaryestimate';
    $data['content']      = 'user/salary_estimates';
    $data['title']        = 'Ước tính lương';
    $this->load->view('master_3', $data);
  }
  public function sum_medium($sum_trungbinh = '', $data_2, $sum = 0, $total)
  {

    if ($sum_trungbinh != '') {
      foreach ($data_2 as $key => $value) {
        if (($value / 2) < $sum_trungbinh && ($value * 2) > $sum_trungbinh) {
          // $sum += $value;
        } else {
          $mang_loi       = [];
          $mang_loi[$key] = $value;
          $lionel13       = array_diff_key($data_2, $mang_loi);
          $count_loi      = count($lionel13);
          return self::sum_medium($sum_trungbinh = '', $lionel13, $sum = 0, $count_loi);
        }
      }
      // return $sum_trungbinh;
    } else {
      foreach ($data_2 as $key => $value) {
        $sum += $value;
      }
      $sum_trungbinh = $sum / $total;
      return self::sum_medium($sum_trungbinh, $data_2, $sum = 0, $total);
    }

    return $sum_trungbinh;
  }
  // Ước tính lương ===========================================================================================

// Chưa sử dụng  ============================================================================================
  public function manager_inforecruitment()
  {
    $data['header']       = 'header';
    $data['class_header'] = 'white';
    $data['title']        = 'Quản lý hồ sơ ứng tuyển';
    $data['content']      = 'user/manager_inforecruitment';
    $this->load->view('master', $data);
  }
  public function setup_recruitment()
  {
    $data['header']       = 'header';
    $data['class_header'] = 'white';
    $data['title']        = 'Cài đặt nhà tuyển dụng';
    $data['content']      = 'user/setup_recruitment';
    $this->load->view('master', $data);
  }
  public function account_candidate()
  {
    $data['header']       = 'header';
    $data['class_header'] = 'white';
    $data['title']        = 'Thay đổi mật khẩu';
    $data['content']      = 'user/account_candidate';
    $this->load->view('master', $data);
  }
// Chưa sử dụng  ============================================================================================

// Tìm kiếm =================================================================================================
  public function autocompleteData()
  {
    $returnData = [];

    // Get skills data
    $conditions['searchTerm'] = $this->input->get('term');
    // $conditions['conditions']['status'] = '1';
    $skillData = $this->user_model->getRows($conditions);

    // Generate array
    if (!empty($skillData)) {
      foreach ($skillData as $row) {
        $data['id']    = $row['id'];
        $data['value'] = $row['name'];
        array_push($returnData, $data);
      }
    }

    // Return results as json encoded array
    echo json_encode($returnData);die;
  }
// Tìm kiếm =================================================================================================

// Bộ đề =================================================================================================
  public function bodetuyendung()
  {
    $data['bodenoibat']  = $this->user_model->gettinnoibat('exam', 'likes', 6)->result();
    $data['bodemoinhat'] = $this->user_model->getbodemoinhat();

    $data['congty']       = $this->user_model->gettable('companytd', '', '', '', '')->result();
    $data['nganhnghe']    = $this->user_model->gettable('type_work', '', '', '', '')->result();
    $data['title']        = " Bộ đề tuyển dụng";
    $data["content"]      = 'site/bodetuyendung';
    $data['header']       = 'header';
    $data['class_header'] = 'white';
    $this->load->view("master", $data);
  }
  public function chitietbode($alias, $id)
  {
    $data['bodenoibat'] = $this->user_model->gettinnoibat('exam', 'likes', 6)->result();
    $flag               = $this->session->userdata('exam_view' . $id);
    if (!isset($flag) || $flag != 1) {
      $this->user_model->capnhatviewbode('exam', $id);
      $this->session->set_userdata('exam_view' . $id, 1);
    }
    $data['slogan']       = 'TÌM KIẾM BỘ ĐỀ';
    $data['description']  = 'Tìm việc làm, việc làm và cơ hội nghề nghiệp.';
    $data['id']           = $id;
    $data['alias']        = $alias;
    $bode                 = $this->user_model->gettable('exam', 'ID', $id, '', '')->row();
    $cpID                 = $bode->cpID;
    $data['bode']         = $bode;
    $data['bodemoinhat']  = $this->user_model->getbodemoinhat();
    $data['bodelienquan'] = $this->user_model->gettable('exam', 'cpID', $cpID, '', '')->result();
    $data['chitietbode']  = $this->user_model->gettable('question', 'examID', $id, '', '')->result();
    $data['gioihan']      = $this->user_model->gettable('question', 'examID', $id, '', '')->num_rows();
    $data['title']        = "Chi tiết bộ đề";
    $data['content']      = "site/chitietbode_3";
    $data['header']       = 'header_3';
    $data['class_header'] = 'chitietbode';
    $data['footer']       = 'footer_3';
    $this->load->view('master_3', $data);
  }
  public function resultexam()
  {
    $data['slogan']       = 'TÌM KIẾM BỘ ĐỀ';
    $data['description']  = 'Tìm việc làm, việc làm và cơ hội nghề nghiệp.';
    $data['title']        = "Kết quả bộ đề";
    $data['content']      = "site/resultexam_3";
    $data['header']       = 'header_3';
    $data['class_header'] = 'resultexam';
    $data['footer']       = 'footer_3';
    $this->load->view('master_3', $data);
  }
  public function xemketquabode($alias, $id)
  {
    $data['bodenoibat'] = $this->user_model->gettinnoibat('exam', 'likes', 6)->result();
    $flag               = $this->session->userdata('exam_result' . $id);
    if (!isset($flag) || $flag != 1) {
      $this->user_model->capnhatketquabode('exam', $id);
      $this->session->set_userdata('exam_result' . $id, 1);
    }
    $do            = $this->user_model->gettable('question', 'examID', $id, '', '')->num_rows();
    $data['id']    = $id;
    $data['alias'] = $alias;
    if (isset($_POST['dapan'])) {
      $stt    = "";
      $dung   = 0;
      $sai    = 0;
      $ketqua = [];
      foreach ($_POST as $key => $value) {
        $dapan = [];
        if (is_numeric($key)) {
          $query = "SELECT * FROM question WHERE id = {$key} LIMIT 1";
          $kq    = $this->db->query($query)->unbuffered_row();
          if ($value == $kq->answer) {
            $ketqua[$key] = "$value.Đúng";

            $dung++;
          } else {
            $ketqua[$key] = "$value.Sai";

            $sai++;
          }
        }
      }
      $tile             = $dung / $do * 100;
      $data['phantram'] = "Tỷ lệ đúng: $tile %";
      $data['tyledung'] = "Đáp án đúng: $dung/$do";
      $data['tylesai']  = "Đáp án sai: $sai/$do";
    }
    $bode                 = $this->user_model->gettable('exam', 'ID', $id, '', '')->row();
    $cpID                 = $bode->cpID;
    $data['bode']         = $bode;
    $data['bodemoinhat']  = $this->user_model->getbodemoinhat();
    $data['bodelienquan'] = $this->user_model->gettable('exam', 'cpID', $cpID, '', '')->result();
    $data['title']        = "Xem kết quả bộ đề";
    $data['ketqua']       = $ketqua;
    $data['chitiet']      = $this->user_model->gettable('question', 'examID', $id, '', '')->result();
    $data['content']      = "site/xemketquabode";
    $data['header']       = 'header';
    $data['class_header'] = 'white';
    $this->load->view('master', $data);
  }

  public function danhsachbode()
  {
    $data['slogan']       = 'TÌM CÔNG VIỆC BẠN YÊU THÍCH';
    $data['description']  = 'Tìm việc làm, việc làm và cơ hội nghề nghiệp.';
    $data['bodenoibat']   = $this->user_model->gettinnoibat('exam', 'likes', 6)->result();
    $data['xemnhieunhat'] = $this->user_model->gettinnoibat('exam', 'view', 6)->result();
    $data['bodemoinhat']  = $this->user_model->getbodemoinhat();
    $data['congty']       = $this->user_model->gettable('companytd', '', '', '', '')->result();
    $data['nganhnghe']    = $this->user_model->gettable('type_work', '', '', '', '')->result();
    $data['title']        = "Bộ đề bộ đề tuyển dụng";
    $data['header']       = 'header';
    $data['header']       = 'header_3';
    $data['class_header'] = 'danhsachbode';
    $data['content']      = "site/danhsachbode_3";
    $data['footer']       = 'footer_3';
    $this->load->view('master_3', $data);
  }

  public function tatcabode()
  {
    $data                = [];
    $limit_per_page      = 7;
    $page                = ($this->uri->segment(2)) ? ($this->uri->segment(2) - 1) : 0;
    $total_records       = $this->user_model->gettabledesc('exam', '', '', '', '')->num_rows();
    $data['total']       = $total_records;
    $data['slogan']      = 'TÌM KIẾM BỘ ĐỀ';
    $data['description'] = 'Hơn 20 bộ đề phỏng vấn tuyển dụng của công ty Apple';
    if ($total_records > 0) {
      // get current page records
      $data["tatcabode"]     = $this->user_model->gettabledesc('exam', '', '', $limit_per_page, $page * $limit_per_page)->result();
      $config['base_url']    = base_url() . 'tat-ca-bo-de.html';
      $config['total_rows']  = $total_records;
      $config['per_page']    = $limit_per_page;
      $config["uri_segment"] = 2;
      // custom paging configuration
      $config['num_links']          = 4;
      $config['use_page_numbers']   = true;
      $config['reuse_query_string'] = true;

      $config['full_tag_open']  = '<ul class="pagination pagintions">';
      $config['full_tag_close'] = '</ul>';

      $config['first_link']      = ' Đầu';
      $config['first_tag_open']  = '<li class="firstlink page-item">';
      $config['first_tag_close'] = '</li>';

      $config['last_link']      = ' Cuối';
      $config['last_tag_open']  = '<li class="lastlink page-item">';
      $config['last_tag_close'] = '</li>';

      $config['next_link']      = ' Tiếp';
      $config['next_tag_open']  = '<li class="nextlink page-item">';
      $config['next_tag_close'] = '</li>';

      $config['prev_link']      = 'Quay';
      $config['prev_tag_open']  = '<li class="prevlink page-item">';
      $config['prev_tag_close'] = '</li>';
      $config['cur_tag_open']   = '<li class="curlink page-item"><a class="page-link">';
      $config['cur_tag_close']  = '</a></li>';

      $config['num_tag_open']  = '<li class="numlink page-item">';
      $config['num_tag_close'] = '</li>';

      $this->pagination->initialize($config);
      $data["pagination"] = $this->pagination->create_links();
    }
    $data['xemnhieunhat'] = $this->user_model->gettinnoibat('exam', 'view', 6)->result();
    $data['congty']       = $this->user_model->gettable('companytd', '', '', '', '')->result();
    $data['nganhnghe']    = $this->user_model->gettable('type_work', '', '', '', '')->result();
    $data['title']        = "Tất cả bộ đề tuyển dụng";
    $data['header']       = 'header_3';
    $data['class_header'] = 'tatcabode';
    $data['content']      = "site/tatcabode_3";
    $data['footer']       = 'footer_3';
    $this->load->view('master_3', $data);
  }
  public function savelikes()
  {
    $ipaddress = $_SERVER['REMOTE_ADDR'];

    $idexam     = $this->input->post('idexam');
    $fetchlikes = $this->db->query('select likes from exam where id="' . $idexam . '"');
    $result     = $fetchlikes->result();
    $checklikes = $this->db->query('select * from storylikes
                                    where idexam="' . $idexam . '"
                                    and ipaddress = "' . $ipaddress . '"');
    $resultchecklikes = $checklikes->num_rows();
    if ($resultchecklikes == '0') {
      if ($result[0]->likes == "" || $result[0]->likes == "NULL") {
        $this->db->query('update exam set likes=1 where id="' . $idexam . '"');
      } else {
        $this->db->query('update exam set likes=likes+1 where id="' . $idexam . '"');
      }

      $data = ['idexam' => $idexam, 'ipaddress' => $ipaddress];
      $this->db->insert('storylikes', $data);
    } else {
      $this->db->delete('storylikes', ['idexam' => $idexam, 'ipaddress' => $ipaddress]);
      $this->db->query('update exam set likes=likes-1 where id="' . $idexam . '"');
    }

    $this->db->select('likes');
    $this->db->from('exam');
    $this->db->where('id', $idexam);
    $query  = $this->db->get();
    $result = $query->result();
    echo $result[0]->likes;
  }

  public function getrating($id)
  {
    echo $this->user_model->html_output($id);
  }

  public function insertrating($id)
  {
    $ipaddress = $_SERVER['REMOTE_ADDR'];
    $flag      = $this->session->userdata('ipaddress' . $id);
    if (!isset($flag) || $flag != 1) {
      if ($this->input->post('examid')) {
        $data = [
          'examid'    => $this->input->post('examid'),
          'rating'    => $this->input->post('index'),
          'ipaddress' => $ipaddress,
        ];
        $this->user_model->insert_rating($data);
        $this->session->set_userdata('ipaddress' . $id, 1);
      }
    }
  }
// Bộ đề =================================================================================================

// Check ==================================================================================================
  public function success()
  {

    $data['header']       = 'header';
    $data['class_header'] = 'white';
    $data['title']        = 'Thông báo thành công!';
    $data['content']      = 'success';

    $this->load->view('master', $data);
  }
  public function error()
  {
    $data['header']       = 'header';
    $data['class_header'] = 'white';

    $data['title']   = 'Thông báo thành công!';
    $data['content'] = 'error';

    $this->load->view('master', $data);
  }

  public function check_login_username()
  {
    $username = $this->input->post('username');
    $this->db->where('user_name', $username);
    $this->db->or_where('email', $username);
    $query = $this->db->get('users');
    if ($query->num_rows() > 0) {
      $row = $query->row();
      if ($row->active == 2) {
        $this->form_validation->set_message(__FUNCTION__, 'Tài khoản của bạn đang bị khóa');
        return false;
      } elseif ($row->active == 0) {
        $this->form_validation->set_message(__FUNCTION__, 'Tài khoản của bạn chưa được kích hoạt, vui lòng kiểm tra email và kích hoạt tài khoản !');
        return false;
      }

      return true;
    }
    $this->form_validation->set_message(__FUNCTION__, 'Tài khoản của bạn không tồn tại');
    return false;
  }
  public function check_forgot_pass()
  {

    $email = $this->input->post('email');
    $this->db->where('email', $email);
    $query = $this->db->get('users');
    if ($query->num_rows() > 0) {
      return true;
    }
    $this->form_validation->set_message(__FUNCTION__, 'Tài khoản của bạn không tồn tại');
    return false;
  }
  public function check_pass_user()
  {
    $username      = $this->input->post('username');
    $password      = $this->input->post('password');
    $password_hash = md5($password);

    $this->db->where('user_name', $username);
    $this->db->or_where('email', $username);
    $query = $this->db->get('users');
    if ($query->num_rows() > 0) {
      $query_check = "SELECT * FROM users WHERE (user_name = '$username' or email = '$username') AND password = '$password_hash'";
      $checkpass   = $this->db->query($query_check);
      if ($checkpass->num_rows() > 0) {
        return true;
        $this->form_validation->set_message(__FUNCTION__, 'Mật khẩu của bạn đúng!');
      }
      $this->form_validation->set_message(__FUNCTION__, 'Mật khẩu của bạn không đúng!');
      return false;
    }
    $this->form_validation->set_message(__FUNCTION__, '');
    return false;
  }

  public function checkIsProfileUser()
  {
    if (isset($_SESSION['loginted'])) {
      $loginted = $this->session->userdata('loginted');
      $user_id  = $loginted['id'];
      if ($loginted['type'] == 1) {
        $user_profiles = $this->db->get_where('user_profiles', ['user_id' => $user_id]);
        if ($user_profiles->num_rows() > 0) {
        } else {
          $this->db->insert("user_profiles", ['user_id' => $user_id]);
        }
      } else if ($loginted['type'] == 2) {
        $company_profiles = $this->db->get_where('company_profiles', ['company_id' => $user_id]);
        if ($company_profiles->num_rows() > 0) {
        } else {
          $this->db->insert("company_profiles", ['company_id' => $user_id]);
        }
      }
    } else {
      header('Location: ' . base_url() . 'dang-nhap.html');
    }
    # code...
  }

  public function checktype()
  {

    if (isset($_SESSION['loginted'])) {
      $loginted = $this->session->userdata('loginted');
      if ($loginted['type'] == 1) {
        return 1;
      } else if ($loginted['type'] == 2) {
        return 2;
      }
    } else {
      header('Location: ' . base_url() . 'dang-nhap.html');
    }
  }
  public function checkstatus()
  {

    if ($this->session->has_userdata('loginted')) {
      $loginted = $this->session->userdata('loginted');
      switch ($loginted['active']) {
        case '0':
          $this->session->set_userdata('notification', 'Tài khoản chưa được kích hoạt, vui lòng check lại email đăng ký của bạn!');
          header('Location: ' . base_url() . 'User/error');
          break;
        case '1':
          break;
        case '2':
          $this->session->set_userdata('notification', 'Tài khoản của bạn bị khóa, vui lòng thử lại sau hoặc liên hệ với phòng nhân sự để được hỗ trợ !');
          header('Location: ' . base_url() . 'User/error');
          break;
      }
    } else {
      header('Location: ' . base_url() . 'dang-nhap.html');
    }
  }

  public function checklogin()
  {

    if ($this->session->has_userdata('loginted')) {
    } else {
      return redirect('User/login');
    }
  }

  public function getProvince($count)
  {
    $count1 = $count + 10;
    $query  = $this->db->get('province', 10, $count1);
    $this->db->order_by("id", "asc");
    $query1 = $this->db->get('province')->num_rows();
    foreach ($query->result_array() as $province) {
      echo '<a href="#" class="list-group-item">' . $province["name"] . '</a>';
    }
    if ($count < 50) {
      echo '<a href="#" class="list-group-item limit_count" data-count="' . $count1 . '">Xem thêm (+10)</a>';
    }
  }
  public function getDistrict($id)
  {
    $this->db->where('province_id', $id);
    $query = $this->db->get('district');
    foreach ($query->result() as $key => $district) {
      echo "<option value='" . $district->id . "'>" . $district->name . "</option>";
    }
  }
  public function checkKytu($value = '')
  {
    $pattern = "/^[a-z0-9]+$/i";
    if (preg_match($pattern, $value)) {
      return true;
    } else {
      $this->form_validation->set_message('checkKytu', '%s không được chứa ký tự đặc biệt ');
      return false;
    }
  }
// Check ==================================================================================================

  public function timkiembode($a)
  {
    if (isset($_GET['findkey'])) {
      $key             = $_GET['findkey'];
      $data['findkey'] = $_GET['findkey'];
    } else {
      $key = '';
    }
    if (isset($_GET['nganh'])) {
      $nganh         = $_GET['nganh'];
      $data['nganh'] = $_GET["nganh"];
    } else {
      $nganh = '';
    }
    if (isset($_GET['congty'])) {
      $congty          = $_GET['congty'];
      $data['company'] = $_GET["congty"];
    } else {
      $congty = '';
    }

    $sql                = $this->db->get_where('tbl_meta', ['id' => 6])->row_array();
    $data['meta_title'] = $sql['title'];
    $data['meta_key']   = $sql['metakeywork'];
    $data['meta_des']   = $sql['metadesc'];
    //echo $_GET['findkey'];die;
    // $nganh = $_GET['nganh'];
    // $congty = $_GET['congty'];

    if ($key != '' || $nganh != '' || $congty != '') {
      $data           = [];
      $limit_per_page = 5;
      $page           = ($this->uri->segment(2)) ? ($this->uri->segment(2) - 1) : 0;

      $total_records = $this->user_model->gettimkiembode($key, $nganh, $congty, '', '')->num_rows();

      $data['total'] = $total_records;
      if ($total_records > 0) {
        $data["tatcabode"]     = $this->user_model->gettimkiembode($key, $nganh, $congty, $limit_per_page, $page * $limit_per_page);
        $config['base_url']    = base_url() . 'ket-qua-tim-kiem-bo-de.html';
        $config['total_rows']  = $total_records;
        $config['per_page']    = $limit_per_page;
        $config["uri_segment"] = 2;
        // custom paging configuration
        $config['num_links']          = 4;
        $config['use_page_numbers']   = true;
        $config['reuse_query_string'] = true;

        $config['full_tag_open']  = '<ul class="pagination pagintions">';
        $config['full_tag_close'] = '</ul>';

        $config['first_link']      = ' Đầu';
        $config['first_tag_open']  = '<li class="firstlink page-item">';
        $config['first_tag_close'] = '</li>';

        $config['last_link']      = ' Cuối';
        $config['last_tag_open']  = '<li class="lastlink page-item">';
        $config['last_tag_close'] = '</li>';

        $config['next_link']      = ' Tiếp';
        $config['next_tag_open']  = '<li class="nextlink page-item">';
        $config['next_tag_close'] = '</li>';

        $config['prev_link']      = 'Quay';
        $config['prev_tag_open']  = '<li class="prevlink page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open']   = '<li class="curlink page-item"><a class="page-link">';
        $config['cur_tag_close']  = '</a></li>';
        $config['num_tag_open']   = '<li class="numlink page-item">';
        $config['num_tag_close']  = '</li>';
        $data['thongbao']         = "Có thể bạn quan tâm";
        $this->pagination->initialize($config);
        $data["pagination"] = $this->pagination->create_links();
      }
      if ($total_records == null) {
        $data['tatcabode'] = $this->user_model->getbodequantam();
        $data['thongbao']  = "Không tìm thấy. Có thể bạn quan tâm";
      }
    } else {
      $data['thongbao']  = "Có thể bạn quan tâm";
      $data['tatcabode'] = $this->user_model->getbodequantam();
    }
    if (isset($_GET['findkey'])) {
      $key             = $_GET['findkey'];
      $data['findkey'] = $_GET['findkey'];
    } else {
      $key = '';
    }
    if (isset($_GET['nganh'])) {
      $nganh         = $_GET['nganh'];
      $data['nganh'] = $_GET["nganh"];
    } else {
      $nganh = '';
    }
    if (isset($_GET['congty'])) {
      $congty          = $_GET['congty'];
      $data['company'] = $_GET["congty"];
    } else {
      $congty = '';
    }
    $data['xemnhieunhat'] = $this->user_model->gettinnoibat('exam', 'view', 6)->result();
    $data['congty']       = $this->user_model->gettable('companytd', '', '', '', '')->result();
    $data['nganhnghe']    = $this->user_model->gettable('type_work', '', '', '', '')->result();
    $data['title']        = "Kết quả tìm kiếm bộ đề tuyển dụng";
    $data['header']       = 'header';
    $data['class_header'] = 'white';
    $data['content']      = "site/kqtimkiembode";
    $this->load->view('master', $data);
  }
}
?>