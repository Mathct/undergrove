<?php 

class Champi20 extends Champi
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
        
        self::DbQuery( "UPDATE player set carbone = carbone WHERE player_id = {$this->player_id}" );
        self::DbQuery( "UPDATE player set azote = azote  WHERE player_id = {$this->player_id}" );
        self::DbQuery( "UPDATE player set phosphore = phosphore  WHERE player_id = {$this->player_id}" );
        self::DbQuery( "UPDATE player set potassium = potassium  WHERE player_id = {$this->player_id}" );

       
        self::DbQuery( "UPDATE player set activation_b = activation_b WHERE player_id = {$this->player_id}" );
        self::DbQuery( "UPDATE player set activation_p = 1 WHERE player_id = {$this->player_id}" );
        self::DbQuery( "UPDATE player set activation_g = 0 WHERE player_id = {$this->player_id}" );
        self::DbQuery( "UPDATE player set activation_y = 1 WHERE player_id = {$this->player_id}" );



        



        

        if ($parg1 != null)
        {
            // carbone doit aller sur le champi copieur $parg1 = champi_id
            
            $explodechampi = explode("_", $parg1);
            $idcopieur = intval($explodechampi[1]);
            $typecopieur = self::getUniqueValueFromDB("SELECT card_type FROM champignon WHERE card_id={$idcopieur}");
            self::DbQuery( "UPDATE champignon set carbone = carbone WHERE card_type = {$typecopieur}" );                        //////////// changer nbre carbone

            undergrove::$instance->notifyAllPlayers("message",clienttranslate( '${player_name} activated ${name1}, copies ${name2} and takes another action' ), array(

                'i18n' => array( 'name1', 'name2' ),
                'player_name' => $this->player_name,
                'name1' => $this->listechampi[$typecopieur]["name"],   
                'name2' => $this->listechampi[20]["name"],   //////////// changer le type
                                             
                )
                );
        }
        
        else 

        {
            self::DbQuery( "UPDATE champignon set carbone = carbone WHERE card_type = 20" );        //////////// changer le type et nbre carbone
            undergrove::$instance->notifyAllPlayers("message",clienttranslate( '${player_name} activated ${name} and takes another action' ), array(

                'i18n' => array( 'name'),
                'player_name' => $this->player_name,
                'name' => $this->listechampi[20]["name"],   //////////// changer le type
                                             
            )
            );
            
        }



        undergrove::$instance->MajRessources();
        undergrove::$instance->GoalTrack();
        
        undergrove::$instance->addPending($this->player_id, "NormalTurn");

    }
}