{OVERALL_GAME_HEADER}

<!-- 
--------
-- BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
-- undergrove implementation : © <Mathieu Chatrain> <mathieu.chatrain@gmail.com>
-- 
-- This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
-- See http://en.boardgamearena.com/#!doc/Studio for more information.
-------

    undergrove_undergrove.tpl
    
    This is the HTML template of your game.
    
    Everything you are writing in this file will be displayed in the HTML page of your game user interface,
    in the "main game zone" of the screen.
    
    You can use in this template:
    _ variables, with the format {MY_VARIABLE_ELEMENT}.
    _ HTML block, with the BEGIN/END format
    
    See your "view" PHP file to check how to set variables and control blocks
    
    Please REMOVE this comment before publishing your game on BGA
-->

<div id="global">
    
    <div id="messagealerte" class="masque">{LASTTURN}</div>

    <div class="zoom">
            <div id="zoomplus"></div>
            <div id="zoomminus"></div>
            <div id="zoomcenter"></div>
            </div>
    
    <div id="map_container">
    <div id="map_scrollable"></div>
    <div id="map_surface"></div>
    <div id="map_scrollable_oversurface">
    
    <!-- BEGIN square -->
            <div id="square_{X}_{Y}" class="square" style="left: {LEFT}px; top: {TOP}px;"></div>
            
    <!-- END square -->
    
    </div>

    <div class="movetop"></div> 
    <div class="movedown"></div> 
    <div class="moveleft"></div> 
    <div class="moveright"></div> 
    </div>

<div id="global2">
    <div id="carbontrack">
    <div id="track0" class="track"></div>
    <div id="track1" class="track"></div>
    <div id="track2" class="track"></div>
    <div id="track3" class="track"></div>
    <div id="track4" class="track"></div>
    <div id="track5" class="track"></div>
    <div id="track6" class="track"></div>
    <div id="track7" class="track"></div>
    <div id="track8" class="track"></div>
    <div id="track9" class="track"></div>
    <div id="track10" class="track"></div>
    <div id="track11" class="track"></div>
    <div id="track12" class="track"></div>
    <div id="track13" class="track"></div>
    <div id="selecttrack9" class="selecttrack"></div>
    <div id="selecttrack10" class="selecttrack"></div>
    <div id="selecttrack11" class="selecttrack"></div>
    <div id="selecttrack12" class="selecttrack"></div>
    <div id="selecttrack13" class="selecttrack"></div>
    <div id="tiles_1" class="tilesboard" style="left: 49px; top: 348px;"></div>
    <div id="tiles_2" class="tilesboard" style="left: 203px; top: 249px;"></div>
    <div id="tiles_3" class="tilesboard" style="left: 28px; top: 161px;"></div>
    <div id="starthere"></div>
    <div id="gameend"></div>
    </div>

    <div id="goals">
    <div id="aide">?</div>
        <div id="goalstitre" class="goalstitre">{GOALS}</div>
        <div id="goals_1" class="goals" style="left: 10px; top: 20px;">
            <!-- BEGIN scoregoal1 -->
                <div id="scoregoal_1_{NO}" class="scoregoal_{COLOR}" style="left: {X}px; top: 5px;"></div>
            <!-- END scoregoal1 -->
            </div>
        <div id="goals_2" class="goals" style="left: 10px; top: 170px;">
            <!-- BEGIN scoregoal2 -->
                <div id="scoregoal_2_{NO}" class="scoregoal_{COLOR}" style="left: {X}px; top: 5px;"></div>
            <!-- END scoregoal2 -->
        </div>
        <div id="goals_3" class="goals" style="left: 10px; top: 320px;">
            <!-- BEGIN scoregoal3 -->
                <div id="scoregoal_3_{NO}" class="scoregoal_{COLOR}" style="left: {X}px; top: 5px;"></div>
            <!-- END scoregoal3 -->
        </div>

    </div>


    
    
    <!-- BEGIN player -->
    <div id="playerhand" class="playerhand">
        <div id="playertitre" class="playertitre">{MY_HAND}</div>
        <div id="hand_{PLAYERID}_1" class="square" style="left: 15px; top: 20px;"></div>
        <div id="hand_{PLAYERID}_2" class="square" style="left: 170px; top: 20px;"></div>
        <div id="hand_{PLAYERID}_3" class="square" style="left: 325px; top: 20px;"></div>
    </div>
    <div id="playertiles" class="playertiles">
        <div id="playertilestitre" class="playertilestitre">{MY_TILES}</div>
        <div id="tiles_{PLAYERID}_1" class="tiles" style="left: 10px; top: 20px;"></div>
        <div id="tiles_{PLAYERID}_2" class="tiles" style="left: 10px; top: 70px;"></div>
        <div id="tiles_{PLAYERID}_3" class="tiles" style="left: 10px; top: 120px;"></div>
    </div>
    
    <!-- END player -->

