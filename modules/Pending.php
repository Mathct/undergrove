<?php
class Pending extends APP_GameClass
{
    public function __construct($player_id)
    {
        $this->player_id = $player_id;
        $p = self::getObjectFromDB("SELECT * FROM player WHERE player_id = {$player_id}");        
        $this->player_no = $p['player_no'];
        $this->player_id = $p['player_id'];
        $this->player_name = $p['player_name'];
        $this->player_score = $p['player_score'];
        $this->player_color = $p['player_color'];
        $this->player_azote = $p['azote'];
        $this->player_phosphore = $p['phosphore'];
        $this->player_potassium = $p['potassium'];
        $this->player_carbone = $p['carbone'];
        $this->player_bonus_racine_reproduce= $p['bonus_racine_reproduce'];
        $this->player_bonus_racine_partner= $p['bonus_racine_partner'];
        $this->player_bonus_champi_reproduce= $p['bonus_champi_reproduce'];
        $this->player_bonus_champi_partner= $p['bonus_champi_partner'];
        $this->player_bonus_carbone= $p['bonus_carbone'];
        $this->player_bonus_score= $p['bonus_score'];
        $this->player_semi= $p['semi'];
        $this->player_arbre= $p['arbre'];
        $this->player_racine= $p['racine'];
        $this->player_activation_b= $p['activation_b'];
        $this->player_activation_p= $p['activation_p'];
        $this->player_activation_g= $p['activation_g'];
        $this->player_activation_y= $p['activation_y'];

    }

    // Aide pending:
    // parg1 = ce qui est envoyé dans addPending
    // varg1 = ce qui est cliqué dans le arg

/////////////////////////////////////////////////////////////////////////////////
//     _____       _ _   _       _   _                    
//    |_   _|     (_) | (_)     | | | |                   
//      | |  _ __  _| |_ _  __ _| | | |_ _   _ _ __ _ __  
//      | | | '_ \| | __| |/ _` | | | __| | | | '__| '_ \ 
//     _| |_| | | | | |_| | (_| | | | |_| |_| | |  | | | |
//    |_____|_| |_|_|\__|_|\__,_|_|  \__|\__,_|_|  |_| |_|
//                                                   
/////////////////////////////////////////////////////////////////////////////////    


function argInitialTurn($parg1, $parg2)
{
    $ret = array();
    $ret["selectable"] = array();
    $ret['buttons'] = array();
    $ret['title'] = clienttranslate('${actplayer} must place the first seedling with the first root');
    $ret['titleyou'] = clienttranslate('${you} must place your first seedling with your first root');

   
    $ret["selectable"] = self::getObjectListFromDB( "SELECT location location FROM foret WHERE type = 'emplacement_semi' AND player_id IS NULL AND (location = 'circle_0_0' OR location = 'circle_0_1' OR location = 'circle_1_0' OR location = 'circle_1_1')", true );
    
            
    return $ret;
}


function InitialTurn($parg1, $parg2, $varg1, $varg2)
{
    $explode = explode("_", $varg1);
    $racine = "minisquare_0_0_".$explode[1]."_".$explode[2];

    self::DbQuery( "UPDATE player set semi = semi -1  WHERE player_id = {$this->player_id}" );
    self::DbQuery( "UPDATE foret set type = 'semi' WHERE location = '{$varg1}'" );
    self::DbQuery( "UPDATE foret set player_id = {$this->player_id} WHERE location = '{$varg1}'" );

    self::DbQuery( "UPDATE player set racine = racine -1  WHERE player_id = {$this->player_id}" );
    self::DbQuery( "UPDATE foret set type = 'racine' WHERE location = '{$racine}'" );
    self::DbQuery( "UPDATE foret set player_id = {$this->player_id} WHERE location = '{$racine}'" );

    $nbreplayers = count(self::getObjectListFromDB( "SELECT player_id id FROM player", true ));
    $nbreposition = count(self::getObjectListFromDB( "SELECT type type FROM foret WHERE type = 'semi'", true ));
    $carbone= self::getUniqueValueFromDB("SELECT carbone FROM foret WHERE location='{$varg1}'");
    $sens = self::getUniqueValueFromDB("SELECT sens_racine FROM foret WHERE location='{$racine}'");

    $numero = "p".$this->player_no;
    self::DbQuery( "UPDATE goal set {$numero} = {$numero} +1  WHERE card_type = 3" );   

    
    undergrove::$instance->notifyAllPlayers("placeinitsemi",clienttranslate( '${player_name} places the first seedling and the first root' ), array(
        'player_name' => $this->player_name,   
        'cible1' =>  $varg1,
        'cible2' =>  $racine,
        'color' =>  $this->player_color,
        'carbone' => $carbone,
        'sens' => $sens,
         
    )
    );

    undergrove::$instance->notifyAllPlayers("placeinitracine",'', array(
        'player_name' => $this->player_name,   
        'cible1' =>  $varg1,
        'cible2' =>  $racine,
        'color' =>  $this->player_color,
        'carbone' => $carbone,
        'sens' => $sens,
         
    )
    );

    $this->ScoreRacine ($racine);

        
    if ($nbreplayers == $nbreposition)
    {
        $tableau = self::getObjectListFromDB( "SELECT location location FROM foret WHERE type = 'emplacement_semi' AND player_id IS NULL AND (location = 'circle_0_0' OR location = 'circle_0_1' OR location = 'circle_1_0' OR location = 'circle_1_1')", true );
        foreach ($tableau as $valeur)
        {
            self::DbQuery( "UPDATE foret set type = 'semi_neutre' WHERE location = '{$valeur}'" );
            self::DbQuery( "UPDATE foret set player_id = 0 WHERE location = '{$valeur}'" );

            undergrove::$instance->notifyAllPlayers("placesemineutre",'', array(
            
                'cible' =>  $valeur,
                'color' =>  "000000",
                 
            )
            );
        }
    }

    undergrove::$instance->GoalTrack();
    undergrove::$instance->MajRessources();


}



/////////////////////////////////////////////////////////////////////////////////
//     _   _                            _   _______               
//    | \ | |                          | | |__   __|              
//    |  \| | ___  _ __ _ __ ___   __ _| |    | |_   _ _ __ _ __  
//    | . ` |/ _ \| '__| '_ ` _ \ / _` | |    | | | | | '__| '_ \ 
//    | |\  | (_) | |  | | | | | | (_| | |    | | |_| | |  | | | |
//    |_| \_|\___/|_|  |_| |_| |_|\__,_|_|    |_|\__,_|_|  |_| |_|
//                                                               
/////////////////////////////////////////////////////////////////////////////////                                                              
    
    function argNormalTurn($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} must choose an action');
        
        undergrove::$instance->GoalTrack2();
        undergrove::$instance->setGameStateValue('idcopieur', 0);
        

        ///////////////////////
        // test Activate
        ///////////////////////

        $ret['buttons'][]="Activate";


        ///////////////////////
        // test Absorb
        ///////////////////////

        // il faut au moins un jeton d'activation et une ressource
        $controlesemi = count(self::getObjectListFromDB( "SELECT location location FROM foret WHERE type = 'semi' AND player_id = {$this->player_id} AND carbone < 4", true ));
        if (($controlesemi >=1) && (($this->player_activation_b + $this->player_activation_p + $this->player_activation_g + $this->player_activation_y) >=1) && (($this->player_phosphore + $this->player_potassium + $this->player_azote) >= 1))
        {
            $ret['buttons'][]="Absorb";
        }    
        
        
        ///////////////////////
        // test Reproduce
        ///////////////////////


        if (($this->player_carbone >= 1) && ($this->player_phosphore >= 2) && ($this->player_semi >=1) && ($this->player_racine >=1))
        {
            $ret['buttons'][]="Reproduce";
        }

        ///////////////////////
        // test Partner
        ///////////////////////

        $tableau = array();
        $tableau2 = array();
        $square=array();
        $tableau["racinelibre"] = self::getObjectListFromDB( "SELECT location location FROM foret WHERE type = 'emplacement_racine'", true );

        foreach ($tableau["racinelibre"] as $racine)
        {
            
            $explode = explode("_", $racine);
            $controle = "circle_".$explode[3]."_".$explode[4];
            $test = self::getUniqueValueFromDB("SELECT player_id FROM foret WHERE location='{$controle}' AND (type='semi' OR type='arbre')");
            if ($test == $this->player_id)
            {
                $tableau2[]=$racine;
            }

        }

        if ($tableau2 == NULL)
        {
        
        $semi=array();  
        $semi = self::getObjectListFromDB( "SELECT location location FROM foret WHERE (type = 'semi' OR type ='arbre') AND player_id = {$this->player_id}" );
        
        foreach ($semi as $emplacement)
        {
            $circle = explode ("_",$emplacement['location']);
            
            $a = intval($circle[1]);
            $b = intval($circle[2]);
            
            
            for ($i=-1; $i<=0; $i++)
            {
                for ($j=-1; $j<=0; $j++)
                {
                    $newa = $a + $i;
                    $newb = $b + $j;
                    $coord = "square_".$newa."_".$newb;
                    
                    $test = self::getUniqueValueFromDB("SELECT location FROM foret WHERE location = '{$coord}' AND type = 'emplacement_champi'");
                    if ($test != NULL)
                    {
                        $square[] = $test;
                    }
                    
                    
                }
            }
        }

        }
        
                

        if (($this->player_carbone >= 1) && ($this->player_potassium >= 2) && ($this->player_racine >=1) && ($tableau2 != NULL))
        {
            $ret['buttons'][]="Partner";
        }

        if (($this->player_carbone >= 1) && ($this->player_potassium >= 2) && ($this->player_racine >=1) && ($tableau2 == NULL) && ($square != NULL))
        {
            if (($this->player_azote >= 1) || ($this->player_phosphore >= 1) || ($this->player_potassium - 2 >= 1))
            {
            $ret['buttons'][]="Partner";
            }
        }


        ///////////////////////
        // test Photo
        ///////////////////////

                
        $ret['buttons'][]="Photo";
        

        ///////////////////////
        // test Bonus tiles
        ///////////////////////
        $playertile = "tilehand_".$this->player_id;
        $tiles = self::getObjectListFromDB( "SELECT card_location location, card_location_arg location_arg FROM tiles WHERE card_location = '{$playertile}' AND card_type_arg = 0 AND card_type <= 10");
        if ($tiles !=null)
        {
            $ret['titleyou'] = clienttranslate('${you} must choose an action (you can activate a bonus tile)');

            foreach ($tiles as $tile)
            {
                
                
                    $ret['selectable'][]="tilehand_".$this->player_id."_".$tile['location_arg'];
                

            }
        }

        
        else
        {

            $ret['titleyou'] = clienttranslate('${you} must choose an action');
        }

        
        

       
        
        return $ret;
    }

    function NormalTurn($parg1, $parg2, $varg1, $varg2)
    {
        $tile = explode ("_",$varg1);
        $a = intval($tile[1]);
        $arg = intval($tile[2]);
        $location = $tile[0]."_".$a;
            
        $type = self::getUniqueValueFromDB("SELECT card_type type FROM tiles WHERE card_location = '{$location}' AND card_location_arg = {$arg}");
        $id = self::getUniqueValueFromDB("SELECT card_id id FROM tiles WHERE card_location = '{$location}' AND card_location_arg = {$arg}");

        if (($type == 1) || ($type == 2))
        {
            self::DbQuery( "UPDATE player set azote = azote +1  WHERE player_id = {$this->player_id}" );
            self::DbQuery( "UPDATE tiles set card_type_arg = 1  WHERE card_type = {$type}" );
            undergrove::$instance->tiles->moveCard( $id, 'discard');
            undergrove::$instance->notifyAllPlayers("usebonustile",clienttranslate( '${player_name} uses a bonus tile and gains ${n}' ), array(
            
                'id' =>  $varg1,
                'player_name' => $this->player_name,
                'n' => undergrove::$instance->getLogsRessource(1),
                               
            )
            );
        }

        if (($type == 3) || ($type == 4))
        {
            self::DbQuery( "UPDATE player set phosphore = phosphore +1  WHERE player_id = {$this->player_id}" );
            self::DbQuery( "UPDATE tiles set card_type_arg = 1  WHERE card_type = {$type}" );
            undergrove::$instance->tiles->moveCard( $id, 'discard');
            undergrove::$instance->notifyAllPlayers("usebonustile",clienttranslate( '${player_name} uses a bonus tile and gains ${p}' ), array(
            
                'id' =>  $varg1,
                'player_name' => $this->player_name,
                'p' => undergrove::$instance->getLogsRessource(2),
                               
            )
            );
        }
        
                
        if (($type == 5) || ($type == 6))
        {
            self::DbQuery( "UPDATE player set potassium = potassium +1  WHERE player_id = {$this->player_id}" );
            self::DbQuery( "UPDATE tiles set card_type_arg = 1  WHERE card_type = {$type}" );
            undergrove::$instance->tiles->moveCard( $id, 'discard');
            undergrove::$instance->notifyAllPlayers("usebonustile",clienttranslate( '${player_name} uses a bonus tile and gains ${k}' ), array(
            
                'id' =>  $varg1,
                'player_name' => $this->player_name,
                'k' => undergrove::$instance->getLogsRessource(3),
                               
            )
            );
        }

        if (($type == 7) || ($type == 8))
        {
            self::DbQuery( "UPDATE player set carbone = carbone +1  WHERE player_id = {$this->player_id}" );

            $compteurcarbone = undergrove::$instance->getGameStateValue('compteurcarbone') + 1;
            undergrove::$instance->setGameStateValue('compteurcarbone', $compteurcarbone);

            self::DbQuery( "UPDATE tiles set card_type_arg = 1  WHERE card_type = {$type}" );
            undergrove::$instance->tiles->moveCard( $id, 'discard');
            undergrove::$instance->notifyAllPlayers("usebonustile",clienttranslate( '${player_name} uses a bonus tile and gains ${c}' ), array(
            
                'id' =>  $varg1,
                'player_name' => $this->player_name,
                'c' => undergrove::$instance->getLogsRessource(4),
                               
            )
            );
        }

        if (($type == 9) || ($type == 10))
        {
            self::DbQuery( "UPDATE player set activation_b = 1  WHERE player_id = {$this->player_id}" );
            self::DbQuery( "UPDATE player set activation_p = 1  WHERE player_id = {$this->player_id}" );
            self::DbQuery( "UPDATE player set activation_g = 1  WHERE player_id = {$this->player_id}" );
            self::DbQuery( "UPDATE player set activation_y = 1  WHERE player_id = {$this->player_id}" );
            self::DbQuery( "UPDATE tiles set card_type_arg = 1  WHERE card_type = {$type}" );
            undergrove::$instance->tiles->moveCard( $id, 'discard');
            undergrove::$instance->notifyAllPlayers("usebonustile",clienttranslate( '${player_name} uses a bonus tile and reactivates the tokens' ), array(
            
                'id' =>  $varg1,
                'player_name' => $this->player_name,             
            )
            );
        }




        undergrove::$instance->MajRessources();
        undergrove::$instance->addPending($this->player_id, "NormalTurn");

    }

  
    
