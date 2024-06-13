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
 * material.inc.php
 *
 * undergrove game material description
 *
 * Here, you can describe the material of your game with PHP variables.
 *   
 * This file is loaded in your game logic class constructor, ie these variables
 * are available everywhere in your game logic code.
 *
 */


 /*

'1' => [
    'name' => clienttranslate(""),
    
    'description' => clienttranslate(""),
    'coutab' => 0,
    'coutap' => 0,
    'coutag' => 0,
    'coutay' => 0,
    'coutc' => 0,
    'coutn' => 0,
    'coutp' => 0,
    'coutk' => 0,
    'coutlibre' => 0,
    'vp' => 0,
    'star' => 0,
    'circle' => 0,
        
   ],


 */

 $this->action = [
  '1' => [
    'name' => clienttranslate("Activate"),
    'description' => clienttranslate("Activate a mushroom where you have a seedling. Pay the Carbon (C) cost to the mushroom"),
  ],
 
  '2' => [
    'name' => clienttranslate("Absorb"),
    'description' => clienttranslate("Absorbs Carbon (C) from a seedling through its root. You must pay to move Carbon (C) from distant mushrooms"),
  ],

  '3' => [
    'name' => clienttranslate("Reproduce"),
    'description' => clienttranslate("Place a seedling and its root. You can first place a mushroom by paying a resource"),
  ],

  '4' => [
    'name' => clienttranslate("Partner"),
    'description' => clienttranslate("Place two roots on your seedlings or trees. You can first place a mushroom by paying a resource"),
  ],

  '5' => [
    'name' => clienttranslate("Photosynthesize"),
    'description' => clienttranslate("Gain two Carbons (C). Reactivate all your tokens. You may discard any number of Mushroom tiles from your hand."),
  ],


];


