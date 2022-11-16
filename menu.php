<?php
    include_once 'util.php';
    include_once 'user.php';
    include_once 'util.php';
    include_once 'sms.php';
   // include_once 'mpesa.php';


    class Menu{
        protected $text;
        protected $sessionId;

        function __construct($text, $sessionId)
        {
            $this->text = $text;
            $this->sessionId = $sessionId;
        }

        public function mainMenuRegistered($name){
            $response = "CON Welcome " . $name . " Reply with:\n";
            $response .= "1: Menstrual Hygiene Mentorship\n";
            $response .= "2: Period and Cycle\n";
            $response .= "3: Donate\n";
            echo $response;
        }

        public function mainMenuUnRegistered(){
            $response = "CON Welcome to USSD service. Reply with:\n";
            $response .= "1. Register\n";
            echo $response;
        }

        public function registerMenu($textArray, $phoneNumber, $pdo){
            $level = count($textArray);
            if($level == 1){
                 echo "CON Please enter your full name:";
            } else if($level == 2){
                 echo "CON Please enter set you PIN:";
            }else if($level == 3){
                 echo "CON Please re-enter your PIN:";
            }else if($level == 4){
                 $name = $textArray[1];
                 $pin = $textArray[2];
                 $confirmPin = $textArray[3];
                 if($pin != $confirmPin){
                     echo "END Your pins do not match. Please try again";
                 }else{
                    $user = new User($phoneNumber);
                    $user->setName($name);
                    $user->setPin($pin);
                    $user->register($pdo);
                     echo "END You have been registered";
                 }
            }

        }

        public function menstrualHygieneMentorship($textArray){
            $level = count($textArray);
            $response = "";
            if($level == 1){
            echo "CON Select:\n";
            echo "1: Reusable Sanitary Pads\n";
            echo "2: Menstrual Health Management\n";
            echo "3: Be Proud\n";
    
        } 
        else if($level==2){
            switch($textArray[1]){
                case 1: //options for reusable sanitary pads
                    
                        echo "CON Choose information you want to view: \n";
                        echo "1: T4B full cycle kit \n";
                        echo "2: How to use it? \n";
                        echo "3: How to wash it? \n";
                        echo "4: Benefit of pads \n";
                        echo "5: How long do the pads last? \n";

                        $response .= Util::$GO_BACK . " Back\n";
                        $response .= Util::$GO_TO_MAIN_MENU .  " Main menu\n";
                        echo $response;

                        break;
                    
                case 2: //options for mhm
                    
                        echo "CON Choose information you want to view: \n";
                        echo "1: What is the normal flow? \n";
                        echo "2: How long should they last? \n";
                        echo "3: How to deal with cramps? \n";
                        echo "4: How does the body feel during menstruation? \n";
                        echo "5: Are girls impure during periods? \n";

                        $response .= Util::$GO_BACK . " Back\n";
                        $response .= Util::$GO_TO_MAIN_MENU .  " Main menu\n";
                        echo $response;
                        break;
                case 3:  //be proud
                    
                        echo "END Key Messages: \n";
                        echo "1. Menstruation is part of growing up. \n";
                        echo "2. Menstruation is the monthly self-cleaning action of a healthy uterus. \n";
                        echo "3. Menstruation is not: Sickness, illness, disease, infection, harmful, dirty, shameful, unclean or otherwise negative. \n";
                        echo "4. Break the stigma chain by educating men and getting them involved.\n";
                    
                    break;
            }
            
        }
        else if
        ($level==3 && $textArray[1]==1){
            switch($textArray[2]){
                case 1: //ans to options for reusable sanitary pads
                    
                    echo "END Each kit contains 6 pads for a monthly cycle and a usage guide. \n";
                    break;

                case 2:
                    
                    echo "END 1. Place pad in underwear and velcro, soft side up.
                    2. Wear the pad and change the pad as needed with time
                    3. Fold used pad and close the velcro 
                    4. Use a bag to carry clean or used pads. \n";

                    break;

                case 3:

                    echo "END 1. Soak the pads and napkins in cold water
                              2. Do not use hot water that will fix blood stains on the  cloth
                              3. Use soap and wash them like you would wash your other clothing
                              4. Please stretch the pads after washing, which helps the cloth to be flat and tidy
                              5. Dry under direct sunlight and do not iron the pads. \n";
                        break;

                case 4:

                    echo "END 1. Made with 100% natural cotton in both the core and the cover
                               2. Extremely soft and gentle for the skin hence avoiding rashes
                               3. Chemical and plastic free
                               4. Biodegradable \n";
                        break;

                
                case 5:

                    echo "END For up to 2 Years. \n";
                                break;

                }
            }
            else if($level==3 && $textArray[1]==2){
                switch($textArray[2]){
    
                    case 1: //ans to options for mhm
                        echo "END It varies from person to person 
                        1. Light Flow: changing pad every 3-5 hours
                        2. Medium Flow: changing pad every 2-3 hours
                        3. High Flow: changing pad every 1-2 hours \n";
                    break;

                    case 2:
                            echo "END They last between 3 and 8 days, but it will usually last for about 5 days.
                            Consult a doctor if your periods last longer than a week \n";
                    break;

                    case 3:
                            echo "END Avoid consuming pain killers as it has unavoidable side effects and it also adds up to the cost. 
                            One should use the hot water bag or take hot water baths, or massage with the oil on cramped areas. \n";
                    break;

                    case 4:
                        echo "END One may experience physical/emotional changes, while others may not feel any change in moods or body.
                     - Physical changes include: cramps, pain, weight gain, food cravings, painful breasts, headache, dizziness or irritability. 
                      - Emotional changes include: short temper, aggression, anger, anxiety or panic, confusion, lack of concentration, nervousness and tension. \n";
                    break;

                   case 5:
                   echo " END There is no impurity in the blood associated with menstruation. Cleanliness and hygiene are important to the menstrual flow, to keep away any odor or infection. \n";
                   break;


                    }
                }
    }
        public function middleware($text){
        //remove entries for going back and going to the main menu
        return $this->goBack($this->goToMainMenu($text));
    }

        public function goBack($text){
        //1*4*5*1*98*2*1234
        $explodedText = explode("*",$text);
        while(array_search(Util::$GO_BACK, $explodedText) != false){
            $firstIndex = array_search(Util::$GO_BACK, $explodedText);
            array_splice($explodedText, $firstIndex-1, 2);
        }
        return join("*", $explodedText);
    }

        public function goToMainMenu($text){
        //1*4*5*1*99*2*1234*99
        $explodedText = explode("*",$text);
        while(array_search(Util::$GO_TO_MAIN_MENU, $explodedText) != false){
            $firstIndex = array_search(Util::$GO_TO_MAIN_MENU, $explodedText);
            $explodedText = array_slice($explodedText, $firstIndex + 1);
        }
        return join("*",$explodedText);
    }


        public function periodAndCycle($textArray){
            
        }
        public function donate($textArray){
           include_once 'mpesa.php';

            $level = count($textArray);
            $response = "";
            if($level == 1){
            echo "CON Enter your phone number to donate:\n";
            
            } 
            else if ($level == 2) {
                echo "CON Enter the amount you wish to donate:\n";
                
                
            }
            else if ($level == 3) {
                
                echo "END Thank you for donating";
                stkpush($textArray[1],$textArray[2]);
                
            }
        }
        
    }

        ?>