</div>    

     <!-- BEGIN playerboard -->
    <div id="playerboard" class="playerboard">
        <div id="playerboard_{ID}" class="playerboard_{COLOR}"></div>
        <div id="aide_1"></div>
        <div id="aide_2"></div>
        <div id="aide_3"></div>
        <div id="aide_4"></div>
        <div id="aide_5"></div>
        <div id="aide_6"></div>
        <div id="bigicone_activation_b_{ID}" class="bigicone_activation_b"></div>
        <div id="bigicone_activation_p_{ID}" class="bigicone_activation_p"></div>
        <div id="bigicone_activation_g_{ID}" class="bigicone_activation_g"></div>
        <div id="bigicone_activation_y_{ID}" class="bigicone_activation_y"></div>
        <div id="bignbre_n_{ID}" class="bignbre_n"></div>
        <div id="bignbre_p_{ID}" class="bignbre_p"></div>
        <div id="bignbre_k_{ID}" class="bignbre_k"></div>
        <div id="bignbre_c_{ID}" class="bignbre_c"></div>
        <div id="bigiconeracine_{ID}" class="bigiconeracine_{COLOR}">
        </div><div id="bignbreracine_{ID}" class="bignbreracine"></div>
        <div id="bigiconesemi_{ID}" class="bigiconesemi_{COLOR}">
        </div><div id="bignbresemi_{ID}" class="bignbresemi"></div>
        <div id="bigiconearbre_{ID}" class="bigiconearbre_{COLOR}">
        </div><div id="bignbrearbre_{ID}" class="bignbrearbre"></div>
    </div>
     <!-- END playerboard -->

    <div id="scorepad">
    <div id="name0"></div>
    <div id="name1"></div>
    <div id="name2"></div>
    <div id="name3"></div>
    <div id="padgoal1_1"></div>
    <div id="padgoal2_1"></div>
    <div id="padgoal3_1"></div>
    <div id="padtotalgoal_1"></div>
    <div id="padplant1_1"></div>
    <div id="padplant2_1"></div>
    <div id="padplant3_1"></div>
    <div id="padplant4_1"></div>
    <div id="padplant5_1"></div>
    <div id="padplant6_1"></div>
    <div id="padtotalplant_1"></div>
    <div id="padbonus_1"></div>
    <div id="padressources_1"></div>
    <div id="padtotal_1"></div>
    <div id="padgoal1_2"></div>
    <div id="padgoal2_2"></div>
    <div id="padgoal3_2"></div>
    <div id="padtotalgoal_2"></div>
    <div id="padplant1_2"></div>
    <div id="padplant2_2"></div>
    <div id="padplant3_2"></div>
    <div id="padplant4_2"></div>
    <div id="padplant5_2"></div>
    <div id="padplant6_2"></div>
    <div id="padtotalplant_2"></div>
    <div id="padbonus_2"></div>
    <div id="padressources_2"></div>
    <div id="padtotal_2"></div>
    <div id="padgoal1_3"></div>
    <div id="padgoal2_3"></div>
    <div id="padgoal3_3"></div>
    <div id="padtotalgoal_3"></div>
    <div id="padplant1_3"></div>
    <div id="padplant2_3"></div>
    <div id="padplant3_3"></div>
    <div id="padplant4_3"></div>
    <div id="padplant5_3"></div>
    <div id="padplant6_3"></div>
    <div id="padtotalplant_3"></div>
    <div id="padbonus_3"></div>
    <div id="padressources_3"></div>
    <div id="padtotal_3"></div>
    <div id="padgoal1_4"></div>
    <div id="padgoal2_4"></div>
    <div id="padgoal3_4"></div>
    <div id="padtotalgoal_4"></div>
    <div id="padplant1_4"></div>
    <div id="padplant2_4"></div>
    <div id="padplant3_4"></div>
    <div id="padplant4_4"></div>
    <div id="padplant5_4"></div>
    <div id="padplant6_4"></div>
    <div id="padtotalplant_4"></div>
    <div id="padbonus_4"></div>
    <div id="padressources_4"></div>
    <div id="padtotal_4"></div>
    
    </div>

</div>




<script type="text/javascript">

var jstpl_square = '<div id="square_${x}_${y}" class="square" style="left: ${left}px; top: ${top}px;"></div>';
var jstpl_minisquare = '<div id="minisquare_${x1}_${y1}_${x2}_${y2}" class="minisquare" style="left: ${left}px; top: ${top}px;"></div>';
var jstpl_circle = '<div id="circle_${x}_${y}" class="circle" style="left: ${left}px; top: ${top}px;"></div>';

var jstpl_champi = '<div id="champi_${id}" class="champi" style="background-position-x: ${x}%; background-position-y: ${y}%;"></div>';
var jstpl_semi = '<div id="semi_${x}_${y}_${id}" class="semi semi_${color}"></div>';
var jstpl_arbre = '<div id="arbre_${x}_${y}_${id}" class="arbre_${color}"></div>';
var jstpl_racine = '<div id="racine_${x1}_${y1}_${x2}_${y2}_${id}" class="racine_${color} sens_${sens}"></div>';
var jstpl_carbonechampi = '<div id="carbonechampi_${id}" class="carbonechampi"><div id="valeurcarbonechampi_${id}" class="valeurchampicarbone"></div></div>';
var jstpl_carbonesemi = '<div id="carbonesemi_${x}_${y}_${id}" class="carbonesemi"><div id="valeurcarbonesemi_${x}_${y}_${id}" class="valeursemicarbone"></div></div>';

