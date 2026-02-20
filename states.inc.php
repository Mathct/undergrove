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
 * states.inc.php
 *
 * undergrove game states description
 *
 */



//    !! It is not a good idea to modify this file when a game is running !!

 
$machinestates = array(
    2 => array(
        "name" => "pending",
        "description" => '',
        "type" => "game",
        "action" => "stPending",
        "updateGameProgression" => true,
        "transitions" => array("end" => 99, "player"=> 3, "same" => 2)
    ),
    
    3 => array(
        "name" => "playerTurn",
        "description" => clienttranslate('${actplayer} must take an action or Pass'),
        "descriptionmyturn" => clienttranslate('${you} must take an action or pass'),
        "type" => "activeplayer",
        "args" => "argPlayerTurn",
        "possibleactions" => array( "actSelect"),
        "transitions" => array( "next" => 2, "zombiePass" => 2, "end" => 99)
    ),
);



