<?php

namespace Manage\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * Kasboek
 * @ORM\Table()
 * @ORM\Entity()
 *
 */
class KasboekOut {

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="pinccv", type="float", nullable=true)
     */
    private $pinccv;
    
    
   /**
     * @ORM\Column(name="creditcards", type="float", nullable=true)
     */
    private $creditcards;
    
    /**
     * @ORM\Column(name="rekening", type="float", nullable=true)
     */
    private $rekening;
    
    /**
     * @ORM\Column(name="voorverkoop", type="float", nullable=true)
     */
    private $voorverkoop;
    
     /**
     * @ORM\Column(name="kadopagina", type="float", nullable=true)
     */
    private $kadopagina;
    
    /**
     * @ORM\Column(name="tickets", type="float", nullable=true)
     */
    private $tickets;
    
    /**
     * @ORM\Column(name="belevenissen", type="float", nullable=true)
     */
    private $belevenissen;
    
    /**
     * @ORM\Column(name="cash", type="float", nullable=true)
     */
    private $cash;
    
    /**
     * @ORM\Column(name="inkoopfood", type="float", nullable=true)
     */
    private $inkoopfood;

 /**
     * @ORM\Column(name="exinkoopfood", type="float", nullable=true)
     */
    private $exinkoopfood;

 /**
     * @ORM\Column(name="btwinkoopfood", type="float", nullable=true)
     */
    private $btwinkoopfood;


    /**
     * @ORM\Column(name="bedrijfskleding", type="float", nullable=true)
     */
    private $bedrijfskleding;

    /**
     * @ORM\Column(name="exbedrijfskleding", type="float", nullable=true)
     */
    private $exbedrijfskleding;

    /**
     * @ORM\Column(name="btwbedrijfskleding", type="float", nullable=true)
     */
    private $btwbedrijfskleding;

    /**
     * @ORM\Column(name="kleineinv", type="float", nullable=true)
     */
    private $kleineinv;

    /**
     * @ORM\Column(name="exkleineinv", type="float", nullable=true)
     */
    private $exkleineinv;

    /**
     * @ORM\Column(name="btwkleineinv", type="float", nullable=true)
     */
    private $btwkleineinv;

    /**
     * @ORM\Column(name="was", type="float", nullable=true)
     */
    private $was;

    /**
     * @ORM\Column(name="exwas", type="float", nullable=true)
     */
    private $exwas;

    /**
     * @ORM\Column(name="btwwas", type="float", nullable=true)
     */
    private $btwwas;

    /**
     * @ORM\Column(name="bankkosten", type="float", nullable=true)
     */
    private $bankkosten;

    /**
     * @ORM\Column(name="entertainment", type="float", nullable=true)
     */
    private $entertainment;

    /**
     * @ORM\Column(name="exentertainment", type="float", nullable=true)
     */
    private $exentertainment;

    /**
     * @ORM\Column(name="btwentertainment", type="float", nullable=true)
     */
    private $btwentertainment;

    /**
     * @ORM\Column(name="diversekosten", type="float", nullable=true)
     */
    private $diversekosten;

    /**
     * @ORM\Column(name="exdiversekosten", type="float", nullable=true)
     */
    private $exdiversekosten;

    /**
     * @ORM\Column(name="btwdiversekosten", type="float", nullable=true)
     */
    private $btwdiversekosten;

    /**
     * @ORM\Column(name="totalout", type="float", nullable=true)
     */
    private $totalout;

    /**
     * @ORM\Column(name="extotalout", type="float", nullable=true)
     */
    private $extotalout;

    /**
     * @ORM\Column(name="btwtotalout", type="float", nullable=true)
     */
    private $btwtotalout;

    /**
     * @ORM\Column(name="saldo", type="float", nullable=true)
     */
    private $saldo;


    public function getId() {
        return $this->id;
    }

    public function getPinccv() {
        return $this->pinccv;
    }

    public function setPinccv ($pinccv) {
        $this->pinccv = $pinccv;
        return $this;
    }
    
    public function getCreditcards() {
        return $this->creditcards;
    }

    public function setCreditcards ($creditcards) {
        $this->creditcards = $creditcards;
        return $this;
    }
    public function getRekening() {
        return $this->rekening;
    }

    public function setRekening ($rekening) {
        $this->rekening = $rekening;
        return $this;
    }
    public function getVoorverkoop() {
        return $this->voorverkoop;
    }

    public function setVoorverkoop ($voorverkoop) {
        $this->voorverkoop = $voorverkoop;
        return $this;
    }
    
     public function getKadopagina () {
        return $this->kadopagina;
    }

    public function setKadopagina ($v) {
        $this->kadopagina = $v;
        return $this;
    }
    
    public function getTickets () {
        return $this->tickets;
    }

    public function setTickets ($v) {
        $this->tickets = $v;
        return $this;
    }
    
    public function getBelevenissen () {
        return $this->belevenissen;
    }

    public function setBelevenissen ($v) {
        $this->belevenissen = $v;
        return $this;
    }
    
    public function getCash () {
        return $this->cash;
    }

    public function setCash ($v) {
        $this->cash = $v;
        return $this;
    }
    
    public function getInkoopfood () {
        return $this->inkoopfood;
    }

    public function setInkoopfood ($v) {
        $this->inkoopfood = $v;
        return $this;
    }

    public function getExinkoopfood(){
        return $this->exinkoopfood;
    }

    public function setExinkoopfood($exinkoopfood){
        $this->exinkoopfood = $exinkoopfood;
        return $this;
    }

    public function getBtwinkoopfood(){
        return $this->btwinkoopfood;
    }

    public function setBtwinkoopfood($btwinkoopfood){
        $this->btwinkoopfood = $btwinkoopfood;
        return $this;
    }

    public function getBedrijfskleding() {
        return $this->bedrijfskleding;
    }

    public function setBedrijfskleding ($bedrijfskleding) {
        $this->bedrijfskleding = $bedrijfskleding;
        return $this;
    }

    public function getExbedrijfskleding(){
        return $this->exbedrijfskleding;
    }

    public function setExbedrijfskleding($exbedrijfskleding){
        $this->exbedrijfskleding = $exbedrijfskleding;
        return $this;
    }

    public function getBtwbedrijfskleding(){
        return $this->btwbedrijfskleding;
    }

    public function setBtwbedrijfskleding($btwbedrijfskleding){
        $this->btwbedrijfskleding = $btwbedrijfskleding;
        return $this;
    }

    public function getKleineinv() {
        return $this->kleineinv;
    }

    public function setKleineinv ($kleineinv) {
        $this->kleineinv = $kleineinv;
        return $this;
    }

    public function getExkleineinv(){
        return $this->exkleineinv;
    }

    public function setExkleineinv($exkleineinv){
        $this->exkleineinv = $exkleineinv;
        return $this;
    }

    public function getBtwkleineinv(){
        return $this->btwkleineinv;
    }

    public function setBtwkleineinv($btwkleineinv){
        $this->btwkleineinv = $btwkleineinv;
        return $this;
    }

    public function getWas() {
        return $this->was;
    }

    public function setWas ($was) {
        $this->was = $was;
        return $this;
    }

    public function getExwas(){
        return $this->exwas;
    }

    public function setExwas($exwas){
        $this->exwas = $exwas;
        return $this;
    }

    public function getBtwwas(){
        return $this->btwwas;
    }

    public function setBtwwas($btwwas){
        $this->btwwas = $btwwas;
        return $this;
    }

    public function getBankkosten() {
        return $this->bankkosten;
    }

    public function setBankkosten ($bankkosten) {
        $this->bankkosten = $bankkosten;
        return $this;
    }

    public function getEntertainment () {
        return $this->entertainment;
    }

    public function setEntertainment ($v) {
        $this->entertainment = $v;
        return $this;
    }

    public function getExentertainment(){
        return $this->exentertainment;
    }

    public function setExentertainment($exentertainment){
        $this->exentertainment = $exentertainment;
        return $this;
    }

    public function getBtwentertainment(){
        return $this->btwentertainment;
    }

    public function setBtwentertainment($btwentertainment){
        $this->btwentertainment = $btwentertainment;
        return $this;
    }

    public function getDiversekosten () {
        return $this->diversekosten;
    }

    public function setDiversekosten ($v) {
        $this->diversekosten = $v;
        return $this;
    }

    public function getExdiversekosten(){
        return $this->exdiversekosten;
    }

    public function setExdiversekosten($exdiversekosten){
        $this->exdiversekosten = $exdiversekosten;
        return $this;
    }

    public function getBtwdiversekosten(){
        return $this->btwdiversekosten;
    }

    public function setBtwdiversekosten($btwdiversekosten){
        $this->btwdiversekosten = $btwdiversekosten;
        return $this;
    }

    public function getTotalout () {
        return $this->totalout;
    }

    public function setTotalout ($v) {
        $this->totalout = $v;
        return $this;
    }

    public function getExtotalout(){
        return $this->extotalout;
    }

    public function setExtotalout($extotalout){
        $this->extotalout = $extotalout;
        return $this;
    }

    public function getBtwtotalout(){
        return $this->btwtotalout;
    }

    public function setBtwtotalout($btwtotalout){
        $this->btwtotalout = $btwtotalout;
        return $this;
    }

    public function getSaldo () {
        return $this->saldo;
    }

    public function setSaldo ($v) {
        $this->saldo = $v;
        return $this;
    }

}
