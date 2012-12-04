<?php
class GetGliddenPalletteComponent extends Component {

	function getClosestColorArry($imageLocation) { 
		$image = $imageLocation;
		function rgb_to_xyz($rgb){
     $red = $rgb[0];
     $green = $rgb[1];
     $blue = $rgb[2]; 
     $_red = $red/255;
     $_green = $green/255;
     $_blue = $blue/255;
     if($_red>0.04045){
          $_red = ($_red+0.055)/1.055;
          $_red = pow($_red,2.4);
     }
     else{
          $_red = $_red/12.92;
     }
     if($_green>0.04045){
          $_green = ($_green+0.055)/1.055;
          $_green = pow($_green,2.4);     
     }
     else{
          $_green = $_green/12.92;
     }
     if($_blue>0.04045){
          $_blue = ($_blue+0.055)/1.055;
          $_blue = pow($_blue,2.4);     
     }
     else{
          $_blue = $_blue/12.92;
     }
     $_red *= 100;
     $_green *= 100;
     $_blue *= 100;
     $x = $_red * 0.4124 + $_green * 0.3576 + $_blue * 0.1805;
     $y = $_red * 0.2126 + $_green * 0.7152 + $_blue * 0.0722;
     $z = $_red * 0.0193 + $_green * 0.1192 + $_blue * 0.9505;
     return(array($x,$y,$z));
}

function xyz_to_lab($xyz){
     $x = $xyz[0];
     $y = $xyz[1];
     $z = $xyz[2];
     $_x = $x/95.047;
     $_y = $y/100;
     $_z = $z/108.883;
     if($_x>0.008856){
          $_x = pow($_x,1/3);
     }
     else{
          $_x = 7.787*$_x + 16/116;
     }
     if($_y>0.008856){
          $_y = pow($_y,1/3);
     }
     else{
          $_y = (7.787*$_y) + (16/116);
     }
     if($_z>0.008856){
          $_z = pow($_z,1/3);
     }
     else{
          $_z = 7.787*$_z + 16/116;
     }
     $l= 116*$_y -16;
     $a= 500*($_x-$_y);
     $b= 200*($_y-$_z);
     return(array($l,$a,$b));
}

function de_1994($lab1,$lab2){
     $c1 = sqrt($lab1[1]*$lab1[1]+$lab1[2]*$lab1[2]);
     $c2 = sqrt($lab2[1]*$lab2[1]+$lab2[2]*$lab2[2]);
     $dc = $c1-$c2;
     $dl = $lab1[0]-$lab2[0];
     $da = $lab1[1]-$lab2[1];
     $db = $lab1[2]-$lab2[2];
     $dh = sqrt(($da*$da)+($db*$db)-($dc*$dc));
     $first = $dl;
     $second = $dc/(1+0.045*$c1);
     $third = $dh/(1+0.015*$c1);
     return(sqrt($first*$first+$second*$second+$third*$third));
}
		// The function takes in an image resource (the result from one
		// of the GD imagecreate... functions) as well as a width and
		// height for the size of colour palette you wish to create.
		// This defaults to a 3x3, 9 block palette.
		function build_palette($img_resource, $palette_w = 2, $palette_h = 3) {
		$width = imagesx($img_resource);
		$height = imagesy($img_resource);
	
		// Calculate the width and height of each palette block
		// based upon the size of the input image and the number
		// of blocks.
		$block_w = round($width / $palette_w);
		$block_h = round($height / $palette_h);
	
		for($y = 0; $y < $palette_h; $y++) {
			for($x = 0; $x < $palette_w; $x++) {
				// Calculate where to take an image sample from the soruce image.
				$block_start_x = ($x * $block_w);
				$block_start_y = ($y * $block_h);
	
				// Create a blank 1x1 image into which we will copy
				// the image sample.
				$block = imagecreatetruecolor(1, 1);
	
				imagecopyresampled($block, $img_resource, 0, 0, $block_start_x, $block_start_y, 1, 1, $block_w, $block_h);
	
				// Convert the block to a palette image of just one colour.
				imagetruecolortopalette($block, true, 1);
	
				// Find the RGB value of the block's colour and save it
				// to an array.
				$colour_index = imagecolorat($block, 0, 0);
                                //error_log('*** '.$colour_index);
				$rgb = imagecolorsforindex($block, $colour_index);
                                //print_r($rgb);
                                /*echo '<div style="float:left;background-color: rgb(';
                                echo $rgb['red'];
                                echo ', ';
                                echo $rgb['green'];
                                echo ', ';
                                echo $rgb['blue'];
                                echo '); height: 40px; width: 40px;"></div>';//*/
                            
				$colour_array[$x][$y]['r'] = $rgb['red'];
				$colour_array[$x][$y]['g'] = $rgb['green'];
				$colour_array[$x][$y]['b'] = $rgb['blue'];
                                //echo '<pre>';
                                //debug($colour_array);
	
				//imagedestroy($block);
			}
		}
	
		imagedestroy($img_resource);
		return $colour_array;
                }//*/
	
		function grabclosestcolor($r, $g, $b){
			$colors = array(
			array(236, 224, 233),
			array(111, 74, 89),
			array(244, 201, 181),
			array(244, 153, 124),
			array(157, 84, 69),
			array(247, 236, 213),
			array(222, 183, 133),
			array(155, 108, 63),
			array(217, 209, 203),
			array(211, 209, 213),
			array(171, 156, 155),
			array(161, 154, 155),
			array(89, 68, 71),
			array(240, 223, 232),
			array(207, 162, 179),
			array(154, 118, 138),
			array(246, 214, 194),
			array(235, 137, 99),
			array(210, 86, 49),
			array(249, 225, 181),
			array(202, 162, 108),
			array(173, 132, 85),
			array(217, 208, 201),
			array(203, 187, 172),
			array(187, 172, 162),
			array(127, 103, 97),
			array(86, 78, 81),
			array(232, 203, 224),
			array(190, 121, 160),
			array(130, 67, 103),
			array(243, 188, 152),
			array(233, 151, 113),
			array(125, 81, 71),
			array(255, 218, 151),
			array(255, 199, 82),
			array(239, 230, 216),
			array(232, 220, 202),
			array(217, 192, 166),
			array(157, 119, 93),
			array(107, 75, 60),
			array(235, 193, 215),
			array(215, 139, 176),
			array(156, 67, 103),
			array(228, 119, 55),
			array(219, 102, 53),
			array(245, 224, 176),
			array(236, 208, 147),
			array(195, 144, 64),
			array(220, 205, 182),
			array(184, 156, 126),
			array(167, 133, 103),
			array(96, 81, 73),
			array(242, 216, 224),
			array(188, 135, 143),
			array(121, 65, 81),
			array(249, 197, 150),
			array(243, 155, 91),
			array(158, 89, 63),
			array(253, 236, 190),
			array(243, 192, 100),
			array(236, 230, 219),
			array(223, 213, 197),
			array(208, 191, 170),
			array(172, 161, 144),
			array(91, 79, 67),
			array(247, 219, 219),
			array(209, 153, 154),
			array(110, 59, 67),
			array(220, 164, 126),
			array(136, 92, 67),
			array(253, 233, 178),
			array(251, 221, 149),
			array(217, 183, 110),
			array(213, 209, 200),
			array(178, 173, 162),
			array(162, 156, 144),
			array(100, 92, 81),
			array(238, 174, 189),
			array(219, 113, 132),
			array(172, 63, 82),
			array(255, 203, 149),
			array(255, 180, 108),
			array(237, 137, 45),
			array(242, 228, 186),
			array(233, 203, 135),
			array(227, 216, 194),
			array(218, 197, 167),
			array(190, 171, 142),
			array(134, 106, 76),
			array(102, 84, 59),
			array(169, 56, 63),
			array(139, 56, 61),
			array(247, 218, 187),
			array(239, 191, 147),
			array(167, 108, 67),
			array(255, 223, 123),
			array(255, 212, 96),
			array(241, 236, 222),
			array(234, 224, 203),
			array(225, 213, 190),
			array(239, 235, 221),
			array(214, 204, 184),
			array(241, 173, 180),
			array(222, 107, 112),
			array(162, 62, 70),
			array(250, 233, 204),
			array(242, 154, 38),
			array(249, 241, 214),
			array(255, 231, 165),
			array(247, 209, 113),
			array(244, 235, 216),
			array(242, 227, 201),
			array(230, 210, 179),
			array(240, 235, 225),
			array(230, 223, 208),
			array(246, 227, 224),
			array(193, 61, 59),
			array(253, 223, 181),
			array(252, 192, 117),
			array(250, 234, 186),
			array(255, 226, 140),
			array(235, 201, 99),
			array(244, 233, 221),
			array(235, 214, 192),
			array(227, 203, 181),
			array(237, 235, 231),
			array(242, 235, 221),
			array(238, 208, 204),
			array(220, 158, 152),
			array(173, 84, 81),
			array(253, 226, 180),
			array(254, 198, 109),
			array(244, 173, 79),
			array(249, 235, 182),
			array(242, 220, 141),
			array(219, 187, 90),
			array(243, 234, 226),
			array(234, 222, 213),
			array(225, 210, 201),
			array(225, 222, 215),
			array(214, 209, 203),
			array(204, 121, 116),
			array(149, 63, 61),
			array(244, 235, 210),
			array(243, 197, 138),
			array(183, 134, 85),
			array(242, 236, 212),
			array(201, 186, 151),
			array(163, 149, 115),
			array(237, 235, 236),
			array(226, 223, 226),
			array(228, 221, 221),
			array(222, 208, 208),
			array(241, 239, 203),
			array(208, 197, 140),
			array(176, 166, 110),
			array(200, 230, 227),
			array(141, 202, 200),
			array(102, 159, 158),
			array(170, 178, 202),
			array(115, 134, 175),
			array(240, 241, 236),
			array(226, 227, 222),
			array(219, 221, 218),
			array(221, 223, 227),
			array(208, 210, 215),
			array(226, 224, 179),
			array(132, 131, 95),
			array(207, 225, 226),
			array(179, 205, 208),
			array(82, 118, 124),
			array(196, 203, 221),
			array(143, 156, 190),
			array(78, 84, 114),
			array(231, 236, 233),
			array(219, 226, 225),
			array(203, 211, 212),
			array(232, 236, 237),
			array(214, 221, 225),
			array(210, 210, 158),
			array(162, 163, 106),
			array(208, 231, 235),
			array(164, 206, 212),
			array(104, 165, 176),
			array(208, 213, 229),
			array(110, 117, 162),
			array(238, 238, 232),
			array(224, 222, 213),
			array(211, 210, 200),
			array(220, 222, 218),
			array(204, 210, 205),
			array(233, 233, 167),
			array(225, 224, 149),
			array(204, 206, 122),
			array(154, 216, 222),
			array(0, 121, 137),
			array(191, 193, 214),
			array(143, 148, 184),
			array(119, 124, 161),
			array(240, 239, 228),
			array(238, 236, 220),
			array(217, 216, 191),
			array(236, 238, 229),
			array(210, 213, 199),
			array(216, 223, 187),
			array(189, 198, 153),
			array(94, 102, 64),
			array(139, 206, 224),
			array(0, 145, 174),
			array(201, 202, 218),
			array(152, 147, 183),
			array(93, 90, 130),
			array(228, 224, 208),
			array(217, 212, 195),
			array(245, 241, 230),
			array(232, 225, 202),
			array(234, 236, 220),
			array(195, 194, 166),
			array(192, 224, 236),
			array(136, 203, 230),
			array(0, 139, 191),
			array(191, 190, 219),
			array(156, 153, 198),
			array(118, 115, 166),
			array(205, 198, 182),
			array(184, 175, 156),
			array(166, 154, 134),
			array(102, 95, 79),
			array(86, 78, 62),
			array(200, 204, 183),
			array(165, 171, 145),
			array(103, 107, 85),
			array(129, 171, 193),
			array(34, 92, 123),
			array(208, 206, 227),
			array(128, 123, 160),
			array(90, 87, 122),
			array(205, 200, 180),
			array(181, 176, 154),
			array(162, 155, 131),
			array(121, 120, 106),
			array(87, 90, 82),
			array(193, 214, 127),
			array(153, 195, 110),
			array(180, 213, 232),
			array(127, 179, 217),
			array(62, 125, 171),
			array(198, 190, 209),
			array(80, 74, 90),
			array(217, 222, 217),
			array(187, 200, 196),
			array(178, 183, 175),
			array(161, 169, 164),
			array(55, 69, 65),
			array(227, 239, 220),
			array(165, 214, 166),
			array(105, 176, 110),
			array(219, 227, 235),
			array(150, 185, 219),
			array(109, 147, 182),
			array(220, 218, 232),
			array(136, 123, 158),
			array(97, 85, 118),
			array(216, 224, 228),
			array(188, 199, 204),
			array(161, 173, 176),
			array(108, 123, 129),
			array(102, 121, 134),
			array(212, 234, 200),
			array(163, 195, 163),
			array(47, 93, 72),
			array(207, 219, 232),
			array(134, 158, 183),
			array(95, 119, 142),
			array(190, 168, 206),
			array(152, 125, 175),
			array(107, 75, 117),
			array(194, 197, 204),
			array(167, 171, 181),
			array(148, 154, 159),
			array(94, 103, 113),
			array(221, 240, 230),
			array(144, 214, 179),
			array(102, 194, 152),
			array(182, 200, 226),
			array(109, 131, 162),
			array(61, 81, 106),
			array(168, 152, 176),
			array(119, 96, 132),
			array(212, 213, 223),
			array(187, 185, 195),
			array(133, 132, 142),
			array(103, 107, 120),
			array(204, 217, 196),
			array(170, 188, 168),
			array(66, 88, 80),
			array(148, 173, 212),
			array(115, 146, 193),
			array(200, 188, 205),
			array(130, 114, 130),
			array(91, 75, 88),
			array(222, 223, 226),
			array(191, 191, 189),
			array(162, 162, 161),
			array(134, 134, 133),
			array(60, 59, 59)
		);
                        $newZ = array();
                        foreach ($colors as $xzy) {
                            $newZ[] = rgb_to_xyz($xzy);
                        }
                        //echo '<pre>';
                        //print_r($newZ);
                        $newLab = array();
                        foreach($newZ as $lab) {
                            $newLab[] = xyz_to_lab($lab);
                        }
                        //print_r($newLab);
                        $passed = array();
                        $passed[] = $r;
                        $passed[] = $g;
                        $passed[] = $b;
                        $passed = rgb_to_xyz($passed);
                        $passed = xyz_to_lab($passed);
                        //error_log('***'.print_r($passed));
                        $newFinArr = array();
                        foreach ($newLab as $k => $newComp) {
                            $newFinArr[$k] = de_1994($passed, $newComp);
                        }
                        //print_r($newFinArr);
                        $small = min($newFinArr);
                        $col = array_search($small, $newFinArr);
                        
                        /*echo '<div style="float:left;background-color: rgb(';
                        echo $colors[$col][0];
                        echo ', ';
                        echo $colors[$col][1];
                        echo ', ';
                        echo $colors[$col][2];
                        echo '); height: 40px; width: 40px;"></div>';//*/
                        //error_log($colors[$col]);
                        return $colors[$col];
                        
			/*$i=0;
			foreach ($colors as $value) {
				$difference[$i] = sqrt((abs($r-$value[0]))^2+(abs($g-$value[1]))^2+(abs($b-$value[2]))^2);
				$i++;
			}
                        
			$newDiff = array();
			foreach($difference as $id => $val){
				if ((!is_nan($val)) && ($val>'0') && ($val<'2.5')){
					$newDiff[$id]=$val;
				}
			}//*/
			//echo '<pre>';
			//print_r($newDiff);
			//print_r($differencearray);
                        /*foreach($newDiff as $k => $v) {
                            echo '<div style="background-color: rgb(';
                            //echo $colors[$k][0].','. $colors[$k[1].','. $colors[$k][2];
                            echo $colors[$k][0];
                            echo ', ';
                            echo $colors[$k][1];
                            echo ', ';
                            echo $colors[$k][2];
                            echo '); height: 40px; width: 40px;">'.$v.'</div>';
                        }
                        if (!empty($newDiff)) {
                            $smallest = min($newDiff);
                            $key = array_search($smallest, $newDiff);
                            //print $key."<br />";
                            return $colors[$key];
                        } else {
                            return $colors['0'];
                        }//*/
		}
		$ext = strtolower(substr(basename($image), strrpos(basename($image), ".") + 1));
                if($ext == "png"){ 
                    $im = imagecreatefrompng(WWW_ROOT.'/img/upload/thumb_'.$image);
                }elseif($ext == "jpg" || $ext == "jpeg"){ 
                    $im = imagecreatefromjpeg(WWW_ROOT.'/img/upload/thumb_'.$image); 
                }elseif($ext == "gif"){ 
                    $im = imagecreatefromgif(WWW_ROOT.'/img/upload/thumb_'.$image);
                } 
		
                //print_r($image);
		$colorArr2 = build_palette($im);
                //echo '<br />';
		$exitArr = array();
                
		foreach($colorArr2 as $col){
			foreach($col as $part){
				//$exitArr[] = $part['r'].','.$part['g'].','.$part['b'];
				$exitArr[] = grabclosestcolor($part['r'], $part['g'], $part['b']);
			}
		}
                
                //print_r($exitArr);
		return $exitArr;
	}	
	
	
}