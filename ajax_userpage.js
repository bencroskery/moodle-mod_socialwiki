
// Let page load

// todo: how to make one such fn for each id in the table? selector of onclick function should be more general that a specific id.
//page is loaded after dom is ready. YUI table rendering must happen before this code is executed...
//$( window ).load(function()

//combinemethod is an attribute of the enclosing div.




$( document ).ready(function()
{
    // add handler to change combine method
    $(".combiner").change(function(){
        $(this).parent().trigger( "refreshevent");
    });

    $(".asyncload").on("refreshevent", function(){
        //alert("refreshevent");

        $(this).children(":last").replaceWith('<img id ="waiting" src="img/160.GIF"/>');
        //$(this).append();
        
        var combineMethod = $(this).find("option:selected").text();
        var thediv= $(this); //TODO:fix

        $.get('table/tableFactory.php?type='+$(this).attr('tabletype')
                +"&userid="+userid
                +"&swid="+swid
                +"&targetuser="+targetuser
                +"&cmid="+cmid
                +"&courseid="+courseid 
                + "&trustcombiner="+combineMethod, function(data) //gets table in html format pageid from pagelib.php: <script>var pageid =... </script>
            {
              //  alert("got response"+data);
            //this.hide?
            var ajaxtable = $(data);
            
            //alert("ok0");
            thediv.children(":last").replaceWith(ajaxtable);
            
            //alert("ok1");
            

            thediv.children(":last").dataTable({
            "sScrollY": "200px",
            "bPaginate": false,
            "bScrollCollapse": true,
            });
            //alert("ok3");
            //this.show?
            $(".socialwiki_unlikeimg").click(function()
                {   // pageid is encoded in the alt attribute
                var pageid = $(this).attr("alt").substring(10); //get the part of the id after "unlikeimg_"
                  // get url [, no data], success
                $.get("likeaj.php?pageid="+pageid , function(data) //gets pageid from pagelib.php: <script>var pageid =... </script>
                    {
            
                        $('.asyncload').trigger("refreshevent");
        
                    });
                });
            $(".socialwiki_likeimg").click(function()
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