/////////////////////////////////////////////////////////////////////////////////
//     _____                          _                
//    |  __ \                        | |               
//    | |__) |___ _ __  _ __ ___   __| |_   _  ___ ___ 
//    |  _  // _ \ '_ \| '__/ _ \ / _` | | | |/ __/ _ \
//    | | \ \  __/ |_) | | | (_) | (_| | |_| | (_|  __/
//    |_|  \_\___| .__/|_|  \___/ \__,_|\__,_|\___\___|
//              | |                                   
//              |_|                                   
/////////////////////////////////////////////////////////////////////////////////

    function argReproduce($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} does the Reproduce action');
        

        $phosphore = self::getUniqueValueFromDB("SELECT phosphore FROM player WHERE player_id={$this->player_id}") -2;
        $azote = self::getUniqueValueFromDB("SELECT azote FROM player WHERE player_id={$this->player_id}");
        $potassium= self::getUniqueValueFromDB("SELECT potassium FROM player WHERE player_id={$this->player_id}");

        undergrove::$instance->setGameStateValue('variable1', 0);
        undergrove::$instance->setGameStateValue('variable2', 0);
        
        
        
        
        $emplacementsemi = count (self::getObjectListFromDB("SELECT id id FROM foret WHERE type='emplacement_semi'"));

        
        if ((($phosphore >=1) || ($azote >=1) || ($potassium >=1 )) && ( $emplacementsemi > 0))
        {
        $ret['titleyou'] = clienttranslate('(Optional) ${you} can place a mushroom from your hand in the forest by paying a resource of your choice');
        $ret['buttons'][]="placechampireproduce";
        $ret['buttons'][]="noplacechampireproduce";            
        $ret['buttons'][]="Cancel";
        }

        if ((($phosphore >=1) || ($azote >=1) || ($potassium >=1 )) && ( $emplacementsemi == 0))
        {
        $ret['titleyou'] = clienttranslate('${you} must place a mushroom from your hand in the forest by paying a resource of your choice in order to generate a location for your seedling');
        $ret['buttons'][]="placechampireproduce";
              
        $ret['buttons'][]="Cancel";
        }
        
        
        return $ret;
    }

    function Reproduce($parg1, $parg2, $varg1, $varg2)
    {
        $phosphore = self::getUniqueValueFromDB("SELECT phosphore FROM player WHERE player_id={$this->player_id}") -2;
        $azote = self::getUniqueValueFromDB("SELECT azote FROM player WHERE player_id={$this->player_id}");
        $potassium= self::getUniqueValueFromDB("SELECT potassium FROM player WHERE player_id={$this->player_id}");
        if (($phosphore == 0) && ($azote ==0) && ($potassium == 0 ))
        {
        undergrove::$instance->addPending($this->player_id, "ReproduceStep1");
        }

    }
    ////////////////////////////
    /////// Placer CHAMPI //////
    ////////////////////////////

    function argReproducePayerChampi($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} does the Reproduce action');
        $ret['titleyou'] = clienttranslate('${you} must choose the type of resource to spend');

        if ($this->player_azote >= 1)
        {
            $ret['buttons'][]="N";
        }

        if ($this->player_phosphore - 2 >= 1)

        {
            $ret['buttons'][]="P";
        }

        if ($this->player_potassium >= 1)

        {
            $ret['buttons'][]="K";
        }
        
                
                
        $ret['buttons'][]="Cancel";
        
        
        return $ret;
    }

    function ReproducePayerChampi($parg1, $parg2, $varg1, $varg2)
    {

        undergrove::$instance->addPending($this->player_id, "ReproducePlacerChampi1", $varg1);


    }

    function argReproducePlacerChampi1($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} does the Reproduce action');
        $ret['titleyou'] = clienttranslate('${you} must select a mushroom');

        $controle = self::getObjectListFromDB( "SELECT card_id id FROM champignon WHERE card_location LIKE CONCAT('hand_', {$this->player_id})", true );
        
        foreach ($controle as $id)
        {
            $ret["selectable"][]="champi_".$id;
        }
                
        $ret['buttons'][]="Cancel";       
        
        return $ret;
    }

    function ReproducePlacerChampi1($parg1, $parg2, $varg1, $varg2)
    {

       // varg1 = ce qui a été cliqué dans ce arg
       
       
       undergrove::$instance->addPending($this->player_id, "ReproducePlacerChampi2", $varg1, $parg1);


    }

    

    function argReproducePlacerChampi2($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} does the Reproduce action');
        $ret['titleyou'] = clienttranslate('${you} must place the Mushroom');

        $ret["selectable"] = self::getObjectListFromDB( "SELECT location location FROM foret WHERE type = 'emplacement_champi'", true );
        $ret["selected"] = array();
        $ret["selected"][] = $parg1;
        
                
        $ret['buttons'][]="Cancel";    
        
        return $ret;
    }

    function ReproducePlacerChampi2($parg1, $parg2, $varg1, $varg2)
    {

        // parg1 = ce qui est envoyé dans addPending
        // varg1 = ce qui est cliqué dans le arg


        ////////////////////////////
        /////// Payer CHAMPI //////
        ////////////////////////////


        if ($parg2 == "N")
        {
        
        self::DbQuery( "UPDATE player set azote = azote -1  WHERE player_id = {$this->player_id}" );
        }

        if ($parg2 == "P")
        {
        
        self::DbQuery( "UPDATE player set phosphore = phosphore -1  WHERE player_id = {$this->player_id}" );
        }

        if ($parg2 == "K")
        {
        
        self::DbQuery( "UPDATE player set potassium = potassium -1  WHERE player_id = {$this->player_id}" );
        }

    /// maj de tous les elements des panneaux joueurs
    undergrove::$instance->MajRessources();



        $explode = explode("_", $parg1);
        $type = self::getUniqueValueFromDB("SELECT card_type type FROM champignon WHERE card_id={$explode[1]}");
        $origine = self::getUniqueValueFromDB("SELECT card_location location FROM champignon WHERE card_id={$explode[1]}");
        $position = self::getUniqueValueFromDB("SELECT card_location_arg location_arg FROM champignon WHERE card_id={$explode[1]}");
        $carbone = self::getUniqueValueFromDB("SELECT carbone carbone FROM champignon WHERE card_id={$explode[1]}");
        undergrove::$instance->champignon->moveCard( $explode[1], $varg1);
        undergrove::$instance->champignon->pickCardForLocation( 'deck', $origine, $position );
        $newid = self::getUniqueValueFromDB("SELECT card_id id FROM champignon WHERE card_location='{$origine}' AND card_location_arg={$position}");
        self::DbQuery( "UPDATE champignon set card_location = 'hand'  WHERE card_id = {$newid}" );
        


        undergrove::$instance->notifyAllPlayers("move",clienttranslate( '${player_name} places a mushroom' ), array(
            
            'mobile' =>  $parg1,
            'parent' => $varg1,
            'id' => $explode[1],
            'type' => $type,
            'carbone' => $carbone,
            'player_name' => $this->player_name,
        )
        );

                
        //emplacement champi devient champi
        self::DbQuery( "UPDATE foret set type = 'champi' WHERE location = '{$varg1}'" );

        $this->ChampiSpecial($type, $varg1);



    ////////////////////////////
    /////// Creation des nouveaux emplacements dans la BdD //////
    ////////////////////////////

        $explode2 = explode("_", $varg1);
        
        //emplacement_champi
        for ($i=-1; $i<=1; $i++)
        {   
            
                $newx = intval($explode2[1])+$i;
                $newy = intval($explode2[2]);
                $newlocation = 'square_'.$newx.'_'.$newy;
                self::DbQuery( "INSERT INTO foret (type, location) SELECT 'emplacement_champi', '{$newlocation}' WHERE NOT EXISTS (SELECT 1 FROM foret WHERE location = '{$newlocation}')" );
            
        }

        for ($j=-1; $j<=1; $j++)
            {
                $newx = intval($explode2[1]);
                $newy = intval($explode2[2])+$j;
                $newlocation = 'square_'.$newx.'_'.$newy;
                self::DbQuery( "INSERT INTO foret (type, location) SELECT 'emplacement_champi', '{$newlocation}' WHERE NOT EXISTS (SELECT 1 FROM foret WHERE location = '{$newlocation}')" );
            }

        //emplacement_semi
        for ($i=0; $i<=1; $i++)
        {   
            for ($j=0; $j<=1; $j++)
            {
                $newx = intval($explode2[1])+$i;
                $newy = intval($explode2[2])+$j;
                $newlocation = 'circle_'.$newx.'_'.$newy;
                self::DbQuery( "INSERT INTO foret (type, location, carbone) SELECT 'emplacement_semi', '{$newlocation}', 0 WHERE NOT EXISTS (SELECT 1 FROM foret WHERE location = '{$newlocation}')" );
            }
        }

         //emplacement_racine

         $sens = array(1,4,2,3);

         for ($i=0; $i<=1; $i++)
         {   
            for ($j=0; $j<=1; $j++)
            {
                $valeursens = array_shift($sens);
                $x = intval($explode2[1]);
                $y = intval($explode2[2]);
                $newx = intval($explode2[1])+$i;
                $newy = intval($explode2[2])+$j;
                $newlocation = 'minisquare_'.$x.'_'.$y.'_'.$newx.'_'.$newy;
                self::DbQuery( "INSERT INTO foret (type, location, sens_racine) SELECT 'emplacement_racine', '{$newlocation}', '{$valeursens}'  WHERE NOT EXISTS (SELECT 1 FROM foret WHERE location = '{$newlocation}')" );
            }
         }
         
        
        //////verifier si bonus champi activé pour ce joueur
        undergrove::$instance->setGameStateValue('variable1', $newid);
        
        if ($this->player_bonus_champi_reproduce == 0)
        {   
            
            /*undergrove::$instance->notifyAllPlayers("hand",'', array(
            
                'id' =>  $newid,
                'location' => self::getUniqueValueFromDB("SELECT card_location location FROM champignon WHERE card_id={$newid}"),
                'position' => self::getUniqueValueFromDB("SELECT card_location_arg location_arg FROM champignon WHERE card_id={$newid}"),
                'type' => self::getUniqueValueFromDB("SELECT card_type type FROM champignon WHERE card_id={$newid}"),
                
            )
            );*/

        undergrove::$instance->addPending($this->player_id, "ReproduceStep1","nocancel");
        }

        if ($this->player_bonus_champi_reproduce == 1)
        {       
        undergrove::$instance->addPending($this->player_id, "ReproducePlacerChampiBonus1", $newid);
        }


    }

    ////////////////////////////
    /////// Bonus CHAMPI //////
    ////////////////////////////

    
    function argReproducePlacerChampiBonus1($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} does the Reproduce action');
        $ret['titleyou'] = clienttranslate('${you} can select and place 2nd mushroom for free thanks to your bonus');

        $controle = self::getObjectListFromDB( "SELECT card_id id FROM champignon WHERE card_location LIKE CONCAT('hand_', {$this->player_id})", true );
        
        foreach ($controle as $id)
        {
            $ret["selectable"][]="champi_".$id;
        }
                
        $ret['buttons'][]="Nobonuschampi";
        
        $ret['buttons'][]="Undo"; // rajouté
        
        return $ret;
    }

    function ReproducePlacerChampiBonus1($parg1, $parg2, $varg1, $varg2)
    {

       // varg1 = ce qui a été cliqué dans ce arg

       

       if($varg1 == "Nobonuschampi")
        {
            /*undergrove::$instance->notifyAllPlayers("hand",'', array(
            
                'id' =>  $parg1,
                'location' => self::getUniqueValueFromDB("SELECT card_location location FROM champignon WHERE card_id={$parg1}"),
                'position' => self::getUniqueValueFromDB("SELECT card_location_arg location_arg FROM champignon WHERE card_id={$parg1}"),
                'type' => self::getUniqueValueFromDB("SELECT card_type type FROM champignon WHERE card_id={$parg1}"),
                
            )
            ); */

            undergrove::$instance->addPending($this->player_id, "ReproduceStep1","nocancel"); 
        }
        else
        {
            undergrove::$instance->addPending($this->player_id, "ReproducePlacerChampiBonus2", $varg1, $parg1);
    
        }
       
       
       


    }

    

    function argReproducePlacerChampiBonus2($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} does the Reproduce action');
        $ret['titleyou'] = clienttranslate('${you} must place the mushroom');

        $ret["selectable"] = self::getObjectListFromDB( "SELECT location location FROM foret WHERE type = 'emplacement_champi'", true );

        $ret["selected"] = array();
        $ret["selected"][] = $parg1;
                
        $ret['buttons'][]="Cancel2";    
        
        return $ret;
    }

    function ReproducePlacerChampiBonus2($parg1, $parg2, $varg1, $varg2)
    {

        // parg1 = champi hand (ce qui a été cliqué au step précedent le varg devient parg)
        // varg1 = square destination (ce qui a été cliqué dans ce arg)

        $explode = explode("_", $parg1);
        $type = self::getUniqueValueFromDB("SELECT card_type type FROM champignon WHERE card_id={$explode[1]}");
        $origine = self::getUniqueValueFromDB("SELECT card_location location FROM champignon WHERE card_id={$explode[1]}");
        $position = self::getUniqueValueFromDB("SELECT card_location_arg location_arg FROM champignon WHERE card_id={$explode[1]}");
        $carbone = self::getUniqueValueFromDB("SELECT carbone carbone FROM champignon WHERE card_id={$explode[1]}");
        undergrove::$instance->champignon->moveCard( $explode[1], $varg1);
        undergrove::$instance->champignon->pickCardForLocation( 'deck', $origine, $position );
        $newid = self::getUniqueValueFromDB("SELECT card_id id FROM champignon WHERE card_location='{$origine}' AND card_location_arg={$position}");
        self::DbQuery( "UPDATE champignon set card_location = 'hand'  WHERE card_id = {$newid}" );

        undergrove::$instance->notifyAllPlayers("move",clienttranslate( '${player_name} places a mushroom (bonus)' ), array(
            
            'mobile' =>  $parg1,
            'parent' => $varg1,
            'id' => $explode[1],
            'type' => $type,
            'carbone' => $carbone, 
            'player_name' => $this->player_name,
        )
        );
        
        //emplacement champi devient champi
        self::DbQuery( "UPDATE foret set type = 'champi' WHERE location = '{$varg1}'" );

        $this->ChampiSpecial($type, $varg1);

    
    ////////////////////////////
    /////// Creation des nouveaux emplacements dans la BdD //////
    ////////////////////////////

        $explode2 = explode("_", $varg1);
        
        //emplacement_champi
        for ($i=-1; $i<=1; $i++)
        {   
            
                $newx = intval($explode2[1])+$i;
                $newy = intval($explode2[2]);
                $newlocation = 'square_'.$newx.'_'.$newy;
                self::DbQuery( "INSERT INTO foret (type, location) SELECT 'emplacement_champi', '{$newlocation}' WHERE NOT EXISTS (SELECT 1 FROM foret WHERE location = '{$newlocation}')" );
            
        }

        for ($j=-1; $j<=1; $j++)
            {
                $newx = intval($explode2[1]);
                $newy = intval($explode2[2])+$j;
                $newlocation = 'square_'.$newx.'_'.$newy;
                self::DbQuery( "INSERT INTO foret (type, location) SELECT 'emplacement_champi', '{$newlocation}' WHERE NOT EXISTS (SELECT 1 FROM foret WHERE location = '{$newlocation}')" );
            }

        //emplacement_semi
        for ($i=0; $i<=1; $i++)
        {   
            for ($j=0; $j<=1; $j++)
            {
                $newx = intval($explode2[1])+$i;
                $newy = intval($explode2[2])+$j;
                $newlocation = 'circle_'.$newx.'_'.$newy;
                self::DbQuery( "INSERT INTO foret (type, location, carbone) SELECT 'emplacement_semi', '{$newlocation}', 0 WHERE NOT EXISTS (SELECT 1 FROM foret WHERE location = '{$newlocation}')" );
            }
        }

         //emplacement_racine

         $sens = array(1,4,2,3);

         for ($i=0; $i<=1; $i++)
         {   
            for ($j=0; $j<=1; $j++)
            {
                $valeursens = array_shift($sens);
                $x = intval($explode2[1]);
                $y = intval($explode2[2]);
                $newx = intval($explode2[1])+$i;
                $newy = intval($explode2[2])+$j;
                $newlocation = 'minisquare_'.$x.'_'.$y.'_'.$newx.'_'.$newy;
                self::DbQuery( "INSERT INTO foret (type, location, sens_racine) SELECT 'emplacement_racine', '{$newlocation}', '{$valeursens}'  WHERE NOT EXISTS (SELECT 1 FROM foret WHERE location = '{$newlocation}')" );
            }
         }
         
         undergrove::$instance->setGameStateValue('variable2', $newid);
        /*undergrove::$instance->notifyAllPlayers("hand",'', array(
            
            'id' =>  $parg2,
            'location' => self::getUniqueValueFromDB("SELECT card_location location FROM champignon WHERE card_id={$parg2}"),
            'position' => self::getUniqueValueFromDB("SELECT card_location_arg location_arg FROM champignon WHERE card_id={$parg2}"),
            'type' => self::getUniqueValueFromDB("SELECT card_type type FROM champignon WHERE card_id={$parg2}"),
            
        )
        );  
        
        undergrove::$instance->notifyAllPlayers("hand",'', array(
            
            'id' =>  $newid,
            'location' => self::getUniqueValueFromDB("SELECT card_location location FROM champignon WHERE card_id={$newid}"),
            'position' => self::getUniqueValueFromDB("SELECT card_location_arg location_arg FROM champignon WHERE card_id={$newid}"),
            'type' => self::getUniqueValueFromDB("SELECT card_type type FROM champignon WHERE card_id={$newid}"),
            
        )
        );   */
        
              
        undergrove::$instance->addPending($this->player_id, "ReproduceStep1","nocancel");
        


    }





    ////////////////////////////
    /////// Placer SEMI //////
    ////////////////////////////

    function argReproduceStep1($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} does the Reproduce action');
        $ret['titleyou'] = clienttranslate('${you} must choose a location for your seedling');

        $ret["selectable"] = self::getObjectListFromDB( "SELECT location location FROM foret WHERE type = 'emplacement_semi'", true );
        
        
        if (($parg1 !="nocancel"))  
        {
            $ret['buttons'][]="Cancel";
        }

        else
        {
            $ret['buttons'][]="Undo"; // rajouté
        }
        
        
        return $ret;
    }

    function ReproduceStep1($parg1, $parg2, $varg1, $varg2)
    {
        // parg1 =  (ce qui a été cliqué au step précedent le varg devient parg et qui envoyé dans game.php dans la fonction d'action)
        // varg1 =  (ce qui a été cliqué dans ce arg)
        self::DbQuery( "UPDATE player set semi = semi -1  WHERE player_id = {$this->player_id}" );
        self::DbQuery( "UPDATE foret set type = 'semi' WHERE location = '{$varg1}'" );
        self::DbQuery( "UPDATE foret set player_id = {$this->player_id} WHERE location = '{$varg1}'" );

        self::DbQuery( "UPDATE player set phosphore = phosphore -2  WHERE player_id = {$this->player_id}" );
        self::DbQuery( "UPDATE player set carbone = carbone -1  WHERE player_id = {$this->player_id}" );
        $carbone= self::getUniqueValueFromDB("SELECT carbone FROM foret WHERE location='{$varg1}'");


        $explode = explode("_", $varg1);
        $filtre = "circle_".$explode[1]."_";
        $count = count(self::getObjectListFromDB( "SELECT id id FROM foret WHERE location LIKE '$filtre%' AND player_id = {$this->player_id}", true ));
        if ($count == 1)
        {
        $numero = "p".$this->player_no;
        self::DbQuery( "UPDATE goal set {$numero} = {$numero} +1  WHERE card_type = 3" );   
        }
        



        undergrove::$instance->notifyAllPlayers("placesemi",clienttranslate( '${player_name} places a seedling' ), array(
            
            'cible' =>  $varg1,
            'color' =>  $this->player_color,
            'carbone' => $carbone,
            'player_name' => $this->player_name,
             
        )
        );

               
        /// maj de tous les elements des panneaux joueurs
        undergrove::$instance->MajRessources();
        



        
        undergrove::$instance->addPending($this->player_id, "ReproduceStep2", $varg1);
        

    }

    ////////////////////////////
    /////// Placer RACINE //////
    ////////////////////////////

    function argReproduceStep2($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} does the Reproduce action');
        $ret['titleyou'] = clienttranslate('${you} must choose a location for your root');

        $explode = explode("_", $parg1);
        $test = "\_".$explode[1]."\_".$explode[2];  // echapement des "_" pour qu'il ne les prennent pas pour des jokers

        $ret["selectable"] = self::getObjectListFromDB( "SELECT location location FROM foret WHERE location LIKE '%$test' AND type = 'emplacement_racine'", true );

        $ret['buttons'][]="Undo"; // rajouté
  
        return $ret;
    }


    function ReproduceStep2($parg1, $parg2, $varg1, $varg2)
    {
        // parg1 =  (ce qui a été cliqué au step précedent le varg devient parg et qui envoyé dans game.php dans la fonction d'action)
        // varg1 =  (ce qui a été cliqué dans ce arg)

        $sens = self::getUniqueValueFromDB("SELECT sens_racine FROM foret WHERE location='{$varg1}'");

        undergrove::$instance->notifyAllPlayers("placeracine",clienttranslate( '${player_name} places a root' ), array(
            
            'cible' =>  $varg1,
            'color' =>  $this->player_color,
            'sens' => $sens,
            'player_name' => $this->player_name,
             
        )
        );

        $this->TestBonusPermanent($varg1);
        $this->ScoreRacine ($varg1);

        self::DbQuery( "UPDATE player set racine = racine -1  WHERE player_id = {$this->player_id}" );
        self::DbQuery( "UPDATE foret set type = 'racine' WHERE location = '{$varg1}'" );
        self::DbQuery( "UPDATE foret set player_id = {$this->player_id} WHERE location = '{$varg1}'" );

        $exploderacine = explode("_", $varg1);
        $square = "square_".$exploderacine[1]."_".$exploderacine[2];
        $type = self::getUniqueValueFromDB("SELECT card_type type FROM champignon WHERE card_location = '{$square}'");
        undergrove::$instance->setGameStateValue('goalracine1', $type);

        /// maj de tous les elements des panneaux joueurs
        undergrove::$instance->MajRessources();

        $nbracine = self::getUniqueValueFromDB("SELECT racine FROM player WHERE player_id={$this->player_id}");
        
        
        $explode = explode("_", $parg1);
        $test = "\_".$explode[1]."\_".$explode[2];  // echapement des "_" pour qu'il ne les prennent pas pour des jokers
        
        $tableau['test'] = self::getObjectListFromDB( "SELECT location location FROM foret WHERE location LIKE '%$test' AND type = 'emplacement_racine'", true );
        

        $emplacementracine = 0;
        if ($tableau['test'] != NULL)
        {
        $emplacementracine = count ($tableau); 
        }

        
        $testpermanent = undergrove::$instance->getGameStateValue('idcopieur'); // pour le test de bonus racine dans le meme tour
        
        if (($this->player_bonus_racine_reproduce == 0) || ($emplacementracine == 0) || ($nbracine == 0) || ($testpermanent == 1))
        {       
            
            
            undergrove::$instance->setGameStateValue('idcopieur', 0);
            undergrove::$instance->GoalTrack();
            undergrove::$instance->CheckEnd();
            //undergrove::$instance->addPendingFirst($this->player_id, "NormalTurn");
            undergrove::$instance->addPending($this->player_id, "Confirm");
        }

        if (($this->player_bonus_racine_reproduce == 1) && ($nbracine >= 1) && ($emplacementracine > 0) && (($testpermanent == 0)||($testpermanent == 2)))
        {       
        undergrove::$instance->addPending($this->player_id, "ReproducePlacerRacineBonus", $parg1);
        }


        

    }

    ////////////////////////////
    /////// Bonus RACINE //////
    ////////////////////////////
    

    
    function argReproducePlacerRacineBonus($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} does the Reproduce action');
        $ret['titleyou'] = clienttranslate('${you} can place a 2nd root around the same seedling thanks to your bonus');

        $explode = explode("_", $parg1);
        $test = "\_".$explode[1]."\_".$explode[2];  // echampement des "_" pour qu'il ne les prennent pas pour des jokers

        $ret["selectable"] = self::getObjectListFromDB( "SELECT location location FROM foret WHERE location LIKE '%$test' AND type = 'emplacement_racine'", true );

        $ret['buttons'][]="Findetour3"; 

        $ret['buttons'][]="Undo"; // rajouté

        
  
        return $ret;
    }


    function ReproducePlacerRacineBonus($parg1, $parg2, $varg1, $varg2)
    {
        // parg1 =  (ce qui a été cliqué au step précedent le varg devient parg et qui envoyé dans game.php dans la fonction d'action)
        // varg1 =  (ce qui a été cliqué dans ce arg)

        if($varg1 == 'Findetour3')
        {
            
        }

        else{

        $sens = self::getUniqueValueFromDB("SELECT sens_racine FROM foret WHERE location='{$varg1}'");

        undergrove::$instance->notifyAllPlayers("placeracine",clienttranslate( '${player_name} places a root (bonus)' ), array(
            
            'cible' =>  $varg1,
            'color' =>  $this->player_color,
            'sens' => $sens,
            'player_name' => $this->player_name,
             
        )
        );

        $this->TestBonusPermanent($varg1);
        $this->ScoreRacine ($varg1);

        self::DbQuery( "UPDATE player set racine = racine -1  WHERE player_id = {$this->player_id}" );
        self::DbQuery( "UPDATE foret set type = 'racine' WHERE location = '{$varg1}'" );
        self::DbQuery( "UPDATE foret set player_id = {$this->player_id} WHERE location = '{$varg1}'" );

        $exploderacine = explode("_", $varg1);
        $square = "square_".$exploderacine[1]."_".$exploderacine[2];
        $type = self::getUniqueValueFromDB("SELECT card_type type FROM champignon WHERE card_location = '{$square}'");
        undergrove::$instance->setGameStateValue('goalracine2', $type);

        
        

    }


        /// maj de tous les elements des panneaux joueurs
        undergrove::$instance->MajRessources();
        
        
        undergrove::$instance->GoalTrack();  
        undergrove::$instance->CheckEnd();
            //undergrove::$instance->addPendingFirst($this->player_id, "NormalTurn");
            undergrove::$instance->addPending($this->player_id, "Confirm");
    
    }


