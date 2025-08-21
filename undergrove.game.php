<?php
 /**
  *------
  * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
  * undergrove implementation : © <Mathieu Chatrain> <mathieu.chatrain@gmail.com>
  * 
  * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
  * See http://en.boardgamearena.com/#!doc/Studio for more information.
  * -----
  * 
  * undergrove.game.php
  *
  * This is the main file for your game logic.
  *
  * In this PHP file, you are going to defines the rules of the game.
  *
  */


require_once( APP_GAMEMODULE_PATH.'module/table/table.game.php' );
include('modules/Pending.php');
include('modules/Champi.php');


class undergrove extends Table
{
    public static $instance = null;


	function __construct( )
	{
        // Your global variables labels:
        //  Here, you can assign labels to global variables you are using for this game.
        //  You can use any number of global variables with IDs between 10 and 99.
        //  If your game has options (variants), you also have to associate here a label to
        //  the corresponding ID in gameoptions.inc.php.
        // Note: afterwards, you can get/set the global variables with getGameStateValue/setGameStateInitialValue/setGameStateValue
        parent::__construct();
        
        self::initGameStateLabels( array( 
            "variable1" => 10,
            "variable2" => 11,
            "compteurcarbone" => 12,
            "goalracine1" => 13,
            "goalracine2" => 14,
            "goalracine3" => 15,
            "idcopieur" => 16,
            "final" => 17,
            
        ) );  
        
        self::$instance = $this;


        $this->champignon = self::getNew( "module.common.deck" );
        $this->champignon->init( "champignon" );
        $this->champignon->autoreshuffle = true;
        $this->tiles = self::getNew( "module.common.deck" );
        $this->tiles->init( "tiles" );
        $this->goal = self::getNew( "module.common.deck" );
        $this->goal->init( "goal" );

	}
	
    protected function getGameName( )
    {
		// Used for translations and stuff. Please do not modify.
        return "undergrove";
    }	

    /*
        setupNewGame:
        
        This method is called only once, when a new game is launched.
        In this method, you must setup the game according to the game rules, so that
        the game is ready to be played.
    */
    protected function setupNewGame( $players, $options = array() )
    {    
        // Set the colors of the players with HTML color code
        // The default below is red/green/blue/orange/brown
        // The number of colors defined here must correspond to the maximum number of players allowed for the gams
        $gameinfos = self::getGameinfos();
        $default_colors = $gameinfos['player_colors'];
 
        // Create players
        // Note: if you added some extra field on "player" table in the database (dbmodel.sql), you can initialize it there.
        $sql = "INSERT INTO player (player_id, player_color, player_canal, player_name, player_avatar) VALUES ";
        $values = array();
        foreach( $players as $player_id => $player )
        {
            $color = array_shift( $default_colors );
            $values[] = "('".$player_id."','$color','".$player['player_canal']."','".addslashes( $player['player_name'] )."','".addslashes( $player['player_avatar'] )."')";
        }
        $sql .= implode( ',', $values );
        self::DbQuery( $sql );
        //self::reattributeColorsBasedOnPreferences( $players, $gameinfos['player_colors'] );
        self::reloadPlayersBasicInfos();



/////////////////////////////////////////////////////////////////////////////////  
//       _____                        _____       _ _   _       _ _          _   _             
//      / ____|                      |_   _|     (_) | (_)     | (_)        | | (_)            
//     | |  __  __ _ _ __ ___   ___    | |  _ __  _| |_ _  __ _| |_ ______ _| |_ _  ___  _ __  
//     | | |_ |/ _` | '_ ` _ \ / _ \   | | | '_ \| | __| |/ _` | | |_  / _` | __| |/ _ \| '_ \ 
//     | |__| | (_| | | | | | |  __/  _| |_| | | | | |_| | (_| | | |/ / (_| | |_| | (_) | | | |
//      \_____|\__,_|_| |_| |_|\___| |_____|_| |_|_|\__|_|\__,_|_|_/___\__,_|\__|_|\___/|_| |_|
//                                                                                               
/////////////////////////////////////////////////////////////////////////////////                                                                                                 
      
        self::initStat( 'player', 'goal1', 0 ); 
        self::initStat( 'player', 'goal2', 0 );
        self::initStat( 'player', 'goal3', 0 );
        self::initStat( 'player', 'racine', 0 );
        self::initStat( 'player', 'tiles', 0 );
        self::initStat( 'player', 'ressources', 0 );

        
        

        $champi = array();
        for ($i = 1; $i <= 53; $i++)
        {
            $champi[] = array( 'type' => $i, 'type_arg' => 0, 'nbr' => 1);
        }

        $this->champignon->createCards( $champi, 'deck' );
        
        self::DbQuery( "UPDATE champignon set card_location = 'square_0_0' WHERE card_type = 1" );
        self::DbQuery( "UPDATE champignon set card_location = 'square_-1_0' WHERE card_type = 2" );
        self::DbQuery( "UPDATE champignon set card_location = 'square_1_0' WHERE card_type = 3" );
        self::DbQuery( "UPDATE champignon set card_location = 'square_0_-1' WHERE card_type = 4" );
        self::DbQuery( "UPDATE champignon set card_location = 'square_0_1' WHERE card_type = 5" );
        self::DbQuery( "UPDATE champignon set carbone = 1 WHERE card_type = 2" );
        self::DbQuery( "UPDATE champignon set carbone = 1 WHERE card_type = 3" );
        self::DbQuery( "UPDATE champignon set carbone = 1 WHERE card_type = 4" );
        self::DbQuery( "UPDATE champignon set carbone = 1 WHERE card_type = 5" );

        $this->champignon->shuffle( 'deck' );


        $tiles = array();
        for ($j = 1; $j <= 16; $j++)
        {
            $tiles[] = array( 'type' => $j, 'type_arg' => 0, 'nbr' => 1);
        }

        $this->tiles->createCards( $tiles, 'deck' );
        $this->tiles->shuffle( 'deck' );

        $nbre = count($players);

        if (($nbre == 2) || ($nbre == 3))
        {
            $this->tiles->pickCardsForLocation( 3, 'deck', 'tiles_1' );
            $this->tiles->pickCardsForLocation( 3, 'deck', 'tiles_2' );
            $this->tiles->pickCardsForLocation( 3, 'deck', 'tiles_3' );
        }

        if ($nbre == 4)
        {
            $this->tiles->pickCardsForLocation( 4, 'deck', 'tiles_1' );
            $this->tiles->pickCardsForLocation( 4, 'deck', 'tiles_2' );
            $this->tiles->pickCardsForLocation( 4, 'deck', 'tiles_3' );
        }

        $goals = array();
        for ($k = 1; $k <= 12; $k++)
        {
            $goals[] = array( 'type' => $k, 'type_arg' => 0, 'nbr' => 1);
        }

        $this->goal->createCards( $goals, 'deck' );
        $this->goal->shuffle( 'deck' );

        $this->goal->pickCardForLocation( 'deck', 'goal_1', 1 );
        $testgoal1 = self::getUniqueValueFromDB("SELECT card_type FROM goal WHERE card_location = 'goal_1'");
        
        if ($testgoal1 % 2 == 0) {
            $idgoal = self::getUniqueValueFromDB("SELECT card_id FROM goal WHERE card_type = {$testgoal1}-1");
            $this->goal->moveCard( $idgoal, 'discard');
            
        } else {
            $idgoal = self::getUniqueValueFromDB("SELECT card_id FROM goal WHERE card_type = {$testgoal1}+1");
            $this->goal->moveCard( $idgoal, 'discard');
        }
        $this->goal->shuffle( 'deck' );

        $this->goal->pickCardForLocation( 'deck', 'goal_2', 2 );
        $testgoal2 = self::getUniqueValueFromDB("SELECT card_type FROM goal WHERE card_location = 'goal_2'");
        if ($testgoal2 % 2 == 0) {
            $idgoal = self::getUniqueValueFromDB("SELECT card_id FROM goal WHERE card_type = {$testgoal2}-1");
            $this->goal->moveCard( $idgoal, 'discard');
            
        } else {
            $idgoal = self::getUniqueValueFromDB("SELECT card_id FROM goal WHERE card_type = {$testgoal2}+1");
            $this->goal->moveCard( $idgoal, 'discard');
        }
        $this->goal->shuffle( 'deck' );

        $this->goal->pickCardForLocation( 'deck', 'goal_3', 3 );
        $testgoal3 = self::getUniqueValueFromDB("SELECT card_type FROM goal WHERE card_location = 'goal_3'");
        if ($testgoal3 % 2 == 0) {
            $idgoal = self::getUniqueValueFromDB("SELECT card_id FROM goal WHERE card_type = {$testgoal3}-1");
            $this->goal->moveCard( $idgoal, 'discard');
            
        } else {
            $idgoal = self::getUniqueValueFromDB("SELECT card_id FROM goal WHERE card_type = {$testgoal3}+1");
            $this->goal->moveCard( $idgoal, 'discard');
        }
        $this->goal->shuffle( 'deck' );

        

        


        
        foreach( $players as $player_id => $player )
        {
            for ($i = 1; $i<= 3; $i++)
            {
                $this->champignon->pickCardForLocation( 'deck', 'hand_'.$player_id, $i );

            }

        }

        for ($i=0; $i<=1; $i++)
        {
            self::DbQuery( "INSERT INTO foret (type, location, carbone) VALUES ('emplacement_semi', CONCAT('circle_',{$i},'_-1'), 0)" );
        }

        for ($i=-1; $i<=2; $i++)
        {
            for ($j=0; $j<=1; $j++)
            {
            self::DbQuery( "INSERT INTO foret (type, location, carbone) VALUES ('emplacement_semi', CONCAT('circle_',{$i},'_',{$j}), 0)" );
            }
        }

        for ($i=0; $i<=1; $i++)
        {
            self::DbQuery( "INSERT INTO foret (type, location, carbone) VALUES ('emplacement_semi', CONCAT('circle_',{$i},'_2'), 0)" );
        }

        $tableau = [
            [0,-1],
            [-1,0],
            [0,0],
            [1,0],
            [0,1]
        ];

        foreach ($tableau as $i)
        {
            self::DbQuery( "INSERT INTO foret (type, location) VALUES ('champi', CONCAT('square_', {$i[0]}, '_', {$i[1]}))" );
            self::DbQuery( "INSERT INTO foret (type, location, sens_racine) VALUES ('emplacement_racine', CONCAT('minisquare_', {$i[0]}, '_', {$i[1]}, '_', {$i[0]}, '_', {$i[1]}), 1)" );
            self::DbQuery( "INSERT INTO foret (type, location, sens_racine) VALUES ('emplacement_racine', CONCAT('minisquare_', {$i[0]}, '_', {$i[1]}, '_', {$i[0]}+1, '_', {$i[1]}), 2)" );
            self::DbQuery( "INSERT INTO foret (type, location, sens_racine) VALUES ('emplacement_racine', CONCAT('minisquare_', {$i[0]}, '_', {$i[1]}, '_', {$i[0]}+1, '_', {$i[1]}+1), 3)" );
            self::DbQuery( "INSERT INTO foret (type, location, sens_racine) VALUES ('emplacement_racine', CONCAT('minisquare_', {$i[0]}, '_', {$i[1]}, '_', {$i[0]}, '_', {$i[1]}+1), 4)" );

        }
        
        $tableau2 = [
            [0,-2],
            [1,-1],
            [2,0],
            [1,1],
            [0,2],
            [-1,1],
            [-2,0],
            [-1,-1]
        ];

        foreach ($tableau2 as $j)
        {
            self::DbQuery( "INSERT INTO foret (type, location) VALUES ('emplacement_champi', CONCAT('square_', {$j[0]}, '_', {$j[1]}))" );
        }



        self::DbQuery( "INSERT INTO champispecial (type) VALUES (41)" );
        self::DbQuery( "INSERT INTO champispecial (type) VALUES (42)" );
        self::DbQuery( "INSERT INTO champispecial (type) VALUES (43)" );
        self::DbQuery( "INSERT INTO champispecial (type) VALUES (44)" );
        for ($n=45; $n<=48; $n++)
        {
            self::DbQuery( "INSERT INTO champispecial (type, score) VALUES ({$n}, 2)" );
        }







        /************ Init Pending *****/

        foreach( $players as $player_id => $player )
        {
            $this->addPendingFirst($player_id, "InitialTurn");
        }
    

        foreach( $players as $player_id => $player )
        {
            $this->addPendingFirst($player_id, "NormalTurn");
        }



        


    
        /************ End of the game initialization *****/
    }

/////////////////////////////////////////////////////////////////////////////////  
//               _            _ _ _____        _            
//              | |     /\   | | |  __ \      | |           
//     __ _  ___| |_   /  \  | | | |  | | __ _| |_ __ _ ___ 
//    / _` |/ _ \ __| / /\ \ | | | |  | |/ _` | __/ _` / __|
//   | (_| |  __/ |_ / ____ \| | | |__| | (_| | || (_| \__ \
//    \__, |\___|\__/_/    \_\_|_|_____/ \__,_|\__\__,_|___/
//     __/ |                                                
//    |___/                                                 
/////////////////////////////////////////////////////////////////////////////////  
   

