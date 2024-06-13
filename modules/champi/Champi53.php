<?php 

class Champi53 extends Champi
{
    public function arginit($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} activates a mushroom');
        $ret['titleyou'] = clienttranslate('${you} must choose the mushroom to copy');
        
       
        $listechampi =  undergrove::$instance->listechampi;

        $champi = self::getObjectListFromDB( "SELECT card_id id, card_type type FROM champignon WHERE card_location LIKE 'square%' AND card_type !=53 AND card_type !=1 AND (card_type <=27 or card_type >=33)");  
        
        foreach ($champi as $test)
        {
            if(($listechampi[$test['type']]["coutag"]==1) && ($listechampi[$test['type']]["coutc"] <= $this->player_carbone) && ($listechampi[$test['type']]["coutn"] <= $this->player_azote) && ($listechampi[$test['type']]["coutp"] <= $this->player_phosphore) && ($listechampi[$test['type']]["coutk"] <= $this->player_potassium) && ($listechampi[$test['type']]["coutlibre"] <= $this->player_azote+$this->player_phosphore+$this->player_potassium))
            $ret["selectable"][]="champi_".$test['id'];
        }
    
        
        $ret['buttons'][]="Cancel";
        
        return $ret;
     }
    
    public function init($parg1, $parg2, $varg1, $varg2)
    {
        $explodechampi = explode("_", $varg1);
        $id = intval($explodechampi[1]);
        $type = self::getUniqueValueFromDB("SELECT card_type FROM champignon WHERE card_id={$id}");
        $idcopieur = self::getUniqueValueFromDB("SELECT card_id FROM champignon WHERE card_type = 53");
        undergrove::$instance->addPendingTarget($this->player_id, "Champi".$type, "init", "champi_".$idcopieur);
    }
}