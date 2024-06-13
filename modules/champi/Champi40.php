<?php 

class Champi40 extends Champi
{
    public function arginit($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} activates a mushroom');
        $ret['titleyou'] = clienttranslate('${you} must pay 1 resource of your choice');
        
        if ($this->player_azote >=1)
        {
            $ret['buttons'][]="N";
        }

        if ($this->player_phosphore >=1)
        {
            $ret['buttons'][]="P";
        }

        if ($this->player_potassium >=1)
        {
            $ret['buttons'][]="K";
        }

        $ret['buttons'][]="Cancel";

        
        return $ret;
     }
    
    public function init($parg1, $parg2, $varg1, $varg2)
    {
        if ($varg1 == "N")
        {
            self::DbQuery( "UPDATE player set azote = azote - 1  WHERE player_id = {$this->player_id}" );
        }

        if ($varg1 == "P")
        {
            self::DbQuery( "UPDATE player set phosphore = phosphore - 1  WHERE player_id = {$this->player_id}" );
        }

        if ($varg1 == "K")
        {
            self::DbQuery( "UPDATE player set potassium = potassium - 1  WHERE player_id = {$this->player_id}" );
        }
        
        
        self::DbQuery( "UPDATE player set carbone = carbone - 2  WHERE player_id = {$this->player_id}" );
        self::DbQuery( "UPDATE player set azote = azote  WHERE player_id = {$this->player_id}" );
        self::DbQuery( "UPDATE player set phosphore = phosphore  WHERE player_id = {$this->player_id}" );
        self::DbQuery( "UPDATE player set potassium = potassium  WHERE player_id = {$this->player_id}" );

        self::DbQuery( "UPDATE player set activation_b = activation_b  WHERE player_id = {$this->player_id}" );
        self::DbQuery( "UPDATE player set activation_p = 0  WHERE player_id = {$this->player_id}" );
        self::DbQuery( "UPDATE player set activation_g = activation_g  WHERE player_id = {$this->player_id}" );
        self::DbQuery( "UPDATE player set activation_y = activation_y  WHERE player_id = {$this->player_id}" );
        
        undergrove::$instance->setGameStateValue('idcopieur', 0);        

        if ($parg1 != null)
        {
            // carbone doit aller sur le champi copieur $parg1 = champi_id
            
            $explodechampi = explode("_", $parg1);
            $idcopieur = intval($explodechampi[1]);

            undergrove::$instance->setGameStateValue('idcopieur', $idcopieur); 


            $typecopieur = self::getUniqueValueFromDB("SELECT card_type FROM champignon WHERE card_id={$idcopieur}");
            self::DbQuery( "UPDATE champignon set carbone = carbone + 2  WHERE card_type = {$typecopieur}" );                        //////////// changer nbre carbone

            undergrove::$instance->notifyAllPlayers("message",clienttranslate( '${player_name} activated ${name1} and copies ${name2}' ), array(

                'i18n' => array( 'name1', 'name2' ),
                'player_name' => $this->player_name,
                'name1' => $this->listechampi[$typecopieur]["name"],   
                'name2' => $this->listechampi[40]["name"],   //////////// changer le type
                                             
                )
                );
        }
        
        else 

        {
            self::DbQuery( "UPDATE champignon set carbone = carbone + 2  WHERE card_type = 40" );        //////////// changer le type et nbre carbone
            undergrove::$instance->notifyAllPlayers("message",clienttranslate( '${player_name} activated ${name}' ), array(

                'i18n' => array( 'name'),
                'player_name' => $this->player_name,
                'name' => $this->listechampi[40]["name"],   //////////// changer le type
                                             
            )
            );
            
        }



        undergrove::$instance->MajRessources();
        undergrove::$instance->GoalTrack();
        undergrove::$instance->addPendingTarget($this->player_id, "Champi40", "Champi40Step1");
    }

    public function argChampi40Step1($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} activates a mushroom');
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
    
    public function Champi40Step1($parg1, $parg2, $varg1, $varg2)
    {
        
        undergrove::$instance->setGameStateValue('variable1', 0);
        undergrove::$instance->setGameStateValue('variable2', 0);

        $explodesemi = explode("_", $varg1);
        $test = "\_".$explodesemi[1]."\_".$explodesemi[2];  // echapement des "_" pour qu'il ne les prennent pas pour des jokers
        $recupminisquare = self::getObjectListFromDB( "SELECT location location FROM foret WHERE type = 'racine' AND player_id = {$this->player_id} AND location LIKE '%$test'", true );
        
        $a = undergrove::$instance->getGameStateValue('idcopieur');
        if ($a == 0)
        {
            $recupsquare = self::getUniqueValueFromDB("SELECT card_location location FROM champignon WHERE card_type = 40");
        }
        else
        {
            $recupsquare = self::getUniqueValueFromDB("SELECT card_location location FROM champignon WHERE card_id = {$a}");
        }

        $explodesquare = explode("_", $recupsquare);

        $absorb = 0;
        foreach ($recupminisquare as $id)
        {
            $explodeminisquare = explode("_", $id);
            if (($explodeminisquare[1]==$explodesquare[1]) && ($explodeminisquare[2]==$explodesquare[2]) && ($explodeminisquare[3]==$explodesemi[1]) && ($explodeminisquare[4]==$explodesemi[2]))
            {
                $circle = "circle_".$explodesemi[1]."_".$explodesemi[2];
                self::DbQuery( "UPDATE foret set carbone = carbone +1  WHERE location = '{$circle}'" );
                if ($a == 0)
                {
                self::DbQuery( "UPDATE champignon set carbone = carbone -1  WHERE card_type = 40" );
                }
                else
                {
                    self::DbQuery( "UPDATE champignon set carbone = carbone -1  WHERE card_id = {$a}");
                }
                
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


                undergrove::$instance->addPendingTarget($this->player_id, "Champi40", "Champi40SecondAbsorb", $varg1);
                


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
            
            undergrove::$instance->addPendingTarget($this->player_id, "Champi40", "Champi40Step2", $varg1);
            
        }

       
    }

    function argChampi40Step2($parg1, $parg2)
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
    
    function Champi40Step2($parg1, $parg2, $varg1, $varg2)
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
            
            $explodesemi = explode("_", $parg1);
            $circle = "circle_".$explodesemi[1]."_".$explodesemi[2];
            self::DbQuery( "UPDATE foret set carbone = carbone +1  WHERE location = '{$circle}'" );
            $a = undergrove::$instance->getGameStateValue('idcopieur');
            if ($a == 0)
            {
            self::DbQuery( "UPDATE champignon set carbone = carbone -1  WHERE card_type = 40" );
            }
            else
            {
                self::DbQuery( "UPDATE champignon set carbone = carbone -1  WHERE card_id = {$a}");
            }
            /////movecarbone
    
            if ($a == 0)
            {
                $recupsquare = self::getUniqueValueFromDB("SELECT card_location location FROM champignon WHERE card_type = 40");
            }
            else
            {
                $recupsquare = self::getUniqueValueFromDB("SELECT card_location location FROM champignon WHERE card_id = {$a}");
            }

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
    
            undergrove::$instance->addPendingTarget($this->player_id, "Champi40", "Champi40SecondAbsorb", $parg1);
            
      
        }
    
        else
        {
            $nbre=undergrove::$instance->getGameStateValue('variable1')+1;
            undergrove::$instance->setGameStateValue('variable1', $nbre);
            undergrove::$instance->addPendingTarget($this->player_id, "Champi40", "Champi40Step2", $parg1);
            
        }
        
        }


        public function argChampi40SecondAbsorb($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} activates a mushroom');
        $ret['titleyou'] = clienttranslate('${you} can choose a 2nd seedling that will absorb the carbon');

        $explodesemi = explode("_", $parg1);
        $circle2 = "circle_".$explodesemi[1]."_".$explodesemi[2];

        $controle = self::getObjectListFromDB( "SELECT location location FROM foret WHERE type = 'semi' AND player_id = {$this->player_id} AND carbone < 4", true );
    
        
        foreach ($controle as $id)
        {
            $circle = explode ("_", $id);
            $ret["selectable"][]= "semi_".$circle[1]."_".$circle[2]."_".$this->player_id;
        }
        if( $ret["selectable"] != null)
        {
            $ret['buttons'][]="Pass";
            $ret['buttons'][]="Undo";
        }
        
            
        return $ret;
     }
    
    public function Champi40SecondAbsorb($parg1, $parg2, $varg1, $varg2)
    {
        if (($varg1 == NULL) || ($varg1 == "Pass"))
        {
            $this->CarbonTrack();
        }
        if (($varg1 != NULL) && ($varg1 != "Pass"))
        {
            

        undergrove::$instance->setGameStateValue('variable1', 0);
        undergrove::$instance->setGameStateValue('variable2', 0);

        $explodesemi = explode("_", $varg1);
        $test = "\_".$explodesemi[1]."\_".$explodesemi[2];  // echapement des "_" pour qu'il ne les prennent pas pour des jokers
        $recupminisquare = self::getObjectListFromDB( "SELECT location location FROM foret WHERE type = 'racine' AND player_id = {$this->player_id} AND location LIKE '%$test'", true );
        
        $a = undergrove::$instance->getGameStateValue('idcopieur');
        if ($a == 0)
        {
            $recupsquare = self::getUniqueValueFromDB("SELECT card_location location FROM champignon WHERE card_type = 40");
        }
        else
        {
            $recupsquare = self::getUniqueValueFromDB("SELECT card_location location FROM champignon WHERE card_id = {$a}");
        }
        
        
        $explodesquare = explode("_", $recupsquare);

        $absorb = 0;
        foreach ($recupminisquare as $id)
        {
            $explodeminisquare = explode("_", $id);
            if (($explodeminisquare[1]==$explodesquare[1]) && ($explodeminisquare[2]==$explodesquare[2]) && ($explodeminisquare[3]==$explodesemi[1]) && ($explodeminisquare[4]==$explodesemi[2]))
            {
                $circle = "circle_".$explodesemi[1]."_".$explodesemi[2];
                self::DbQuery( "UPDATE foret set carbone = carbone +1  WHERE location = '{$circle}'" );
                if ($a == 0)
                {
                self::DbQuery( "UPDATE champignon set carbone = carbone -1  WHERE card_type = 40" );
                }
                else
                {
                    self::DbQuery( "UPDATE champignon set carbone = carbone -1  WHERE card_id = {$a}");
                }
                
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
            
            undergrove::$instance->addPendingTarget($this->player_id, "Champi40", "Champi40SecondAbsorbStep2", $varg1);
            
        }


        }
        
    }


    function argChampi40SecondAbsorbStep2($parg1, $parg2)
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
    
    function Champi40SecondAbsorbStep2($parg1, $parg2, $varg1, $varg2)
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
            
            $explodesemi = explode("_", $parg1);
            $circle = "circle_".$explodesemi[1]."_".$explodesemi[2];
            self::DbQuery( "UPDATE foret set carbone = carbone +1  WHERE location = '{$circle}'" );
            $a = undergrove::$instance->getGameStateValue('idcopieur');
            if ($a == 0)
            {
            self::DbQuery( "UPDATE champignon set carbone = carbone -1  WHERE card_type = 40" );
            }
            else
            {
                self::DbQuery( "UPDATE champignon set carbone = carbone -1  WHERE card_id = {$a}");
            }
            /////movecarbone
    
            if ($a == 0)
                {
                    $recupsquare = self::getUniqueValueFromDB("SELECT card_location location FROM champignon WHERE card_type = 40");
                }
                else
                {
                    $recupsquare = self::getUniqueValueFromDB("SELECT card_location location FROM champignon WHERE card_id = {$a}");
                }
            
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
            undergrove::$instance->addPendingTarget($this->player_id, "Champi40", "Champi40SecondAbsorbStep2", $parg1);
            
        }
        
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
}