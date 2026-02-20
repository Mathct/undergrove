<?php 

class Champi25 extends Champi
{
    public function arginit($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} activates a mushroom');
        $ret['titleyou'] = clienttranslate('${you} must choose the first resource to recover');

        $ret['buttons'][]="N";
        $ret['buttons'][]="P";
        $ret['buttons'][]="K";
        $ret['buttons'][]="Cancel";
        
        

        
        return $ret;
     }
    
    public function init($parg1, $parg2, $varg1, $varg2)
    {
        
        self::DbQuery( "UPDATE `player` set `carbone` = `carbone` - 2  WHERE `player_id` = {$this->player_id}" );
        self::DbQuery( "UPDATE `player` set `azote` = `azote`  WHERE `player_id` = {$this->player_id}" );
        self::DbQuery( "UPDATE `player` set `phosphore` = `phosphore`  WHERE `player_id` = {$this->player_id}" );
        self::DbQuery( "UPDATE `player` set `potassium` = `potassium`  WHERE `player_id` = {$this->player_id}" );

       
        self::DbQuery( "UPDATE `player` set `activation_b` = 0 WHERE `player_id` = {$this->player_id}" );
        self::DbQuery( "UPDATE `player` set `activation_p` = `activation_p` WHERE `player_id` = {$this->player_id}" );
        self::DbQuery( "UPDATE `player` set `activation_g` = `activation_g` WHERE `player_id` = {$this->player_id}" );
        self::DbQuery( "UPDATE `player` set `activation_y` = `activation_y` WHERE `player_id` = {$this->player_id}" );


        if ($parg1 != null)
        {
            // carbone doit aller sur le champi copieur $parg1 = champi_id
            
            $explodechampi = explode("_", $parg1);
            $idcopieur = intval($explodechampi[1]);
            $typecopieur = self::getUniqueValueFromDB("SELECT `card_type` FROM `champignon` WHERE `card_id`={$idcopieur}");
            self::DbQuery( "UPDATE `champignon` set `carbone` = `carbone` + 2  WHERE `card_type` = {$typecopieur}" );                        //////////// changer nbre carbone

            undergrove::$instance->notifyAllPlayers("message",clienttranslate( '${player_name} activated ${name1} and copies ${name2}' ), array(

                'i18n' => array( 'name1', 'name2' ),
                'player_name' => $this->player_name,
                'name1' => $this->listechampi[$typecopieur]["name"],   
                'name2' => $this->listechampi[25]["name"],   //////////// changer le type
                                             
                )
                );
        }
        
        else 

        {
            self::DbQuery( "UPDATE `champignon` set `carbone` = `carbone` + 2  WHERE `card_type` = 25" );        //////////// changer le type et nbre carbone
            undergrove::$instance->notifyAllPlayers("message",clienttranslate( '${player_name} activated ${name}' ), array(

                'i18n' => array( 'name'),
                'player_name' => $this->player_name,
                'name' => $this->listechampi[25]["name"],   //////////// changer le type
                                             
            )
            );
            
        }

        if ($varg1 == "N")
        {
            self::DbQuery( "UPDATE `player` set `azote` = `azote` + 1  WHERE `player_id` = {$this->player_id}" );
        }

        if ($varg1 == "P")
        {
            self::DbQuery( "UPDATE `player` set `phosphore` = `phosphore` + 1  WHERE `player_id` = {$this->player_id}" );
        }

        if ($varg1 == "K")
        {
            self::DbQuery( "UPDATE `player` set `potassium` = `potassium` + 1  WHERE `player_id` = {$this->player_id}" );
        }
        undergrove::$instance->MajRessources();
        undergrove::$instance->addPendingTarget($this->player_id, "Champi25", "Champi25Step2");

        

    }


    public function argChampi25Step2($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} activates a mushroom');
        $ret['titleyou'] = clienttranslate('${you} must choose the 2nd resource to recover');

        $ret['buttons'][]="N";
        $ret['buttons'][]="P";
        $ret['buttons'][]="K";
        $ret['buttons'][]="Undo";
        
        

        
        return $ret;
     }
    
    public function Champi25Step2($parg1, $parg2, $varg1, $varg2)
    {
        
        

        if ($varg1 == "N")
        {
            self::DbQuery( "UPDATE `player` set `azote` = `azote` + 1  WHERE `player_id` = {$this->player_id}" );
        }

        if ($varg1 == "P")
        {
            self::DbQuery( "UPDATE `player` set `phosphore` = `phosphore` + 1  WHERE `player_id` = {$this->player_id}" );
        }

        if ($varg1 == "K")
        {
            self::DbQuery( "UPDATE `player` set `potassium` = `potassium` + 1  WHERE `player_id` = {$this->player_id}" );
        }
        undergrove::$instance->MajRessources();
        undergrove::$instance->addPendingTarget($this->player_id, "Champi25", "Champi25Step3");

        

    }

    public function argChampi25Step3($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} activates a mushroom');
        $ret['titleyou'] = clienttranslate('${you} must choose the 3rd resource to recover');

        $ret['buttons'][]="N";
        $ret['buttons'][]="P";
        $ret['buttons'][]="K";
        $ret['buttons'][]="Undo";
        
        

        
        return $ret;
     }
    
    public function Champi25Step3($parg1, $parg2, $varg1, $varg2)
    {
        
        

        if ($varg1 == "N")
        {
            self::DbQuery( "UPDATE `player` set `azote` = `azote` + 1  WHERE `player_id` = {$this->player_id}" );
        }

        if ($varg1 == "P")
        {
            self::DbQuery( "UPDATE `player` set `phosphore` = `phosphore` + 1  WHERE `player_id` = {$this->player_id}" );
        }

        if ($varg1 == "K")
        {
            self::DbQuery( "UPDATE `player` set `potassium` = `potassium` + 1  WHERE `player_id` = {$this->player_id}" );
        }
        undergrove::$instance->MajRessources();
        undergrove::$instance->addPendingTarget($this->player_id, "Champi25", "Champi25Step4");

        

    }

    public function argChampi25Step4($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} activates a mushroom');
        $ret['titleyou'] = clienttranslate('${you} must choose the 4th resource to recover');

        $ret['buttons'][]="N";
        $ret['buttons'][]="P";
        $ret['buttons'][]="K";
        $ret['buttons'][]="Undo";
        
        

        
        return $ret;
     }
    
    public function Champi25Step4($parg1, $parg2, $varg1, $varg2)
    {
        
        

        if ($varg1 == "N")
        {
            self::DbQuery( "UPDATE `player` set `azote` = `azote` + 1  WHERE `player_id` = {$this->player_id}" );
        }

        if ($varg1 == "P")
        {
            self::DbQuery( "UPDATE `player` set `phosphore` = `phosphore` + 1  WHERE `player_id` = {$this->player_id}" );
        }

        if ($varg1 == "K")
        {
            self::DbQuery( "UPDATE `player` set `potassium` = `potassium` + 1  WHERE `player_id` = {$this->player_id}" );
        }
        
        undergrove::$instance->MajRessources();
        undergrove::$instance->GoalTrack();
        undergrove::$instance->CheckEnd();
        undergrove::$instance->addPendingFirst($this->player_id, "NormalTurn");

        

    }

}