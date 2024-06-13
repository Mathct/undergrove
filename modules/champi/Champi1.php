<?php 

class Champi1 extends Champi
{
    public function arginit($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} activates a mushroom');
        $ret['titleyou'] = clienttranslate('${you} must choose a resource to gain');

        $ret['buttons'][]="N";
        $ret['buttons'][]="P";
        $ret['buttons'][]="K";
      
        $ret['buttons'][]="Cancel";
        
        return $ret;
     }
    
    public function init($parg1, $parg2, $varg1, $varg2)
    {
        if ($varg1 == "N")
        {
            self::DbQuery( "UPDATE player set azote = azote + 1  WHERE player_id = {$this->player_id}" );
        }

        if ($varg1 == "P")
        {
            self::DbQuery( "UPDATE player set phosphore = phosphore + 1  WHERE player_id = {$this->player_id}" );
        }

        if ($varg1 == "K")
        {
            self::DbQuery( "UPDATE player set potassium = potassium + 1  WHERE player_id = {$this->player_id}" );
        }
        undergrove::$instance->MajRessources();
        undergrove::$instance->addPendingTarget($this->player_id, "Champi1", "Champi1Step1");

    }

    public function argChampi1Step1($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} activates a mushroom');
        $ret['titleyou'] = clienttranslate('${you} can choose your first resource to exchange');

        $nbren = self::getUniqueValueFromDB("SELECT azote FROM player WHERE player_id={$this->player_id}");
        $nbrep = self::getUniqueValueFromDB("SELECT phosphore FROM player WHERE player_id={$this->player_id}");
        $nbrek = self::getUniqueValueFromDB("SELECT potassium FROM player WHERE player_id={$this->player_id}");

        if($nbren>=1)
        {
            $ret['buttons'][]="N";
        }

        if($nbrep>=1)
        {
            $ret['buttons'][]="P";
        }

        if($nbrek>=1)
        {
            $ret['buttons'][]="K";
        }
        
        
        $ret['buttons'][]="Findetour";
        $ret['buttons'][]="Undo";
        
        return $ret;
     }
    
    public function Champi1Step1($parg1, $parg2, $varg1, $varg2)
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
        undergrove::$instance->MajRessources();
        undergrove::$instance->addPendingTarget($this->player_id, "Champi1", "Champi1Step2");

    }


    public function argChampi1Step2($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} activates a mushroom');
        $ret['titleyou'] = clienttranslate('${you} must choose the resource to recover');

        $ret['buttons'][]="N";
        $ret['buttons'][]="P";
        $ret['buttons'][]="K";
        $ret['buttons'][]="Undo";
      
        
        
        return $ret;
     }
    
    public function Champi1Step2($parg1, $parg2, $varg1, $varg2)
    {
        if ($varg1 == "N")
        {
            self::DbQuery( "UPDATE player set azote = azote + 1  WHERE player_id = {$this->player_id}" );
        }

        if ($varg1 == "P")
        {
            self::DbQuery( "UPDATE player set phosphore = phosphore + 1  WHERE player_id = {$this->player_id}" );
        }

        if ($varg1 == "K")
        {
            self::DbQuery( "UPDATE player set potassium = potassium + 1  WHERE player_id = {$this->player_id}" );
        }
        undergrove::$instance->MajRessources();
        undergrove::$instance->addPendingTarget($this->player_id, "Champi1", "Champi1Step3");

    }


    public function argChampi1Step3($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} activates a mushroom');
        $ret['titleyou'] = clienttranslate('${you} can choose your 2nd resources to exchange');

        $nbren = self::getUniqueValueFromDB("SELECT azote FROM player WHERE player_id={$this->player_id}");
        $nbrep = self::getUniqueValueFromDB("SELECT phosphore FROM player WHERE player_id={$this->player_id}");
        $nbrek = self::getUniqueValueFromDB("SELECT potassium FROM player WHERE player_id={$this->player_id}");

        if($nbren>=1)
        {
            $ret['buttons'][]="N";
        }

        if($nbrep>=1)
        {
            $ret['buttons'][]="P";
        }

        if($nbrek>=1)
        {
            $ret['buttons'][]="K";
        }
        
        $ret['buttons'][]="Findetour";
        $ret['buttons'][]="Undo";
        
        return $ret;
     }
    
    public function Champi1Step3($parg1, $parg2, $varg1, $varg2)
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
        undergrove::$instance->MajRessources();
        undergrove::$instance->addPendingTarget($this->player_id, "Champi1", "Champi1Step4");

    }

    public function argChampi1Step4($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} activates a mushroom');
        $ret['titleyou'] = clienttranslate('${you} must choose the resource to recover');

        $ret['buttons'][]="N";
        $ret['buttons'][]="P";
        $ret['buttons'][]="K";
        $ret['buttons'][]="Undo";
      
        
        
        return $ret;
     }
    
    public function Champi1Step4($parg1, $parg2, $varg1, $varg2)
    {
        if ($varg1 == "N")
        {
            self::DbQuery( "UPDATE player set azote = azote + 1  WHERE player_id = {$this->player_id}" );
        }

        if ($varg1 == "P")
        {
            self::DbQuery( "UPDATE player set phosphore = phosphore + 1  WHERE player_id = {$this->player_id}" );
        }

        if ($varg1 == "K")
        {
            self::DbQuery( "UPDATE player set potassium = potassium + 1  WHERE player_id = {$this->player_id}" );
        }
        undergrove::$instance->notifyAllPlayers("message",clienttranslate( '${player_name} activated ${name}' ), array(
            
            'i18n' => array( 'name' ),
            'player_name' => $this->player_name,
            'name' => $this->listechampi[1]["name"],    // changer numero

            )
            );
        undergrove::$instance->MajRessources();
        undergrove::$instance->GoalTrack();
        undergrove::$instance->CheckEnd();
        undergrove::$instance->addPendingFirst($this->player_id, "NormalTurn");

    }


}