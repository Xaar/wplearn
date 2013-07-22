<?php
/*
Template Name: map_new
*/
?>

<?php get_header(); ?>

<div id="content" class="hero-content row clear-nav" role="main">
  	<div class="page-title row">

		<h1>Map Test</h1>
		
	</div>

	<div class="about-leftcol-wrapper" id="mapwidth">
		<h2 class="heading-leftcol">Find a Heartworks Distributor</h2>
		<p>Click on the map to find a distributor in your region.
  </p>


  

  <script type="text/javascript">
  function set_places() {
    var places_setup = {
        0: {
          name: "Japan",
          distributor: "Nihon Light Inc",
          address: "3-42-1 Hongo,<br>Bunkyo-ku,<br>Tokyo,<br>113-0033,<br>Japan",
          email: "hiroshi@nlsinc.co.jp",
          color: "green",
          radius: 25,
          xpos: 0.83,
          ypos: 0.25
        },
        1: {
          name: "Indonesia",
          distributor: "PT INTERGASTRA",
          address: "Jl. P. Jayakarta,<br>24/31-35,<br>Jakarta10730,<br>Indonesia",
          email: "karli@intergastra.co.id",
          color: "green",
          radius: 25,
          xpos: 0.78,
          ypos: 0.57
        },
        2: {   
          name: "China",
          distributor: "V-MedEd",
          address: "Beijing,<br>China",
          email: "michelle.xiao@vmeded.com",
          color: "green",
          radius: 25,
          xpos: 0.71,
          ypos: 0.3
        },
        3: {
          name: "Australia",
          distributor: "Inition – Asia Pacific",
          address: "Factory 31,<br>91 Moreland Street,<br>Footscray,<br>VIC,3011,<br>Australia",
          email: "christopher.sutton@inition.com.au",
          color: "green",
          radius: 25,
          xpos: 0.85,
          ypos: 0.8
         },
        4: {
          name: "Middle_East",
          distributor: "EWG International",
          address: "P.O.Box 18475,Jebel Ali <br>Dubai UAE",
          email: "kassemtofailli@gmail.comkassemtofailli@gmail.com",
          color: "green",
          radius: 25,
          xpos: 0.55,
          ypos: 0.3
         }, 
         5: {  
          name: "Singapore_Malaysia",
          distributor: "United BMEC Pte Ltd",
          address: "No 2 Kim Chuan Drive,<br>06-01,CSI Distribution Centre,<br>Singapore,<br>537080",
          email: "chngken.bmec@uwhpl.com",
          color: "green",
          radius: 25,
          xpos: 0.72,
          ypos: 0.37
         }, 
         6: {  
          name: "India",
          distributor: "Prakash Medicos",
          address: "WZ-428C Nangal Raya,<br>New Delhi,<br>110046",
          email: "prakashmedicos@gmail.com",
          color: "green",
          radius: 25,
          xpos: 0.67,
          ypos: 0.4
         }, 
        7: { 
          name: "Thailand",
          distributor: "Berli Jucker Public Company Limited",
          address: "Berli Jucker House, 99 <br>Soi Rubia, Sukhumvit 42 Road,<br>Phrakanong, Klongtoey,<br>Bangkok 10110,<br>Thailand",
          email: "PornchaK@bjc.co.th",
          color: "green",
          radius: 25,
          xpos: 0.75,
          ypos: 0.44
         }, 
        8: {
          name: "South_Korea",
          distributor: "KyongDo Medical Simulation Corp",
          address: "ShinWol B/D #202,<br>347-2,ShinDaeBang-Dong,<br>Dongjak-Gu and Seoul,<br>Korea 156-847",
          email: "leekd9595@hanmail.net",
          color: "green",
          radius: 25,
          xpos: 0.78,
          ypos: 0.3
         },
        9: {
          name: "Ireland",
          distributor: "Cardiac Services",
          address: "Dublin",
          email: "something",
          color: "green",
          radius: 25,
          xpos: 0.38,
          ypos: 0.13
        }      
  }
  return places_setup;
}

