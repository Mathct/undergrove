<?php
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * undergrove implementation : © <Mathieu Chatrain> <mathieu.chatrain@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 * -----
 * 
 * undergrove.action.php
 *
 * undergrove main action entry point
 *
 *
 * In this file, you are describing all the methods that can be called from your
 * user interface logic (javascript).
 *       
 * If you define a method "myAction" here, then you can call it from your javascript code with:
 * this.ajaxcall( "/undergrove/undergrove/myAction.html", ...)
 *
 */
  
  
  class action_undergrove extends APP_GameAction
  { 
    // Constructor: please do not modify
   	public function __default()
  	{
  	    if( self::isArg( 'notifwindow') )
  	    {
            $this->view = "common_notifwindow";
  	        $this->viewArgs['table'] = self::getArg( "table", AT_posint, true );
  	    }
  	    else
  	    {
            $this->view = "undergrove_undergrove";
            self::trace( "Complete reinitialization of board game" );
      }
  	} 
  	
  	// TODO: defines your action entry points there


    public function actSelect()
  	{
  	    self::setAjaxMode();
  	    
  	    $arg1 = self::getArg( "arg1", AT_alphanum_dash );
  	    
  	    
  	    $this->game->actSelect( $arg1);
  	    
  	    self::ajaxResponse( );
  	}

	public function actCancel()
  	{
  	    self::setAjaxMode();
  	    
  	    $this->game->actCancel();
  	    
  	    self::ajaxResponse( );
  	}

	
	public function actReproduce()
  	{
  	    self::setAjaxMode();
  	    
  	    $this->game->actReproduce();
  	    
  	    self::ajaxResponse( );
  	}

	public function actYesPlaceChampiReproduce()
  	{
  	    self::setAjaxMode();
  	    
  	    $this->game->actYesPlaceChampiReproduce();
  	    
  	    self::ajaxResponse( );
  	}

	public function actNoPlaceChampiReproduce()
  	{
  	    self::setAjaxMode();
  	    
  	    $this->game->actNoPlaceChampiReproduce();
  	    
  	    self::ajaxResponse( );
  	}

	
	  public function actNobonuschampi()
  	{
  	    self::setAjaxMode();
  	    
  	    $arg1 = self::getArg( "arg1", AT_alphanum_dash );
  	    
  	    
  	    $this->game->actNobonuschampi( $arg1);
  	    
  	    self::ajaxResponse( );
  	}

	 
	  public function actCancel2()
  	{
  	    self::setAjaxMode();
  	    
  	    $this->game->actCancel2();
  	    
  	    self::ajaxResponse( );
  	}

	  public function actCancel3()
  	{
  	    self::setAjaxMode();
  	    
  	    $this->game->actCancel3();
  	    
  	    self::ajaxResponse( );
  	}

	  public function actPartner()
  	{
  	    self::setAjaxMode();
  	    
  	    $this->game->actPartner();
  	    
  	    self::ajaxResponse( );
  	}

	public function actYesPlaceChampiPartner()
  	{
  	    self::setAjaxMode();
  	    
  	    $this->game->actYesPlaceChampiPartner();
  	    
  	    self::ajaxResponse( );
  	}

	public function actNoPlaceChampiPartner()
  	{
  	    self::setAjaxMode();
  	    
  	    $this->game->actNoPlaceChampiPartner();
  	    
  	    self::ajaxResponse( );
  	}

	  public function actFindetour()
  	{
  	    self::setAjaxMode();
  	    
  	    $this->game->actFindetour();
  	    
  	    self::ajaxResponse( );
  	}

	  public function actFindetour2()
  	{
  	    self::setAjaxMode();
  	    
  	    $this->game->actFindetour2();
  	    
  	    self::ajaxResponse( );
  	}

	  public function actPhoto()
  	{
  	    self::setAjaxMode();
  	    
  	    $this->game->actPhoto();
  	    
  	    self::ajaxResponse( );
  	}

	  public function actNoPhotoEchange()
  	{
  	    self::setAjaxMode();
  	    
  	    $this->game->actNoPhotoEchange();
  	    
  	    self::ajaxResponse( );
  	}

	  public function actYesPhotoEchange()
  	{
  	    self::setAjaxMode();

		  $arg1 = self::getArg( "arg1", AT_alphanum_dash );
  	    
  	    $this->game->actYesPhotoEchange($arg1);
  	    
  	    self::ajaxResponse( );
  	}

	  public function actNoPhotoEchangeSupp()
  	{
  	    self::setAjaxMode();
  	    
  	    $this->game->actNoPhotoEchangeSupp();
  	    
  	    self::ajaxResponse( );
  	}

	  public function actYesPhotoEchangeSupp()
  	{
  	    self::setAjaxMode();

		  $arg1 = self::getArg( "arg1", AT_alphanum_dash );
  	    
  	    $this->game->actYesPhotoEchangeSupp($arg1);
  	    
  	    self::ajaxResponse( );
  	}
	
	  public function actValiderPhotoDiscard()
  	{
  	    self::setAjaxMode();
  	    
  	    $arg1 = self::getArg( "arg1", AT_alphanum );
  	    $arg2 = self::getArg( "arg2", AT_alphanum );
		$arg3 = self::getArg( "arg3", AT_alphanum );
  	    
  	    $this->game->actValiderPhotoDiscard( $arg1, $arg2, $arg3 );
  	    
  	    self::ajaxResponse( );
  	}
	
	  public function actActivate()
  	{
  	    self::setAjaxMode();
  	    
  	    $this->game->actActivate();
  	    
  	    self::ajaxResponse( );
  	}

	  public function actAbsorb()
  	{
  	    self::setAjaxMode();
  	    
  	    $this->game->actAbsorb();
  	    
  	    self::ajaxResponse( );
  	}

	  public function actActivation()
  	{
  	    self::setAjaxMode();
		$arg1 = self::getArg( "arg1", AT_alphanum );
  	    
  	    $this->game->actActivation($arg1);
  	    
  	    self::ajaxResponse( );
  	}

	  public function actRessource()
  	{
  	    self::setAjaxMode();
		$arg1 = self::getArg( "arg1", AT_alphanum );
  	    
  	    $this->game->actRessource($arg1);
  	    
  	    self::ajaxResponse( );
  	}

	  public function actFindetour3()
  	{
  	    self::setAjaxMode();
		$arg1 = self::getArg( "arg1", AT_alphanum );
  	    
  	    $this->game->actFindetour3($arg1);
  	    
  	    self::ajaxResponse( );
  	}

	  public function actUndo()
  	{
  	    self::setAjaxMode();
  	    
  	    $this->game->actUndo();
  	    
  	    self::ajaxResponse( );
  	}

	  public function actBonusTile()
  	{
  	    self::setAjaxMode();
		$arg1 = self::getArg( "arg1", AT_alphanum );
  	    
  	    $this->game->actBonusTile($arg1);
  	    
  	    self::ajaxResponse( );
  	}

	  public function actPass()
  	{
  	    self::setAjaxMode();
		$arg1 = self::getArg( "arg1", AT_alphanum );
  	    
  	    $this->game->actPass($arg1);
  	    
  	    self::ajaxResponse( );
  	}

	  public function actNbre()
  	{
  	    self::setAjaxMode();
		$arg1 = self::getArg( "arg1", AT_alphanum );
  	    
  	    $this->game->actNbre($arg1);
  	    
  	    self::ajaxResponse( );
  	}

	  public function actAction()
  	{
  	    self::setAjaxMode();
		$arg1 = self::getArg( "arg1", AT_alphanum );
  	    
  	    $this->game->actAction($arg1);
  	    
  	    self::ajaxResponse( );
  	}

	  public function actConfirm()
  	{
  	    self::setAjaxMode();
  	    
  	    $this->game->actConfirm();
  	    
  	    self::ajaxResponse( );
  	}
















  }
  