/////////////////////////////////////////////////////////////////////////////////
//     _____           _                   
//    |  __ \         | |                  
//    | |__) |_ _ _ __| |_ _ __   ___ _ __ 
//    |  ___/ _` | '__| __| '_ \ / _ \ '__|
//    | |  | (_| | |  | |_| | | |  __/ |   
//    |_|   \__,_|_|   \__|_| |_|\___|_|   
//                                        
/////////////////////////////////////////////////////////////////////////////////       

function argPartner($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} does the Partner action');
        
        undergrove::$instance->setGameStateValue('variable1', 0);
        undergrove::$instance->setGameStateValue('variable2', 0);

        $phosphore = self::getUniqueValueFromDB("SELECT phosphore FROM player WHERE player_id={$this->player_id}");
        $azote = self::getUniqueValueFromDB("SELECT azote FROM player WHERE player_id={$this->player_id}");
        $potassium= self::getUniqueValueFromDB("SELECT potassium FROM player WHERE player_id={$this->player_id}")-2;
        $carbone= self::getUniqueValueFromDB("SELECT carbone FROM player WHERE player_id={$this->player_id}") -1;

        /// test obligation placer champi
        $tableau = array();
        $tableau2 = array();
        $square=array();
        $tableau["racinelibre"] = self::getObjectListFromDB( "SELECT location location FROM foret WHERE type = 'emplacement_racine'", true );

        foreach ($tableau["racinelibre"] as $racine)
        {
            
            $explode = explode("_", $racine);
            $controle = "circle_".$explode[3]."_".$explode[4];
            $test = self::getUniqueValueFromDB("SELECT player_id FROM foret WHERE location='{$controle}' AND (type='semi' OR type='arbre')");
            if ($test == $this->player_id)
            {
                $tableau2[]=$racine;
            }

        }

        if ($tableau2 == NULL)
        {
        
        $semi=array();  
        $semi = self::getObjectListFromDB( "SELECT location location FROM foret WHERE (type = 'semi' OR type ='arbre') AND player_id = {$this->player_id}" );
        
        foreach ($semi as $emplacement)
        {
            $circle = explode ("_",$emplacement['location']);
            
            $a = intval($circle[1]);
            $b = intval($circle[2]);
            
            
            for ($i=-1; $i<=0; $i++)
            {
                for ($j=-1; $j<=0; $j++)
                {
                    $newa = $a + $i;
                    $newb = $b + $j;
                    $coord = "square_".$newa."_".$newb;
                    
                    $test = self::getUniqueValueFromDB("SELECT location FROM foret WHERE location = '{$coord}' AND type = 'emplacement_champi'");
                    if ($test != NULL)
                    {
                        $square[] = $test;
                    }
                    
                    
                }
            }
        }

        }
        /// fin test obligation placer champi

        if ((($phosphore >=1) || ($azote >=1) || ($potassium >=1 )) && ($this->player_racine >=1) && ($tableau2 != NULL))
        {
        $ret['titleyou'] = clienttranslate('(Optional) ${you} can place a mushroom from your hand in the forest by paying a resource of your choice');
        $ret['buttons'][]="placechampipartner";
        $ret['buttons'][]="noplacechampipartner";            
        $ret['buttons'][]="Cancel";
        }

        
        if ((($phosphore >=1) || ($azote >=1) || ($potassium >=1 )) && ($tableau2 == NULL) && ($square != NULL))
        {
        $ret['titleyou'] = clienttranslate('${you} must place a mushroom from your hand in the forest by paying a resource of your choice, connected to one of your seedling, in order to generate a location for your root');
        $ret['buttons'][]="placechampipartner";
        $ret['buttons'][]="Cancel";
        }
        
        
        
        return $ret;
    }

    function Partner($parg1, $parg2, $varg1, $varg2)
    {
        $phosphore = self::getUniqueValueFromDB("SELECT phosphore FROM player WHERE player_id={$this->player_id}");
        $azote = self::getUniqueValueFromDB("SELECT azote FROM player WHERE player_id={$this->player_id}");
        $potassium= self::getUniqueValueFromDB("SELECT potassium FROM player WHERE player_id={$this->player_id}")-2;
        $carbone= self::getUniqueValueFromDB("SELECT carbone FROM player WHERE player_id={$this->player_id}") -1;
        if (($phosphore == 0) && ($azote == 0) && ($potassium == 0 ))
        {
        undergrove::$instance->addPending($this->player_id, "PartnerStep1");
        }

    }

    ////////////////////////////
    /////// Placer CHAMPI //////
    ////////////////////////////

    function argPartnerPayerChampi($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} does the Partner action');
        $ret['titleyou'] = clienttranslate('${you} must choose the type of resource to spend');

        if ($this->player_azote >= 1)
        {
            $ret['buttons'][]="N";
        }

        if ($this->player_phosphore >= 1)

        {
            $ret['buttons'][]="P";
        }

        if ($this->player_potassium - 2 >= 1)

        {
            $ret['buttons'][]="K";
        }
        
                
                
        $ret['buttons'][]="Cancel";
        
        
        return $ret;
    }

    function PartnerPayerChampi($parg1, $parg2, $varg1, $varg2)
    {

        undergrove::$instance->addPending($this->player_id, "PartnerPlacerChampi1", $varg1);


    }

    function argPartnerPlacerChampi1($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} does the Partner action');
        $ret['titleyou'] = clienttranslate('${you} must select a mushroom');

        $controle = self::getObjectListFromDB( "SELECT card_id id FROM champignon WHERE card_location LIKE CONCAT('hand_', {$this->player_id})", true );
        
        foreach ($controle as $id)
        {
            $ret["selectable"][]="champi_".$id;
        }
                
        $ret['buttons'][]="Cancel";       
        
        return $ret;
    }

    function PartnerPlacerChampi1($parg1, $parg2, $varg1, $varg2)
    {

       // varg1 = ce qui a été cliqué dans ce arg
       
       
       undergrove::$instance->addPending($this->player_id, "PartnerPlacerChampi2", $varg1, $parg1);


    }

    

    function argPartnerPlacerChampi2($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} does the Partner action');
        $ret['titleyou'] = clienttranslate('${you} must place the mushroom');

        /// test obligation placer champi
        $tableau = array();
        $tableau2 = array();
        $square=array();
        $tableau["racinelibre"] = self::getObjectListFromDB( "SELECT location location FROM foret WHERE type = 'emplacement_racine'", true );

        foreach ($tableau["racinelibre"] as $racine)
        {
            
            $explode = explode("_", $racine);
            $controle = "circle_".$explode[3]."_".$explode[4];
            $test = self::getUniqueValueFromDB("SELECT player_id FROM foret WHERE location='{$controle}' AND (type='semi' OR type='arbre')");
            if ($test == $this->player_id)
            {
                $tableau2[]=$racine;
            }

        }

        if ($tableau2 == NULL)
        {
        
        $semi=array();  
        $semi = self::getObjectListFromDB( "SELECT location location FROM foret WHERE (type = 'semi' OR type ='arbre') AND player_id = {$this->player_id}" );
        
        foreach ($semi as $emplacement)
        {
            $circle = explode ("_",$emplacement['location']);
            
            $a = intval($circle[1]);
            $b = intval($circle[2]);
            
            
            for ($i=-1; $i<=0; $i++)
            {
                for ($j=-1; $j<=0; $j++)
                {
                    $newa = $a + $i;
                    $newb = $b + $j;
                    $coord = "square_".$newa."_".$newb;
                    
                    $test = self::getUniqueValueFromDB("SELECT location FROM foret WHERE location = '{$coord}' AND type = 'emplacement_champi'");
                    if ($test != NULL)
                    {
                        $square[] = $test;
                    }
                    
                    
                }
            }
        }

        }
        /// fin test obligation placer champi
        if (($this->player_carbone >= 1) && ($this->player_potassium >= 2) && ($this->player_racine >=1) && ($tableau2 != NULL))
        {
        $ret["selectable"] = self::getObjectListFromDB( "SELECT location location FROM foret WHERE type = 'emplacement_champi'", true );
        }

        if (($this->player_carbone >= 1) && ($this->player_potassium >= 2) && ($this->player_racine >=1) && ($tableau2 == NULL) && ($square != NULL))
        {
            $ret["selectable"] = $square;
        }
        
        
        
        
        
        
        $ret["selected"] = array();
        $ret["selected"][] = $parg1;
        
                
        $ret['buttons'][]="Cancel";    
        
        return $ret;
    }

    function PartnerPlacerChampi2($parg1, $parg2, $varg1, $varg2)
    {

        // parg1 = champi hand (ce qui a été cliqué au step précedent le varg devient parg)
        // varg1 = square destination (ce qui a été cliqué dans ce arg)

        ////////////////////////////
        /////// Payer CHAMPI //////
        ////////////////////////////


        if ($parg2 == "N")
        {
        
        self::DbQuery( "UPDATE player set azote = azote -1  WHERE player_id = {$this->player_id}" );
        }

        if ($parg2 == "P")
        {
        
        self::DbQuery( "UPDATE player set phosphore = phosphore -1  WHERE player_id = {$this->player_id}" );
        }

        if ($parg2 == "K")
        {
        
        self::DbQuery( "UPDATE player set potassium = potassium -1  WHERE player_id = {$this->player_id}" );
        }

        /// maj de tous les elements des panneaux joueurs
        undergrove::$instance->MajRessources();

        $explode = explode("_", $parg1);
        $type = self::getUniqueValueFromDB("SELECT card_type type FROM champignon WHERE card_id={$explode[1]}");
        $origine = self::getUniqueValueFromDB("SELECT card_location location FROM champignon WHERE card_id={$explode[1]}");
        $position = self::getUniqueValueFromDB("SELECT card_location_arg location_arg FROM champignon WHERE card_id={$explode[1]}");
        $carbone = self::getUniqueValueFromDB("SELECT carbone carbone FROM champignon WHERE card_id={$explode[1]}");
        undergrove::$instance->champignon->moveCard( $explode[1], $varg1);
        undergrove::$instance->champignon->pickCardForLocation( 'deck', $origine, $position );
        $newid = self::getUniqueValueFromDB("SELECT card_id id FROM champignon WHERE card_location='{$origine}' AND card_location_arg={$position}");
        self::DbQuery( "UPDATE champignon set card_location = 'hand'  WHERE card_id = {$newid}" );


        undergrove::$instance->notifyAllPlayers("move",clienttranslate( '${player_name} places a mushroom' ), array(
            
            'mobile' =>  $parg1,
            'parent' => $varg1,
            'id' => $explode[1],
            'type' => $type,
            'carbone' => $carbone,
            'player_name' => $this->player_name,
        )
        );
        
        //emplacement champi devient champi
        self::DbQuery( "UPDATE foret set type = 'champi' WHERE location = '{$varg1}'" );


        $this->ChampiSpecial($type, $varg1);


    ////////////////////////////
    /////// Creation des nouveaux emplacements dans la BdD //////
    ////////////////////////////

        $explode2 = explode("_", $varg1);
        
        //emplacement_champi
        for ($i=-1; $i<=1; $i++)
        {   
            
                $newx = intval($explode2[1])+$i;
                $newy = intval($explode2[2]);
                $newlocation = 'square_'.$newx.'_'.$newy;
                self::DbQuery( "INSERT INTO foret (type, location) SELECT 'emplacement_champi', '{$newlocation}' WHERE NOT EXISTS (SELECT 1 FROM foret WHERE location = '{$newlocation}')" );
            
        }

        for ($j=-1; $j<=1; $j++)
            {
                $newx = intval($explode2[1]);
                $newy = intval($explode2[2])+$j;
                $newlocation = 'square_'.$newx.'_'.$newy;
                self::DbQuery( "INSERT INTO foret (type, location) SELECT 'emplacement_champi', '{$newlocation}' WHERE NOT EXISTS (SELECT 1 FROM foret WHERE location = '{$newlocation}')" );
            }

        //emplacement_semi
        for ($i=0; $i<=1; $i++)
        {   
            for ($j=0; $j<=1; $j++)
            {
                $newx = intval($explode2[1])+$i;
                $newy = intval($explode2[2])+$j;
                $newlocation = 'circle_'.$newx.'_'.$newy;
                self::DbQuery( "INSERT INTO foret (type, location, carbone) SELECT 'emplacement_semi', '{$newlocation}', 0 WHERE NOT EXISTS (SELECT 1 FROM foret WHERE location = '{$newlocation}')" );
            }
        }

         //emplacement_racine

         $sens = array(1,4,2,3);

         for ($i=0; $i<=1; $i++)
         {   
            for ($j=0; $j<=1; $j++)
            {
                $valeursens = array_shift($sens);
                $x = intval($explode2[1]);
                $y = intval($explode2[2]);
                $newx = intval($explode2[1])+$i;
                $newy = intval($explode2[2])+$j;
                $newlocation = 'minisquare_'.$x.'_'.$y.'_'.$newx.'_'.$newy;
                self::DbQuery( "INSERT INTO foret (type, location, sens_racine) SELECT 'emplacement_racine', '{$newlocation}', '{$valeursens}'  WHERE NOT EXISTS (SELECT 1 FROM foret WHERE location = '{$newlocation}')" );
            }
         }
         
        
        //////verifier si bonus champi activé pour ce joueur
        undergrove::$instance->setGameStateValue('variable1', $newid);
        
        
        if ($this->player_bonus_champi_partner == 0)
        {       
            /*undergrove::$instance->notifyAllPlayers("hand",'', array(
            
                'id' =>  $newid,
                'location' => self::getUniqueValueFromDB("SELECT card_location location FROM champignon WHERE card_id={$newid}"),
                'position' => self::getUniqueValueFromDB("SELECT card_location_arg location_arg FROM champignon WHERE card_id={$newid}"),
                'type' => self::getUniqueValueFromDB("SELECT card_type type FROM champignon WHERE card_id={$newid}"),
                
            )
            );*/
        undergrove::$instance->addPending($this->player_id, "PartnerStep1","nocancel");
        }

        if ($this->player_bonus_champi_partner == 1)
        {       
        undergrove::$instance->addPending($this->player_id, "PartnerPlacerChampiBonus1", $newid);
        }


    }

    ////////////////////////////
    /////// Bonus CHAMPI //////
    ////////////////////////////

    
    function argPartnerPlacerChampiBonus1($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} does the Partner action');
        $ret['titleyou'] = clienttranslate('${you} can select and place 2nd mushroom for free thanks to your bonus');

        $controle = self::getObjectListFromDB( "SELECT card_id id FROM champignon WHERE card_location LIKE CONCAT('hand_', {$this->player_id})", true );
        
        foreach ($controle as $id)
        {
            $ret["selectable"][]="champi_".$id;
        }

        $ret['buttons'][]="Nobonuschampi"; 

        $ret['buttons'][]="Undo"; // rajouté
                
              
        
        return $ret;
    }

    function PartnerPlacerChampiBonus1($parg1, $parg2, $varg1, $varg2)
    {

       // varg1 = ce qui a été cliqué dans ce arg

       
       if($varg1 == "Nobonuschampi")
        {
            /*undergrove::$instance->notifyAllPlayers("hand",'', array(
            
                'id' =>  $parg1,
                'location' => self::getUniqueValueFromDB("SELECT card_location location FROM champignon WHERE card_id={$parg1}"),
                'position' => self::getUniqueValueFromDB("SELECT card_location_arg location_arg FROM champignon WHERE card_id={$parg1}"),
                'type' => self::getUniqueValueFromDB("SELECT card_type type FROM champignon WHERE card_id={$parg1}"),
                
            )
            ); */

        undergrove::$instance->addPending($this->player_id, "PartnerStep1","nocancel"); 
        }
       
       else 
       {
        undergrove::$instance->addPending($this->player_id, "PartnerPlacerChampiBonus2", $varg1, $parg1);
       }


    }

    

    function argPartnerPlacerChampiBonus2($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} does the Partner action');
        $ret['titleyou'] = clienttranslate('${you} must place the mushroom');

        $ret["selectable"] = self::getObjectListFromDB( "SELECT location location FROM foret WHERE type = 'emplacement_champi'", true );

        $ret["selected"] = array();
        $ret["selected"][] = $parg1;
                
        $ret['buttons'][]="Cancel3";    
        
        return $ret;
    }

    function PartnerPlacerChampiBonus2($parg1, $parg2, $varg1, $varg2)
    {

        // parg1 = champi hand (ce qui a été cliqué au step précedent le varg devient parg)
        // varg1 = square destination (ce qui a été cliqué dans ce arg)

        $explode = explode("_", $parg1);
        $type = self::getUniqueValueFromDB("SELECT card_type type FROM champignon WHERE card_id={$explode[1]}");
        $origine = self::getUniqueValueFromDB("SELECT card_location location FROM champignon WHERE card_id={$explode[1]}");
        $position = self::getUniqueValueFromDB("SELECT card_location_arg location_arg FROM champignon WHERE card_id={$explode[1]}");
        $carbone = self::getUniqueValueFromDB("SELECT carbone carbone FROM champignon WHERE card_id={$explode[1]}");
        undergrove::$instance->champignon->moveCard( $explode[1], $varg1);
        undergrove::$instance->champignon->pickCardForLocation( 'deck', $origine, $position );
        $newid = self::getUniqueValueFromDB("SELECT card_id id FROM champignon WHERE card_location='{$origine}' AND card_location_arg={$position}");
        self::DbQuery( "UPDATE champignon set card_location = 'hand'  WHERE card_id = {$newid}" );

        undergrove::$instance->notifyAllPlayers("move",clienttranslate( '${player_name} places a mushroom (bonus)' ), array(
            
            'mobile' =>  $parg1,
            'parent' => $varg1,
            'id' => $explode[1],
            'type' => $type,
            'carbone' => $carbone, 
            'player_name' => $this->player_name,
        )
        );
        
        //emplacement champi devient champi
        self::DbQuery( "UPDATE foret set type = 'champi' WHERE location = '{$varg1}'" );

        $this->ChampiSpecial($type, $varg1);
    
    ////////////////////////////
    /////// Creation des nouveaux emplacements dans la BdD //////
    ////////////////////////////

        $explode2 = explode("_", $varg1);
        
        //emplacement_champi
        for ($i=-1; $i<=1; $i++)
        {   
            
                $newx = intval($explode2[1])+$i;
                $newy = intval($explode2[2]);
                $newlocation = 'square_'.$newx.'_'.$newy;
                self::DbQuery( "INSERT INTO foret (type, location) SELECT 'emplacement_champi', '{$newlocation}' WHERE NOT EXISTS (SELECT 1 FROM foret WHERE location = '{$newlocation}')" );
            
        }

        for ($j=-1; $j<=1; $j++)
            {
                $newx = intval($explode2[1]);
                $newy = intval($explode2[2])+$j;
                $newlocation = 'square_'.$newx.'_'.$newy;
                self::DbQuery( "INSERT INTO foret (type, location) SELECT 'emplacement_champi', '{$newlocation}' WHERE NOT EXISTS (SELECT 1 FROM foret WHERE location = '{$newlocation}')" );
            }

        //emplacement_semi
        for ($i=0; $i<=1; $i++)
        {   
            for ($j=0; $j<=1; $j++)
            {
                $newx = intval($explode2[1])+$i;
                $newy = intval($explode2[2])+$j;
                $newlocation = 'circle_'.$newx.'_'.$newy;
                self::DbQuery( "INSERT INTO foret (type, location, carbone) SELECT 'emplacement_semi', '{$newlocation}', 0 WHERE NOT EXISTS (SELECT 1 FROM foret WHERE location = '{$newlocation}')" );
            }
        }

         //emplacement_racine

         $sens = array(1,4,2,3);

         for ($i=0; $i<=1; $i++)
         {   
            for ($j=0; $j<=1; $j++)
            {
                $valeursens = array_shift($sens);
                $x = intval($explode2[1]);
                $y = intval($explode2[2]);
                $newx = intval($explode2[1])+$i;
                $newy = intval($explode2[2])+$j;
                $newlocation = 'minisquare_'.$x.'_'.$y.'_'.$newx.'_'.$newy;
                self::DbQuery( "INSERT INTO foret (type, location, sens_racine) SELECT 'emplacement_racine', '{$newlocation}', '{$valeursens}'  WHERE NOT EXISTS (SELECT 1 FROM foret WHERE location = '{$newlocation}')" );
            }
         }
         
         undergrove::$instance->setGameStateValue('variable2', $newid);
         /*undergrove::$instance->notifyAllPlayers("hand",'', array(
            
            'id' =>  $parg2,
            'location' => self::getUniqueValueFromDB("SELECT card_location location FROM champignon WHERE card_id={$parg2}"),
            'position' => self::getUniqueValueFromDB("SELECT card_location_arg location_arg FROM champignon WHERE card_id={$parg2}"),
            'type' => self::getUniqueValueFromDB("SELECT card_type type FROM champignon WHERE card_id={$parg2}"),
            
        )
        );  
        
        undergrove::$instance->notifyAllPlayers("hand",'', array(
            
            'id' =>  $newid,
            'location' => self::getUniqueValueFromDB("SELECT card_location location FROM champignon WHERE card_id={$newid}"),
            'position' => self::getUniqueValueFromDB("SELECT card_location_arg location_arg FROM champignon WHERE card_id={$newid}"),
            'type' => self::getUniqueValueFromDB("SELECT card_type type FROM champignon WHERE card_id={$newid}"),
            
        )
        );      */
        
              
        undergrove::$instance->addPending($this->player_id, "PartnerStep1","nocancel");
        


    }


    ////////////////////////////
    /////// Placer RACINE //////
    ////////////////////////////


    function argPartnerStep1($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} does the Partner action');
        $ret['titleyou'] = clienttranslate('${you} must place a root');

        $ret["racinelibre"] = self::getObjectListFromDB( "SELECT location location FROM foret WHERE type = 'emplacement_racine'", true );

        foreach ($ret["racinelibre"] as $racine)
        {
            
            $explode = explode("_", $racine);
            $controle = "circle_".$explode[3]."_".$explode[4];
            $test = self::getUniqueValueFromDB("SELECT player_id FROM foret WHERE location='{$controle}' AND (type='semi' OR type='arbre')");
            if ($test == $this->player_id)
            {
                $ret["selectable"][]=$racine;
            }

        }
                
        if (($parg1 !="nocancel"))  
        {
            $ret['buttons'][]="Cancel";
        }

        else
        {
            $ret['buttons'][]="Undo"; // rajouté
        }
        
        
        return $ret;
    }

    function PartnerStep1($parg1, $parg2, $varg1, $varg2)
    {
        $sens = self::getUniqueValueFromDB("SELECT sens_racine FROM foret WHERE location='{$varg1}'");

        undergrove::$instance->notifyAllPlayers("placeracine",clienttranslate( '${player_name} places a root' ), array(
            
            'cible' =>  $varg1,
            'color' =>  $this->player_color,
            'sens' => $sens,
            'player_name' => $this->player_name,
             
        )
        );

        $this->TestBonusPermanent($varg1);
        $this->ScoreRacine ($varg1);

        self::DbQuery( "UPDATE player set racine = racine -1  WHERE player_id = {$this->player_id}" );
        self::DbQuery( "UPDATE foret set type = 'racine' WHERE location = '{$varg1}'" );
        self::DbQuery( "UPDATE foret set player_id = {$this->player_id} WHERE location = '{$varg1}'" );

        $exploderacine = explode("_", $varg1);
        $square = "square_".$exploderacine[1]."_".$exploderacine[2];
        $type = self::getUniqueValueFromDB("SELECT card_type type FROM champignon WHERE card_location = '{$square}'");
        undergrove::$instance->setGameStateValue('goalracine1', $type);

        $nbracine = self::getUniqueValueFromDB("SELECT racine FROM player WHERE player_id={$this->player_id}");

        $tableau["racinelibre"] = self::getObjectListFromDB( "SELECT location location FROM foret WHERE type = 'emplacement_racine'", true );
        $emplacement = array();
        foreach ($tableau["racinelibre"] as $racine)
        {
            
            $explode = explode("_", $racine);
            $controle = "circle_".$explode[3]."_".$explode[4];
            $test = self::getUniqueValueFromDB("SELECT player_id FROM foret WHERE location='{$controle}' AND (type='semi' OR type='arbre')");
            if ($test == $this->player_id)
            {
                $emplacement[]=$racine;
            }

        }

        $emplacementracine = count ($emplacement); 
        
        
        self::DbQuery( "UPDATE player set potassium = potassium -2  WHERE player_id = {$this->player_id}" );
        self::DbQuery( "UPDATE player set carbone = carbone -1  WHERE player_id = {$this->player_id}" );

        /// maj de tous les elements des panneaux joueurs
        undergrove::$instance->MajRessources();
        
        
        
        
        if (($nbracine == 0) || ($emplacementracine == 0))
        {      
            
            undergrove::$instance->GoalTrack(); 
            undergrove::$instance->CheckEnd();
            //undergrove::$instance->addPendingFirst($this->player_id, "NormalTurn");
            undergrove::$instance->addPending($this->player_id, "Confirm");
        }

        if (($nbracine >= 1) && ($emplacementracine > 0))
        {       
        undergrove::$instance->addPending($this->player_id, "PartnerStep2");
        }


    }

    ////////////////////////////
    /////// Placer 2 nd RACINE //////
    ////////////////////////////


    function argPartnerStep2($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} does the Partner action');
        $ret['titleyou'] = clienttranslate('${you} can place a 2nd root');

        $ret["racinelibre"] = self::getObjectListFromDB( "SELECT location location FROM foret WHERE type = 'emplacement_racine'", true );

        foreach ($ret["racinelibre"] as $racine)
        {
            
            $explode = explode("_", $racine);
            $controle = "circle_".$explode[3]."_".$explode[4];
            $test = self::getUniqueValueFromDB("SELECT player_id FROM foret WHERE location='{$controle}' AND (type='semi' OR type='arbre')");
            if ($test == $this->player_id)
            {
                $ret["selectable"][]=$racine;
            }

        }
                
        

        $ret['buttons'][]="Findetour3";

        $ret['buttons'][]="Undo"; // rajouté
        
        
        return $ret;
    }

    function PartnerStep2($parg1, $parg2, $varg1, $varg2)
    {
        if($varg1 == 'Findetour3')
        {
            
             /// maj de tous les elements des panneaux joueurs
        undergrove::$instance->MajRessources();
        
        
        undergrove::$instance->GoalTrack();  
        undergrove::$instance->CheckEnd();
            //undergrove::$instance->addPendingFirst($this->player_id, "NormalTurn");
            undergrove::$instance->addPending($this->player_id, "Confirm");
        }
        
        
        else{
        
        
        $sens = self::getUniqueValueFromDB("SELECT sens_racine FROM foret WHERE location='{$varg1}'");

        undergrove::$instance->notifyAllPlayers("placeracine",clienttranslate( '${player_name} places a root' ), array(
            
            'cible' =>  $varg1,
            'color' =>  $this->player_color,
            'sens' => $sens,
            'player_name' => $this->player_name,
        )
        );

        $this->TestBonusPermanent($varg1);
        $this->ScoreRacine ($varg1);

        self::DbQuery( "UPDATE player set racine = racine -1  WHERE player_id = {$this->player_id}" );
        self::DbQuery( "UPDATE foret set type = 'racine' WHERE location = '{$varg1}'" );
        self::DbQuery( "UPDATE foret set player_id = {$this->player_id} WHERE location = '{$varg1}'" );

        $exploderacine = explode("_", $varg1);
        $square = "square_".$exploderacine[1]."_".$exploderacine[2];
        $type = self::getUniqueValueFromDB("SELECT card_type type FROM champignon WHERE card_location = '{$square}'");
        undergrove::$instance->setGameStateValue('goalracine2', $type);

        $nbracine = self::getUniqueValueFromDB("SELECT racine FROM player WHERE player_id={$this->player_id}");

        $tableau["racinelibre"] = self::getObjectListFromDB( "SELECT location location FROM foret WHERE type = 'emplacement_racine'", true );
        $emplacement = array();
        foreach ($tableau["racinelibre"] as $racine)
        {
            
            $explode = explode("_", $racine);
            $controle = "circle_".$explode[3]."_".$explode[4];
            $test = self::getUniqueValueFromDB("SELECT player_id FROM foret WHERE location='{$controle}' AND (type='semi' OR type='arbre')");
            if ($test == $this->player_id)
            {
                $emplacement[]=$racine;
            }

        }

        $emplacementracine = count ($emplacement); 
        
        
        
        /// maj de tous les elements des panneaux joueurs
        undergrove::$instance->MajRessources();
        
        $testpermanent = undergrove::$instance->getGameStateValue('idcopieur'); // pour le test de raccne dans le meme tour     
        
        if (($nbracine == 0) || ($emplacementracine == 0) || ($this->player_bonus_racine_partner == 0) || ($testpermanent == 2))
        {   
             
            undergrove::$instance->setGameStateValue('idcopieur', 0);
            undergrove::$instance->GoalTrack();
            undergrove::$instance->CheckEnd();
            //undergrove::$instance->addPendingFirst($this->player_id, "NormalTurn");
            undergrove::$instance->addPending($this->player_id, "Confirm");
        }

        if (($nbracine >= 1) && ($emplacementracine > 0) && ($this->player_bonus_racine_partner == 1) && (($testpermanent == 0)||($testpermanent == 1)))
        {       
        undergrove::$instance->addPending($this->player_id, "Partnerbonusracine");
        }

    }


    }

    ////////////////////////////
    /////// Placer Bonus RACINE //////
    ////////////////////////////


    function argPartnerbonusracine($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} does the Partner action');
        $ret['titleyou'] = clienttranslate('${you} can place a 3rd root thanks to your bonus');

        $ret["racinelibre"] = self::getObjectListFromDB( "SELECT location location FROM foret WHERE type = 'emplacement_racine'", true );

        foreach ($ret["racinelibre"] as $racine)
        {
            
            $explode = explode("_", $racine);
            $controle = "circle_".$explode[3]."_".$explode[4];
            $test = self::getUniqueValueFromDB("SELECT player_id FROM foret WHERE location='{$controle}' AND (type='semi' OR type='arbre')");
            if ($test == $this->player_id)
            {
                $ret["selectable"][]=$racine;
            }

        }
                
        

        $ret['buttons'][]="Findetour3";
        $ret['buttons'][]="Undo"; // rajouté
        
        
        return $ret;
    }

    function Partnerbonusracine($parg1, $parg2, $varg1, $varg2)
    {
        
        
        if($varg1 == 'Findetour3')
        {
            
        }

        else
        {
        
        $sens = self::getUniqueValueFromDB("SELECT sens_racine FROM foret WHERE location='{$varg1}'");

        undergrove::$instance->notifyAllPlayers("placeracine",clienttranslate( '${player_name} places a root (bonus)' ), array(
            
            'cible' =>  $varg1,
            'color' =>  $this->player_color,
            'sens' => $sens,
            'player_name' => $this->player_name,
             
        )
        );

        $this->TestBonusPermanent($varg1);
        $this->ScoreRacine ($varg1);

        self::DbQuery( "UPDATE player set racine = racine -1  WHERE player_id = {$this->player_id}" );
        self::DbQuery( "UPDATE foret set type = 'racine' WHERE location = '{$varg1}'" );
        self::DbQuery( "UPDATE foret set player_id = {$this->player_id} WHERE location = '{$varg1}'" );



        $exploderacine = explode("_", $varg1);
        $square = "square_".$exploderacine[1]."_".$exploderacine[2];
        $type = self::getUniqueValueFromDB("SELECT card_type type FROM champignon WHERE card_location = '{$square}'");
        undergrove::$instance->setGameStateValue('goalracine3', $type);

        
               
    }
        /// maj de tous les elements des panneaux joueurs
        undergrove::$instance->MajRessources();
        
        undergrove::$instance->GoalTrack();
        undergrove::$instance->CheckEnd();
        //undergrove::$instance->addPendingFirst($this->player_id, "NormalTurn");
        undergrove::$instance->addPending($this->player_id, "Confirm");
        
       

    }



