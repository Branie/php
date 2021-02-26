<?php
    //刪掉
    function remove_preg($data ,$rempreg){
       
        $data = preg_replace($rempreg,'',$data);
       
        return $data;
    }
   //取代成換行
    function replace($data ,$reppreg){

        $data = preg_replace($reppreg,"\n",$data);

        return $data;
    }
    
?>