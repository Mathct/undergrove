/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * undergrove implementation : © <Mathieu Chatrain> <mathieu.chatrain@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * undergrove.js
 *
 * undergrove user interface script
 * 
 * In this file, you are describing the logic of your user interface, in Javascript language.
 *
 */

define([
    "dojo","dojo/_base/declare",
    "ebg/core/gamegui",
    "ebg/counter",
    "ebg/scrollmap",
    "ebg/zone"
],
function (dojo, declare) {
    return declare("bgagame.undergrove", ebg.core.gamegui, {
        constructor: function(){
            console.log('undergrove constructor');
              
            this.track0 = new ebg.zone();
            this.track1 = new ebg.zone();
            this.track2 = new ebg.zone();
            this.track3 = new ebg.zone();
            this.track4 = new ebg.zone();
            this.track5 = new ebg.zone();
            this.track6 = new ebg.zone();
            this.track7 = new ebg.zone();
            this.track8 = new ebg.zone();
            this.track9 = new ebg.zone();
            this.track10 = new ebg.zone();
            this.track11 = new ebg.zone();
            this.track12 = new ebg.zone();
            this.track13 = new ebg.zone();

        },
        
        /*
            setup:
            
            This method must set up the game user interface according to current game situation specified
            in parameters.
            
            The method is called each time the game interface is displayed to a player, ie:
            _ when the game starts
            _ when a player refreshes the game page (F5)
            
            "gamedatas" argument contains all datas retrieved by your "getAllDatas" PHP method.
        */
        
        setup: function( gamedatas )
        {

            console.log( "Starting game setup" );

/////////////////////////////////////////////////////////////////////////////////           
//    _____                      _____        _            
//   / ____|                    |  __ \      | |           
//  | |  __  __ _ _ __ ___   ___| |  | | __ _| |_ __ _ ___ 
//  | | |_ |/ _` | '_ ` _ \ / _ \ |  | |/ _` | __/ _` / __|
//  | |__| | (_| | | | | | |  __/ |__| | (_| | || (_| \__ \
//   \_____|\__,_|_| |_| |_|\___|_____/ \__,_|\__\__,_|___/
//                                                        
/////////////////////////////////////////////////////////////////////////////////  

            this.players = gamedatas.players;



            if (this.prefs[100].value == 1)
           {
            dojo.query("#playerboard").addClass("masque");
            var monDiv = document.getElementById("global");
            monDiv.style.height = "750px";
           }
           if (this.prefs[100].value == 2)
           {
            dojo.query("#playerboard").removeClass("masque");
            var monDiv = document.getElementById("global");
                monDiv.style.height = "1350px";
           }





            this.track0.create( this, 'track0', 20, 20 );
            this.track0.setPattern( 'horizontalfit' );
            this.track1.create( this, 'track1', 20, 20 );
            this.track1.setPattern( 'horizontalfit' );
            this.track2.create( this, 'track2', 20, 20 );
            this.track2.setPattern( 'horizontalfit' );
            this.track3.create( this, 'track3', 20, 20 );
            this.track3.setPattern( 'horizontalfit' );
            this.track4.create( this, 'track4', 20, 20 );
            this.track4.setPattern( 'horizontalfit' );
            this.track5.create( this, 'track5', 20, 20 );
            this.track5.setPattern( 'horizontalfit' );
            this.track6.create( this, 'track6', 20, 20 );
            this.track6.setPattern( 'horizontalfit' );
            this.track7.create( this, 'track7', 20, 20 );
            this.track7.setPattern( 'horizontalfit' );
            this.track8.create( this, 'track8', 20, 20 );
            this.track8.setPattern( 'horizontalfit' );
            this.track9.create( this, 'track9', 20, 20 );
            this.track9.setPattern( 'horizontalfit' );
            this.track10.create( this, 'track10', 20, 20 );
            this.track10.setPattern( 'horizontalfit' );
            this.track11.create( this, 'track11', 20, 20 );
            this.track11.setPattern( 'horizontalfit' );
            this.track12.create( this, 'track12', 20, 20 );
            this.track12.setPattern( 'horizontalfit' );
            this.track13.create( this, 'track13', 20, 20 );
            this.track13.setPattern( 'horizontalfit' );


            


            this.scrollmap = new ebg.scrollmap(); // declare an object (this can also go in constructor)
            // Make map scrollable        	
            this.scrollmap.create( $('map_container'),$('map_scrollable'),$('map_surface'),$('map_scrollable_oversurface') ); // use ids from template
            this.scrollmap.setupOnScreenArrows( 100 ); // this will hook buttons to onclick functions with 150px scroll step

            this.trl_zoom = 1;
            dojo.connect($('zoomplus'), 'onclick', () => this.onZoomButton(0.2));
            dojo.connect($('zoomminus'), 'onclick', () => this.onZoomButton(-0.2));
            dojo.connect($('zoomcenter'), 'onclick', () => this.onZoomCenter());

            
 
            if(this.isSpectator)
            {
                dojo.query("#playerhand").addClass("masque");
                dojo.query("#playertiles").addClass("masque");
                dojo.query("#playerboard").addClass("masque");
                var monDiv = document.getElementById("global");
                monDiv.style.height = "750px";
            }
            
            // Setting up player boards
            for( var player_id in gamedatas.players )
            {
                var player = gamedatas.players[player_id];
                         
                // TODO: Setting up players boards if needed
            }

            for( var t in gamedatas.track)   
            {
                var variabletrack = gamedatas.track[t];
                this.addMarqueur(t, variabletrack[0], variabletrack[1]);
                this['track' + variabletrack[0]].placeInZone('marqueur_' + t);                
               
            }
            


            for (var champi in gamedatas.champiforet)

            {
                var variablechampi = gamedatas.champiforet[champi];
                if( variablechampi.id !== null )
                {
                    this.addChampi(variablechampi.id, variablechampi.location, variablechampi.type, variablechampi.carbone);
                }

            }

            
            for (var hand in gamedatas.hand)

            {
                var variablehand = gamedatas.hand[hand];
                if( variablehand.id !== null )
                {
                    this.addChampiHand( variablehand.id, variablehand.location, variablehand.location_arg, variablehand.type);
                }

            }

            for (var tile in gamedatas.tilehand)

            {
                var variabletilehand = gamedatas.tilehand[tile];
                if( variabletilehand.id !== null )
                {
                    var tableau = variabletilehand.location.split("_");
                    var player = parseInt(tableau[1], 10);
                    this.addTileHand( variabletilehand.type, player, variabletilehand.location_arg);
                }

            }



            for( var player_id in gamedatas.players )   
            {
                                        
                var player_board_div = $('player_board_'+player_id);
                dojo.place( this.format_block('jstpl_player_activation', {id: player_id } ), player_board_div );
                
                
            }


            for( var i in gamedatas.activation )   
            {
                var player = i;

                if(gamedatas.activation[i][0] == 1)
                {
                dojo.query("#icone_activation_b_"+i).removeClass("used");
                dojo.query("#bigicone_activation_b_"+i).removeClass("bigused");
                }
                if(gamedatas.activation[i][0] == 0)
                {
                dojo.query("#icone_activation_b_"+i).addClass("used");
                dojo.query("#bigicone_activation_b_"+i).addClass("bigused");
                }

                if(gamedatas.activation[i][1] == 1)
                {
                dojo.query("#icone_activation_p_"+i).removeClass("used");
                dojo.query("#bigicone_activation_p_"+i).removeClass("bigused");
                }
                if(gamedatas.activation[i][1] == 0)
                {
                dojo.query("#icone_activation_p_"+i).addClass("used");
                dojo.query("#bigicone_activation_p_"+i).addClass("bigused");
                }
        
                if(gamedatas.activation[i][2] == 1)
                {
                dojo.query("#icone_activation_g_"+i).removeClass("used");
                dojo.query("#bigicone_activation_g_"+i).removeClass("bigused");
                }
                if(gamedatas.activation[i][2] == 0)
                {
                dojo.query("#icone_activation_g_"+i).addClass("used");
                dojo.query("#bigicone_activation_g_"+i).addClass("bigused");
                }
        
                if(gamedatas.activation[i][3] == 1)
                {
                dojo.query("#icone_activation_y_"+i).removeClass("used");
                dojo.query("#bigicone_activation_y_"+i).removeClass("bigused");
                }
                if(gamedatas.activation[i][3] == 0)
                {
                dojo.query("#icone_activation_y_"+i).addClass("used");
                dojo.query("#bigicone_activation_y_"+i).addClass("bigused");
                }
        
        
                
                
            }



            for( var player_id in gamedatas.players )   
            {
                                        
                var player_board_div = $('player_board_'+player_id);
                dojo.place( this.format_block('jstpl_player_ressources', {id: player_id } ), player_board_div );
                
                
            }

            for( var player_id in gamedatas.players )   
            {
                                 
                var player_board_div = $('player_board_'+player_id);
                dojo.place( this.format_block('jstpl_player_ressources2', {id: player_id, color: gamedatas.players[player_id].color } ), player_board_div );
                
                
            }

            for( var k in gamedatas.ressources2 )   
            {
                                
                $('nbreracine_'+k).innerHTML = gamedatas.ressources2[k][0];
                $('nbresemi_'+k).innerHTML = gamedatas.ressources2[k][1];
                $('nbrearbre_'+k).innerHTML = gamedatas.ressources2[k][2];
                
            }

            for( var j in gamedatas.ressources )   
            {
                              
                $('nbre_n_'+j).innerHTML = gamedatas.ressources[j][0];
                $('nbre_p_'+j).innerHTML = gamedatas.ressources[j][1];
                $('nbre_k_'+j).innerHTML = gamedatas.ressources[j][2];
                $('nbre_c_'+j).innerHTML = gamedatas.ressources[j][3];
                
            }

            dojo.place( this.format_block('jstpl_firstplayer', {} ), 'player_board_'+gamedatas.firstplayer);

            if(!this.isSpectator)
            {
            for( var l in gamedatas.ressources3 )   
            {
                                
                $('bignbre_n_'+l).innerHTML = gamedatas.ressources3[l][0];
                $('bignbre_p_'+l).innerHTML = gamedatas.ressources3[l][1];
                $('bignbre_k_'+l).innerHTML = gamedatas.ressources3[l][2];
                $('bignbre_c_'+l).innerHTML = gamedatas.ressources3[l][3];
            }

            for( var m in gamedatas.ressources4 )   
            {
                                
                $('bignbreracine_'+m).innerHTML = gamedatas.ressources4[m][0];
                $('bignbresemi_'+m).innerHTML = gamedatas.ressources4[m][1];
                $('bignbrearbre_'+m).innerHTML = gamedatas.ressources4[m][2];
                
            }
        }

            for( var semi in gamedatas.semi)   
            {
                var variablesemi = gamedatas.semi[semi];
                            
                if( variablesemi.id !== null )
                {   
                    
                    this.addSemi( variablesemi.location, variablesemi.id, gamedatas.color[variablesemi.id]);
                    this.addValeurCarboneSemi(variablesemi.id, variablesemi.location, variablesemi.carbone )
                }
            }

            for( var arbre in gamedatas.arbre)   
            {
                var variablearbre = gamedatas.arbre[arbre];
                            
                if( variablearbre.id !== null )
                {   
                    
                    this.addArbre( variablearbre.location, variablearbre.id, gamedatas.color[variablearbre.id]);
                    
                }
            }

            for( var semineutre in gamedatas.semineutre)   
            {
                var variablesemineutre = gamedatas.semineutre[semineutre];
                            
                if( variablesemineutre.id !== null )
                {   
                    
                    this.addSemi( variablesemineutre.location, variablesemineutre.id, '000000');
                }
            }

            for( var racine in gamedatas.racine)   
            {
                var variableracine = gamedatas.racine[racine];
                            
                if( variableracine.id !== null )
                {   
                    
                    this.addRacine( variableracine.location, variableracine.id, gamedatas.color[variableracine.id], variableracine.sens);
                }
            }


            for( var goal in gamedatas.goals)   
            {
                var variablegoal = gamedatas.goals[goal];
                            
                if( variablegoal.id !== null )
                {   
                    
                    this.addGoal( variablegoal.location, variablegoal.type);
                }
            }


            if (gamedatas.nbreplayers == 2)
            {
                $('scoregoal_1_1').innerHTML = gamedatas.goal_1.goal_1.p1;
                $('scoregoal_2_1').innerHTML = gamedatas.goal_2.goal_2.p1;
                $('scoregoal_3_1').innerHTML = gamedatas.goal_3.goal_3.p1;
                $('scoregoal_1_2').innerHTML = gamedatas.goal_1.goal_1.p2;
                $('scoregoal_2_2').innerHTML = gamedatas.goal_2.goal_2.p2;
                $('scoregoal_3_2').innerHTML = gamedatas.goal_3.goal_3.p2;
                
            }

            if (gamedatas.nbreplayers == 3)
            {
                $('scoregoal_1_1').innerHTML = gamedatas.goal_1.goal_1.p1;
                $('scoregoal_2_1').innerHTML = gamedatas.goal_2.goal_2.p1;
                $('scoregoal_3_1').innerHTML = gamedatas.goal_3.goal_3.p1;
                $('scoregoal_1_2').innerHTML = gamedatas.goal_1.goal_1.p2;
                $('scoregoal_2_2').innerHTML = gamedatas.goal_2.goal_2.p2;
                $('scoregoal_3_2').innerHTML = gamedatas.goal_3.goal_3.p2;
                $('scoregoal_1_3').innerHTML = gamedatas.goal_1.goal_1.p3;
                $('scoregoal_2_3').innerHTML = gamedatas.goal_2.goal_2.p3;
                $('scoregoal_3_3').innerHTML = gamedatas.goal_3.goal_3.p3;
                
            }

            if (gamedatas.nbreplayers == 4)
            {
                $('scoregoal_1_1').innerHTML = gamedatas.goal_1.goal_1.p1;
                $('scoregoal_2_1').innerHTML = gamedatas.goal_2.goal_2.p1;
                $('scoregoal_3_1').innerHTML = gamedatas.goal_3.goal_3.p1;
                $('scoregoal_1_2').innerHTML = gamedatas.goal_1.goal_1.p2;
                $('scoregoal_2_2').innerHTML = gamedatas.goal_2.goal_2.p2;
                $('scoregoal_3_2').innerHTML = gamedatas.goal_3.goal_3.p2;
                $('scoregoal_1_3').innerHTML = gamedatas.goal_1.goal_1.p3;
                $('scoregoal_2_3').innerHTML = gamedatas.goal_2.goal_2.p3;
                $('scoregoal_3_3').innerHTML = gamedatas.goal_3.goal_3.p3;
                $('scoregoal_1_4').innerHTML = gamedatas.goal_1.goal_1.p4;
                $('scoregoal_2_4').innerHTML = gamedatas.goal_2.goal_2.p4;
                $('scoregoal_3_4').innerHTML = gamedatas.goal_3.goal_3.p4;
                
            }
            
           if (gamedatas.champiscore2 == 1)
           {
                this.addChampiScore2(41, 1);
           }

           if (gamedatas.champiscore2 == 2)
           {
                this.addChampiScore2(41, 1);
                this.addChampiScore2(41, 2);
           }

           for (var score in gamedatas.champiscore4)

           {
            var variablescore = gamedatas.champiscore4[score];
            
            if(variablescore.nbre == 1)
            {
                this.addChampiScore4(variablescore.type, 1);
                
            }

            if(variablescore.nbre == 2)
            {
                this.addChampiScore4(variablescore.type, 1);
                this.addChampiScore4(variablescore.type, 2);
                
            }

            if(variablescore.nbre == 3)
            {
                this.addChampiScore4(variablescore.type, 1);
                this.addChampiScore4(variablescore.type, 2);
                this.addChampiScore4(variablescore.type, 3);
            }

            if(variablescore.nbre == 4)
            {
                this.addChampiScore4(variablescore.type, 1);
                this.addChampiScore4(variablescore.type, 2);
                this.addChampiScore4(variablescore.type, 3);
                this.addChampiScore4(variablescore.type, 4);
            }
  

           }

            this.addTooltip( 'starthere', _('At the end of your turn, if your seedlings have absorbed one or more Carbon, move one space up the carbon track'),'' );
            this.addTooltip( 'aide_6', _('At the end of your turn, if your seedlings have absorbed one or more Carbon, move one space up the carbon track'),'' );
            this.addTooltip( 'gameend', _('Game End: Finish round and play one more'),'' );

            var name = _("Explanation for Competitive Tables");
            var descriptiona = _("In the case of a tie, add the points for the tied places together and divide them by the number of players rounded down.");
            var descriptionb = _("Example: If 3 players are tied for 1st, 2nd and 3rd, they have each 5 points [(9+5+3)/3] rounded down.");
            var html = '<div class="anatooltip"><div class="anataide">'+this.format_block('jstpl_aidetool',{name: name, description1: descriptiona, description2: descriptionb})+'</div></div>';
            this.addTooltipHtml( 'aide', html,1000);

            var name1 = _(this.gamedatas.action[1].name);
            var description1 = _(this.gamedatas.action[1].description);
            var html1 = '<div class="anatooltip"><div class="anatactions">'+this.format_block('jstpl_actionstool',{name: name1, description: description1, x: 0})+'</div></div>';
            this.addTooltipHtml( 'aide_1', html1,1000);

            var name2 = _(this.gamedatas.action[2].name);
            var description2 = _(this.gamedatas.action[2].description);
            var html2 = '<div class="anatooltip"><div class="anatactions">'+this.format_block('jstpl_actionstool',{name: name2, description: description2, x: -212})+'</div></div>';
            this.addTooltipHtml( 'aide_2', html2,1000);

            var name3 = _(this.gamedatas.action[3].name);
            var description3 = _(this.gamedatas.action[3].description);
            var html3 = '<div class="anatooltip"><div class="anatactions">'+this.format_block('jstpl_actionstool',{name: name3, description: description3, x: -424})+'</div></div>';
            this.addTooltipHtml( 'aide_3', html3,1000);
           
            var name4 = _(this.gamedatas.action[4].name);
            var description4 = _(this.gamedatas.action[4].description);
            var html4 = '<div class="anatooltip"><div class="anatactions">'+this.format_block('jstpl_actionstool',{name: name4, description: description4, x: -636})+'</div></div>';
            this.addTooltipHtml( 'aide_4', html4,1000);

            var name5 = _(this.gamedatas.action[5].name);
            var description5 = _(this.gamedatas.action[5].description);
            var html5 = '<div class="anatooltip"><div class="anatactions">'+this.format_block('jstpl_actionstool',{name: name5, description: description5, x: -848})+'</div></div>';
            this.addTooltipHtml( 'aide_5', html5,1000);
           

           /////////////////////////////////////// Scorepad////////////////////

           for( var name in gamedatas.name)   
            {
                var variablename = gamedatas.name[name];
                            
                if( variablename.name !== null )
                {   
                    $('name'+name).innerHTML = variablename.name;
                    
                }
            }

            for( var score in gamedatas.score)   
            {
                var variablescore = gamedatas.score[score];
                            
                
                if(score == 'tableaufinalracine')
                {
                    this.addtableaufinalracine(variablescore);
                }
                if(score == 'tableaufinaltotalracine')
                {
                    this.addtableaufinaltotalracine(variablescore);
                }
                if(score == 'tableaufinalgoals')
                {
                    this.addtableautableaufinalgoals(variablescore);
                }
                if(score == 'tableaufinaltotalgoals')
                {
                    this.addtableaufinaltotalgoals(variablescore);
                }

                if(score == 'tableaufinalressources')
                {
                    this.addtableaufinalressources(variablescore);
                }
                if(score == 'tableaufinalbonus')
                {
                    this.addtableaufinalbonus(variablescore);
                }
                if(score == 'tableaufinaltotal')
                {
                    this.addtableaufinaltotal(variablescore);
                }
                
                
            }

                    
            dojo.query("#scorepad").addClass("masque");

            if (gamedatas.messagealerte >=1)
            {
                dojo.query("#messagealerte").removeClass("masque");
            }

            
           

           
           // TODO: Set up your game interface here, according to "gamedatas"
            
 
            // Setup game notifications to handle (see "setupNotifications" method below)
            this.setupNotifications();

            dojo.query(".selecttrack").connect('onclick', this, 'onSelect' );
           




            console.log( "Ending game setup" );
        },
       
/////////////////////////////////////////////////////////////////////////////////   
//         _____ _        _            
//        / ____| |      | |           
//       | (___ | |_ __ _| |_ ___  ___ 
//        \___ \| __/ _` | __/ _ \/ __|
//        ____) | || (_| | ||  __/\__ \
//       |_____/ \__\__,_|\__\___||___/
//                                    
/////////////////////////////////////////////////////////////////////////////////                                        
      

        // onEnteringState: this method is called each time we are entering into a new game state.
        //                  You can use this method to perform some user interface changes at this moment.
        //
        onEnteringState: function( stateName, args )
        {
            console.log( 'Entering state: '+stateName );

            dojo.query(".selectable").removeClass("selectable");
            dojo.query(".selected").removeClass("selected");
            dojo.query(".selectable2").removeClass("selectable2");
            dojo.query(".selected2").removeClass("selected2");
            
            switch( stateName )
            {
            
            case 'playerTurn':
                this.args = args.args;
                
                for( var sid in this.args.selectable)
                    {
                        
                        if(this.isCurrentPlayerActive())
                        {
                        dojo.query("#"+this.args.selectable[sid]).addClass("selectable");
                        }
                    }
                for( var sid in this.args.selected)
                {
                    
                    if(this.isCurrentPlayerActive())
                    {
                        
                    dojo.query("#"+this.args.selected[sid]).addClass("selected");
                    }
                }

                for( var sid in this.args.selectable2)
                {
                    
                    if(this.isCurrentPlayerActive())
                    {
                        
                    dojo.query("#"+this.args.selectable2[sid]).addClass("selectable2");
                    }
                }


                    //this.gamedatas.gamestate.descriptionmyturn = _(this.args.titleyou);
                    //this.gamedatas.gamestate.description = _(this.args.title);
                    //this.updatePageTitle();
                    if( this.isCurrentPlayerActive() )
                    {
                        if(args.args.titleyou != null)
	            	{
	            		$('pagemaintitletext').innerHTML = 	this.format_string_recursive(_(args.args.titleyou).replace('${you}', this.divYou()).replace('#nb#',args.args.nb).replace('#nb2#',args.args.nb2), args.args);   
	    			}
                    } 
                    
                    else{
                        if(args.args.title != null)
                        {
                            $('pagemaintitletext').innerHTML = this.format_string_recursive(_(args.args.title).replace('${actplayer}', this.divActPlayer()).replace('#nb#',args.args.nb), args.args);  
                        }
                    }

                
                break;
    
           
           
            case 'dummmy':
                break;
            }
        },

        // onLeavingState: this method is called each time we are leaving a game state.
        //                 You can use this method to perform some user interface changes at this moment.
        //
        onLeavingState: function( stateName )
        {
            console.log( 'Leaving state: '+stateName );
            
            switch( stateName )
            {
            
            
           
           
            case 'dummmy':
                break;
            }               
        }, 

        // onUpdateActionButtons: in this method you can manage "action buttons" that are displayed in the
        //                        action status bar (ie: the HTML links in the status bar).
        //        
        
        onUpdateActionButtons: function( stateName, args )
        {
            console.log( 'onUpdateActionButtons: '+stateName );
                      
            if( this.isCurrentPlayerActive() )
            {            
                switch( stateName )
                {

                    case "playerTurn":
                        for( var nb in args.buttons )
 		                {        
                            if(args.buttons[nb] == "Cancel")
                            {
                            this.addActionButton( 'cancel', _("Undo Action") ,'onOpCancel', null, null, 'gray' );
                            }
                            if(args.buttons[nb] == "Reproduce")
                            {
                            this.addActionButton( 'Reproduce', _("Reproduce") ,'onOpReproduce', null, null, 'gray' );
                            var name = _(this.gamedatas.action[3].name);
                            var description = _(this.gamedatas.action[3].description);
                            var html = '<div class="anatooltip"><div class="anatactions">'+this.format_block('jstpl_actionstool',{name: name, description: description, x: -424})+'</div></div>';
	                        this.addTooltipHtml( 'Reproduce', html,1000);
                            }
                            if(args.buttons[nb] == "placechampireproduce")
                            {
                            this.addActionButton( 'placechampireproduce', _("Yes") ,'onOpYesPlaceChampiReproduce', null, null, 'gray' );
                            }
                            if(args.buttons[nb] == "noplacechampireproduce")
                            {
                            this.addActionButton( 'noplacechampireproduce', _("No") ,'onOpNoPlaceChampiReproduce', null, null, 'gray' );
                            }
                            if(args.buttons[nb] == "Nobonuschampi")
                            {
                            this.addActionButton( 'Nobonuschampi', _("Pass") ,'onOpNobonuschampi', null, null, 'gray' );
                            }
                            if(args.buttons[nb] == "Cancel2")
                            {
                            this.addActionButton( 'cancel2', _("Cancel") ,'onOpCancel2', null, null, 'gray' );
                            }
                            if(args.buttons[nb] == "Cancel3")
                            {
                            this.addActionButton( 'cancel3', _("Cancel") ,'onOpCancel3', null, null, 'gray' );
                            }
                            if(args.buttons[nb] == "Partner")
                            {
                            this.addActionButton( 'Partner', _("Partner") ,'onOpPartner', null, null, 'gray' );
                            var name = _(this.gamedatas.action[4].name);
                            var description = _(this.gamedatas.action[4].description);
                            var html = '<div class="anatooltip"><div class="anatactions">'+this.format_block('jstpl_actionstool',{name: name, description: description, x: -636})+'</div></div>';
	                        this.addTooltipHtml( 'Partner', html,1000);
                            }
                            if(args.buttons[nb] == "placechampipartner")
                            {
                            this.addActionButton( 'placechampipartner', _("Yes") ,'onOpYesPlaceChampiPartner', null, null, 'gray' );
                            }
                            if(args.buttons[nb] == "noplacechampipartner")
                            {
                            this.addActionButton( 'noplacechampipartner', _("No") ,'onOpNoPlaceChampiPartner', null, null, 'gray' );
                            }
                            if(args.buttons[nb] == "Findetour")
                            {
                            this.addActionButton( 'Findetour', _("Pass") ,'onOpFindetour', null, null, 'gray' );
                            }
                            if(args.buttons[nb] == "Findetour2")
                            {
                            this.addActionButton( 'Findetour2', _("Pass") ,'onOpFindetour2', null, null, 'gray' );
                            }
                            if(args.buttons[nb] == "Photo")
                            {
                            this.addActionButton( 'Photo', _("Photosynthesize") ,'onOpPhoto', null, null, 'gray' );
                            var name = _(this.gamedatas.action[5].name);
                            var description = _(this.gamedatas.action[5].description);
                            var html = '<div class="anatooltip"><div class="anatactions">'+this.format_block('jstpl_actionstool',{name: name, description: description, x: -848})+'</div></div>';
	                        this.addTooltipHtml( 'Photo', html,1000);
                            }
                            if(args.buttons[nb] == "Nophotoechange")
                            {
                            this.addActionButton( 'Nophotoechange', _("Pass") ,'onOpNoPhotoEchange', null, null, 'gray' );
                            }
                            if(args.buttons[nb] == "Yesphotoechange")
                            {
                            this.addActionButton( 'Yesphotoechange', '- <div class="littleicone_n"></div> / + <div class="littleicone_c"></div>' ,'onOpYesPhotoEchange', null, null, 'gray' );
                            var monElement = document.getElementById("Yesphotoechange");
                            monElement.style.marginRight = "20px";
                            }
                            if(args.buttons[nb] == "Nophotoechangesupp")
                            {
                            this.addActionButton( 'Nophotoechangesupp', _("Pass") ,'onOpNoPhotoEchangeSupp', null, null, 'gray' );
                            }
                            if(args.buttons[nb] == "Yesphotoechangesupp")
                            {
                            this.addActionButton( 'Yesphotoechangesupp', '- <div class="littleicone_n"></div> / + <div class="littleicone_c"></div>' ,'onOpYesPhotoEchangeSupp', null, null, 'gray' );
                            var monElement = document.getElementById("Yesphotoechangesupp");
                            monElement.style.marginRight = "20px";
                            }
                            if(args.buttons[nb] == "Validerphotodiscard")
                            {
                            this.addActionButton( 'Validerphotodiscard', _("Validate selection") ,'onOpValiderPhotoDiscard', null, null, 'gray' );
                            }
                            if(args.buttons[nb] == "Activate")
                            {
                            this.addActionButton( 'Activate', _("Activate") ,'onOpActivate', null, null, 'gray' );
                            var name = _(this.gamedatas.action[1].name);
                            var description = _(this.gamedatas.action[1].description);
                            var html = '<div class="anatooltip"><div class="anatactions">'+this.format_block('jstpl_actionstool',{name: name, description: description, x: 0})+'</div></div>';
	                        this.addTooltipHtml( 'Activate', html,1000);
                            }
                            if(args.buttons[nb] == "Absorb")
                            {
                            this.addActionButton( 'Absorb', _("Absorb") ,'onOpAbsorb', null, null, 'gray' );
                            var name = _(this.gamedatas.action[2].name);
                            var description = _(this.gamedatas.action[2].description);
                            var html = '<div class="anatooltip"><div class="anatactions">'+this.format_block('jstpl_actionstool',{name: name, description: description, x: -212})+'</div></div>';
	                        this.addTooltipHtml( 'Absorb', html,1000);
                            }
                            if(args.buttons[nb] == "Activationb")
                            {
                            this.addActionButton( 'Activationb', '<div class="button_activation_b"></div>' ,'onOpActivation', null, null, 'gray' );
                            }
                            if(args.buttons[nb] == "Activationp")
                            {
                            this.addActionButton( 'Activationp', '<div class="button_activation_p"></div>' ,'onOpActivation', null, null, 'gray' );
                            }
                            if(args.buttons[nb] == "Activationg")
                            {
                            this.addActionButton( 'Activationg', '<div class="button_activation_g"></div>' ,'onOpActivation', null, null, 'gray' );
                            }
                            if(args.buttons[nb] == "Activationy")
                            {
                            this.addActionButton( 'Activationy', '<div class="button_activation_y"></div>' ,'onOpActivation', null, null, 'gray' );
                            }
                            if(args.buttons[nb] == "N")
                            {
                            this.addActionButton( 'N', '<div class="icone_n"></div>' ,'onOpRessource', null, null, 'gray' );
                            }
                            if(args.buttons[nb] == "P")
                            {
                            this.addActionButton( 'P', '<div class="icone_p"></div>' ,'onOpRessource', null, null, 'gray' );
                            }
                            if(args.buttons[nb] == "K")
                            {
                            this.addActionButton( 'K', '<div class="icone_k"></div>' ,'onOpRessource', null, null, 'gray' );
                            }
                            if(args.buttons[nb] == "Undo")
                            {
                            this.addActionButton( 'Undo', _("Undo Action") ,'onOpUndo', null, null, 'gray' );
                            }
                            
                            if((args.buttons[nb] == "bonustile_1")||(args.buttons[nb] == "bonustile_2"))
                            {
                            this.addActionButton( args.buttons[nb], '<div class="bonustileN"></div>' ,'onOpBonusTile', null, null, 'gray' );
                            this.addTooltipHtml( 'bonustile_1', _('Bonus to use at the start of your turn to gain 1 Nitrogen',500));
                            this.addTooltipHtml( 'bonustile_2', _('Bonus to use at the start of your turn to gain 1 Nitrogen',500));
                            }
                            if((args.buttons[nb] == "bonustile_3")||(args.buttons[nb] == "bonustile_4"))
                            {
                            this.addActionButton( args.buttons[nb], '<div class="bonustileP"></div>' ,'onOpBonusTile', null, null, 'gray' );
                            this.addTooltipHtml( 'bonustile_3', _('Bonus to use at the start of your turn to gain 1 Phosphorus',500));
                            this.addTooltipHtml( 'bonustile_4', _('Bonus to use at the start of your turn to gain 1 Phosphorus',500));
                            }
                            if((args.buttons[nb] == "bonustile_5")||(args.buttons[nb] == "bonustile_6"))
                            {
                            this.addActionButton( args.buttons[nb], '<div class="bonustileK"></div>' ,'onOpBonusTile', null, null, 'gray' );
                            this.addTooltipHtml( 'bonustile_5', _('Bonus to use at the start of your turn to gain 1 Potassium',500));
                            this.addTooltipHtml( 'bonustile_6', _('Bonus to use at the start of your turn to gain 1 Potassium',500));
                            }
                            if((args.buttons[nb] == "bonustile_7")||(args.buttons[nb] == "bonustile_8"))
                            {
                            this.addActionButton( args.buttons[nb], '<div class="bonustileC"></div>' ,'onOpBonusTile', null, null, 'gray' );
                            this.addTooltipHtml( 'bonustile_7', _('Bonus to use at the start of your turn to gain 1 Carbon',500));
                            this.addTooltipHtml( 'bonustile_8', _('Bonus to use at the start of your turn to gain 1 Carbon',500));
                            }
                            if((args.buttons[nb] == "bonustile_9")||(args.buttons[nb] == "bonustile_10"))
                            {
                            this.addActionButton( args.buttons[nb], '<div class="bonustileR"></div>' ,'onOpBonusTile', null, null, 'gray' );
                            this.addTooltipHtml( 'bonustile_9', _('Bonus to use at the start of your turn to reactivate all tokens',500));
                            this.addTooltipHtml( 'bonustile_10', _('Bonus to use at the start of your turn to reactivate all tokens',500));
                            }
                            if((args.buttons[nb] == "bonustile_11")||(args.buttons[nb] == "bonustile_12")||(args.buttons[nb] == "bonustile_13"))
                            {
                            this.addActionButton( args.buttons[nb], '<div class="bonustileS1"></div>' ,'onOpBonusTile', null, null, 'gray' );
                            this.addTooltipHtml( 'bonustile_11', _('+1 Victory Point at the end of game',500));
                            this.addTooltipHtml( 'bonustile_12', _('+1 Victory Point at the end of game',500));
                            this.addTooltipHtml( 'bonustile_13', _('+1 Victory Point at the end of game',500));
                            }
                            if((args.buttons[nb] == "bonustile_14")||(args.buttons[nb] == "bonustile_15")||(args.buttons[nb] == "bonustile_16"))
                            {
                            this.addActionButton( args.buttons[nb], '<div class="bonustileS2"></div>' ,'onOpBonusTile', null, null, 'gray' );
                            this.addTooltipHtml( 'bonustile_14', _('+2 Victory Points at the end of game',500));
                            this.addTooltipHtml( 'bonustile_15', _('+2 Victory Points at the end of game',500));
                            this.addTooltipHtml( 'bonustile_16', _('+2 Victory Points at the end of game',500));
                            }
                            if(args.buttons[nb] == "Pass")
                            {
                            this.addActionButton( 'Pass', _("Pass") ,'onOpPass', null, null, 'gray' );
                            }
                            if(args.buttons[nb] == "0")
                            {
                            this.addActionButton( '0', "0" ,'onOpNbre', null, null, 'gray' );
                            }
                            if(args.buttons[nb] == "1")
                            {
                            this.addActionButton( '1', "1" ,'onOpNbre', null, null, 'gray' );
                            }
                            if(args.buttons[nb] == "2")
                            {
                            this.addActionButton( '2', "2" ,'onOpNbre', null, null, 'gray' );
                            }
                            if(args.buttons[nb] == "3")
                            {
                            this.addActionButton( '3', "3" ,'onOpNbre', null, null, 'gray' );
                            }
                            if(args.buttons[nb] == "Action")
                            {
                            this.addActionButton( 'Action', _("Action") ,'onOpAction', null, null, 'gray' );
                            }
                            if(args.buttons[nb] == "Findetour3")
                            {
                            this.addActionButton( 'Findetour3', _("Pass") ,'onOpFindetour3', null, null, 'gray' );
                            }
                            if(args.buttons[nb] == "Confirm")
                            {
                            this.addActionButton( 'Confirm', _("Confirm") ,'onOpConfirm', null, null, 'gray' );
                            }

                        
                        
                        }          
                        
                        break;



                }
            }
        },        

/////////////////////////////////////////////////////////////////////////////////         
//   _    _ _   _ _ _ _                          _   _               _     
//  | |  | | | (_) (_) |                        | | | |             | |    
//  | |  | | |_ _| |_| |_ _   _   _ __ ___   ___| |_| |__   ___   __| |___ 
//  | |  | | __| | | | __| | | | | '_ ` _ \ / _ \ __| '_ \ / _ \ / _` / __|
//  | |__| | |_| | | | |_| |_| | | | | | | |  __/ |_| | | | (_) | (_| \__ \
//   \____/ \__|_|_|_|\__|\__, | |_| |_| |_|\___|\__|_| |_|\___/ \__,_|___/
//                         __/ |                                           
//                        |___/                                            
/////////////////////////////////////////////////////////////////////////////////  
 
        divYou : function() {
            
            var color = this.players[this.player_id].color;
            if(color == 'ffffff')
            {
                color = 'aeacac';
            }
            var color_bg = "";
            var you = "<span style=\"font-weight:bold;color:#" + color + ";" + color_bg + "\">" + _("You") + "</span>";
            return you;
        },

        divActPlayer : function() {        	
            var color = this.players[this.getActivePlayerId()].color;
            var name = this.players[this.getActivePlayerId()].name;
            var color_bg = "";
            if(color == 'ffffff')
            {
                color = 'aeacac';
            }
            var you = "<span style=\"font-weight:bold;color:#" + color + ";" + color_bg + "\">" + name + "</span>";
            return you;
        },

        format_string_recursive : function(log, args) {
            try {
                if (log && args && !args.processed) {
                    args.processed = true;

                    
                }
            } catch (e) {
                console.error(log,args,"Exception thrown", e.stack);
            }
            return this.inherited(arguments);
        },
        
        attachToNewParentNoDestroy: function (mobile_in, new_parent_in, relation, place_position) 
        {
    
            const mobile = $(mobile_in);
            const new_parent = $(new_parent_in);

            var src = dojo.position(mobile);
            if (place_position)
                mobile.style.position = place_position;
            dojo.place(mobile, new_parent, relation);
            mobile.offsetTop;//force re-flow
            var tgt = dojo.position(mobile);
            var box = dojo.marginBox(mobile);
            var cbox = dojo.contentBox(mobile);
            var left = box.l + src.x - tgt.x;
            var top = box.t + src.y - tgt.y;

            mobile.style.position = "absolute";
            mobile.style.left = left + "px";
            mobile.style.top = top + "px";
            box.l += box.w - cbox.w;
            box.t += box.h - cbox.h;
            mobile.offsetTop;//force re-flow
            return box;
        },

        
        addChampi: function( id, location, type, carbone)  
        {
            this.addSquare(location);
            this.addCircle(location);
            this.addMiniSquare(location);
            
            if(type<=15)       
            {
            dojo.place( this.format_block( 'jstpl_champi', {
                id: id,
                x: (type-1)*(-100),
                y: 0,
                                        
            } ) , location );
            }

            if((type>=16)&&(type<=30))       
            {
            dojo.place( this.format_block( 'jstpl_champi', {
                id: id,
                x: (type-16)*(-100),
                y: -100,
                                        
            } ) , location );
            }

            if((type>=31)&&(type<=45))       
            {
            dojo.place( this.format_block( 'jstpl_champi', {
                id: id,
                x: (type-31)*(-100),
                y: -200,
                                        
            } ) , location );
            }

            if(type>=46)       
            {
            dojo.place( this.format_block( 'jstpl_champi', {
                id: id,
                x: (type-46)*(-100),
                y: -300,
                                        
            } ) , location );
            }
            
            dojo.query("#champi_"+id).connect('onclick', this, 'onSelect' );
            var name = _(this.gamedatas.listechampi[type].name);
            var description = _(this.gamedatas.listechampi[type].description);
            var html = '<div class="anatooltip"><div class="anatchampi">'+this.format_block('jstpl_champitool',{name: name, description: description, x: (type-1)*(-250)})+'</div></div>';
            this.addTooltipHtml( 'champi_'+id, html,1000);
          
            setTimeout(() => 
            {
            this.addValeurCarboneChampi(id, location, type, carbone);
            }, "700");

            if (type == 41)
            {
            this.addScoreSquare2(id, type);
            }

            if ((type >= 42) && (type <= 44))
            {
            this.addScoreSquare4(id, type);
            }


        },

        addValeurCarboneChampi: function( id, location, type, carbone )  
        {
            if ((type != 1)&&(type != 28)&&(type != 29)&&(type != 30)&&(type != 31)&&(type != 32)&&(type != 19)&&(type != 20)&&(type != 21)&&(type != 22)&&(type != 45)&&(type != 46)&&(type != 47)&&(type != 48)&&(type != 49))
            {
            dojo.place( this.format_block( 'jstpl_carbonechampi', {
                id: id,
                
                                        
            } ) , location );

            $('valeurcarbonechampi_'+id).innerHTML = carbone;

            }
        },



        addSquare: function(location)
        {
            
            var tableau = location.split("_");
            for (var i=-1; i<=1; i++)
            {
                
                    var newx = parseInt(tableau[1], 10) + i;
                    var newy = parseInt(tableau[2], 10);
                    var element = document.getElementById('square_'+newx+'_'+newy);
                    if (element === null)
                    {
                        dojo.place( this.format_block( 'jstpl_square', {
                            x: newx,
                            y: newy,
                            left: -70 + (newx*140),
                            top: -70 + (newy*+140),
                                                    
                        } ) , map_scrollable_oversurface );
                        dojo.query("#square_"+newx+"_"+newy).connect('onclick', this, 'onSelect' );
                    }
                
            }

            for (var j=-1; j<=1; j++)
            {
                
                    var newx = parseInt(tableau[1], 10);
                    var newy = parseInt(tableau[2], 10) + j;
                    var element = document.getElementById('square_'+newx+'_'+newy);
                    if (element === null)
                    {
                        dojo.place( this.format_block( 'jstpl_square', {
                            x: newx,
                            y: newy,
                            left: -70 + (newx*140),
                            top: -70 + (newy*140),
                                                    
                        } ) , map_scrollable_oversurface );
                        dojo.query("#square_"+newx+"_"+newy).connect('onclick', this, 'onSelect' );
                    }
                
            }
        },

        addCircle: function(location)
        {
            var tableau = location.split("_");
            for (var i=0; i<=1; i++)
            {   
                for (var j=0; j<=1; j++)
                {
                
                    var newx = parseInt(tableau[1], 10) + i;
                    var newy = parseInt(tableau[2], 10) + j;
                    var element = document.getElementById('circle_'+newx+'_'+newy);
                    if (element === null)
                    {
                        dojo.place( this.format_block( 'jstpl_circle', {
                            x: newx,
                            y: newy,
                            left: -84 + (newx*140),
                            top: -84 + (newy*140),
                                                    
                        } ) , map_scrollable_oversurface );
                        dojo.query("#circle_"+newx+"_"+newy).connect('onclick', this, 'onSelect' );
                    }
                }
                
            }

        },


        addMiniSquare: function(location)
        {
            var tableau = location.split("_");
            for (var i=0; i<=1; i++)
            {   
                for (var j=0; j<=1; j++)
                {
                
                    var newx = parseInt(tableau[1], 10) + i;
                    var newy = parseInt(tableau[2], 10) + j;
                    var element = document.getElementById('minisquare_'+tableau[1]+'_'+tableau[2]+'_'+newx+'_'+newy);
                    if (element === null)
                    {
                        dojo.place( this.format_block( 'jstpl_minisquare', {
                            x1: parseInt(tableau[1], 10),
                            y1: parseInt(tableau[2], 10),
                            x2: newx,
                            y2: newy,
                            left: (100*i),
                            top: (100*j),                                                 
                        } ) , location);
                        dojo.query("#minisquare_"+tableau[1]+"_"+tableau[2]+"_"+newx+"_"+newy).connect('onclick', this, 'onSelect' );
                    }
                }
                
            }

        },

        
        addChampiHand: function(id, location, location_arg, type)
        {   
            var tableau = location.split("_");
            var player = parseInt(tableau[1], 10);
            if (player == this.getCurrentPlayerId())
            {
            
           
            if(type<=15)       
            {
            dojo.place( this.format_block( 'jstpl_champi', {
                id: id,
                x: (type-1)*(-100),
                y: 0,
                                        
            } ) , location+'_'+location_arg);
            }

            if((type>=16)&&(type<=30))      
            {
            dojo.place( this.format_block( 'jstpl_champi', {
                id: id,
                x: (type-16)*(-100),
                y: -100,
                                        
            } ) , location+'_'+location_arg);
            }

            if((type>=31)&&(type<=45))      
            {
            dojo.place( this.format_block( 'jstpl_champi', {
                id: id,
                x: (type-31)*(-100),
                y: -200,
                                        
            } ) , location+'_'+location_arg);
            }

            if(type>=46)       
            {
            dojo.place( this.format_block( 'jstpl_champi', {
                id: id,
                x: (type-46)*(-100),
                y: -300,
                                        
            } ) , location+'_'+location_arg);
            }

            dojo.query("#champi_"+id).connect('onclick', this, 'onSelect' );

            var name = _(this.gamedatas.listechampi[type].name);
            var description = _(this.gamedatas.listechampi[type].description);
            var html = '<div class="anatooltip"><div class="anatchampi">'+this.format_block('jstpl_champitool',{name: name, description: description, x: (type-1)*(-250)})+'</div></div>';
            this.addTooltipHtml( 'champi_'+id, html,1000);

            if (type == 41)
            {
            this.addScoreSquare2(id, type);
            }

            if ((type >= 42) && (type <= 44))
            {
            this.addScoreSquare4(id, type);
            }

            }

        },

        addScoreSquare2: function(id, type)

        {
            dojo.place( this.format_block( 'jstpl_squarescore2', {
                type: type,
                pos: 1,
                                                                
            } ) , 'champi_'+id);

            dojo.place( this.format_block( 'jstpl_squarescore2', {
                type: type,
                pos: 2,
                                                                
            } ) , 'champi_'+id);
            
        },

        addScoreSquare4: function(id, type)

        {
            dojo.place( this.format_block( 'jstpl_squarescore4', {
                type: type,
                pos: 1,
                
                                                                
            } ) , 'champi_'+id);

            dojo.place( this.format_block( 'jstpl_squarescore4', {
                type: type,
                pos: 2,
               
                                                                
            } ) , 'champi_'+id);

            dojo.place( this.format_block( 'jstpl_squarescore4', {
                type: type,
                pos: 3,
                
                                                                
            } ) , 'champi_'+id);

            dojo.place( this.format_block( 'jstpl_squarescore4', {
                type: type,
                pos: 4,
                
                                                                
            } ) , 'champi_'+id);
            
        },

        addSemi: function(location, player, color)

        {
            
            var tableau = location.split("_");
            var x = parseInt(tableau[1], 10);
            var y = parseInt(tableau[2], 10);
            dojo.place( this.format_block( 'jstpl_semi', {
                x: x,
                y: y,
                color: color,
                id: player,
                                        
            } ) , location );
            
            dojo.query("#semi_"+x+"_"+y+"_"+player).connect('onclick', this, 'onSelect' );

        },

        addArbre: function(location, player, color)

        {
            
            var tableau = location.split("_");
            var x = parseInt(tableau[1], 10);
            var y = parseInt(tableau[2], 10);
            dojo.place( this.format_block( 'jstpl_arbre', {
                x: x,
                y: y,
                color: color,
                id: player,
                                        
            } ) , location );
            
            dojo.query("#arbre_"+x+"_"+y+"_"+player).connect('onclick', this, 'onSelect' );

        },

        addValeurCarboneSemi: function( player, location, carbone )  
        {
            var tableau = location.split("_");
            var x = parseInt(tableau[1], 10);
            var y = parseInt(tableau[2], 10);
            dojo.place( this.format_block( 'jstpl_carbonesemi', {
                id: player,
                x: x,
                y:y,
                
                                        
            } ) , location );

            $('valeurcarbonesemi_'+x+'_'+y+'_'+player).innerHTML = carbone;

            
        },

        addRacine: function(location, player, color, sens)

        {
            
            var tableau = location.split("_");
            var x1 = parseInt(tableau[1], 10);
            var y1 = parseInt(tableau[2], 10);
            var x2 = parseInt(tableau[3], 10);
            var y2 = parseInt(tableau[4], 10);
            dojo.place( this.format_block( 'jstpl_racine', {
                x1: x1,
                y1: y1,
                x2: x2,
                y2: y2,
                color: color,
                id: player,
                sens: sens,
                                        
            } ) , location );
            
            dojo.query("#racine_"+x1+"_"+y1+"_"+x2+"_"+y2+"_"+player).connect('onclick', this, 'onSelect' );



        },

        addMarqueur: function(id, track, color)
        {   
            
            dojo.place( this.format_block( 'jstpl_marqueur', {
                id: id,
                color: color,
                                                                
            } ) , 'track'+track);

        },

        addGoal: function(location, type)
        {   
            
            dojo.place( this.format_block( 'jstpl_goal', {
                pos: location,
                x: (type-1)*(-240),
                                                                
            } ) , 'goals_'+location);

            if(type<11)
            {
            var description = _(this.gamedatas.finalgoal[type].description);
            var html = '<div class="anatooltip"><div class="anatgoal">'+this.format_block('jstpl_goaltool',{description: description, x: (type-1)*(-360)})+'</div></div>';
            this.addTooltipHtml( 'goals_'+location, html,1000);
            }
            if(type==11)
            {

                var description = _(this.gamedatas.finalgoal[type].description);
                var html = '<div class="anatooltip"><div class="anatgoal">'+this.format_block('jstpl_goaltool',{description: description, x: (type-1)*(-360)})+'<div class="aidegoaltool1"></div></div>';
                this.addTooltipHtml( 'goals_'+location, html,1000);

            }
            if (type==12)
            {
                var description = _(this.gamedatas.finalgoal[type].description);
                var html = '<div class="anatooltip"><div class="anatgoal">'+this.format_block('jstpl_goaltool',{description: description, x: (type-1)*(-360)})+'<div class="aidegoaltool2"></div></div>';
                this.addTooltipHtml( 'goals_'+location, html,1000);
            }

        },

        addTileHand: function(type, player, location)
        {   
            if (player == this.getCurrentPlayerId())
            {
            dojo.place( this.format_block( 'jstpl_tile', {
                id: player,
                pos: location,
                x: (type-1)*(-40),
                                                                
            } ) , 'tiles_'+player+'_'+location);

            dojo.query("#tilehand_"+player+"_"+location).connect('onclick', this, 'onSelect' );
            }
        },


        addChampiScore2: function(type, pos)
        {
            dojo.place( this.format_block( 'jstpl_champiscore2', {
                type: type,
                pos: pos,
                                                                
            } ) , 'squarescore2_'+type+'_'+pos);

        },

        addChampiScore4: function(type, pos)
        {
            dojo.place( this.format_block( 'jstpl_champiscore4', {
                type: type,
                pos: pos,
                                                                
            } ) , 'squarescore4_'+type+'_'+pos);

        },


        addtableaufinalracine: function (tableau)
        {
            for (var index in tableau)
                {
                    for (var index2 in tableau[index])
                    {
                        
                        
                        var index3 = parseInt(index2)+1;
                        $('padplant'+index3+'_'+index).innerHTML = tableau[index][index2];
                        
                    }

                    
                }
        },

        addtableaufinaltotalracine: function (tableau)
        {
            for (var index in tableau)
                {
                    $('padtotalplant_'+index).innerHTML = tableau[index];
                    
                    
                }
        },

        addtableautableaufinalgoals: function (tableau)
        {
            for (var index in tableau)
                {
                    for (var index2 in tableau[index])
                    {
                        
                        
                        var index3 = parseInt(index2)+1;
                        $('padgoal'+index3+'_'+index).innerHTML = tableau[index][index2];
                        
                    }

                    
                }
        },

        addtableaufinaltotalgoals: function (tableau)
        {
            
            for (var index in tableau)
                {
                    
                    $('padtotalgoal_'+index).innerHTML = tableau[index];
                    

                    
                }
        },

        addtableaufinalressources: function (tableau)
        {
            
            for (var index in tableau)
                {
                    
                    $('padressources_'+index).innerHTML = tableau[index];
                    

                    
                }
        },

        addtableaufinalbonus: function (tableau)
        {
            
            for (var index in tableau)
                {
                    
                    $('padbonus_'+index).innerHTML = tableau[index];
                    

                    
                }
        },

        addtableaufinaltotal: function (tableau)
        {
            
            for (var index in tableau)
                {
                    var index2 = parseInt(index)+1;
                    $('padtotal_'+index2).innerHTML = tableau[index];
                    
                    
                    
                }
        },








/////////////////////////////////////////////////////////////////////////////////  
//         _____  _                       _                  _   _             
//        |  __ \| |                     ( )                | | (_)            
//        | |__) | | __ _ _   _  ___ _ __|/ ___    __ _  ___| |_ _  ___  _ __  
//        |  ___/| |/ _` | | | |/ _ \ '__| / __|  / _` |/ __| __| |/ _ \| '_ \ 
//        | |    | | (_| | |_| |  __/ |    \__ \ | (_| | (__| |_| | (_) | | | |
//        |_|    |_|\__,_|\__, |\___|_|    |___/  \__,_|\___|\__|_|\___/|_| |_|
//                         __/ |                                               
//                        |___/                                                
/////////////////////////////////////////////////////////////////////////////////  

        onZoomButton: function(deltaZoom) {
            zoom = this.trl_zoom + deltaZoom;
            this.trl_zoom = zoom <= 0.6 ? 0.6 : zoom >= 1.6? 1.6 : zoom;  // zoom >= 1.4? 1.4 : zoom;
            dojo.style($('map_scrollable'), 'transform', 'scale(' + this.trl_zoom + ')');
            dojo.style($('map_scrollable_oversurface'), 'transform', 'scale(' + this.trl_zoom + ')');
        },

        onZoomCenter: function() {
            this.scrollmap.scrollto(0, 0)
        },
        

        
        onSelect: function(evt)
            {        	 
                // Preventing default browser reaction
                 dojo.stopEvent( evt );
    
                
                 
                if( !this.isCurrentPlayerActive() || (!(evt.currentTarget.classList.contains('selectable')) && !(evt.currentTarget.classList.contains('selectable2')) && !(evt.currentTarget.classList.contains('selected2'))))
                {   
                    return; 
                }
                
                if(this.isCurrentPlayerActive() && evt.currentTarget.classList.contains('selectable') && !(evt.currentTarget.classList.contains('selectable2')) && !(evt.currentTarget.classList.contains('selected2')) && this.checkAction( "actSelect" ))
                {
                    
                    this.ajaxcall( "/undergrove/undergrove/actSelect.html", { 
                        lock: true,
                        arg1: evt.currentTarget.id
                        
                     }, 
                     this, function( result ) {}, function( is_error) {} );
                }

                else if(this.isCurrentPlayerActive() && !(evt.currentTarget.classList.contains('selectable')) && evt.currentTarget.classList.contains('selectable2') && !(evt.currentTarget.classList.contains('selected2')) && this.checkAction( "actSelect" ))
                {
                    
                    dojo.query("#"+evt.currentTarget.id).addClass("selected2");
                    
                                      
                   
                }

                else if(this.isCurrentPlayerActive() && !(evt.currentTarget.classList.contains('selectable')) && evt.currentTarget.classList.contains('selectable2') && evt.currentTarget.classList.contains('selected2') && this.checkAction( "actSelect" ))
                {
                    
                    dojo.query("#"+evt.currentTarget.id).removeClass("selected2");
                    
                     
                   
                }


                


            },


        onOpCancel: function(evt)
        {
            this.ajaxcall( "/undergrove/undergrove/actCancel.html", { 
                lock: true,
                            
                }, 
                this, function( result ) {}, function( is_error) {} );

        },

        
        onOpReproduce: function(evt)
        {
            this.ajaxcall( "/undergrove/undergrove/actReproduce.html", { 
                lock: true,
                            
                }, 
                this, function( result ) {}, function( is_error) {} );

        },

        onOpYesPlaceChampiReproduce: function(evt)
        {
            this.ajaxcall( "/undergrove/undergrove/actYesPlaceChampiReproduce.html", { 
                lock: true,
                            
                }, 
                this, function( result ) {}, function( is_error) {} );

        },

        onOpNoPlaceChampiReproduce: function(evt)
        {
            this.ajaxcall( "/undergrove/undergrove/actNoPlaceChampiReproduce.html", { 
                lock: true,
                            
                }, 
                this, function( result ) {}, function( is_error) {} );

        },

        
        
        onOpNobonuschampi: function(evt)
        {
            this.ajaxcall( "/undergrove/undergrove/actNobonuschampi.html", { 
                lock: true,
                arg1: evt.currentTarget.id
                            
                }, 
                this, function( result ) {}, function( is_error) {} );

        },

       

        onOpCancel2: function(evt)
        {
            this.ajaxcall( "/undergrove/undergrove/actCancel2.html", { 
                lock: true,
                            
                }, 
                this, function( result ) {}, function( is_error) {} );

        },

        onOpCancel3: function(evt)
        {
            this.ajaxcall( "/undergrove/undergrove/actCancel3.html", { 
                lock: true,
                            
                }, 
                this, function( result ) {}, function( is_error) {} );

        },

        onOpPartner: function(evt)
        {
            this.ajaxcall( "/undergrove/undergrove/actPartner.html", { 
                lock: true,
                            
                }, 
                this, function( result ) {}, function( is_error) {} );

        },

        onOpYesPlaceChampiPartner: function(evt)
        {
            this.ajaxcall( "/undergrove/undergrove/actYesPlaceChampiPartner.html", { 
                lock: true,
                            
                }, 
                this, function( result ) {}, function( is_error) {} );

        },

        onOpNoPlaceChampiPartner: function(evt)
        {
            this.ajaxcall( "/undergrove/undergrove/actNoPlaceChampiPartner.html", { 
                lock: true,
                            
                }, 
                this, function( result ) {}, function( is_error) {} );

        },

        onOpFindetour: function(evt)
        {
            this.ajaxcall( "/undergrove/undergrove/actFindetour.html", { 
                lock: true,
                            
                }, 
                this, function( result ) {}, function( is_error) {} );

        },

        onOpFindetour2: function(evt)
        {
            this.ajaxcall( "/undergrove/undergrove/actFindetour2.html", { 
                lock: true,
                            
                }, 
                this, function( result ) {}, function( is_error) {} );

        },

        onOpPhoto: function(evt)
        {
            this.ajaxcall( "/undergrove/undergrove/actPhoto.html", { 
                lock: true,
                            
                }, 
                this, function( result ) {}, function( is_error) {} );

        },

        onOpNoPhotoEchange: function(evt)
        {
            this.ajaxcall( "/undergrove/undergrove/actNoPhotoEchange.html", { 
                lock: true,
                            
                }, 
                this, function( result ) {}, function( is_error) {} );

        },

        onOpYesPhotoEchange: function(evt)
        {
            this.ajaxcall( "/undergrove/undergrove/actYesPhotoEchange.html", { 
                lock: true,
                arg1: evt.currentTarget.id
                            
                }, 
                this, function( result ) {}, function( is_error) {} );

        },

        onOpNoPhotoEchangeSupp: function(evt)
        {
            this.ajaxcall( "/undergrove/undergrove/actNoPhotoEchangeSupp.html", { 
                lock: true,
                            
                }, 
                this, function( result ) {}, function( is_error) {} );

        },

        onOpYesPhotoEchangeSupp: function(evt)
        {
            this.ajaxcall( "/undergrove/undergrove/actYesPhotoEchangeSupp.html", { 
                lock: true,
                arg1: evt.currentTarget.id
                            
                }, 
                this, function( result ) {}, function( is_error) {} );

        },

        onOpValiderPhotoDiscard: function(evt)
        {

            
        // Sélectionnez tous les éléments avec la classe spécifiée
        const elementsAvecClasse = document.querySelectorAll(".selected2");

        // Convertissez la NodeList en un tableau et extrayez les IDs
        const ids = Array.from(elementsAvecClasse, element => element.id);

        const nombreElements = ids.length;

        if (nombreElements == 0)
        {
            var a = 0;
            var b = 0;
            var c = 0;
        }

        if (nombreElements == 1)
        {
            var a = ids[0];
            var b = 0;
            var c = 0;
        }

        if (nombreElements == 2)
        {
            var a = ids[0];
            var b = ids[1];
            var c = 0;
        }

        if (nombreElements == 3)
        {
            var a = ids[0];
            var b = ids[1];
            var c = ids[2];
        }

        
        this.ajaxcall( "/undergrove/undergrove/actValiderPhotoDiscard.html", { 
                lock: true,
                arg1: a,
                arg2: b,
                arg3: c
                            
                }, 
                this, function( result ) {}, function( is_error) {} );

        },

        onOpActivate: function(evt)
        {
            this.ajaxcall( "/undergrove/undergrove/actActivate.html", { 
                lock: true,
                            
                }, 
                this, function( result ) {}, function( is_error) {} );

        },

        onOpAbsorb: function(evt)
        {
            this.ajaxcall( "/undergrove/undergrove/actAbsorb.html", { 
                lock: true,
                            
                }, 
                this, function( result ) {}, function( is_error) {} );

        },

        onOpActivation: function(evt)
        {
            this.ajaxcall( "/undergrove/undergrove/actActivation.html", { 
                lock: true,
                arg1: evt.currentTarget.id
                            
                }, 
                this, function( result ) {}, function( is_error) {} );

        },

        onOpRessource: function(evt)
        {
            this.ajaxcall( "/undergrove/undergrove/actRessource.html", { 
                lock: true,
                arg1: evt.currentTarget.id
                            
                }, 
                this, function( result ) {}, function( is_error) {} );

        },

        onOpFindetour3: function(evt)
        {
            this.ajaxcall( "/undergrove/undergrove/actFindetour3.html", { 
                lock: true,
                arg1: evt.currentTarget.id
                            
                }, 
                this, function( result ) {}, function( is_error) {} );

        },

        onOpUndo: function(evt)
        {
            this.ajaxcall( "/undergrove/undergrove/actUndo.html", { 
                lock: true,
                
                            
                }, 
                this, function( result ) {}, function( is_error) {} );

        },

        onOpBonusTile: function(evt)
        {
            this.ajaxcall( "/undergrove/undergrove/actBonusTile.html", { 
                lock: true,
                arg1: evt.currentTarget.id
                
                            
                }, 
                this, function( result ) {}, function( is_error) {} );

        },

        onOpPass: function(evt)
        {
            this.ajaxcall( "/undergrove/undergrove/actPass.html", { 
                lock: true,
                arg1: evt.currentTarget.id
                            
                }, 
                this, function( result ) {}, function( is_error) {} );

        },

        onOpNbre: function(evt)
        {
            this.ajaxcall( "/undergrove/undergrove/actNbre.html", { 
                lock: true,
                arg1: evt.currentTarget.id
                            
                }, 
                this, function( result ) {}, function( is_error) {} );

        },

        onOpAction: function(evt)
        {
            this.ajaxcall( "/undergrove/undergrove/actAction.html", { 
                lock: true,
                arg1: evt.currentTarget.id
                            
                }, 
                this, function( result ) {}, function( is_error) {} );

        },

        onOpConfirm: function(evt)
        {
            this.ajaxcall( "/undergrove/undergrove/actConfirm.html", { 
                lock: true,
                
                            
                }, 
                this, function( result ) {}, function( is_error) {} );

        },

        
        
        
        
///////////////////////////////////////////////////////////////////////////////// 
//       _   _       _   _  __ _           _   _                 
//      | \ | |     | | (_)/ _(_)         | | (_)                
//      |  \| | ___ | |_ _| |_ _  ___ __ _| |_ _  ___  _ __  ___ 
//      | . ` |/ _ \| __| |  _| |/ __/ _` | __| |/ _ \| '_ \/ __|
//      | |\  | (_) | |_| | | | | (_| (_| | |_| | (_) | | | \__ \
//      |_| \_|\___/ \__|_|_| |_|\___\__,_|\__|_|\___/|_| |_|___/
//                                                                 
/////////////////////////////////////////////////////////////////////////////////                                                                  
       


        setupNotifications: function()
        {
            console.log( 'notifications subscriptions setup' );
            
            dojo.subscribe( 'move', this, "notif_move" );
            dojo.subscribe( 'hand', this, "notif_hand" );
            dojo.subscribe( 'placeinitsemi', this, "notif_placeinitsemi" );
            dojo.subscribe( 'placeinitracine', this, "notif_placeinitracine" );
            dojo.subscribe( 'majresssources', this, "notif_majresssources" );
            dojo.subscribe( 'placesemi', this, "notif_placesemi" );
            dojo.subscribe( 'placesemineutre', this, "notif_placesemineutre" );
            dojo.subscribe( 'placeracine', this, "notif_placeracine" );
            dojo.subscribe( 'discard', this, "notif_discard" );
            dojo.subscribe( 'movecarbone', this, "notif_movecarbone" );
            dojo.subscribe( 'placearbre', this, "notif_placearbre" );
            dojo.subscribe( 'powerearthlover', this, "notif_powerearthlover" );
            dojo.subscribe( 'carbontrack', this, "notif_carbontrack" );
            dojo.subscribe( 'actumarqueur', this, "notif_actumarqueur" );
            dojo.subscribe( 'handtile', this, "notif_handtile" );
            dojo.subscribe( 'usebonustile', this, "notif_usebonustile" );
            dojo.subscribe( 'scoregoal', this, "notif_scoregoal" );
            dojo.subscribe( 'champiscore2', this, "notif_champiscore2" );
            dojo.subscribe( 'champiscore4', this, "notif_champiscore4" );
            dojo.subscribe( 'padracine', this, "notif_padracine" );
            dojo.subscribe( 'padbonus', this, "notif_padbonus" );
            dojo.subscribe( 'padressources', this, "notif_padressources");
            dojo.subscribe( 'padgoals', this, "notif_padgoals");
            dojo.subscribe( 'padtotal', this, "notif_padtotal");
            dojo.subscribe( 'scoregoal4', this, "notif_scoregoal4");
            dojo.subscribe( 'affichagescore', this, "notif_affichagescore");
            dojo.subscribe( 'messagealerte', this, "notif_messagealerte");
            dojo.subscribe( 'finmessagealerte', this, "notif_finmessagealerte");
            dojo.subscribe( 'newscore', this, "notif_newscore");
            

            this.notifqueue.setSynchronous( 'placeinitsemi', 800 );
            this.notifqueue.setSynchronous( 'placeinitracine', 800 );
            
            
            
            

        },  

        notif_newscore: function( notif )
            {
                                            
                
                for( var player_id in notif.args.newscore )
                {
                    var newScore = notif.args.newscore[ player_id ];
                    this.scoreCtrl[ player_id ].toValue( newScore );
                }



            },
        
        notif_affichagescore: function( notif )
        {
            
            dojo.query("#playerhand").addClass("masque");
            dojo.query("#scorepad").removeClass("masque");
            var monElement = document.getElementById("playertiles");
            monElement.style.right = "180px";
            
              
                
            
        },

        
        notif_messagealerte: function( notif )
        {
            
            dojo.query("#messagealerte").removeClass("masque");
            
              
                
            
        },

        notif_finmessagealerte: function( notif )
        {
            
            dojo.destroy('messagealerte');
            
              
                
            
        },

        notif_usebonustile: function( notif )
        {
            
            
                dojo.destroy(notif.args.id);
                
           
        },
        
        notif_actumarqueur: function( notif )
        {
            
            /*for (var player in notif.args.track)
            {
                dojo.destroy('marqueur_' + player);
                
                
                
            }*/
        

            setTimeout(() =>
            {
                for (var player in notif.args.track)
                {
                    
                    this['track' + notif.args.track[player][0]].removeAll();
                    
                }
            }, "1000")

            setTimeout(() =>
                {
                    for (var player in notif.args.track)
                    {
                        
                        this.addMarqueur (player, notif.args.track[player][0], notif.args.track[player][1]);
                        this['track' + notif.args.track[player][0]].placeInZone('marqueur_' + player);
                        
                    }
            }, "2000")

           
       
       
        },

        

        notif_move: function( notif )
            {
                if (this.isCurrentPlayerActive()) 
                {
                var player = this.getActivePlayerId();
                //this.attachToNewParentNoDestroy( notif.args.mobile, notif.args.parent );
                //this.slideToObject( notif.args.mobile, notif.args.parent ).play();

                const mobile = document.getElementById(notif.args.mobile);
                const target = document.getElementById(notif.args.parent);
                mobile.style.left = (mobile.offsetLeft - target.offsetLeft) + "px";
                mobile.style.top = (mobile.offsetTop - target.offsetTop) + "px";
                dojo.place(mobile, target);
                mobile.offsetTop;//force re-flow
                mobile.style.left = "0px";
                mobile.style.top = "0px";


                this.addSquare(notif.args.parent);
                this.addCircle(notif.args.parent);
                this.addMiniSquare(notif.args.parent);
                setTimeout(() => 
                {
                this.addValeurCarboneChampi(notif.args.id, notif.args.parent, notif.args.type, notif.args.carbone);
                }, "700");
                }

                if (!this.isCurrentPlayerActive()) 
                {
                
                var player = this.getActivePlayerId();
                this.addChampi( notif.args.id, notif.args.parent, notif.args.type, notif.args.carbone );
                /*this.placeOnObject( notif.args.mobile, 'overall_player_board_'+player );
                this.slideToObject( notif.args.mobile, notif.args.parent ).play();*/

                }
  
                   
            },

        notif_hand: function( notif )
        {
            if (this.isCurrentPlayerActive()) 
            {
            var player = this.getActivePlayerId();
            this.addChampiHand(notif.args.id, notif.args.location, notif.args.position, notif.args.type);
            this.placeOnObject( 'champi_'+notif.args.id, 'overall_player_board_'+player );
            this.slideToObject( 'champi_'+notif.args.id, notif.args.location+'_'+notif.args.position ).play();
            
            }
                
        },


            

        notif_discard: function( notif )
        {
            if (this.isCurrentPlayerActive()) 
            {
            var player = this.getActivePlayerId();
            dojo.destroy(notif.args.mobile);
            this.addChampiHand(notif.args.newid, notif.args.origine, notif.args.position, notif.args.type);
            this.placeOnObject( 'champi_'+notif.args.newid, 'overall_player_board_'+player );
            this.slideToObject( 'champi_'+notif.args.newid, notif.args.origine+'_'+notif.args.position ).play();
            }
    
        },

        notif_carbontrack: function( notif )
        {
            var player = this.getActivePlayerId();
            this['track' + notif.args.track].placeInZone('marqueur_' + player);
            
    
        },


        notif_majresssources: function( notif )
        {
            var currentplayer = this.getCurrentPlayerId();
            for( var player_id in notif.args.ressources )
                {

                    

                    $('nbre_n_'+player_id).innerHTML = notif.args.ressources[player_id]['azote'];
                    $('nbre_p_'+player_id).innerHTML = notif.args.ressources[player_id]['phosphore'];
                    $('nbre_k_'+player_id).innerHTML = notif.args.ressources[player_id]['potassium'];
                    $('nbre_c_'+player_id).innerHTML = notif.args.ressources[player_id]['carbone'];

                    if(!this.isSpectator)
                    {
                    $('bignbre_n_'+currentplayer).innerHTML = notif.args.ressources[currentplayer]['azote'];
                    $('bignbre_p_'+currentplayer).innerHTML = notif.args.ressources[currentplayer]['phosphore'];
                    $('bignbre_k_'+currentplayer).innerHTML = notif.args.ressources[currentplayer]['potassium'];
                    $('bignbre_c_'+currentplayer).innerHTML = notif.args.ressources[currentplayer]['carbone'];
                    }

                    $('nbreracine_'+player_id).innerHTML = notif.args.ressources[player_id]['racine'];
                    $('nbresemi_'+player_id).innerHTML = notif.args.ressources[player_id]['semi'];
                    $('nbrearbre_'+player_id).innerHTML = notif.args.ressources[player_id]['arbre'];

                    if(!this.isSpectator)
                    {
                    $('bignbreracine_'+currentplayer).innerHTML = notif.args.ressources[currentplayer]['racine'];
                    $('bignbresemi_'+currentplayer).innerHTML = notif.args.ressources[currentplayer]['semi'];
                    $('bignbrearbre_'+currentplayer).innerHTML = notif.args.ressources[currentplayer]['arbre'];
                    }

                    if(notif.args.ressources[player_id]['activation_b'] == 1)
                    {
                    dojo.query("#icone_activation_b_"+player_id).removeClass("used");
                    dojo.query("#bigicone_activation_b_"+player_id).removeClass("bigused");
                    }
                    if(notif.args.ressources[player_id]['activation_b'] == 0)
                    {
                    dojo.query("#icone_activation_b_"+player_id).addClass("used");
                    dojo.query("#bigicone_activation_b_"+player_id).addClass("bigused");
                    }

                    if(notif.args.ressources[player_id]['activation_p'] == 1)
                    {
                    dojo.query("#icone_activation_p_"+player_id).removeClass("used");
                    dojo.query("#bigicone_activation_p_"+player_id).removeClass("bigused");
                    }
                    if(notif.args.ressources[player_id]['activation_p'] == 0)
                    {
                    dojo.query("#icone_activation_p_"+player_id).addClass("used");
                    dojo.query("#bigicone_activation_p_"+player_id).addClass("bigused");
                    }
            
                    if(notif.args.ressources[player_id]['activation_g'] == 1)
                    {
                    dojo.query("#icone_activation_g_"+player_id).removeClass("used");
                    dojo.query("#bigicone_activation_g_"+player_id).removeClass("bigused");
                    }
                    if(notif.args.ressources[player_id]['activation_g'] == 0)
                    {
                    dojo.query("#icone_activation_g_"+player_id).addClass("used");
                    dojo.query("#bigicone_activation_g_"+player_id).addClass("bigused");
                    }
            
                    if(notif.args.ressources[player_id]['activation_y'] == 1)
                    {
                    dojo.query("#icone_activation_y_"+player_id).removeClass("used");
                    dojo.query("#bigicone_activation_y_"+player_id).removeClass("bigused");
                    }
                    if(notif.args.ressources[player_id]['activation_y'] == 0)
                    {
                    dojo.query("#icone_activation_y_"+player_id).addClass("used");
                    dojo.query("#bigicone_activation_y_"+player_id).addClass("bigused");
                    }

                    
                }

                for( var semi in notif.args.carbonessemi )
                {
                                        
                        var tableau = notif.args.carbonessemi[semi]['location'].split("_");
                        var x = parseInt(tableau[1], 10);
                        var y = parseInt(tableau[2], 10);
                  
                        $('valeurcarbonesemi_'+x+'_'+y+'_'+notif.args.carbonessemi[semi]['player']).innerHTML = notif.args.carbonessemi[semi]['carbone'];
                    

                                      
                    
                }

                for( var champi in notif.args.carboneschampi )
                {
                    

                        $('valeurcarbonechampi_'+notif.args.carboneschampi[champi]['id']).innerHTML = notif.args.carboneschampi[champi]['carbone'];
                    
                }


                
        },

        notif_placesemi: function( notif )
        {
            var location = notif.args.cible;
            var player = this.getActivePlayerId();
            var color = notif.args.color;

            var tableau = notif.args.cible.split("_");
            var x = parseInt(tableau[1], 10);
            var y = parseInt(tableau[2], 10);

            this.addSemi(location, player, color);
            this.addValeurCarboneSemi( player, location, notif.args.carbone );
            /*this.placeOnObject( "semi_"+x+"_"+y+"_"+player, 'overall_player_board_'+player );
            this.slideToObject( "semi_"+x+"_"+y+"_"+player, location ).play();*/   
        },

        notif_placearbre: function( notif )
        {
            var location = notif.args.cible;
            var player = this.getActivePlayerId();
            var color = notif.args.color;

            var tableau = notif.args.cible.split("_");
            var x = parseInt(tableau[1], 10);
            var y = parseInt(tableau[2], 10);

           // setTimeout(() => 
           // {
            dojo.destroy("semi_"+x+"_"+y+"_"+player);
            dojo.destroy("carbonesemi_"+x+"_"+y+"_"+player);
            this.addArbre(location, player, color);
            /*this.placeOnObject( "arbre_"+x+"_"+y+"_"+player, 'overall_player_board_'+player );
            this.slideToObject( "arbre_"+x+"_"+y+"_"+player, location ).play();*/
            
               
                
          //  }, "800");

        },

        notif_placesemineutre: function( notif )
        {
            var location = notif.args.cible;
            var player = 0;
            var player2 = this.getActivePlayerId();
            var color = notif.args.color;

            var tableau = notif.args.cible.split("_");
            var x = parseInt(tableau[1], 10);
            var y = parseInt(tableau[2], 10);

            setTimeout(() => 
            {
            this.addSemi(location, player, color);
            }, "1200");
                       
               
        },

        notif_placeracine: function( notif )
        {
            var location = notif.args.cible;
            var player = this.getActivePlayerId();
            var color = notif.args.color;

            var tableau = notif.args.cible.split("_");
            var x1 = parseInt(tableau[1], 10);
            var y1 = parseInt(tableau[2], 10);
            var x2 = parseInt(tableau[3], 10);
            var y2 = parseInt(tableau[4], 10);

            this.addRacine(location, player, color, notif.args.sens);
            /*this.placeOnObject( "racine_"+x1+"_"+y1+"_"+x2+"_"+y2+"_"+player, 'overall_player_board_'+player );
            this.slideToObject( "racine_"+x1+"_"+y1+"_"+x2+"_"+y2+"_"+player, location ).play();*/
            
        },


        notif_placeinitsemi: function( notif )
        {
            var location1 = notif.args.cible1;
            var location2 = notif.args.cible2;
            var player = this.getActivePlayerId();
            var color = notif.args.color;

            var tableau1 = notif.args.cible1.split("_");
            var x = parseInt(tableau1[1], 10);
            var y = parseInt(tableau1[2], 10);

            var tableau2 = notif.args.cible2.split("_");
            var x1 = parseInt(tableau2[1], 10);
            var y1 = parseInt(tableau2[2], 10);
            var x2 = parseInt(tableau2[3], 10);
            var y2 = parseInt(tableau2[4], 10);

            
            this.addSemi(location1, player, color);
            this.addValeurCarboneSemi( player, location1, notif.args.carbone );
            //this.placeOnObject( "semi_"+x+"_"+y+"_"+player, 'overall_player_board_'+player );
            //this.slideToObject( "semi_"+x+"_"+y+"_"+player, location1, 400, 0 ).play(); 
                           
            //this.addRacine(location2, player, color, notif.args.sens);
            //this.placeOnObject( "racine_"+x1+"_"+y1+"_"+x2+"_"+y2+"_"+player, 'overall_player_board_'+player );
           // this.slideToObject( "racine_"+x1+"_"+y1+"_"+x2+"_"+y2+"_"+player, location2, 400, 200 ).play(); 
            
        },

        notif_placeinitracine: function( notif )
        {
            var location1 = notif.args.cible1;
            var location2 = notif.args.cible2;
            var player = this.getActivePlayerId();
            var color = notif.args.color;

            var tableau1 = notif.args.cible1.split("_");
            var x = parseInt(tableau1[1], 10);
            var y = parseInt(tableau1[2], 10);

            var tableau2 = notif.args.cible2.split("_");
            var x1 = parseInt(tableau2[1], 10);
            var y1 = parseInt(tableau2[2], 10);
            var x2 = parseInt(tableau2[3], 10);
            var y2 = parseInt(tableau2[4], 10);

            
            //this.addSemi(location1, player, color);
            //this.addValeurCarboneSemi( player, location1, notif.args.carbone );
            //this.placeOnObject( "semi_"+x+"_"+y+"_"+player, 'overall_player_board_'+player );
            //this.slideToObject( "semi_"+x+"_"+y+"_"+player, location1, 400, 0 ).play(); 
                           
            this.addRacine(location2, player, color, notif.args.sens);
            //this.placeOnObject( "racine_"+x1+"_"+y1+"_"+x2+"_"+y2+"_"+player, 'overall_player_board_'+player );
           // this.slideToObject( "racine_"+x1+"_"+y1+"_"+x2+"_"+y2+"_"+player, location2, 400, 200 ).play(); 
            
        },

       
        notif_movecarbone: function( notif )  // animation jeton carbone
        {
            var id = 0;
            dojo.place( this.format_block( 'jstpl_carbonechampi', {
                id: id,
                
                                        
            } ) , notif.args.debut );

            dojo.destroy('valeurcarbonechampi_'+id);

            this.slideToObject("carbonechampi_"+id, notif.args.arrivee).play(); 

            setTimeout(() => 
            {   
                dojo.destroy("carbonechampi_"+id);
            }, "600");
        
           
            
              
        },

        notif_powerearthlover: function( notif )
        {
            /*var player = this.getActivePlayerId();
            
            var id1 = 1;
            
            dojo.place( this.format_block( 'jstpl_carbonechampi', {
                id: id1,
                
                                        
            } ) , 'square_1_0' );  
            
            this.placeOnObject( "carbonechampi_"+id1, 'overall_player_board_'+player );
            this.slideToObject("carbonechampi_"+id1, 'square_1_0').play();
            setTimeout(() => 
            { 
            dojo.destroy("carbonechampi_"+id1);
            }, "600");

           var id2 = 2;
            
            dojo.place( this.format_block( 'jstpl_carbonechampi', {
                id: id2,
                
                                        
            } ) , 'square_-1_0' );  
            
            this.placeOnObject( "carbonechampi_"+id2, 'overall_player_board_'+player );
            this.slideToObject("carbonechampi_"+id2, 'square_-1_0').play();
            setTimeout(() => 
            { 
            dojo.destroy("carbonechampi_"+id2);
            }, "600");
            var id3 = 3;
            dojo.place( this.format_block( 'jstpl_carbonechampi', {
                id: id3,
                
                                        
            } ) , 'square_0_1' );  
            
            this.placeOnObject( "carbonechampi_"+id3, 'overall_player_board_'+player );
            this.slideToObject("carbonechampi_"+id3, 'square_0_1').play();
            setTimeout(() => 
            { 
            dojo.destroy("carbonechampi_"+id3);
            }, "600");

            var id4 = 4;
            dojo.place( this.format_block( 'jstpl_carbonechampi', {
                id: id4,
                
                                        
            } ) , 'square_0_-1' );  
            
            this.placeOnObject( "carbonechampi_"+id4, 'overall_player_board_'+player );
            this.slideToObject("carbonechampi_"+id4, 'square_0_-1').play();
            setTimeout(() => 
            { 
            dojo.destroy("carbonechampi_"+id4);
            }, "600");*/
              
        },

        notif_handtile: function( notif )
        {
            if (this.isCurrentPlayerActive()) 
            {
            var player = this.getActivePlayerId();

            this.addTileHand(notif.args.type, player, notif.args.position);
            this.placeOnObject( "tilehand_"+player+"_"+notif.args.position, 'tiles_'+notif.args.position );
            this.slideToObject("tilehand_"+player+"_"+notif.args.position, 'tiles_'+player+'_'+notif.args.position).play();
            
            }
                
        },

        notif_scoregoal: function( notif )
        {
            $('scoregoal_1_'+notif.args.no).innerHTML = notif.args.scoregoal1;
            $('scoregoal_2_'+notif.args.no).innerHTML = notif.args.scoregoal2;
            $('scoregoal_3_'+notif.args.no).innerHTML = notif.args.scoregoal3;
                
        },

        notif_champiscore2: function( notif )
        {
            this.addChampiScore2(notif.args.type, notif.args.pos);
                
        },

        notif_champiscore4: function( notif )
        {
            this.addChampiScore4(notif.args.type, notif.args.pos);
                
        },


        notif_padracine: function( notif )
        {
            
                
                for (var id in notif.args.tableaufinalracine)
                {
                    for (var index in notif.args.tableaufinalracine[id])
                    {
                        var index2 = parseInt(index)+1;
                        $('padplant'+index2+'_'+id).innerHTML = notif.args.tableaufinalracine[id][index];
                    }
                    
                }

                for (var id in notif.args.tableaufinaltotalracine)
                {
                    $('padtotalplant_'+id).innerHTML = notif.args.tableaufinaltotalracine[id];
                       
                    
                    
                }
            
                
        },

        notif_padbonus: function( notif )
        {
        
                for (var id in notif.args.tableaufinalbonus)
                {
                    $('padbonus_'+id).innerHTML = notif.args.tableaufinalbonus[id];
                    
                }
                
        },

        notif_padressources: function( notif )
        {
        
                for (var id in notif.args.tableaufinalressources)
                {
                    $('padressources_'+id).innerHTML = notif.args.tableaufinalressources[id];
                    
                }
                
        },

        notif_padgoals: function( notif )
        {
            
            
            for (var id in notif.args.tableaufinalgoals)
                {
                    for (var index in notif.args.tableaufinalgoals[id])
                {
                    var index2 = parseInt(index)+1;
                    $('padgoal'+index2+'_'+id).innerHTML = notif.args.tableaufinalgoals[id][index];
                }
                
                }

            for (var id in notif.args.tableaufinaltotalgoals)
            {
                
                $('padtotalgoal_'+id).innerHTML = notif.args.tableaufinaltotalgoals[id];
                
            }
                
        },

        notif_padtotal: function( notif )
        {
        
                for (var id in notif.args.total)
                {
                    var index = parseInt(id)+1;
                    
                
                    $('padtotal_'+index).innerHTML = notif.args.total[id];
                    
                }
                
        },
        
        notif_scoregoal4: function( notif )
        {
            if(notif.args.emplacement == 'goal_1')
            {
                if (notif.args.nbre >= 2)
                {
                    $('scoregoal_1_1').innerHTML = notif.args.score1;
                    $('scoregoal_1_2').innerHTML = notif.args.score2;
                }
                if (notif.args.nbre >= 3)
                {
                    $('scoregoal_1_3').innerHTML = notif.args.score3;
                }

                if (notif.args.nbre >= 4)
                {
                    $('scoregoal_1_4').innerHTML = notif.args.score4;
                }

            }

            if(notif.args.emplacement == 'goal_2')
            {
                if (notif.args.nbre >= 2)
                {
                    $('scoregoal_2_1').innerHTML = notif.args.score1;
                    $('scoregoal_2_2').innerHTML = notif.args.score2;
                }
                if (notif.args.nbre >= 3)
                {
                    $('scoregoal_2_3').innerHTML = notif.args.score3;
                }

                if (notif.args.nbre >= 4)
                {
                    $('scoregoal_2_4').innerHTML = notif.args.score4;
                }

            }

            if(notif.args.emplacement == 'goal_3')
            {
                if (notif.args.nbre >= 2)
                {
                    $('scoregoal_3_1').innerHTML = notif.args.score1;
                    $('scoregoal_3_2').innerHTML = notif.args.score2;
                }
                if (notif.args.nbre >= 3)
                {
                    $('scoregoal_3_3').innerHTML = notif.args.score3;
                }

                if (notif.args.nbre >= 4)
                {
                    $('scoregoal_3_4').innerHTML = notif.args.score4;
                }

            }
           
                
        },


        

        


   });             
});