/////////////////////////////////////////////////////////////////////////////////  
//   _____  _           _                        _   _               _         
//  |  __ \| |         | |                      | | | |             (_)        
//  | |__) | |__   ___ | |_ ___  ___ _   _ _ __ | |_| |__   ___  ___ _ _______ 
//  |  ___/| '_ \ / _ \| __/ _ \/ __| | | | '_ \| __| '_ \ / _ \/ __| |_  / _ \
//  | |    | | | | (_) | || (_) \__ \ |_| | | | | |_| | | |  __/\__ \ |/ /  __/
//  |_|    |_| |_|\___/ \__\___/|___/\__, |_| |_|\__|_| |_|\___||___/_/___\___|
//                                    __/ |                                    
//                                   |___/                                     
/////////////////////////////////////////////////////////////////////////////////


function argPhoto($parg1, $parg2)
{
    $ret = array();
    $ret["selectable"] = array();
    $ret['buttons'] = array();
    $ret['title'] = clienttranslate('${actplayer} does the Photosynthesis action');
    $ret['titleyou'] = clienttranslate('${you} can exchange a Nitrogen (N) for a Carbon (C)');

    if ($this->player_azote >= 1)
    {
        $ret['buttons'][]="Yesphotoechange";
        $ret['buttons'][]="Nophotoechange";
        $ret['buttons'][]="Cancel";
    }
    
    
    
   
    
        
    return $ret;
}


