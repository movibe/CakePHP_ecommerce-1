<style>
.thumbnailw{display:none;}
</style>

<?php
/**
 * Application level View Helper
 *
 * This file is application-wide helper file. You can put all
 * application-wide helper-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Helper
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Helper', 'View');

class CropimageHelper extends Helper {
    var $helpers = array('Html', 'Javascript', 'Form','Session');

    function createJavaScript($imgW, $imgH, $thumbW, $thumbH) {
            return $this->output("<script type=\"text/javascript\">
                function preview(img, selection) {
                    var scaleX = $thumbW / selection.width;
                    var scaleY = $thumbH / selection.height;

                    $('#thumbn + div > img').css({
                        width: Math.round(scaleX * $imgW) + 'px',
                        height: Math.round(scaleY * $imgH) + 'px',
                        marginLeft: '-' + Math.round(scaleX * selection.x1) + 'px',
                        marginTop: '-' + Math.round(scaleY * selection.y1) + 'px'
                    });
                    $('#x1').val(selection.x1);
                    $('#y1').val(selection.y1);
                    $('#x2').val(selection.x2);
                    $('#y2').val(selection.y2);
                    $('#w').val(selection.width);
                    $('#h').val(selection.height);
                }

                $(document).ready(function () {
                    $('#save_thumb').click(function() {
                        var x1 = $('#x1').val();
                        var y1 = $('#y1').val();
                        var x2 = $('#x2').val();
                        var y2 = $('#y2').val();
                        var w = $('#w').val();
                        var h = $('#h').val();
                        if(x1==\"\" || y1==\"\" || x2==\"\" || y2==\"\"|| w==\"\" || h==\"\"){
                            alert('Please choose a area to crop...');
                            return false;
                        }else{
                            return true;
                    }
                });
            });

            $(window).load(function () {
                $('#thumbn').imgAreaSelect({ x1: 81, y1: 19, x2: 280, y2: 264 });
			    $('#thumbn').imgAreaSelect({ aspectRatio: '9:11', onSelectChange: preview });
            });
            </script>");
    }


    function createForm($imagePath, $tH, $tW){
        	 
		    $x1 =         $this->Form->hidden('x1', array("value" => "", "id"=>"x1"));
            $y1 =         $this->Form->hidden('y1', array("value" => "", "id"=>"y1"));
            $x2 =         $this->Form->hidden('x2', array("value" => "", "id"=>"x2",));
            $y2 =         $this->Form->hidden('y2', array("value" => "", "id"=>"y2"));
            $w =          $this->Form->hidden('w', array("value" => "", "id"=>"w"));
            $h =          $this->Form->hidden('h', array("value" => "", "id"=>"h"));
            $imgP =       $this->Form->hidden('imagePath', array("value" => $imagePath));
            $imgTum = $this->Html->image($imagePath, array('style'=>'float: left; margin-right: 10px;', 'id'=>'thumbn', 'alt'=>'Create thumbn'));
            $imgTumPrev = $this->Html->image($imagePath, array('style'=>'position: relative;', 'id'=>'thumbn','class'=>'thumbnailw', 'alt'=>'thumbn Preview'));
            return $this->output("$imgTum
            <div style=\"position:relative; overflow:hidden; width:".$tW."px; height:".$tH."px;\"> 
                $imgTumPrev
            </div>
            <br style=\"clear:both;\"/>$x1 $y1 $x2 $y2 $w $h $imgP");
    } 
}
?>