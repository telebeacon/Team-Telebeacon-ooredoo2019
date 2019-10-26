$(document).ready(function(e) {

    var windowname = "main";
    var orderCount=0;
    var container=$('#main');
    var aktifSiparis;
    var globData;

    
    var orders_template = $('#orders').html();
    var orderdetail_template = $('#orderdetail').html();
    Mustache.parse(orders_template);
    Mustache.parse(orderdetail_template);
    



 $.ajax({
  url: "http://kiwi.eercan.com/api/siparisler.api.php",
  dataType: "json",
  success: function (data) {
    globData = data;
    renderOrders({'orders':data});
  },
  error:function(data){


  }
});

   
    setInterval(function(){ 

        if(windowname=="main" && orderCount!=data.length){

          //  data = [{"siparis_id":"1","kullanici_id":"1","kullanici_bilgi":{"isim":"Emir Ercan Ayar","eposta":"eercanayar@gmail.com","telefon":"05445742192"},"siparis_veri":[{"veri_id":"1","siparis_id":"1","miktar":"2","urun_tutari":24,"urun_bilgi":{"urun_id":"1","kategori_id":"1","isim":"Izgara K\u00f6fte","fiyat":"12","gorsel":"izgara_kofte.jpg"}},{"veri_id":"2","siparis_id":"1","miktar":"3","urun_tutari":48,"urun_bilgi":{"urun_id":"2","kategori_id":"1","isim":"Tavuk Pirzola","fiyat":"16","gorsel":"tavuk_pirzola.png"}}],"teslim_saat":"1419651707","siparis_saat":"1419651107","durum":"1"}]
           
             $.ajax({
                  url: "http://kiwi.eercan.com/api/siparisler.api.php",
                  dataType: "json",
                  success: function (data) {
                    globData = data;
                    renderOrders({'orders':data});
                  },
                  error:function(data){


                  }
                });

        }

    }, 3000);

    
   // renderOrders(data);
    
    
    
    function renderOrders(d) {
        var rendered = Mustache.render(orders_template,d);
        container.hide();
        container.html(rendered);
        container.fadeIn(500);
        windowname="main";
        orderCount=globData;
    }

   function renderDetail(d) {
    console.log(d);
        aktifSiparis = d.products[0].siparis_id;
        var rendered = Mustache.render(orderdetail_template,d);
        container.hide();
        container.html(rendered);
        container.fadeIn(400);
        windowname="detail";
    }




$(document).on('click','#main_menu',function(){
    gotoMain();
});



function gotoMain(){
    renderOrders({'orders':globData});
    windowname="main";
}


$(document).on('click','#orders_div a',function(){

    container.html(""); // clear

    var id = $(this).attr('data-id');
 
    //console.log(data);
    //console.log(data);
    for(var i = 0; i< globData.length;i++){

       // alert(globData[i].siparis_id+"  - -  "+id);
        if(globData[i].siparis_id==id){

            var dt = globData[i].siparis_veri;
        
            renderDetail({products:dt});
            break;
        }
    }









});




    $(document).on('click','#urunEkle',function(){
            console.log("zaa");
            var order = new newOrder();
        
            order.updateData(function(){
                order.render();
                $('#urunekleModal').modal('show');        
            });


    });



    $('#urunekleModal').on('click','.caption a',function(){
            var id = $(this).attr('data-id');
            console.log(globData);
             $.get("http://kiwi.eercan.com/api/siparise_ekle.api.php",{urun_id:id,siparis_id:aktifSiparis},
    
                   function () {
                        alert("ekledim");


                  } ,'json');


    });



});






function newOrder(sepet){
    
    
    this.container=$('#urunekleModal .modal-body');
    
    
    this.container.on('click','.nav-pills a',function(e) {
        e.preventDefault();
        
        var obj=$(this);
        var cat_id=obj.attr('data-id');
        
        $('.nav-pills').find('.active').removeClass('active');
        obj.parent().addClass('active'); 
        
        if(cat_id==="0")
        {
            $('#new_order_urunler .col-md-3').removeClass('hide');
        }
        else {
            

            $('#new_order_urunler .col-md-3').addClass('hide');
            $('#new_order_urunler .kategori'+cat_id).removeClass('hide');
        
        }
        
        
    });
    
    this.data={
        'kategoriler':[],
        'urunler':[],
        'sepet':[]
    };
    
    if(sepet)
        data["sepet"]=sepet;
    
    
    this.new_order_template = $('#new_order').html();
    
    Mustache.parse(this.new_order_template);
    
    
}


newOrder.prototype.updateData=function(callback) {
    var that=this;
    console.log(this);
    
    var done=0;
    
    $.ajax({
        url: 'http://kiwi.eercan.com/api/urun_kategori.api.php',
        dataType: "json",
        success: function (data) {
            console.log(data)
            that.data['kategoriler']=data;
            cb();
            
        }
    });
    
    $.ajax({
        url: 'http://kiwi.eercan.com/api/urunler.api.php',
        dataType: "json",
        success: function (data) {
            console.log(data)
            that.data['urunler']=data;
            cb();
        }
    });
    
    function cb() {
        done++;
        if(done==2)
            callback();
    }
    
}


newOrder.prototype.render=function() {
    var rendered = Mustache.render(this.new_order_template,this.data);
    this.container.html(rendered);
}
