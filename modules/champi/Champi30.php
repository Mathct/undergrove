<?php 

class Champi30 extends Champi
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
        
    }
}