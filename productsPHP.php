<?php
class Constants
{
    //DATABASE DETAILS
    static $DB_SERVER="localhost";
    static $DB_NAME="bebeburada";
    static $USERNAME="root";
    static $PASSWORD="";
    //STATEMENTS
    static $SQL_SELECT_ALL="SELECT * FROM urunler";
}
class Spacecrafts
{
    /*******************************************************************************************************************************************/
    /*
       1.CONNECT TO DATABASE.
       2. RETURN CONNECTION OBJECT
    */
    public function connect()
    {
        $con=new mysqli(Constants::$DB_SERVER,Constants::$USERNAME,Constants::$PASSWORD,Constants::$DB_NAME);
        if($con->connect_error)
        {
            // echo "Unable To Connect"; - For debug
            return null;
        }else
        {
            //echo "Connected"; - For debug
            return $con;
        }
    }
    /*******************************************************************************************************************************************/
    /*
       1.SELECT FROM DATABASE.
    */
    public function select()
    {
        $con=$this->connect();
        if($con != null)
        {
            $result=$con->query(Constants::$SQL_SELECT_ALL);
            if($result->num_rows>0)
            {
                $spacecrafts=array();
                $imageArray=array();


                while($row=$result->fetch_array())
                {

                	$stokKODU=$row["stokkodu"];
                	$imageSQL="SELECT * FROM urunfoto WHERE stokkodu='$stokKODU'";
                	$resultImg=$con->query($imageSQL);
                	$rowImage=$resultImg->fetch_array();

                	// while ($rowImage=$resultImg->fetch_array()) {
                		
                	// 	array_push($imageArray, array($rowImage["fotoyolu"]));
                	// }


                    array_push($spacecrafts, array("id"=>$row["urunID"],"name"=>$row['urunAdi'],"stokkodu"=>$row['stokkodu'], "image_url"=>$rowImage['fotoyolu'],"fiyat"=> $row['fiyat'],"stokadet"=>$row['adet'],"ay"=>$row['ay'],
                   ));
  
                    	
                }

                print(json_encode(array_reverse($spacecrafts)));
            }
            else
            {
                print(json_encode(array("PHP EXCEPTION : CAN'T RETRIEVE FROM MYSQL. ")));
            }
            $con->close();
        }else{
            print(json_encode(array("PHP EXCEPTION : CAN'T CONNECT TO MYSQL. NULL CONNECTION.")));
        }
    }
}
$spacecrafts=new Spacecrafts();
$spacecrafts->select();
?>