function map_init () {
    var mapwidth = $('#mapwidth').width();
    var mapheight=mapwidth*0.6;
    $('#map_new').css('height' , mapheight);
    var places = set_places();
    var info_place = $('#distributor_info h2');
    var info_name = $('#distributor_info h3');
    var info_address = $('#distributor_info p.addy');
    var info_email =$('#distributor_info a');
    var panel=$('#distributor_info');

       
    
  /*  for (var place in places) {

        var pl=places[place];
        var pl_xpos=mapwidth*pl.xpos;
        var pl_ypos=mapheight*pl.ypos;
        var pl_size=pl.radius;
        var pl_name=pl.name;
        var marker= $('#map_new').prepend("<div id='pl_"+pl_name+"' class='dot' style='position: absolute; left:"+pl_xpos+"px; top:"+pl_ypos+"px;display: block; z-index: 1000; padding:10px; float:left; width:"+pl_size+"px;height:"+pl_size+"px;'></div>");
        var pl_paper=Raphael(document.getElementById('pl_'+pl_name), pl_size, pl_size);

       // paper.circle(320, 240, 60).animate({fill: "#223fa3", stroke: "#000", "stroke-width": 80, "stroke-opacity": 0.5}, 2000);

        var pl_circle = pl_paper.circle(pl_size/2, pl_size/2, pl_size/3)
                      .attr({fill: "#fff" , stroke:"#fff" , "fill-opacity": 0 , "stroke-width": 30 , "stroke-opacity":0, "cursor":"pointer"})
                      .animate({fill: ""+pl.color+"" , "fill-opacity": 1 , "stroke-width": 4 , "stroke-opacity":0.8 },1000,'backOut')
                      .data("id", pl_name)
                      .click(function (event) {
                            setInfo(this.data("id"));
                            event.stopPropagation();
                         })
                      .mouseover(function () {
                            this.animate({fill: "#c00"} , 300);
                            
                         })
                      .mouseout(function () {
                            this.animate({fill: ""+pl.color+""}, 300)
                         });*/

        var place=0;
        var str=String(places); 
        var repeat=str.length
        console.log(places);
        console.log(repeat);

        function createPlace() {

          if (place<(repeat-5)) {

        var pl=places[place];
        var pl_xpos=mapwidth*pl.xpos;
        var pl_ypos=mapheight*pl.ypos;
        var pl_size=pl.radius;
        var pl_name=pl.name;
        var marker= $('#map_new').prepend("<div id='pl_"+pl_name+"' class='dot' style='position: absolute; left:"+pl_xpos+"px; top:"+pl_ypos+"px;display: inline; z-index: 1000; padding:10px; float:left; width:"+pl_size+"px;height:"+pl_size+"px;'></div>");
        var pl_paper=Raphael(document.getElementById('pl_'+pl_name), pl_size, pl_size);

       // paper.circle(320, 240, 60).animate({fill: "#223fa3", stroke: "#000", "stroke-width": 80, "stroke-opacity": 0.5}, 2000);

        var pl_circle = pl_paper.circle(pl_size/2, pl_size/2, pl_size/3)
                      .attr({fill: "#fff" , stroke:"#fff" , "fill-opacity": 0 , "stroke-width": 30 , "stroke-opacity":0, "cursor":"pointer"})
                      .animate({fill: ""+pl.color+"" , "fill-opacity": 1 , "stroke-width": 4 , "stroke-opacity":0.8 },1000,'backOut')
                      .data("id", pl_name)
                      .click(function (event) {
                            setInfo(this.data("id"));
                            event.stopPropagation();
                         })
                      .mouseover(function () {
                            this.animate({fill: "#c00"} , 300);
                            
                         })
                      .mouseout(function () {
                            this.animate({fill: ""+pl.color+""}, 300)
                         });   

            
               place++; 
               intervalTimeOut();
             } else {
              console.log('else');
             }
                     
    }

    function intervalTimeOut () {
           
         console.log(place);      
        window.setTimeout(createPlace, 200)
       
    
  }

  intervalTimeOut();

    

    function setInfo(id) {
      
      for (var place in places) {
        var pla=places[place];
        planame=pla.name;        
        var distributor=pla.distributor;
        var address=pla.address;
        var email=pla.email;
        

        if (planame==id) {
          info_place.html(planame);
          info_name.html(distributor);
          info_address.html(address);
          info_email.html(email);
          $('.hidden').fadeToggle("fast");
          $('.hidden').addClass('visible');
          $('.visible').removeClass('hidden');
        }

      }
     

    }

 
     $('.click_close').click(function (e) {
     
      if (e.target == this) {
         
         $('.visible').fadeToggle("fast");
          $('.visible').addClass('hidden');
          $('.hidden').removeClass('visible');
         
        }
          
    });


    
}



   function map_resize() {
    var mapwidth = $('#mapwidth').width();
    var mapheight=mapwidth*0.6;
    $('#map_new').css('height' , mapheight);
    var places = set_places();
    for (var place in places) {
      var pl=places[place];
      var pl_xpos=mapwidth*pl.xpos;
      var pl_ypos=mapheight*pl.ypos;
      var pl_name=pl.name;
      $('#pl_'+pl_name).css({'left' : pl_xpos+'px' , 'top' : pl_ypos+'px' });  

      
    }
    //alert (pl_name);

  }

  $(document).ready(function () {
    map_init();
  });


$(window).resize(function () {
      map_resize();
      
    });

/*for the dropdown menu*/

function selectLoc (locId) {
  var places = set_places();

   for (var place in places) {
        var info_place = $('#distributor_info h2');
        var info_name = $('#distributor_info h3');
        var info_address = $('#distributor_info p.addy');
        var info_email =$('#distributor_info a');
        var panel=$('#distributor_info');

        var pla=places[place];
        planame=pla.name;        
        var distributor=pla.distributor;
        var address=pla.address;
        var email=pla.email;
        

        if (planame==locId) {
          info_place.html(planame);
          info_name.html(distributor);
          info_address.html(address);
          info_email.html(email);
          $('.hidden').fadeToggle("fast");
          $('.hidden').addClass('visible');
          $('.visible').removeClass('hidden');
        }
         //$("#pl_"+locId).circle.node.animate({fill: "#c00"} , 300);

         var target = $('#pl_'+locId+' circle.node');

        console.log(svgElem);
        target.animate({fill: "#c00"} , 300);
       // var div = svgElem.parentNode;

      }



}


  </script>

  <select name="aa" onchange="selectLoc(this.value)"> 
<option value="">Please select</option>
<option value="Australia">Australia</option>
<option value="Japan">Japan</option>
</select>
  <div id="map_new">
    <div id="distributor_info" class="hidden">
      <h2>Region Name</h2>
      <h3>Distributor Name</h3>
      <p class="addy">Address line one</p>
      <a href="">email Address</a>
    </div>
    <img class="click_close" src="<?php bloginfo('template_directory'); ?>/images/iml_map.png">
    
 </div>
   

 

</div>
<div class="sidebar-wrapper">
<?php
  get_sidebar('upcoming-events');
  get_sidebar('news');
?>
  </div> <!-- sidebar wrapper -->

<?php get_footer(); ?>
