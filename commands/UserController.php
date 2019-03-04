<?php
namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\User;
use app\models\SignupForm;
use yii\helpers\VarDumper;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Create admin user for feedpro admin web application
 *
 * @author wisaruthk
 */
class UserController extends \yii\console\Controller {
    //put your code here
    public function actionHello($input){
        echo "Hello ".$input;
        //$this->stdout("Hello?\n",Console::BOLD);
        return Controller::EXIT_CODE_NORMAL;
    }

    public function actionCreateAdmin($email,$password){
        echo "Hello ".$email."\n";
        $form = new SignupForm();
        $form->username = $email;
        $form->email = $email;
        $form->password = $password;
        $user = $form->signup();
        if($user){
            echo "Welcome ".$user->username."\n";
            return Controller::EXIT_CODE_NORMAL;
        }
//        $newUser = new User();
//        $newUser->username = $email;
//        $newUser->email = $email;
//        $newUser->setPassword($password);
//        $newUser->generateAuthKey();
//        if($newUser->save()){
//            echo "Welcome ".$newUser->username."\n";
//            return Controller::EXIT_CODE_NORMAL;
//        }

        //$this->stdout("Hello?\n",Console::BOLD);
        return Controller::EXIT_CODE_ERROR;
    }

    public function actionDeleteAdmin($email){
        $user = User::findByUsername($email);
        $user->delete();
        echo "delete ".$email." success";
        return Controller::EXIT_CODE_NORMAL;
    }
}