<?php

namespace App\Controllers\Client;

use Core\Controller;
use App\Models\UserModel;
use PHPMailer\PHPMailer\PHPMailer;

class AuthController extends ClientController
{
    private $userModel;

    public function __construct()
    {
        parent::__construct('Client');
        $this->userModel = new UserModel();
    }

    public function showLoginPage(): void
    {
        if (isset($_SESSION['username'])) {
            header('Location: /');
            exit;
        }
        parent::render('login');
    }

    public function register()
    {
        if (isset($_POST['action']) && $_POST['action'] == 'register') {
            $name = $this->checkInput($_POST['name']);
            $uname = $this->checkInput($_POST['uname']);
            $email = $this->checkInput($_POST['email']);
            $pass = $this->checkInput($_POST['pass']);
            $cpass = $this->checkInput($_POST['cpass']);
            $created = date('Y-m-d');

            if ($pass != $cpass) {
                echo 'Password did not match!';
                exit();
            } else {
                if ($this->userModel->registerUser($name, $uname, $email, $pass, $created)) {
                    echo 'Registered Successfully. Login Now!';
                } else {
                    echo 'Something went wrong. Please try again!';
                }
            }
        }
    }

    public function login()
    {
        if (isset($_POST['action']) && $_POST['action'] == 'login') {
            $username = $this->checkInput($_POST['username']);
            $password = $this->checkInput($_POST['password']);

            $user = $this->userModel->loginUser($username, $password);
            $userId = $this->userModel->getUserIdByUsername($username);


            if ($user != null) {
                $_SESSION['username'] = $username;
                $_SESSION['userId'] = $userId;
                echo 'ok';
                if (!empty($_POST['rem'])) {
                    setcookie("username", $_POST['username'], time() + (10 * 365 * 24 * 60 * 60));
                    setcookie("password", $_POST['password'], time() + (10 * 365 * 24 * 60 * 60));
                } else {
                    if (isset($_COOKIE['username'])) {
                        setcookie("username", "");
                    }

                    if (isset($_COOKIE['password'])) {
                        setcookie("password", "");
                    }
                }
            } else {
                echo "Login Failed! Check your username and password";
            }
        }
    }

    public function logout()
    {
        session_destroy();
        header('Location: /account/login');
    }

    public function forgotPassword()
    {
        $mail = new PHPMailer(true);

        try {
            if (isset($_POST['action']) && $_POST['action'] == 'forgot') {
                $femail = $_POST['femail'];

                if ($this->userModel->generateResetToken($femail)) {
                    $token = $this->userModel->generateResetToken($femail);
                    $mail->SMTPDebug = 0;
                    $mail->Host = 'smtp.gmail.com';
                    $mail->Port = 587;
                    $mail->isSMTP();
                    $mail->SMTPAuth = true;
                    $mail->SMTPSecure = 'tls';
                    $mail->Username = 'haucanquandoi@gmail.com';
                    $mail->Password = 'hlwj zryl cpue befn';
                    $mail->addAddress($femail);
                    $mail->setFrom('haucanquandoi@gmail.com');
                    $mail->Subject = 'Reset Password';
                    $mail->isHTML(false);
                    $mail->Body = $this->generateChangePasswordEmail("http:localhost/account/resetPassword?email=$femail&token=$token");

                    $mail->smtpConnect(array("tls" => array(
                        "verify_peer" => false,
                        "verify_peer_name" => false,
                        "allow_self_signed" => true
                    )));

                    if ($mail->send()) {
                        echo 'We have sent you the reset link in your email ID, please check your email.';
                    } else {
                        echo 'Something went wrong please try again later.', $mail->ErrorInfo;
                    }
                }
            }
        } catch (Exception $e) {
            echo $e;
        }
    }

    public function resetPassword()
    {
        $msg = "";

        if (isset($_GET['email']) && isset($_GET['token'])) {
            $email = $_GET['email'];
            $token = $_GET['token'];

            if ($this->userModel->verifyResetToken($email, $token)) {
                if (isset($_POST['submit'])) {
                    $newpass = $_POST['newpass'];
                    $cnewpass = $_POST['cnewpass'];

                    if ($newpass == $cnewpass) {
                        if ($this->userModel->resetPassword($email, $newpass)) {
                            $msg = 'Password changed successfully! <br> <a href="/account/login">Login Here</a>';
                        } else {
                            $msg = "Something went wrong. Please try again!";
                        }
                    } else {
                        $msg = "Password did not match!";
                    }
                }
            } else {
                header("location: /");
                exit();
            }
        } else {
            header("location: /");
            exit();
        }

        parent::render('resetPassword', ['msg' => $msg]);
    }

    public function generateChangePasswordEmail($resetLink)
    {
        $recipientName = "Quân";
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $operatingSystem = $this->getOSInfo($userAgent);
        $browserName = $this->getBrowserInfo($userAgent);
        $supportUrl = "http://localhost/contact";

        $body = "Use this link to reset your password. The link is only valid for 24 hours.\n\n";
        $body .= "Tiệm bánh ngọt (http://localhost)\n\n";
        $body .= "************\n";
        $body .= "Hi my friend,\n";
        $body .= "************\n\n";
        $body .= "You recently requested to reset your password for your [Product Name] account. Use the button below to reset it. This password reset is only valid for the next 24 hours.\n\n";
        $body .= "Reset your password ($resetLink)\n\n";
        $body .= "For security, this request was received from a $operatingSystem device using $browserName. If you did not request a password reset, please ignore this email or contact support ($supportUrl) if you have questions.\n\n";
        $body .= "Thanks,\nThe [Product Name] team\n\n";
        $body .= "If you’re having trouble with the button above, copy and paste the URL below into your web browser.\n\n";
        $body .= "$resetLink\n\n";
        $body .= "[Company Name, LLC]\n";
        $body .= "1234 Street Rd.\n";
        $body .= "Suite 1234\n";

        return $body;
    }

    function checkInput($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }

    function getBrowserInfo($user_agent)
    {
        $browser_info = 'Unknown';

        if (strpos($user_agent, 'MSIE') !== FALSE || strpos($user_agent, 'Trident') !== FALSE) {
            $browser_info = 'Internet Explorer';
        } elseif (strpos($user_agent, 'Firefox') !== FALSE) {
            $browser_info = 'Mozilla Firefox';
        } elseif (strpos($user_agent, 'Chrome') !== FALSE) {
            $browser_info = 'Google Chrome';
        } elseif (strpos($user_agent, 'Safari') !== FALSE) {
            $browser_info = 'Apple Safari';
        } elseif (strpos($user_agent, 'Opera') !== FALSE || strpos($user_agent, 'OPR') !== FALSE) {
            $browser_info = 'Opera';
        } elseif (strpos($user_agent, 'Edge') !== FALSE) {
            $browser_info = 'Microsoft Edge';
        }

        return $browser_info;
    }

    function getOSInfo($user_agent)
    {
        $os_info = 'Unknown';

        if (strpos($user_agent, 'Win') !== FALSE) {
            $os_info = 'Windows';
        } elseif (strpos($user_agent, 'Mac') !== FALSE) {
            $os_info = 'MacOS';
        } elseif (strpos($user_agent, 'Linux') !== FALSE) {
            $os_info = 'Linux';
        } elseif (strpos($user_agent, 'Android') !== FALSE) {
            $os_info = 'Android';
        } elseif (strpos($user_agent, 'iOS') !== FALSE) {
            $os_info = 'iOS';
        }

        return $os_info;
    }
}
