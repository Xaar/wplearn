<?php
/*
Template Name: map_new
*/
?>

<?php get_header(); ?>

  <script type="text/javascript">

 $(document).ready( function() {

  function set_places() {
    var places_setup = {
      0: {
          uid: "Global1",
          name: "IML Global Sales Team",
          distributor: "Craig Henshaw",
          address: "Tel: +44 (0) 203 447 9360<br>Mob: +44 (0) 790 801 0253",
          email: "craig.henshaw@inventivemedical.com",
          color: "green",
          radius: 35,
          xpos: 0.18,
          ypos: 0.55
        },
        1: {
          uid: "Global2",
          name: "IML Global Sales Team",
          distributor: "Craig Henshaw",
          address: "Tel: +44 (0) 203 447 9360<br>Mob: +44 (0) 790 801 0253",
          email: "craig.henshaw@inventivemedical.com",
          color: "green",
          radius: 35,
          xpos: 0.48,
          ypos: 0.48
        },
        2: { 
          uid: "Global3", 
          name: "IML Global Sales Team",
          distributor: "Craig Henshaw",
          address: "Tel: +44 (0) 203 447 9360<br>Mob: +44 (0) 790 801 0253",
          email: "craig.henshaw@inventivemedical.com",
          color: "green",
          radius: 35,
          xpos: 0.64,
          ypos: 0.12
        },
        3: {
          uid: "Europe",
          name: "IML European Sales Team",
          distributor: "Jake Rahman",
          address: "Tel: +44 (0) 203 447 9360<br>Mob: +44 (0) 7956 338 317",
          email: "jake.rahman@inventivemedical.com",
          color: "orange",
          radius: 35,
          xpos: 0.42,
          ypos: 0.14
         },
        4: {
          uid: "America",
          name: "IML North American Sales Team",
          distributor: "Thomas Brown",
          address: "Tel: +44 (0) 203 447 9360<br>Mob: +44 (0) 790 801 0253",
          email: "thomas.brown@inventivemedical.com",
          color: "#0d7eef",
          radius: 35,
          xpos: 0.1,
          ypos: 0.18
         }   ,
        5: {
          uid: "Japan",
          name: "Japan",
          distributor: "Nihon Light Inc",
          address: "3-42-1 Hongo,<br>Bunkyo-ku,<br>Tokyo,<br>113-0033,<br>Japan",
          email: "hiroshi@nlsinc.co.jp",
          color: "#c00",
          radius: 25,
          xpos: 0.81,
          ypos: 0.2
        },
        6: {
          uid: "Indonesia",
          name: "Indonesia",
          distributor: "PT INTERGASTRA",
          address: "Jl. P. Jayakarta,<br>24/31-35,<br>Jakarta10730,<br>Indonesia",
          email: "karli@intergastra.co.id",
          color: "#c00",
          radius: 25,
          xpos: 0.78,
          ypos: 0.57
        },
        7: { 
          uid: "China", 
          name: "China",
          distributor: "V-MedEd",
          address: "Beijing,<br>China",
          email: "michelle.xiao@vmeded.com",
          color: "#c00",
          radius: 25,
          xpos: 0.74,
          ypos: 0.22
        },
        8: {
          uid: "Australia",
          name: "Australia",
          distributor: "Inition – Asia Pacific",
          address: "Factory 31,<br>91 Moreland Street,<br>Footscray,<br>VIC,3011,<br>Australia",
          email: "christopher.sutton@inition.com.au",
          color: "#c00",
          radius: 25,
          xpos: 0.85,
          ypos: 0.8
         },
        9: {
          uid: "Middle_East",
          name: "Middle East",
          distributor: "EWG International",
          address: "P.O.Box 18475,Jebel Ali <br>Dubai UAE",
          email: "kassemtofailli@gmail.comkassemtofailli@gmail.com",
          color: "#c00",
          radius: 25,
          xpos: 0.59,
          ypos: 0.33
         }, 
         10: { 
          uid: "Singapore_Malaysia",
          name: "Singapore and Malaysia",
          distributor: "United BMEC Pte Ltd",
          address: "No 2 Kim Chuan Drive,<br>06-01,CSI Distribution Centre,<br>Singapore,<br>537080",
          email: "chngken.bmec@uwhpl.com",
          color: "#c00",
          radius: 25,
          xpos: 0.77,
          ypos: 0.5
         }, 
         11: { 
          uid: "India",
          name: "India",
          distributor: "Prakash Medicos",
          address: "WZ-428C Nangal Raya,<br>New Delhi,<br>110046",
          email: "prakashmedicos@gmail.com",
          color: "#c00",
          radius: 25,
          xpos: 0.67,
          ypos: 0.4
         }, 
        12: { 
          uid: "Thailand",
          name: "Thailand",
          distributor: "Berli Jucker Public Company Limited",
          address: "Berli Jucker House, 99 <br>Soi Rubia, Sukhumvit 42 Road,<br>Phrakanong, Klongtoey,<br>Bangkok 10110,<br>Thailand",
          email: "PornchaK@bjc.co.th",
          color: "#c00",
          radius: 25,
          xpos: 0.75,
          ypos: 0.38
         }, 
        13: {
          uid: "South_Korea",
          name: "South Korea",
          distributor: "KyongDo Medical Simulation Corp",
          address: "ShinWol B/D #202,<br>347-2,ShinDaeBang-Dong,<br>Dongjak-Gu and Seoul,<br>Korea 156-847",
          email: "leekd9595@hanmail.net",
          color: "#c00",
          radius: 25,
          xpos: 0.795,
          ypos: 0.24
         },
        14: {
          uid: "Ireland",
          name: "Ireland",
          distributor: "Cardiac Services",
          address: "Dublin<br>www.cardiac-services.com",
          email: "g-dempsey@cardiac-services.com",
          color: "#c00",
          radius: 25,
          xpos: 0.38,
          ypos: 0.13
        },
        15: {
          uid: "Russia",
          name: "Russia",
          distributor: "MedRescue LLC",
          address: "Moscow, Russia<br>http://mirmanekenov.ru",
          email: "nikolaj.krogh.jensen@mirmanekenov.ru",
          color: "#c00",
          radius: 25,
          xpos: 0.5,
          ypos: 0.12
        },
        16: {
          uid: "Taiwan",
          name: "Taiwan",
          distributor: "Medsim Healthcare Education Ltd",
          address: "Hong Kong, Shanghai & Taiwan",
          email: "jaywalker4@gmail.com",
          color: "#c00",
          radius: 25,
          xpos: 0.804,
          ypos: 0.322
        },
        17: {
          uid: "Romania",
          name: "Romania",
          distributor: "Tehnoplus Medical",
          address: "Romania<br>www.tehnoplus.ro",
          email: "irina.ban@tehnoplus.ro",
          color: "#c00",
          radius: 25,
          xpos: 0.475,
          ypos: 0.19
        }  
         
  }
  return places_setup;
}




function map_init () {
    //rest the dropdown list
    $('#distributor-select').val('select');
    var mapwidth = $('.sales-tabs-wrapper').width();
    console.log(mapwidth);
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
     

        function createPlace() {

          if (place<18) {

        var pl=places[place];
        var pl_xpos=mapwidth*pl.xpos;
        var pl_ypos=mapheight*pl.ypos;
        var pl_size=pl.radius;
        var pl_name=pl.name;
        var pl_Id=pl.uid;
        var marker= $('#map_new').prepend("<div id='pl_"+pl_Id+"' class='dot' style='position: absolute; left:"+pl_xpos+"px; top:"+pl_ypos+"px;display: inline; z-index: 1000; padding:10px; float:left; width:"+pl_size+"px;height:"+pl_size+"px;'></div>");
        var pl_paper=Raphael(document.getElementById('pl_'+pl_Id), pl_size, pl_size);

       // paper.circle(320, 240, 60).animate({fill: "#223fa3", stroke: "#000", "stroke-width": 80, "stroke-opacity": 0.5}, 2000);

        var pl_circle = pl_paper.circle(pl_size/2, pl_size/2, pl_size/3)
                      .attr({fill: "#fff" , stroke:"#fff" , "fill-opacity": 0 , "stroke-width": 30 , "stroke-opacity":0, "cursor":"pointer"})
                      .animate({fill: ""+pl.color+"" , "fill-opacity": 1 , "stroke-width": 4 , "stroke-opacity":0.8 },1000,'backOut')
                      .data("id", pl_Id)
                      .click(function (event) {
                            resetDots();
                            setInfo(this.data("id"));
                            //this.attr({fill: "#c00"});
                            this.unhover();
                            event.stopPropagation();


                         })
                      .touchstart(function (event) {
                            resetDots();
                            setInfo(this.data("id"));
                            //this.attr({fill: "#c00"});
                            this.unhover();
                            event.stopPropagation();


                         })
                      .mouseover(function () {
                            //this.animate({fill: "#c00"} , 300);
                            
                         })
                      .mouseout(function () {
                            //this.animate({fill: ""+pl.color+""}, 300)
                         });   

            
               place++; 
               intervalTimeOut();
             } else {
              
             }
                     
    }

    function intervalTimeOut () {

        window.setTimeout(createPlace, 200)
 
  }

  intervalTimeOut();

    

    function setInfo(id) {
      
      for (var place in places) {
        var pla=places[place];
        planame=pla.name;   
        plaId=pla.uid;     
        var distributor=pla.distributor;
        var address=pla.address;
        var email=pla.email;

        

        if (plaId==id) {
          info_place.html(planame);
          info_name.html(distributor);
          info_address.html(address);
          info_email.attr("href", "mailto:"+email);
          $('.hidden').fadeToggle("fast");
          $('.hidden').addClass('visible');
          $('.visible').removeClass('hidden');
          var dropdown = $("#distributor-select");
          dropdown.val(this.plaId);
         

        }

      }
     

    }

 
     $('.click-close').click(function (e) {
     
      if (e.target == this) {
         
         $('.visible').fadeToggle("fast");
          $('.visible').addClass('hidden');
          $('.hidden').removeClass('visible');
          resetDots();
          resetSelect();

         
        }
          
    });


    
}



   function map_resize() {
    var mapwidth = $('#map_new').width();
    var mapheight=mapwidth*0.6;
    $('#map_new').css('height' , mapheight);
    var places = set_places();
    for (var place in places) {
      var pl=places[place];
      var pl_xpos=mapwidth*pl.xpos;
      var pl_ypos=mapheight*pl.ypos;
      var pl_name=pl.uid;
      $('#pl_'+pl_name).css({'left' : pl_xpos+'px' , 'top' : pl_ypos+'px' });  

      
    }
    //alert (pl_name);

  }


 map_init();

  /*var mapDrawn = false;

  $('.mapTab').click(function () {
    if(mapDrawn == false) {
      
      console.log('fired');
      mapDrawn = true;
    }

  });
*/


$(window).resize(function () {
      map_resize();
      
    });


/* Function to reset all hovers and active states */

function resetDots () {
  var places = set_places();

    for (var place in places) {
      var dotId=places[place];
      var dotName=dotId.uid;
      var dot = $('#pl_'+dotName+' circle');
      var col = dotId.color;

      dot.attr({"fill": col});

    }
  

}

function resetSelect () {
    $('#distributor-select').val('select');

}


/*for the dropdown menu*/

function selectLoc (locId) {
  resetDots();

  var places = set_places();

   for (var place in places) {
        var info_place = $('#distributor_info h2');
        var info_name = $('#distributor_info h3');
        var info_address = $('#distributor_info p.addy');
        var info_email =$('#distributor_info a');
        var panel=$('#distributor_info');

        var pla=places[place];
        planame=pla.name; 
        placeId=pla.uid;       
        var distributor=pla.distributor;
        var address=pla.address;
        var email=pla.email;
        

        if (placeId==locId) {
          info_place.html(planame);
          info_name.html(distributor);
          info_address.html(address);
          info_email.attr("href", "mailto:"+email);
          $('.hidden').fadeToggle("fast");
          $('.hidden').addClass('visible');
          $('.visible').removeClass('hidden');
        }
         //$("#pl_"+locId).circle.node.animate({fill: "#c00"} , 300);

         var target = $('#pl_'+locId+' circle');

        target.attr({"fill": "#c00"});
       

      }



}
  $(function() {
    $("ul.tabs").tabs("div.panes > div");
});

  $( "select" ).change(function () {
    
    selectLoc(this.value)

  });

  });




  </script>

  <div id="content" class="hero-content row clear-nav" role="main">

  <div class="page-title row">
    <h1>Contact us</h1>
  </div>

  <div class="sales-leftcol-wrapper">
    <div class="sales-tabs-wrapper">
      <div class="sales-tabs">
        <ul class="tabs">
      <li><a class="mapTab" href="#">Sales Coverage</a></li>
          <li><a href="#">Sales and Enquiries</a></li>
        </ul>
      </div><!-- sales-tabs -->

      <div class="panes">
             
        <!-- Enquiries -->
        <div class="pane pane-sales">
          
                     <h2>Global sales coverage</h2>
          <p>Click on the map to find a member of the IML team or a Heartworks distributor in your region</p>

  <select name="distributors" id="distributor-select"> 