function Photo($parg1, $parg2, $varg1, $varg2)
{

    if ($varg1 == "Yesphotoechange")
    {
        self::DbQuery( "UPDATE player set azote = azote -1  WHERE player_id = {$this->player_id}" );
        self::DbQuery( "UPDATE player set carbone = carbone+1  WHERE player_id = {$this->player_id}" );

        undergrove::$instance->notifyAllPlayers("message",clienttranslate( '${player_name} exchanges ${n} for ${c}' ), array(
            'player_name' => $this->player_name,
            'n' => undergrove::$instance->getLogsRessource(1),
            'c' => undergrove::$instance->getLogsRessource(4),
                         
        )
        );

        $compteurcarbone = undergrove::$instance->getGameStateValue('compteurcarbone') + 1;
        undergrove::$instance->setGameStateValue('compteurcarbone', $compteurcarbone);

        $numero = "p".$this->player_no;
        self::DbQuery( "UPDATE goal set {$numero} = {$numero} +1  WHERE card_type = 2" );  
        


        /// maj de tous les elements des panneaux joueurs
        undergrove::$instance->MajRessources();
        $azote = self::getUniqueValueFromDB("SELECT azote FROM player WHERE player_id={$this->player_id}");
        if ($azote >= 1)
        {
        undergrove::$instance->addPending($this->player_id, "PhotoEchangeSupp");
        }
        else
        {
            undergrove::$instance->addPending($this->player_id, "PhotoDiscard", 1);
        }
    }
    else
    {
    undergrove::$instance->addPending($this->player_id, "PhotoDiscard", 0);
    }


}

function argPhotoEchangeSupp($parg1, $parg2)
{
    $ret = array();
    $ret["selectable"] = array();
    $ret['buttons'] = array();
    $ret['title'] = clienttranslate('${actplayer} does the Photosynthesis action');
    $ret['titleyou'] = clienttranslate('${you} can exchange another N for a C');

    
    
    
        $ret['buttons'][]="Yesphotoechangesupp";
        $ret['buttons'][]="Nophotoechangesupp";
        
    
        $ret['buttons'][]="Undo"; // rajouté
    
    
   
    
        
    return $ret;
}


function PhotoEchangeSupp($parg1, $parg2, $varg1, $varg2)
{
    
    self::DbQuery( "UPDATE player set azote = azote -1  WHERE player_id = {$this->player_id}" );
        self::DbQuery( "UPDATE player set carbone = carbone+1  WHERE player_id = {$this->player_id}" );

        undergrove::$instance->notifyAllPlayers("message",clienttranslate( '${player_name} exchanges ${n} for ${c}' ), array(
            'player_name' => $this->player_name,
            'n' => undergrove::$instance->getLogsRessource(1),
            'c' => undergrove::$instance->getLogsRessource(4),
                         
        )
        );

        $compteurcarbone = undergrove::$instance->getGameStateValue('compteurcarbone') + 1;
        undergrove::$instance->setGameStateValue('compteurcarbone', $compteurcarbone);
        $numero = "p".$this->player_no;
        self::DbQuery( "UPDATE goal set {$numero} = {$numero} +1  WHERE card_type = 2" ); 
        


        /// maj de tous les elements des panneaux joueurs
        undergrove::$instance->MajRessources();
        $azote = self::getUniqueValueFromDB("SELECT azote FROM player WHERE player_id={$this->player_id}");
        if ($azote >= 1)
        {
        undergrove::$instance->addPending($this->player_id, "PhotoEchangeSupp");
        }
        else
        {
            undergrove::$instance->addPending($this->player_id, "PhotoDiscard", 1);
        }
    

    


}

function argPhotoDiscard($parg1, $parg2)
{
    $ret = array();
    $ret["selectable"] = array();
    $ret['buttons'] = array();
    $ret['title'] = clienttranslate('${actplayer} does the Photosynthesis action');
    $ret['titleyou'] = clienttranslate('${you} can select the mushrooms to discard');

    $ret["selectable2"] = array();
    $controle = self::getObjectListFromDB( "SELECT card_id id FROM champignon WHERE card_location LIKE CONCAT('hand_', {$this->player_id})", true );
        
        foreach ($controle as $id)
        {
            $ret["selectable2"][]="champi_".$id;
        }
       
        $ret['buttons'][]="Validerphotodiscard";
        
        if ($parg1 == 0)
        {
        $ret['buttons'][]="Cancel";
        }

        else{
            $ret['buttons'][]="Undo"; // rajouté
        }

        
    
        
    return $ret;
}


function PhotoDiscard($parg1, $parg2, $varg1, $varg2)
{
    if ($this->player_bonus_carbone == 0)
    {
        self::DbQuery( "UPDATE player set carbone = carbone+2  WHERE player_id = {$this->player_id}" );
        $compteurcarbone = undergrove::$instance->getGameStateValue('compteurcarbone') + 2;
        undergrove::$instance->setGameStateValue('compteurcarbone', $compteurcarbone);
        undergrove::$instance->notifyAllPlayers("message",clienttranslate( '${player_name} gains ${c} ${c} thanks to the Photosynthesize action' ), array(
            'player_name' => $this->player_name,
            'c' => undergrove::$instance->getLogsRessource(4),
                         
        )
        );
    }

    if ($this->player_bonus_carbone == 1)
    {
        self::DbQuery( "UPDATE player set carbone = carbone+3  WHERE player_id = {$this->player_id}" );
        $compteurcarbone = undergrove::$instance->getGameStateValue('compteurcarbone') + 3;
        undergrove::$instance->setGameStateValue('compteurcarbone', $compteurcarbone);
        undergrove::$instance->notifyAllPlayers("message",clienttranslate( '${player_name} gains ${c} ${c} ${c} thanks to the Photosynthesize action' ), array(
            'player_name' => $this->player_name,
            'c' => undergrove::$instance->getLogsRessource(4),
                         
        )
        );
    }
    
    self::DbQuery( "UPDATE player set activation_b = 1  WHERE player_id = {$this->player_id}" );
    self::DbQuery( "UPDATE player set activation_p = 1  WHERE player_id = {$this->player_id}" );
    self::DbQuery( "UPDATE player set activation_g = 1  WHERE player_id = {$this->player_id}" );
    self::DbQuery( "UPDATE player set activation_y = 1  WHERE player_id = {$this->player_id}" );

    undergrove::$instance->MajRessources();

    undergrove::$instance->GoalTrack();
    undergrove::$instance->CheckEnd();
    undergrove::$instance->addPendingFirst($this->player_id, "NormalTurn");
    //undergrove::$instance->addPending($this->player_id, "Confirm");
 
}

/////////////////////////////////////////////////////////////////////////////////  
//                 _   _            _       
//       /\       | | (_)          | |      
//      /  \   ___| |_ ___   ____ _| |_ ___ 
//     / /\ \ / __| __| \ \ / / _` | __/ _ \
//    / ____ \ (__| |_| |\ V / (_| | ||  __/
//   /_/    \_\___|\__|_| \_/ \__,_|\__\___|
//                                   
/////////////////////////////////////////////////////////////////////////////////

function argActivate($parg1, $parg2)
{
    $ret = array();
    $ret["selectable"] = array();
    $ret['buttons'] = array();
    $ret['title'] = clienttranslate('${actplayer} does the Activate action');
    $ret['titleyou'] = clienttranslate('${you} can activate a mushroom');

    $listechampi = undergrove::$instance->listechampi;
    
    $racines =  self::getObjectListFromDB( "SELECT location location FROM foret WHERE type = 'racine' AND player_id = {$this->player_id}", true );

    foreach ($racines as $racine)

    {
        $exploderacine = explode("_", $racine);
        $square = "square_".$exploderacine[1]."_".$exploderacine[2];

        $id = self::getUniqueValueFromDB("SELECT card_id id FROM champignon WHERE card_location = '{$square}'");
        $type = intval(self::getUniqueValueFromDB("SELECT card_type type FROM champignon WHERE card_id = {$id}"));

           
         
        if (($listechampi[$type]["coutab"] <= $this->player_activation_b) && ($listechampi[$type]["coutap"] <= $this->player_activation_p) && ($listechampi[$type]["coutag"] <= $this->player_activation_g) && ($listechampi[$type]["coutay"] <= $this->player_activation_y) && ($listechampi[$type]["coutc"] <= $this->player_carbone) && ($listechampi[$type]["coutn"] <= $this->player_azote) && ($listechampi[$type]["coutp"] <= $this->player_phosphore) && ($listechampi[$type]["coutk"] <= $this->player_potassium) && ($listechampi[$type]["coutlibre"] <= $this->player_azote+$this->player_phosphore+$this->player_potassium) && (($type <= 27) || ($type >= 33)))
            {
            $ret["selectable"][]="champi_".$id;
            }
    
   
    }
    
    $ret['buttons'][]="Cancel";
    return $ret;
}


function Activate($parg1, $parg2, $varg1, $varg2)
{

    $champi = explode("_",$varg1);
    $champiid = $champi[1];
    $champitype = self::getUniqueValueFromDB("SELECT card_type FROM champignon WHERE card_id={$champiid}");
    
    undergrove::$instance->addPendingTarget($this->player_id, "Champi".$champitype, "init");

}

/////////////////////////////////////////////////////////////////////////////////  
//             _                    _     
//       /\   | |                  | |    
//      /  \  | |__  ___  ___  _ __| |__  
//     / /\ \ | '_ \/ __|/ _ \| '__| '_ \ 
//    / ____ \| |_) \__ \ (_) | |  | |_) |
//   /_/    \_\_.__/|___/\___/|_|  |_.__/ 
//                              
/////////////////////////////////////////////////////////////////////////////////

function argAbsorb($parg1, $parg2)
{
    $ret = array();
    $ret["selectable"] = array();
    $ret['buttons'] = array();
    $ret['title'] = clienttranslate('${actplayer} does the Absorb action');
    $ret['titleyou'] = clienttranslate('${you} must choose an activation token');

    

        if($this->player_activation_b == 1)
        {
            $ret['buttons'][]="Activationb";
        }
        if($this->player_activation_p == 1)
        {
            $ret['buttons'][]="Activationp";
        }
        if($this->player_activation_g == 1)
        {
            $ret['buttons'][]="Activationg";
        }
        if($this->player_activation_y == 1)
        {
            $ret['buttons'][]="Activationy";
        }
        $ret['buttons'][]="Cancel";
    
    
   
    
        
    return $ret;
}


function Absorb($parg1, $parg2, $varg1, $varg2)
{
    
    undergrove::$instance->setGameStateValue('variable1', 0);
    undergrove::$instance->setGameStateValue('variable2', 0);
    


    if ($varg1 == "Activationb")
    {
        self::DbQuery( "UPDATE player set activation_b = 0  WHERE player_id = {$this->player_id}" );
    }

    if ($varg1 == "Activationp")
    {
        self::DbQuery( "UPDATE player set activation_p = 0  WHERE player_id = {$this->player_id}" );
    }

    if ($varg1 == "Activationg")
    {
        self::DbQuery( "UPDATE player set activation_g = 0  WHERE player_id = {$this->player_id}" );
    }

    if ($varg1 == "Activationy")
    {
        self::DbQuery( "UPDATE player set activation_y = 0  WHERE player_id = {$this->player_id}" );
    }

    undergrove::$instance->MajRessources();
    undergrove::$instance->addPending($this->player_id, "AbsorbStep2");
    

}

function argAbsorbStep2($parg1, $parg2)
{
    $ret = array();
    $ret["selectable"] = array();
    $ret['buttons'] = array();
    $ret['title'] = clienttranslate('${actplayer} does the Absorb action');
    $ret['titleyou'] = clienttranslate('${you} must choose a resource');

        if ($this->player_azote >= 1)
        {
            $ret['buttons'][]="N";
        }

        if ($this->player_phosphore >= 1)

        {
            $ret['buttons'][]="P";
        }

        if ($this->player_potassium >= 1)

        {
            $ret['buttons'][]="K";
        }
    
    
        
        $ret['buttons'][]="Undo";
    
    
   
    
        
    return $ret;
}

function AbsorbStep2($parg1, $parg2, $varg1, $varg2)
{
    if ($varg1 == "N")
    {
        self::DbQuery( "UPDATE player set azote = azote - 1  WHERE player_id = {$this->player_id}" );
    }

    if ($varg1 == "P")
    {
        self::DbQuery( "UPDATE player set phosphore = phosphore -1  WHERE player_id = {$this->player_id}" );
    }

    if ($varg1 == "K")
    {
        self::DbQuery( "UPDATE player set potassium = potassium -1  WHERE player_id = {$this->player_id}" );
    }

    
    undergrove::$instance->MajRessources();
    undergrove::$instance->addPending($this->player_id, "AbsorbStep3");
    

}


function argAbsorbStep3($parg1, $parg2)
{
    $ret = array();
    $ret["selectable"] = array();
    $ret['buttons'] = array();
    $ret['title'] = clienttranslate('${actplayer} does the Absorb action');
    $ret['titleyou'] = clienttranslate('${you} must choose the seedling that will absorb the carbon');

    $controle = self::getObjectListFromDB( "SELECT location location FROM foret WHERE type = 'semi' AND player_id = {$this->player_id} AND carbone < 4", true );
    
        
    foreach ($controle as $id)
    {
        $circle = explode ("_", $id);
        $ret["selectable"][]= "semi_".$circle[1]."_".$circle[2]."_".$this->player_id;
    }

    
        $ret['buttons'][]="Undo";
    
    
   
    
        
    return $ret;
}

function AbsorbStep3($parg1, $parg2, $varg1, $varg2)
{
    
 
    undergrove::$instance->addPending($this->player_id, "AbsorbStep4", $varg1);
    

}

function argAbsorbStep4($parg1, $parg2)
{
    $ret = array();
    $ret["selectable"] = array();
    $ret['buttons'] = array();
    $ret['title'] = clienttranslate('${actplayer} does the Absorb action');
    $ret['titleyou'] = clienttranslate('${you} must choose the mushroom to absorb');

        
    $controle = self::getObjectListFromDB( "SELECT card_id id FROM champignon WHERE card_location LIKE 'square%' AND carbone >= 1", true );    
    foreach ($controle as $id)
    {
        
        $ret["selectable"][]= "champi_".$id;
    }

    
        $ret['buttons'][]="Undo";
    
    
   
    
        
    return $ret;
}

