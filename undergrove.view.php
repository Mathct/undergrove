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
 * undergrove.view.php
 *
 * This is your "view" file.
 *
 * The method "build_page" below is called each time the game interface is displayed to a player, ie:
 * _ when the game starts
 * _ when a player refreshes the game page (F5)
 *
 * "build_page" method allows you to dynamically modify the HTML generated for the game interface. In
 * particular, you can set here the values of variables elements defined in undergrove_undergrove.tpl (elements
 * like {MY_VARIABLE_ELEMENT}), and insert HTML block elements (also defined in your HTML template file)
 *
 * Note: if the HTML of your game interface is always the same, you don't have to place anything here.
 *
 */
  
require_once( APP_BASE_PATH."view/common/game.view.php" );
  
class view_undergrove_undergrove extends game_view
{
    protected function getGameName()
    {
        // Used for translations and stuff. Please do not modify.
        return "undergrove";
    }
    
    function build_page( $viewArgs )
    {		
        
        
        
        // Get players & players number
      global $g_user;
      $current_player_id = $g_user->get_id(); // id current player
      $spectator = $this->game->isSpectator();  // true ou false
      $players = $this->game->loadPlayersBasicInfos();
      
      $players_nbr = count( $players );
      $template = self::getGameName() . "_" . self::getGameName();

      $player_ordre = $this->game->getPlayerRelativePositions();

      /*********** Place your code below:  ************/
      
      $this->page->begin_block($template, "square");
      
      
      $this->page->insert_block( "square", array(
          'X' => 0,
          'Y' => 0,
          'LEFT' => -70,
          'TOP' => -70,
          
      ) );

      $this->page->insert_block( "square", array(
          'X' => -1,
          'Y' => 0,
          'LEFT' => -70-140,
          'TOP' => -70,
          
      ) );

      $this->page->insert_block( "square", array(
          'X' => 0,
          'Y' => -1,
          'LEFT' => -70,
          'TOP' => -70-140,
          
      ) );

      $this->page->insert_block( "square", array(
          'X' => 1,
          'Y' => 0,
          'LEFT' => -70+140,
          'TOP' => -70,
          
      ) );

      $this->page->insert_block( "square", array(
          'X' => 0,
          'Y' => 1,
          'LEFT' => -70,
          'TOP' => -70+140,
          
      ) );

           
      
      $this->page->begin_block($template, "player");
      $this->page->insert_block( "player", array(
          'PLAYERID' => $current_player_id,
      ) );
      
      $this->tpl['MY_HAND'] = self::_("My mushrooms");
      $this->tpl['MY_TILES'] = self::_("Bonus");
      $this->tpl['GOALS'] = self::_("Goals");
      $this->tpl['LASTTURN'] = self::_("The endgame has just been triggered. One more full turn and it's the end.");


      $this->page->begin_block($template, "scoregoal1");
      if($players_nbr == 2)
      {
        $this->page->insert_block( "scoregoal1", array(
            'X' => 186,
            'COLOR' => "6c4740",
            'NO' => 1,
                        
        ) );

        $this->page->insert_block( "scoregoal1", array(
            'X' => 213,
            'COLOR' => "708975",
            'NO' => 2,
                        
        ) );

      }

      if($players_nbr == 3)
      {
        $this->page->insert_block( "scoregoal1", array(
            'X' => 160,
            'COLOR' => "6c4740",
            'NO' => 1,
                        
        ) );

        $this->page->insert_block( "scoregoal1", array(
            'X' => 186,
            'COLOR' => "708975",
            'NO' => 2,
                        
        ) );

        $this->page->insert_block( "scoregoal1", array(
            'X' => 213,
            'COLOR' => "caab5d",
            'NO' => 3,
                        
        ) );

      }

      if($players_nbr == 4)
      {
        $this->page->insert_block( "scoregoal1", array(
            'X' => 134,
            'COLOR' => "6c4740",
            'NO' => 1,
                        
        ) );

        $this->page->insert_block( "scoregoal1", array(
            'X' => 160,
            'COLOR' => "708975",
            'NO' => 2,
                        
        ) );

        $this->page->insert_block( "scoregoal1", array(
            'X' => 186,
            'COLOR' => "caab5d",
            'NO' => 3,
                        
        ) );

        $this->page->insert_block( "scoregoal1", array(
            'X' => 213,
            'COLOR' => "ffffff",
            'NO' => 4,
                        
        ) );

      }



      $this->page->begin_block($template, "scoregoal2");
      if($players_nbr == 2)
      {
        $this->page->insert_block( "scoregoal2", array(
            'X' => 186,
            'COLOR' => "6c4740",
            'NO' => 1,
                        
        ) );

        $this->page->insert_block( "scoregoal2", array(
            'X' => 213,
            'COLOR' => "708975",
            'NO' => 2,
                        
        ) );

      }

      if($players_nbr == 3)
      {
        $this->page->insert_block( "scoregoal2", array(
            'X' => 160,
            'COLOR' => "6c4740",
            'NO' => 1,
                        
        ) );

        $this->page->insert_block( "scoregoal2", array(
            'X' => 186,
            'COLOR' => "708975",
            'NO' => 2,
                        
        ) );

        $this->page->insert_block( "scoregoal2", array(
            'X' => 213,
            'COLOR' => "caab5d",
            'NO' => 3,
                        
        ) );

      }

      if($players_nbr == 4)
      {
        $this->page->insert_block( "scoregoal2", array(
            'X' => 134,
            'COLOR' => "6c4740",
            'NO' => 1,
                        
        ) );

        $this->page->insert_block( "scoregoal2", array(
            'X' => 160,
            'COLOR' => "708975",
            'NO' => 2,
                        
        ) );

        $this->page->insert_block( "scoregoal2", array(
            'X' => 186,
            'COLOR' => "caab5d",
            'NO' => 3,
                        
        ) );

        $this->page->insert_block( "scoregoal2", array(
            'X' => 213,
            'COLOR' => "ffffff",
            'NO' => 4,
                        
        ) );

      }



      $this->page->begin_block($template, "scoregoal3");
      if($players_nbr == 2)
      {
        $this->page->insert_block( "scoregoal3", array(
            'X' => 186,
            'COLOR' => "6c4740",
            'NO' => 1,
                        
        ) );

        $this->page->insert_block( "scoregoal3", array(
            'X' => 213,
            'COLOR' => "708975",
            'NO' => 2,
                        
        ) );

      }

      if($players_nbr == 3)
      {
        $this->page->insert_block( "scoregoal3", array(
            'X' => 160,
            'COLOR' => "6c4740",
            'NO' => 1,
                                    
        ) );

        $this->page->insert_block( "scoregoal3", array(
            'X' => 186,
            'COLOR' => "708975",
            'NO' => 2,
                        
        ) );

        $this->page->insert_block( "scoregoal3", array(
            'X' => 213,
            'COLOR' => "caab5d",
            'NO' => 3,
                        
        ) );

      }

      if($players_nbr == 4)
      {
        $this->page->insert_block( "scoregoal3", array(
            'X' => 134,
            'COLOR' => "6c4740",
            'NO' => 1,
                        
        ) );

        $this->page->insert_block( "scoregoal3", array(
            'X' => 160,
            'COLOR' => "708975",
            'NO' => 2,
                        
        ) );

        $this->page->insert_block( "scoregoal3", array(
            'X' => 186,
            'COLOR' => "caab5d",
            'NO' => 3,
                        
        ) );

        $this->page->insert_block( "scoregoal3", array(
            'X' => 213,
            'COLOR' => "ffffff",
            'NO' => 4,
                        
        ) );

      }

      if($spectator === false)
      {
      $this->page->begin_block($template, "playerboard");
      $this->page->insert_block( "playerboard", array(
        'ID' => $current_player_id,
        'COLOR' => $players[$current_player_id]['player_color'],
        
                    
    ) );
    
      
      }



      /*********** Do not change anything below this line  ************/
    }
}
