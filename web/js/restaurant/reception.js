$(document).ready(function() {
    //Visualizar campos con las anotaciones del formulario
    $('#form-basic-reception').on('click', '.form-reception-details', function() {
        var status = $('#form-reception-show-details').prop('hidden')
        $('#form-reception-show-details').prop('hidden', !status)
    })
    
    //Annadir dinamicamente los campos de los gift-values
    var saved_vouchers = JSON.parse($('#manage_restaurantbundle_reception_giftvouchers').val());
    for (var i = 0; i < saved_vouchers.length ; i++){        
        create_gift_voucher(saved_vouchers[i])        
        //$('fieldset.gift-vouchers').append('<li>' + saved_vouchers[i] + '</li>')
    }
    
    //Annadir dinamicamente los campos de los parqueos
    var saved_parking = JSON.parse($('#manage_restaurantbundle_reception_parking').val());
    for (var i = 0; i < saved_parking.length ; i++){
        create_parking(saved_parking[i])        
        //$('fieldset.gift-vouchers').append('<li>' + saved_vouchers[i] + '</li>')
    }
    
    //Funcion para crear cada campo de voucher
    function create_gift_voucher(data){
        var form_group = '<div class="row form-group">'+
                         '<label for="" class="">'+data.details+'</label>'+
                            '<input id="free-voucher-'+data.id+'" name="'+data.value+'" required="required" class="input-sm pull-right form-control gift-voucher" value="0" type="text">'+
                        '</div>'        
        $('#prepared-gift-form').append(form_group)
    }
    
    //Funcion para crear cada campo de parqueo
    function create_parking(data){
        var form_group = '<div class="row form-group">'+
                         '<label for="" class="">'+data.details+'</label>'+
                         '<input id="parking-'+data.id+'" name="'+data.value+'" required="required" class="input-sm pull-right form-control parking" value="0" type="text">'+
                        '</div>'        
        $('#prepared-parking-form').append(form_group)
    }
    
    //Llenar campo oculto con los valores de los vouchers y el parking
    $('input[type="submit"]').click(function (e){
        e.preventDefault();
        var giftvouchersvalues = new Array();
        $('.gift-voucher').each(function(index, item){
            giftvouchersvalues[index] = $(item).val()
        })
        $('#manage_restaurantbundle_reception_giftvouchersvalues').val(giftvouchersvalues.toString());
        var parkingvalues = new Array();
        $('.parking').each(function(index, item){
            parkingvalues[index] = $(item).val()
        })
        $('#manage_restaurantbundle_reception_parkingvalues').val(parkingvalues.toString());
        $('#form-basic-reception').submit();
    })
    
    //Calculo automatico Total vouchers diarios
    function updateVD(){
        var vd = (Number($('#manage_restaurantbundle_reception_vdstart').val())
                 - Number($('#manage_restaurantbundle_reception_vdend').val()))
       $('#manage_restaurantbundle_reception_vdamount').val(vd);   
    }
    function updateVN(){
        var vn = (Number($('#manage_restaurantbundle_reception_vnstart').val())
                 - Number($('#manage_restaurantbundle_reception_vnend').val()))
       $('#manage_restaurantbundle_reception_vnamount').val(vn);   
    }
    function updateDN(){
        var dn = (Number($('#manage_restaurantbundle_reception_vdamount').val())
                 + Number($('#manage_restaurantbundle_reception_vnamount').val()))
       $('#manage_restaurantbundle_reception_generalamount').val(dn);   
    }
    function updateProfit(){
        var profit = ((Number($('#manage_restaurantbundle_reception_generalamount').val())
                    - Number($('#manage_restaurantbundle_reception_freevoucher').val()))
                    * Number($('#manage_restaurantbundle_reception_voucher').val()))
                    + (Number($('#manage_restaurantbundle_reception_halfprice').val())
                    * Number($('#manage_restaurantbundle_reception_voucher').val()) / 2)
        $('#manage_restaurantbundle_reception_profit').val(profit)
    }
    
    $('.reception-general').on('keyup', '.form-control', function() {
        updateVD()
        updateVN()   
        updateDN()
        updateProfit()
    })
    
    //Calculo de tipos de vouchers
   
    function updateFreeVouchers(){  
         var total = 0;
        $('input.gift-voucher').each(function(){
            total += Number($(this).val())*Number($(this).attr('name'))
        })
        return total
    }
    $('fieldset.gift-vouchers').on('keyup', 'input.gift-voucher', function() {
        
        $('#manage_restaurantbundle_reception_giftvoucherstotal').val(updateFreeVouchers())
    })
//    $('gift-voucher')
})