function AbsorbStep4($parg1, $parg2, $varg1, $varg2)
{
    
    //parg1 = semi
    //varg1 = champi

    $explodesemi = explode("_", $parg1);
    $test = "\_".$explodesemi[1]."\_".$explodesemi[2];  // echapement des "_" pour qu'il ne les prennent pas pour des jokers
    $recupminisquare = self::getObjectListFromDB( "SELECT location location FROM foret WHERE type = 'racine' AND player_id = {$this->player_id} AND location LIKE '%$test'", true );
    $explodechampi = explode("_", $varg1);
    $recupsquare = self::getUniqueValueFromDB("SELECT card_location location FROM champignon WHERE card_id = {$explodechampi[1]}");
    $explodesquare = explode("_", $recupsquare);


    $absorb = 0;
    foreach ($recupminisquare as $id)
    {
        $explodeminisquare = explode("_", $id);
        if (($explodeminisquare[1]==$explodesquare[1]) && ($explodeminisquare[2]==$explodesquare[2]) && ($explodeminisquare[3]==$explodesemi[1]) && ($explodeminisquare[4]==$explodesemi[2]))
        {
            $circle = "circle_".$explodesemi[1]."_".$explodesemi[2];
            self::DbQuery( "UPDATE foret set carbone = carbone +1  WHERE location = '{$circle}'" );
            self::DbQuery( "UPDATE champignon set carbone = carbone -1  WHERE card_id = {$explodechampi[1]}" );

             /////movecarbone

            //$recupsquare = self::getUniqueValueFromDB("SELECT card_location location FROM champignon WHERE card_id = {$explodechampi[1]}");

            undergrove::$instance->notifyAllPlayers("movecarbone",clienttranslate( '${player_name} absorbs ${c}' ), array(
            
                'debut' =>  $recupsquare,
                'arrivee' => $circle,
                'player_name' => $this->player_name,
                'c' => undergrove::$instance->getLogsRessource(4),

                
                                    
            )
            );
        undergrove::$instance->MajRessources();

            

            $absorb = 1;
            //test transform arbre
            $testarbre = self::getUniqueValueFromDB("SELECT carbone carbone FROM foret WHERE location = '{$circle}'");
            if (($this->player_arbre >=1) && ($testarbre == 3))
            {
                self::DbQuery( "UPDATE player set arbre = arbre -1  WHERE player_id = {$this->player_id}" );
                self::DbQuery( "UPDATE foret set type = 'arbre' WHERE location = '{$circle}'" );
                
                undergrove::$instance->notifyAllPlayers("placearbre",clienttranslate( '${player_name} places a tree' ), array(
        
                    'cible' =>  $circle,
                    'color' =>  $this->player_color,
                    'player_name' => $this->player_name,                   
                )
                );
                undergrove::$instance->MajRessources();
            }


            $this->CarbonTrack();
            


        }

        
    }


    
    if ($absorb == 0)
    {
        $total=array();
        $xchampi = intval($explodesquare[1]);
        $ychampi = intval($explodesquare[2]);

        foreach ($recupminisquare as $id)
        {
            $explodeminisquare = explode("_", $id);
            $xracine = intval($explodeminisquare[1]);
            $yracine = intval($explodeminisquare[2]);
            

            $top=abs($xracine-$xchampi);
            $left=abs($yracine-$ychampi);

            $total[]=$top+$left;
                  
        }

        $plusPetiteValeur = min($total);
        undergrove::$instance->setGameStateValue('variable2', $plusPetiteValeur);
        $nbre=undergrove::$instance->getGameStateValue('variable1')+1;
        undergrove::$instance->setGameStateValue('variable1', $nbre);
        

        undergrove::$instance->addPending($this->player_id, "AbsorbStep5", $parg1, $varg1);
    }
    

}

function argAbsorbStep5($parg1, $parg2)
{
    $ret = array();
    $ret["selectable"] = array();
    $ret['buttons'] = array();
    $ret['title'] = clienttranslate('${actplayer} does the Absorb action');
    

    
    if(($this->player_azote + $this->player_phosphore + $this->player_potassium + $this->player_activation_b + $this->player_activation_p + $this->player_activation_g + $this->player_activation_y) >= (undergrove::$instance->getGameStateValue('variable2')-(undergrove::$instance->getGameStateValue('variable1')-1)))
    
    {
    $ret['titleyou'] = clienttranslate('${you} must spend a resource/activation for each move from the carbon to your root (#nb#/#nb2#)');
    
    $ret['nb'] = undergrove::$instance->getGameStateValue('variable1');
    $ret['nb2'] = undergrove::$instance->getGameStateValue('variable2');

        if ($this->player_azote >= 1)
        {
            $ret['buttons'][]="N";
        }

        if ($this->player_phosphore >= 1)

        {
            $ret['buttons'][]="P";
        }

        if ($this->player_potassium >= 1)

        {
            $ret['buttons'][]="K";
        }
        if($this->player_activation_b == 1)
        {
            $ret['buttons'][]="Activationb";
        }
        if($this->player_activation_p == 1)
        {
            $ret['buttons'][]="Activationp";
        }
        if($this->player_activation_g == 1)
        {
            $ret['buttons'][]="Activationg";
        }
        if($this->player_activation_y == 1)
        {
            $ret['buttons'][]="Activationy";
        }
    }
    
    else
    {
        $ret['titleyou'] = clienttranslate('${you} don\'t have enough resources to pay for carbon displacement');
    }
        $ret['buttons'][]="Undo";
    
    
   
    
        
    return $ret;
}

function AbsorbStep5($parg1, $parg2, $varg1, $varg2)
{
    
    if ($varg1 == "Activationb")
    {
        self::DbQuery( "UPDATE player set activation_b = 0  WHERE player_id = {$this->player_id}" );
    }

    if ($varg1 == "Activationp")
    {
        self::DbQuery( "UPDATE player set activation_p = 0  WHERE player_id = {$this->player_id}" );
    }

    if ($varg1 == "Activationg")
    {
        self::DbQuery( "UPDATE player set activation_g = 0  WHERE player_id = {$this->player_id}" );
    }

    if ($varg1 == "Activationy")
    {
        self::DbQuery( "UPDATE player set activation_y = 0  WHERE player_id = {$this->player_id}" );
    }

    if ($varg1 == "N")
    {
        self::DbQuery( "UPDATE player set azote = azote - 1  WHERE player_id = {$this->player_id}" );
    }

    if ($varg1 == "P")
    {
        self::DbQuery( "UPDATE player set phosphore = phosphore -1  WHERE player_id = {$this->player_id}" );
    }

    if ($varg1 == "K")
    {
        self::DbQuery( "UPDATE player set potassium = potassium -1  WHERE player_id = {$this->player_id}" );
    }
    
    undergrove::$instance->MajRessources();

    if (undergrove::$instance->getGameStateValue('variable1') == undergrove::$instance->getGameStateValue('variable2'))

    {
        $explodechampi = explode("_", $parg2);
        $explodesemi = explode("_", $parg1);
        $circle = "circle_".$explodesemi[1]."_".$explodesemi[2];
        self::DbQuery( "UPDATE foret set carbone = carbone +1  WHERE location = '{$circle}'" );
        self::DbQuery( "UPDATE champignon set carbone = carbone -1  WHERE card_id = {$explodechampi[1]}" );

        /////movecarbone

        $recupsquare = self::getUniqueValueFromDB("SELECT card_location location FROM champignon WHERE card_id = {$explodechampi[1]}");

        undergrove::$instance->notifyAllPlayers("movecarbone",clienttranslate( '${player_name} absorbs ${c}' ), array(
        
            'debut' =>  $recupsquare,
            'arrivee' => $circle,
            'player_name' => $this->player_name,
            'c' => undergrove::$instance->getLogsRessource(4),

            
                                 
        )
        );
        undergrove::$instance->MajRessources();


        //test transform arbre
        $testarbre = self::getUniqueValueFromDB("SELECT carbone carbone FROM foret WHERE location = '{$circle}'");
        if (($this->player_arbre >=1) && ($testarbre == 3))
        {
            self::DbQuery( "UPDATE player set arbre = arbre -1  WHERE player_id = {$this->player_id}" );
            self::DbQuery( "UPDATE foret set type = 'arbre' WHERE location = '{$circle}'" );
            
            undergrove::$instance->notifyAllPlayers("placearbre",clienttranslate( '${player_name} places a tree' ), array(
        
                'cible' =>  $circle,
                'color' =>  $this->player_color,
                'player_name' => $this->player_name,                   
            )
            );
            undergrove::$instance->MajRessources();
        }

        $this->CarbonTrack();
        
  
    }

    else
    {
        $nbre=undergrove::$instance->getGameStateValue('variable1')+1;
        undergrove::$instance->setGameStateValue('variable1', $nbre);
        undergrove::$instance->addPending($this->player_id, "AbsorbStep5", $parg1, $parg2);
    }
    
    }



/////////////////////////////////////////////////////////////////////////////////     
//    ______                _   _                 
//   |  ____|              | | (_)                
//   | |__ _   _ _ __   ___| |_ _  ___  _ __  ___ 
//   |  __| | | | '_ \ / __| __| |/ _ \| '_ \/ __|
//   | |  | |_| | | | | (__| |_| | (_) | | | \__ \
//   |_|   \__,_|_| |_|\___|\__|_|\___/|_| |_|___/
//                                              
/////////////////////////////////////////////////////////////////////////////////                                                
 

    function earthlover()   //après chaque absorb et juste avant le addpending NormalTurl peut etre a mettre plutot dans le carbontrack

    {
        $testchampicentral = count(self::getObjectListFromDB( "SELECT card_id id FROM champignon WHERE carbone != 0",true));
        
        if ($testchampicentral == 0)
        {
            
            self::DbQuery( "UPDATE champignon set carbone = carbone +1  WHERE card_location = 'square_1_0'" );
            self::DbQuery( "UPDATE champignon set carbone = carbone +1  WHERE card_location = 'square_-1_0'" );
            self::DbQuery( "UPDATE champignon set carbone = carbone +1  WHERE card_location = 'square_0_1'" );
            self::DbQuery( "UPDATE champignon set carbone = carbone +1  WHERE card_location = 'square_0_-1'" );
            undergrove::$instance->notifyAllPlayers('powerearthlover',clienttranslate( 'there are no more ${c} to absorb in the forest, Earthlover generates 4 ${c} around him' ), array(
                'c' => undergrove::$instance->getLogsRessource(4),
            )
            );
            undergrove::$instance->notifyAllPlayers( 'simplePause', '', [ 'time' => 800] ); 
            undergrove::$instance->MajRessources();
        }
    }

    function ScoreRacine ($locationracine)
    {
        $listechampi = undergrove::$instance->listechampi;
        $exploderacine = explode("_", $locationracine);
        $locationchampi = "square_".$exploderacine[1]."_".$exploderacine[2];
        $locationsemi = "circle_".$exploderacine[3]."_".$exploderacine[4];

        $type = self::getUniqueValueFromDB("SELECT card_type type FROM champignon WHERE card_location = '{$locationchampi}'");
        if (($type >= 41) && ($type <=48))
        {
            $vp = self::getUniqueValueFromDB("SELECT score score FROM champispecial WHERE type = {$type}");
        }
        else
        {
            $vp = $listechampi[$type]["vp"];
            
        }

        
            
        $sens = self::getUniqueValueFromDB("SELECT sens_racine FROM foret WHERE location = '{$locationracine}'");
        
        $colonne = "vp_racine".$sens;

        

        self::DbQuery( "UPDATE foret set {$colonne} = {$vp}  WHERE location = '{$locationsemi}'" );
        

    }





    function CarbonTrack()   //après chaque absorb 

    {
        $trackbefore = self::getUniqueValueFromDB("SELECT track track FROM player WHERE player_id = {$this->player_id}");
        if ($trackbefore < 8)
            {
                self::DbQuery( "UPDATE player set track = track +1  WHERE player_id = {$this->player_id}" );
                $trackafter = self::getUniqueValueFromDB("SELECT track track FROM player WHERE player_id = {$this->player_id}");
                undergrove::$instance->notifyAllPlayers('carbontrack','', array(
                    'track' =>  $trackafter,
                    )
                    );


                    if ($trackafter == 1)
                    {
                        self::DbQuery( "UPDATE player set azote = azote +1  WHERE player_id = {$this->player_id}" );
                        undergrove::$instance->MajRessources();
                        undergrove::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} gains ${n} (bonus carbon track)' ), array(
                            'player_name' => $this->player_name,
                            'n' => undergrove::$instance->getLogsRessource(1),
                            
                            )
                            );

                        $this->earthlover();  
                        undergrove::$instance->GoalTrack();
                        undergrove::$instance->CheckEnd();
                        undergrove::$instance->addPendingFirst($this->player_id, "NormalTurn");

                    }

                    if ($trackafter == 8)
                    {
                        self::DbQuery( "UPDATE player set carbone = carbone +1  WHERE player_id = {$this->player_id}" );
                        $compteurcarbone = undergrove::$instance->getGameStateValue('compteurcarbone') + 1;
                        undergrove::$instance->setGameStateValue('compteurcarbone', $compteurcarbone);
                        undergrove::$instance->MajRessources();
                        undergrove::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} gains ${c} (bonus carbon track)' ), array(
                            'player_name' => $this->player_name,
                            'c' => undergrove::$instance->getLogsRessource(4),
                            
                            )
                            );

                        $this->earthlover(); 
                        undergrove::$instance->GoalTrack(); 
                        undergrove::$instance->CheckEnd();
                        undergrove::$instance->addPendingFirst($this->player_id, "NormalTurn");

                    }

                    if ($trackafter == 5)
                    {
                        undergrove::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} gains a resource (bonus carbon track)' ), array(
                            'player_name' => $this->player_name,
                            )
                            );
                        undergrove::$instance->addPending($this->player_id, "BonusCarbonTrackRessource");

                    }

                    if (($trackafter == 3)||($trackafter == 7))
                    {

                        $nbracine = self::getUniqueValueFromDB("SELECT racine FROM player WHERE player_id={$this->player_id}");

                        $tableau["racinelibre"] = self::getObjectListFromDB( "SELECT location location FROM foret WHERE type = 'emplacement_racine'", true );
                        $emplacement = array();
                        foreach ($tableau["racinelibre"] as $racine)
                        {
                            
                            $explode = explode("_", $racine);
                            $controle = "circle_".$explode[3]."_".$explode[4];
                            $test = self::getUniqueValueFromDB("SELECT player_id FROM foret WHERE location='{$controle}' AND (type='semi' OR type='arbre')");
                            if ($test == $this->player_id)
                            {
                                $emplacement[]=$racine;
                            }

                        }

                        $emplacementracine = count ($emplacement); 
                        
                        
                        
                        if (($nbracine == 0) || ($emplacementracine == 0))
                        {      
                            $this->earthlover(); 
                            undergrove::$instance->GoalTrack(); 
                            undergrove::$instance->CheckEnd();
                            undergrove::$instance->addPendingFirst($this->player_id, "NormalTurn");
                        }

                        if (($nbracine >= 1) && ($emplacementracine > 0))
                        {       
                            undergrove::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} gains a root (bonus carbon track)' ), array(
                                'player_name' => $this->player_name,
                                )
                                );
                            $this->earthlover(); 
                            undergrove::$instance->addPending($this->player_id, "BonusCarbonTrackRacine");
                        }



                        

                    }

                    if (($trackafter == 2)||($trackafter == 4)||($trackafter == 6))
                    {
                        undergrove::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} gains a bonus tile (bonus carbon track)' ), array(
                            'player_name' => $this->player_name,
                            )
                            );
                        undergrove::$instance->addPending($this->player_id, "BonusCarbonTrackTiles", $trackafter);

                    }
        
            }

        if ($trackbefore == 8)
        {
            undergrove::$instance->addPending($this->player_id, "BonusCarbonTrackEnd");

        }

        if ($trackbefore > 8)
        {
            $this->earthlover();  
            undergrove::$instance->GoalTrack();
            undergrove::$instance->CheckEnd();
            undergrove::$instance->addPendingFirst($this->player_id, "NormalTurn");

        }

    }

    function TestBonusPermanent($racine)
    {
        $exploderacine = explode("_", $racine);
        $square = "square_".$exploderacine[1]."_".$exploderacine[2];
        $type = self::getUniqueValueFromDB("SELECT card_type type FROM champignon WHERE card_location = '{$square}'");

        if ($type == 28)
        {
            undergrove::$instance->setGameStateValue('idcopieur', 0);     //pour que le bonus soit pris dans le meme tour ou pas
            $test = self::getUniqueValueFromDB("SELECT bonus_racine_reproduce FROM player WHERE player_id = {$this->player_id}");
            if ($test != 1)
            {
            self::DbQuery( "UPDATE player set bonus_racine_reproduce = 1 WHERE player_id = {$this->player_id}" );
            undergrove::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} gains the root bonus for the Reproduce action' ), array(
                'player_name' => $this->player_name,
                )
                );

                undergrove::$instance->setGameStateValue('idcopieur', 1);     //pour que le bonus soit pris dans le meme tour ou pas
            }
            
        }

        if ($type == 29)
        {
            undergrove::$instance->setGameStateValue('idcopieur', 0);     //pour que le bonus soit pris dans le meme tour ou pas
            $test = self::getUniqueValueFromDB("SELECT bonus_racine_partner FROM player WHERE player_id = {$this->player_id}");
            if ($test != 1)
            {
            self::DbQuery( "UPDATE player set bonus_racine_partner = 1 WHERE player_id = {$this->player_id}" );
            undergrove::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} gains the root bonus for the Partner action' ), array(
                'player_name' => $this->player_name,
                )
                );

                undergrove::$instance->setGameStateValue('idcopieur', 2);     //pour que le bonus soit pris dans le meme tour ou pas
            }
            
        }

        if ($type == 30)
        {
            $test = self::getUniqueValueFromDB("SELECT bonus_champi_reproduce FROM player WHERE player_id = {$this->player_id}");
            if ($test != 1)
            {
            self::DbQuery( "UPDATE player set bonus_champi_reproduce = 1 WHERE player_id = {$this->player_id}" );
            undergrove::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} gains the mushroom bonus for the Reproduce action' ), array(
                'player_name' => $this->player_name,
                )
                );

            }
        }

        if ($type == 31)
        {
            $test = self::getUniqueValueFromDB("SELECT bonus_champi_partner FROM player WHERE player_id = {$this->player_id}");
            if ($test != 1)
            {
            self::DbQuery( "UPDATE player set bonus_champi_partner = 1 WHERE player_id = {$this->player_id}" );
            undergrove::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} gains the mushroom bonus for the Partner action' ), array(
                'player_name' => $this->player_name,
                )
                );

            }
        }

        if ($type == 32)
        {
            $test = self::getUniqueValueFromDB("SELECT bonus_carbone FROM player WHERE player_id = {$this->player_id}");
            if ($test != 1)
            {
            self::DbQuery( "UPDATE player set bonus_carbone = 1 WHERE player_id = {$this->player_id}" );
            undergrove::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} gains the carbon bonus for the Photosynthesize action' ), array(
                'player_name' => $this->player_name,
                )
                );

            }
        }

    }

    
    function ChampiSpecial($type, $location)
    {
        $listechampi = undergrove::$instance->listechampi;
        
        $explodeposition = explode("_", $location);
        $a = intval($explodeposition[1]);
        $b = intval($explodeposition[2]);

        $square1 = "square_".($a)."_".($b-1);
        $square2 = "square_".($a)."_".($b+1);
        $square3 = "square_".($a-1)."_".($b);
        $square4 = "square_".($a+1)."_".($b);

        $typechampiadjacent = self::getObjectListFromDB( "SELECT card_type type FROM champignon WHERE card_location = '{$square1}' OR card_location = '{$square2}' OR card_location = '{$square3}' OR card_location = '{$square4}'", true );

        foreach ($typechampiadjacent as $test)
        {
            if ((($type == 45) && ($listechampi[$test]["coutap"] == 1)) || (($test == 45) && ($listechampi[$type]["coutap"] == 1)))
            {
                self::DbQuery( "UPDATE champispecial set score = score + 1  WHERE type = 45" );

                $square = self::getUniqueValueFromDB("SELECT card_location FROM champignon WHERE card_type = 45");
                $explode = explode("_", $square);
                $test = "minisquare_".$explode[1]."_".$explode[2];  

                $racine = self::getObjectListFromDB( "SELECT location location, sens_racine sens FROM foret WHERE location LIKE '$test%' AND type ='racine'" );
                
                foreach ($racine as $controle)
                {
                    $exploderacine = explode("_", $controle['location']);
                    $semi = "circle_".$exploderacine[3]."_".$exploderacine[4];
                    $colonne = "vp_racine".$controle['sens'];
                    $newscore = self::getUniqueValueFromDB("SELECT score FROM champispecial WHERE type = 45");

                    self::DbQuery( "UPDATE foret set {$colonne} = {$newscore} WHERE location = '{$semi}'" );
                }

            }

            if ((($type == 46) && ($listechampi[$test]["coutay"] == 1)) || (($test == 46) && ($listechampi[$type]["coutay"] == 1)))
            {
                self::DbQuery( "UPDATE champispecial set score = score + 1  WHERE type = 46" );

                $square = self::getUniqueValueFromDB("SELECT card_location FROM champignon WHERE card_type = 46");
                $explode = explode("_", $square);
                $test = "minisquare_".$explode[1]."_".$explode[2];  

                $racine = self::getObjectListFromDB( "SELECT location location, sens_racine sens FROM foret WHERE location LIKE '$test%' AND type ='racine'" );
                
                foreach ($racine as $controle)
                {
                    $exploderacine = explode("_", $controle['location']);
                    $semi = "circle_".$exploderacine[3]."_".$exploderacine[4];
                    $colonne = "vp_racine".$controle['sens'];
                    $newscore = self::getUniqueValueFromDB("SELECT score FROM champispecial WHERE type = 46");

                    self::DbQuery( "UPDATE foret set {$colonne} = {$newscore} WHERE location = '{$semi}'" );
                }

            }

            if ((($type == 47) && ($listechampi[$test]["coutab"] == 1)) || (($test == 47) && ($listechampi[$type]["coutab"] == 1)))
            {
                self::DbQuery( "UPDATE champispecial set score = score + 1  WHERE type = 47" );

                $square = self::getUniqueValueFromDB("SELECT card_location FROM champignon WHERE card_type = 47");
                $explode = explode("_", $square);
                $test = "minisquare_".$explode[1]."_".$explode[2];  

                $racine = self::getObjectListFromDB( "SELECT location location, sens_racine sens FROM foret WHERE location LIKE '$test%' AND type ='racine'" );
                
                foreach ($racine as $controle)
                {
                    $exploderacine = explode("_", $controle['location']);
                    $semi = "circle_".$exploderacine[3]."_".$exploderacine[4];
                    $colonne = "vp_racine".$controle['sens'];
                    $newscore = self::getUniqueValueFromDB("SELECT score FROM champispecial WHERE type = 47");

                    self::DbQuery( "UPDATE foret set {$colonne} = {$newscore} WHERE location = '{$semi}'" );
                }

            }

            if ((($type == 48) && ($listechampi[$test]["coutag"] == 1)) || (($test == 48) && ($listechampi[$type]["coutag"] == 1)))
            {
                self::DbQuery( "UPDATE champispecial set score = score + 1  WHERE type = 48" );

                $square = self::getUniqueValueFromDB("SELECT card_location FROM champignon WHERE card_type = 48");
                $explode = explode("_", $square);
                $test = "minisquare_".$explode[1]."_".$explode[2];  

                $racine = self::getObjectListFromDB( "SELECT location location, sens_racine sens FROM foret WHERE location LIKE '$test%' AND type ='racine'" );
                
                foreach ($racine as $controle)
                {
                    $exploderacine = explode("_", $controle['location']);
                    $semi = "circle_".$exploderacine[3]."_".$exploderacine[4];
                    $colonne = "vp_racine".$controle['sens'];
                    $newscore = self::getUniqueValueFromDB("SELECT score FROM champispecial WHERE type = 48");

                    self::DbQuery( "UPDATE foret set {$colonne} = {$newscore} WHERE location = '{$semi}'" );
                }

            }

            
        }
        
        
    }