    protected function getAllDatas()
    {
               
        $result = array();
        $current_player_id = self::getCurrentPlayerId();    // !! We must only return informations visible by this player !!
    
        // Get information about players
        // Note: you can retrieve some extra field you added for "player" table in "dbmodel.sql" if you need it.
        $sql = "SELECT player_id id, player_score score, player_color color FROM player ";
        $result['players'] = self::getCollectionFromDb( $sql );


        $result['champiforet'] = self::getObjectListFromDB( "SELECT card_id id, card_type type, card_location location, carbone carbone FROM champignon WHERE card_location LIKE 'square%'" );
        $result['hand'] = self::getObjectListFromDB( "SELECT card_id id, card_type type, card_location location, card_location_arg location_arg FROM champignon WHERE card_location LIKE 'hand%'" );
        $result['tilehand'] = self::getObjectListFromDB( "SELECT card_id id, card_type type, card_location location, card_location_arg location_arg FROM tiles WHERE card_location LIKE 'tilehand%'" );

        $result['ressources']= array();
        $result['ressources2']= array();
        $result['ressources3']= array();
        $result['ressources4']= array();

        $listplayers = self::getObjectListFromDB("SELECT player_id id FROM player", true);
                 
            foreach($listplayers as $player)
        {
            $result['ressources'][$player][] = self::getUniqueValueFromDB("SELECT azote FROM player WHERE player_id = '{$player}'");
            $result['ressources'][$player][] = self::getUniqueValueFromDB("SELECT phosphore FROM player WHERE player_id = '{$player}'");
            $result['ressources'][$player][] = self::getUniqueValueFromDB("SELECT potassium FROM player WHERE player_id = '{$player}'");
            $result['ressources'][$player][] = self::getUniqueValueFromDB("SELECT carbone FROM player WHERE player_id = '{$player}'");
            
        }

        foreach($listplayers as $player)
        {
            $result['ressources2'][$player][] = self::getUniqueValueFromDB("SELECT racine FROM player WHERE player_id = '{$player}'");
            $result['ressources2'][$player][] = self::getUniqueValueFromDB("SELECT semi FROM player WHERE player_id = '{$player}'");
            $result['ressources2'][$player][] = self::getUniqueValueFromDB("SELECT arbre FROM player WHERE player_id = '{$player}'");
            
            
        }

            $result['ressources3'][$current_player_id][] = self::getUniqueValueFromDB("SELECT azote FROM player WHERE player_id = '{$current_player_id}'");
            $result['ressources3'][$current_player_id][] = self::getUniqueValueFromDB("SELECT phosphore FROM player WHERE player_id = '{$current_player_id}'");
            $result['ressources3'][$current_player_id][] = self::getUniqueValueFromDB("SELECT potassium FROM player WHERE player_id = '{$current_player_id}'");
            $result['ressources3'][$current_player_id][] = self::getUniqueValueFromDB("SELECT carbone FROM player WHERE player_id = '{$current_player_id}'");
            $result['ressources4'][$current_player_id][] = self::getUniqueValueFromDB("SELECT racine FROM player WHERE player_id = '{$current_player_id}'");
            $result['ressources4'][$current_player_id][] = self::getUniqueValueFromDB("SELECT semi FROM player WHERE player_id = '{$current_player_id}'");
            $result['ressources4'][$current_player_id][] = self::getUniqueValueFromDB("SELECT arbre FROM player WHERE player_id = '{$current_player_id}'");
        

        foreach($listplayers as $player)
        {
            $result['track'][$player][] = self::getUniqueValueFromDB("SELECT track FROM player WHERE player_id = '{$player}'");
            $result['track'][$player][] = self::getUniqueValueFromDB("SELECT player_color FROM player WHERE player_id = '{$player}'");
            
        }


        $result['activation']= array();
        $listplayers = self::getObjectListFromDB("SELECT player_id id FROM player", true);
                 
            foreach($listplayers as $player)
        {
            $result['activation'][$player][] = self::getUniqueValueFromDB("SELECT activation_b FROM player WHERE player_id = '{$player}'");
            $result['activation'][$player][] = self::getUniqueValueFromDB("SELECT activation_p FROM player WHERE player_id = '{$player}'");
            $result['activation'][$player][] = self::getUniqueValueFromDB("SELECT activation_g FROM player WHERE player_id = '{$player}'");
            $result['activation'][$player][] = self::getUniqueValueFromDB("SELECT activation_y FROM player WHERE player_id = '{$player}'");
            
        }
        
  
        $result['color'] = self::getCollectionFromDB( "SELECT player_id id, player_color color FROM player", true );

        $result['semi'] = self::getObjectListFromDB( "SELECT player_id id, location location, carbone carbone FROM foret WHERE type ='semi'" );
        $result['arbre'] = self::getObjectListFromDB( "SELECT player_id id, location location FROM foret WHERE type ='arbre'" );
        $result['semineutre'] = self::getObjectListFromDB( "SELECT player_id id, location location FROM foret WHERE type ='semi_neutre'" );
        $result['racine'] = self::getObjectListFromDB( "SELECT player_id id, sens_racine sens, location location FROM foret WHERE type ='racine'" );

        $result['goals'] = self::getObjectListFromDB( "SELECT card_id id, card_location_arg location, card_type type FROM goal WHERE card_location != 'deck' AND card_location != 'discard'");


        $result['nbreplayers'][] = count(self::getObjectListFromDB("SELECT player_id id FROM player", true));
        $result['goal_1'] = self::getCollectionFromDB( "SELECT card_location location, p1 p1, p2 p2, p3 p3, p4 p4 FROM goal WHERE card_location = 'goal_1'" );
        $result['goal_2'] = self::getCollectionFromDB( "SELECT card_location location, p1 p1, p2 p2, p3 p3, p4 p4 FROM goal WHERE card_location = 'goal_2'" );
        $result['goal_3'] = self::getCollectionFromDB( "SELECT card_location location, p1 p1, p2 p2, p3 p3, p4 p4 FROM goal WHERE card_location = 'goal_3'" );


        $result['listechampi'] = $this->listechampi;
        $result['action'] = $this->action;
        $result['finalgoal'] = $this->finalgoal;
        
        
        $result['champiscore2'][] = self::getUniqueValueFromDB("SELECT nbre FROM champispecial WHERE type = 41");

        $result['champiscore4'] = self::getObjectListFromDB( "SELECT type type, nbre nbre FROM champispecial WHERE type = 42 OR type = 43 OR type = 44" );

        /////////////////////////////////////// Scorepad////////////////////

        $result['name'] = self::getObjectListFromDB( "SELECT player_name name FROM player");

        $result['score'] = $this->EndGame();

               
        ///////////////////////////////

        $result['messagealerte'][] = count(self::getObjectListFromDB("SELECT final final FROM player WHERE final >= 1", true));


        $result['firstplayer'][] = self::getUniqueValueFromDB("SELECT player_id FROM player WHERE player_no=1");
        


        // $name1 = self::getUniqueValuefromDB("SELECT player_name FROM player WHERE player_no = 1");
        // $name2 = self::getUniqueValuefromDB("SELECT player_name FROM player WHERE player_no = 2");

        // if(($name1 == 'grisolf' || $name2 == 'grisolf')&&($name1 == 'Choupi4008' || $name2 == 'Choupi4008'))
        // {
        //     self::DbQuery( "UPDATE player set player_score = 0" ); 
        //     $this->gamestate->nextState( 'end' );

        // }


        return $result;
    }

/////////////////////////////////////////////////////////////////////////////////  
//     _____                      _____                                   _             
//    / ____|                    |  __ \                                 (_)            
//   | |  __  __ _ _ __ ___   ___| |__) | __ ___   __ _ _ __ ___  ___ ___ _  ___  _ __  
//   | | |_ |/ _` | '_ ` _ \ / _ \  ___/ '__/ _ \ / _` | '__/ _ \/ __/ __| |/ _ \| '_ \ 
//   | |__| | (_| | | | | | |  __/ |   | | | (_) | (_| | | |  __/\__ \__ \ | (_) | | | |
//    \_____|\__,_|_| |_| |_|\___|_|   |_|  \___/ \__, |_|  \___||___/___/_|\___/|_| |_|
//                                                 __/ |                                
//                                                |___/                                 
/////////////////////////////////////////////////////////////////////////////////    
    
