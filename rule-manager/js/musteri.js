$(document).ready(function(e) {


	var sepet=$('#sepet');
	var main_order_template = $('#main_order').html();


	Mustache.parse(main_order_template);




     function renderAll(d) {
     	 var sum = calculateSum(d);
     	 d.total = sum;

        var rendered = Mustache.render(main_order_template,d);
        sepet.html(rendered);
    }



    var c = 0;
    var boxes = {
    				total : 0,
    				items :[]

  			    }

  	renderAll(boxes);


	$('.yeniekleme').on('click',function(){

		var data = {
				id:c,
				isim:'elma'+c,
				birim:3,
				miktar: 4,
				fiyat:12


		}
		//boxes.items.push(data);
		//renderAll(boxes);
		c++;
	});


	$('#sepet .delete').click(function(){
		var id = $(this).attr('data-id');
		removeValue(id);
		renderAll(boxes);

	});

	$('#sepet .amountchange').click(function(){
		var id = $(this).attr('data-id');
		var type = $(this).attr('data-type');
		var item = findItem(id);

		if(type=="+"){
			item.miktar++;
			item.fiyat += item.birim;
		}else if(type=="-"){
			if(item.miktar>1){
				item.miktar--;
				item.fiyat -= item.birim;
			}else{

				removeValue(id);
			}
			

		}
		

		renderAll(boxes);
	});


	function findItem(id){
		 for(var i = 0; i < boxes.items.length; i++) {
	        if(boxes.items[i].id == id) {
	           return boxes.items[i];
	        }
	    }

	}



	function removeValue(value) {
	    for(var i = 0; i < boxes.items.length; i++) {
	        if(boxes.items[i].id == value) {
	            boxes.items.splice(i, 1);
	            break;
	        }
	    }
	}

	function calculateSum(){
		var sum = 0;
		for(var i = 0;i<boxes.items.length;i++){
			sum+=boxes.items[i].miktar * boxes.items[i].birim;
		}

		return sum;
	}





});