///////////////////////////////////////////////////////////////////////////////// 
//     ____                            _____           _                  _______             _    
//    |  _ \                          / ____|         | |                |__   __|           | |   
//    | |_) | ___  _ __  _   _ ___   | |     __ _ _ __| |__   ___  _ __     | |_ __ __ _  ___| | __
//    |  _ < / _ \| '_ \| | | / __|  | |    / _` | '__| '_ \ / _ \| '_ \    | | '__/ _` |/ __| |/ /
//    | |_) | (_) | | | | |_| \__ \  | |___| (_| | |  | |_) | (_) | | | |   | | | | (_| | (__|   < 
//    |____/ \___/|_| |_|\__,_|___/   \_____\__,_|_|  |_.__/ \___/|_| |_|   |_|_|  \__,_|\___|_|\_\
//                                                                                                 
/////////////////////////////////////////////////////////////////////////////////                                                                                                 
   
function argBonusCarbonTrackRessource($parg1, $parg2)
{
    $ret = array();
    $ret["selectable"] = array();
    $ret['buttons'] = array();
    $ret['title'] = clienttranslate('${actplayer} triggers a bonus from the carbon track');
    $ret['titleyou'] = clienttranslate('${you} must choose a resource to win (Bonus Carbon Track)');

    $ret['buttons'][]="N";
    $ret['buttons'][]="P";
    $ret['buttons'][]="K"; 
       
    
        
    return $ret;
}


function BonusCarbonTrackRessource($parg1, $parg2, $varg1, $varg2)
{

    if ($varg1 == "N")
    {
        self::DbQuery( "UPDATE player set azote = azote + 1  WHERE player_id = {$this->player_id}" );
        undergrove::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} gains ${n} (bonus carbon track)' ), array(
            'player_name' => $this->player_name,
            'n' => undergrove::$instance->getLogsRessource(1),
            )
            );
    }

    if ($varg1 == "P")
    {
        self::DbQuery( "UPDATE player set phosphore = phosphore +1  WHERE player_id = {$this->player_id}" );
        undergrove::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} gains ${p} (bonus carbon track)' ), array(
            'player_name' => $this->player_name,
            'p' => undergrove::$instance->getLogsRessource(2),
            )
            );
    }

    if ($varg1 == "K")
    {
        self::DbQuery( "UPDATE player set potassium = potassium +1  WHERE player_id = {$this->player_id}" );
        undergrove::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} gains ${k} (bonus carbon track)' ), array(
            'player_name' => $this->player_name,
            'k' => undergrove::$instance->getLogsRessource(3),
            )
            );
    }
    
    undergrove::$instance->MajRessources();

    
    $this->earthlover(); 
    undergrove::$instance->GoalTrack();
    undergrove::$instance->CheckEnd();
    undergrove::$instance->addPendingFirst($this->player_id, "NormalTurn");
} 



function argBonusCarbonTrackRacine($parg1, $parg2)
{
    $ret = array();
    $ret["selectable"] = array();
    $ret['buttons'] = array();
    $ret['title'] = clienttranslate('${actplayer} triggers a bonus from the carbon track');
    $ret['titleyou'] = clienttranslate('${you} can place a root (Bonus Carbon Track)');

        $ret["racinelibre"] = self::getObjectListFromDB( "SELECT location location FROM foret WHERE type = 'emplacement_racine'", true );

        foreach ($ret["racinelibre"] as $racine)
        {
            
            $explode = explode("_", $racine);
            $controle = "circle_".$explode[3]."_".$explode[4];
            $test = self::getUniqueValueFromDB("SELECT player_id FROM foret WHERE location='{$controle}' AND (type='semi' OR type='arbre')");
            if ($test == $this->player_id)
            {
                $ret["selectable"][]=$racine;
            }

        }
                
        

        $ret['buttons'][]="Findetour";

        
    return $ret;
}

function BonusCarbonTrackRacine($parg1, $parg2, $varg1, $varg2)
{
    $sens = self::getUniqueValueFromDB("SELECT sens_racine FROM foret WHERE location='{$varg1}'");

    undergrove::$instance->notifyAllPlayers("placeracine",clienttranslate( '${player_name} places a root (bonus carbon track)' ), array(
            
        'cible' =>  $varg1,
        'color' =>  $this->player_color,
        'sens' => $sens,
        'player_name' => $this->player_name,
         
    )
    );

    $this->TestBonusPermanent($varg1);
    undergrove::$instance->setGameStateValue('idcopieur', 0);     //pour que le bonus soit pris dans le meme tour ou pas
    $this->ScoreRacine ($varg1);

    self::DbQuery( "UPDATE player set racine = racine -1  WHERE player_id = {$this->player_id}" );
    self::DbQuery( "UPDATE foret set type = 'racine' WHERE location = '{$varg1}'" );
    self::DbQuery( "UPDATE foret set player_id = {$this->player_id} WHERE location = '{$varg1}'" );

    undergrove::$instance->MajRessources();

    $this->earthlover(); 
    undergrove::$instance->GoalTrack();
    undergrove::$instance->CheckEnd();
    undergrove::$instance->addPendingFirst($this->player_id, "NormalTurn");

}  



function argBonusCarbonTrackEnd($parg1, $parg2)
{
    $ret = array();
    $ret["selectable"] = array();
    $ret['buttons'] = array();
    $ret['title'] = clienttranslate('${actplayer} triggers a bonus from the carbon track');
    $ret['titleyou'] = clienttranslate('${you} must choose your carbon track bonus');
    
    $test = self::getObjectListFromDB( "SELECT track FROM player", true );
    if (!in_array(9, $test))
    {
    $ret["selectable"][]= "selecttrack9";
    }
    if (!in_array(10, $test))
    {
    $ret["selectable"][]= "selecttrack10";
    }
    if (!in_array(11, $test))
    {
    $ret["selectable"][]= "selecttrack11";
    }
    if (!in_array(12, $test))
    {
    $ret["selectable"][]= "selecttrack12";
    }
    if (!in_array(13, $test))
    {
    $ret["selectable"][]= "selecttrack13";
    }

    $ret["buttons"][]= "Undo";

    return $ret;
}

function BonusCarbonTrackEnd($parg1, $parg2, $varg1, $varg2)
{
    


    if ($varg1 == "selecttrack9")
    {
        self::DbQuery( "UPDATE player set track = 9  WHERE player_id = {$this->player_id}" );
        

        undergrove::$instance->notifyAllPlayers('carbontrack','', array(
            'track' =>  9,
            'player_name' => $this->player_name,
            )
            );


        
        $nbracine = self::getUniqueValueFromDB("SELECT racine FROM player WHERE player_id={$this->player_id}");

        $tableau["racinelibre"] = self::getObjectListFromDB( "SELECT location location FROM foret WHERE type = 'emplacement_racine'", true );
        $emplacement = array();
        foreach ($tableau["racinelibre"] as $racine)
        {
            
            $explode = explode("_", $racine);
            $controle = "circle_".$explode[3]."_".$explode[4];
            $test = self::getUniqueValueFromDB("SELECT player_id FROM foret WHERE location='{$controle}' AND (type='semi' OR type='arbre')");
            if ($test == $this->player_id)
            {
                $emplacement[]=$racine;
            }

        }

        $emplacementracine = count ($emplacement); 
        
        
        
        if (($nbracine == 0) || ($emplacementracine == 0))
        {    
            $this->earthlover();   
            undergrove::$instance->GoalTrack(); 
               //declenchement fin de partie
                $securite = 0;
                if (undergrove::$instance->getGameStateValue('final') == 0)
                {
                undergrove::$instance->setGameStateValue('final', 1);
                undergrove::$instance->notifyAllPlayers('messagealerte',clienttranslate( '${player_name} has just triggered the end of the game. This will end at the end of the next round.' ), array(
                    'player_name' => $this->player_name,
                    )
                    );
                
                $securite = 1;
                for ($i = 1; $i <= $this->player_no; $i++)
                {
                    self::DbQuery( "UPDATE player set final = 1 WHERE player_no={$i}" );
                }
                }

                if ((undergrove::$instance->getGameStateValue('final') == 1) && ($securite == 0))
                {
                undergrove::$instance->CheckEnd();
                }

                undergrove::$instance->addPendingFirst($this->player_id, "NormalTurn");

        }

        if (($nbracine >= 1) && ($emplacementracine > 0))
        {       
            undergrove::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} gains a root (bonus carbon track)' ), array(
                'player_name' => $this->player_name,
                )
                );
                $this->earthlover(); 
                undergrove::$instance->addPending($this->player_id, "BonusCarbonTrackEndRacine");
        }

        
        
    }

    if ($varg1 == "selecttrack10")
    {
        self::DbQuery( "UPDATE player set track = 10  WHERE player_id = {$this->player_id}" );
                
        undergrove::$instance->notifyAllPlayers('carbontrack',clienttranslate( '${player_name} gains ${c} and 2 resources (bonus carbon track)' ), array(
            'track' =>  10,
            'player_name' => $this->player_name,
            'c' => undergrove::$instance->getLogsRessource(4),
            )
            );
        
        self::DbQuery( "UPDATE player set carbone = carbone +1  WHERE player_id = {$this->player_id}" );
        $compteurcarbone = undergrove::$instance->getGameStateValue('compteurcarbone') + 1;
        undergrove::$instance->setGameStateValue('compteurcarbone', $compteurcarbone);
        undergrove::$instance->MajRessources();
        undergrove::$instance->addPending($this->player_id, "BonusCarbonTrackEndTrack10");
    }

    if ($varg1 == "selecttrack11")
    {
        self::DbQuery( "UPDATE player set carbone = carbone +3  WHERE player_id = {$this->player_id}" );
        $compteurcarbone = undergrove::$instance->getGameStateValue('compteurcarbone') + 3;
        undergrove::$instance->setGameStateValue('compteurcarbone', $compteurcarbone);
        undergrove::$instance->MajRessources();

        self::DbQuery( "UPDATE player set track = 11  WHERE player_id = {$this->player_id}" );
                
        undergrove::$instance->notifyAllPlayers('carbontrack',clienttranslate( '${player_name} gains ${c} ${c} ${c} (bonus carbon track)' ), array(
            'track' =>  11,
            'player_name' => $this->player_name,
            'c' => undergrove::$instance->getLogsRessource(4),
            )
            );

        $this->earthlover(); 
        undergrove::$instance->GoalTrack();

        //declenchement fin de partie
    $securite = 0;
    if (undergrove::$instance->getGameStateValue('final') == 0)
    {
    undergrove::$instance->setGameStateValue('final', 1);
    undergrove::$instance->notifyAllPlayers('messagealerte',clienttranslate( '${player_name} has just triggered the end of the game. This will end at the end of the next round.' ), array(
        'player_name' => $this->player_name,
        )
        );

    $securite = 1;
    for ($i = 1; $i <= $this->player_no; $i++)
    {
        self::DbQuery( "UPDATE player set final = 1 WHERE player_no={$i}" );
    }
    }

    if ((undergrove::$instance->getGameStateValue('final') == 1) && ($securite == 0))
    {
    undergrove::$instance->CheckEnd();
    }

        undergrove::$instance->addPendingFirst($this->player_id, "NormalTurn");
    }

    if ($varg1 == "selecttrack12")
    {
        self::DbQuery( "UPDATE player set track = 12  WHERE player_id = {$this->player_id}" );
                
        undergrove::$instance->notifyAllPlayers('carbontrack',clienttranslate('${player_name} gains a reactivation of a token and a resource (bonus carbon track)' ), array(
            'track' =>  12,
            'player_name' => $this->player_name,
            )
            );
        undergrove::$instance->addPending($this->player_id, "BonusCarbonTrackEndTrack12");
    }

    if ($varg1 == "selecttrack13")
    {
        self::DbQuery( "UPDATE player set bonus_score = bonus_score +2  WHERE player_id = {$this->player_id}" );
        undergrove::$instance->MajRessources();

        self::DbQuery( "UPDATE player set track = 13  WHERE player_id = {$this->player_id}" );
                
        undergrove::$instance->notifyAllPlayers('carbontrack',clienttranslate( '${player_name} gains 2 victory points (bonus carbon track)' ), array(
            'track' =>  13,
            'player_name' => $this->player_name,
            )
            );

        $this->earthlover(); 
        undergrove::$instance->GoalTrack();

        //declenchement fin de partie
    $securite = 0;
    if (undergrove::$instance->getGameStateValue('final') == 0)
    {
    undergrove::$instance->setGameStateValue('final', 1);
    undergrove::$instance->notifyAllPlayers('messagealerte',clienttranslate( '${player_name} has just triggered the end of the game. This will end at the end of the next round.' ), array(
        'player_name' => $this->player_name,
        )
        );
    $securite = 1;
    for ($i = 1; $i <= $this->player_no; $i++)
    {
        self::DbQuery( "UPDATE player set final = 1 WHERE player_no={$i}" );
    }
    }

    if ((undergrove::$instance->getGameStateValue('final') == 1) && ($securite == 0))
    {
    undergrove::$instance->CheckEnd();
    }

        undergrove::$instance->addPendingFirst($this->player_id, "NormalTurn");
    }
    

}  



