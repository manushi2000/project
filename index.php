<?php
 // https://e5e2-41-90-69-120.eu.ngrok.io/ussdsms/index.php

include_once 'menu.php';
include_once 'db.php';
include_once 'user.php';

    // Read the variables sent via POST from our API
    $sessionId   = $_POST["sessionId"];
    $serviceCode = $_POST["serviceCode"];
    $phoneNumber = $_POST["phoneNumber"];
    $text        = $_POST["text"];

    $user = new user($phoneNumber);
    $db = new DBConnector();
    $pdo = $db->connectToDB();


    //create object for menu class
    $menu = new Menu($text, $sessionId);  
    $text = $menu->middleware($text);

    if($text == "" && $user->isUserRegistered($pdo)){
        //user is registered and string is empty
        $menu->mainMenuRegistered($user->readName($pdo));
    }else if($text == "" && !$user->isUserRegistered($pdo)){
        //user is unregisered and string is empty
        $menu->mainMenuUnRegistered();

    }else if(!$user->isUserRegistered($pdo)){
        //user is unregistered and string is not empty
        $textArray = explode("*", $text);
        switch($textArray[0]){
            case 1: 
                $menu->registerMenu($textArray, $phoneNumber, $pdo);
            break;
            default:
                echo "END Invalid choice. Please try again";
        }
    }else{
        //user is registered and string is not empty
        $textArray = explode("*", $text);
        switch($textArray[0]){
            case 1: 
                $menu->menstrualHygieneMentorship($textArray);
            break;
            case 2: 
                $menu->periodAndCycle($textArray);
            break;
            case 3:
                $menu->donate($textArray);
                break;
            default:
            echo "END Invalid choice. Please try again";
        }
    }



    ?>