$this->finalgoal = [
  '1' => [
    'description' => clienttranslate("Number of times you make 6 Carbon (C) in the same game turn"),
  ],
 
  '2' => [
    'description' => clienttranslate("Number of times you turn 1 Nitrogen (N) into 1 Carbon (C)"),
  ],

  '3' => [
    'description' => clienttranslate("Number of vertical lines where you have at least one seedling or tree"),
  ],

  '4' => [
    'description' => clienttranslate("Number of unique musroom's touched by your seedlings or trees"),
  ],

  '5' => [
    'description' => clienttranslate("Total trees"),
  ],

  '6' => [
    'description' => clienttranslate("Number of seedlings and trees with at least 2 roots"),
  ],
 
  '7' => [
    'description' => clienttranslate("Total roots"),
  ],

  '8' => [
    'description' => clienttranslate("Number of times you place 2 roots on the same type of mushroom (color) in the same game turn"),
  ],

  '9' => [
    'description' => clienttranslate("Number of roots on mushrooms with an EVEN number of points"),
  ],

  '10' => [
    'description' => clienttranslate("Number of roots on mushrooms with an ODD number of points"),
  ],

  '11' => [
    'description' => clienttranslate("Number of roots on mushrooms with a color in their name (english name). Mushrooms in this category are marked with an icon:"),
  ],

  '12' => [
    'description' => clienttranslate("Number of roots on mushrooms with an animal or person in their name (english name). Mushrooms in this category are marked with an icon:"),
  ],



];




 $this->listechampi = [
  '1' => [
    'name' => clienttranslate("Earthlover"),
    
    'description' => clienttranslate("ONGOING (if you have a root on this mushroom): if no Carbon (C) is in the Forest, +1 Carbon (C) to each adjacent mushroom"),
    'coutab' => 0,
    'coutap' => 0,
    'coutag' => 0,
    'coutay' => 0,
    'coutc' => 0,
    'coutn' => 0,
    'coutp' => 0,
    'coutk' => 0,
    'coutlibre' => 0,
    'vp' => 2,
    'color' => 0,
    'animal' => 0,
        
   ],
 
   '2' => [
    'name' => clienttranslate("Painted Suillus"),
    
    'description' => "",
    'coutab' => 0,
    'coutap' => 0,
    'coutag' => 1,
    'coutay' => 0,
    'coutc' => 1,
    'coutn' => 0,
    'coutp' => 0,
    'coutk' => 0,
    'coutlibre' => 0,
    'vp' => 3,
    'color' => 0,
    'animal' => 0,
        
       
   ],

   '3' => [
    'name' => clienttranslate("Fly Agaric"),
    
    'description' => "",
    'coutab' => 1,
    'coutap' => 0,
    'coutag' => 0,
    'coutay' => 0,
    'coutc' => 1,
    'coutn' => 0,
    'coutp' => 0,
    'coutk' => 0,
    'coutlibre' => 0,
    'vp' => 3,
    'color' => 0,
    'animal' => 1,
        
       
   ],

   '4' => [
    'name' => clienttranslate("Pacific Golden Chanterelle"),
    
    'description' => "",
    'coutab' => 0,
    'coutap' => 0,
    'coutag' => 0,
    'coutay' => 1,
    'coutc' => 1,
    'coutn' => 0,
    'coutp' => 0,
    'coutk' => 0,
    'coutlibre' => 0,
    'vp' => 3,
    'color' => 1,
    'animal' => 0,
        
       
   ],

   '5' => [
    'name' => clienttranslate("Common Deceiver"),
    
    'description' => "",
    'coutab' => 0,
    'coutap' => 1,
    'coutag' => 0,
    'coutay' => 0,
    'coutc' => 1,
    'coutn' => 0,
    'coutp' => 0,
    'coutk' => 0,
    'coutlibre' => 0,
    'vp' => 3,
    'color' => 0,
    'animal' => 0,
        
     
   ],

   '6' => [
    'name' => clienttranslate("Vermillion Waxcap"),
    
    'description' => clienttranslate("Other players gain the indicated resource"),
    'coutab' => 0,
    'coutap' => 1,
    'coutag' => 0,
    'coutay' => 0,
    'coutc' => 1,
    'coutn' => 0,
    'coutp' => 0,
    'coutk' => 0,
    'coutlibre' => 0,
    'vp' => 3,
    'color' => 1,
    'animal' => 0,
        
     
   ],

   '7' => [
    'name' => clienttranslate("Dead Man's Foot"),
    
    'description' => clienttranslate("Other players gain the indicated resource"),
    'coutab' => 0,
    'coutap' => 0,
    'coutag' => 0,
    'coutay' => 1,
    'coutc' => 1,
    'coutn' => 0,
    'coutp' => 0,
    'coutk' => 0,
    'coutlibre' => 0,
    'vp' => 3,
    'color' => 0,
    'animal' => 1,
        
     
   ],

   '8' => [
    'name' => clienttranslate("Snyder's Black Morel"),
    
    'description' => clienttranslate("Other players gain the indicated resource"),
    'coutab' => 0,
    'coutap' => 0,
    'coutag' => 0,
    'coutay' => 1,
    'coutc' => 1,
    'coutn' => 0,
    'coutp' => 0,
    'coutk' => 0,
    'coutlibre' => 0,
    'vp' => 3,
    'color' => 1,
    'animal' => 1,
        
     
   ],


   '9' => [
    'name' => clienttranslate("Black Elfin Saddle"),
    
    'description' => clienttranslate("Other players gain the indicated resource"),
    'coutab' => 0,
    'coutap' => 0,
    'coutag' => 0,
    'coutay' => 1,
    'coutc' => 1,
    'coutn' => 0,
    'coutp' => 0,
    'coutk' => 0,
    'coutlibre' => 0,
    'vp' => 3,
    'color' => 1,
    'animal' => 0,
        
     
   ],



   '10' => [
    'name' => clienttranslate("King Bolete"),
    
    'description' => clienttranslate("Other players gain the indicated resource"),
    'coutab' => 0,
    'coutap' => 0,
    'coutag' => 1,
    'coutay' => 0,
    'coutc' => 1,
    'coutn' => 0,
    'coutp' => 0,
    'coutk' => 0,
    'coutlibre' => 0,
    'vp' => 3,
    'color' => 0,
    'animal' => 1,
        
     
   ],

   '11' => [
    'name' => clienttranslate("Orange Tooth"),
    
    'description' => clienttranslate("Other players gain the indicated resource"),
    'coutab' => 0,
    'coutap' => 0,
    'coutag' => 1,
    'coutay' => 0,
    'coutc' => 1,
    'coutn' => 0,
    'coutp' => 0,
    'coutk' => 0,
    'coutlibre' => 0,
    'vp' => 3,
    'color' => 1,
    'animal' => 0,
        
     
   ],

   '12' => [
    'name' => clienttranslate("Surprise Webcap"),
    
    'description' => clienttranslate("Other players gain the indicated resource"),
    'coutab' => 1,
    'coutap' => 0,
    'coutag' => 0,
    'coutay' => 0,
    'coutc' => 1,
    'coutn' => 0,
    'coutp' => 0,
    'coutk' => 0,
    'coutlibre' => 0,
    'vp' => 3,
    'color' => 0,
    'animal' => 0,
        
     
   ],

   '13' => [
    'name' => clienttranslate("Gooseberry Russula"),
    
    'description' => clienttranslate("Other players gain the indicated resource"),
    'coutab' => 0,
    'coutap' => 1,
    'coutag' => 0,
    'coutay' => 0,
    'coutc' => 1,
    'coutn' => 0,
    'coutp' => 0,
    'coutk' => 0,
    'coutlibre' => 0,
    'vp' => 3,
    'color' => 0,
    'animal' => 0,
        
     
   ],


   '14' => [
    'name' => clienttranslate("Sunshine Amanita"),
    
    'description' => clienttranslate("Other players gain the indicated resource"),
    'coutab' => 1,
    'coutap' => 0,
    'coutag' => 0,
    'coutay' => 0,
    'coutc' => 1,
    'coutn' => 0,
    'coutp' => 0,
    'coutk' => 0,
    'coutlibre' => 0,
    'vp' => 3,
    'color' => 0,
    'animal' => 0,
        
     
   ],



   '15' => [
    'name' => clienttranslate("Red-cracking Bolete"),
    
    'description' => "",
    'coutab' => 0,
    'coutap' => 0,
    'coutag' => 1,
    'coutay' => 0,
    'coutc' => 1,
    'coutn' => 0,
    'coutp' => 0,
    'coutk' => 0,
    'coutlibre' => 0,
    'vp' => 2,
    'color' => 1,
    'animal' => 0,
        
     
   ],

   '16' => [
    'name' => clienttranslate("Woodland Lepidella"),
    
    'description' => "",
    'coutab' => 1,
    'coutap' => 0,
    'coutag' => 0,
    'coutay' => 0,
    'coutc' => 1,
    'coutn' => 0,
    'coutp' => 0,
    'coutk' => 0,
    'coutlibre' => 0,
    'vp' => 2,
    'color' => 0,
    'animal' => 0,
        
     
   ],

   '17' => [
    'name' => clienttranslate("Rehm's Haircup"),
    
    'description' => "",
    'coutab' => 0,
    'coutap' => 0,
    'coutag' => 0,
    'coutay' => 1,
    'coutc' => 1,
    'coutn' => 0,
    'coutp' => 0,
    'coutk' => 0,
    'coutlibre' => 0,
    'vp' => 2,
    'color' => 0,
    'animal' => 1,
        
     
   ],

   '18' => [
    'name' => clienttranslate("Velvet Milkcap"),
    
    'description' => "",
    'coutab' => 0,
    'coutap' => 1,
    'coutag' => 0,
    'coutay' => 0,
    'coutc' => 1,
    'coutn' => 0,
    'coutp' => 0,
    'coutk' => 0,
    'coutlibre' => 0,
    'vp' => 2,
    'color' => 0,
    'animal' => 0,
        
     
   ],


   '19' => [
    'name' => clienttranslate("Red-bleeding Milkcap"),
    
    'description' => clienttranslate("Gain an additional action"),
    'coutab' => 0,
    'coutap' => 1,
    'coutag' => 0,
    'coutay' => 0,
    'coutc' => 0,
    'coutn' => 0,
    'coutp' => 0,
    'coutk' => 0,
    'coutlibre' => 0,
    'vp' => 4,
    'color' => 1,
    'animal' => 0,
        
     
   ],



   '20' => [
    'name' => clienttranslate("Bellybutton Hedgehog"),
    
    'description' => clienttranslate("Gain an additional action"),
    'coutab' => 0,
    'coutap' => 0,
    'coutag' => 1,
    'coutay' => 0,
    'coutc' => 0,
    'coutn' => 0,
    'coutp' => 0,
    'coutk' => 0,
    'coutlibre' => 0,
    'vp' => 4,
    'color' => 0,
    'animal' => 1,
        
     
   ],

   '21' => [
    'name' => clienttranslate("Scaly Vase"),
    
    'description' => clienttranslate("Gain an additional action"),
    'coutab' => 0,
    'coutap' => 0,
    'coutag' => 0,
    'coutay' => 1,
    'coutc' => 0,
    'coutn' => 0,
    'coutp' => 0,
    'coutk' => 0,
    'coutlibre' => 0,
    'vp' => 4,
    'color' => 0,
    'animal' => 0,
        
     
   ],

   '22' => [
    'name' => clienttranslate("Gassy Webcap"),
    
    'description' => clienttranslate("Gain an additional action"),
    'coutab' => 1,
    'coutap' => 0,
    'coutag' => 0,
    'coutay' => 0,
    'coutc' => 0,
    'coutn' => 0,
    'coutp' => 0,
    'coutk' => 0,
    'coutlibre' => 0,
    'vp' => 4,
    'color' => 0,
    'animal' => 0,
        
     
   ],

   '23' => [
    'name' => clienttranslate("Zeller's Bolete"),
    
    'description' => "",
    'coutab' => 0,
    'coutap' => 0,
    'coutag' => 1,
    'coutay' => 0,
    'coutc' => 2,
    'coutn' => 0,
    'coutp' => 0,
    'coutk' => 0,
    'coutlibre' => 0,
    'vp' => 2,
    'color' => 0,
    'animal' => 1,
        
     
   ],


   '24' => [
    'name' => clienttranslate("White Chanterelle"),
    
    'description' => "",
    'coutab' => 0,
    'coutap' => 0,
    'coutag' => 0,
    'coutay' => 1,
    'coutc' => 2,
    'coutn' => 0,
    'coutp' => 0,
    'coutk' => 0,
    'coutlibre' => 0,
    'vp' => 2,
    'color' => 1,
    'animal' => 0,
        
     
   ],



   '25' => [
    'name' => clienttranslate("Blood-red Webcap"),
    
    'description' => "",
    'coutab' => 1,
    'coutap' => 0,
    'coutag' => 0,
    'coutay' => 0,
    'coutc' => 2,
    'coutn' => 0,
    'coutp' => 0,
    'coutk' => 0,
    'coutlibre' => 0,
    'vp' => 2,
    'color' => 1,
    'animal' => 0,
        
     
   ],

   '26' => [
    'name' => clienttranslate("Cowboy's Handkerchief"),
    
    'description' => "",
    'coutab' => 0,
    'coutap' => 1,
    'coutag' => 0,
    'coutay' => 0,
    'coutc' => 2,
    'coutn' => 0,
    'coutp' => 0,
    'coutk' => 0,
    'coutlibre' => 0,
    'vp' => 2,
    'color' => 0,
    'animal' => 1,
        
     
   ],

   '27' => [
    'name' => clienttranslate("Pig's Ears"),
    
    'description' => "",
    'coutab' => 0,
    'coutap' => 0,
    'coutag' => 0,
    'coutay' => 1,
    'coutc' => 2,
    'coutn' => 0,
    'coutp' => 0,
    'coutk' => 0,
    'coutlibre' => 0,
    'vp' => 2,
    'color' => 0,
    'animal' => 1,
        
     
   ],

   '28' => [
    'name' => clienttranslate("Candy Cap"),
    
    'description' => clienttranslate("ONGOING (if you have a root on this mushroom): you can place an additional root during the Reproduce action around the same seedling"),
    'coutab' => 0,
    'coutap' => 1,
    'coutag' => 0,
    'coutay' => 0,
    'coutc' => 0,
    'coutn' => 0,
    'coutp' => 0,
    'coutk' => 0,
    'coutlibre' => 0,
    'vp' => 1,
    'color' => 0,
    'animal' => 0,
        
     
   ],


   '29' => [
    'name' => clienttranslate("Earthfan"),
    
    'description' => clienttranslate("ONGOING (if you have a root on this mushroom): you can place an additional root during the Partner"),
    'coutab' => 0,
    'coutap' => 0,
    'coutag' => 0,
    'coutay' => 1,
    'coutc' => 0,
    'coutn' => 0,
    'coutp' => 0,
    'coutk' => 0,
    'coutlibre' => 0,
    'vp' => 1,
    'color' => 0,
    'animal' => 0,
        
     
   ],



   '30' => [
    'name' => clienttranslate("Slippery Jack"),
    
    'description' => clienttranslate("ONGOING (if you have a root on this mushroom): you can place an additional mushroom for free during the Reproduce action"),
    'coutab' => 0,
    'coutap' => 0,
    'coutag' => 1,
    'coutay' => 0,
    'coutc' => 0,
    'coutn' => 0,
    'coutp' => 0,
    'coutk' => 0,
    'coutlibre' => 0,
    'vp' => 4,
    'color' => 0,
    'animal' => 1,
        
     
   ],

   '31' => [
    'name' => clienttranslate("Smith's Amanita"),
    
    'description' => clienttranslate("ONGOING (if you have a root on this mushroom): you can place an additional mushroom for free during the Partner action"),
    'coutab' => 1,
    'coutap' => 0,
    'coutag' => 0,
    'coutay' => 0,
    'coutc' => 0,
    'coutn' => 0,
    'coutp' => 0,
    'coutk' => 0,
    'coutlibre' => 0,
    'vp' => 4,
    'color' => 0,
    'animal' => 1,
        
     
   ],

   '32' => [
    'name' => clienttranslate("Brown Roll-rim"),
    
    'description' => clienttranslate("ONGOING (if you have a root on this mushroom): you gain one more Carbon (C) during the Photosynthesize action"),
    'coutab' => 0,
    'coutap' => 1,
    'coutag' => 0,
    'coutay' => 0,
    'coutc' => 0,
    'coutn' => 0,
    'coutp' => 0,
    'coutk' => 0,
    'coutlibre' => 0,
    'vp' => 3,
    'color' => 1,
    'animal' => 0,
        
     
   ],

   '33' => [
    'name' => clienttranslate("Woolly Pine Spike"),
    
    'description' => clienttranslate("Absorb 2 Carbons (C) from seedlings (you can pay to move the Carbon)"),
    'coutab' => 1,
    'coutap' => 0,
    'coutag' => 0,
    'coutay' => 0,
    'coutc' => 2,
    'coutn' => 0,
    'coutp' => 0,
    'coutk' => 0,
    'coutlibre' => 1,
    'vp' => 2,
    'color' => 0,
    'animal' => 0,
        
     
   ],


   '34' => [
    'name' => clienttranslate("Hot-Pink Coral"),
    
    'description' => clienttranslate("Absorb 2 Carbons (C) from seedlings (you can pay to move the Carbon)"),
    'coutab' => 0,
    'coutap' => 0,
    'coutag' => 0,
    'coutay' => 1,
    'coutc' => 2,
    'coutn' => 0,
    'coutp' => 0,
    'coutk' => 0,
    'coutlibre' => 1,
    'vp' => 2,
    'color' => 1,
    'animal' => 1,
        
     
   ],



   '35' => [
    'name' => clienttranslate("Admirable Bolete"),
    
    'description' => clienttranslate("Absorb 2 Carbons (C) from seedlings (you can pay to move the Carbon)"),
    'coutab' => 0,
    'coutap' => 0,
    'coutag' => 1,
    'coutay' => 0,
    'coutc' => 2,
    'coutn' => 0,
    'coutp' => 0,
    'coutk' => 0,
    'coutlibre' => 1,
    'vp' => 2,
    'color' => 0,
    'animal' => 0,
        
     
   ],

   '36' => [
    'name' => clienttranslate("Short-stemmed Russula"),
    
    'description' => clienttranslate("Absorb 2 Carbon (C) from seedlings (you can pay to move the Carbon)"),
    'coutab' => 0,
    'coutap' => 1,
    'coutag' => 0,
    'coutay' => 0,
    'coutc' => 2,
    'coutn' => 0,
    'coutp' => 0,
    'coutk' => 0,
    'coutlibre' => 1,
    'vp' => 2,
    'color' => 0,
    'animal' => 0,
        
     
   ],

   '37' => [
    'name' => clienttranslate("Woolly Owl Eyes"),
    
    'description' => clienttranslate("Absorb 2 Carbons (C) of this mushroom from seedlings (you can pay to move the Carbon)"),
    'coutab' => 0,
    'coutap' => 0,
    'coutag' => 1,
    'coutay' => 0,
    'coutc' => 2,
    'coutn' => 0,
    'coutp' => 0,
    'coutk' => 0,
    'coutlibre' => 1,
    'vp' => 3,
    'color' => 0,
    'animal' => 1,
        
     
   ],

   '38' => [
    'name' => clienttranslate("Western Matsutake"),
    
    'description' => clienttranslate("Absorb 2 Carbons (C) of this mushroom from seedlings (you can pay to move the Carbon)"),
    'coutab' => 1,
    'coutap' => 0,
    'coutag' => 0,
    'coutay' => 0,
    'coutc' => 2,
    'coutn' => 0,
    'coutp' => 0,
    'coutk' => 0,
    'coutlibre' => 1,
    'vp' => 3,
    'color' => 0,
    'animal' => 0,
        
     
   ],


   '39' => [
    'name' => clienttranslate("Brown Elfin Saddle"),
    
    'description' => clienttranslate("Absorb 2 Carbons (C) of this mushroom from seedlings (you can pay to move the Carbon)"),
    'coutab' => 0,
    'coutap' => 0,
    'coutag' => 0,
    'coutay' => 1,
    'coutc' => 2,
    'coutn' => 0,
    'coutp' => 0,
    'coutk' => 0,
    'coutlibre' => 1,
    'vp' => 3,
    'color' => 1,
    'animal' => 0,
        
     
   ],



   '40' => [
    'name' => clienttranslate("Amethyst Deceiver"),
    
    'description' => clienttranslate("Absorb 2 Carbons (C) of this mushroom from seedlings (you can pay to move the Carbon)"),
    'coutab' => 0,
    'coutap' => 1,
    'coutag' => 0,
    'coutay' => 0,
    'coutc' => 2,
    'coutn' => 0,
    'coutp' => 0,
    'coutk' => 0,
    'coutlibre' => 1,
    'vp' => 3,
    'color' => 1,
    'animal' => 0,
        
     
   ],

   '41' => [
    'name' => clienttranslate("Yellow-veiled Amanita"),
    
    'description' => clienttranslate("Add a resource from the reserve to the mushroom"),
    'coutab' => 1,
    'coutap' => 0,
    'coutag' => 0,
    'coutay' => 0,
    'coutc' => 1,
    'coutn' => 0,
    'coutp' => 0,
    'coutk' => 0,
    'coutlibre' => 0,
    'vp' => 0,
    'color' => 1,
    'animal' => 0,
        
     
   ],

   '42' => [
    'name' => clienttranslate("Peppery Bolete"),
    
    'description' => clienttranslate("Place any number of resources gained on the mushroom"),
    'coutab' => 0,
    'coutap' => 0,
    'coutag' => 1,
    'coutay' => 0,
    'coutc' => 1,
    'coutn' => 0,
    'coutp' => 0,
    'coutk' => 0,
    'coutlibre' => 0,
    'vp' => 0,
    'color' => 0,
    'animal' => 0,
        
     
   ],

   '43' => [
    'name' => clienttranslate("Green Russula"),
    
    'description' => clienttranslate("Place any number of resources gained on the mushroom"),
    'coutab' => 0,
    'coutap' => 1,
    'coutag' => 0,
    'coutay' => 0,
    'coutc' => 1,
    'coutn' => 0,
    'coutp' => 0,
    'coutk' => 0,
    'coutlibre' => 0,
    'vp' => 0,
    'color' => 1,
    'animal' => 0,
        
     
   ],


   '44' => [
    'name' => clienttranslate("Panthercap"),
    
    'description' => clienttranslate("Place any number of resources gained on the mushroom"),
    'coutab' => 1,
    'coutap' => 0,
    'coutag' => 0,
    'coutay' => 0,
    'coutc' => 1,
    'coutn' => 0,
    'coutp' => 0,
    'coutk' => 0,
    'coutlibre' => 0,
    'vp' => 0,
    'color' => 0,
    'animal' => 1,
        
     
   ],



   '45' => [
    'name' => clienttranslate("Mouse Trich"),
    
    'description' => "",
    'coutab' => 0,
    'coutap' => 1,
    'coutag' => 0,
    'coutay' => 0,
    'coutc' => 0,
    'coutn' => 0,
    'coutp' => 0,
    'coutk' => 0,
    'coutlibre' => 0,
    'vp' => 0,
    'color' => 0,
    'animal' => 1,
        
     
   ],

   '46' => [
    'name' => clienttranslate("Yellow-tipped Coral"),
    
    'description' => "",
    'coutab' => 0,
    'coutap' => 0,
    'coutag' => 0,
    'coutay' => 1,
    'coutc' => 0,
    'coutn' => 0,
    'coutp' => 0,
    'coutk' => 0,
    'coutlibre' => 0,
    'vp' => 0,
    'color' => 1,
    'animal' => 1,
        
     
   ],

   '47' => [
    'name' => clienttranslate("Violet Webcap"),
    
    'description' => "",
    'coutab' => 1,
    'coutap' => 0,
    'coutag' => 0,
    'coutay' => 0,
    'coutc' => 0,
    'coutn' => 0,
    'coutp' => 0,
    'coutk' => 0,
    'coutlibre' => 0,
    'vp' => 0,
    'color' => 1,
    'animal' => 0,
        
     
   ],

   '48' => [
    'name' => clienttranslate("Fat Jack"),
    
    'description' => "",
    'coutab' => 0,
    'coutap' => 0,
    'coutag' => 1,
    'coutay' => 0,
    'coutc' => 0,
    'coutn' => 0,
    'coutp' => 0,
    'coutk' => 0,
    'coutlibre' => 0,
    'vp' => 0,
    'color' => 0,
    'animal' => 1,
        
     
   ],


   '49' => [
    'name' => clienttranslate("Scarletina Bolete"),
    
    'description' => clienttranslate("Absorb 1 Carbon (C) from a seedling (you can pay to move the Carbon. The first two trips are free)"),
    'coutab' => 0,
    'coutap' => 0,
    'coutag' => 1,
    'coutay' => 0,
    'coutc' => 0,
    'coutn' => 0,
    'coutp' => 0,
    'coutk' => 0,
    'coutlibre' => 1,
    'vp' => 4,
    'color' => 1,
    'animal' => 0,
        
     
   ],



   '50' => [
    'name' => clienttranslate("Crested Coral"),
    
    'description' => clienttranslate("Select any mushroom of the matching type and carry out its action, as if it were printed on the copycat mushroom. (Pay the Carbon (C) cost of the copied mushroom on the copycat. If copying an “Absorb from HERE” action, Absorb from the copycat.)"),
    'coutab' => 0,
    'coutap' => 0,
    'coutag' => 0,
    'coutay' => 1,
    'coutc' => 0,
    'coutn' => 0,
    'coutp' => 0,
    'coutk' => 0,
    'coutlibre' => 0,
    'vp' => 2,
    'color' => 0,
    'animal' => 1,
        
     
   ],

   '51' => [
    'name' => clienttranslate("Rosy Slime Spike"),
    
    'description' => clienttranslate("Select any mushroom of the matching type and carry out its action, as if it were printed on the copycat mushroom. (Pay the Carbon (C) cost of the copied mushroom on the copycat. If copying an “Absorb from HERE” action, Absorb from the copycat.)"),
    'coutab' => 1,
    'coutap' => 0,
    'coutag' => 0,
    'coutay' => 0,
    'coutc' => 0,
    'coutn' => 0,
    'coutp' => 0,
    'coutk' => 0,
    'coutlibre' => 0,
    'vp' => 2,
    'color' => 1,
    'animal' => 0,
        
     
   ],

   '52' => [
    'name' => clienttranslate("Golden Waxcap"),
    
    'description' => clienttranslate("Select any mushroom of the matching type and carry out its action, as if it were printed on the copycat mushroom. (Pay the Carbon (C) cost of the copied mushroom on the copycat. If copying an “Absorb from HERE” action, Absorb from the copycat.)"),
    'coutab' => 0,
    'coutap' => 1,
    'coutag' => 0,
    'coutay' => 0,
    'coutc' => 0,
    'coutn' => 0,
    'coutp' => 0,
    'coutk' => 0,
    'coutlibre' => 0,
    'vp' => 2,
    'color' => 1,
    'animal' => 0,
        
     
   ],

   '53' => [
    'name' => clienttranslate("Suede Bolete"),
    
    'description' => clienttranslate("Select any mushroom of the matching type and carry out its action, as if it were printed on the copycat mushroom. (Pay the Carbon (C) cost of the copied mushroom on the copycat. If copying an “Absorb from HERE” action, Absorb from the copycat.)"),
    'coutab' => 0,
    'coutap' => 0,
    'coutag' => 1,
    'coutay' => 0,
    'coutc' => 0,
    'coutn' => 0,
    'coutp' => 0,
    'coutk' => 0,
    'coutlibre' => 0,
    'vp' => 2,
    'color' => 0,
    'animal' => 0,
        
     
   ],



   
  ];




