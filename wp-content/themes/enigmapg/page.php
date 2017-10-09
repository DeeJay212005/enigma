<?php

get_header(); 
header_block($post);

$post_id = $post->ID;

$page_links = get_field( "page_links",$post_id);
$accordian = get_field( "accordian", $post_id);
$compositions  = get_field("compositions",$post_id);
$show_team = get_field( "show_team", $post_id);

$team = get_field( "team", $post_id);

?>

<!-- MAIN CONTENT -->
<div class="container-fluid three-blocks-container">
   <div class="container container-full the-content-container">
      <?php the_content(); ?>
   </div>
</div>     

<?php

if ($accordian) {
   accord_displ($accordian);
} //end if
if($compositions) {

   $cnt = 0;
   $comp_displ .= "<div class='container-fluid'>";
   $comp_displ .= "   <div class='container accord-content-container'>";
   $comp_displ .= "      <button class='accordion' style='font-weight: 600;'>INVESTOR COMPOSITIONS</button>";
   $comp_displ .= "		 <div class='panel'>";


   foreach ($compositions as $composition) {
      $cnt++;

      $percent = "";
      $list_names = "";
      $colours = "";
      
      $comp_title = $composition['comp_title'];
      $comps = $composition['composition'];
      
      $comp_cnt = 0;
      
      foreach ($comps as $comp) {
         $comp_cnt ++;

         $percent .= "['".$comp['percent']."',   ".$comp['percent']."],";
         $list_names .= "<li>".$comp['list_name']."<b> (".$comp['percent']."%)</b></li>";
         
         if ($comp_cnt == 1) {$colours .= "'".$comp['colour']."'";} else {$colours .= " ,'".$comp['colour']."'";}
         
      } //end foreach


      $comp_displ .= "		    <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12' style='padding: 0'>";      
      $comp_displ .= "		       <div class='col-xs-12 col-sm-4 col-md-3 col-lg-3' style='padding: 0'>";
      $comp_displ .= "		          <div id=chart-".$cnt." style='min-width: 310px; height: auto; max-width: 600px; margin: 0 auto'></div>";
      $comp_displ .= "		       </div>";

      $comp_displ .= "		       <div class='col-xs-12 col-sm-8 col-md-9 col-lg-9'>";
      $comp_displ .= "		          <p style='color: #000000 !important;font-size: 14px;letter-spacing: 2px;font-weight: 600;'>".$comp_title."</p>";
      $comp_displ .= "		          <ul class='bpoint gold-circle' style='padding: 0;'>";
      $comp_displ .= "		          ".$list_names;
      $comp_displ .= "		          </ul>";
      $comp_displ .= "		       </div>";
      $comp_displ .= "		    </div>";
      
      
      
      
      $chart_js .= "   

         Highcharts.chart('chart-".$cnt."', {
            colors: [".$colours." ], 
            chart: {
                 plotBackgroundColor: null,
                 plotBorderWidth: 0,
                 plotShadow: false
             },
             title: {
                 text: '',
                 align: 'center',
                 verticalAlign: 'middle',
                 y: 40
             },
             tooltip: {
                 style: {display: 'none',}
             },
             plotOptions: {
                 pie: {
                     dataLabels: {
                         enabled: true,
                         distance: -35,
                         style: {
                             fontWeight: '600',
                             color: 'white',
                             fontSize: '13px',
                             textShadow: 'false',
                             textOutline: 'false',
                         }
                     },
                     startAngle: 0,
                     endAngle: 180,
                     center: ['10%', '50%'],
                 }
             },
             series: [{
                 type: 'pie',
                 name: '',
                 innerSize: '50%',
                 borderWidth: 0, // < set this option
                 data: [
                     ".$percent."
                     {
                         name: '',
                         y: 0.2,
                         dataLabels: {
                             enabled: false
                         }

                     }
                 ]
             }]
         });
         
         setTimeout(function() {
            var highchart = document.querySelector('#chart-".$cnt." svg:last-of-type');
            highchart.setAttribute('viewBox', '55 132 350 290');
         }, 10);"; 
      
   } //end foreach

   $comp_displ .= "      </div>";
   $comp_displ .= "   </div>";
   $comp_displ .= "</div>";
   
   echo $comp_displ;   
}


if($page_links) {
   if($post->post_parent == 34) {
      page_link_div($page_links, 3);
   } else {
      page_link_div($page_links, 2);
   }
}

if($show_team[0] == "Y") {
?>
<div class='container-fluid'>
   <div class='container'> 
      <div class='row'>     
         
         <?php
            echo team_display($team);
         ?>  

      </div>
   </div>
</div>	
<?php
} //end if team
?>

<script>
   <?php echo  $chart_js; ?>   
</script>

<?php
get_footer(); 
?>