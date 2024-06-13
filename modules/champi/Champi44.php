<?php 

class Champi44 extends Champi
{
    public function arginit($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} activates a mushroom');
        $ret['titleyou'] = clienttranslate('${you} activate a mushroom');

        
        
        return $ret;
     }
    
    public function init($parg1, $parg2, $varg1, $varg2)
    {
        
        self::DbQuery( "UPDATE player set carbone = carbone - 1  WHERE player_id = {$this->player_id}" );
        self::DbQuery( "UPDATE player set azote = azote  WHERE player_id = {$this->player_id}" );
        self::DbQuery( "UPDATE player set phosphore = phosphore+3  WHERE player_id = {$this->player_id}" );
        self::DbQuery( "UPDATE player set potassium = potassium  WHERE player_id = {$this->player_id}" );

       
        self::DbQuery( "UPDATE player set activation_b = 0 WHERE player_id = {$this->player_id}" );
        self::DbQuery( "UPDATE player set activation_p = activation_p WHERE player_id = {$this->player_id}" );
        self::DbQuery( "UPDATE player set activation_g = activation_g WHERE player_id = {$this->player_id}" );
        self::DbQuery( "UPDATE player set activation_y = activation_y WHERE player_id = {$this->player_id}" );


        if ($parg1 != null)
        {
            // carbone doit aller sur le champi copieur $parg1 = champi_id
            
            $explodechampi = explode("_", $parg1);
            $idcopieur = intval($explodechampi[1]);
            $typecopieur = self::getUniqueValueFromDB("SELECT card_type FROM champignon WHERE card_id={$idcopieur}");
            self::DbQuery( "UPDATE champignon set carbone = carbone + 1  WHERE card_type = {$typecopieur}" );                        //////////// changer nbre carbone

            undergrove::$instance->notifyAllPlayers("message",clienttranslate( '${player_name} activated ${name1} and copies ${name2}' ), array(

                'i18n' => array( 'name1', 'name2' ),
                'player_name' => $this->player_name,
                'name1' => $this->listechampi[$typecopieur]["name"],   
                'name2' => $this->listechampi[44]["name"],   //////////// changer le type
                                             
                )
                );
        }
        
        else 

        {
            self::DbQuery( "UPDATE champignon set carbone = carbone + 1  WHERE card_type = 44" );        //////////// changer le type et nbre carbone
            undergrove::$instance->notifyAllPlayers("message",clienttranslate( '${player_name} activated ${name}' ), array(

                'i18n' => array( 'name'),
                'player_name' => $this->player_name,
                'name' => $this->listechampi[44]["name"],   //////////// changer le type
                                             
            )
            );
            
        }

        $nbre = self::getUniqueValueFromDB("SELECT nbre FROM champispecial WHERE type = 44");
        if ($nbre == 4)
        {
            undergrove::$instance->MajRessources();
            undergrove::$instance->GoalTrack();
            undergrove::$instance->CheckEnd();
            undergrove::$instance->addPendingFirst($this->player_id, "NormalTurn");
        }

        else
        {
            undergrove::$instance->MajRessources();
            undergrove::$instance->addPendingTarget($this->player_id, "Champi44", "Champi44Step2");
        }

        

    }

    public function argChampi44Step2($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} activates a mushroom');
        $ret['titleyou'] = clienttranslate('${you} must choose the number of resources to place on the mushroom');

        $nbre = self::getUniqueValueFromDB("SELECT nbre FROM champispecial WHERE type = 44");

        if (($nbre >=0) && ($nbre <= 1))
        {
        $ret['buttons'][]="0";
        $ret['buttons'][]="1";
        $ret['buttons'][]="2";
        $ret['buttons'][]="3";
        }

        if ($nbre == 2)
        {
        $ret['buttons'][]="0";
        $ret['buttons'][]="1";
        $ret['buttons'][]="2";
        
        }

        if ($nbre == 3)
        {
        $ret['buttons'][]="0";
        $ret['buttons'][]="1";
                
        }
        
        $ret['buttons'][]="Undo";
        

        
        return $ret;
     }
    
    public function Champi44Step2($parg1, $parg2, $varg1, $varg2)
    {
        
        $nbre = self::getUniqueValueFromDB("SELECT nbre FROM champispecial WHERE type = 44");

        
        if ($varg1 == "1")
        {
            self::DbQuery( "UPDATE player set phosphore = phosphore -1  WHERE player_id = {$this->player_id}" );
            self::DbQuery( "UPDATE champispecial set nbre = nbre + 1  WHERE type = 44" );
        }

        if ($varg1 == "2")
        {
            
            self::DbQuery( "UPDATE player set phosphore = phosphore -2  WHERE player_id = {$this->player_id}" );
            self::DbQuery( "UPDATE champispecial set nbre = nbre + 2  WHERE type = 44" );
        }

        if ($varg1 == "3")
        {
            self::DbQuery( "UPDATE player set phosphore = phosphore -3  WHERE player_id = {$this->player_id}" );
            self::DbQuery( "UPDATE champispecial set nbre = nbre + 3  WHERE type = 44" );
        }

        $newnbre = self::getUniqueValueFromDB("SELECT nbre FROM champispecial WHERE type = 44");

        if ($newnbre == 1)
        {
            self::DbQuery( "UPDATE champispecial set score = 1  WHERE type = 44" );
        }

        if ($newnbre == 2)
        {
            self::DbQuery( "UPDATE champispecial set score = 2  WHERE type = 44" );
        }

        if ($newnbre == 3)
        {
            self::DbQuery( "UPDATE champispecial set score = 3  WHERE type = 44" );
        }

        if ($newnbre == 4)
        {
            self::DbQuery( "UPDATE champispecial set score = 5  WHERE type = 44" );
        }

        if (($newnbre-$nbre) !=0)

        {

            $square = self::getUniqueValueFromDB("SELECT card_location FROM champignon WHERE card_type = 44");
            $explode = explode("_", $square);
            $test = "minisquare_".$explode[1]."_".$explode[2];  

            $racine = self::getObjectListFromDB( "SELECT location location, sens_racine sens FROM foret WHERE location LIKE '$test%' AND type ='racine'" );
            
            foreach ($racine as $controle)
            {
                $exploderacine = explode("_", $controle['location']);
                $semi = "circle_".$exploderacine[3]."_".$exploderacine[4];
                $colonne = "vp_racine".$controle['sens'];
                $newscore = self::getUniqueValueFromDB("SELECT score FROM champispecial WHERE type = 44");

                self::DbQuery( "UPDATE foret set {$colonne} = {$newscore} WHERE location = '{$semi}'" );
            }

            for ($i=($nbre+1); $i <= $newnbre; $i++)

            {
                undergrove::$instance->notifyAllPlayers('champiscore4','', array(
                    'type' => 44,
                    'pos' => $i,
                )
                );
            }




        }

        

        

        undergrove::$instance->MajRessources();
        undergrove::$instance->GoalTrack();
        undergrove::$instance->CheckEnd();
        undergrove::$instance->addPendingFirst($this->player_id, "NormalTurn");

        

    }
}