<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Reporting
{
     private $_ci;
     public function Reporting()
     {
        $this->_ci=&get_instance();
     }
     function xls_bof() 
     {
        echo pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
     }
     function xls_eof() 
     {
        echo pack("ss", 0x0A, 0x00);
     }
     function xls_write_number($Row, $Col, $Value) 
     {
        echo pack("sssss", 0x203, 14, $Row, $Col, 0x0);
        echo pack("d", $Value);
     }
     function xls_write_label($Row, $Col, $Value ) 
     {
        $L = strlen($Value);
        echo pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
        echo $Value;
     }
     function data_table($header, $data)
     {
        $index=1;
        $this->xls_write_label(1,0,"No");
        foreach($header as $col=>$isidata)
        { 
            $this->xls_write_label(1,$index,$isidata['text']);            
            $index++;
        }
        $index=2;
        if($data)
        {
            foreach($data as $row)
            {
                $this->xls_write_number($index,0,($index-1));
                $colke=1;
                foreach($header as $col=>$isidata)
                { 
                    if($isidata['tipe']=="number")
                    {
                        $this->xls_write_number($index,$colke,$row->{$isidata['filed']});
                    }
                    else
                    {
                        $this->xls_write_label($index,$colke,$row->{$isidata['filed']});
                    }              
                    $colke++;
                }                
                $index++;
            }
        }
        else
        {
            $this->xls_write_label($index,0,"Data tidak ada." );
        }
     }
     function header_rtf($filename="")
     {
         header("Pragma: public" );
         header("Expires: 0" );
         header("Cache-Control: must-revalidate, post-check=0, pre-check=0" );
         header("Content-Type: application/force-download" );
         header("Content-Type: application/doc");
         header("Content-Type: application/octet-stream" );
         header("Content-Type: application/download" );
         header("Content-Disposition: attachment;filename=".$filename.".doc " );
         header("Content-Transfer-Encoding: binary " );
     }
     function header_xls($filename="")
     {
         header("Pragma: public" );
         header("Expires: 0" );
         header("Cache-Control: must-revalidate, post-check=0, pre-check=0" );
         header("Content-Type: application/force-download" );
         header("Content-Type: application/xls");
         header("Content-Type: application/octet-stream" );
         header("Content-Type: application/download" );
         header("Content-Disposition: attachment;filename=".$filename.".xls " );
         header("Content-Transfer-Encoding: binary " );
     }
     function header_txt($filename="")
     {
         header("Pragma: public" );
         header("Expires: 0" );
         header("Cache-Control: must-revalidate, post-check=0, pre-check=0" );
         header("Content-Type: application/force-download" );
         header("Content-Type: application/doc");
         header("Content-Type: application/octet-stream" );
         header("Content-Type: application/download" );
         header("Content-Disposition: attachment;filename=".$filename.".txt " );
         header("Content-Transfer-Encoding: binary " );
     }
 }
 