<option value="select">Please select</option>
<option value="Global1">IML Global Sales</option>
<option value="Europe">IML European Sales</option>
<option value="America">IML North American Sales</option>
<option value="Australia">Australia</option>
<option value="China">China</option>
<option value="India">India</option>
<option value="Indonesia">Indonesia</option>
<option value="Ireland">Ireland</option>
<option value="Japan">Japan</option>
<option value="Middle_East">Middle East</option>
<option value="Romania">Romania</option>
<option value="Russia">Russia</option>
<option value="Singapore_Malaysia">Singapore and Malaysia</option>
<option value="South_Korea">South Korea</option>
<option value="Taiwan">Taiwan</option>
<option value="Thailand">Thailand</option>
</select>
  <div id="map_new">
    <div id="distributor_info" class="hidden">
      <div id="distributor-close" class="click-close"></div>
      <h2>Region Name</h2>
      <h3>Distributor Name</h3>
      <p class="addy">Address line one</p>
      <a>Send email &raquo;</a>
    </div>
    <img class="click-close" src="<?php bloginfo('template_directory'); ?>/images/iml_map.png">
    
 </div>   
  <div class="row">
        <div class="map-key">
          <h2>Key</h2>
          <ul>
        <li> <img src="<?php bloginfo('template_directory'); ?>/images/key-iml.png"><h3>- IML Sales teams</h3></li>
         
        <li> <img src="<?php bloginfo('template_directory'); ?>/images/key-distributor.png">  <h3>- IML Distributors</h3></li>
        </ul>
          
        </div>
      
      </div><!-- row -->
          
        
        </div><!-- pane -->


        <div class="pane pane-sales">
         <h2>Submit a sales enquiry</h2>

<?=do_shortcode('[si-contact-form form=\'1\']');?>

         
        </div><!-- pane -->
      </div> <!-- close panes -->
    </div><!-- products-tabs-wrapper -->
    


  </div> <!-- leftcol -->


<div class="sidebar-wrapper">
<?php
  get_sidebar('upcoming-events');
  get_sidebar('news');
?>
  </div> <!-- sidebar wrapper -->
</div>
<?php get_footer(); ?>