function argBonusCarbonTrackEndRacine($parg1, $parg2)
{
    $ret = array();
    $ret["selectable"] = array();
    $ret['buttons'] = array();
    $ret['title'] = clienttranslate('${actplayer} triggers a bonus from the carbon track');
    $ret['titleyou'] = clienttranslate('${you} can place a root');

        $ret["racinelibre"] = self::getObjectListFromDB( "SELECT location location FROM foret WHERE type = 'emplacement_racine'", true );

        foreach ($ret["racinelibre"] as $racine)
        {
            
            $explode = explode("_", $racine);
            $controle = "circle_".$explode[3]."_".$explode[4];
            $test = self::getUniqueValueFromDB("SELECT player_id FROM foret WHERE location='{$controle}' AND (type='semi' OR type='arbre')");
            if ($test == $this->player_id)
            {
                $ret["selectable"][]=$racine;
            }

        }
                
        

        $ret['buttons'][]="Findetour2";

        return $ret;

}

function BonusCarbonTrackEndRacine($parg1, $parg2, $varg1, $varg2)
{

    $sens = self::getUniqueValueFromDB("SELECT sens_racine FROM foret WHERE location='{$varg1}'");

    undergrove::$instance->notifyAllPlayers("placeracine",clienttranslate( '${player_name} places a root (bonus carbon track)' ), array(
            
        'cible' =>  $varg1,
        'color' =>  $this->player_color,
        'sens' => $sens,
        'player_name' => $this->player_name,
         
    )
    );

    $this->TestBonusPermanent($varg1);
    undergrove::$instance->setGameStateValue('idcopieur', 0);     //pour que le bonus soit pris dans le meme tour ou pas
    $this->ScoreRacine ($varg1);

    self::DbQuery( "UPDATE player set racine = racine -1  WHERE player_id = {$this->player_id}" );
    self::DbQuery( "UPDATE foret set type = 'racine' WHERE location = '{$varg1}'" );
    self::DbQuery( "UPDATE foret set player_id = {$this->player_id} WHERE location = '{$varg1}'" );

    undergrove::$instance->MajRessources();

    $this->earthlover(); 
    undergrove::$instance->GoalTrack();
    //declenchement fin de partie
    $securite = 0;
    if (undergrove::$instance->getGameStateValue('final') == 0)
    {
    undergrove::$instance->setGameStateValue('final', 1);
    undergrove::$instance->notifyAllPlayers('messagealerte',clienttranslate( '${player_name} has just triggered the end of the game. This will end at the end of the next round.' ), array(
        'player_name' => $this->player_name,
        )
        );
    $securite = 1;
    for ($i = 1; $i <= $this->player_no; $i++)
    {
        self::DbQuery( "UPDATE player set final = 1 WHERE player_no={$i}" );
    }
    }

    if ((undergrove::$instance->getGameStateValue('final') == 1) && ($securite == 0))
    {
    undergrove::$instance->CheckEnd();
    }
    undergrove::$instance->addPendingFirst($this->player_id, "NormalTurn");
    
} 




function argBonusCarbonTrackEndTrack10($parg1, $parg2)
{
    $ret = array();
    $ret["selectable"] = array();
    $ret['buttons'] = array();
    $ret['title'] = clienttranslate('${actplayer} triggers a bonus from the carbon track');
    $ret['titleyou'] = clienttranslate('${you} must choose a first resource to win (Bonus Carbon Track)');

    $ret['buttons'][]="N";
    $ret['buttons'][]="P";
    $ret['buttons'][]="K"; 
       
    
        
    return $ret;
}

function BonusCarbonTrackEndTrack10($parg1, $parg2, $varg1, $varg2)
{

    if ($varg1 == "N")
    {
        self::DbQuery( "UPDATE player set azote = azote + 1  WHERE player_id = {$this->player_id}" );
        undergrove::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} gains ${n} (bonus carbon track)' ), array(
            'player_name' => $this->player_name,
            'n' => undergrove::$instance->getLogsRessource(1),
            )
            );
    }

    if ($varg1 == "P")
    {
        self::DbQuery( "UPDATE player set phosphore = phosphore +1  WHERE player_id = {$this->player_id}" );
        undergrove::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} gains ${p} (bonus carbon track)' ), array(
            'player_name' => $this->player_name,
            'p' => undergrove::$instance->getLogsRessource(2),
            )
            );
    }

    if ($varg1 == "K")
    {
        self::DbQuery( "UPDATE player set potassium = potassium +1  WHERE player_id = {$this->player_id}" );
        undergrove::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} gains ${k} (bonus carbon track)' ), array(
            'player_name' => $this->player_name,
            'k' => undergrove::$instance->getLogsRessource(3),
            )
            );
    }
    
    undergrove::$instance->MajRessources();

    
    undergrove::$instance->addPending($this->player_id, "BonusCarbonTrackEndTrack102");


} 

function argBonusCarbonTrackEndTrack102($parg1, $parg2)
{
    $ret = array();
    $ret["selectable"] = array();
    $ret['buttons'] = array();
    $ret['title'] = clienttranslate('${actplayer} triggers a bonus from the carbon track');
    $ret['titleyou'] = clienttranslate('${you} must choose a second resource to win (Bonus Carbon Track)');

    $ret['buttons'][]="N";
    $ret['buttons'][]="P";
    $ret['buttons'][]="K"; 
    
        
    return $ret;
}

function BonusCarbonTrackEndTrack102($parg1, $parg2, $varg1, $varg2)
{

    if ($varg1 == "N")
    {
        self::DbQuery( "UPDATE player set azote = azote + 1  WHERE player_id = {$this->player_id}" );
        undergrove::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} gains ${n} (bonus carbon track)' ), array(
            'player_name' => $this->player_name,
            'n' => undergrove::$instance->getLogsRessource(1),
            )
            );
    }

    if ($varg1 == "P")
    {
        self::DbQuery( "UPDATE player set phosphore = phosphore +1  WHERE player_id = {$this->player_id}" );
        undergrove::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} gains ${p} (bonus carbon track)' ), array(
            'player_name' => $this->player_name,
            'p' => undergrove::$instance->getLogsRessource(2),
            )
            );
    }

    if ($varg1 == "K")
    {
        self::DbQuery( "UPDATE player set potassium = potassium +1  WHERE player_id = {$this->player_id}" );
        undergrove::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} gains ${k} (bonus carbon track)' ), array(
            'player_name' => $this->player_name,
            'k' => undergrove::$instance->getLogsRessource(3),
            )
            );
    }
    
    undergrove::$instance->MajRessources();

    $this->earthlover(); 
    undergrove::$instance->GoalTrack();
    //declenchement fin de partie
    $securite = 0;
    if (undergrove::$instance->getGameStateValue('final') == 0)
    {
    undergrove::$instance->setGameStateValue('final', 1);
    undergrove::$instance->notifyAllPlayers('messagealerte',clienttranslate( '${player_name} has just triggered the end of the game. This will end at the end of the next round.' ), array(
        'player_name' => $this->player_name,
        )
        );
    $securite = 1;
    for ($i = 1; $i <= $this->player_no; $i++)
    {
        self::DbQuery( "UPDATE player set final = 1 WHERE player_no={$i}" );
    }
    }

    if ((undergrove::$instance->getGameStateValue('final') == 1) && ($securite == 0))
    {
    undergrove::$instance->CheckEnd();
    }
    undergrove::$instance->addPendingFirst($this->player_id, "NormalTurn");

} 



function argBonusCarbonTrackEndTrack12($parg1, $parg2)
{
    $ret = array();
    $ret["selectable"] = array();
    $ret['buttons'] = array();
    $ret['title'] = clienttranslate('${actplayer} triggers a bonus from the carbon track');
    $ret['titleyou'] = clienttranslate('${you} must choose a token to reactivate');

        if($this->player_activation_b == 0)
        {
            $ret['buttons'][]="Activationb";
        }
        if($this->player_activation_p == 0)
        {
            $ret['buttons'][]="Activationp";
        }
        if($this->player_activation_g == 0)
        {
            $ret['buttons'][]="Activationg";
        }
        if($this->player_activation_y == 0)
        {
            $ret['buttons'][]="Activationy";
        }

        
    return $ret;
}

function BonusCarbonTrackEndTrack12($parg1, $parg2, $varg1, $varg2)
{

    if ($varg1 == "Activationb")
    {
        self::DbQuery( "UPDATE player set activation_b = 1  WHERE player_id = {$this->player_id}" );
        undergrove::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} reactivates ${b}' ), array(
            'player_name' => $this->player_name,
            'b' => undergrove::$instance->getLogsActivation(1),
            )
            );
    }

    if ($varg1 == "Activationp")
    {
        self::DbQuery( "UPDATE player set activation_p = 1  WHERE player_id = {$this->player_id}" );
        undergrove::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} reactivates ${p}' ), array(
            'player_name' => $this->player_name,
            'p' => undergrove::$instance->getLogsActivation(2),
            )
            );
    }

    if ($varg1 == "Activationg")
    {
        self::DbQuery( "UPDATE player set activation_g = 1  WHERE player_id = {$this->player_id}" );
        undergrove::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} reactivates ${g}' ), array(
            'player_name' => $this->player_name,
            'g' => undergrove::$instance->getLogsActivation(3),
            )
            );
    }

    if ($varg1 == "Activationy")
    {
        self::DbQuery( "UPDATE player set activation_y = 1  WHERE player_id = {$this->player_id}" );
        undergrove::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} reactivates ${y}' ), array(
            'player_name' => $this->player_name,
            'y' => undergrove::$instance->getLogsActivation(4),
            )
            );
    }

    undergrove::$instance->MajRessources();
    undergrove::$instance->addPending($this->player_id, "BonusCarbonTrackEndTrack122");

} 

function argBonusCarbonTrackEndTrack122($parg1, $parg2)
{
    $ret = array();
    $ret["selectable"] = array();
    $ret['buttons'] = array();
    $ret['title'] = clienttranslate('${actplayer} triggers a bonus from the carbon track');
    $ret['titleyou'] = clienttranslate('${you} must choose a resource to win (Bonus Carbon Track)');

    $ret['buttons'][]="N";
    $ret['buttons'][]="P";
    $ret['buttons'][]="K"; 
       
    
        
    return $ret;
}


function BonusCarbonTrackEndTrack122($parg1, $parg2, $varg1, $varg2)
{

    if ($varg1 == "N")
    {
        self::DbQuery( "UPDATE player set azote = azote + 1  WHERE player_id = {$this->player_id}" );
        undergrove::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} gains ${n} (bonus carbon track)' ), array(
            'player_name' => $this->player_name,
            'n' => undergrove::$instance->getLogsRessource(1),
            )
            );
    }

    if ($varg1 == "P")
    {
        self::DbQuery( "UPDATE player set phosphore = phosphore +1  WHERE player_id = {$this->player_id}" );
        undergrove::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} gains ${p} (bonus carbon track)' ), array(
            'player_name' => $this->player_name,
            'p' => undergrove::$instance->getLogsRessource(2),
            )
            );
    }

    if ($varg1 == "K")
    {
        self::DbQuery( "UPDATE player set potassium = potassium +1  WHERE player_id = {$this->player_id}" );
        undergrove::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} gains ${k} (bonus carbon track)' ), array(
            'player_name' => $this->player_name,
            'k' => undergrove::$instance->getLogsRessource(3),
            )
            );
    }
    
    undergrove::$instance->MajRessources();

    
    $this->earthlover(); 
    undergrove::$instance->GoalTrack();
    //declenchement fin de partie
    $securite = 0;
    if (undergrove::$instance->getGameStateValue('final') == 0)
    {
    undergrove::$instance->setGameStateValue('final', 1);
    undergrove::$instance->notifyAllPlayers('messagealerte',clienttranslate( '${player_name} has just triggered the end of the game. This will end at the end of the next round.' ), array(
        'player_name' => $this->player_name,
        )
        );
    $securite = 1;
    for ($i = 1; $i <= $this->player_no; $i++)
    {
        self::DbQuery( "UPDATE player set final = 1 WHERE player_no={$i}" );
    }
    }

    if ((undergrove::$instance->getGameStateValue('final') == 1) && ($securite == 0))
    {
    undergrove::$instance->CheckEnd();
    }
    undergrove::$instance->addPendingFirst($this->player_id, "NormalTurn");
} 



function argBonusCarbonTrackTiles($parg1, $parg2)
{
    $ret = array();
    $ret["selectable"] = array();
    $ret['buttons'] = array();
    $ret['title'] = clienttranslate('${actplayer} triggers a bonus from the carbon track');
    $ret['titleyou'] = clienttranslate('${you} must choose a bonus tile');

    $tiles = array();
    if ($parg1 == 2)
    {
    $tiles = self::getObjectListFromDB( "SELECT card_type type FROM tiles WHERE card_location = 'tiles_1'", true );
    }

    if ($parg1 == 4)
    {
    $tiles = self::getObjectListFromDB( "SELECT card_type type FROM tiles WHERE card_location = 'tiles_2'", true );
    }

    if ($parg1 == 6)
    {
    $tiles = self::getObjectListFromDB( "SELECT card_type type FROM tiles WHERE card_location = 'tiles_3'", true );
    }

    foreach($tiles as $type)
    {
        $ret["buttons"][] = "bonustile_".$type;
    }

    
    return $ret;
}

function BonusCarbonTrackTiles($parg1, $parg2, $varg1, $varg2)
{
    $explodebonus = explode("_", $varg1);
    $type = intval($explodebonus[1]);
    $bonusid = self::getUniqueValueFromDB("SELECT card_id FROM tiles WHERE card_type={$type}");
    if ($parg1 == 2)
    {
    $position = 1;
    }
    if ($parg1 == 4)
    {
    $position = 2;
    }
    if ($parg1 == 6)
    {
    $position = 3;
    }
    undergrove::$instance->tiles->moveCard( $bonusid, "tilehand_".$this->player_id, $position);
    undergrove::$instance->notifyAllPlayers('handtile',clienttranslate( '${player_name} gains a bonus tile' ), array(
        'type' =>  $type,
        'position' => $position,
        'player_name' => $this->player_name,
        )
        );

    if (($type == 11)||($type == 12)||($type == 13))
    {
    self::DbQuery( "UPDATE player set bonus_score = bonus_score +1  WHERE player_id = {$this->player_id}" );
    undergrove::$instance->MajRessources();
    }

    if (($type == 14)||($type == 15)||($type == 16))
    {
    self::DbQuery( "UPDATE player set bonus_score = bonus_score +2  WHERE player_id = {$this->player_id}" );
    undergrove::$instance->MajRessources();
    }
    
   
    $this->earthlover(); 
    undergrove::$instance->GoalTrack(); 
    undergrove::$instance->CheckEnd();
    undergrove::$instance->addPendingFirst($this->player_id, "NormalTurn");

} 


/////////////////// Step confirm //////////////

function argConfirm($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} must confirm action');
        $ret['titleyou'] = clienttranslate('${you} confirm your actions ?');

        
        $ret['buttons'][]="Confirm";
        $ret['buttons'][]="Undo";
  
        return $ret;
    }


    function Confirm($parg1, $parg2, $varg1, $varg2)
    {
        $hand = 'hand_'.$this->player_id;

        
        if(undergrove::$instance->getGameStateValue('variable1')!=0)
            {

                $newid = undergrove::$instance->getGameStateValue('variable1');
                self::DbQuery( "UPDATE champignon set card_location = '{$hand}'  WHERE card_id = {$newid}" );
                undergrove::$instance->notifyAllPlayers("hand",'', array(
            
                    'id' =>  $newid,
                    'location' => self::getUniqueValueFromDB("SELECT card_location location FROM champignon WHERE card_id={$newid}"),
                    'position' => self::getUniqueValueFromDB("SELECT card_location_arg location_arg FROM champignon WHERE card_id={$newid}"),
                    'type' => self::getUniqueValueFromDB("SELECT card_type type FROM champignon WHERE card_id={$newid}"),
                    
                )
                );
            }

            if(undergrove::$instance->getGameStateValue('variable2')!=0)
            {
                $newid = undergrove::$instance->getGameStateValue('variable2');
                self::DbQuery( "UPDATE champignon set card_location = '{$hand}'  WHERE card_id = {$newid}" );
                undergrove::$instance->notifyAllPlayers("hand",'', array(
            
                    'id' =>  $newid,
                    'location' => self::getUniqueValueFromDB("SELECT card_location location FROM champignon WHERE card_id={$newid}"),
                    'position' => self::getUniqueValueFromDB("SELECT card_location_arg location_arg FROM champignon WHERE card_id={$newid}"),
                    'type' => self::getUniqueValueFromDB("SELECT card_type type FROM champignon WHERE card_id={$newid}"),
                    
                )
                );
            }

            undergrove::$instance->addPendingFirst($this->player_id, "NormalTurn");


        

    }







}