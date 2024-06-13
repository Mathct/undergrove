<?php 

for($i = 1; $i<=53;$i++)
{
    include("champi/Champi{$i}.php");    
}

class Champi extends APP_GameClass
{
    public $type = 0;
        
    public function __construct()
    {
        $this->type = (int) filter_var(get_class($this), FILTER_SANITIZE_NUMBER_INT);


        $player_id = undergrove::$instance->getActivePlayerId();
        
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

        $this->listechampi = undergrove::$instance->listechampi;



    }
    
    public function arginit($parg1, $parg2)
    {
        
    }
    
    public function init($parg1, $parg2, $varg1, $varg2)
    {
        
    }
    
    
}