    function getGameProgression()
    {
        // TODO: compute and return the game progression
        $track = array();
        $track = self::getObjectListFromDB( "SELECT track track FROM player", true );
        $valeurMax = max($track);
        if ($valeurMax<=8)
        {
            return $valeurMax*10;
        }
        else{
            return 100;
        }
        
    }


/////////////////////////////////////////////////////////////////////////////////  
//     _    _ _   _ _ _ _            __                  _   _                 
//    | |  | | | (_) (_) |          / _|                | | (_)                
//    | |  | | |_ _| |_| |_ _   _  | |_ _   _ _ __   ___| |_ _  ___  _ __  ___ 
//    | |  | | __| | | | __| | | | |  _| | | | '_ \ / __| __| |/ _ \| '_ \/ __|
//    | |__| | |_| | | | |_| |_| | | | | |_| | | | | (__| |_| | (_) | | | \__ \
//     \____/ \__|_|_|_|\__|\__, | |_|  \__,_|_| |_|\___|\__|_|\___/|_| |_|___/
//                           __/ |                                             
//                          |___/                                              
/////////////////////////////////////////////////////////////////////////////////  


function addPending($player_id, $function, $arg = NULL, $arg2 = NULL, $arg3 = NULL, $arg4 = NULL) {
    $sql = "INSERT INTO pending (player_id, function, arg, arg2, arg3, arg4) VALUES (".$player_id.", '".$function."', '".$arg."', '".$arg2."', '".$arg3."', '".$arg4."')";
    self::DbQuery( $sql );
}

function addPendingTarget($player_id, $function, $target, $arg = NULL, $arg2 = NULL, $arg3 = NULL, $arg4 = NULL) {
    $sql = "INSERT INTO pending (player_id, function, target, arg, arg2, arg3, arg4) VALUES (".$player_id.", '".$function."', '".$target."', '".$arg."', '".$arg2."', '".$arg3."', '".$arg4."')";
    self::DbQuery( $sql );
}

function addPendingFirst($player_id, $function, $arg = NULL, $arg2 = NULL, $arg3 = NULL, $arg4 = NULL) {
    $minid = self::getUniqueValueFromDB( "select min(id) from pending")-1;
    $sql = "INSERT INTO pending (id, player_id, function, arg, arg2) VALUES (".$minid.",".$player_id.", '".$function."', '".$arg."', '".$arg2."')";
    self::DbQuery( $sql );
}

function getPlayerRelativePositions()  // permet de mettre dans view.php les joueurs dans l'ordre de la base de données et de positionner le current player en haut avec les autres joueurs dans l'ordre du tour
    {
        $result = array();
        
        $players = self::loadPlayersBasicInfos();
        $nextPlayer = self::createNextPlayerTable(array_keys($players)); //met joueurs dans l'ordre du tour au niveau de l'affichage à droite
        
        $current_player = self::getCurrentPlayerId();
        
        if(!isset($nextPlayer[$current_player])) {
            // Spectator mode: prend la vue du premier joueur de la liste
            $player_id = $nextPlayer[0];
        }
        else {
            // Normal mode: current player est premier de la liste puis les autres dans l ordre de la base de données player
            $player_id = $current_player;
        }
        $result[] = $player_id;
        
        for($i=1; $i<count($players); $i++) {
            $player_id = $nextPlayer[$player_id];
            $result[] = $player_id;
        }
        return $result;
    }

function MajRessources ()
    {
        $newsressources = self::getCollectionFromDB( "SELECT player_id id, azote azote, phosphore phosphore, potassium potassium, carbone carbone, activation_b activation_b, activation_p activation_p, activation_g activation_g, activation_y activation_y, racine racine, semi semi, arbre arbre FROM player" );
        $newscarbonesemi = self::getCollectionFromDB( "SELECT id id, player_id player, location location, carbone carbone FROM foret WHERE type = 'semi'" );
        $newscarboneschampi = self::getCollectionFromDB( "SELECT card_id id, carbone carbone FROM champignon WHERE card_location LIKE 'square%' AND card_location != 'square_0_0' AND card_type != 28 AND card_type != 29 AND card_type != 30 AND card_type != 31 AND card_type != 32 AND card_type != 19 AND card_type != 20 AND card_type != 21 AND card_type != 22 AND card_type != 45 AND card_type != 46 AND card_type != 47 AND card_type != 48 AND card_type != 49");
        
        undergrove::$instance->notifyAllPlayers( "majresssources", '',
                    array(
    
                        'ressources' => $newsressources,
                        'carbonessemi' => $newscarbonesemi,
                        'carboneschampi' => $newscarboneschampi,
                        
                
                    )
                    );

    $this->EndGame();
    }

    



function GoalTrack()
    {
        $player_id = $this->getActivePlayerId();
        $n = self::getUniqueValueFromDB("SELECT player_no FROM player WHERE player_id = '{$player_id}'");
        $numero = "p".$n;
        $listechampi = undergrove::$instance->listechampi;

        //tie-breaker
        $aux1 = count(self::getObjectListFromDB( "SELECT id id FROM foret WHERE player_id ={$player_id} AND type = 'arbre'", true ));
        $aux2 = self::getObjectListFromDB( "SELECT carbone carbone FROM foret WHERE player_id ={$player_id} AND type = 'semi'", true );
        $aux2b = array_sum($aux2);
        
        
        $score_aux= 100*$aux1 + 1*$aux2b;
        self::DbQuery( "UPDATE player set player_score_aux = {$score_aux} WHERE player_id = '{$player_id}'" );

        /////// Objectif 1

        if(($this->getGameStateValue('compteurcarbone') >= 6) && ($this->getGameStateValue('compteurcarbone') < 12))
        {
            self::DbQuery( "UPDATE goal set {$numero} = {$numero} +1  WHERE card_type = 1" );   

        }
        if(($this->getGameStateValue('compteurcarbone') >= 12) && ($this->getGameStateValue('compteurcarbone') < 18))
        {
            self::DbQuery( "UPDATE goal set {$numero} = {$numero} +2  WHERE card_type = 1" );   

        }
        if(($this->getGameStateValue('compteurcarbone') >= 18) && ($this->getGameStateValue('compteurcarbone') < 24))
        {
            self::DbQuery( "UPDATE goal set {$numero} = {$numero} +3  WHERE card_type = 1" );   

        }
        if($this->getGameStateValue('compteurcarbone') >= 24)
        {
            self::DbQuery( "UPDATE goal set {$numero} = {$numero} +4  WHERE card_type = 1" );   

        }
        undergrove::$instance->setGameStateValue('compteurcarbone', 0);


        /////// Objectif 2 (rien à faire ici de plus)

        /////// Objectif 3 (rien à faire ici de plus)

    /*    /////// Objectif 4
        $calcul = 0;
        $champi = self::getObjectListFromDB( "SELECT card_location location FROM champignon WHERE card_location LIKE 'square%'", true );
        foreach ($champi as $square)
        {
            $explodesquare = explode("_", $square);
            $x = $explodesquare[1];
            $y = $explodesquare[2];
            $loc1 = "circle_".$x."_".$y;
            $loc2 = "circle_".($x+1)."_".$y;
            $loc3 = "circle_".($x+1)."_".($y+1);
            $loc4 = "circle_".($x)."_".($y+1);

            $count = count(self::getObjectListFromDB( "SELECT id id FROM foret WHERE player_id = '{$player_id}' AND (location = '{$loc1}' OR location = '{$loc2}' OR location = '{$loc3}' OR location = '{$loc4}')", true ));

            if ($count >= 1)
            {
                $calcul = $calcul +1;
            }
        }
        self::DbQuery( "UPDATE goal set {$numero} = {$calcul} WHERE card_type = 4" );
    */

        /////// Objectif 4
        $calcul = 0;
            
        $calcul1 = 0;
        $calcul2 = 0;
        $calcul3 = 0;
        $calcul4 = 0;
        $nbrejoueur = count((self::getObjectListFromDB( "SELECT player_id id FROM player", true )));
        if($nbrejoueur ==2)
        {
        $player_id1 = self::getUniqueValueFromDB("SELECT player_id FROM player WHERE player_no=1");
        $player_id2 = self::getUniqueValueFromDB("SELECT player_id FROM player WHERE player_no=2");
        }
        if($nbrejoueur ==3)
        {
        $player_id1 = self::getUniqueValueFromDB("SELECT player_id FROM player WHERE player_no=1");
        $player_id2 = self::getUniqueValueFromDB("SELECT player_id FROM player WHERE player_no=2");
        $player_id3 = self::getUniqueValueFromDB("SELECT player_id FROM player WHERE player_no=3");
        }
        if($nbrejoueur ==4)
        {
        $player_id1 = self::getUniqueValueFromDB("SELECT player_id FROM player WHERE player_no=1");
        $player_id2 = self::getUniqueValueFromDB("SELECT player_id FROM player WHERE player_no=2");
        $player_id3 = self::getUniqueValueFromDB("SELECT player_id FROM player WHERE player_no=3");
        $player_id4 = self::getUniqueValueFromDB("SELECT player_id FROM player WHERE player_no=4");
        }

        $champi = self::getObjectListFromDB( "SELECT card_location location FROM champignon WHERE card_location LIKE 'square%'", true );
        foreach ($champi as $square)
        {
            $explodesquare = explode("_", $square);
            $x = $explodesquare[1];
            $y = $explodesquare[2];
            $loc1 = "circle_".$x."_".$y;
            $loc2 = "circle_".($x+1)."_".$y;
            $loc3 = "circle_".($x+1)."_".($y+1);
            $loc4 = "circle_".($x)."_".($y+1);

            
            if ($nbrejoueur == 2)
            {
                $count1 = count(self::getObjectListFromDB( "SELECT id id FROM foret WHERE player_id = '{$player_id1}' AND (location = '{$loc1}' OR location = '{$loc2}' OR location = '{$loc3}' OR location = '{$loc4}')", true ));
                if ($count1 >= 1)
                {
                $calcul1 = $calcul1 +1;
                }
                $count2 = count(self::getObjectListFromDB( "SELECT id id FROM foret WHERE player_id = '{$player_id2}' AND (location = '{$loc1}' OR location = '{$loc2}' OR location = '{$loc3}' OR location = '{$loc4}')", true ));
                if ($count2 >= 1)
                {
                $calcul2 = $calcul2 +1;
                }
            }

            if ($nbrejoueur == 3)
            {
                $count1 = count(self::getObjectListFromDB( "SELECT id id FROM foret WHERE player_id = '{$player_id1}' AND (location = '{$loc1}' OR location = '{$loc2}' OR location = '{$loc3}' OR location = '{$loc4}')", true ));
                if ($count1 >= 1)
                {
                $calcul1 = $calcul1 +1;
                }
                $count2 = count(self::getObjectListFromDB( "SELECT id id FROM foret WHERE player_id = '{$player_id2}' AND (location = '{$loc1}' OR location = '{$loc2}' OR location = '{$loc3}' OR location = '{$loc4}')", true ));
                if ($count2 >= 1)
                {
                $calcul2 = $calcul2 +1;
                }
                $count3 = count(self::getObjectListFromDB( "SELECT id id FROM foret WHERE player_id = '{$player_id3}' AND (location = '{$loc1}' OR location = '{$loc2}' OR location = '{$loc3}' OR location = '{$loc4}')", true ));
                if ($count3 >= 1)
                {
                $calcul3 = $calcul3 +1;
                }
            }
            if ($nbrejoueur == 4)
            {
                $count1 = count(self::getObjectListFromDB( "SELECT id id FROM foret WHERE player_id = '{$player_id1}' AND (location = '{$loc1}' OR location = '{$loc2}' OR location = '{$loc3}' OR location = '{$loc4}')", true ));
                if ($count1 >= 1)
                {
                $calcul1 = $calcul1 +1;
                }
                $count2 = count(self::getObjectListFromDB( "SELECT id id FROM foret WHERE player_id = '{$player_id2}' AND (location = '{$loc1}' OR location = '{$loc2}' OR location = '{$loc3}' OR location = '{$loc4}')", true ));
                if ($count2 >= 1)
                {
                $calcul2 = $calcul2 +1;
                }
                $count3 = count(self::getObjectListFromDB( "SELECT id id FROM foret WHERE player_id = '{$player_id3}' AND (location = '{$loc1}' OR location = '{$loc2}' OR location = '{$loc3}' OR location = '{$loc4}')", true ));
                if ($count3 >= 1)
                {
                $calcul3 = $calcul3 +1;
                }
                $count4 = count(self::getObjectListFromDB( "SELECT id id FROM foret WHERE player_id = '{$player_id4}' AND (location = '{$loc1}' OR location = '{$loc2}' OR location = '{$loc3}' OR location = '{$loc4}')", true ));
                if ($count4 >= 1)
                {
                $calcul4 = $calcul4 +1;
                }
            }
        }
            
        
        $emplacementgoal = self::getUniqueValueFromDB("SELECT card_location FROM goal WHERE card_type = 4");
        self::DbQuery( "UPDATE goal set p1 = {$calcul1} WHERE card_type = 4" );
        self::DbQuery( "UPDATE goal set p2 = {$calcul2} WHERE card_type = 4" );
        self::DbQuery( "UPDATE goal set p3 = {$calcul3} WHERE card_type = 4" );
        self::DbQuery( "UPDATE goal set p4 = {$calcul4} WHERE card_type = 4" );
        if(($emplacementgoal!='deck')&&($emplacementgoal!='discard'))
        {
        undergrove::$instance->notifyAllPlayers("scoregoal4",'', array(
            
            'nbre' =>  $nbrejoueur,
            'score1' =>  $calcul1,
            'score2' =>  $calcul2,
            'score3' =>  $calcul3,
            'score4' =>  $calcul4,
            'emplacement' => $emplacementgoal,
            

            
        )
        );
        }
        /////// Objectif 5 
        $arbre = self::getUniqueValueFromDB("SELECT arbre FROM player WHERE player_id = '{$player_id}'");
        self::DbQuery( "UPDATE goal set {$numero} = 4-{$arbre} WHERE card_type = 5" );

        /////// Objectif 6 
        $calcul = 0;
        $semi = self::getObjectListFromDB( "SELECT location location FROM foret WHERE (type = 'semi' OR type ='arbre') AND player_id = '{$player_id}'", true );
        foreach ($semi as $circle)
        {
            $explodecircle = explode("_", $circle);
            $test = "\_".$explodecircle[1]."\_".$explodecircle[2];
            
            $count = count(self::getObjectListFromDB( "SELECT id id FROM foret WHERE player_id = '{$player_id}' AND type = 'racine' AND location LIKE '%$test'", true));
            

            if ($count >= 2)
            {
                $calcul = $calcul +1;
            }
        }
        self::DbQuery( "UPDATE goal set {$numero} = {$calcul} WHERE card_type = 6" );

        /////// Objectif 7
        $racine = self::getUniqueValueFromDB("SELECT racine FROM player WHERE player_id = '{$player_id}'");
        self::DbQuery( "UPDATE goal set {$numero} = 18-{$racine} WHERE card_type = 7" );

        /////// Objectif 8

        $test1 = $this->getGameStateValue('goalracine1');
        $test2 = $this->getGameStateValue('goalracine2');
        $test3 = $this->getGameStateValue('goalracine3');

        

        if (($test1 != 0) && ($test2 != 0) && ($test3 != 0))
        {
        $nbactb = $listechampi[$test1]["coutab"] + $listechampi[$test2]["coutab"] +$listechampi[$test3]["coutab"];
        $nbactp = $listechampi[$test1]["coutap"] + $listechampi[$test2]["coutap"] +$listechampi[$test3]["coutap"];
        $nbactg = $listechampi[$test1]["coutag"] + $listechampi[$test2]["coutag"] +$listechampi[$test3]["coutag"];
        $nbacty = $listechampi[$test1]["coutay"] + $listechampi[$test2]["coutay"] +$listechampi[$test3]["coutay"];
        if (($nbactb >= 2) || ($nbactp >= 2) || ($nbactg >= 2) || ($nbacty >= 2))
            {
                self::DbQuery( "UPDATE goal set {$numero} = {$numero} +1 WHERE card_type = 8" );
            }
        
        }

        if (($test1 !=0) && ($test2 !=0) && ($test3 == 0))
        {
        $nbactb = $listechampi[$test1]["coutab"] + $listechampi[$test2]["coutab"];
        $nbactp = $listechampi[$test1]["coutap"] + $listechampi[$test2]["coutap"];
        $nbactg = $listechampi[$test1]["coutag"] + $listechampi[$test2]["coutag"];
        $nbacty = $listechampi[$test1]["coutay"] + $listechampi[$test2]["coutay"];

        
        if (($nbactb >= 2) || ($nbactp >= 2) || ($nbactg >= 2) || ($nbacty >= 2))
            {
                self::DbQuery( "UPDATE goal set {$numero} = {$numero} +1 WHERE card_type = 8" );
            }
        
        }
        undergrove::$instance->setGameStateValue('goalracine1', 0);
        undergrove::$instance->setGameStateValue('goalracine2', 0);
        undergrove::$instance->setGameStateValue('goalracine3', 0);

        /////// Objectif 9 et 10

        $semijoueur = self::getObjectListFromDB( "SELECT location location FROM foret WHERE (type = 'semi' OR type = 'arbre') AND player_id = '{$player_id}'", true );
        $totalChiffresPairs = 0;
        $totalChiffresImpairs = 0;

        

        foreach($semijoueur as $locationsemijoueur)
        {
            
            
            $ChiffresPairs = self::getUniqueValueFromDB("SELECT 
            SUM(CASE WHEN vp_racine1 % 2 = 0 AND vp_racine1 != 0 THEN 1 ELSE 0 END) + 
            SUM(CASE WHEN vp_racine2 % 2 = 0 AND vp_racine2 != 0 THEN 1 ELSE 0 END) + 
            SUM(CASE WHEN vp_racine3 % 2 = 0 AND vp_racine3 != 0 THEN 1 ELSE 0 END) + 
            SUM(CASE WHEN vp_racine4 % 2 = 0 AND vp_racine4 != 0 THEN 1 ELSE 0 END) AS chiffres_pairs 
            FROM foret WHERE location = '{$locationsemijoueur}'");
           
           
            $totalChiffresPairs = $totalChiffresPairs + $ChiffresPairs;
            

            $ChiffresImpairs = self::getUniqueValueFromDB("SELECT 
            SUM(CASE WHEN vp_racine1 % 2 <> 0 AND vp_racine1 != 0 THEN 1 ELSE 0 END) + 
            SUM(CASE WHEN vp_racine2 % 2 <> 0 AND vp_racine2 != 0 THEN 1 ELSE 0 END) + 
            SUM(CASE WHEN vp_racine3 % 2 <> 0 AND vp_racine3 != 0 THEN 1 ELSE 0 END) + 
            SUM(CASE WHEN vp_racine4 % 2 <> 0 AND vp_racine4 != 0 THEN 1 ELSE 0 END) AS chiffres_impairs 
            FROM foret WHERE location = '{$locationsemijoueur}'");
            $totalChiffresImpairs = $totalChiffresImpairs + $ChiffresImpairs;
        
        }

        $countpair=0;

            $special = self::getObjectListFromDB( "SELECT card_location location, card_type type FROM champignon WHERE (card_type=41 OR card_type=42 OR card_type=43 OR card_type=44) AND card_location LIKE 'square%'");
            foreach($special as $square)
            { 
                
                $recherche = self::getUniqueValueFromDB("SELECT score FROM champispecial WHERE type = {$square['type']}");
                
                if($recherche == 0)
                {
                    
                    $expodesquare = explode ("_",$square['location']);
                
                    $recherche2 = "minisquare_".$expodesquare[1]."_".$expodesquare[2];
                    $countpair = count(self::getObjectListFromDB( "SELECT id id FROM foret WHERE location LIKE '$recherche2%' AND player_id={$player_id}", true ));
                    $totalChiffresPairs = $totalChiffresPairs + $countpair;
                    
                }
                
                
            }

        
        self::DbQuery( "UPDATE goal set {$numero} = {$totalChiffresPairs} WHERE card_type = 9" );
        self::DbQuery( "UPDATE goal set {$numero} = {$totalChiffresImpairs} WHERE card_type = 10" );

        /////// Objectif 11 et 12

        $racinejoueur = self::getObjectListFromDB( "SELECT location location FROM foret WHERE type = 'racine' AND player_id = '{$player_id}'", true );
        $compteurcolor = 0;
        $compteuranimal = 0;
        foreach($racinejoueur as $locationracinejoueur)
        {
            $exploderacine = explode("_", $locationracinejoueur);
            $square = "square_".$exploderacine[1]."_".$exploderacine[2];
            $type = self::getUniqueValueFromDB("SELECT card_type FROM champignon WHERE card_location = '{$square}'");
            if ($listechampi[$type]["color"] == 1)
            {
                $compteurcolor = $compteurcolor + 1;
            }

            if ($listechampi[$type]["animal"] == 1)
            {
                $compteuranimal = $compteuranimal + 1;
            }

        self::DbQuery( "UPDATE goal set {$numero} = {$compteurcolor} WHERE card_type = 11" );
        self::DbQuery( "UPDATE goal set {$numero} = {$compteuranimal} WHERE card_type = 12" );
            

        }


        /////// Maj des valeurs du joueur actif

        $scoregoal1 = self::getUniqueValueFromDB("SELECT {$numero} FROM goal WHERE card_location = 'goal_1'");
        $scoregoal2 = self::getUniqueValueFromDB("SELECT {$numero} FROM goal WHERE card_location = 'goal_2'");
        $scoregoal3 = self::getUniqueValueFromDB("SELECT {$numero} FROM goal WHERE card_location = 'goal_3'");

        undergrove::$instance->notifyAllPlayers("scoregoal",'', array(
            
            'scoregoal1' =>  $scoregoal1,
            'scoregoal2' =>  $scoregoal2,
            'scoregoal3' =>  $scoregoal3,
            'no' => $n,

            
        )
        );

    $this->EndGame();

    }



    function GoalTrack2()    //sans l'objectif 1 juste pour NormalTurn (pour ne pas remettre a zero le compteur carbone si le joueur a utilisé un tuile bonus carbone)
    {
        $player_id = $this->getActivePlayerId();
        $n = self::getUniqueValueFromDB("SELECT player_no FROM player WHERE player_id = '{$player_id}'");
        $numero = "p".$n;
        $listechampi = undergrove::$instance->listechampi;

        //tie-breaker
        $aux1 = count(self::getObjectListFromDB( "SELECT id id FROM foret WHERE player_id ={$player_id} AND type = 'arbre'", true ));
        $aux2 = self::getObjectListFromDB( "SELECT carbone carbone FROM foret WHERE player_id ={$player_id} AND type = 'semi'", true );
        $aux2b = array_sum($aux2);
        
        
        $score_aux= 100*$aux1 + 1*$aux2b;
        self::DbQuery( "UPDATE player set player_score_aux = {$score_aux} WHERE player_id = '{$player_id}'" );

        /////// Objectif 1 (supprimé de cette fonction)

        


        /////// Objectif 2 (rien à faire ici de plus)

        /////// Objectif 3 (rien à faire ici de plus)

    /*    /////// Objectif 4
        $calcul = 0;
        $champi = self::getObjectListFromDB( "SELECT card_location location FROM champignon WHERE card_location LIKE 'square%'", true );
        foreach ($champi as $square)
        {
            $explodesquare = explode("_", $square);
            $x = $explodesquare[1];
            $y = $explodesquare[2];
            $loc1 = "circle_".$x."_".$y;
            $loc2 = "circle_".($x+1)."_".$y;
            $loc3 = "circle_".($x+1)."_".($y+1);
            $loc4 = "circle_".($x)."_".($y+1);

            $count = count(self::getObjectListFromDB( "SELECT id id FROM foret WHERE player_id = '{$player_id}' AND (location = '{$loc1}' OR location = '{$loc2}' OR location = '{$loc3}' OR location = '{$loc4}')", true ));

            if ($count >= 1)
            {
                $calcul = $calcul +1;
            }
        }
        self::DbQuery( "UPDATE goal set {$numero} = {$calcul} WHERE card_type = 4" );
    */

        /////// Objectif 4
        $calcul = 0;
            
        $calcul1 = 0;
        $calcul2 = 0;
        $calcul3 = 0;
        $calcul4 = 0;
        $nbrejoueur = count((self::getObjectListFromDB( "SELECT player_id id FROM player", true )));
        if($nbrejoueur ==2)
        {
        $player_id1 = self::getUniqueValueFromDB("SELECT player_id FROM player WHERE player_no=1");
        $player_id2 = self::getUniqueValueFromDB("SELECT player_id FROM player WHERE player_no=2");
        }
        if($nbrejoueur ==3)
        {
        $player_id1 = self::getUniqueValueFromDB("SELECT player_id FROM player WHERE player_no=1");
        $player_id2 = self::getUniqueValueFromDB("SELECT player_id FROM player WHERE player_no=2");
        $player_id3 = self::getUniqueValueFromDB("SELECT player_id FROM player WHERE player_no=3");
        }
        if($nbrejoueur ==4)
        {
        $player_id1 = self::getUniqueValueFromDB("SELECT player_id FROM player WHERE player_no=1");
        $player_id2 = self::getUniqueValueFromDB("SELECT player_id FROM player WHERE player_no=2");
        $player_id3 = self::getUniqueValueFromDB("SELECT player_id FROM player WHERE player_no=3");
        $player_id4 = self::getUniqueValueFromDB("SELECT player_id FROM player WHERE player_no=4");
        }

        $champi = self::getObjectListFromDB( "SELECT card_location location FROM champignon WHERE card_location LIKE 'square%'", true );
        foreach ($champi as $square)
        {
            $explodesquare = explode("_", $square);
            $x = $explodesquare[1];
            $y = $explodesquare[2];
            $loc1 = "circle_".$x."_".$y;
            $loc2 = "circle_".($x+1)."_".$y;
            $loc3 = "circle_".($x+1)."_".($y+1);
            $loc4 = "circle_".($x)."_".($y+1);

            
            if ($nbrejoueur == 2)
            {
                $count1 = count(self::getObjectListFromDB( "SELECT id id FROM foret WHERE player_id = '{$player_id1}' AND (location = '{$loc1}' OR location = '{$loc2}' OR location = '{$loc3}' OR location = '{$loc4}')", true ));
                if ($count1 >= 1)
                {
                $calcul1 = $calcul1 +1;
                }
                $count2 = count(self::getObjectListFromDB( "SELECT id id FROM foret WHERE player_id = '{$player_id2}' AND (location = '{$loc1}' OR location = '{$loc2}' OR location = '{$loc3}' OR location = '{$loc4}')", true ));
                if ($count2 >= 1)
                {
                $calcul2 = $calcul2 +1;
                }
            }

            if ($nbrejoueur == 3)
            {
                $count1 = count(self::getObjectListFromDB( "SELECT id id FROM foret WHERE player_id = '{$player_id1}' AND (location = '{$loc1}' OR location = '{$loc2}' OR location = '{$loc3}' OR location = '{$loc4}')", true ));
                if ($count1 >= 1)
                {
                $calcul1 = $calcul1 +1;
                }
                $count2 = count(self::getObjectListFromDB( "SELECT id id FROM foret WHERE player_id = '{$player_id2}' AND (location = '{$loc1}' OR location = '{$loc2}' OR location = '{$loc3}' OR location = '{$loc4}')", true ));
                if ($count2 >= 1)
                {
                $calcul2 = $calcul2 +1;
                }
                $count3 = count(self::getObjectListFromDB( "SELECT id id FROM foret WHERE player_id = '{$player_id3}' AND (location = '{$loc1}' OR location = '{$loc2}' OR location = '{$loc3}' OR location = '{$loc4}')", true ));
                if ($count3 >= 1)
                {
                $calcul3 = $calcul3 +1;
                }
            }
            if ($nbrejoueur == 4)
            {
                $count1 = count(self::getObjectListFromDB( "SELECT id id FROM foret WHERE player_id = '{$player_id1}' AND (location = '{$loc1}' OR location = '{$loc2}' OR location = '{$loc3}' OR location = '{$loc4}')", true ));
                if ($count1 >= 1)
                {
                $calcul1 = $calcul1 +1;
                }
                $count2 = count(self::getObjectListFromDB( "SELECT id id FROM foret WHERE player_id = '{$player_id2}' AND (location = '{$loc1}' OR location = '{$loc2}' OR location = '{$loc3}' OR location = '{$loc4}')", true ));
                if ($count2 >= 1)
                {
                $calcul2 = $calcul2 +1;
                }
                $count3 = count(self::getObjectListFromDB( "SELECT id id FROM foret WHERE player_id = '{$player_id3}' AND (location = '{$loc1}' OR location = '{$loc2}' OR location = '{$loc3}' OR location = '{$loc4}')", true ));
                if ($count3 >= 1)
                {
                $calcul3 = $calcul3 +1;
                }
                $count4 = count(self::getObjectListFromDB( "SELECT id id FROM foret WHERE player_id = '{$player_id4}' AND (location = '{$loc1}' OR location = '{$loc2}' OR location = '{$loc3}' OR location = '{$loc4}')", true ));
                if ($count4 >= 1)
                {
                $calcul4 = $calcul4 +1;
                }
            }
        }
            
        
        $emplacementgoal = self::getUniqueValueFromDB("SELECT card_location FROM goal WHERE card_type = 4");
        self::DbQuery( "UPDATE goal set p1 = {$calcul1} WHERE card_type = 4" );
        self::DbQuery( "UPDATE goal set p2 = {$calcul2} WHERE card_type = 4" );
        self::DbQuery( "UPDATE goal set p3 = {$calcul3} WHERE card_type = 4" );
        self::DbQuery( "UPDATE goal set p4 = {$calcul4} WHERE card_type = 4" );
        if(($emplacementgoal!='deck')&&($emplacementgoal!='discard'))
        {
        undergrove::$instance->notifyAllPlayers("scoregoal4",'', array(
            
            'nbre' =>  $nbrejoueur,
            'score1' =>  $calcul1,
            'score2' =>  $calcul2,
            'score3' =>  $calcul3,
            'score4' =>  $calcul4,
            'emplacement' => $emplacementgoal,
            

            
        )
        );
        }
        /////// Objectif 5 
        $arbre = self::getUniqueValueFromDB("SELECT arbre FROM player WHERE player_id = '{$player_id}'");
        self::DbQuery( "UPDATE goal set {$numero} = 4-{$arbre} WHERE card_type = 5" );

        /////// Objectif 6 
        $calcul = 0;
        $semi = self::getObjectListFromDB( "SELECT location location FROM foret WHERE (type = 'semi' OR type ='arbre') AND player_id = '{$player_id}'", true );
        foreach ($semi as $circle)
        {
            $explodecircle = explode("_", $circle);
            $test = "\_".$explodecircle[1]."\_".$explodecircle[2];
            
            $count = count(self::getObjectListFromDB( "SELECT id id FROM foret WHERE player_id = '{$player_id}' AND type = 'racine' AND location LIKE '%$test'", true));
            

            if ($count >= 2)
            {
                $calcul = $calcul +1;
            }
        }
        self::DbQuery( "UPDATE goal set {$numero} = {$calcul} WHERE card_type = 6" );

        /////// Objectif 7
        $racine = self::getUniqueValueFromDB("SELECT racine FROM player WHERE player_id = '{$player_id}'");
        self::DbQuery( "UPDATE goal set {$numero} = 18-{$racine} WHERE card_type = 7" );

        /////// Objectif 8

        $test1 = $this->getGameStateValue('goalracine1');
        $test2 = $this->getGameStateValue('goalracine2');
        $test3 = $this->getGameStateValue('goalracine3');

        

        if (($test1 != 0) && ($test2 != 0) && ($test3 != 0))
        {
        $nbactb = $listechampi[$test1]["coutab"] + $listechampi[$test2]["coutab"] +$listechampi[$test3]["coutab"];
        $nbactp = $listechampi[$test1]["coutap"] + $listechampi[$test2]["coutap"] +$listechampi[$test3]["coutap"];
        $nbactg = $listechampi[$test1]["coutag"] + $listechampi[$test2]["coutag"] +$listechampi[$test3]["coutag"];
        $nbacty = $listechampi[$test1]["coutay"] + $listechampi[$test2]["coutay"] +$listechampi[$test3]["coutay"];
        if (($nbactb >= 2) || ($nbactp >= 2) || ($nbactg >= 2) || ($nbacty >= 2))
            {
                self::DbQuery( "UPDATE goal set {$numero} = {$numero} +1 WHERE card_type = 8" );
            }
        
        }

        if (($test1 !=0) && ($test2 !=0) && ($test3 == 0))
        {
        $nbactb = $listechampi[$test1]["coutab"] + $listechampi[$test2]["coutab"];
        $nbactp = $listechampi[$test1]["coutap"] + $listechampi[$test2]["coutap"];
        $nbactg = $listechampi[$test1]["coutag"] + $listechampi[$test2]["coutag"];
        $nbacty = $listechampi[$test1]["coutay"] + $listechampi[$test2]["coutay"];

        
        if (($nbactb >= 2) || ($nbactp >= 2) || ($nbactg >= 2) || ($nbacty >= 2))
            {
                self::DbQuery( "UPDATE goal set {$numero} = {$numero} +1 WHERE card_type = 8" );
            }
        
        }
        undergrove::$instance->setGameStateValue('goalracine1', 0);
        undergrove::$instance->setGameStateValue('goalracine2', 0);
        undergrove::$instance->setGameStateValue('goalracine3', 0);

        /////// Objectif 9 et 10

        $semijoueur = self::getObjectListFromDB( "SELECT location location FROM foret WHERE (type = 'semi' OR type = 'arbre') AND player_id = '{$player_id}'", true );
        $totalChiffresPairs = 0;
        $totalChiffresImpairs = 0;

        

        foreach($semijoueur as $locationsemijoueur)
        {
            
            
            $ChiffresPairs = self::getUniqueValueFromDB("SELECT 
            SUM(CASE WHEN vp_racine1 % 2 = 0 AND vp_racine1 != 0 THEN 1 ELSE 0 END) + 
            SUM(CASE WHEN vp_racine2 % 2 = 0 AND vp_racine2 != 0 THEN 1 ELSE 0 END) + 
            SUM(CASE WHEN vp_racine3 % 2 = 0 AND vp_racine3 != 0 THEN 1 ELSE 0 END) + 
            SUM(CASE WHEN vp_racine4 % 2 = 0 AND vp_racine4 != 0 THEN 1 ELSE 0 END) AS chiffres_pairs 
            FROM foret WHERE location = '{$locationsemijoueur}'");
           
           
            $totalChiffresPairs = $totalChiffresPairs + $ChiffresPairs;
            

            $ChiffresImpairs = self::getUniqueValueFromDB("SELECT 
            SUM(CASE WHEN vp_racine1 % 2 <> 0 AND vp_racine1 != 0 THEN 1 ELSE 0 END) + 
            SUM(CASE WHEN vp_racine2 % 2 <> 0 AND vp_racine2 != 0 THEN 1 ELSE 0 END) + 
            SUM(CASE WHEN vp_racine3 % 2 <> 0 AND vp_racine3 != 0 THEN 1 ELSE 0 END) + 
            SUM(CASE WHEN vp_racine4 % 2 <> 0 AND vp_racine4 != 0 THEN 1 ELSE 0 END) AS chiffres_impairs 
            FROM foret WHERE location = '{$locationsemijoueur}'");
            $totalChiffresImpairs = $totalChiffresImpairs + $ChiffresImpairs;
        
        }

        $countpair=0;

            $special = self::getObjectListFromDB( "SELECT card_location location, card_type type FROM champignon WHERE (card_type=41 OR card_type=42 OR card_type=43 OR card_type=44) AND card_location LIKE 'square%'");
            foreach($special as $square)
            { 
                
                $recherche = self::getUniqueValueFromDB("SELECT score FROM champispecial WHERE type = {$square['type']}");
                
                if($recherche == 0)
                {
                    
                    $expodesquare = explode ("_",$square['location']);
                
                    $recherche2 = "minisquare_".$expodesquare[1]."_".$expodesquare[2];
                    $countpair = count(self::getObjectListFromDB( "SELECT id id FROM foret WHERE location LIKE '$recherche2%' AND player_id={$player_id}", true ));
                    
                    $totalChiffresPairs = $totalChiffresPairs + $countpair;
                }
                
                
            }

        

        self::DbQuery( "UPDATE goal set {$numero} = {$totalChiffresPairs} WHERE card_type = 9" );
        self::DbQuery( "UPDATE goal set {$numero} = {$totalChiffresImpairs} WHERE card_type = 10" );

        /////// Objectif 11 et 12

        $racinejoueur = self::getObjectListFromDB( "SELECT location location FROM foret WHERE type = 'racine' AND player_id = '{$player_id}'", true );
        $compteurcolor = 0;
        $compteuranimal = 0;
        foreach($racinejoueur as $locationracinejoueur)
        {
            $exploderacine = explode("_", $locationracinejoueur);
            $square = "square_".$exploderacine[1]."_".$exploderacine[2];
            $type = self::getUniqueValueFromDB("SELECT card_type FROM champignon WHERE card_location = '{$square}'");
            if ($listechampi[$type]["color"] == 1)
            {
                $compteurcolor = $compteurcolor + 1;
            }

            if ($listechampi[$type]["animal"] == 1)
            {
                $compteuranimal = $compteuranimal + 1;
            }

        self::DbQuery( "UPDATE goal set {$numero} = {$compteurcolor} WHERE card_type = 11" );
        self::DbQuery( "UPDATE goal set {$numero} = {$compteuranimal} WHERE card_type = 12" );
            

        }


        /////// Maj des valeurs du joueur actif

        $scoregoal1 = self::getUniqueValueFromDB("SELECT {$numero} FROM goal WHERE card_location = 'goal_1'");
        $scoregoal2 = self::getUniqueValueFromDB("SELECT {$numero} FROM goal WHERE card_location = 'goal_2'");
        $scoregoal3 = self::getUniqueValueFromDB("SELECT {$numero} FROM goal WHERE card_location = 'goal_3'");

        undergrove::$instance->notifyAllPlayers("scoregoal",'', array(
            
            'scoregoal1' =>  $scoregoal1,
            'scoregoal2' =>  $scoregoal2,
            'scoregoal3' =>  $scoregoal3,
            'no' => $n,

            
        )
        );

    $this->EndGame();

    }

       
    function getLogsRessource( $type ) 
    {
		if($type == 1)
        {return "<div class='icone_n' title=''></div>";}
        if($type == 2)
        {return "<div class='icone_p' title=''></div>";}
        if($type == 3)
        {return "<div class='icone_k' title=''></div>";}
        if($type == 4)
        {return "<div class='icone_c' title=''></div>";}
        
    }

    function getLogsActivation( $type ) 
    {
		if($type == 1)
        {return "<div class='button_activation_b' title=''></div>";}
        if($type == 2)
        {return "<div class='button_activation_p' title=''></div>";}
        if($type == 3)
        {return "<div class='button_activation_g' title=''></div>";}
        if($type == 4)
        {return "<div class='button_activation_y' title=''></div>";}
        

    }

    function CheckEnd()
    {
        $player_id = $this->getActivePlayerId();
        $final = self::getUniqueValueFromDB("SELECT final FROM player WHERE player_id={$player_id}");
        
        if ((undergrove::$instance->getGameStateValue('final') == 1) && ($final == 0))
        {
            self::DbQuery( "UPDATE player set final = 1 WHERE player_id={$player_id}" );
                        
        }

        if(($final == 1)||($final == 2))
        {
            self::DbQuery( "UPDATE player set final = 2 WHERE player_id={$player_id}" );
            $countplayer = count(self::getObjectListFromDB( "SELECT player_id id FROM player", true ));
            $countfinal = count(self::getObjectListFromDB( "SELECT player_id id FROM player WHERE final = 2", true ));
            if ($countplayer == $countfinal)
            {
                $this->GoalTrack();
                $this->EndGame(1);
                $this->AffichageScore();
                undergrove::$instance->notifyAllPlayers('finmessagealerte','', array(
                    
                    )
                    );
                $this->gamestate->nextState( 'end' ); 
            }

        }


    }

    function AffichageScore()
    {
        undergrove::$instance->notifyAllPlayers("affichagescore",'', array(
            
            
        )
        );
    }


    function EndGame($end=0)
    {
        $res  = array();
        $nbre = count(self::getObjectListFromDB( "SELECT player_id id FROM player", true ));

        self::DbQuery( "UPDATE player set player_score = 0" );
        $players = self::getObjectListFromDB( "SELECT player_id id FROM player", true );
        foreach($players as $n)
        {
            $this->setStat( 0, 'racine', $n );
            $this->setStat( 0, 'tiles', $n );
            $this->setStat( 0, 'ressources', $n );
            $this->setStat( 0, 'goal1', $n );
            $this->setStat( 0, 'goal2', $n );
            $this->setStat( 0, 'goal3', $n );
        }
        
        $tableaufinalracine = array();
        $tableaufinaltotalracine = array();
        $tableaufinalbonus = array();
        $tableaufinalressources = array();
        $tableaufinalgoals = array();
        $tableaufinaltotalgoals = array();
        $tableaufinaltotal = array();
        
        
        ///////// score racine

        $foret = self::getObjectListFromDB( "SELECT type type, carbone carbone, player_id id, vp_racine1 vp1, vp_racine2 vp2, vp_racine3 vp3, vp_racine4 vp4 FROM foret WHERE type = 'semi' or type ='arbre'" );
        foreach($foret as $indice)
        {
            $id = $indice['id'];

            if($indice['type'] == 'arbre')
            {
                
                $vp = $indice['vp1'] + $indice['vp2'] + $indice['vp3'] + $indice['vp4'];
                self::DbQuery( "UPDATE player set player_score = player_score + {$vp} WHERE player_id={$id}" );
                $this->incStat($vp, 'racine', $id);
            }

            if ($indice['type'] == 'semi')

            {
                $tableau = [$indice['vp1'], $indice['vp2'], $indice['vp3'], $indice['vp4']];
                $carbone = $indice['carbone'];
                rsort($tableau);
                if ($carbone == 0)
                {
                    $vp = 0;
                }
                if ($carbone == 1)
                {
                    $vp = $tableau[0];
                }

                if ($carbone == 2)
                {
                    $vp = $tableau[0] + $tableau[1];
                }

                if ($carbone == 3)
                {
                    $vp = $tableau[0] + $tableau[1] + $tableau[2];

                }

                if ($carbone == 4)
                {
                    $vp = $tableau[0] + $tableau[1] + $tableau[2] + $tableau[3];
                }

                self::DbQuery( "UPDATE player set player_score = player_score + {$vp} WHERE player_id={$id}" );
                $this->incStat($vp, 'racine', $id);
                
                
            }

            $position = self::getUniqueValueFromDB("SELECT player_no FROM player WHERE player_id={$id}");
            $tableaufinalracine[$position][] = $vp;
            $tableaufinaltotalracine[$position] = $this->getStat('racine', $id);
            
        }

        undergrove::$instance->notifyAllPlayers("padracine",'', array(
            
            'tableaufinalracine' => $tableaufinalracine,
            'tableaufinaltotalracine' => $tableaufinaltotalracine,
        )
        );
        
        

        ///////// score bonus

        $bonus = self::getObjectListFromDB( "SELECT player_id id, bonus_score bonus FROM player" );
        foreach($bonus as $indice2)
        {
            $id = $indice2['id'];
            $vp = $indice2['bonus'];

            if($end ==1)
            {
            self::DbQuery( "UPDATE player set player_score = player_score + {$vp} WHERE player_id={$id}" );
            }
            $this->setStat($vp, 'tiles', $id);
            
            $position = self::getUniqueValueFromDB("SELECT player_no FROM player WHERE player_id={$id}");
            $tableaufinalbonus[$position][] = $vp;
            
        }

        undergrove::$instance->notifyAllPlayers("padbonus",'', array(
            
            'tableaufinalbonus' => $tableaufinalbonus,
            
        )
        );
        
        ////// score ressources

        $ressource = self::getObjectListFromDB( "SELECT player_id id, carbone c, azote a, phosphore p, potassium k FROM player" );
        foreach($ressource as $indice3)
        {
            $id = $indice3['id'];
            $vp = ($indice3['c']+$indice3['a']+$indice3['p']+$indice3['k'])/2;
            $vp = floor($vp);

            self::DbQuery( "UPDATE player set player_score = player_score + {$vp} WHERE player_id={$id}" );
            $this->setStat($vp, 'ressources', $id);

            $position = self::getUniqueValueFromDB("SELECT player_no FROM player WHERE player_id={$id}");
            $tableaufinalressources[$position][] = $vp;

        }

        undergrove::$instance->notifyAllPlayers("padressources",'', array(
            
            'tableaufinalressources' => $tableaufinalressources,
            
        )
        );
        
        ////// score goals
        
        $goals = self::getObjectListFromDB( "SELECT card_location goal, card_type type, p1 p1, p2 p2, p3 p3, p4 p4 FROM goal WHERE card_location = 'goal_1' or card_location = 'goal_2' or card_location = 'goal_3'" );
        foreach($goals as $indice4)
        {
            $score = array();
            $score = [$indice4['p1'], $indice4['p2'], $indice4['p3'], $indice4['p4']];

            if($indice4['goal']== 'goal_1')
            {
                if($indice4['type'] == 1)
                {
                    $scoregoal1 = $this->goal1($score);
                }
                if($indice4['type'] == 2)
                {
                    $scoregoal1 = $this->goalranking($score);
                }
                if($indice4['type'] == 3)
                {
                    $scoregoal1 = $this->goal3($score);
                }
                if($indice4['type'] == 4)
                {
                    $scoregoal1 = $this->goalranking($score);
                }
                if($indice4['type'] == 5)
                {
                    $scoregoal1 = $this->goal5($score);
                }
                if($indice4['type'] == 6)
                {
                    $scoregoal1 = $this->goal6($score);
                }
                if($indice4['type'] == 7)
                {
                    $scoregoal1 = $this->goalranking($score);
                }
                if($indice4['type'] == 8)
                {
                    $scoregoal1 = $this->goal8($score);
                }
                if($indice4['type'] == 9)
                {
                    $scoregoal1 = $this->goalranking($score);
                }
                if($indice4['type'] == 10)
                {
                    $scoregoal1 = $this->goalranking($score);
                }
                if($indice4['type'] == 11)
                {
                    $scoregoal1 = $this->goalranking($score);
                }
                if($indice4['type'] == 12)
                {
                    $scoregoal1 = $this->goalranking($score);
                }
            }
            
            if($indice4['goal']== 'goal_2')
            {
                if($indice4['type'] == 1)
                {
                    $scoregoal2 = $this->goal1($score);
                }
                if($indice4['type'] == 2)
                {
                    $scoregoal2 = $this->goalranking($score);
                }
                if($indice4['type'] == 3)
                {
                    $scoregoal2 = $this->goal3($score);
                }
                if($indice4['type'] == 4)
                {
                    $scoregoal2 = $this->goalranking($score);
                }
                if($indice4['type'] == 5)
                {
                    $scoregoal2 = $this->goal5($score);
                }
                if($indice4['type'] == 6)
                {
                    $scoregoal2 = $this->goal6($score);
                }
                if($indice4['type'] == 7)
                {
                    $scoregoal2 = $this->goalranking($score);
                }
                if($indice4['type'] == 8)
                {
                    $scoregoal2 = $this->goal8($score);
                }
                if($indice4['type'] == 9)
                {
                    $scoregoal2 = $this->goalranking($score);
                }
                if($indice4['type'] == 10)
                {
                    $scoregoal2 = $this->goalranking($score);
                }
                if($indice4['type'] == 11)
                {
                    $scoregoal2 = $this->goalranking($score);
                }
                if($indice4['type'] == 12)
                {
                    $scoregoal2 = $this->goalranking($score);
                }
            }

            if($indice4['goal']== 'goal_3')
            {
                if($indice4['type'] == 1)
                {
                    $scoregoal3 = $this->goal1($score);
                }
                if($indice4['type'] == 2)
                {
                    $scoregoal3 = $this->goalranking($score);
                }
                if($indice4['type'] == 3)
                {
                    $scoregoal3 = $this->goal3($score);
                }
                if($indice4['type'] == 4)
                {
                    $scoregoal3 = $this->goalranking($score);
                }
                if($indice4['type'] == 5)
                {
                    $scoregoal3 = $this->goal5($score);
                }
                if($indice4['type'] == 6)
                {
                    $scoregoal3 = $this->goal6($score);
                }
                if($indice4['type'] == 7)
                {
                    $scoregoal3 = $this->goalranking($score);
                }
                if($indice4['type'] == 8)
                {
                    $scoregoal3 = $this->goal8($score);
                }
                if($indice4['type'] == 9)
                {
                    $scoregoal3 = $this->goalranking($score);
                }
                if($indice4['type'] == 10)
                {
                    $scoregoal3 = $this->goalranking($score);
                }
                if($indice4['type'] == 11)
                {
                    $scoregoal3 = $this->goalranking($score);
                }
                if($indice4['type'] == 12)
                {
                    $scoregoal3 = $this->goalranking($score);
                }
            }
            
        }

        
        if ($nbre == 2)
        {
            $vp1 = $scoregoal1[0] + $scoregoal2[0] + $scoregoal3[0];
            self::DbQuery( "UPDATE player set player_score = player_score + {$vp1} WHERE player_no=1" );
            $id = self::getUniqueValueFromDB("SELECT player_id FROM player WHERE player_no=1");
            $this->setStat($scoregoal1[0], 'goal1', $id);
            $tableaufinalgoals[1][]=$scoregoal1[0];
            $this->setStat($scoregoal2[0], 'goal2', $id);
            $tableaufinalgoals[1][]=$scoregoal2[0];
            $this->setStat($scoregoal3[0], 'goal3', $id);
            $tableaufinalgoals[1][]=$scoregoal3[0];
            $tableaufinaltotalgoals[1]=$vp1;
            $vp2 = $scoregoal1[1] + $scoregoal2[1] + $scoregoal3[1];
            self::DbQuery( "UPDATE player set player_score = player_score + {$vp2} WHERE player_no=2" );
            $id = self::getUniqueValueFromDB("SELECT player_id FROM player WHERE player_no=2");
            $this->setStat($scoregoal1[1], 'goal1', $id);
            $tableaufinalgoals[2][]=$scoregoal1[1];
            $this->setStat($scoregoal2[1], 'goal2', $id);
            $tableaufinalgoals[2][]=$scoregoal2[1];
            $this->setStat($scoregoal3[1], 'goal3', $id);
            $tableaufinalgoals[2][]=$scoregoal3[1];
            $tableaufinaltotalgoals[2]=$vp2;

                        
        }
        if ($nbre == 3)
        {
            $vp1 = $scoregoal1[0] + $scoregoal2[0] + $scoregoal3[0];
            self::DbQuery( "UPDATE player set player_score = player_score + {$vp1} WHERE player_no=1" );
            $id = self::getUniqueValueFromDB("SELECT player_id FROM player WHERE player_no=1");
            $this->setStat($scoregoal1[0], 'goal1', $id);
            $tableaufinalgoals[1][]=$scoregoal1[0];
            $this->setStat($scoregoal2[0], 'goal2', $id);
            $tableaufinalgoals[1][]=$scoregoal2[0];
            $this->setStat($scoregoal3[0], 'goal3', $id);
            $tableaufinalgoals[1][]=$scoregoal3[0];
            $tableaufinaltotalgoals[1]=$vp1;
            $vp2 = $scoregoal1[1] + $scoregoal2[1] + $scoregoal3[1];
            self::DbQuery( "UPDATE player set player_score = player_score + {$vp2} WHERE player_no=2" );
            $id = self::getUniqueValueFromDB("SELECT player_id FROM player WHERE player_no=2");
            $this->setStat($scoregoal1[1], 'goal1', $id);
            $tableaufinalgoals[2][]=$scoregoal1[1];
            $this->setStat($scoregoal2[1], 'goal2', $id);
            $tableaufinalgoals[2][]=$scoregoal2[1];
            $this->setStat($scoregoal3[1], 'goal3', $id);
            $tableaufinalgoals[2][]=$scoregoal3[1];
            $tableaufinaltotalgoals[2]=$vp2;
            $vp3 = $scoregoal1[2] + $scoregoal2[2] + $scoregoal3[2];
            self::DbQuery( "UPDATE player set player_score = player_score + {$vp3} WHERE player_no=3" );
            $id = self::getUniqueValueFromDB("SELECT player_id FROM player WHERE player_no=3");
            $this->setStat($scoregoal1[2], 'goal1', $id);
            $tableaufinalgoals[3][]=$scoregoal1[2];
            $this->setStat($scoregoal2[2], 'goal2', $id);
            $tableaufinalgoals[3][]=$scoregoal2[2];
            $this->setStat($scoregoal3[2], 'goal3', $id);
            $tableaufinalgoals[3][]=$scoregoal3[2];
            $tableaufinaltotalgoals[3]=$vp3;
        }
        if ($nbre == 4)
        {
            $vp1 = $scoregoal1[0] + $scoregoal2[0] + $scoregoal3[0];
            self::DbQuery( "UPDATE player set player_score = player_score + {$vp1} WHERE player_no=1" );
            $id = self::getUniqueValueFromDB("SELECT player_id FROM player WHERE player_no=1");
            $this->setStat($scoregoal1[0], 'goal1', $id);
            $tableaufinalgoals[1][]=$scoregoal1[0];
            $this->setStat($scoregoal2[0], 'goal2', $id);
            $tableaufinalgoals[1][]=$scoregoal2[0];
            $this->setStat($scoregoal3[0], 'goal3', $id);
            $tableaufinalgoals[1][]=$scoregoal3[0];
            $tableaufinaltotalgoals[1]=$vp1;
            $vp2 = $scoregoal1[1] + $scoregoal2[1] + $scoregoal3[1];
            self::DbQuery( "UPDATE player set player_score = player_score + {$vp2} WHERE player_no=2" );
            $id = self::getUniqueValueFromDB("SELECT player_id FROM player WHERE player_no=2");
            $this->setStat($scoregoal1[1], 'goal1', $id);
            $tableaufinalgoals[2][]=$scoregoal1[1];
            $this->setStat($scoregoal2[1], 'goal2', $id);
            $tableaufinalgoals[2][]=$scoregoal2[1];
            $this->setStat($scoregoal3[1], 'goal3', $id);
            $tableaufinalgoals[2][]=$scoregoal3[1];
            $tableaufinaltotalgoals[2]=$vp2;
            $vp3 = $scoregoal1[2] + $scoregoal2[2] + $scoregoal3[2];
            self::DbQuery( "UPDATE player set player_score = player_score + {$vp3} WHERE player_no=3" );
            $id = self::getUniqueValueFromDB("SELECT player_id FROM player WHERE player_no=3");
            $this->setStat($scoregoal1[2], 'goal1', $id);
            $tableaufinalgoals[3][]=$scoregoal1[2];
            $this->setStat($scoregoal2[2], 'goal2', $id);
            $tableaufinalgoals[3][]=$scoregoal2[2];
            $this->setStat($scoregoal3[2], 'goal3', $id);
            $tableaufinalgoals[3][]=$scoregoal3[2];
            $tableaufinaltotalgoals[3]=$vp3;
            $vp4 = $scoregoal1[3] + $scoregoal2[3] + $scoregoal3[3];
            self::DbQuery( "UPDATE player set player_score = player_score + {$vp4} WHERE player_no=4" );
            $id = self::getUniqueValueFromDB("SELECT player_id FROM player WHERE player_no=4");
            $this->setStat($scoregoal1[3], 'goal1', $id);
            $tableaufinalgoals[4][]=$scoregoal1[3];
            $this->setStat($scoregoal2[3], 'goal2', $id);
            $tableaufinalgoals[4][]=$scoregoal2[3];
            $this->setStat($scoregoal3[3], 'goal3', $id);
            $tableaufinalgoals[4][]=$scoregoal3[3];
            $tableaufinaltotalgoals[4]=$vp4;
        }
        
                  
        

        undergrove::$instance->notifyAllPlayers("padgoals",'', array(
            
            'tableaufinalgoals' => $tableaufinalgoals,
            'tableaufinaltotalgoals' => $tableaufinaltotalgoals,
        )
        );

        $tableaufinaltotal = self::getObjectListFromDB( "SELECT player_score FROM player", true );
        undergrove::$instance->notifyAllPlayers("padtotal",'', array(
            
            'total' => $tableaufinaltotal,
            
        )
        );

        
        
        $res['tableaufinalracine']=$tableaufinalracine;
        $res['tableaufinaltotalracine']=$tableaufinaltotalracine;
        $res['tableaufinalbonus']=$tableaufinalbonus;
        $res['tableaufinalressources']=$tableaufinalressources;
        $res['tableaufinalgoals']=$tableaufinalgoals;
        $res['tableaufinaltotalgoals']=$tableaufinaltotalgoals;
        $res['tableaufinaltotal']=$tableaufinaltotal;

        $newscoreplayers = self::getCollectionFromDb( "SELECT player_id, player_score FROM player", true );

        $this->notifyAllPlayers( "newscore", '',
                    array(
    
                        'newscore' => $newscoreplayers,
                                                
                
                    )
                    );

        return $res;


    }


    function goal1($score)
    {
        $nbre = count(self::getObjectListFromDB( "SELECT player_id id FROM player", true ));
        $resulat = array();
        for ($i =0; $i <= $nbre-1; $i++)
        {
            if ($score[$i] == 0)
            {
                $resultat[] = 0;
            }
            if ($score[$i] == 1)
            {
                $resultat[] = 3;
            }
            if ($score[$i] == 2)
            {
                $resultat[] = 5;
            }
            if ($score[$i] >= 3)
            {
                $resultat[] = 9;
            }
        
        }

        return $resultat;
    }

    function goalranking($score)
    {
        $scores =array();
        $nbre = count(self::getObjectListFromDB( "SELECT player_id id FROM player", true ));
        if($nbre == 2)
        {
            $scores = [$score[0], $score[1]];
            
        }

        if($nbre == 3)
        {
            $scores = [$score[0], $score[1], $score[2]];
            
            
        }

        if($nbre == 4)
        {
            $scores = [$score[0], $score[1], $score[2], $score[3]];
            
        }
        
         // Création d'un tableau associatif pour conserver l'association joueur-score
    $scoresAssociatifs = array();
    foreach ($scores as $index => $score) {
        $scoresAssociatifs[$index] = $score;
    }

    // Tri du tableau associatif en ordre décroissant selon les scores
    arsort($scoresAssociatifs);

    // Initialisation des variables
    $nombreDeJoueurs = count($scores);
    $scoresFinaux = array();

    // Attribution des points selon les règles
    $classement = 1;
    foreach ($scoresAssociatifs as $index => $score) {
        // Attribuer les points en fonction du classement
        if ($classement == 1) {
            $scoresFinaux[$index] = 9;
        } elseif ($classement == 2) {
            $scoresFinaux[$index] = 5;
        } elseif ($classement == 3) {
            $scoresFinaux[$index] = 3;
        } else {
            $scoresFinaux[$index] = 1;
        }

        $classement++;
    }

    // Réorganisation du tableau final dans l'ordre initial des joueurs
    array_multisort(array_keys($scoresAssociatifs), $scoresFinaux);

    //return $scoresFinaux;

    $indices = array();

    foreach ($scores as $i => $valeur) 
    {
        if (array_key_exists($valeur, $indices)) {
            $indices[$valeur][] = $i;
        } else {
            $indices[$valeur] = array($i);
        }
    }

    $indicesEgaux = array_filter($indices, function($v) {
        return count($v) > 1;
    });

    
    if ($indicesEgaux !=null)
    {
    foreach($indicesEgaux as $tableau)
        {
            $count=count($tableau);
            $calcul=0;
            for ($k=0; $k <$count; $k++)
            {
                $calcul = $calcul + $scoresFinaux[$tableau[$k]];
            }

            $resultat = intval(floor($calcul/$count));
            
            for ($m=0; $m <$count; $m++)
            {
                $scoresFinaux[$tableau[$m]]=$resultat;
            }


        }
        return $scoresFinaux;

    }
    
    
    
    else
    {
        return $scoresFinaux;
    }
        
        
    

    }


        function goal3($score)
    {
        $nbre = count(self::getObjectListFromDB( "SELECT player_id id FROM player", true ));
        $resulat = array();
        for ($i =0; $i <= $nbre-1; $i++)
        {
            if ($score[$i] < 3)
            {
                $resultat[] = 0;
            }
            if ($score[$i] == 3)
            {
                $resultat[] = 1;
            }
            if ($score[$i] == 4)
            {
                $resultat[] = 3;
            }
            if ($score[$i] == 5)
            {
                $resultat[] = 6;
            }
            if ($score[$i] >= 6)
            {
                $resultat[] = 10;
            }
        
        }

        return $resultat;
    }

    function goal5($score)
    {
        $nbre = count(self::getObjectListFromDB( "SELECT player_id id FROM player", true ));
        $resulat = array();
        for ($i =0; $i <= $nbre-1; $i++)
        {
            if ($score[$i] <1)
            {
                $resultat[] = 0;
            }
            if ($score[$i] == 1)
            {
                $resultat[] = 1;
            }
            if ($score[$i] == 2)
            {
                $resultat[] = 2;
            }
            if ($score[$i] >= 3)
            {
                $resultat[] = 4;
            }
        
        }

        return $resultat;
    }

    function goal6($score)
    {
        $nbre = count(self::getObjectListFromDB( "SELECT player_id id FROM player", true ));
        $resulat = array();
        for ($i =0; $i <= $nbre-1; $i++)
        {
            if ($score[$i] <4)
            {
                $resultat[] = 0;
            }
            if ($score[$i] == 4)
            {
                $resultat[] = 1;
            }
            if ($score[$i] == 5)
            {
                $resultat[] = 5;
            }
            if ($score[$i] >= 6)
            {
                $resultat[] = 10;
            }
        
        }

        return $resultat;
    }

    function goal8($score)
    {
        $nbre = count(self::getObjectListFromDB( "SELECT player_id id FROM player", true ));
        $resulat = array();
        for ($i =0; $i <= $nbre-1; $i++)
        {
            
                $resultat[] = $score[$i]*2;
            
        
        }

        return $resultat;
    }

    


    

///////////////////////////////////////////////////////////////////////////////// 
//     _____  _                                    _   _                 
//    |  __ \| |                                  | | (_)                
//    | |__) | | __ _ _   _  ___ _ __    __ _  ___| |_ _  ___  _ __  ___ 
//    |  ___/| |/ _` | | | |/ _ \ '__|  / _` |/ __| __| |/ _ \| '_ \/ __|
//    | |    | | (_| | |_| |  __/ |    | (_| | (__| |_| | (_) | | | \__ \
//    |_|    |_|\__,_|\__, |\___|_|     \__,_|\___|\__|_|\___/|_| |_|___/
//                     __/ |                                             
//                    |___/                                              
/////////////////////////////////////////////////////////////////////////////////    

function actSelect($arg1)
{
   
    self::checkAction( 'actSelect' );        
      
    $pending =  self::getObjectFromDB( "SELECT* FROM pending order by id desc limit 1");
    $this->callPending($pending, true, $arg1);
    self::DbQuery("delete from pending where id=".$pending['id']);
    $this->giveExtraTime(self::getActivePlayerId());
    $this->gamestate->nextState( 'next');
    
}

function actCancel()
{
   
    self::checkAction( 'actSelect' );        
            
    $pending =  self::getObjectFromDB( "SELECT* FROM pending order by id desc limit 1");
    self::DbQuery("delete from pending where id=".$pending['id']);
    $this->addPending ($pending['player_id'], "NormalTurn");
    
    $this->gamestate->nextState( 'next');
    
}

function actReproduce()
{
   
    self::checkAction( 'actSelect' ); 

            
    $pending =  self::getObjectFromDB( "SELECT* FROM pending order by id desc limit 1");
    self::DbQuery("delete from pending where id=".$pending['id']);
    $this->addPending ($pending['player_id'], "Reproduce");

    $this->undoSavepoint();   
    
    $this->gamestate->nextState( 'next');
    
}

function actYesPlaceChampiReproduce()
{
   
    self::checkAction( 'actSelect' );        
            
    $pending =  self::getObjectFromDB( "SELECT* FROM pending order by id desc limit 1");
    self::DbQuery("delete from pending where id=".$pending['id']);
    $this->addPending ($pending['player_id'], "ReproducePayerChampi");
    
    $this->gamestate->nextState( 'next');
    
}

function actNoPlaceChampiReproduce()
{
   
    self::checkAction( 'actSelect' );        
            
    $pending =  self::getObjectFromDB( "SELECT* FROM pending order by id desc limit 1");
    self::DbQuery("delete from pending where id=".$pending['id']);
    $this->addPending ($pending['player_id'], "ReproduceStep1");
    
    $this->gamestate->nextState( 'next');
    
}


function actNobonuschampi($arg1)
{
   
    self::checkAction( 'actSelect' );        
      
    $pending =  self::getObjectFromDB( "SELECT* FROM pending order by id desc limit 1");
    $this->callPending($pending, true, $arg1);
    self::DbQuery("delete from pending where id=".$pending['id']);
    $this->giveExtraTime(self::getActivePlayerId());
    $this->gamestate->nextState( 'next');
    
}


function actCancel2()
{
   
    self::checkAction( 'actSelect' );        
            
    $pending =  self::getObjectFromDB( "SELECT* FROM pending order by id desc limit 1");
    self::DbQuery("delete from pending where id=".$pending['id']);
    $this->addPending ($pending['player_id'], "ReproducePlacerChampiBonus1");
    
    $this->gamestate->nextState( 'next');
    
}

function actCancel3()
{
   
    self::checkAction( 'actSelect' );        
            
    $pending =  self::getObjectFromDB( "SELECT* FROM pending order by id desc limit 1");
    self::DbQuery("delete from pending where id=".$pending['id']);
    $this->addPending ($pending['player_id'], "PartnerPlacerChampiBonus1");
    
    $this->gamestate->nextState( 'next');
    
}

function actPartner()
{
   
    self::checkAction( 'actSelect' );        
            
    $pending =  self::getObjectFromDB( "SELECT* FROM pending order by id desc limit 1");
    self::DbQuery("delete from pending where id=".$pending['id']);
    $this->addPending ($pending['player_id'], "Partner");

    $this->undoSavepoint();   
    
    $this->gamestate->nextState( 'next');
    
}

function actYesPlaceChampiPartner()
{
   
    self::checkAction( 'actSelect' );        
            
    $pending =  self::getObjectFromDB( "SELECT* FROM pending order by id desc limit 1");
    self::DbQuery("delete from pending where id=".$pending['id']);
    $this->addPending ($pending['player_id'], "PartnerPayerChampi");
    
    $this->gamestate->nextState( 'next');
    
}

function actNoPlaceChampiPartner()
{
   
    self::checkAction( 'actSelect' );        
            
    $pending =  self::getObjectFromDB( "SELECT* FROM pending order by id desc limit 1");
    self::DbQuery("delete from pending where id=".$pending['id']);
    $this->addPending ($pending['player_id'], "PartnerStep1");
    
    $this->gamestate->nextState( 'next');
    
}

function actFindetour()
{
   
    self::checkAction( 'actSelect' );  
    
    $this->GoalTrack();
    undergrove::$instance->CheckEnd();
            
    $pending =  self::getObjectFromDB( "SELECT* FROM pending order by id desc limit 1");
    self::DbQuery("delete from pending where id=".$pending['id']);
    $this->addPendingFirst ($pending['player_id'], "NormalTurn");
    
    
    $this->gamestate->nextState( 'next');
    
}

function actFindetour2()
{
   
    self::checkAction( 'actSelect' );  
    
    $this->GoalTrack();
    $player_id = $this->getActivePlayerId();
    $player_name = self::getUniqueValueFromDB("SELECT player_name FROM player WHERE player_id={$player_id}");
    //declenchement fin de partie
    $securite = 0;
    if (undergrove::$instance->getGameStateValue('final') == 0)
    {
    undergrove::$instance->setGameStateValue('final', 1);
    undergrove::$instance->notifyAllPlayers('messagealerte',clienttranslate( '${player_name} has just triggered the end of the game. This will end at the end of the next round.' ), array(
        'player_name' => $player_name,
        )
        );
    $securite = 1;
    
    $numero = self::getUniqueValueFromDB("SELECT player_no FROM player WHERE player_id={$player_id}");
    for ($i = 1; $i <= $numero; $i++)
    {
        self::DbQuery( "UPDATE player set final = 1 WHERE player_no={$i}" );
    }
    }

    if ((undergrove::$instance->getGameStateValue('final') == 1) && ($securite == 0))
    {
    undergrove::$instance->CheckEnd();
    }
            
    $pending =  self::getObjectFromDB( "SELECT* FROM pending order by id desc limit 1");
    self::DbQuery("delete from pending where id=".$pending['id']);
    $this->addPendingFirst ($pending['player_id'], "NormalTurn");
    
    
    $this->gamestate->nextState( 'next');
    
}

function actPhoto()
{
   
    self::checkAction( 'actSelect' );        
            
    $pending =  self::getObjectFromDB( "SELECT* FROM pending order by id desc limit 1");
    self::DbQuery("delete from pending where id=".$pending['id']);
    $this->addPending ($pending['player_id'], "Photo");

    $this->undoSavepoint();   
    
    $this->gamestate->nextState( 'next');
    
}

function actNoPhotoEchange()
{
   
    self::checkAction( 'actSelect' );        
            
    $pending =  self::getObjectFromDB( "SELECT* FROM pending order by id desc limit 1");
    self::DbQuery("delete from pending where id=".$pending['id']);
    $this->addPending ($pending['player_id'], "PhotoDiscard", 0);
    
    $this->gamestate->nextState( 'next');
    
}

function actYesPhotoEchange($arg1)
{
   
    self::checkAction( 'actSelect' );        
            
    $pending =  self::getObjectFromDB( "SELECT* FROM pending order by id desc limit 1");
    $this->callPending($pending, true, $arg1);
    self::DbQuery("delete from pending where id=".$pending['id']);
    
    
    $this->gamestate->nextState( 'next');
    
}

function actNoPhotoEchangeSupp()
{
   
    self::checkAction( 'actSelect' );        
            
    $pending =  self::getObjectFromDB( "SELECT* FROM pending order by id desc limit 1");
    self::DbQuery("delete from pending where id=".$pending['id']);
    $this->addPending ($pending['player_id'], "PhotoDiscard", 1);
    
    $this->gamestate->nextState( 'next');
    
}

function actYesPhotoEchangeSupp($arg1)
{
   
    self::checkAction( 'actSelect' );        
            
    $pending =  self::getObjectFromDB( "SELECT* FROM pending order by id desc limit 1");
    $this->callPending($pending, true, $arg1);
    self::DbQuery("delete from pending where id=".$pending['id']);
    
    
    $this->gamestate->nextState( 'next');
    
}

function actValiderPhotoDiscard( $arg1, $arg2, $arg3 )
{
    self::checkAction( 'actSelect' ); 

    
    if ($arg1 != "0")
    {
    $explode = explode("_", $arg1);
    
    $origine = self::getUniqueValueFromDB("SELECT card_location location FROM champignon WHERE card_id={$explode[1]}");
    $position = self::getUniqueValueFromDB("SELECT card_location_arg location_arg FROM champignon WHERE card_id={$explode[1]}");
    undergrove::$instance->champignon->moveCard( $explode[1], 'discard');
    undergrove::$instance->champignon->pickCardForLocation( 'deck', $origine, $position );
    $newid = self::getUniqueValueFromDB("SELECT card_id id FROM champignon WHERE card_location='{$origine}' AND card_location_arg={$position}");
    $type = self::getUniqueValueFromDB("SELECT card_type type FROM champignon WHERE card_id={$newid}");

    

    undergrove::$instance->notifyAllPlayers("discard",'', array(
        
        'mobile' => $arg1,
        'id' => $explode[1],
        'type' => $type,
        'origine' => $origine,
        'newid' => $newid,
        'position' => $position, 
    )
    );
    }

    if ($arg2 != "0")
    {
    $explode = explode("_", $arg2);
    
    $origine = self::getUniqueValueFromDB("SELECT card_location location FROM champignon WHERE card_id={$explode[1]}");
    $position = self::getUniqueValueFromDB("SELECT card_location_arg location_arg FROM champignon WHERE card_id={$explode[1]}");
    undergrove::$instance->champignon->moveCard( $explode[1], 'discard');
    undergrove::$instance->champignon->pickCardForLocation( 'deck', $origine, $position );
    $newid = self::getUniqueValueFromDB("SELECT card_id id FROM champignon WHERE card_location='{$origine}' AND card_location_arg={$position}");
    $type = self::getUniqueValueFromDB("SELECT card_type type FROM champignon WHERE card_id={$newid}");


    undergrove::$instance->notifyAllPlayers("discard",'', array(
        
        'mobile' => $arg2,
        'id' => $explode[1],
        'type' => $type,
        'origine' => $origine,
        'newid' => $newid,
        'position' => $position, 
    )
    );
    }

    if ($arg3 != "0")
    {
    $explode = explode("_", $arg3);
    
    $origine = self::getUniqueValueFromDB("SELECT card_location location FROM champignon WHERE card_id={$explode[1]}");
    $position = self::getUniqueValueFromDB("SELECT card_location_arg location_arg FROM champignon WHERE card_id={$explode[1]}");
    undergrove::$instance->champignon->moveCard( $explode[1], 'discard');
    undergrove::$instance->champignon->pickCardForLocation( 'deck', $origine, $position );
    $newid = self::getUniqueValueFromDB("SELECT card_id id FROM champignon WHERE card_location='{$origine}' AND card_location_arg={$position}");
    $type = self::getUniqueValueFromDB("SELECT card_type type FROM champignon WHERE card_id={$newid}");


    undergrove::$instance->notifyAllPlayers("discard",'', array(
        
        'mobile' => $arg3,
        'id' => $explode[1],
        'type' => $type,
        'origine' => $origine,
        'newid' => $newid,
        'position' => $position, 
    )
    );
    }

    if(($arg1 != "0") || ($arg2 != "0") || ($arg3 != "0")) 
    {
    $this->notifyAllPlayers( 'message', '',
        array(

                      
            
        )
        );
    }
          
    
    $pending =  self::getObjectFromDB( "SELECT* FROM pending order by id desc limit 1");
    $this->callPending($pending, true);
    self::DbQuery("delete from pending where id=".$pending['id']);
    $this->giveExtraTime(self::getActivePlayerId());
    $this->gamestate->nextState( 'next');

    }

    function actActivate()
    {
       
        self::checkAction( 'actSelect' );        
                
        $pending =  self::getObjectFromDB( "SELECT* FROM pending order by id desc limit 1");
        self::DbQuery("delete from pending where id=".$pending['id']);
        $this->addPending ($pending['player_id'], "Activate");

        $this->undoSavepoint();   
        
        $this->gamestate->nextState( 'next');
        
    }

    function actAbsorb()
    {
       
        self::checkAction( 'actSelect' );        
                
        $pending =  self::getObjectFromDB( "SELECT* FROM pending order by id desc limit 1");
        self::DbQuery("delete from pending where id=".$pending['id']);
        $this->addPending ($pending['player_id'], "Absorb");

        $this->undoSavepoint();   
        
        $this->gamestate->nextState( 'next');
        
    }

    function actActivation($arg1)
{
   
    self::checkAction( 'actSelect' );        
      
    $pending =  self::getObjectFromDB( "SELECT* FROM pending order by id desc limit 1");
    $this->callPending($pending, true, $arg1);
    self::DbQuery("delete from pending where id=".$pending['id']);
    $this->giveExtraTime(self::getActivePlayerId());
    $this->gamestate->nextState( 'next');
    
}

function actRessource($arg1)
{
   
    self::checkAction( 'actSelect' );        
      
    $pending =  self::getObjectFromDB( "SELECT* FROM pending order by id desc limit 1");
    $this->callPending($pending, true, $arg1);
    self::DbQuery("delete from pending where id=".$pending['id']);
    $this->giveExtraTime(self::getActivePlayerId());
    $this->gamestate->nextState( 'next');
    
}

function actFindetour3($arg1)
{
   
    self::checkAction( 'actSelect' );        
      
    $pending =  self::getObjectFromDB( "SELECT* FROM pending order by id desc limit 1");
    $this->callPending($pending, true, $arg1);
    self::DbQuery("delete from pending where id=".$pending['id']);
    $this->giveExtraTime(self::getActivePlayerId());
    $this->gamestate->nextState( 'next');
    
}

function actUndo()
{
   
    self::checkAction( 'actSelect' );   
    
         
    $this->undoRestorePoint();
    //undergrove::$instance->GoalTrack();
    undergrove::$instance->MajRessources();

    $tableau = array(); 
    $listplayers = self::getObjectListFromDB("SELECT player_id id FROM player", true);
    foreach($listplayers as $player)
        {
            $tableau[$player][] = self::getUniqueValueFromDB("SELECT track FROM player WHERE player_id = '{$player}'");
            $tableau[$player][] = self::getUniqueValueFromDB("SELECT player_color FROM player WHERE player_id = '{$player}'");
        } 
    undergrove::$instance->notifyAllPlayers("actumarqueur",'', array(
        'track' => $tableau,
    )
    );

    $pending =  self::getObjectFromDB( "SELECT* FROM pending order by id desc limit 1");
    self::DbQuery("delete from pending where id=".$pending['id']);
    $this->addPending ($pending['player_id'], "NormalTurn");
    
    $this->gamestate->nextState( 'next');
    
}

function actBonusTile($arg1)
{
   
    self::checkAction( 'actSelect' );        
      
    $pending =  self::getObjectFromDB( "SELECT* FROM pending order by id desc limit 1");
    $this->callPending($pending, true, $arg1);
    self::DbQuery("delete from pending where id=".$pending['id']);
    $this->giveExtraTime(self::getActivePlayerId());
    $this->gamestate->nextState( 'next');
    
}

function actPass($arg1)
{
   
    self::checkAction( 'actSelect' );        
      
    $pending =  self::getObjectFromDB( "SELECT* FROM pending order by id desc limit 1");
    $this->callPending($pending, true, $arg1);
    self::DbQuery("delete from pending where id=".$pending['id']);
    $this->giveExtraTime(self::getActivePlayerId());
    $this->gamestate->nextState( 'next');
    
}

function actNbre($arg1)
{
   
    self::checkAction( 'actSelect' );        
      
    $pending =  self::getObjectFromDB( "SELECT* FROM pending order by id desc limit 1");
    $this->callPending($pending, true, $arg1);
    self::DbQuery("delete from pending where id=".$pending['id']);
    $this->giveExtraTime(self::getActivePlayerId());
    $this->gamestate->nextState( 'next');
    
}

function actAction($arg1)
{
   
    self::checkAction( 'actSelect' );        
      
    $pending =  self::getObjectFromDB( "SELECT* FROM pending order by id desc limit 1");
    $this->callPending($pending, true, $arg1);
    self::DbQuery("delete from pending where id=".$pending['id']);
    $this->giveExtraTime(self::getActivePlayerId());
    $this->gamestate->nextState( 'next');
    
}

function actConfirm()
{
   
    self::checkAction( 'actSelect' );        
      
    $pending =  self::getObjectFromDB( "SELECT* FROM pending order by id desc limit 1");
    $this->callPending($pending, true);
    self::DbQuery("delete from pending where id=".$pending['id']);
    $this->giveExtraTime(self::getActivePlayerId());
    $this->gamestate->nextState( 'next');;
    
}



    
///////////////////////////////////////////////////////////////////////////////// 
//     _____                             _        _                                                    _       
//    / ____|                           | |      | |                                                  | |      
//    | |  __  __ _ _ __ ___   ___   ___| |_ __ _| |_ ___    __ _ _ __ __ _ _   _ _ __ ___   ___ _ __ | |_ ___ 
//    | | |_ |/ _` | '_ ` _ \ / _ \ / __| __/ _` | __/ _ \  / _` | '__/ _` | | | | '_ ` _ \ / _ \ '_ \| __/ __|
//    | |__| | (_| | | | | | |  __/ \__ \ || (_| | ||  __/ | (_| | | | (_| | |_| | | | | | |  __/ | | | |_\__ \
//     \_____|\__,_|_| |_| |_|\___| |___/\__\__,_|\__\___|  \__,_|_|  \__, |\__,_|_| |_| |_|\___|_| |_|\__|___/
//                                                                    __/ |                                   
//                                                                   |___/                                    
///////////////////////////////////////////////////////////////////////////////// 

   
function argPlayerTurn()
{
    $arg = array();
    $pending =  self::getObjectFromDB( "SELECT* FROM pending order by id desc limit 1");
    $arg = $this->callPending($pending, false);

    
    
    return $arg;
}

///////////////////////////////////////////////////////////////////////////////// 
//      _____                            _        _                    _   _                 
//     / ____|                          | |      | |                  | | (_)                
//    | |  __  __ _ _ __ ___   ___   ___| |_ __ _| |_ ___    __ _  ___| |_ _  ___  _ __  ___ 
//    | | |_ |/ _` | '_ ` _ \ / _ \ / __| __/ _` | __/ _ \  / _` |/ __| __| |/ _ \| '_ \/ __|
//    | |__| | (_| | | | | | |  __/ \__ \ || (_| | ||  __/ | (_| | (__| |_| | (_) | | | \__ \
//     \_____|\__,_|_| |_| |_|\___| |___/\__\__,_|\__\___|  \__,_|\___|\__|_|\___/|_| |_|___/
//                                                                                       
/////////////////////////////////////////////////////////////////////////////////                                                                                       


function callPending($pending, $execute, $arg1 = null, $arg2 = null)
{
   
    if(class_exists($pending['function'])){
        $obj = new $pending['function']();
        $obj->player_id = $this->getActivePlayerId();
        if($pending['player_id'] != null)
        {
            $obj->player_id = $pending['player_id'];
        }
        $obj->player = new Pending($obj->player_id);
        
        $method = "";
        if($pending['target'] != null)
        {
            $method = $pending['target'];
        }
        if(!$execute)
        {
            $name = "arg".$method;
        }
        else
        {
            $name = $method;
        }
        $ret = $obj->$name($pending['arg'], $pending['arg2'], $arg1, $arg2);
    }
    else
    {
        $obj = $this;
        if($pending['player_id'] != null)
        {
            $obj = new Pending($pending['player_id']);
        }
        
        $fname ="";
        if(!$execute)
        {
            $fname .= "arg";
        }
        $fname .= $pending['function'];
        
        $ret = null;
        if(method_exists($obj, $fname))
        {
            $ret = $obj->$fname($pending['arg'], $pending['arg2'], $arg1, $arg2);
        }
    }
    return $ret;
}


function stPending() {
   
   $pending =  self::getObjectFromDB( "SELECT * FROM pending order by id desc limit 1");
   if($pending == null)
   {
        //$this->endGame();
        $this->gamestate->nextState( 'end' ); 
   }
   else
   {
       $args = $this->callPending($pending, false);
              
       if($args == null || (count($args['selectable']) == 0 && count($args['buttons']) == 0))
       {
           //no args required, execute
           $this->callPending($pending, true);
           self::DbQuery("delete from pending where id=".$pending['id']);
           $this->gamestate->nextState( 'same' );  
       }
       /*else if(count($args['selectable']) + count($args['buttons']) == 1)
       {
           //AUTO PLAY IF ONLY ONE CHOICE
           foreach($args['selectable'] as $arg1 => $argnul)
           {
               $this->callPending($pending, true, $arg1);
           }
           foreach($args['buttons'] as $arg1 => $argnul)
           {
               $this->callPending($pending, true, $arg1);
           }
           self::DbQuery("delete from pending where id=".$pending['id']);
           $this->gamestate->nextState( 'same' );  
       }*/
       else
       {
          
           $this->gamestate->changeActivePlayer( $pending['player_id']);
           
           //player input required
           $this->gamestate->nextState( 'player' ); 
       }            
   }
   
}

/////////////////////////////////////////////////////////////////////////////////
//    ______               _     _      
//   |___  /              | |   (_)     
//      / / ___  _ __ ___ | |__  _  ___ 
//     / / / _ \| '_ ` _ \| '_ \| |/ _ \
//    / /_| (_) | | | | | | |_) | |  __/
//   /_____\___/|_| |_| |_|_.__/|_|\___|
//                                   
/////////////////////////////////////////////////////////////////////////////////                                   


    function zombieTurn( $state, $active_player )
    {
    	$statename = $state['name'];
    	
        if ($state['type'] === "activeplayer") {
            switch ($statename) {
                default:
                    $player_id = $this->getActivePlayerId();
                    self::DbQuery( "UPDATE player set final = 2 WHERE player_id={$player_id}" ); // score final
                    $this->CheckEnd();
                    self::DbQuery("delete from pending where player_id = {$player_id}");
                    $this->gamestate->nextState( "zombiePass" );
                	break;
            }

            return;
        }

        if ($state['type'] === "multipleactiveplayer") {
            // Make sure player is in a non blocking status for role turn
            $this->gamestate->setPlayerNonMultiactive( $active_player, '' );
            
            return;
        }

        throw new feException( "Zombie mode not supported at this game state: ".$statename );
    }
    
///////////////////////////////////////////////////////////////////////////////// 
//     _____  ____                                    _      
//    |  __ \|  _ \                                  | |     
//    | |  | | |_) |  _   _ _ __   __ _ _ __ __ _  __| | ___ 
//    | |  | |  _ <  | | | | '_ \ / _` | '__/ _` |/ _` |/ _ \
//    | |__| | |_) | | |_| | |_) | (_| | | | (_| | (_| |  __/
//    |_____/|____/   \__,_| .__/ \__, |_|  \__,_|\__,_|\___|
//                         | |     __/ |                     
//                         |_|    |___/                      
/////////////////////////////////////////////////////////////////////////////////    

        function upgradeTableDb( $from_version )
    {
        // $from_version is the current version of this game database, in numerical form.
        // For example, if the game was running with a release of your game named "140430-1345",
        // $from_version is equal to 1404301345
        
        // Example:
//        if( $from_version <= 1404301345 )
//        {
//            // ! important ! Use DBPREFIX_<table_name> for all tables
//
//            $sql = "ALTER TABLE DBPREFIX_xxxxxxx ....";
//            self::applyDbUpgradeToAllDB( $sql );
//        }
//        if( $from_version <= 1405061421 )
//        {
//            // ! important ! Use DBPREFIX_<table_name> for all tables
//
//            $sql = "CREATE TABLE DBPREFIX_xxxxxxx ....";
//            self::applyDbUpgradeToAllDB( $sql );
//        }
//        // Please add your future database scheme changes here
//
//

    if( $from_version <= 2312012249 )
    {

        $sql = "ALTER TABLE DBPREFIX_tiles MODIFY COLUMN card_location VARCHAR(50);";
        self::applyDbUpgradeToAllDB( $sql );
    }

    if( $from_version <= 2312012249 )
    {

        $sql = "ALTER TABLE DBPREFIX_champignon MODIFY COLUMN card_location VARCHAR(50);";
        self::applyDbUpgradeToAllDB( $sql );
    }

    if( $from_version <= 2312012249 )
    {

        $sql = "ALTER TABLE DBPREFIX_goal MODIFY COLUMN card_location VARCHAR(50);";
        self::applyDbUpgradeToAllDB( $sql );
    }

    
    }    
}
