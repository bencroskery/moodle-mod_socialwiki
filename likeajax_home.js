
// Let page load

// todo: how to make one such fn for each id in the table? selector of onclick function should be more general that a specific id.
//page is loaded after dom is ready. YUI table rendering must happen before this code is executed...
//$( window ).load(function()

        


$( document ).ready(function()
{
    $(".combiner").change(function(){
        $(this).parent().trigger( "refreshevent");
    });

    $(".asyncload").on("refreshevent", function(){
        //alert("refreshevent");

        $(this).children(":last").replaceWith('<img id ="waiting" src="img/160.GIF"/>');
        //$(this).append();
        var combining = false;
        var combineMethod = '';

        var combineoption = $(this).find("option:selected");
        if ( combineoption.length ){
            combineMethod=combineoption.text();
            combining=true;
        }
        
        
        var thediv= $(this); //TODO:fix
        var theurl = 'table/tableFactory.php?type='+$(this).attr('tabletype')
                    +"&userid="+userid
                    +"&swid="+swid
                    +"&cmid="+cmid
                    +"&courseid="+courseid ;
        if (combining){
            theurl = theurl + "&trustcombiner="+combineMethod
        }

        $.get(theurl, function(data) //gets table in html format pageid from pagelib.php: <script>var pageid =... </script>
            {
             var ajaxtable = $(data);
            
            //alert("ok0");
            thediv.children(":last").replaceWith(ajaxtable);
            
            //alert("ok1");
            
            if (thediv.find("thead").length==0){ //hide combineform if there's no table
                thediv.children(".combineform").hide(); //the combineform
            }

            thediv.children(":last").dataTable({
            "sScrollY": "200px",
            "bPaginate": false,
            "bScrollCollapse": true,
            });

             
            if (thediv.find("tr").size()<12){ //hide search thingy for tables with less than 12 rows
                thediv.find(".dataTables_filter").hide();
            }

            
            /*if ( oTable.length > 0 ) {
                oTable.fnAdjustColumnSizing();
            }*/
            //alert("ok3");
            //this.show?
            thediv.find(".socialwiki_unlikeimg").click(function()
                {   // pageid is encoded in the alt attribute
                var pageid = $(this).attr("alt").substring(10); //get the part of the id after "unlikeimg_"
                  // get url [, no data], success
                $.get("likeaj.php?pageid="+pageid , function(data) //gets pageid from pagelib.php: <script>var pageid =... </script>
                    {
            
                        $('.asyncload').trigger("refreshevent");
        
                    });
                });
            thediv.find(".socialwiki_likeimg").click(function()
                {
            // get url [, no data], success
                var pageid = $(this).attr("alt").substring(8); //get the part of the alt after "likeimg_"
            // get url [, no data], success
                $.get("likeaj.php?pageid="+pageid , function(data) //gets pageid from pagelib.php: <script>var pageid =... </script>
                {
                    $(".asyncload").trigger( "refreshevent");
                });
                });
            });


        
    });     //custom event

    $(".asyncload").trigger( "refreshevent");
    


    /*/render tables    
    $('.asyncload').dataTable({
        "sScrollY": "200px",
        "bPaginate": false,
        "bScrollCollapse": true,
        //"bProcessing": true,
        //"sAjaxSource": 'table/tableFactory.php?type='+$(this).attr('tabletype')+"&userid="+userid+"&swid"+swid //variables passed in <script>...</script> block
    });*/


    //$(".socialwiki_unlikeimg").click(function(){alert("click");});

    // every link of class "unlikelink"

});
