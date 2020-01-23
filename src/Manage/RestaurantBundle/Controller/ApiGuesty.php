<?php

namespace Manage\RestaurantBundle\Controller;



class ApiGuesty {
  //  private $base_url = "https://superhostuser.herokuapp.com/api/v2/";
    private $base_url = "https://api.guesty.com/api/v2/";
    private $user = "1557f8d8d289daced94570a67fd3c81a";
    private $pass = "e029cb29fecb6ef9d3bc315eff7ee26c";
    private $accountId = "58a5d7f18687ec10007b02c4";

    public function conect($request, $array_to_send = array(), $method = 'GET'){
        $curl_request = curl_init();
        curl_setopt($curl_request, CURLOPT_URL, $this->base_url.$request);
        curl_setopt($curl_request, CURLOPT_VERBOSE, 1);
        curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_request, CURLOPT_CUSTOMREQUEST, $method);
        if (count($array_to_send) > 0)
            curl_setopt($curl_request, CURLOPT_POSTFIELDS, http_build_query($array_to_send));
        
        curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($curl_request, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl_request, CURLOPT_USERPWD, "$this->user:$this->pass");
        if ($method == 'GET')
            curl_setopt($curl_request, CURLOPT_HTTPHEADER, array('Content-Type: application/json',));
        $result = curl_exec($curl_request); // execute the request
        $status_code = curl_getinfo($curl_request, CURLINFO_HTTP_CODE);
        curl_close($curl_request);
        $a = json_decode($result,true);
        return array('result'=>$a, 'status' => $status_code);
    }

    //Obtener los checkin correspondientes a la fecha que se pasa por parámetro
    public function checkin($date=NULL){
        if (is_null($date)) $date = 'now';
        $campos = array('fields'=> 'checkIn checkOut confirmationCode guest.fullName listing.title status source nightsCount guestsCount notes.guest guest.notes guest.email guest.phone money.fareAccommodation money.invoiceItems money.balanceDue money.hostPayout canceledAt', 'limit'=>'100' );
        return $this->conect('reservations/?'.http_build_query($campos));
    }

    //Obtener los checkin correspondientes a la fecha que se pasa por parámetro
    public function canceledcheckin($date=NULL){
        if (is_null($date)) $date = 'now';
        $campos = array(
            'fields'=> 'checkIn confirmationCode guest.fullName listing.title status source nightsCount guestsCount notes.guest guest.notes guest.email canceledAt',
            'filters' => "[{field:'status', operator:'\$in', value:['canceled']}]",
            'limit'=>'100' );

        return $this->conect('reservations/?'.http_build_query($campos));
    }

    //Obtener los checkout correspondientes a la fecha que se pasa por parámetro
    public function checkuot($skip){


        return $this->conect('reservations/?viewId=5ad4bcbe3613ce002b4d2af0&limit=100');

        //return $this->conect('views/5ad4bcbe3613ce002b4d2af0');
    }

    //Obtener el listado actualizado de las habitaciones disponibles en guesty
    public function listinglist(){
        $array_to_send  = array(
            "accountId"     =>  $this->user,
            "active"            =>  true,
            "fields"            =>  "title publicDescription tags"
        );
       return $this->conect('listings');
    }

    //Obtener una reservación
    public function reservation($id){

        return $this->conect('reservations/'.$id);
    }


    //Obtener una reservación
    public function reservationMoney($id){
        $array_to_send  = array(

            "fields"            =>  "money"
        );
        return $this->conect('reservations/'.$id.'/?fields=money');
    }

    //Obtener una reservación
    public function guest($id){
       return $this->conect('guests/'.$id);
    }

    //Obtener un source en guesty
    public function integration($id){
        return $this->conect('integrations/'.$id);
    }
    public function integrations(){
        $array_to_send  = array(
            "accountId"     =>  $this->user,
        );
        return $this->conect('/integrations');
    }

    //Obtener Listing calendar
    public function getListingCalendar($idListing, $from, $to){
        ///listings/:id/calendar
        return $this->conect('listings/'.$idListing.'/calendar/?from='.$from.'&to='.$to);
    }

    //Obtener Listings calendar
    public function getMultipleCalendar($listings, $from, $to){
        $data = array('ids'=> $listings, 'from'=> $from, 'to'=>$to);
        return $this->conect('listings/calendars/?'.http_build_query($data));
    }

    //Obtener Listing calendar
    public function listingtag($id){
        ///listings/:id/calendar
        $campos = array(
            'fields'=> 'tags title',
            'limit'=>'100' );

        //return $this->conect('reservations.csv/?'.http_build_query($campos));
        return $this->conect('listings/'.$id.'/?'.http_build_query($campos));
    }

    public function setListingCalendar($data)
    {
        return $this->conect('/listings/calendars', $data, 'PUT');
    }

    public function setAutopricing($listing, $data){
        return $this->conect('/listings/'.$listing,$data, 'PUT');
    }

    public function getStatus($number){
        switch ($number){
            case 200:
                return 'Success';
                break;
            default:
                return "Error";
        }
    }

    public function createHook($url, $events = array()){
        $campos = array('url'=>$url, 'accountId'=>$this->accountId, 'events'=>$events);
        return $this->conect('/webhooks', $campos, 'POST');
    }

    public function updateHook($id, $url, $events = array()){
        $campos = array('url'=>$url, 'events'=>$events);
        return $this->conect('/webhooks/'.$id, $campos, 'PUT');
    }

    public function getWebhooks(){
        return $this->conect('/webhooks');
    }

    /**
     * Actualizar el estado de la limpieza del departamento
     * */
    public function setCleaningStatus($id, $status){
        $campos = array('cleaningStatus'=>array("value"=>$status, "updatedAt"=>date("Y-m-d H:i:s")));
        return $this->conect("/listings/".$id, $campos, "PUT");
    }

    /**
     * Conocer el estado de la limpieza de los departamento
     * */
    public function getCleaningStatus(){   ///listings/:id/calendar
        $campos = array(
            'fields'=> 'cleaning cleaningStatus',
            'limit'=>'100' );

        return $this->conect('listings/?'.http_build_query($campos));
    }




}