var jstpl_marqueur = '<div id="marqueur_${id}" class="marqueur_${color}"></div>';
var jstpl_tile = '<div id="tilehand_${id}_${pos}" class="tileshand" style="background-position-x: ${x}px;"></div>';
var jstpl_goal = '<div id="goal_${pos}" class="goal" style="background-position-x: ${x}px;"></div>';

var jstpl_champitool='<div class="champitool" style="background-position-x: ${x}px;"></div><br><div style="text-align: center; font-weight: bold;">${name}</div><div style="text-align: center;">${description}</div>';
var jstpl_aidetool='<div style="text-align: center; font-weight: bold;">${name}</div><div class="aidetool"></div><br><div style="text-align: center;">${description1}</div><div style="text-align: center;">${description2}</div>';
var jstpl_goaltool='<div class="goaltool" style="background-position-x: ${x}px;"></div><br><div style="text-align: center;">${description}</div>';
var jstpl_actionstool='<div class="actionstool" style="background-position-x: ${x}px;"></div><br><div style="text-align: center; font-weight: bold;">${name}</div><div style="text-align: center;">${description}</div>';

var jstpl_squarescore2='<div id="squarescore2_${type}_${pos}" class="squarescore2_${pos}"></div>';
var jstpl_squarescore4='<div id="squarescore4_${type}_${pos}" class="squarescore4_${pos}"></div>';
var jstpl_champiscore2='<div id="champiscore2_${type}_${pos}" class="champiscore2"></div>';
var jstpl_champiscore4='<div id="champiscore4_${type}_${pos}" class="champiscore4_${type}"></div>';

var jstpl_player_activation = '<div class="player_activation" id="player_activation_${id}" style="display: flex; align-items: center; flex-direction: column; justify-content: center; z-index: 100; position: relative;">\
<div id="icone_activation" style="text-align: center; display: flex; margin-top: 10px; margin-bottom: 5px;">\
<div id="icone_activation_b_${id}" class="icone_activation_b" style="display: inline-block; margin-right: 4px;"></div>\
<div id="icone_activation_p_${id}" class="icone_activation_p" style="display: inline-block; margin-right: 4px;"></div>\
<div id="icone_activation_g_${id}" class="icone_activation_g" style="display: inline-block; margin-right: 4px;"></div>\
<div id="icone_activation_y_${id}" class="icone_activation_y" style="display: inline-block; margin-right: 4px;"></div>\
</div>\
</div>';

var jstpl_player_ressources = '<div class="player_ressources" id="player_ressources_${id}" style="display: flex; align-items: center; flex-direction: column; justify-content: center; z-index: 100; position: relative;">\
<div id="icone_ressources" style="text-align: center; align-items: center; display: flex; margin-top: 10px; margin-bottom: 5px;">\
<div id="icone_n" class="icone_n" style="display: inline-block; margin-right: 6px;"></div>\
<div id="nbre_n_${id}" class="nbre_n" style="display: inline-block; margin-right: 4px;"></div>\
<div id="icone_p" class="icone_p" style="display: inline-block; margin-right: 6px;"></div>\
<div id="nbre_p_${id}" class="nbre_p" style="display: inline-block; margin-right: 4px;"></div>\
<div id="icone_k" class="icone_k" style="display: inline-block; margin-right: 6px;"></div>\
<div id="nbre_k_${id}" class="nbre_k" style="display: inline-block; margin-right: 4px;"></div>\
<div id="icone_c" class="icone_c" style="display: inline-block; margin-right: 6px;"></div>\
<div id="nbre_c_${id}" class="nbre_c" style="display: inline-block; margin-right: 4px;"></div>\
</div>\
</div>';

var jstpl_player_ressources2 = '<div class="player_ressources2" id="player_ressources2_${id}" style="display: flex; align-items: center; flex-direction: column; justify-content: center; z-index: 100; position: relative;">\
<div id="icone_ressources2" style="text-align: center; align-items: center; display: flex; margin-top: 10px; margin-bottom: 5px;">\
<div id="iconeracine_${id}" class="iconeracine_${color}" style="display: inline-block; margin-right: 6px;"></div>\
<div id="nbreracine_${id}" class="nbre_racine" style="display: inline-block; margin-right: 4px;"></div>\
<div id="iconesemi_${id}" class="iconesemi_${color}" style="display: inline-block; margin-right: 6px;"></div>\
<div id="nbresemi_${id}" class="nbre_semi" style="display: inline-block; margin-right: 4px;"></div>\
<div id="iconearbre_${id}" class="iconearbre_${color}" style="display: inline-block; margin-right: 6px;"></div>\
<div id="nbrearbre_${id}" class="nbre_arbre" style="display: inline-block; margin-right: 4px;"></div>\
</div>\
</div>';

var jstpl_firstplayer='<div id="firstplayer" title="First Player"></div>';


</script>  

{OVERALL_GAME_FOOTER}
